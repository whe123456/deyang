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
$sql="SELECT zdb.sjhm,zdb.xm,zdb.bj_mc,zdb.`grade` FROM zjzz_xs zx,zjzz_dhbmd zdb where zx.wxid=? and zx.dhbmd_id=zdb.id";
$user=Database::ReadoneRow($sql,$conn,array($wxid));
if(!$user){
	alertExitHtml("无此学生信息");
}
$now=date('Y-m-d H:i:s');
$today=date('Y-m-d');
$sql="SELECT count(*) from zjzz_kq where xs_id=? and kq_lx=? and create_ts like	'$today%'";
$jrkq=Database::ReadoneStr($sql,$conn,array($user['sjhm'],$type));
if($jrkq>0){
	alertExitHtml($user['grade']." ".$user['bj_mc']." 手机号码".$user['sjhm']." ".$user['xm']."今日已考勤");
}
$sql="INSERT into zjzz_kq VALUES (NULL,?,?,?,?,?)";
Database::InsertOrUpdate($sql,$conn,array($user['sjhm'],$type,$now,$gps,$address));
echo json_encode(array('state'=>'true','msg'=>$user['grade']." ".$user['bj_mc']." 手机号码".$user['sjhm']." ".$user['xm'].'考勤成功'));