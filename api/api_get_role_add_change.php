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
    alertExit('请输入角色信息');
}
if(empty($form['name'])||empty($form['tranfer'])||empty($form['ms'])){
    alertExit('请输入完整角色信息');
}
$form['tranfer']=implode(',', $form['tranfer']);
$conn=Database::Connect();
$now=date('Y-m-d H:i:s');
if($id!=''){
    $sql="UPDATE zjzz_juese SET name=?,qx_list=?,ms=? WHERE id=?";
    $arr=array($form['name'],$form['tranfer'],$form['ms'],$id);
    $msg='角色信息修改成功';
    Database::Update_pre($sql,$conn,$arr);
}else{
    $sql="INSERT INTO zjzz_juese VALUES(NULL,?,?,?,?)";
    $msg='角色信息增加成功';
    Database::InsertOrUpdate($sql,$conn,array($form['name'],$now,$form['tranfer'],$form['ms']));
}
echo json_encode(array('state'=>'true','msg'=>$msg));