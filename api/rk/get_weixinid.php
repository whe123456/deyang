<?php
include_once '../../config/setting.php';
include_once $dii_ctx_root_dir.'/include/function.php';
include_once $dii_ctx_root_dir.'/include/class.Database.php';
if(!isset($_SESSION)){
    session_start();
}

$urlwx = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
$url='http://'.$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI'];
if(!isset($_GET['code'])){
    header("Location:".sprintf($urlwx,$url));exit;
}

$api2data="";

if (isset($_GET['code'])){
    $code= $_GET['code'];
    $api1="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=".$code."&grant_type=authorization_code";
    $api1data =file_get_contents($api1);

    $api1_arry = json_decode($api1data,true);
    $api2="https://api.weixin.qq.com/sns/userinfo?access_token=".
        $api1_arry['access_token']."&openid=".$api1_arry['openid'];

    $api2data =file_get_contents($api2);

    $_SESSION['wx_oauth2']=$api2data;

    $json = file_get_contents('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$api1_arry['access_token'].'&type=jsapi');
    $data = json_decode($json,true);
    $ticket = $data['ticket'];
    $_SESSION['ticket']=$ticket;


}else{
    header("Location:".sprintf($urlwx,$url));exit;

}

if($api2data==""){

    header("Location:".sprintf($urlwx,$url));exit;
}
$userdata = json_decode($api2data,true);

//var_dump($userdata);exit;
if($userdata['openid']==""){
    header("Location:".sprintf($urlwx,$url));exit;
}
$_SESSION['userinfo']=$userdata;


