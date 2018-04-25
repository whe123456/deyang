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
checkRequestKeyHtml("wxid", "用户信息不能为空");
checkRequestKeyHtml("latitude", "纬度不能为空");
checkRequestKeyHtml("longitude", "经度不能为空");
$wxid = $_REQUEST['wxid'];
$latitude = $_REQUEST['latitude'];
$longitude = $_REQUEST['longitude'];
$url="http://restapi.amap.com/v3/geocode/regeo?key=e588a56fc21fb3742b41edb940188b93&location=$longitude,$latitude&radius=1000&extensions=base&batch=false&roadlevel=0";
$info=file_get_contents($url);
$info=json_decode($info,true);
if($info['infocode']!='10000'){
	alertExitHtml($info['info']);
}
echo json_encode(array('state'=>'true','now_wz'=>$info['regeocode']['formatted_address']));
