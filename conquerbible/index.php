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
session_start();
//$db->record_msg_log("123123",file_get_contents('php://input'));
foreach ($client->parseEvents() as $event) {
    $uuid = $event['source']['userId'];
    $msg = $event['message']['text'];
    $msg = trim($msg);
    $msg = convertStrType($msg);
    if(in_array($msg,$default_arr)){
        exit;
    }
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



    switch ($action) {
        case '+':
            $action_str = '攻略';
        case '-':
            $analy_result = analysis_read__str($new_msg);
            if($analy_result['error']==1){
                $result['msg'] = $analy_result['error_msg'];
                $status = 2;
                goto end;
            }
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
            $start_date = isset($new_player_info['start_date'])?$new_player_info['start_date']:0;
            $startdate=strtotime($start_date);
            $enddate=strtotime(date("Y-m-d"));
            $days=ceil(abs($startdate - $enddate)/86400);
            $days_p = $days/365*100;
            $days_p = round($days_p, 1);


            $msg .= "\n\n---攻略進度---\n舊約:".$old_percent."%\n新約:".$new_percent."%\n白波:".$all_percen."%\n\n現在至少要讀白波的".$days_p."%";
            //pr($new_player_info);
            $result = array(
                "error" => 0,
                "msg"   => $msg,
            );
            break;
        case '/':
            if($new_msg=='myinfo'){
                $new_player_info = $db->getPlayerInfo($player_id);
                $start_date =isset($new_player_info['start_date'])?$new_player_info['start_date']:'';
                $new_percent =isset($new_player_info['new_percent'])?$new_player_info['new_percent']:0;
                $old_percent =isset($new_player_info['old_percent'])?$new_player_info['old_percent']:0;
                $all_percen = isset($new_player_info['all_percen'])?$new_player_info['all_percen']:0;
                $done_count = isset($new_player_info['done_count'])?$new_player_info['done_count']:0;

                $startdate=strtotime($start_date);
                $enddate=strtotime(date("Y-m-d"));
                $days=ceil(abs($startdate - $enddate)/86400);
                $days_p = $days/365*100;
                $days_p = round($days_p, 1);

                $msg = "開始日期：$start_date\n\n攻略進度\n  舊約：".$old_percent."%\n  新約：".$new_percent."%\n  整本：".$all_percen."%\n\n現在至少要讀白波的".$days_p."%\n\n完整攻略次數：$done_count";
            }else if($new_msg=='mypage'){
                $player_id_str = getEncodeStr($player_id);
                $msg = "http://bibleline2.herokuapp.com/conquerbible/web/?player_id=$player_id_str";
            }
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