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
    alertExit('请输入教师信息');
}
if(empty($form['classs'])||empty($form['js_bm'])||empty($form['xm'])||empty($form['sjhm'])){
    alertExit('请输入完整教师信息');
}
$bjbm=empty($form['bjbm'])?'':$form['bjbm'];
$bj_mc=empty($form['bj_mc'])?'':$form['bj_mc'];
$js_bh=empty($form['js_bh'])?'':$form['js_bh'];
$grade=empty($form['grade'])?'':$form['grade'];
$conn=Database::Connect();
$now=date('Y-m-d H:i:s');
if($id!=''){
    $sql="UPDATE zjzz_js SET xm=?,dl_mm=?,sjhm=?,wxid=?,zc_ts=?,ewm_url=?,sf_zc=?,js_id=?,bjbm=?,bj_mc=?,js_bh=?,grade=? WHERE id=?";
    $arr=array($form['xm'],md5('123456'),$form['sjhm'],'',$now,'','0',$form['classs'],$bjbm,$bj_mc,$js_bh,$grade,$id);
    $msg='教师信息修改成功';
    Database::Update_pre($sql,$conn,$arr);
}else{
    $sql="SELECT COUNT(*) FROM zjzz_js WHERE js_bm=?";
    $sfcz=Database::ReadoneStr($sql,$conn,array($form['js_bm']));
    if($sfcz>0){
        alertExit('已有该教师编号');
    }
    $sql="INSERT INTO zjzz_js VALUES(NULL,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $msg='教师信息增加成功';
    Database::InsertOrUpdate($sql,$conn,array($form['js_bm'],$form['xm'],md5('123456'),$form['sjhm'],'',$now,'','0',$form['classs'],$bjbm,$bj_mc,$js_bh,$grade));
}
echo json_encode(array('state'=>'true','msg'=>$msg));