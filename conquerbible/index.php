<?php
header("Content-Type:text/html; charset=utf-8");
include_once("conf/conf.php");
include_once('../vendor/autoload.php');
include_once("lib/common.php");
include_once("lib/db_lib.php");
include_once('../lib/LINEBotTiny.php');
$client = new LINEBotTiny($channelAccessToken, $channelSecret);
$db = new db_lib;

//$db->record_msg_log("123123",file_get_contents('php://input'));
foreach ($client->parseEvents() as $event) {
    $uuid = $event['source']['userId'];
    $msg = $event['message'];
    pr($event);
    //$guestdata = getGuestInfo($channelAccessToken,$channelSecret,$user_id);
    $db->record_msg_log($uuid,json_encode($msg));
    //$db->db->debug = 1;
    $user_info = $db->getUserInfo($uuid,'uuid');
    if(count($user_info)==0){
        $line_user_result = $db->addLineUser($uuid);
        if($line_user_result){
            $user_info = $db->getUserInfo($line_user_result,'uuid');
        }
    }
    //$db->db->debug = 0;
    $client->reply_text($event['replyToken'],$msg['text']);
}
?>