<?php
header("Content-Type:text/html; charset=utf-8");
include_once("conf/conf.php");
include_once('../vendor/autoload.php');
include_once("lib/common.php");
include_once("lib/db_lib.php");
include_once('../lib/LINEBotTiny.php');
$client = new LINEBotTiny($channelAccessToken, $channelSecret);
$db = new db_lib;
$result = array(
    "error" => 1,
    "msg"   => '',
);
$status = 0;
//$db->record_msg_log("123123",file_get_contents('php://input'));
foreach ($client->parseEvents() as $event) {
    $uuid = $event['source']['userId'];
    $msg = $event['message']['text'];

    $msg_log_id = $db->record_msg_log($uuid,file_get_contents('php://input'));
    $user_info = $db->getUserInfo($uuid,'uuid');
    if(count($user_info)==0){
        $line_user_result = $db->addLineUser($uuid);
        if($line_user_result){
            $user_info = $db->getUserInfo($line_user_result,'uuid');
        }
    }
    $player_info = $db->getPlayerInfo($user_info['id']);
    if(count($player_info)==0){
        $add_plyer_result = $db->addPlyerUser($user_info['id']);
        if($add_plyer_result){
            $player_info = $db->getPlayerInfo($add_plyer_result);
        }
    }
    pr($event);
    $BibleBook= $db->getBibleBook();
    $action = substr($msg, 0,1);
    $new_msg = substr($msg, 1);
    $player_id = isset($player_info['id'])?$player_info['id']:'';
    $analy_result = analysis_str($new_msg);
    $client->reply_text($event['replyToken'],json_encode($analy_result));
    if($analy_result['error']==1){
        $result['msg'] = $analy_result['error_msg'];
        $status = 2;
        goto end;
    }


    $client->reply_text($event['replyToken'],json_encode($result));
}

end:
    $db->result_msg_log($msg_log_id,'1',json_encode($result));
    exit;
?>