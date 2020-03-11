<?php
header("Content-Type:text/html; charset=utf-8");
function dxpected_done_percent($start_date,$done_month_count){
    $date = new DateTime($start_date);
    $date->add(new DateInterval("P".$done_month_count."M"));
    $done_date = $date->format('Y-m-d');
    $total_days = round((strtotime($done_date)-strtotime($start_date))/3600/24);
    $remain_days = round((strtotime(date("Y-m-d"))-strtotime($start_date))/3600/24);
    $remain_days = $remain_days+1;
    $days_p = $remain_days/$total_days*100;
    $days_p = round($days_p, 1);
    return $days_p;
}

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

function DateClearZeor($date){
    $date_arr = explode('/',$date);
    $m = (int)$date_arr[0];
    $d = (int)$date_arr[1];
    return $m."/".$d;
}


function convertStrType($strs, $types = 'wf_to_nf'){ //全形半形轉換
    $nft = array(
        "(", ")", "[", "]", "{", "}", ".", ",", ";", ":",
        "-", "?", "!", "@", "#", "$", "%", "&", "|", "\\",
        "/", "+", "=", "*", "~", "`", "'", "\"", "<", ">",
        "^", "_", "[", "]",
        "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j",
        "k", "l", "m", "n", "o", "p", "q", "r", "s", "t",
        "u", "v", "w", "x", "y", "z",
        "A", "B", "C", "D", "E", "F", "G", "H", "I", "J",
        "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T",
        "U", "V", "W", "X", "Y", "Z",
        " "
    );
    $wft = array(
        "（", "）", "〔", "〕", "｛", "｝", "﹒", "，", "；", "：",
        "－", "？", "！", "＠", "＃", "＄", "％", "＆", "｜", "＼",
        "／", "＋", "＝", "＊", "～", "、", "、", "＂", "＜", "＞",
        "︿", "＿", "【", "】",
        "０", "１", "２", "３", "４", "５", "６", "７", "８", "９",
        "ａ", "ｂ", "ｃ", "ｄ", "ｅ", "ｆ", "ｇ", "ｈ", "ｉ", "ｊ",
        "ｋ", "ｌ", "ｍ", "ｎ", "ｏ", "ｐ", "ｑ", "ｒ", "ｓ", "ｔ",
        "ｕ", "ｖ", "ｗ", "ｘ", "ｙ", "ｚ",
        "Ａ", "Ｂ", "Ｃ", "Ｄ", "Ｅ", "Ｆ", "Ｇ", "Ｈ", "Ｉ", "Ｊ",
        "Ｋ", "Ｌ", "Ｍ", "Ｎ", "Ｏ", "Ｐ", "Ｑ", "Ｒ", "Ｓ", "Ｔ",
        "Ｕ", "Ｖ", "Ｗ", "Ｘ", "Ｙ", "Ｚ",
        "　"
    );
    if ( $types == 'nf_to_wf' ){// 轉全形
        return str_replace($nft, $wft, $strs);
    }else if( $types == 'wf_to_nf' ){// 轉半形
        return str_replace($wft, $nft, $strs);
    }else{
        return $strtmp;
    }
}
function getEncodeStr($str){
    $hashKey4encode = '758614';
    $str=openssl_encrypt($str,'des-ede3',oaksPadKeyLength($hashKey4encode),0);
    //$str = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, oaksPadKeyLength($hashKey4encode), $str, MCRYPT_MODE_CBC, md5($hashKey4encode)));
    $str = base64_encode($str);
    $str = str_replace(array('+', '/', '='), array('-', '_', ''), $str);
    return $str;
}
function getDecodeStr($str){
    $hashKey4encode = '758614';
    $str = str_replace(array('-', '_'), array('+', '/'), $str);
    $str = base64_decode($str);
    //$str = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, oaksPadKeyLength($hashKey4encode), base64_decode($str), MCRYPT_MODE_CBC, md5($hashKey4encode));
    $str=openssl_decrypt($str,'des-ede3',oaksPadKeyLength($hashKey4encode),0);
    return trim($str);
}

function oaksPadKeyLength($key){
    if(strlen($key) > 32) {
        return false;
    }
    $sizes = array(16,24,32);

    foreach($sizes as $s){
        while(strlen($key) < $s) $key = $key."\0";
        if(strlen($key) == $s) break; // finish if the key matches a size
    }
    return $key;
}

function pr($str){
    echo "<pre>";
    print_r($str);
    echo "</pre>";
}

?>