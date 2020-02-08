<?php
header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');
include('bible_list_arr.php');
include ('vendor/autoload.php');

function insertData($db,$table,$data){
    //$table = $db->table($table,);
    $column_arr = $arr = '';
    $arr_prestr = array();
    foreach ($data as $key => $value) {
        $arr.= '?,';
        $column_arr.= "`".$key.'`,';
        array_push($arr_prestr,$value);
    }
    $arr        = substr($arr,0,-1);
    $column_arr = substr($column_arr,0,-1);
    $sql = "INSERT INTO $table ($column_arr) VALUES ($arr)";
    $result = $db->Execute($sql,$arr_prestr);
    if($result){
        $result_id = $db->_insertid();
        if( $result_id!=0 ){
            return $result_id;
        }else{
            return 1;
        }
    }return 0;
}

function search_keyword($kw='',$fz='kw'){
    include('bible_list_arr.php');
    if($fz=='kwf'){
        $keyword = urlencode($kw);
    }else{
        $keyword = urlencode('"'.$kw.'"');
    }

    $url = 'http://bible.fhl.net/new/search.php?VERSION=unv&TABFLAG=1&orig=0&strongflag=-1&keyword='.$keyword.'&RANGE=0&m=';
    $html=get_html($url);

    $over_data = strpos($html,'資料太多共');
    if($over_data !=''){
        $pattern_data = '|</script></head>[^>]*>(.*)|isU';
        preg_match_all($pattern_data, $html, $matches_data);
        preg_match_all('/\d+/u', $matches_data[0][0], $over_count);
        $retune_arr = array('error'  => '0',
                            'status' => 'over',
                            'msg'    => '資料量太多啦！高達'.$over_count[0][0].'筆資料，換個關鍵字查詢吧！！'.emoji('10009B').emoji('10009B').emoji('10009B')
        );
        goto goreturn;
    }
    else{
        $count =array();
        $matches_data = array();
        $pattern_count = '|</a></center><p />[^>]*>(.*)<table border="1">|isU';
        preg_match_all($pattern_count, $html, $matches_data);

        preg_match_all('/\d+/', $matches_data[0][0], $count);
        $count[0][0] = isset($count[0][0])?$count[0][0]:0;
        if($count[0][0]>40){
            $retune_arr = array('error'  => '0',
                                'status' => 'over',
                                'msg'    => "資料量太多啦！高達".$count[0][0].'筆資料，換個關鍵字查詢吧！！'.emoji('10009B').emoji('10009B').emoji('10009B')
            );
            goto goreturn;
        }elseif ($count[0][0]<=0) {
            $retune_arr = array('error'  => '0',
                                'status' => 'null',
                                'msg'    => "查尋不到任何資料喔...".emoji('10007B').emoji('10007B').emoji('10007B')
            );
            goto goreturn;
        }

        $pattern_text = '|<font color="#000000">[^>]*>(.*)</font></td>|isU';
        preg_match_all($pattern_text, $html, $matches_text);
        $pattern_book = '|<tr><td>[^>]*>(.*)</td>|isU';
        preg_match_all($pattern_book, $html, $matches_book);
        $arr = array();
        $sec_arr = array();

        if(count($matches_book[0])!=count($matches_text[0])){
            $retune_arr = array('error'  => '1',
                                'status' => 'error',
                                'msg'    => '發生意料以外的錯誤！麻煩通知開發人員！'.emoji('100083')
            );
            goto goreturn;
        }
        foreach ($matches_book[0] as $key => $matches_book_data) {
            $sec_epar_arr = preg_split("/([a-zA-Z0-9]+)/", trim(strip_tags($matches_book_data)), 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $book = trim(strip_tags($sec_epar_arr[0]));
            $chap = trim(strip_tags($sec_epar_arr[1]));
            $sec  = trim(strip_tags($sec_epar_arr[3]));
            $sec_arr[] = $abbre_chang[$book].$chap.'章'.$sec.'節';
        }
        $retune_data ='';
        foreach ($sec_arr as $key => $book_sec) {
            trim(strip_tags($matches_text[0][$key]));
            $text = trim(strip_tags($matches_text[0][$key]));
            $retune_data .= "(".$book_sec.")"."\n".$text."\n\n";
        }
        $retune_data = trim($retune_data);
        $retune_arr = array('error'  => '0',
                            'status' => 'ok',
                            'msg'    => '總共找到了'.$count[0][0].'筆資料喔！！'.emoji('100079').emoji('100079').emoji('100079')."\n\n".$retune_data,
        );
    }
    goreturn:
    return $retune_arr;
}


function write_log($db,$username,$user_id,$msg,$status){
    $data =array(
        "name_id" => $user_id,
        "name"    => $username,
        "inster_msg"    => $msg,
        "status"    => $status,
        "repont_json" => '',
        "create_time"    => date("Y-m-d H:i:s"),
    );
    pr($data);
    insertData($db,'line_bible_log',$data);
}


function getGuestInfo($channelAccessToken,$channelSecret,$userid){
    $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($channelAccessToken);
    $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
    $response = $bot->getProfile($userid);
    $profile = array();
    if ($response->isSucceeded()) {
        $profile = $response->getJSONDecodedBody();
    }
    return $profile;
}

function emoji($code){
    $bin = hex2bin(str_repeat('0', 8 - strlen($code)) . $code);
    $emoticon =  mb_convert_encoding($bin, 'UTF-8', 'UTF-32BE');
    return $emoticon;
}

function cheack_arrange($str){
    include 'bible_list_arr.php';
    $arr = array();
    $arr = preg_split("/([a-zA-Z0-9]+)/", $str, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    $code = '100095';
    $bin = hex2bin(str_repeat('0', 8 - strlen($code)) . $code);
    $emoticon =  mb_convert_encoding($bin, 'UTF-8', 'UTF-32BE');
    $search_fail_msg = '格式不太正確喔！可以輸入「這到底怎麼用啦」或「?」來開啟使用說明喔！'.emoji('100095').emoji('100095').emoji('100095');

    if(count($arr)==2 && ($arr[0] == 'kw' || $arr[0] == 'kwf')){
        $return = array('error' =>'0',
                        'type'  =>$arr[0],
                        'kw'    =>trim($arr[1])
                );
        return $return;
    }else if(count($arr)!=3 && count($arr)!=4 && count($arr)!=5){
        $return = array('error' => '1',
                        'msg'   => $search_fail_msg
              );
        return $return;
    }
    if(in_array($arr[0],$book_arr)!=''){

        $return['book'] = $arr[0];
        //檢查章捲
        if(is_numeric($arr[1])){
            $return['chap'] = $arr[1];
        }else{
            $return = array('error' => '1',
                            'msg'   => $search_fail_msg
                  );
            return $return;
        }

        if(count($arr)==3 || count($arr)==5){
            if($arr[2] != '章' && $arr[2] != '篇'){
                $return = array('error' => '1',
                                'msg'   => $search_fail_msg
                      );
                return $return;
            }
        }
        //XXX書oo:zz
        if(count($arr)==4){
            if($arr[2]==':' && is_numeric($arr[1]) && is_numeric($arr[3]) ){
                $return['sec'] = $arr[3];
                $return['chap'] = $arr[1];
            }else{
                $return = array('error' => '1',
                                'msg'   => $search_fail_msg
                      );
                return $return;
            }
        }
        //檢查節
        if(count($arr)==5){
            if($arr[4]=='節' && is_numeric($arr[3])){
                $return['sec'] = $arr[3];
            }else{
                $return = array('error' => '1',
                                'msg'   => $search_fail_msg
                      );
                return $return;
            }
        }
        $return['error'] = '0';
        $return['type']  = 'search';

    }else{
        $return = array('error' => '1',
                        'msg'   => $search_fail_msg
              );
        return $return;
    }

    return $return;

}

function text_change_arr($text='',$limit = 1800){

    $text = strip_tags($text);
    $text_arr = mb_split("\n",$text);
    $text_arr_nonull = array_filter($text_arr);
    $sec_arr = array();
    $change_key_val = 1;
    $count = 0;
    $text_arr_return = array();
    $text_arr_count = 0;
    $tetx_len = 0;
    foreach ($text_arr_nonull as $key => $text_data) {
        $text_arr_return[$text_arr_count] = isset($text_arr_return[$text_arr_count])?$text_arr_return[$text_arr_count]:'';
        $text_arr_return[$text_arr_count] .= $text_data."\n";
        $tetx_len = $tetx_len + mb_strlen($text_data);
        if($tetx_len>$limit){
            $text_arr_return[$text_arr_count] = trim($text_arr_return[$text_arr_count]);
            $text_arr_count++;
            $tetx_len = 0;
        }
    }
    $text_arr_return[$text_arr_count] = trim($text_arr_return[$text_arr_count]);
    return $text_arr_return;
}

function search($chin='',$chap='',$sec='',$str=''){
    include 'bible_list_arr.php';
       if($chin == ''){
        $return = array('error' => '1',
                        'msg'   => '書捲為必要填寫'
              );
        return $return;
    }else if($chap == ''){
        $return = array('error' => '1',
                        'msg'   => '章節為必要填寫!'
              );
        return $return;
    }
    $chineses = $chineses_arr[$chin];
    $chapsec = '';
    if($sec!=''){
        $url = "http://bible.fhl.net/new/read.php?chineses=".$chineses."&chap=".$chap."&sec=".$sec;
        $pattern = '|'.$sec.'" /></a[^>]*>(.*)<br />|isU';

    }else{
        $url = "http://bible.fhl.net/new/read.php?chineses=".$chineses."&chap=".$chap;
        $pattern = '|<div[^>]*>(.*)<hr />|isU';
    }


    $html=get_html($url);
    $coding = mb_detect_encoding($html);
    if ($coding != "UTF-8" || !mb_check_encoding($html, "UTF-8")){
        $html = mb_convert_encoding($html, 'utf-8', 'GBK,UTF-8,ASCII');
    }

    preg_match_all($pattern, $html, $matches);
    if($matches[1][0] != ''){
        if($sec!=''){
            $return = array('error'  => '0',
                            'data'   => trim(strip_tags($matches[1][0]))."($str)",
                            'status' => '1'
                  );
        }else{
            $return = array('error' => '0',
            'data'  => "($str)"."\n".trim(strip_tags($matches[1][0])),
                            'status' => '1'
                  );
        }

    }else{
        $return = array('error' => '0',
                        'data'  => '哇！查不到這個經文捏！'.emoji('10007B').emoji('10007B').emoji('10007B'),
                        'status' => '2'
              );
    }
    return $return;
}

function get_html($url){
    $ch = curl_init();
    $timeout = 10;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) chrome/34.0.1847.131 Safari/537.36');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $html = curl_exec($ch);
    return $html;
}
function pr($str){
    echo "<pre>";
    print_r($str);
    echo "</pre>";
}
?>