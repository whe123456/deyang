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
$js_bm = $_REQUEST['js_bm'];
$name = $_REQUEST['name'];
$tel = $_REQUEST['tel'];
$conn=Database::Connect();
$sql="SELECT * from zjzz_js where js_bm=?";
$user=Database::ReadoneRow($sql,$conn,array($js_bm));
if(!$user){
	alertExitHtml("无此教师编码信息");
}
if($name!=$user['xm']){
	alertExitHtml("教师姓名信息错误");
}
if($tel!=$user['sjhm']){
	alertExitHtml("教师电话信息错误");
}
$now=date('Y-m-d H:i:s');
$min_ago=date('Y-m-d H:i:s',strtotime( '-1 Minute'));
$sql="UPDATE zjzz_yzm set is_use=1 where sjhm=?";
@Database::Update_pre($sql,$conn,array($tel));
$sql="INSERT into zjzz_yzm VALUES (?,?,?,?,?)";
$code=rand(100000,999999);
$code=123456;
Database::InsertOrUpdate($sql,$conn,array(NULL,$code,$tel,$now,'0'));
echo json_encode(array('state'=>'true'));