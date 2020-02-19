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
$book_arr = array(
    '創世記','出埃及記','利未記','民數記','申命記','約書亞記','士師記','路得記',
                  '撒母耳記上','撒母耳記下','列王紀上','列王紀下','歷代志上','歷代志下','以斯拉記',
                  '尼希米記','以斯帖記','約伯記','詩篇','箴言','傳道書','雅歌','以賽亞書','耶利米書',
                  '耶利米哀歌','以西結書','但以理書','何西阿書','約珥書','阿摩司書','俄巴底亞書','約拿書',
                  '彌迦書','那鴻書','哈巴谷書','西番雅書','哈該書','撒迦利亞書','瑪拉基書','馬太福音',
                  '馬可福音','路加福音','約翰福音','使徒行傳','羅馬書','哥林多前書','哥林多後書',
                  '加拉太書','以弗所書','腓立比書','歌羅西書','帖撒羅尼迦前書','帖撒羅尼迦後書',
                  '提摩太前書','提摩太後書','提多書','腓利門書','希伯來書','雅各書','彼得前書',
                  '彼得後書','約翰一書','約翰二書','約翰三書','猶大書','啟示錄'
);

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
    $BibleBook= $db->getBibleBook();
    $action = mb_substr($msg, 0,1);
    $new_msg = mb_substr($msg, 1);
    $player_id = isset($player_info['id'])?$player_info['id']:'';
    $analy_result = analysis_str($new_msg);
    if($analy_result['error']==1){
        $result['msg'] = $analy_result['error_msg'];
        $status = 2;
        goto end;
    }

    switch ($action) {
        case '+':
            $action_str = '閱讀';
        case '-':
            $read_resule = $db->readBible($player_id,$action,$analy_result['data']);
            $chapter_str = implode(",",$analy_result['data']['chapter']);
            if($action=='-'){
                $action_str = '移除';
            }
            $result = array(
                "error" => 0,
                "msg"   => '已'.$action_str.'了'.$analy_result['data']['book'].$chapter_str."章",
            );
            pr($result);
            break;
        default:
            // code...
            break;
    }
    goto end;

}

end:
    $client->reply_text($event['replyToken'],$result['msg']);
    $db->result_msg_log($msg_log_id,'1',json_encode($result));
    exit;
?>