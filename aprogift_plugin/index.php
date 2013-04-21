<?php

include('config.php');
include('utils.php');

 callFacebookOauth();

 function callFacebookOauth(){

   $APP_ID = "544973535543605";
   $APP_SECRET = "c6235827c4cfe0b14eedf5723e1b5551";
   $MY_URL = "http://localhost/widget/index.php";
   session_start();

   if(!isset($_REQUEST["code"])) {
     FBAuthenticate();
   }
   
   if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {

     $access_token = getAccessToken($_REQUEST["code"]);
     $graph_url = "https://graph.facebook.com/me?access_token=" . $access_token;
     
     $user = json_decode(getUrl($graph_url));

     $name = $user->name;
     $id = $user->id;
     $facebookLink = $user->link;
     $firstName = $user->first_name;
     $lastName = $user->last_name;
     $userName = $user->username;
     $gender = $user->gender;
     $ageRange = $user->age_range;
     $biography = $user->bio;
     $birthday = $user->birthday;
     $languages = $user->languages;
     $education = $user->education;
     $interestedIn = $user->interested_in;
     $number_array = array("id","0","1","2","3","4","5","6","7","8","9");

     $graphforlike_url = "https://graph.facebook.com/".$id."/likes?access_token=" . $access_token;
     $user_likes = json_decode(getUrl($graphforlike_url));

     $facebookDetailsArray = array( "Full Name: "=>$name,"User Id: "=>$id,"Facebook Link: "=>$facebookLink,"First Name: "=>$firstName,"Last Name: "=>$lastName,"User Name: "=>$userName,"Gender: "=>$gender,"Biography: "=>$biography );
  
     foreach($facebookDetailsArray as $key=>$value){
       echo "<strong>".$key."</strong> -> ".$value."<br/>";
     }

     echo '<strong>Languages you speak: </strong><br>';
      foreach($languages as $language){
       foreach($language as $key => $value){
         echo "<strong>".$key."</strong> - ".$value."<br/>";
       }       
     }  
 
     echo '<hr>';
     echo "<strong>Your education: </strong><br/>";
 
     function looper($input) {
       foreach ($input as $key => $val) {
         if (is_array($val) || is_object($val)) {
           looper($val);
         } else{
           printf("<strong>%s</strong> -> %s<br>",$key,$val);
         }
       }
     }     

     looper($education);


     echo '<hr>';
     echo "<strong>Your likes: </strong><br/>";
     foreach($user_likes->data as $like){
       echo $like->name." <br/>";         
     }     
}

 else {
     echo"There is some problem with the session_data, please reload (Ctrl + R)it again.";
   }

}   

?>

<?
function displayForm(){
?> 
<!Doctype html>
<head>
<style>

*{
box-sizing: border-box;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
}

.FacebookButton{
width:150px;
height:22px;
background: url('images/facebook_signin.png') no-repeat 0px 0px;
}
.FacebookButton:hover, .FacebookButton:focus{
cursor:pointer;
background-position: 0px -24px;
}
.FacebookButton:active{
background-position: 0px -48px;
}
</style>
</head>
<body> 
 
<a href='index.php?oauth=facebook'><div class="FacebookButton"></div></a>
  
<?
}

?>



</body>
</html>