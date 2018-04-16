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
checkRequestKeyHtml("wxid", "用户信息不能为空");
$wxid = $_REQUEST['wxid'];
checkRequestKeyHtml("id", "请假信息不能为空");
$id = $_REQUEST['id'];
$conn=Database::Connect();
$sql="SELECT zq.*,zj.xm from zjzz_qj zq,zjzz_js zj WHERE zq.id=? AND zj.js_bm=zq.js_bm";
$info=Database::ReadoneRow($sql,$conn,array($id));
if(!$info){
    alertExitHtml("无此请假信息");
}
$sql="SELECT xm FROM zjzz_dhbmd WHERE xh=?";
$user=Database::ReadoneStr($sql,$conn,array($info['xs_id']));
if(!$user){
    alertExitHtml("无此学号信息");
}
$info['stu_xm']=$user;
echo json_encode(array('state'=>'true','info'=>$info));