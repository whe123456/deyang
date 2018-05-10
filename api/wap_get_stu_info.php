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
$conn=Database::Connect();
$sql="SELECT * FROM zjzz_xs WHERE wxid=?";
$xs=Database::ReadoneRow($sql,$conn,array($_REQUEST['wxid']));
if(!$xs){
    echo json_encode(array('state'=>'false','msg'=>'未注册'));exit;
}
$sql="SELECT * FROM zjzz_dhbmd WHERE id=?";
$bmd=Database::ReadoneRow($sql,$conn,array($xs['dhbmd_id']));
if(!$bmd){
    echo json_encode(array('state'=>'false'));exit;
}
$data=array(
    'xm'=>$bmd['xm'],
    'sjhm'=>$bmd['sjhm'],
);
echo json_encode(array('state'=>'true','data'=>$data));