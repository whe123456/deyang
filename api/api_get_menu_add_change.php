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
    alertExit('请输入菜单信息');
}
if(empty($form['name'])||empty($form['fj'])||empty($form['order'])){
    alertExit('请输入完整菜单信息');
}
$conn=Database::Connect();
$now=date('Y-m-d H:i:s');
if($id!=''){
    $sql="UPDATE sidebar_icon SET title=?,parent=?,`order`=? WHERE id=?";
    $arr=array($form['name'],$form['fj'],$form['order'],$id);
    $msg='菜单信息修改成功';
    Database::Update_pre($sql,$conn,$arr);
}
echo json_encode(array('state'=>'true','msg'=>$msg));