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
checkRequestKeyHtml("id", "id不能为空");
checkRequestKeyHtml("type", "类型不能为空");
$id=$_REQUEST['id'];
$type=$_REQUEST['type'];
if($type!='sm'){
    alertExit('非法访问');
}
$conn=Database::Connect();
$sql="SELECT zq.*,zdb.xm,zdb.bj_mc,zdb.sex,zdb.photo,zdb.`grade` FROM zjzz_dhbmd zdb,zjzz_qj zq where zq.id=? and zdb.sjhm=zq.xs_id";
$info=Database::ReadoneRow($sql,$conn,array($id));
if(!$info){
    alertExit('非法访问');
}
if($info['sf_ty']!='1'){
    alertExit('此申请未被同意');
}
$now=date('Y-m-d H:i:s');
$qj_ts=str_replace(".","至",$info['qj_sj']);
$msg=$info['grade']." ".$info['bj_mc']." 手机号码".$info['xs_id']." ".$info['xm']." 请假时间".$qj_ts." 返校记录成功！";
$sql="INSERT INTO saoma_list VALUES (?,?,?)";
Database::InsertOrUpdate($sql,$conn,array(NULL,$id,$now));
echo json_encode(array('state'=>0,'msg'=>$msg,'stu_info'=>array('name'=>$info['xm'],'sex'=>$info['sex'],'photo'=>$info['photo'],'bj_mc'=>$info['bj_mc'])));