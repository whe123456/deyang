<?php
include_once 'login.php';
$conn=Database::Connect();
$sql="SELECT * FROM zjzz_js WHERE wxid=?";
$xs=Database::ReadoneRow($sql,$conn,array($_SESSION['user_id']));
if(!$xs){
    header("location:$urls/projectPath/#/teacher?url=$urls&wxid=$wid");exit;
}
$ts=date('Y-m-d H:i:s',strtotime("-1 month"));
if($ts>$xs['zc_ts']||$xs['sf_zc']=='0'){
    header("location:$urls/projectPath/#/teacher?url=$urls&wxid=$wid");exit;
}
header("location:$urls/projectPath/#/teachlist?url=$urls&wxid=$wid");exit;