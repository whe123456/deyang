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
$user_name=$_REQUEST['username'];
$page=empty($_REQUEST['page'])?0:$_REQUEST['page']-1;
$bj_mc=empty($_REQUEST['bj_mc'])?'':$_REQUEST['bj_mc'];
$sf_ty=!isset($_REQUEST['sf_ty'])?'':$_REQUEST['sf_ty'];
$kq_sj=empty($_REQUEST['kq_sj'])?array():$_REQUEST['kq_sj'];
$xm=empty($_REQUEST['xm'])?'':$_REQUEST['xm'];
$sjhm=empty($_REQUEST['sjhm'])?'':$_REQUEST['sjhm'];

$where=' FROM zjzz_qj zq,zjzz_dhbmd zb,zjzz_js zj WHERE zq.xs_id=zb.sjhm and zj.js_bm=?';
if($bj_mc!=''){
    $where.=" AND zb.bj_mc like '%$bj_mc%'";
}
if($xm!=''){
    $where.=" AND zb.xm like '%$xm%'";
}
if($sf_ty!=''){
    if($sf_ty==1){
        $where.=" AND zk.jdc_ty='$sf_ty'";
    }elseif($sf_ty==-1){
        $where.=" AND (zk.jdc_ty='$sf_ty' or zk.sf_ty='$sf_ty')";
    }
}
if($sjhm!=''){
    $where.=" AND zb.sjhm LIKE '%$sjhm%'";
}
if(count($kq_sj)>0){
    $where.=" AND zq.create_ts>'".$kq_sj[0]."' AND zq.create_ts<'".$kq_sj[1]."'";
}
$page_count=10;
$conn=Database::Connect();

$sql="SELECT js_bm,js_id FROM zjzz_js WHERE js_bm=?";
$user=Database::ReadoneRow($sql,$conn,array($user_name));
if(!$user){
    alertExitHtml("无此教师信息");
}

if($user['js_id']==4){
    $where .= " AND (zq.js_bm = zj.js_bm OR zq.jdc_teacher = zj.js_bm OR (zq.`sf_ty`=1 AND zq.`jdc_ty`=0))";
}else{
    $where .= " AND zq.js_bm=zj.js_bm";
}
$count=Database::ReadoneStr("SELECT count(*) $where ",$conn,array($user_name));
$qz_count=$page*$page_count;
$sql="SELECT zq.*,zb.xm,zb.bj_mc,zj.xm as js_xm $where  ORDER BY zq.id DESC LIMIT $qz_count,$page_count";
$user_list=Database::Readall($sql,$conn,array($user_name));
echo json_encode(array('state'=>'true','user'=>$user_list,'js_id'=>$user['js_id'],'count'=>$count,'page_size'=>$page_count));