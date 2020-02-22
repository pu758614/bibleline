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
$user_id = isset($_SESSION['player_id'])?$_SESSION['player_id']:'';
pr($_SESSION);
$db = new db_lib;
$action = isset($_GET['action'])?$_GET['action']:'';
switch ($action) {
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
            "player_id" => $user_id,
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
            "player_id" => $user_id,
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
                $result = array(
                    "error" => false,
                    "msg"   => $type_str.'成功',
                    "data"  =>$type,
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