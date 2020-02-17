<?php
header("Content-Type:text/html; charset=utf-8");
include ('../vendor/autoload.php');
include ('../../vendor/autoload.php');
include ('../LINEBotTiny.php');
$channelAccessToken = '1613644531';
$channelSecret = '9f28c513a8553cca44af6e42637b9f58';
$client = new LINEBotTiny($channelAccessToken, $channelSecret);
echo "12345";
foreach ($client->parseEvents() as $event) {
    $client->reply_text($event['replyToken'],$event['message']);
}
?>