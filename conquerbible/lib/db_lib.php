<?php
include_once(dirname(__DIR__)."/../vendor/autoload.php");
include_once(dirname(__DIR__)."/lib/common.php");
include_once(dirname(__DIR__)."/lib/db_crud.php");
include_once(dirname(__DIR__)."/conf/conf.php");
class db_lib {
    use DB_CRUD\DB_CRUD;
    function __construct(){
        date_default_timezone_set('asia/taipei');
        header("Content-type: text/html; charset=utf-8");
        $host = 'us-cdbr-iron-east-04.cleardb.net';
        $user = 'b65f080869b290';
        $passwd = 'afa6a322';
        $database = 'heroku_4d9bdcbc4d69fab';
        $this->db = ADONewConnection('mysqli');
        $this->db->setCharset('utf8');
        $this->db->Connect($host,$user,$passwd,$database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    //記錄所有LINE進來的訊息
    function record_msg_log($uuid,$msg,$memo=''){
        $data = array(
            "user_uuid" => $uuid,
            "msg"       => $msg,
            "memo"      => $memo,
            "modify_time" =>date("Y:m:d H:i:s"),
            "create_time" =>date("Y:m:d H:i:s"),
        );
        $result = $this->insertData("line_msg_log",$data);
        return $result;
    }

    function result_msg_log($id,$status,$memo){
        $data = array(
            "status" => $status,
            "memo" => $memo,
            "modify_time" =>date("Y:m:d H:i:s"),
        );
        $cond= array("id"=>$id);
        $this->updateData("line_msg_log",$data,$cond);
    }

    //取得LINE使用者訊息
    function getUserInfo($id,$type='id'){
        if($type=='uuid'){
            $field = 'uuid';
        }else{
            $field = 'id';
        }
        $result = $this->getSingleById('line_user',$field,$id);
        return $result;
    }
    //新增LINE使用者資訊
    function addLineUser($user_uuid){
        global $channelSecret,$channelAccessToken;
        $result = 0;
        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($channelAccessToken);
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
        $response = $bot->getProfile($user_uuid);
        $profile = array();
        if ($response->isSucceeded()) {
            $profile = $response->getJSONDecodedBody();
        }

        // $profile = array(
        //     "userId" => 'U7024af33ac34455f97b39b7bee8b8436',
        //     "displayName" => '黃世勳',
        //     "pictureUrl"  => 'https://profile.line-scdn.net/0hFPoPz-vuGX9SKTPiIsxmKG5sFxIlBx83KhxSHid7RRh8SQ4oaBsDSX4qRht_S1kuPEtfGyUoQkl6',
        //     "statusMessage" => '(emoji)(emoji)(emoji)(emoji)(emoji)(emoji)(emoji)(emoji)(emoji)(emoji)(emoji)(emoji)(emoji)(emoji)(emoji)(emoji)(emoji)(emoji)(emoji)(emoji),)'
        // );
        if(is_array($profile)&&count($profile)>0){
            $data = array(
                "uuid" => isset($profile['userId'])?$profile['userId']:'',
                "name" => isset($profile['displayName'])?$profile['displayName']:'',
                "modify_time" =>date("Y:m:d H:i:s"),
                "create_time" =>date("Y:m:d H:i:s"),
            );
            $result = $this->insertData('line_user',$data);
        }
        return $result;
    }
    //取得讀經使用者資訊
    function getPlayerInfo($id,$key = 'id'){
        $PlayerInfo = $this->getSingleById("conquer_bible_player",$key,$id);
        return $PlayerInfo;
    }
    //新增讀經使用者資訊
    function addPlyerUser($user_id){
        $data = array(
            "user_id" => $user_id,
            "start_date" => date("Y-m:d"),
            "modify_time"  => date("Y-m-d H:i:s"),
            "create_time"  => date("Y-m-d H:i:s"),
        );
        $result = $this->insertData("conquer_bible_player",$data);
        return $result;
    }

    //取得書捲資料
    function getBibleBook($book_arr = array()){
        $result = $result_arr = array();
        if(count($book_arr)>0){
            $cond_book = array();
            foreach ($book_arr as $book) {
                $cond_book[] = $book;
            }
            $cond = array(
                "in" => array("name"=>$cond_book),
            );
            $result_arr = $this->getArrayByArray('conquer_bible_book',$cond);
            foreach ($result_arr as $data) {
                $result[$data['name']] = $data['chapter_count'];
            }
        }else{
            $cond = array();
            $result_arr = $this->getArrayByArray('conquer_bible_book',$cond);
        }
        foreach ($result_arr as $data) {
            $result[$data['name']]['count'] = $data['chapter_count'];
            $result[$data['name']]['id']    = $data['id'];
            $result[$data['name']]['testament']    = $data['testament'];
        }
        return $result;
    }

    //新增/減少讀經進度
    function readBible($player_id,$action,$data,$msg_log_id){
        global $BibleBook;
        $book = isset($data['book'])?$data['book']:'';
        $book_id = isset($BibleBook[$book])?$BibleBook[$book]['id']:'';
        $result = false;
        //echo $book;
        $chapter_arr = isset($data['chapter'])?$data['chapter']:array();
        if($action=='+'){
            $type = "add";
        }else{
            $type = "minus";
        }
        foreach ($chapter_arr as  $chapter) {
            $record_data = array(
                "player_id" => $player_id,
                "book_id"   => $book_id,
                "chapter_no" => $chapter,
                "type"      => $type,
                "msg_log_id" => $msg_log_id,
                "create_time" =>date("Y:m:d H:i:s"),
            );
            $record_result = $this->insertData("conquer_bible_read_record",$record_data);
            if($record_result){
                if($type=='add'){
                    $add_data = array(
                        "player_id" => $player_id,
                        "book_id"   => $book_id,
                        "chapter_no" => $chapter,
                        "read_record_id" => $record_result,
                        "create_time" =>date("Y:m:d H:i:s"),
                    );
                    $result = $this->insertData("conquer_bible_enter_msg_log",$add_data);
                }else {
                    $minus_cond = array(
                        "player_id" => $player_id,
                        "book_id"   => $book_id,
                        "chapter_no" => $chapter,
                    );
                    $result = $this->deleteData("conquer_bible_enter_msg_log",$minus_cond);
                }
            }
        }
        return $result;
    }

    function getReadDate($user_id){
        $cond = array("player_id" => $user_id);
        $read_data = $this->getArrayByArray('conquer_bible_enter_msg_log',$cond);

        $sort_data = array();
        foreach ($read_data as  $data) {
            $time = isset($data['create_time'])?$data['create_time']:'';
            $time_arr = explode(' ',$time);
            $date = $time_arr[0];
            $sort_data[$data['book_id']."_".$data['chapter_no']] = $date;
        }
        return $sort_data;
    }

    //整理個人進度率
    function sortPlayerChapter($player_id){
        $old_count = $new_count = 0;
        $sql ="SELECT COUNT(*)
               FROM conquer_bible_enter_msg_log
               JOIN conquer_bible_book
               ON   conquer_bible_enter_msg_log.book_id=conquer_bible_book.id
               WHERE player_id=? AND conquer_bible_book.testament = 1;
               ";
        $result = $this->db->Execute($sql,array($player_id));
        if($result && $result->RecordCount() > 0){
            $data =  $result->FetchRow();
            $new_count = $data['COUNT(*)'];
        }
        $sql ="SELECT COUNT(*)
              FROM conquer_bible_enter_msg_log
              JOIN conquer_bible_book
              ON   conquer_bible_enter_msg_log.book_id=conquer_bible_book.id
              WHERE player_id=? AND conquer_bible_book.testament = 0;
              ";
        $result = $this->db->Execute($sql,array($player_id));
        if($result && $result->RecordCount() > 0){
            $data =  $result->FetchRow();
            $old_count = $data['COUNT(*)'];
        }
        $new_p = $new_count/260*100;
        $new_p = round($new_p, 1);
        $old_p = $old_count/929*100;
        $old_p = round($old_p, 1);
        $total_p = ($new_count+$old_count)/1189*100;
        $total_p = round($total_p, 1);
        $data = array(
            "new_percent" => $new_p,
            "old_percent" => $old_p,
            "all_percen"  => $total_p,
            "modify_time" =>date("Y:m:d H:i:s"),
        );

        $this->updateData('conquer_bible_player',$data,array("id"=>$player_id));
    }

    function PlayerTotalReadCount($player_id){
        $sql = "SELECT count(*) 
                FROM  conquer_bible_enter_msg_log
                WHERE player_id= ?";
        $result = $this->db->Execute($sql,array($player_id)); 
        $count = 0; 
        if($result && $result->RecordCount() > 0){
            $data =  $result->FetchRow();
            $count = isset($data['count(*)'])?$data['count(*)']:0;
        }  
        return $count;
    }

    function reReadSet($player_id){
        $result = $this->deleteData('conquer_bible_enter_msg_log',array("player_id"=>$player_id));
        return $result;
    }

    function addDoneCount($player_id){
        $player_info = $this->getSingleById('conquer_bible_player','id',$player_id);
        $done_count = isset($player_info['done_count'])?$player_info['done_count']:0;
        $done_count = $done_count+1;
        $data = array(
            'done_count'  =>$done_count,
            "start_date" => date("Y-m-d"),
            "new_percent" => 0,
            "old_percent" => 0,
            "all_percen" => 0,
            "modify_time" => date("Y:m:d H:i:s"),
        );
        $cond = array("id"=>$player_id);
        $up_result = $this->updateData('conquer_bible_player',$data,$cond);
        return $done_count;
    }
}
 ?>