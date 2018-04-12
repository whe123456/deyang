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
checkRequestKeyHtml("username", "用户名不能为空");
$username = trim($_REQUEST['username']);
$page=empty($_REQUEST['page'])?0:$_REQUEST['page']-1;
$BjName=empty($_REQUEST['BjName'])?'':$_REQUEST['BjName'];
$ClassTeach=empty($_REQUEST['ClassTeach'])?'':$_REQUEST['ClassTeach'];
$where=' zb.`js_bm`=zj.`js_bm`';
if($BjName!=''){
    $where.=" AND zb.bj_mc LIKE '%$BjName%'";
}
if($ClassTeach!=''){
    $where.=" AND zj.`xm` LIKE '%$ClassTeach%'";
}
$page_count=10;
$conn=Database::Connect();
$sql="SELECT * from zjzz_js where js_bm=?";
$user=Database::ReadoneRow($sql,$conn,array($username));
if(!$user){
    alertExitHtml("无此用户");
}
if($user['js_id']=='1'){
    $where.=" AND zj.`js_bm`= '".$_REQUEST['username']."'";
}
$count=Database::ReadoneStr("SELECT count(*) FROM zjzz_bj zb,zjzz_js zj WHERE $where",$conn,array());
$qz_count=$page*$page_count;
$sql="SELECT zb.*,zj.`xm` FROM zjzz_bj zb,zjzz_js zj WHERE $where ORDER BY id DESC LIMIT $qz_count,$page_count";
$user_list=Database::Readall($sql,$conn,array());
echo json_encode(array('state'=>'true','list'=>$user_list,'count'=>$count,'page_size'=>$page_count));