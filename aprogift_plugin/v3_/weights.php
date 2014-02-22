<?php
/*
function getDateDiff($biggerDate, $smallerDate) {
    $d1 = strtotime($biggerDate);
    $d2 = strtotime($smallerDate);

    return $d1 - $d2;
}
*/

function getDateWeight($toTestTime, $minTime, $maxTime, $logOfRange/*for efficiency*/) {
    if ($toTestTime == 0)
        return 0.5;

    $totalTime = $maxTime - $minTime;
    $timeSinceMin = $toTestTime - $minTime;
    
    $val = log($timeSinceMin)/$logOfRange;
    return $val*$val*$val;
}

function assignCategoryWeights(&$userData) {
    $categories = array_keys($userData['data']);
    
    $maxCount = 0;
    foreach ($categories as $category) {
        $count = sizeof($userData['data'][$category]['data']);
        if ($count > $maxCount)
            $maxCount = $count;
    }
    
    $userData['properties']['categoryChildrenCardinalityMax'] = $maxCount;
    
    foreach ($categories as $category) {
        $userData['data'][$category]['properties']['weight'] = sizeof($userData['data'][$category]['data']) / $maxCount;
    }
    
    $userData['properties']['categoryWeight'] = 0.7;
}

function assignDateWeights(&$userData) {
    $categories = array_keys($userData['data']);

    //echo $userData['properties']['dateMin'], ' to ',$userData['properties']['dateMax'], "\n";
    
    $timePadSeconds = 3600;    
    $minTime = strtotime($userData['properties']['dateMin']) - 24*$timePadSeconds;
    $maxTime = strtotime($userData['properties']['dateMax']) + 1*$timePadSeconds;
    $logOfRange = log($maxTime - $minTime);
    
    foreach ($categories as $category) {
        $subcategories = array_keys($userData['data'][$category]['data']);
        
        foreach ($subcategories as $subcategory) {
            $userData['data'][$category]['data'][$subcategory] = getDateWeight(strtotime($userData['data'][$category]['data'][$subcategory]), $minTime, $maxTime, $logOfRange);
            //unset($userData['data'][$category]['data'][$i]['createdDate']);
        }
    }
    
    $userData['properties']['dateWeight'] = 0.3;
}

/*
function createSubcategoryToCategoryHash(&$userData) {
    $categories = array_keys($userData['data']);
    $subcategoryToCategoryHash = array();
    
    foreach ($categories as $category) {
        $subcategories = array_keys($userData['data'][$category]['data']);
        foreach ($subcategories as $subcategory) {
            if (!isset($subcategoryToCategoryHash[$subcategory])) {
                $subcategoryToCategoryHash[$subcategory] = $category;
            }
            else {
                // already set
                // compare weights and choose the bigger one
                $earlierItemCategory = $subcategoryToCategoryHash[$subcategory];
                if ($userData['data'][$category]['properties']['weight'] > $userData['data'][$earlierItemCategory]['properties']['weight']) {
                    $subcategoryToCategoryHash[$subcategory] = $category; 
                }
            }
        }
    }
    
    $userData['properties']['subcategoryToCategoryHash'] = $subcategoryToCategoryHash;
}
*/


function assignWeightToResult(&$item, &$userData) {
    $highCategories = array_keys($userData['data']);
    $itemName = trim(strtolower($item['name']));
    
    foreach ($userData['properties']['reverseLookupCategoryHash'][strtolower($item['category'])] as $category) {    
        foreach ($highCategories as $highCategory) {
            if (!isset($userData['data'][$highCategory]['data'][$category]))
                continue;
                
            foreach ($userData['data'][$highCategory]['data'][$category]['data'] as $name=>&$dateWeight) {
                if (matchLoose($itemName, $name) || matchLoose($item['description'], $name)) {
                    /*
                    echo "Match Success : $name\n";
                    print_r($item);                    
                    echo "\n";
                    */
                    //if ($itemName=='manchester united')
                    $weight  = $userData['data'][$highCategory]['data'][$category]['properties']['weight'] * $userData['data'][$highCategory]['properties']['categoryWeight'];
                    $weight += $dateWeight * $userData['data'][$highCategory]['properties']['dateWeight'];
                    $weight *= $userData['data'][$highCategory]['properties']['weight'];
                    return $weight;
                }
            }
        }
        
    }
    
    return 0;    
}

function matchLoose($str1, $str2) {
    /*if (stripos($str1, $str2)!== false || stripos($str2, $str1) !== false)
        return true;*/
    return isStringSubset($str1, $str2) || isStringSubset($str2, $str1);
}


function isStringSubset($str1, $str2) {
    $str1Arr = explode(' ', $str1);
    
    $loc = 0;
    foreach ($str1Arr as $word) {
        $loc = stripos($str2, $word, $loc);
        if ($loc === false) {
            return false;
        }
    }
    return true;
}

?>