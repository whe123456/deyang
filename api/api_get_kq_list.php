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
$page=empty($_REQUEST['page'])?0:$_REQUEST['page']-1;
$bj_mc=empty($_REQUEST['bj_mc'])?'':$_REQUEST['bj_mc'];
$kq_lx=!isset($_REQUEST['kq_lx'])?'':$_REQUEST['kq_lx'];
$kq_sj=empty($_REQUEST['kq_sj'])?array():$_REQUEST['kq_sj'];
$xm=empty($_REQUEST['xm'])?'':$_REQUEST['xm'];
$sjhm=empty($_REQUEST['sjhm'])?'':$_REQUEST['sjhm'];
$where=' zk.xs_id=zb.id';
if($bj_mc!=''){
    $where.=" AND zb.bj_mc like '%$bj_mc%'";
}
if($xm!=''){
    $where.=" AND zb.xm like '%$xm%'";
}
if($sjhm!=''){
    $where.=" AND zb.sjhm LIKE '%$sjhm%'";
}
if($kq_lx!=''){
    $where.=" AND zk.kq_lx='$kq_lx'";
}
if(count($kq_sj)>0){
    $where.=" AND zk.create_ts>'".$kq_sj[0]."' AND zk.create_ts<'".$kq_sj[1]."'";
}
$page_count=10;
$conn=Database::Connect();
$count=Database::ReadoneStr("SELECT count(*) FROM zjzz_kq zk,zjzz_dhbmd zb WHERE $where ",$conn,array());
$qz_count=$page*$page_count;
$sql="SELECT zk.*,zb.xm,zb.bj_mc from zjzz_kq zk,zjzz_dhbmd zb where $where ORDER BY zk.id DESC LIMIT $qz_count,$page_count";
$user_list=Database::Readall($sql,$conn,array());
echo json_encode(array('state'=>'true','user'=>$user_list,'count'=>$count,'page_size'=>$page_count));