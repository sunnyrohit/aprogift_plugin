<?php
function getFbLikesURL($fbId, $fbAccessToken) {
	return "https://graph.facebook.com/".$fbId."/likes?limit=5000&access_token=".$fbAccessToken;
}

function getFbInterestsURL($fbId, $fbAccessToken) {
	return "https://graph.facebook.com/".$fbId."/interests?limit=5000&access_token=".$fbAccessToken;
}

function open_FB_URL($URL) {
	$data = json_decode(file_get_contents($URL), true);
	return $data['data'];	
}

function combineAccordingToCategory($totalData) {
	$finalOb = array();
	
	foreach($totalData as $component) {
		$category = $component['category'];
		if (!isset($finalOb[$category]))
			$finalOb[$category] = array();
		
		$name = preg_replace('/[^a-zA-Z0-9]/', ' ', $component['name']);
		$name = preg_replace('/ +(?= )/', '', $name);
		
		$createdDate = (isset($component['created_time'])) ? substr($component['created_time'], 0, 10) : 0;
		
		$finalOb[$category][] = array("name"=>$name, "createdDate"=>$createdDate);	
	}
	
	return $finalOb;
}
?>