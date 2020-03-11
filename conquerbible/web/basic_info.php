<?php

$type = isset($_GET['type'])?$_GET['type']:'';
$user_id = isset($player_info['user_id'])?$player_info['user_id']:'';
$user_info = $db->getUserInfo($user_id);
$user_name = isset($user_info['name'])?$user_info['name']:'';
$start_date = isset($player_info['start_date'])?$player_info['start_date']:'';
$new_percent = isset($player_info['new_percent'])?$player_info['new_percent']:'';
$old_percent = isset($player_info['old_percent'])?$player_info['old_percent']:'';
$all_percen = isset($player_info['all_percen'])?$player_info['all_percen']:'';
$done_count = isset($player_info['done_count'])?$player_info['done_count']:'';
$done_month_count = isset($player_info['done_month_count'])?$player_info['done_month_count']:12;
$days_percen = dxpected_done_percent($start_date,$done_month_count);
$read_count = $db->PlayerTotalReadCount($player_id);
if($boot_total_count==$read_count){
    $tpl->newBlock("reset_bt_block");
}
$tpl->gotoBlock("_ROOT");
$tpl->assign(array(
    "user_name" => htmlspecialchars($user_name),
    "start_date" => $start_date,
    "old_percent" => $old_percent,
    "new_percent" => $new_percent,
    "all_percen" => $all_percen,
    "days_percen" => $days_percen,
    "page_type"  => $action,
    "type"       => $type,
    "done_count" => $done_count,
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
        $show_date = '';
        $color_data = "000000";
        if(isset($read_data[$book_id.'_'.$i])){
            $date = $read_data[$book_id.'_'.$i];
            $date_arr = explode('-',$date);
            $day = $date_arr[2];
            $color_no = $i%10;
            $color_data = $color_arr[$color_no];
            $new_date = DateClearZeor(date('m/d',strtotime($date)));
            $show_date = (date('Y',strtotime($date))-1911)."<br>".$new_date;
            $check = '✔';
        }
        
        $tpl->newBlock("chapter");
        $tpl->assign(array(
            "chapter_no" => $i,
            "check" => $check,
            "book_id" => $book_id,
            "data" => $book_id.'_'.$i,
            "no_color" => $color_data,
            "show_date" => $show_date,
        ) );
        if($row_count==9){
            $row_count=0;
        }else{
            $row_count++;
        }
    }
}

?>