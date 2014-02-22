<?php
	require_once("helper_functions.php");
	require_once("connect_db.php");

	$fbId = $_POST['fbId'];
	$fbAccessToken = $_POST['fbAccessToken'];
	
	$likes_FB_URL = getFbGraphURL($fbId, $fbAccessToken, "likes");
	$interests_FB_URL = getFbGraphURL($fbId, $fbAccessToken, "interests");
    $user_events_URL = getFbGraphURL($fbId, $fbAccessToken, "events");
    
    $user_FQL_URL_info = getFbFQLURL1($fbId, $fbAccessToken, "hometown_location,current_location,work,education", "user");
    $user_FQL_URL_groups = getFbFQLURL2($fbId, $fbAccessToken, "groups");
    $user_FQL_URL_events = getFbFQLURL2($fbId, $fbAccessToken, "events");
	
    $info1 = open_FB_URL($user_FQL_URL_info);
    $events_FQL = open_FB_URL($user_FQL_URL_events);
    $events = open_FB_URL_recursive($user_events_URL);
    
    $hometown_location = extractFromArray($info1, "0:hometown_location:city,state");
    $hometown_location = addCategoryAndDummyDate($hometown_location, "hometown_location");
    
    $current_location = extractFromArray($info1, "0:current_location:city,state");
    $current_location = addCategoryAndDummyDate($current_location, "current_location");
    
    
    $work = extractFromArray($info1, "0:work[]:employer:name");
    $work = addCategoryAndDummyDate($work, "work");
    
    $education = extractFromArray($info1, "0:education[]:school:name");
    $education = addCategoryAndDummyDate($education, "education");
    
    
    
    filterArrayByIndexValue($events, "rsvp_status", "unsure,attending");
    $events = array_merge($events, $events_FQL);
    filterArrayByIndex($events, "name,start_time->created_time");
    addNewIndexToArray($events, "category->events");
    
    $groups = open_FB_URL($user_FQL_URL_groups);
    filterArrayByIndex($groups, "name,update_time->created_time");
    foreach($groups as &$group) {
        $group['created_time'] = timetostr($group['created_time']);
    }
    addNewIndexToArray($groups, "category->groups");
    
    
    $userDataLikesInterests = array_merge(open_FB_URL($likes_FB_URL), open_FB_URL($interests_FB_URL));
    
    $userData = array();
    $userData['data']['likes interests'] = buildStructuredData($userDataLikesInterests, 0.97);
    $userData['data']['events groups'] = buildStructuredData(array_merge($events, $groups), 0.52);
    $userData['data']['work education'] = buildStructuredData(array_merge($work, $education), 0.68);
    $userData['data']['location'] = buildStructuredData(array_merge($hometown_location, $current_location), 0.43);
    
    
    $userData['properties']['reverseLookupCategoryHash'] = createReverseLookupCategoryHash();
    
    foreach ($userData['properties']['reverseLookupCategoryHash'] as $rev=>&$cat) {
        usort($cat, "catStrCmpRev");
    }
    
    //displayTmp($userData, "USER Data Summary");
    
    $categorySearchString = buildCategorySearchString($userData);
    $subcategorySearchString = buildSubcategorySearchString($userData);
    
    
    $query = 'SELECT * FROM products WHERE MATCH (name, description) against (\''.$subcategorySearchString.'\' IN BOOLEAN MODE) AND MATCH (category) against (\''.$categorySearchString.'\' IN BOOLEAN MODE)';
    echo $query, " \n\n";
    
    $res = mysql_query($query);
    
    function dbResultsToArray(&$dbResults) {
        $data = array();
        while ($row = mysql_fetch_assoc($dbResults)) {
            $data[] = 
                array(
                'name' => trim($row['name']),
                'description' => trim($row['description']),
                'id' => $row['id'],
                'price' => $row['price'],
                'category' => $row['category'],
                );
        }
        return $data;
    }
    
    $dbResults = dbResultsToArray($res);
    //print_r($dbResults);
    
    foreach ($dbResults as &$item) {
        $item['weight'] = assignWeightToResult($item, $userData);
    }
    
    usort($dbResults, "cmpWeightRev");
    
    echo "Results from DB sorted in decreasing order according to weight\n\n";
    print_r($dbResults);
    
    //displayTmp($userData, "USER Data Summary");
    
?>