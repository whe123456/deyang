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
checkRequestKeyHtml("password", "密码不能为空");
$username = trim($_REQUEST['username']);
$password = trim($_REQUEST['password']);
$conn=Database::Connect();
$sql="SELECT * from zjzz_js where js_bm=?";
$user=Database::ReadoneRow($sql,$conn,array($username));
if(!$user){
	alertExitHtml("无此用户");
}
if(md5($password)!=$user['dl_mm'])alertExitHtml("密码错误");
$qx_list=Database::ReadoneStr("SELECT qx_list from zjzz_juese where id=?",$conn,array($user['js_id']));
$page=explode(',', $qx_list);
echo json_encode(array('state'=>'true','user'=>array('id'=>$user['id'],'name'=>$username,'page'=>$page[0],'js_id'=>$user['js_id'])));