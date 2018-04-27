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
$sql="SELECT * FROM zjzz_qj WHERE id=?";
$info=Database::ReadoneRow($sql,$conn,array($id));
if(!$info){
    alertExit('非法访问');
}
if($info['sf_ty']!='1'){
    alertExit('此申请未被同意');
}
$qj_sj=explode('.',$info['qj_sj']);
$sql="SELECT count(*) FROM saoma_list WHERE qj_id=?";
$list=Database::ReadoneStr($sql,$conn,array($id));
$now=date('Y-m-d H:i:s');
if($list==0){
    if(time()<strtotime($qj_sj[0])){
        $msg='于请假开始前离校';
    }
}else{
    if(time()>strtotime($qj_sj[1])){
        $msg='返校迟到';
    }
}
$sql="INSERT INTO saoma_list VALUES (?,?,?)";
Database::InsertOrUpdate($sql,$conn,array(NULL,$id,$now));
echo json_encode(array('state'=>0,'msg'=>$msg));