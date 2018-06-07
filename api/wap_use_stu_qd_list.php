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
checkRequestKeyHtml("ts", "月份信息不能为空");
$ts = $_REQUEST['ts'];
$conn=Database::Connect();
$sql="SELECT zdb.id FROM zjzz_xs zx,zjzz_dhbmd zdb where zx.wxid=? and zx.dhbmd_id=zdb.id";
$user=Database::ReadoneStr($sql,$conn,array($wxid));
if(!$user){
	alertExitHtml("无此学生信息");
}
$now=date('Y-m',strtotime($ts));
$sql="SELECT * from zjzz_kq WHERE xs_id=? and create_ts like '$now%'";
$date=Database::Readall($sql,$conn,array($user));
$list=array();
if($date&&count($date)>0){
	foreach ($date as $key => $value) {
		$list[$key]=date('d',strtotime($value['create_ts']));
	}
}
echo json_encode(array('state'=>'true','list'=>$list));