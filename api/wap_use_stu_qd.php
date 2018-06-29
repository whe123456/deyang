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
$sql="SELECT zdb.* FROM zjzz_dhbmd zdb,zjzz_xs zq where zq.wxid=? and zdb.id=zq.dhbmd_id";
$user=Database::ReadoneRow($sql,$conn,array($wxid));
if(!$user){
	alertExitHtml("无此学生信息");
}
$sql="SELECT * FROM user_kq_ts where wxid=?";
$ts_info=Database::ReadoneRow($sql,$conn,array($wxid));
if(!$ts_info){
	alertExitHtml("无此二维码信息");
}
$min_ago=date('Y-m-d H:i:s',strtotime('-30 minutes'));
if($min_ago>$ts_info['ts']){
	alertExitHtml("二维码已过期，请重新登录获取二维码");
}
$now=date('Y-m-d H:i:s');
$today=date('Y-m-d');
$sql="SELECT count(*) from zjzz_kq where xs_id=? and kq_lx=? and create_ts like	'$today%'";
$jrkq=Database::ReadoneStr($sql,$conn,array($user['id'],$type));
if($jrkq>0){
echo json_encode(array('state'=>'false','msg'=>$user['bj_mc']." 手机号码".$user['sjhm']." ".$user['xm']."今日已考勤",'stu_info'=>array('name'=>$user['xm'],'sex'=>$user['sex'],'photo'=>$user['photo'],'bj_mc'=>$user['bj_mc'])));exit;
}
$sql="INSERT into zjzz_kq VALUES (NULL,?,?,?,?,?)";
Database::InsertOrUpdate($sql,$conn,array($user['id'],$type,$now,$gps,$address));
echo json_encode(array('state'=>'true','msg'=>$user['bj_mc']." 手机号码".$user['sjhm']." ".$user['xm'].'考勤成功','stu_info'=>array('name'=>$user['xm'],'sex'=>$user['sex'],'photo'=>$user['photo'],'bj_mc'=>$user['bj_mc'])));