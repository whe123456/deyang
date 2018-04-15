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
$conn=Database::Connect();
$sql="SELECT zdb.bjbm FROM zjzz_xs zx,zjzz_dhbmd zdb where zx.wxid=? and zx.dhbmd_id=zdb.id";
$user=Database::ReadoneStr($sql,$conn,array($wxid));
if(!$user){
	alertExitHtml("无此学号信息");
}
$sql="SELECT zj.js_bm as `key`,zj.xm as `value` from zjzz_bj zb,zjzz_js zj where zb.bj_bm=? and zb.js_bm=zj.js_bm";
$date=Database::Readall($sql,$conn,array($user));
echo json_encode(array('state'=>'true','list'=>$date));