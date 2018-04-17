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
$wxid = $_REQUEST['wxid'];
$conn=Database::Connect();
$sql="SELECT zdb.xh,zx.ewm_url FROM zjzz_xs zx,zjzz_dhbmd zdb where zx.wxid=? and zx.dhbmd_id=zdb.id";
$user=Database::ReadoneRow($sql,$conn,array($wxid));
if(!$user){
	alertExitHtml("无此学号信息");
}
$now=date('Y-m-d');
$sql="SELECT count(*) from zjzz_kq where xs_id=? and kq_lx=0 and create_ts like	'$now%'";
$jrkq=Database::ReadoneStr($sql,$conn,array($user['xh']));
//$sql="SELECT count(*) from zjzz_kq where xs_id=? and kq_lx=1 and create_ts like	'$now%'";
//$zmkq=Database::ReadoneStr($sql,$conn,array($user));
//if(isset($_SESSION['ticket']) && !empty($_SESSION['ticket'])){
//    $ticket = $_SESSION['ticket'];
//}else{
//    $re_json = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx9f455046e8c5ba6e&secret=e826241da2ca1fa08c821517320171c4');
//    $data = json_decode($re_json,true);
//    $access_token = $data['access_token'];
//    $json = file_get_contents('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi');
//    $data = json_decode($json,true);
//    $ticket = $data['ticket'];
//    $_SESSION['ticket']=$ticket;
//    $_SESSION['access_token']=$access_token;
//}
$url=$url.'#/today';
//$url='http://127.0.0.1:8180/projectPath/index.html';
$time=time();
$nons=getRandstr();

$re_data['noncestr'] = $nons;
$re_data['url'] = $url;
$re_data['timestamp'] =$time;
$re_data['ticket'] =$ticket;

$string = "jsapi_ticket=".$ticket."&noncestr=".$nons."&timestamp=".$time."&url=".$url."";
$sign = sha1($string);
//'zmkq'=>$zmkq,
echo json_encode(array('state'=>'true','jrkq'=>$jrkq,'str'=>$str,'ewm_url'=>$user['ewm_url'],'config'=>array('wxapp'=>$appid,'timestamp'=>$time,'Str'=>$nons,'sign'=>$sign)));
