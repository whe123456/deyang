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
$where='  sidebar_icon where 1=1';
if($name!=''){
    $where.=" AND title LIKE '%$name%'";
}
$conn=Database::Connect();
$page_count=10;
$conn=Database::Connect();
$count=Database::ReadoneStr("SELECT count(*) FROM$where",$conn,array());
$qz_count=$page*$page_count;
$sql="SELECT * from$where ORDER BY `order` desc,id DESC";
$icon=Database::Readall($sql,$conn,array());
foreach ($icon as $key => $value) {
	$icon[$key]['parent_name']='无';
	if($value['parent']!=0){
	$icon[$key]['parent_name']=Database::ReadoneStr("SELECT title from sidebar_icon where `index`=?",$conn,array($value['parent']));
	}
}
echo json_encode(array('state'=>'true','list'=>$icon,'count'=>$count,'page_size'=>$page_count));