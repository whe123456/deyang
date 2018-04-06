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
$username=$_REQUEST['username'];
$conn=Database::Connect();
$qx_list=Database::ReadoneStr("SELECT zjs.qx_list from zjzz_js zj,zjzz_juese zjs where zj.js_bm=? and zjs.id=zj.js_id",$conn,array($username));
$qx_list=explode(',', $qx_list);
foreach ($qx_list as $key => $value) {
	$qx_list[$key]="'$value'";
}
$qx_list=implode(',', $qx_list);
$sql="SELECT * from sidebar_icon where parent=0";
$icon=Database::Readall($sql,$conn,array());
foreach($icon as $k=>$v){
	$arr=array($v['index']);
	$icon[$k]['subs']=Database::Readall("SELECT * from sidebar_icon where parent=? and `index` in ($qx_list)",$conn,$arr);
	if(count($icon[$k]['subs'])==0){
		unset($icon[$k]);
	}

}
echo json_encode(array('state'=>'true','list'=>$icon));