<?php
include_once('../../vendor/autoload.php');
include_once('../lib/db_lib.php');
include_once('../lib/common.php');
$result = array(
    "error" => 1,
    "msg"   => '',
    "data"  =>'',
);
session_start();
$player_id = isset($_SESSION['player_id'])?$_SESSION['player_id']:'';

$db = new db_lib;
$action = isset($_GET['action'])?$_GET['action']:'';
switch ($action) {
    
    case 'feed_back':
        $name = isset($_POST['name'])?$_POST['name']:'';
        $message = isset($_POST['message'])?$_POST['message']:'';
        if($message==''){
            $result['msg'] = '請輸入訊息';
            goto end;
        }
        if($name==''){
            $result['msg'] = '請輸入稱呼';
            goto end;
        }
        $data = array(
            "name" => $name,
            "message" => $message,
            "create_time" => date("Y-m-d H:i:s"),
        );
        $insert_result = $db->insertData('conquer_bible_feed_back',$data);
        if($insert_result){
            $result['error'] = false;
        }
        else{
            $result['msg'] = '訊息發送失敗';
        }
        break;
    
    case 'read_book':
        $data = isset($_POST['data'])?$_POST['data']:'';
        $data_arr = explode('_',$data);
        $book_id = isset($data_arr[0])?$data_arr[0]:'';
        $chapter_no = isset($data_arr[1])?$data_arr[1]:'';
        if($book_id=='' ||$book_id==''){
            $result['msg'] = 'error_101';
            goto end;
        }
        $cond = array(
            "book_id"   => $book_id,
            "player_id" => $player_id,
            "chapter_no" => $chapter_no,
        );
        $ch_read = $db->getSingleByArray('conquer_bible_enter_msg_log',$cond);
        if(empty($ch_read)){
            $type = 'add';
        }else{
            $type = 'minus';
        }
        $data = array(
            "book_id"   => $book_id,
            "player_id" => $player_id,
            "chapter_no" => $chapter_no,
            "type"      => $type,
            "msg_log_id" => 0,
            "modify_time" => date("Y-m-d H:i:s"),
            "create_time" => date("Y-m-d H:i:s"),
        );
        $resule = $db->insertData('conquer_bible_read_record',$data);
        if($resule){
            if($type=='add'){
                $cond['create_time'] = date("Y-m-d H:i:s");
                $cond['read_record_id'] = $resule;
                $res = $db->insertData('conquer_bible_enter_msg_log',$cond);
            }else{
                $res = $db->deleteData('conquer_bible_enter_msg_log',$cond);
            }


            if($resule){
                $type_str = '撤退';
                if($type=="add"){
                    $type_str = '進攻';
                }

                $db->sortPlayerChapter($player_id);
                $new_player_info = $db->getPlayerInfo($player_id);
                $new_percent =isset($new_player_info['new_percent'])?$new_player_info['new_percent']:0;
                $old_percent =isset($new_player_info['old_percent'])?$new_player_info['old_percent']:0;
                $all_percen =isset($new_player_info['all_percen'])?$new_player_info['all_percen']:0;
                $start_date = isset($new_player_info['start_date'])?$new_player_info['start_date']:0;
                $return_data = array(
                    "type" => $type,
                    "old_percent" => $old_percent,
                    "new_percent" => $new_percent,
                    "all_percen" => $all_percen,
                );
                $result = array(
                    "error" => false,
                    "msg"   => $type_str.'成功',
                    "data"  => $return_data,
                );
            }else{
                $result['msg'] = 'error_102';
            }
        }else{
            $result['msg'] = 'error_103';
        }
        goto end;
        break;
}
end:
    echo json_encode($result);
?>