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
$db = new db_lib;
$msg = $client->parseEvents();
pr($msg);
$data = array(
    "name_id" => "123456",
    "name" => "黃世X",
    "inster_msg" => "123123",
    "repont_json" => json_encode($msg),
    "create_time" => date("Y-m-d H:i:s"),
);
$db_lib->db->debug = 1;
$db_lib->insertData('line_bible_log',$data);
$db_lib->db->debug = 0;
// $db->record_msg_log($uuid,$msg);

foreach ($client->parseEvents() as $event) {

    //$client->reply_text($event['replyToken'],"123456");
}
?>