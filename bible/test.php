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
$db->connect($host,$user,$passwd,$database);
//$conn->debug = true;
$sql = "SELECT * FROM `conquer_bible_book`";
$result = $db->execute($sql);
if($result){
    $arr = $result->getAll();
    $return = array();
    foreach ($arr as  $arr_data) {
        //print_r($arr_data);
        $return[] = array(
            "id" => isset($arr_data['id'])?$arr_data['id']:'',
            "name_id" => isset($arr_data['name_id'])?$arr_data['name_id']:'',
            "name" => isset($arr_data['name'])?$arr_data['name']:'',
            "inster_msg" => isset($arr_data['inster_msg'])?$arr_data['inster_msg']:'',
            "status" => isset($arr_data['status'])?$arr_data['status']:'',
            "repont_json" => isset($arr_data['repont_json'])?$arr_data['repont_json']:'',
            "create_time" => isset($arr_data['create_time'])?$arr_data['create_time']:'',
        );
    }
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}else{
    echo "fail";
}