<?php
header("Content-Type:text/html; charset=utf-8");
include_once("conf/conf.php");
include_once('../vendor/autoload.php');
include_once("lib/common.php");
include_once("lib/db_lib.php");
include_once('../LINEBotTiny.php');
$channelAccessToken = 'W8zsv6O4gUUTZtfhuphcpDtrwim8YMgWu5vBXT0FQgAzJCzIe6IB6nELeSmDZxCQNTB8i3LCmgNYNCH78Em6RKyWJxNwJLZeJaeQ5yw838tp4vqbpl5e5QUJtUn7ZtcE75i9IHvq0XH+Him1EXJxfwdB04t89/1O/w1cDnyilFU=';
$channelSecret = '9f28c513a8553cca44af6e42637b9f58';
$client = new LINEBotTiny($channelAccessToken, $channelSecret);
$db_lib = new db_lib;
$msg = $client->parseEvents();
//pr($msg);
$data = array(
    "name_id" => "123456",
    "name" => "黃世X",
    "inster_msg" => "123123",
    "repont_json" => json_encode($msg),
    "create_time" => date("Y-m-d H:i:s"),
);

//$db_lib->db->debug = 1;
$db_lib->insertData('line_bible_log',$data);
//$db_lib->db->debug = 0;
// $db->record_msg_log($uuid,$msg);

foreach ($client->parseEvents() as $event) {

    $client->reply_text($event['replyToken'],"123456");
}
?>