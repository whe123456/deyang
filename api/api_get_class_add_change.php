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
$BjBm=empty($_REQUEST['BjBm'])?'':$_REQUEST['BjBm'];
$form=empty($_REQUEST['form'])?json_encode(array()):$_REQUEST['form'];
$form=json_decode($form,true);
if(count($form)==0){
    alertExit('请输入班级信息');
}
if(empty($form['bj_mc'])||empty($form['BjCode'])||empty($form['gl_teacher'])){
    alertExit('请输入完整班级信息');
}
$conn=Database::Connect();
if($BjBm!=''){
    $sql="UPDATE zjzz_Bj SET bj_mc=? ,js_bh=? ,js_bm=? ,bz=? WHERE bj_bm=?";
    $arr=array($form['bj_mc'],$form['js_bh'],$form['gl_teacher'],$form['desc'],$BjBm);
    $msg='信息修改成功';
    Database::Update_pre($sql,$conn,$arr);
}else{
    $sql="SELECT COUNT(*) FROM zjzz_Bj WHERE bj_bm=?";
    $sfcz=Database::ReadoneStr($sql,$conn,array($form['BjCode']));
    if($sfcz>0){
        alertExit('已有该教室编码');
    }
    $sql="INSERT INTO zjzz_Bj (bj_bm,bj_mc,js_bh,js_bm,bz)VALUES(?,?,?,?,?)";
    $msg='班级增加成功';
    Database::InsertOrUpdate($sql,$conn,array($form['BjCode'],$form['bj_mc'],$form['js_bh'],$form['gl_teacher'],$form['desc']));
}
echo json_encode(array('state'=>'true','msg'=>$msg));