<?php

require_once('excludeList.php');
require_once('weights.php');
require_once('synonyms.php');

function getFbGraphURL($fbId, $fbAccessToken, $category="likes") {
		return "https://graph.facebook.com/{$fbId}/{$category}?limit=5000&access_token={$fbAccessToken}";
}

function getFbFQLURL1($fbId, $fbAccessToken, $fields, $table) {
        return "https://graph.facebook.com/fql?q=SELECT+{$fields}+FROM+{$table}+WHERE+uid={$fbId}&access_token={$fbAccessToken}";
}

function getFbFQLURL2($fbId, $fbAccessToken, $category) {
    if ($category == "events")
        return "https://graph.facebook.com/fql?q=SELECT+name,start_time+FROM+event+WHERE+eid+IN(SELECT+eid+FROM+event_member+WHERE+uid={$fbId}+AND+rsvp_status+IN+('unsure','attending'))+ORDER+BY+start_time+asc&access_token={$fbAccessToken}";
    else
    if ($category == "groups")
        return "https://graph.facebook.com/fql?q=SELECT+name,venue,description,update_time+FROM+group+WHERE+gid+IN(SELECT+gid+FROM+group_member+WHERE+uid={$fbId})+ORDER+BY+update_time+desc&access_token={$fbAccessToken}";
}

function open_FB_URL($URL) {
	$data = json_decode(file_get_contents($URL), true);
	return (isset($data['data']) ? $data['data'] : null);
}

function open_FB_URL_recursive($URL) {
	$finalData = array();
    while (true) {
        $data = json_decode(file_get_contents($URL), true);
        if (isset($data['paging'])) {
            $finalData = array_merge($finalData, $data['data']);
            $URL = $data['paging']['next'];
        }
        else
            break;
    }
	return $finalData;
    //return (isset($data['data']) ? $data['data'] : null);
}

function extractFromArray(&$arr, $path) {
    $path = explode(":", $path);
    $tmpArr = array(&$arr);
    foreach ($path as  $el) {
        //print_r($tmpArr);
        $p = explode(",", $el);
        
        $tmpArr2 = array();
        foreach ($p as $p2) {
            $p1 = $p2;
            if (stripos($p1, "[]")) {
                $p1 = substr($p1, 0, stripos($p1, "[]"));
                foreach($tmpArr as &$tmp) {
                    foreach($tmp[$p1] as &$subArr) {
                        array_push($tmpArr2, $subArr);
                    }
                }
            }
            else {
                foreach ($tmpArr as &$tmp)
                    array_push($tmpArr2, $tmp[$p1]);
            }
        }
        $tmpArr = $tmpArr2;
        
    }
    return $tmpArr;
}

function filterArrayByIndexValue(&$arr, $index, $values) {
    $values = explode(",", $values);
    $c = count($arr);
    for ($i=0; $i<$c; $i++) {
        if (!in_array($arr[$i][$index], $values))
            unset($arr[$i]);
    }
}
function filterArrayByIndex(&$arr, $indices) {
    $indices = explode(",", $indices);
    $c = count($indices);
    
    for ($i=0; $i<$c; $i++) {
        $pair = explode("->", $indices[$i]);
        if (!isset($pair[1]))
            $pair[1] = $pair[0];
        $indices[$pair[0]] = $pair[1];
        unset($indices[$i]);
    }
    $indices_keys = array_keys($indices);
    
    $c = count($arr);
    for ($i=0; $i<$c; $i++) {
        $keys = array_keys($arr[$i]);
        foreach ($keys as $key) {
            if (in_array($key, $indices_keys)) {
                if ($key != $indices[$key]) {
                    $arr[$i][$indices[$key]] = $arr[$i][$key];
                    unset($arr[$i][$key]);
                }
            }
            else {
                unset($arr[$i][$key]);
            }
            
        }
    }
}

function addNewIndexToArray(&$arr, $index) {
    $index = explode("->", $index);
    $c = count($arr);
    for ($i=0; $i<$c; $i++)
        $arr[$i][$index[0]] = $index[1];
}


function timetostr($t) {
    return date('Y-m-d', $t);
}

function findWeight1($category) {
    global $userData;
    
    $highCategories = array_keys($userData['data']);
    
    foreach ($highCategories as $highCategory) {
        if (!isset($userData['data'][$highCategory]['data'][$category]))
            continue;
        return $userData['data'][$highCategory]['data'][$category]['properties']['weight'];
    }
    return 0;
}

function catStrCmpRev($cat1Str, $cat2Str) {
        global $userData;
        
        $cat1w = findWeight1($cat1Str);
        $cat2w = findWeight1($cat2Str);

        if ($cat1w < $cat2w)
            return 1;
        else if ($cat1w == $cat2w)
            return 0;
        else
            return -1;
}

function cmpWeightRev(&$item1, $item2) {
    $w1 = $item1['weight'];
    $w2 = $item2['weight'];
    
    if ($w1 < $w2)
        return 1;
    else if ($w1 == $w2)
        return 0;
    else return -1;
}


function revCmpCategory(&$cat1, &$cat2) {
    $cat1w = $cat1['properties']['weight'];
    $cat2w = $cat2['properties']['weight'];
    
    if ($cat1w < $cat2w)
        return 1;
    else if ($cat1w == $cat2w)
        return 0;
    else
        return -1;
}

function addCategoryAndDummyDate($arr, $category) {
    $result = array();
    $dummyDate = "2010-10-10";
    
    foreach($arr as $el) {
        $tmp = array();
        $tmp['name'] = $el;
        $tmp['category'] = $category;
        $tmp['created_time'] = $dummyDate;
        $result[] = $tmp;
    }
    
    return $result;
}


function buildStructuredData(&$rawData, $weight) {
    $finalOb = array('data'=>array(), 'properties'=>array());
    $minDate = '2070-00-00';
    $maxDate = '1995-00-00';
    
    $itemCount = 0;
    $categoryCount = 0;
    
	foreach($rawData as $element) {
        
        initializeExcludeListHash();
        
        $name = cleanExtraneous($element['name']);
		if ($name == '')
            continue;
		
        $category = strtolower($element['category']);
        $category = preg_replace('/[^a-zA-Z0-9]/', ' ', $category);
        
        if (!isset($finalOb['data'][$category])) {
			$finalOb['data'][$category] = array('data'=>array(), 'properties'=>array());
            $categoryCount++;
        }
        
        $createdDate = (isset($element['created_time'])) ? substr($element['created_time'], 0, 10) : 0;
        if ($createdDate > $maxDate)
            $maxDate = $createdDate;
		if ($createdDate < $minDate)
            $minDate = $createdDate;
        
		$finalOb['data'][$category]['data'][$name] = $createdDate;
        
        $itemCount++;
	}
	
    $finalOb['properties']['dateMin'] = $minDate;
    $finalOb['properties']['dateMax'] = $maxDate;
    $finalOb['properties']['totalItemCount'] = $itemCount;
    $finalOb['properties']['categoryCount'] = $categoryCount;
    
    $finalOb['properties']['weight'] = $weight;
    
    
    assignSynonyms($finalOb);
    assignCategoryWeights($finalOb);
    assignDateWeights($finalOb);
    //createSubcategoryToCategoryHash($finalOb);
    
    uasort($finalOb['data'], revCmpCategory);
    
	return $finalOb;
}


function buildCategorySearchString(&$userData) {
    $categories = array();
    $highCategories = array_keys($userData['data']);
    
    
    foreach ($highCategories as $highCategory) {
        $keys = array_keys($userData['data'][$highCategory]['data']);
        foreach ($keys as $key) {
            $categories[$key] = true;
            foreach ($userData['data']['likes interests']['data'][$key]['properties']['synonyms'] as $synonym)
                $categories[$synonym] = true;
        }
    }
    
    $searchString = '';
    foreach ($categories as $category=>$dummy) {
        $searchString .= '"'.$category.'" ';
    }
    
    return $searchString;
}


function buildSubcategorySearchString(&$userData) {
    $subcategories = array();
    
    $highCategories = array_keys($userData['data']);
    
    foreach ($highCategories as $highCategory) {
        $keys = array_keys($userData['data'][$highCategory]['data']);
        
        foreach ($keys as $key) {
            $subkeys = array_keys($userData['data'][$highCategory]['data'][$key]['data']);
            foreach ($subkeys as $subkey) {
                $subcategories[$subkey] = true;
            }
        }
    }
    
    $searchString = '';
    
    foreach ($subcategories as $subcategory=>$dummy) {
        $searchString .= '"'.$subcategory.'" ';
    }

    return $searchString;    
}



function displayTmp($data, $title) {
    $line_len = 110;
    $space = 4;
    $dat_len = strlen($title);
    
    for ($i=0; $i<$line_len; $i++)
        echo "_";
    echo "\n";
        
	for ($i=0; $i<($line_len-$dat_len-2*$space)/2; $i++)
        echo "_";
    for ($i=0; $i<$dat_len+2*$space; $i++)
        echo " ";
    for ($i=0; $i<($line_len-$dat_len-2*$space)/2; $i++)
        echo "_";
    echo "\n";

    for ($i=0; $i<($line_len-$dat_len-2*$space)/2; $i++)
        echo "_";
    for ($i=0; $i<$space; $i++)
        echo " ";
    echo $title;
    for ($i=0; $i<$space; $i++)
        echo " ";        
    for ($i=0; $i<($line_len-$dat_len-2*$space)/2; $i++)
        echo "_";
    echo "\n";
    
    for ($i=0; $i<$line_len; $i++)
        echo "_";
    echo "\n";
	
	print_r($data);
	echo "\n\n\n\n\n";
}
?>