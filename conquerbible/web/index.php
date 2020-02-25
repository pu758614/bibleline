<?php
include_once("../../lib/TemplatePower/class.TemplatePower.inc.php");
include_once('../../vendor/autoload.php');
include_once('../lib/db_lib.php');
include_once('../lib/common.php');
$db = new db_lib;
$tpl_path = "tpl/index.tpl";
$tpl = new TemplatePower ($tpl_path);
session_start();
$action = isset($_GET['action'])?$_GET['action']:'basic_info';
$player_str = isset($_GET['player_id'])?$_GET['player_id']:'';
$player_id = getDecodeStr($player_str);
if($player_str==''){
    $player_id = isset($_SESSION['player_id'])?$_SESSION['player_id']:'';
}
$player_info = $db->getPlayerInfo($player_id);
if(empty($player_info)){
    exit("缺少參數或超過登入時間，請重新由LINE連結登入");
}
$_SESSION['player_id'] = $player_id;
$tpl->assignGlobal(array(
    "action" => $action,
));
$tpl -> prepare ();
$tpl_path = "tpl/".$action.".tpl";
$tpl->assignInclude( "content", $tpl_path );
$tpl -> prepare ();
if(is_file($action.".php")){
	include($action.".php");
}


$tpl -> printToScreen ();
?>