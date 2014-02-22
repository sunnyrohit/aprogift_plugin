<?php

function getUrl($url) {
    global $USE_PROXY;
    global $PROXY;
    global $PROXYAUTH;
    $ch = curl_init ();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt ($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");  
    if ($USE_PROXY) {
        curl_setopt($ch, CURLOPT_PROXY, $PROXY);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $PROXYAUTH);        
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $returndata = curl_exec ($ch);
    return $returndata;
}

function CSRF() {
    return md5(uniqid(rand(), TRUE));
}

function FBAuthenticate($redirect_uri = "") {
    global $MY_URL;
    global $APP_ID;
    global $STATE;
    if ($redirect_uri == "") {
        $redirect_uri = $MY_URL;
    }
    $_SESSION[$STATE] = CSRF(); // CSRF protection
    $dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
        . $APP_ID . "&redirect_uri=" . urlencode($redirect_uri) . "&state="
        . $_SESSION[$STATE] . "&scope=user_interests,user_birthday,friends_likes,user_education_history,user_relationship_details,read_stream,user_likes,user_about_me";

    echo("<script> top.location.href='" . $dialog_url . "'</script>");
}

function getAccessToken($code) {
    global $MY_URL;
    global $APP_ID;
    global $APP_SECRET;
    $token_url = "https://graph.facebook.com/oauth/access_token?"
        . "client_id=" . $APP_ID . "&redirect_uri=" . urlencode($MY_URL)
        . "&client_secret=" . $APP_SECRET . "&code=" . $code;
    //$response = file_get_contents($token_url);
    $response = getUrl($token_url);
    $params = null;
    parse_str($response, $params); 
    $_SESSION['access_token'] = $params['access_token'];
    return $params['access_token'];
}

?>