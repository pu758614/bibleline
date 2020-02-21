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
/**
* 字串半形和全形間相互轉換
* @param string $str 待轉換的字串
* @param int  $type TODBC:轉換為半形；TOSBC，轉換為全形
* @return string 返回轉換後的字串
*/
function convertStrType($str, $type='TODBC') {
    $dbc = array(
        '０' , '１' , '２' , '３' , '４' , 
        '５' , '６' , '７' , '８' , '９' ,
        'Ａ' , 'Ｂ' , 'Ｃ' , 'Ｄ' , 'Ｅ' ,
        'Ｆ' , 'Ｇ' , 'Ｈ' , 'Ｉ' , 'Ｊ' ,
        'Ｋ' , 'Ｌ' , 'Ｍ' , 'Ｎ' , 'Ｏ' ,
        'Ｐ' , 'Ｑ' , 'Ｒ' , 'Ｓ' , 'Ｔ' ,
        'Ｕ' , 'Ｖ' , 'Ｗ' , 'Ｘ' , 'Ｙ' ,
        'Ｚ' , 'ａ' , 'ｂ' , 'ｃ' , 'ｄ' ,
        'ｅ' , 'ｆ' , 'ｇ' , 'ｈ' , 'ｉ' ,
        'ｊ' , 'ｋ' , 'ｌ' , 'ｍ' , 'ｎ' ,
        'ｏ' , 'ｐ' , 'ｑ' , 'ｒ' , 'ｓ' ,
        'ｔ' , 'ｕ' , 'ｖ' , 'ｗ' , 'ｘ' ,
        'ｙ' , 'ｚ' , '－' , '　' , '：' ,
        '．' , '，' , '／' , '％' , '＃' ,
        '！' , '＠' , '＆' , '（' , '）' ,
        '＜' , '＞' , '＂' , '＇' , '？' ,
        '［' , '］' , '｛' , '｝' , '＼' ,
        '｜' , '＋' , '＝' , '＿' , '＾' ,
        '￥' , '￣' , '｀'
    );
    $sbc = array( //半形
        '0', '1', '2', '3', '4',
        '5', '6', '7', '8', '9',
        'A', 'B', 'C', 'D', 'E',
        'F', 'G', 'H', 'I', 'J',
        'K', 'L', 'M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T',
        'U', 'V', 'W', 'X', 'Y',
        'Z', 'a', 'b', 'c', 'd',
        'e', 'f', 'g', 'h', 'i',
        'j', 'k', 'l', 'm', 'n',
        'o', 'p', 'q', 'r', 's',
        't', 'u', 'v', 'w', 'x',
        'y', 'z', '-', ' ', ':',
        '.', ',', '/', '%', ' #',
        '!', '@', '&', '(', ')',
        '<', '>', '"', '\'','?',
        '[', ']', '{', '}', '\\',
        '|', ' ', '=', '_', '^',
        '￥','~', '`'
    );
    if($type == 'TODBC'){
        return str_replace( $sbc, $dbc, $str ); //半形到全形
    }elseif($type == 'TOSBC'){
        return str_replace( $dbc, $sbc, $str ); //全形到半形
    }else{
        return $str;
    }
}

function pr($str){
    echo "<pre>";
    print_r($str);
    echo "<pre>";
}

?>