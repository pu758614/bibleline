<?php

$file_path = "user_call.log";
if(file_exists($file_path)){
$fp = fopen($file_path,"r");
$str = fread($fp,filesize($file_path));//指定讀取大小，這裡把整個檔案內容讀取出來
echo $str = str_replace("\r\n","<br />",$str);
}


 ?>