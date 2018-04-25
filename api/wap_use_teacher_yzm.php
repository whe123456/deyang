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
checkRequestKeyHtml("js_bm", "教师编码不能为空");
checkRequestKeyHtml("name", "姓名不能为空");
checkRequestKeyHtml("tel", "手机号码不能为空");
checkRequestKeyHtml("yzm", "验证码不能为空");
checkRequestKeyHtml("dlmm", "登录密码不能为空");
checkRequestKeyHtml("wxid", "微信id不能为空");
$js_bm = $_REQUEST['js_bm'];
$name = $_REQUEST['name'];
$tel = $_REQUEST['tel'];
$yzm = $_REQUEST['yzm'];
$dlmm = $_REQUEST['dlmm'];
$wxid = $_REQUEST['wxid'];
$conn=Database::Connect();
$sql="SELECT * from zjzz_js where js_bm=?";
$user=Database::ReadoneRow($sql,$conn,array($js_bm));
if(!$user){
	alertExitHtml("无此教师编号信息");
}
if($name!=$user['xm']){
	alertExitHtml("教师姓名信息错误");
}
if($tel!=$user['sjhm']){
	alertExitHtml("教师电话信息错误");
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
$bmd_sql="UPDATE zjzz_js SET dl_mm=?,wxid=?,zc_ts=?,ewm_url=?,sf_zc=? WHERE js_bm=?";
$arr=array(md5($dlmm),$wxid,$now,'','1',$js_bm);
@Database::Update_pre($bmd_sql,$conn,$arr);
echo json_encode(array('state'=>'true'));