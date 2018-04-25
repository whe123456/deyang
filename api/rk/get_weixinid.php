<?php
if(!isset($_SESSION)){
    session_start();
}
$appid="wxfee467cf675f5006";
$secret="c40e72d98ddac3b6a80e4f2817495124";


$urlwx = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
$url=urlencode('http://'.$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI']);

if(!isset($_GET['code'])){
    header("Location:".sprintf($urlwx,$url));exit;
}

// $sss=$_SESSION['wx_oauth2'];
// $json=json_decode($sss,true);

// if( $_SESSION['wx_oauth2']!=""&&$json['openid']!=""){

// 	echo "session";
// 	var_dump($_SESSION['wx_oauth2']);
// 	exit;

// echo 1111;

// }else{
$api2data="";


$code= $_GET['code'];
$api1="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=".$code."&grant_type=authorization_code";
$api1data =file_get_contents($api1);


$api1_arry = json_decode($api1data,true);


//  var_dump($api1_arry);



$api2="https://api.weixin.qq.com/sns/userinfo?access_token=".
    $api1_arry['access_token']."&openid=".$api1_arry['openid'];

$api2data =file_get_contents($api2);

$_SESSION['wx_oauth2']=$api2data;

//     echo "request";
//   var_dump($api2data);
//   exit;

// 	echo "请求错误！";
// 	exit;


// }

?>
