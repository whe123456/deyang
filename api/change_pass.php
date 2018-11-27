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
checkRequestKeyHtml("pass", "新密码不能为空");
checkRequestKeyHtml("checkPass", "新密码不能为空");
checkRequestKeyHtml("oldPass", "原密码不能为空");
$username=$_REQUEST['username'];
$pass=$_REQUEST['pass'];
$checkPass=$_REQUEST['checkPass'];
$oldPass=$_REQUEST['oldPass'];
if($pass!=$checkPass){
    alertExit('请输入两次密码不同，请重新输入');
}
if($pass==$oldPass){
    alertExit('新旧密码相同，请重新输入');
}
$conn=Database::Connect();
$dl_mm=Database::ReadoneStr("SELECT dl_mm FROM zjzz_js WHERE js_bm=?",$conn,array($username));
if(!$dl_mm){
    alertExit('无此教师编号信息');
}
if(md5($oldPass)!=$dl_mm){
    alertExit('原密码错误，请重新输入');
}
Database::Update_pre("UPDATE zjzz_js SET dl_mm=? WHERE js_bm=?",$conn,array(md5($pass),$username));
echo json_encode(array('state'=>'true'));