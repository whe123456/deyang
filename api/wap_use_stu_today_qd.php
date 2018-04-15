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
$sql="SELECT zdb.xh FROM zjzz_xs zx,zjzz_dhbmd zdb where zx.wxid=? and zx.dhbmd_id=zdb.id";
$user=Database::ReadoneStr($sql,$conn,array($wxid));
if(!$user){
	alertExitHtml("无此学号信息");
}
$now=date('Y-m-d');
$sql="SELECT count(*) from zjzz_kq where xs_id=? and kq_lx=0 and create_ts like	'$now%'";
$jrkq=Database::ReadoneStr($sql,$conn,array($user));
$sql="SELECT count(*) from zjzz_kq where xs_id=? and kq_lx=1 and create_ts like	'$now%'";
$zmkq=Database::ReadoneStr($sql,$conn,array($user));
echo json_encode(array('state'=>'true','jrkq'=>$jrkq,'zmkq'=>$zmkq));