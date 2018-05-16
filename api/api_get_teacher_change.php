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
$id=empty($_REQUEST['id'])?'':$_REQUEST['id'];
$conn=Database::Connect();
$js_list=array();
if($id!=''){
    $js_list=Database::ReadoneRow("SELECT * FROM zjzz_js WHERE id=$id",$conn,array());
}
$sql="SELECT id,name FROM zjzz_juese";
$list=Database::Readall($sql,$conn,array());
echo json_encode(array('state'=>'true','list'=>$list,'bmd'=>$js_list));