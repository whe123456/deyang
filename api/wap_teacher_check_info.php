<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Method:POST,GET");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Content-type: text/html; charset=utf-8");
include_once dirname(__FILE__) . '/../config/setting.php';
include_once($dii_ctx_root_dir . '/include/function.php');
include_once $dii_ctx_root_dir . '/include/class.Database.php';
include_once $dii_ctx_root_dir . '/include/code.php';
include_once $dii_ctx_root_dir . '/include/phpqrcode.php';
require_once $dii_ctx_root_dir . '/include/DES.php';
include_once $dii_ctx_root_dir.'/include/wx_function.php';
if(!isset($_SESSION)){
    session_start();
}
checkRequestKeyHtml("wxid", "用户信息不能为空");
$wxid = $_REQUEST['wxid'];
checkRequestKeyHtml("id", "请假信息不能为空");
$id = $_REQUEST['id'];
checkRequestKeyHtml("zt", "审核状态不能为空");
$zt = $_REQUEST['zt'];
$yj = empty($_REQUEST['yj'])?'':$_REQUEST['yj'];
$conn=Database::Connect();
$sql="SELECT zq.*,zj.xm from zjzz_qj zq,zjzz_js zj WHERE zq.id=? AND zj.js_bm=zq.js_bm AND zj.wxid=?";
$info=Database::ReadoneRow($sql,$conn,array($id,$wxid));
if(!$info){
    alertExitHtml("无此请假信息");
}
$now=date('Y-m-d H:i:s');
$url='';
$msg='不同意';
if($zt==1) {
	$msg='同意';
    $des = new Crypt_DES();
    $des->setKey('zjzz_zkey');
    $ewm_str = $url . "api/mw_yz_ewm.php?id=$id&type=sm";//周末签到二维码字符串
    $str = base64_encode($des->encrypt($ewm_str));
    $filePath = scerweima($str);
    $url = 'http://xs.17189.net/api/' . $filePath;
//    $upyun = new UpYun($config_file['bucket'], $config_file['user_name'], $config_file['pwd'], UpYun::ED_TELECOM, 6000);
//    $upyunpicpath = '/dy_ewm/' . $filePath;
//    if ($filePath) {
//        $fh = @fopen($filePath, 'rb');
//        if ($fh) {
//            $rsp = $upyun->writeFile($upyunpicpath, $fh, true);
//            @fclose($fh);
//            @unlink($filePath);
//            if ($rsp) {
//                $url = $upyun_host_file . $upyunpicpath;
//            } else {
//                alertExitHtml("系统错误");
//            }
//        }
//    }
}
$sql="UPDATE zjzz_qj SET sf_ty=?,sh_yj=?,ewm_url=?,sh_sj=? WHERE id=?";
$user=Database::Update_pre($sql,$conn,array($zt,$yj,$url,$now,$id));
if(!$user){
    alertExitHtml("无此学号信息");
}

$wx = new JSSDK($appid, $secret);
$token = $wx->getAccessToken1();
$url_token="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$token;
$sql="select zd.xm,w.wxid from zjzz_dhbmd zd,zjzz_xs zx,wxid_b w where zd.xh=? and zx.dhbmd_id=zd.id and w.id=zx.wxid";
$openid=Database::ReadoneRow($sql,$conn,array($info['xs_id']));
if($openid){
	$post_data = array(
	    'touser'=>$openid['wxid'],//推送给谁,openid
	    'template_id'=>$jgtz, //微信后台的模板信息id
	    "url"=>"http://xs.17189.net/api/rk/stu.php",
	    "data"=> array(
	    	"first" => array(
	    		"value"=>"您好，您的请假已审批！",
	    		"color"=>"#173177"
	    		),
	    	"keyword1"=>array(
		    	"value"=>$openid['xm'],
		    	"color"=>"#173177"
		    	),
	    	"keyword2"=>array(
	            "value"=>$msg,
	            "color"=>"#173177"
	        ),
	        "keyword3"=> array(
	            "value"=>date('Y-m-d H:i:s'),
	            "color"=>"#173177"
	        ),
	        "remark"=> array(
	            "value"=>"请及时查看哦！",
	            "color"=>"#173177"
	        ),
	    )
	);
	$post_data = json_encode($post_data);
	$info=post($url_token,$post_data);
}
$info['stu_xm']=$user;
echo json_encode(array('state'=>'true','info'=>$info));