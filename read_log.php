<?php

$file_path = "test.log";
if(file_exists($file_path)){
    echo "is_file<br>";
    $fp = fopen($file_path,"r");
    $str = fread($fp,filesize($file_path));//指定讀取大小，這裡把整個檔案內容讀取出來
    echo $str = str_replace("\r\n","<br />",$str);
}


 ?>