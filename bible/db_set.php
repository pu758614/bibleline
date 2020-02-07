<?php
include ('../vendor/autoload.php');
$host = 'us-cdbr-iron-east-04.cleardb.net';
//改成你登入phpmyadmin帳號
$user = 'b65f080869b290';
//改成你登入phpmyadmin密碼
$passwd = 'afa6a322';
//資料庫名稱
$database = 'heroku_4d9bdcbc4d69fab';
//實例化mysqli(資料庫路徑, 登入帳號, 登入密碼, 資料庫)
$db = ADONewConnection('mysqli');
//$conn->debug = true;
$db -> Connect($host,$user,$passwd,$database);