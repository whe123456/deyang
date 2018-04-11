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
$name=empty($_REQUEST['name'])?'':$_REQUEST['name'];
$xh=empty($_REQUEST['xh'])?'':$_REQUEST['xh'];
$bm=empty($_REQUEST['bj_bm'])?'':$_REQUEST['bj_bm'];
$where=' 1=1';
if($name!=''){
    $where.=" AND xm LIKE '%$name%' ";
}
if($xh!=''){
    $where.=" AND xh LIKE '%$xh%'";
}
if($bm!=''){
    $where.=" AND bjbm = '$bm'";
}
$page_count=10;
$conn=Database::Connect();
$count=Database::ReadoneStr("SELECT count(*) FROM zjzz_dhbmd WHERE $where",$conn,array());
$qz_count=$page*$page_count;
$sql="SELECT * FROM zjzz_dhbmd WHERE $where ORDER BY id DESC LIMIT $qz_count,$page_count";
$user_list=Database::Readall($sql,$conn,array());
echo json_encode(array('state'=>'true','list'=>$user_list,'count'=>$count,'page_size'=>$page_count));