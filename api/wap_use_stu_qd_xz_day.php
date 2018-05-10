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
$sql="SELECT zdb.sjhm FROM zjzz_xs zx,zjzz_dhbmd zdb where zx.wxid=? and zx.dhbmd_id=zdb.id";
$user=Database::ReadoneStr($sql,$conn,array($wxid));
if(!$user){
	alertExitHtml("无此学生信息");
}
$now=date('Y-m-d',strtotime($ts));
$sql="SELECT * from zjzz_kq WHERE xs_id=? and create_ts like '$now%'";
$date=Database::Readall($sql,$conn,array($user));
$rckq=false;
$zmkq=false;
if($date&&count($date)>0){
	foreach ($date as $key => $value) {
		if($value['kq_lx']==0){
			$rckq=true;
		}
		if($value['kq_lx']==1){
			$zmkq=true;
		}
	}
}
$weekarray=array("日","一","二","三","四","五","六");
echo json_encode(array('state'=>'true','ts_info'=>$now.' 星期'.$weekarray[date("w",strtotime($ts))],'rckq'=>$rckq,'zmkq'=>$zmkq));