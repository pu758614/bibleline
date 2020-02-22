<?php
$player_id = isset($_GET['user_id'])?$_GET['user_id']:'';

$player_info = $db->getPlayerInfo("1");
if(empty($player_info)){
    exit("錯誤的id參數");
}
$user_id = isset($player_info['user_id'])?$player_info['user_id']:'';
$user_info = $db->getUserInfo($user_id);
$user_name = isset($user_info['name'])?$user_info['name']:'';
$start_date = isset($player_info['start_date'])?$player_info['start_date']:'';
$new_percent = isset($player_info['new_percent'])?$player_info['new_percent']:'';
$old_percent = isset($player_info['old_percent'])?$player_info['old_percent']:'';
$all_percen = isset($player_info['all_percen'])?$player_info['all_percen']:'';
$tpl->gotoBlock( "content" );
$tpl->assignGlobal(array(
    "user_name" => $user_name,
    "start_date" => $start_date,
    "old_percent" => $old_percent,
    "new_percent" => $new_percent,
    "all_percen" => $all_percen,
    "page_type"  => $action,
));
$book_arr = $db->getBibleBook();
foreach ($book_arr as $book_name => $book_data) {
    $count = $book_data['count'];
    $testament = $book_data['testament'];
    $tpl->newBlock("book_block");
    $tpl->assign( "book_name", $book_name );
    $tpl->assign(array(
        "book_name" => $book_name,
        "testament_type" => "testament_".$testament
    ));
    $row_count = 0;
    for ($i=1; $i <=$count ; $i++) {
        if($row_count==0){
            $tpl->newBlock("row");
        }
        $tpl->newBlock("chapter");
        $tpl->assign(array(
            "chapter_no" => $i,
        ) );
        if($row_count==9){
            $row_count=0;
        }else{
            $row_count++;
        }
    }
}

?>