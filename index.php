<?php
    
require_once('TwitterAPIExchange.php');
require_once('textalytics/SMAClient.php');


/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
    'oauth_access_token' => "<YOUR_ACCESS_TOKEN_HERE>",
    'oauth_access_token_secret' => "<YOUR_ACCESS_TOKEN_SECRET_HERE>",
    'consumer_key' => "<CONSUMER_KEY_HERE>",
    'consumer_secret' => "<CONSUMER_SECRET_HERE>"
);

$db = new mysqli("<SERVER_HERE>", "<DB_USERNAME>", "<DB_PASSWORD_HERE>", "<DB_NAME_HERE>");

// Your specific requirements
$url = 'https://api.twitter.com/1.1/search/tweets.json';
$requestMethod = 'GET';
$hashtag = "#Codemotion";
$getfield = '?q='.$hashtag;

// Perform the request
$twitter = new TwitterAPIExchange($settings);
 $jsonTwitter = $twitter->setGetfield($getfield)
             ->buildOauth($url, $requestMethod)
             ->performRequest();
             

$json = json_decode($jsonTwitter);
$smaClient = new SMAClient(sentiment, <YOUR_TEXTALYTICS_API_KEY_HERE>);
   
   

 foreach($json->statuses as $info) {
    $fecha = strtotime($info->created_at);
    $user = $info->user->screen_name;
    $text = $db->real_escape_string($info->text);
    $img = $db->real_escape_string($info->user->profile_image_url);
    $location = $db->real_escape_string(utf8_decode($info->user->location));
    if(!empty($text)) {
        echo $text."<br />";
     $result = $smaClient->analyze($smaClient->createDocument($text, 'es', 'myID', 'TWITTER', '', 'txt'));
    
    $sentimiento = $result->getSentiment();

    $consulta = $db->query("SELECT id FROM tweets WHERE idtweet='".$info->id_str."'");
    if($consulta->num_rows==0) {
        $db->query("INSERT INTO tweets SET idtweet='".$info->id_str."', hashtag='".$hashtag."', fecha='".$fecha."', text='".$text."', user='".$user."', location='".$location."', img='".$img."', sentimiento='".$sentimiento."', caracteres='".strlen($text)."'");


    }
    }
 }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
    </head>
    <body>
        
    </body>
</html>
