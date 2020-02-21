<?php
$player_id = isset($_GET['player_id'])?$_GET['player_id']:'';
//$player_id = 42;

$player_info = $db->getPlayerInfo($player_id);
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
}

?>