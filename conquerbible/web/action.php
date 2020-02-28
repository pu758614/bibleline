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

    case 'save_user_set':
        $year = isset($_POST['year'])?$_POST['year']:'';
        $month = isset($_POST['month'])?$_POST['month']:'';
        $done_month_count = $year*12+(int)$month;
        $start_date = isset($_POST['start_date'])?$_POST['start_date']:'';
        $start_date = str_replace('/','-',$start_date);
        $data = array(
            "start_date" => $start_date,
            "done_month_count" => $done_month_count,
            "modify_time" =>  date("Y:m:d H:i:s"),
        );
        $insert_result = $db->updateData('conquer_bible_player',$data,array("id"=>$player_id));
        if($insert_result){
            $result['error'] = false;
        }
        else{
            $result['msg'] = '儲存失敗';
        }
        break;

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
            "name" => htmlspecialchars($name),
            "message" => htmlspecialchars($message),
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
            "create_time" => date("Y-m-d H:i:s"),
        );
        $is_done = false;
        $resule = $db->insertData('conquer_bible_read_record',$data);
        if($resule){
            if($type=='add'){
                $cond['create_time'] = date("Y-m-d H:i:s");
                $cond['read_record_id'] = $resule;
                $res = $db->insertData('conquer_bible_enter_msg_log',$cond);
            }else{
                $res = $db->deleteData('conquer_bible_enter_msg_log',$cond);
            }
            if($type=='add'){
                $count = $db->PlayerTotalReadCount($player_id);
                if($boot_total_count==$count){
                    $is_done = true;
                }
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
                    "is_done"       => $is_done,
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
    case 're_read_book':
        $read_count = $db->PlayerTotalReadCount($player_id);
        if($read_count!=$boot_total_count){
            $result['msg'] = '你還沒讀完！';
            goto end;
        }
        $re_resule = $db->reReadSet($player_id);
        if($re_resule){
            $add_resut = $db->addDoneCount($player_id);
            if($add_resut){
                $data = array(
                    "book_id"   => 0,
                    "player_id" => $player_id,
                    "chapter_no" => $add_resut,
                    "type"      => 'reset',
                    "msg_log_id" => 0,
                    "create_time" => date("Y-m-d H:i:s"),
                );
                $db->insertData('conquer_bible_read_record',$data);
                $result['date'] = $add_resut;
                $result['error'] = false;
            }else{
                $result['msg'] = '紀錄清除成功，完成次數更新失敗。';
            }
        }else{
            $result['msg'] = '紀錄清除失敗。';
        }
        break;
}
end:
    echo json_encode($result);
?>