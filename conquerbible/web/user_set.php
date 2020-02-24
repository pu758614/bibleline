<?php 
$start_date = isset($player_info['start_date'])?$player_info['start_date']:date("Y-m-d");
$done_month_count = isset($player_info['done_month_count'])?$player_info['done_month_count']:'12';
$year = floor($done_month_count/12);
$month = $done_month_count%12;
$start_date = str_replace('-','/',$start_date);
$tpl->assign("start_date",$start_date);
$tpl->assign(array(
    "start_date" => $start_date,
    "year" => $year,
    "month" => $month,
));
?>