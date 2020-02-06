<?php
include "bibleAPI.php";
include "bible_list_arr.php";
header("Content-Type:text/html; charset=utf-8");
include 'bible_list_arr.php';
echo "<pre>";
$input = 'kw百姓';




$data = cheack_arrange($input);


if($data['error'] == '1'){
    echo $data['msg'];
    exit;
}
if($data['type'] == 'search' ){
    $results = search($data['book'],$data['chap'],$data['sec'],$input);
    if (mb_strlen($results['data'],"utf-8")>1900){
        echo '太大啦';
    }else{
        echo $results['data'];
    }
}else if($data['type'] == 'kw' ||$data['type'] == 'kwf'){
    $results = search_keyword($data['kw'],$data['type']);
    if($results['status']=='ok'){
        echo $results['msg']."\n";
        echo $results['data'];
    }else{
        echo $results['msg'];
    }
}else{
    echo '意料以外的錯誤，請通知開發人。'.emoji('10007D');
}


exit;
    if($fz=='1'){
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
        preg_match_all('/\d+/', $matches_data[0][0], $over_count);
        $retune_arr = array('error'  => '0',
                            'status' => 'over',
                            'msg'    => "資料太多啦，高達".$over_count[0][0].'筆資料，換個關鍵字查詢吧！！'
        );
    }
    else{
        $pattern_count = '|</a></center><p />[^>]*>(.*)<table border="1">|isU';
        preg_match_all($pattern_count, $html, $matches_data);
        preg_match_all('/\d+/', $matches_data[0][0], $count);
        if($count[0][0]>40){
            $retune_arr = array('error'  => '0',
                                'status' => 'over',
                                'msg'    => "資料太多啦，高達".$count[0][0].'筆資料，換個關鍵字查詢吧！！'
            );
        }

        $pattern_text = '|<font color="#000000">[^>]*>(.*)</font></td>|isU';
        preg_match_all($pattern_text, $html, $matches_text);
        $pattern_book = '|<tr><td>[^>]*>(.*)</td>|isU';
        preg_match_all($pattern_book, $html, $matches_book);
        $arr = array();
        foreach ($matches_book[0] as $key => $matches_book_data) {
            $sec_epar_arr = preg_split("/([a-zA-Z0-9]+)/", trim(strip_tags($matches_book_data)), 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $book = trim(strip_tags($sec_epar_arr[0]));
            $chap = trim(strip_tags($sec_epar_arr[1]));
            $sec  = trim(strip_tags($sec_epar_arr[3]));
            $sec_arr[] = $abbre_chang[$book].$chap.'章'.$sec.'節';
        }

        foreach ($sec_arr as $key => $sec) {
            trim(strip_tags($matches_text[0][$key]));
             $text = trim(strip_tags($matches_text[0][$key]));
             $retune_data .= $text."(".$sec.")"."\n";
        }

        $retune_arr = array('error'  => '0',
                            'status' => 'ok',
                            'msg'    => '總共找到了'.$count[0][0].'筆資料！！',
                            'data'   => $retune_data
        );
    }


print_r($retune_arr);
/****將章節與內文轉換為array的key val  (未完成)
$str = "詩篇119篇";
$arr = preg_split("/([a-zA-Z0-9]+)/", $str, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
$data = cheack_arrange($str);
if($data['error'] == '0'){
    $results= API($data['book'],$data['chap'],$data['sec'],$str);
}else{
    echo $data['msg'];
}
$text = strip_tags($results['data']);
$text_arr = mb_split("\s",$text);
$sec_arr = array();
$change_key_val = 1;
$count = 0;
foreach ($text_arr as $key => $value) {
    if($value==''){
        continue;
    }
    $text_ch_arr[$count] = $value;
    $count++;
}

print_r($text_ch_arr);
foreach ($text_ch_arr as $sec_num => $sec) {
    if($change_key_val>0){
        $sec_arr[$sec] = $text_ch_arr[$sec_num+1];
    }

    $change_key_val = -$change_key_val;
}

print_r($sec_arr);
***/
?>
