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
checkRequestKeyHtml("address", "签到位置不能为空");
checkRequestKeyHtml("gps", "签到位置不能为空");
$type=empty($_REQUEST['type'])?0:$_REQUEST['type'];
$wxid = $_REQUEST['wxid'];
$address = $_REQUEST['address'];
$gps = $_REQUEST['gps'];
$conn=Database::Connect();
$sql="SELECT zdb.xh FROM zjzz_xs zx,zjzz_dhbmd zdb where zx.id=? and zx.dhbmd_id=zdb.id";
$user=Database::ReadoneStr($sql,$conn,array($wxid));
if(!$user){
	alertExitHtml("无此学号信息");
}
$now=date('Y-m-d H:i:s');
$today=date('Y-m-d');
$sql="SELECT count(*) from zjzz_kq where xs_id=? and kq_lx=? and create_ts like	'$today%'";
$jrkq=Database::ReadoneStr($sql,$conn,array($user,$type));
if($jrkq>0){
	alertExitHtml("今日已签到");
}
$sql="INSERT into zjzz_kq VALUES (NULL,?,?,?,?,?)";
Database::InsertOrUpdate($sql,$conn,array($user,$type,$now,$gps,$address));
echo json_encode(array('state'=>'true','msg'=>'签到成功'));