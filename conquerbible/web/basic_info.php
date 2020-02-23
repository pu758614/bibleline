<?php
$player_str = isset($_GET['player_id'])?$_GET['player_id']:'';
$player_id = getDecodeStr($player_str);

$player_info = $db->getPlayerInfo($player_id);
if(empty($player_info)){
    exit("錯誤的id參數");
}
$_SESSION['player_id'] = $player_id;
$user_id = isset($player_info['user_id'])?$player_info['user_id']:'';
$user_info = $db->getUserInfo($user_id);
$user_name = isset($user_info['name'])?$user_info['name']:'';
$start_date = isset($player_info['start_date'])?$player_info['start_date']:'';
$new_percent = isset($player_info['new_percent'])?$player_info['new_percent']:'';
$old_percent = isset($player_info['old_percent'])?$player_info['old_percent']:'';
$all_percen = isset($player_info['all_percen'])?$player_info['all_percen']:'';
$startdate=strtotime($start_date);
$enddate=strtotime(date("Y-m-d"));
$days=ceil(abs($startdate - $enddate)/86400);
$days= $days+1;
$days_p = $days/300*100;
$days_p = round($days_p, 1);


$tpl->gotoBlock( "content" );
$tpl->assign(array(
    "user_name" => $user_name,
    "start_date" => $start_date,
    "old_percent" => $old_percent,
    "new_percent" => $new_percent,
    "all_percen" => $all_percen,
    "page_type"  => $action,
));
$read_data = $db->getReadDate($player_id);
$book_arr = $db->getBibleBook();
foreach ($book_arr as $book_name => $book_data) {
    $count = $book_data['count'];
    $testament = $book_data['testament'];
    $book_id = $book_data['id'];
    $tpl->newBlock("book_block");
    $table_w = 100;
    if($count<10){
        $table_w = $count*10;
    }

    $tpl->assign(array(
        "book_name" => $book_name,
        "table_w"   => $table_w
    ));
    $tpl->assign(array(
        "book_name" => $book_name,
        "testament_type" => "testament_".$testament
    ));
    $row_count = 0;
    for ($i=1; $i <=$count ; $i++) {
        if($row_count==0){
            $tpl->newBlock("row");
        }
        $check = '';
        if(isset($read_data[$book_id.'_'.$i])){
            $check = '✔';
        }
        $tpl->newBlock("chapter");
        $tpl->assign(array(
            "chapter_no" => $i,
            "check" => $check,
            "data" => $book_id.'_'.$i,
        ) );
        if($row_count==9){
            $row_count=0;
        }else{
            $row_count++;
        }
    }
}

?>