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
if(empty($form['bj_mc'])){
    alertExit('请输入完整班级信息');
}
$conn=Database::Connect();
if($id!=''){
    $sql="UPDATE grade SET `name`=? ,state=? WHERE id=?";
    $arr=array($form['bj_mc'],'1',$id);
    $msg='信息修改成功';
    Database::Update_pre($sql,$conn,$arr);
}else{
    $sql="SELECT COUNT(*) FROM grade WHERE name=?";
    $sfcz=Database::ReadoneStr($sql,$conn,array($form['bj_mc']));
    if($sfcz>0){
        alertExit('已有该年级名称');
    }
    $sql="INSERT INTO grade (`name`,create_ts,state)VALUES(?,?,?)";
    $msg='班级增加成功';
    Database::InsertOrUpdate($sql,$conn,array($form['bj_mc'],date('Y-m-d H:i:s'),'1'));
}
echo json_encode(array('state'=>'true','msg'=>$msg));