<?php
header("Content-Type:text/html; charset=utf-8");
include ('../vendor/autoload.php');
include_once("conf/conf.php");
include ('../../vendor/autoload.php');
include_once("lib/common.php");
include_once("lib/db_lib.php");
include ('../LINEBotTiny.php');
$channelAccessToken = '1613644531';
$channelSecret = '9f28c513a8553cca44af6e42637b9f58';
$client = new LINEBotTiny($channelAccessToken, $channelSecret);

// $db->record_msg_log($uuid,$msg);

foreach ($client->parseEvents() as $event) {

    $client->reply_text($event['replyToken'],"123456");
}
?>