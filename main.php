<?php

/**
 *
 * $status :
*    0=> 錯誤的格式查詢
*    1=> 查詢成功
*    2=> 查無資料
*    3=> 禁止查詢的章節
*    4=> 命令關鍵字
*    5=> 非文字型態的訊息
*    6=> bug錯誤
 */
header("Content-Type:text/html; charset=utf-8");
include ('vendor/autoload.php');
include ('channel_data.php');
require_once('./LINEBotTiny.php');
include "bibleAPI.php";
include 'bible_list_arr.php';

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
echo "lineeeeee";
exit;
//$even = $client->parseEvents();

foreach ($client->parseEvents() as $event) {
    $guestdata = getGuestInfo($channelAccessToken,$channelSecret,$event['source']['userId']);
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
                    if($message['text']=='三民聖教會'){
                        $client->replyMessage([
                            'replyToken' => $event['replyToken'],
                            'messages' => [
                                [
                                    "type"=>"location",
                                    "title"=>"灣告輝底家啦！！",
                                    "address"=>"813左營區重立路61號",
                                    "latitude"=>'22.673217',
                                    "longitude"=>'120.313176'
                                ]
                            ]
                        ]);
                        write_log($guestdata['displayName'],$event['source']['userId'],$message['text'],'4');
                        break;
                    }
                    $comman_key =array('?','這到底怎麼用啦','目錄','舊約','新約','我要抽');
                    if(in_array($message['text'],$comman_key)){
                        write_log($guestdata['displayName'],$event['source']['userId'],'comman-'.$message['text'],'4');
                        exit;
                    }
                    $status = 0;
                    $data['sec'] ='';
                    $data = cheack_arrange($message['text']);
                    if($data['error'] == '1'){
                        $client->reply_text($event['replyToken'],$data['msg']);
                        write_log($guestdata['displayName'],$event['source']['userId'],$message['text'],'0');
                        exit;
                    }
                    if($data['type'] == 'search' ){

                        $data['sec'] = isset($data['sec'])?$data['sec']:'';
                        $results = search($data['book'],$data['chap'],$data['sec'],$message['text']);

                        if($results['error']!='1' && $results['status']=='1'){
                            $text_arr = text_change_arr($results['data']);
                            $client->reply_text_arr($event['replyToken'],$text_arr);
                        }else if($results['error']!='1' && $results['status']=='2'){
                            $client->reply_text($event['replyToken'],$results['data']);
                        }

                    }else if($data['type'] == 'kw' ||$data['type'] == 'kwf'){
                        $results = search_keyword($data['kw'],$data['type']);
                        if($results['status']=='ok'){
                            $status ='1';
                        }else if($results['status']=='error'){
                            $status = '6';
                        }else{
                            $status = '1';
                        }
                            $client->reply_text($event['replyToken'],$results['msg']);
                    }else{
                        $text = '意料以外的錯誤，請麻煩通知開發人一下！'.emoji('10007D');
                        $client->reply_text($event['replyToken'],$text);
                        $status = '6';
                    }
                    write_log($guestdata['displayName'],$event['source']['userId'],$message['text'],$status);
                    break;
                default:
                    write_log($guestdata['displayName'],$event['source']['userId'],'Unsupported message type-'.$message['type'],'5');
                    error_log('Unsupported message type: ' . $message['type']);
                    break;
            }
            break;
        default:
            write_log($guestdata['displayName'],$event['source']['userId'],'Unsupported event type-'.$event['type'],'5');
            error_log('Unsupported event type: ' . $event['type']);
            break;
    }
};