<?php
include_once 'login.php';
$conn=Database::Connect();
$sql="SELECT * FROM zjzz_js WHERE wxid=?";
$xs=Database::ReadoneRow($sql,$conn,array($_SESSION['user_id']));
if(!$xs){
    header("location:http://192.168.0.188:8080/#/teacher?url=$url&wxid=$wid");exit;
}
$ts=date('Y-m-d H:i:s',strtotime("-1 month"));
if($ts>$xs['zc_ts']||$xs['sf_zc']=='0'){
    header("location:http://192.168.0.188:8080/#/teacher?url=$url&wxid=$wid");exit;
}
header("location:http://192.168.0.188:8080/#/teachlist?url=$url&wxid=$wid");exit;