<?php
header("Content-Type:text/html; charset=utf-8");
include ('../vendor/autoload.php');
include ('../../vendor/autoload.php');
include ('../LINEBotTiny.php');

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
foreach ($client->parseEvents() as $event) {
    $client->reply_text($event['replyToken'],$event['message']);
    
}
?>