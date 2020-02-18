<?php
header("Content-Type:text/html; charset=utf-8");
include_once("conf/conf.php");
include_once('../vendor/autoload.php');
include_once("lib/common.php");
include_once("lib/db_lib.php");
include_once('../lib/LINEBotTiny.php');
// pr($channelAccessToken);
// pr($channelSecret);
$channelAccessToken ='W8zsv6O4gUUTZtfhuphcpDtrwim8YMgWu5vBXT0FQgAzJCzIe6IB6nELeSmDZxCQNTB8i3LCmgNYNCH78Em6RKyWJxNwJLZeJaeQ5yw838tp4vqbpl5e5QUJtUn7ZtcE75i9IHvq0XH+Him1EXJxfwdB04t89/1O/w1cDnyilFU=';
$channelSecret = '9f28c513a8553cca44af6e42637b9f58';
$client = new LINEBotTiny($channelAccessToken, $channelSecret);
$db = new db_lib;


foreach ($client->parseEvents() as $event) {
    $uuid = $event['source']['userId'];
    $msg = $event['message'];
    //$guestdata = getGuestInfo($channelAccessToken,$channelSecret,$user_id);
    $db->record_msg_log($uuid,$msg);

    // $user_info = $db->getUserInfo($uuid,'uuid');
    // if(count($user_info)==0){
    //     $line_user_result = $db->addLineUser('U7024af33ac34455f97b39b7bee8b8436');
    //     if($line_user_result){
    //         $user_info = $db->getUserInfo($line_user_result,'uuid');
    //     }
    // }

    $client->reply_text($event['replyToken'],"123213");
}
?>