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
    $player_info = $db->getPlayerInfo($user_info['id'],'user_id');
    if(count($player_info)==0){
        $add_plyer_result = $db->addPlyerUser($user_info['id']);
        if($add_plyer_result){
            $player_info = $db->getPlayerInfo($add_plyer_result);
        }
    }
    $BibleBook= $db->getBibleBook();
    $action = mb_substr($msg, 0,1);
    $new_msg = mb_substr($msg, 1);
    $player_id = isset($player_info['id'])?$player_info['id']:'';
    if($action=="+" || $action=="-"){
        $analy_result = analysis_str($new_msg);
        if($analy_result['error']==1){
            $result['msg'] = $analy_result['error_msg'];
            $status = 2;
            goto end;
        }
    }


    switch ($action) {
        case '+':
            $action_str = '攻略';
        case '-':
            $read_resule = $db->readBible($player_id,$action,$analy_result['data'],$msg_log_id);
            $chapter_str = implode(",",$analy_result['data']['chapter']);
            if($action=='-'){
                $action_str = '撤退';
            }
            $msg = '你已'.$action_str.'了'.$analy_result['data']['book'].$chapter_str."章";
            $db->sortPlayerChapter($player_id);
            $new_player_info = $db->getPlayerInfo($player_id);
            $new_percent =isset($new_player_info['new_percent'])?$new_player_info['new_percent']:0;
            $old_percent =isset($new_player_info['old_percent'])?$new_player_info['old_percent']:0;
            $all_percen =isset($new_player_info['all_percen'])?$new_player_info['all_percen']:0;
            $msg .= "\n\n當前進度\n舊約:".$old_percent."%\n新約:".$new_percent."%\n全部:".$all_percen
            //pr($new_player_info);
            $result = array(
                "error" => 0,
                "msg"   => $msg,
            );
            break;
        default:
            $result['msg'] = "不正確的動作";
            $status = 2;
            goto end;
            break;
    }
    goto end;

}

end:
    $client->reply_text($event['replyToken'],$result['msg']);
    $db->result_msg_log($msg_log_id,'1',json_encode($result));
    exit;
?>