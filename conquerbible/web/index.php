<?php
include_once("../../lib/TemplatePower/class.TemplatePower.inc.php");
include_once('../../vendor/autoload.php');
include_once('../lib/db_lib.php');
include_once('../lib/common.php');
$db = new db_lib;
$tpl_path = "tpl/index.tpl";
$tpl = new TemplatePower ($tpl_path);
$action = isset($_GET['action'])?$_GET['action']:'basic_info';

$tpl_path = "tpl/".$action.".tpl";
$tpl->newBlock("book_block");
$book_arr = $db->getBibleBook();

foreach ($book_arr as $book_name => $book_data) {
    $count = $book_data['count'];
    $testament = $book_data['testament'];

    $tpl->newBlock("book_block");
    $tpl->assign( "book_name", $book_name );
}
$tpl->assignInclude( "content", $tpl_path );
if(is_file($action.".php")){
	include($action.".php");
}
$tpl -> prepare ();

$tpl -> printToScreen ();
?>