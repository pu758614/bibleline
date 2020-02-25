<?php

$type = isset($_GET['type'])?$_GET['type']:'';
$user_id = isset($player_info['user_id'])?$player_info['user_id']:'';
$user_info = $db->getUserInfo($user_id);
$user_name = isset($user_info['name'])?$user_info['name']:'';
$start_date = isset($player_info['start_date'])?$player_info['start_date']:'';
$new_percent = isset($player_info['new_percent'])?$player_info['new_percent']:'';
$old_percent = isset($player_info['old_percent'])?$player_info['old_percent']:'';
$all_percen = isset($player_info['all_percen'])?$player_info['all_percen']:'';
$done_month_count = isset($player_info['done_month_count'])?$player_info['done_month_count']:12;
$days_percen = dxpected_done_percent($start_date,$done_month_count);

$tpl->gotoBlock( "content" );
$tpl->assign(array(
    "user_name" => $user_name,
    "start_date" => $start_date,
    "old_percent" => $old_percent,
    "new_percent" => $new_percent,
    "all_percen" => $all_percen,
    "days_percen" => $days_percen,
    "page_type"  => $action,
    "type"       => $type,
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
        "table_w"   => $table_w,
        "testament_type" => "testament_".$testament,
        "book_id" => $book_id,
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
            "book_id" => $book_id,
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