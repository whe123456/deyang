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
$id=empty($_REQUEST['id'])?'':$_REQUEST['id'];
$form=empty($_REQUEST['form'])?json_encode(array()):$_REQUEST['form'];
$form=json_decode($form,true);
if(count($form)==0){
    alertExit('请输入班级信息');
}
if(empty($form['bjbm'])||empty($form['xm'])||empty($form['sjhm'])||empty($form['bj_mc'])||empty($form['js_bh'])||empty($form['grade'])){
    alertExit('请输入完整白名单信息');
}
$xh=empty($form['xh'])?'':$form['xh'];
$conn=Database::Connect();
$now=date('Y-m-d H:i:s');
if($id!=''){
    $sql="UPDATE zjzz_dhbmd SET sjhm=?,xm=?,cj_time=?,sf_yz=?,yzm=?,yzsj=?,bjbm=?,xh=?,bj_mc=?,js_bh=?,grade=? WHERE id=?";
    $arr=array($form['sjhm'],$form['xm'],$now,'0','','',$form['bjbm'],$xh,$form['bj_mc'],$form['js_bh'],$form['grade'],$id);
    $msg='白名单信息修改成功';
    Database::Update_pre($sql,$conn,$arr);
}else{
    if($xh!='') {
        $sql = "SELECT COUNT(*) FROM zjzz_dhbmd WHERE xh=?";
        $sfcz = Database::ReadoneStr($sql, $conn, array($xh));
        if ($sfcz > 0) {
            alertExit('已有该学号白名单');
        }
    }
    $sql="INSERT INTO zjzz_dhbmd VALUES('',?,?,?,?,?,?,?,?,?,?,?,?)";
    $msg='白名单信息增加成功';
    Database::InsertOrUpdate($sql,$conn,array($form['sjhm'],$form['xm'],$now,$now,'0','','',$form['bjbm'],$xh,$form['bj_mc'],$form['js_bh'],$form['grade']));
}
echo json_encode(array('state'=>'true','msg'=>$msg));