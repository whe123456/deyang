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
checkRequestKeyHtml("xh", "学号不能为空");
checkRequestKeyHtml("name", "姓名不能为空");
checkRequestKeyHtml("tel", "手机号码不能为空");
checkRequestKeyHtml("yzm", "验证码不能为空");
checkRequestKeyHtml("wxid", "验证码不能为空");
$xh = $_REQUEST['xh'];
$name = $_REQUEST['name'];
$tel = $_REQUEST['tel'];
$yzm = $_REQUEST['yzm'];
$wxid = $_REQUEST['wxid'];
$conn=Database::Connect();
$sql="SELECT * from zjzz_dhbmd where xh=?";
$user=Database::ReadoneRow($sql,$conn,array($xh));
if(!$user){
	alertExitHtml("无此学号信息");
}
if($name!=$user['xm']){
	alertExitHtml("学生姓名信息错误");
}
if($tel!=$user['sjhm']){
	alertExitHtml("学生电话信息错误");
}
$now=date('Y-m-d H:i:s');
$min_ago=date('Y-m-d H:i:s',strtotime( '-10 Minute'));
$sql="SELECT * from zjzz_yzm WHERE sjhm=? and yzm=? and is_use=0 ORDER BY id desc";
$info=Database::ReadoneRow($sql,$conn,array($tel,$yzm));
if(!$info){
	alertExitHtml("验证码错误");
}
if($info['create_ts']<$min_ago){
	alertExitHtml("验证码已过期");
}
$sql="UPDATE zjzz_yzm set is_use=1 where sjhm=?";
@Database::Update_pre($sql,$conn,array($tel));
$bmd_sql="UPDATE zjzz_dhbmd SET yz_ts=?,sf_yz='1',yzm=?,yzsj=? where xh=?";
$arr=array($now,$yzm,$now,$xh);
@Database::Update_pre($bmd_sql,$conn,$arr);
$xs_sql="SELECT count(*) from zjzz_xs WHERE dhbmd_id=?";
$have=Database::ReadoneStr($xs_sql,$conn,array($info['id']));
if($have==0){
//des加密二维码
	require_once $dii_ctx_root_dir . '/include/DES.php';
	include_once $dii_ctx_root_dir . '/include/phpqrcode.php';
	$des = new Crypt_DES();
	$des->setKey('zjzz_zkey');
	$ewm_str=$url."api/wap_use_stu_qd.php?wxid=$wxid&type=1&address=周末签到&gps=周末签到";//周末签到二维码字符串
	$str = base64_encode($des->encrypt($ewm_str));
	$filePath=scerweima($str);
	$url = 'http://xs.17189.net/api/' . $filePath;
	$sql="INSERT INTO zjzz_xs VALUES (?,?,?,?,?)";
	@Database::InsertOrUpdate($sql,$conn,array(NULL,$user['id'],$wxid,$now,$url));
}
echo json_encode(array('state'=>'true'));