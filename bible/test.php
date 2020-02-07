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
$data =array(
    "name_id" => 4,
    "name"    => 'aaa',
    "inster_msg"    => '6666',
    "status"    => '2',
    "create_time"    => date("Y-m-d H:i:s"),
);
print_r($data);
insertData($db,'line_bible_call_log',$data);
//INSERT INTO `line_bible_call_log` (`id`, `name_id`, `name`, `inster_msg`, `status`, `create_time`) VALUES (NULL, '1', '2', '3', '4', NOW());

function insertData($db,$table,$data){
    //$table = $db->table($table,);
    $column_arr = $arr = '';
    $arr_prestr = array();
    print_r("66666");
    foreach ($data as $key => $value) {
        $arr.= '?,';
        $column_arr.= $key.'`,';
        array_push($arr_prestr,$value);
    }
    $arr        = substr($arr,0,-1);
    $column_arr = substr($column_arr,0,-1);

    $sql = "INSERT INTO $table ($column_arr) VALUES ($arr)";
    echo $sql;
    $db->debug=true;
    $result = $db->Execute($sql,$arr_prestr);
    echo "-------";
    print_r($result);
    $db->debug=false;
    if($result){
        $result_id = $db->_insertid();
        if( $result_id!=0 ){
            return $result_id;
        }else{
            return 1;
        }
    }return 0;
}