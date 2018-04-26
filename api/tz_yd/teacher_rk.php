<?php
include_once '../rk/login.php';
$id=$_REQUEST['id'];
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
$info=Database::ReadoneRow("select * from zjzz_qj where id=?",$conn,array($id));
if(!$info){
	header("location:$urls/projectPath/#/teachlist?url=$urls&wxid=$wid");exit;
}
if($info['sf_ty']==0){
	header("location:$urls/projectPath/#/leavesp?id=$id&url=$urls&wxid=$wid");exit;
}elseif($info['jdc_ty']==0&&$xs['js_id']==4){
	header("location:$urls/projectPath/#/leavesp?id=$id&url=$urls&wxid=$wid");exit;
}else{
	header("location:$urls/projectPath/#/leaveinfo?id=$id&url=$urls&wxid=$wid");exit;
}