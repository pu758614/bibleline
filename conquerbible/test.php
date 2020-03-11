<?php
include_once("conf/conf.php");
include_once('../vendor/autoload.php');
include_once("lib/common.php");
include_once("lib/db_lib.php");

$db = new db_lib;
 
$a = getDecodeStr('QnkwLytVaEl2c3M9');
echo $a;
$b = getEncodeStr('');
echo $b;
// $data=array(
//     "name" => '帖撒羅尼迦後書',
// );
// $db->db->debug = 1;
// $result=$db->updateData('conquer_bible_book',$data,array("id"=>53));
// $db->db->debug = 0;
// pr($result);
// $cond = array("testament"=>1);
// $data = $db->getArrayByArray('conquer_bible_book',$cond);
// pr($data);
?>