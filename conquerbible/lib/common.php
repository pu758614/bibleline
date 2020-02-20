<?php

header("Content-Type:text/html; charset=utf-8");
function analysis_read__str($msg){
    global $book_arr,$abbre_chang,$BibleBook;
    $retuen = array(
        "error" => 1,
        "error_msg" => '',
        "data" => array(),
    );

    $msg_arr = preg_split("/([a-zA-Z0-9]+)/", $msg, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    if(count($msg_arr)>0){
        $sort = array();
        $book = isset($msg_arr[0])?trim($msg_arr[0]):'';
        $start = isset($msg_arr[1])?trim($msg_arr[1]):'';
        $type= isset($msg_arr[2])?trim($msg_arr[2]):'';
        $chapter = array();
        if(isset($BibleBook[$book]) || isset($abbre_chang[$book])){
            if(isset($abbre_chang[$book])){
                $book = $abbre_chang[$book];
            }
            $max_chapter = $BibleBook[$book]['count'];
            $sort['book'] = $book;
            if($type=='~' || $type=='～' || $type=='-'|| $type=='－'){
                $end = isset($msg_arr[3])?trim($msg_arr[3]):'';
                if(is_numeric($start) && is_numeric($end)){
                    if($start<=0 || $end<=0 || $start>$max_chapter|| $end>$max_chapter){
                        $retuen['error_msg'] = '章節輸入錯誤';
                    }else{
                        for ($i=$start; $i <=$end ; $i++) {
                            $chapter[] = $i;
                        }
                        $sort['chapter'] = $chapter;
                        $retuen['error'] = 0;
                        $retuen['data'] = $sort;
                    }
                }else{
                    $retuen['error_msg'] = '格式輸入錯誤';
                }
            }else if($type=='.'||$type=='、'||$type==',' || ($type==''&&count($msg_arr)==2)){
                $is_fail = 0;
                foreach ($msg_arr as $msg_data) {
                    if(is_numeric($msg_data) && $msg_data>0 && $msg_data<$max_chapter){
                        $chapter[] = $msg_data;
                    }
                }
                if(count($chapter)>0){
                    $sort['chapter'] = $chapter;
                    $retuen['error'] = 0;
                    $retuen['data'] = $sort;
                }else{
                    $retuen['error_msg'] = '格式輸入錯誤';
                }
            }else{
                $retuen['error_msg'] = '格式輸入錯誤';
            }
        }else{
            $retuen['error_msg'] = '取得張卷資料失敗';
        }
    }else{
        $retuen['error_msg'] = '格式輸入錯誤';
    }
    return $retuen;
}


function pr($str){
    echo "<pre>";
    print_r($str);
    echo "<pre>";
}

?>