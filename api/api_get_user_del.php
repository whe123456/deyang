<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Method:POST,GET");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Content-type: text/html; charset=utf-8");
include_once dirname(__FILE__) . '/../config/setting.php';
include_once($dii_ctx_root_dir . '/include/function.php');
include_once $dii_ctx_root_dir . '/include/class.Database.php';
include_once $dii_ctx_root_dir . '/include/code.php';
if(!isset($_SESSION)){
    session_start();
}
checkRequestKeyHtml("username", "用户名不能为空");
$id=empty($_REQUEST['id'])?'':$_REQUEST['id'];
$sql="SELECT count(*) from zjzz_bj where js_bm='$id'";
$conn=Database::Connect();
$count=Database::ReadoneStr($sql,$conn,array());
if($count>0){
    alertExit('该老师正在管理某班级，无法删除，请修改后再试');
}
$sql="DELETE FROM zjzz_js WHERE js_bm=?";
Database::InsertOrUpdate($sql,$conn,array($id));
echo json_encode(array('state'=>'true'));