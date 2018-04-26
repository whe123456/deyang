<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Method:POST,GET");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Content-type: text/html; charset=utf-8");
include_once dirname(__FILE__) . '/../config/setting.php';
include_once($dii_ctx_root_dir . '/include/function.php');
include_once $dii_ctx_root_dir . '/include/class.Database.php';
include_once $dii_ctx_root_dir . '/include/code.php';
include_once $dii_ctx_root_dir.'/include/wx_function.php';
if(!isset($_SESSION)){
    session_start();
}
checkRequestKeyHtml("wxid", "用户信息不能为空");
$wxid = $_REQUEST['wxid'];
checkRequestKeyHtml("array", "请假信息不能为空");
$array = json_decode($_REQUEST['array'],true);
if(empty($array['qj_bt'])||empty($array['value'])||empty($array['value1'])||empty($array['teacher'])){
	alertExitHtml("请填写完整信息");
}
$conn=Database::Connect();
$sql="SELECT zdb.xh FROM zjzz_xs zx,zjzz_dhbmd zdb where zx.wxid=? and zx.dhbmd_id=zdb.id";
$user=Database::ReadoneStr($sql,$conn,array($wxid));
if(!$user){
	alertExitHtml("无此学号信息");
}
$now=date('Y-m-d H:i:s');
$sql="INSERT into zjzz_qj VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
@Database::InsertOrUpdate($sql,$conn,array(NULL,$user,$array['value'].'.'.$array['value1'],$array['qj_bt'],$array['qj_nr'],$array['teacher'],'0','','',$now,$now,json_encode($array['xz_img']),'','','',$now));

$wx = new JSSDK($appid, $secret);
$token = $wx->getAccessToken1();
$url_token="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$token;
$sql="select w.wxid from zjzz_js zj,wxid_b w where zj.js_bm=? and w.id=zj.wxid";
$openid=Database::ReadoneStr($sql,$conn,array($array['teacher']));
if($openid){
	$post_data = array(
	    'touser'=>$openid,//推送给谁,openid
	    'template_id'=>$shtz, //微信后台的模板信息id
	    "url"=>"http://xs.17189.net/api/rk/teacher.php",
	    "data"=> array(
	    	"first" => array(
	    		"value"=>'学生请假申请',
	    		"color"=>"#173177"
	    		),
	    	"keyword1"=>array(
		    	"value"=>date('Y-m-d H:i:s'),
		    	"color"=>"#173177"
		    	),
	    	"keyword2"=>array(
	            "value"=>$array['qj_bt'],
	            "color"=>"#173177"
	        ),
	        "remark"=> array(
	            "value"=>"请及时审批哦！",
	            "color"=>"#173177"
	        ),
	    )
	);
	$post_data = json_encode($post_data);
	$info=post($url_token,$post_data);
}
echo json_encode(array('state'=>'true','msg'=>'申请已提交'));