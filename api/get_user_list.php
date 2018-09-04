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
checkRequestKeyHtml("phone", "手机号码不能为空");
$phone=$_REQUEST['phone'];
$conn=Database::Connect();

$page_count=20;
$sql="SELECT zdb.* FROM zjzz_dhbmd zdb where zdb.sjhm=?";
$info=Database::ReadoneRow($sql,$conn,array($phone));
if(!$info){
    alertExit('非法访问');
}

$sql="select xm from zjzz_js where find_in_set(?, bjbm)";
$teacher_name=Database::ReadoneStr($sql,$conn,array($info['bjbm']));
$now=date('Y-m-d H:i:s');

//kq_lx0日常1周末
$sql="SELECT * from zjzz_kq where xs_id=? ORDER BY id DESC LIMIT $page_count";
$kq_list=Database::Readall($sql,$conn,array($info['id']));

//sf_ty0待审核-1不同意1同意
$sql="SELECT * FROM zjzz_qj WHERE xs_id=? ORDER BY id DESC LIMIT $page_count";
$qj_list=Database::Readall($sql,$conn,array($phone));
echo json_encode(array('state'=>0,'stu_info'=>array('name'=>$info['xm'],'sex'=>$info['sex'],'photo'=>$info['photo'],'bj_mc'=>$info['bj_mc'],'teacher_name'=>$teacher_name),'kq_list'=>$kq_list,'qj_list'=>$qj_list));