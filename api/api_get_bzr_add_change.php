<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Method:POST,GET");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS'){
    exit;
}
header("Content-type: text/html; charset=utf-8");
include_once dirname(__FILE__) . '/../config/setting.php';
include_once($dii_ctx_root_dir . '/include/function.php');
include_once $dii_ctx_root_dir . '/include/class.Database.php';
include_once $dii_ctx_root_dir . '/include/code.php';
if(!isset($_SESSION)){
    session_start();
}
checkRequestKeyHtml("username", "用户名不能为空");
checkRequestKeyHtml("bjbm", "班级编码不能为空");
checkRequestKeyHtml("js_bm", "教师编号不能为空");
$jsbm=empty($_REQUEST['js_bm'])?'':$_REQUEST['js_bm'];
$bjbm=empty($_REQUEST['bjbm'])?'':$_REQUEST['bjbm'];
$conn=Database::Connect();
$now=date('Y-m-d H:i:s');
$info=Database::ReadoneRow("select bjbm,bj_mc from zjzz_dhbmd where bjbm=? order by id asc limit 1",$conn,array($bjbm));
if(!$info){
    alertExitHtml('无此班级信息');
}
$have=Database::ReadoneStr("select count(*) from zjzz_js where js_bm=?",$conn,array($jsbm));
if($have==0){
    alertExitHtml('无此教师信息');
}
$sql="update zjzz_js set bjbm='', bj_mc='' where bjbm=?";
$arr=array($bjbm);
$msg='班主任设置成功';
Database::Update_pre($sql,$conn,$arr);
$sql="update zjzz_js set bjbm=?, bj_mc=? where js_bm=?";
Database::Update_pre($sql,$conn,array($info['bjbm'],$info['bj_mc'],$jsbm));
echo json_encode(array('state'=>'true','msg'=>$msg));