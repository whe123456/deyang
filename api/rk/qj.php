<?php
include_once 'login.php';
$conn=Database::Connect();
$sql="SELECT * FROM zjzz_xs WHERE wxid=?";
$xs=Database::ReadoneRow($sql,$conn,array($_SESSION['user_id']));
if(!$xs){
    header("location:$urls/projectPath/#/stu?url=$urls&wxid=$wid");exit;
}
$sql="SELECT * FROM zjzz_dhbmd WHERE id=? AND yzsj>? AND sf_yz='1'";
$ts=date('Y-m-d H:i:s',strtotime("-1 month"));
$bmd=Database::ReadoneRow($sql,$conn,array($xs['dhbmd_id'],$ts));
if(!$bmd){
    header("location:$urls/projectPath/#/stu?url=$urls&wxid=$wid");exit;
}
header("location:$urls/projectPath/#/leave?url=$urls&wxid=$wid");exit;