<?php
header("Content-Type:text/html; charset=utf-8");
include ('vendor/autoload.php');
include ('channel_data.php');
require_once('./LINEBotTiny.php');
include "bibleAPI.php";
include 'bible_list_arr.php';
echo "<pre>";
$text_str = "路加福音2章";
$data = cheack_arrange($text_str);
print_r($data);
