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
$from = empty($_REQUEST['from'])?'wap':$_REQUEST['from'];
$conn=Database::Connect();
if($from=='wap') {
	checkRequestKeyHtml("wxid", "用户信息不能为空");
	$wxid = $_REQUEST['wxid'];
}else{
	checkRequestKeyHtml("username", "用户名不能为空");
	$user_name=$_REQUEST['username'];
	$sql="SELECT wxid FROM zjzz_js WHERE js_bm=?";
	$wxid=Database::ReadoneStr($sql,$conn,array($user_name));
	if(!$wxid){
		alertExitHtml("教师未绑定微信，请绑定后审核");
	}
}
checkRequestKeyHtml("id", "请假信息不能为空");
$id = $_REQUEST['id'];
checkRequestKeyHtml("zt", "审核状态不能为空");
$zt = empty($_REQUEST['zt'])?1:$_REQUEST['zt'];
$yj = empty($_REQUEST['yj'])?'':$_REQUEST['yj'];
$sql="SELECT zq.*,zj.xm,zj.wxid from zjzz_qj zq,zjzz_js zj WHERE zq.id=? AND zj.js_bm=zq.js_bm";
$info=Database::ReadoneRow($sql,$conn,array($id));
if(!$info){
    alertExitHtml("无此请假信息");
}
$now = date('Y-m-d H:i:s');	
if($info['sf_ty']==0) {
	if($info['sf_ty']!=0){
		alertExitHtml("已审核");
	}
	if($wxid!=$info['wxid']){
		alertExitHtml("无权审核");
	}
	$url = '';
	$sql = "UPDATE zjzz_qj SET sf_ty=?,sh_yj=?,ewm_url=?,sh_sj=? WHERE id=?";
	$user = Database::Update_pre($sql, $conn, array($zt, $yj, $url, $now, $id));
	if (!$user) {
		alertExitHtml("无此学生信息");
	}
	$wx = new JSSDK($appid, $secret);
	$token = $wx->getAccessToken1();
	$url_token = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $token;
	/*if ($zt == 1) {
		$sql = "select zd.xm,w.wxid from zjzz_dhbmd zd,zjzz_xs zx,wxid_b w where zd.sjhm=? and zx.dhbmd_id=zd.id and w.id=zx.wxid";
		$stuid = Database::ReadoneRow($sql, $conn, array($info['xs_id']));
		if ($stuid) {
			$post_data = array(
					'touser' => $stuid['wxid'],//推送给谁,openid
					'template_id' => $jgtz, //微信后台的模板信息id
					"url" => "http://xs.17189.net/api/tz_yd/stu_rk.php?id=".$id,
					"data" => array(
							"first" => array(
									"value" => "您好，您的请假申请已提交学生处审核！",
									"color" => "#173177"
							),
							"keyword1" => array(
									"value" => $stuid['xm'],
									"color" => "#173177"
							),
							"keyword2" => array(
									"value" => '同意',
									"color" => "#173177"
							),
							"keyword3" => array(
									"value" => $now,
									"color" => "#173177"
							),
							"remark" => array(
									"value" => "请及时查看哦！",
									"color" => "#173177"
							),
					)
			);
			$post_data = json_encode($post_data);
			post($url_token, $post_data);
		}
		$sql="SELECT w.wxid FROM zjzz_js zj,wxid_b w WHERE zj.js_id=4 AND w.id=zj.wxid";
		$openid=Database::Readall($sql,$conn,array());
		if(count($openid)>0){
			foreach($openid as $v){
				$post_data = array(
					'touser'=>$v['wxid'],//推送给谁,openid
					'template_id'=>$shtz, //微信后台的模板信息id
					"url"=>"http://xs.17189.net/api/rk/teacher.php",
					"data"=> array(
							"first" => array(
									"value"=>'学生请假申请二次审批',
									"color"=>"#173177"
							),
							"keyword1"=>array(
									"value"=>$now,
									"color"=>"#173177"
							),
							"keyword2"=>array(
									"value"=>$info['qj_yy'],
									"color"=>"#173177"
							),
							"remark"=> array(
									"value"=>"请及时审批哦！",
									"color"=>"#173177"
							),
					)
				);
				$post_data = json_encode($post_data);
				post($url_token,$post_data);
			}
		}
//		$des = new Crypt_DES();
//		$des->setKey('98765432');
//		$ewm_str = $url . "api/mw_yz_ewm.php?id=$id&type=sm";//周末签到二维码字符串
//		$str = base64_encode($des->encrypt($ewm_str));
//		$filePath = scerweima($str);
//		$url = 'http://xs.17189.net/api/' . $filePath;
	} else {
		$sql = "select zd.xm,w.wxid from zjzz_dhbmd zd,zjzz_xs zx,wxid_b w where zd.sjhm=? and zx.dhbmd_id=zd.id and w.id=zx.wxid";
		$openid = Database::ReadoneRow($sql, $conn, array($info['xs_id']));
		if ($openid) {
			$post_data = array(
					'touser' => $openid['wxid'],//推送给谁,openid
					'template_id' => $jgtz, //微信后台的模板信息id
					"url" => "http://xs.17189.net/api/tz_yd/stu_rk.php?id=".$id,
					"data" => array(
							"first" => array(
									"value" => "您好，您的请假申请未通过！",
									"color" => "#173177"
							),
							"keyword1" => array(
									"value" => $openid['xm'],
									"color" => "#173177"
							),
							"keyword2" => array(
									"value" => '不同意',
									"color" => "#173177"
							),
							"keyword3" => array(
									"value" => $now,
									"color" => "#173177"
							),
							"remark" => array(
									"value" => "请及时查看哦！",
									"color" => "#173177"
							),
					)
			);
			$post_data = json_encode($post_data);
			post($url_token, $post_data);
		}
	}*/

	$info['stu_xm'] = $user;
}else{
	if($info['jdc_ty']!=0){
		alertExitHtml("已审核");
	}
	$sql="SELECT * FROM zjzz_js WHERE wxid=?";
	$js=Database::ReadoneRow($sql,$conn,array($wxid));
	if($js['js_id']!=4){
		alertExitHtml("仅学生处教师可审核");
	}
	$url = '';
	$msg='不同意';
	$info_msg="您好，您的请假申请未通过！";
	if ($zt == 1) {
		$msg='同意';
		$info_msg="您好，您的请假已审批！";
		// $des = new Crypt_DES();
		// $des->setKey('');
		$ewm_str = $url . "api/mw_yz_ewm.php?id=$id&type=sm";//周末签到二维码字符串
		// $str = base64_encode($des->encrypt($ewm_str));
		$str="bbb".$id;
		$filePath = scerweima($str);
		$url = 'http://xs.17189.net/api/' . $filePath;
	}
	$jdc_bm=Database::ReadoneStr("SELECT js_bm FROM zjzz_js WHERE wxid=?",$conn,array($wxid));
	$sql = "UPDATE zjzz_qj SET jdc_ty=?,jdc_yj=?,ewm_url=?,ec_sj=?,jdc_teacher=? WHERE id=?";
	$user = Database::Update_pre($sql, $conn, array($zt, $yj, $url, $now,$jdc_bm, $id));
	if (!$user) {
		alertExitHtml("无此学生信息");
	}
	$sql = "select zd.xm,w.wxid from zjzz_dhbmd zd,zjzz_xs zx,wxid_b w where zd.sjhm=? and zx.dhbmd_id=zd.id and w.id=zx.wxid";
	$openid = Database::ReadoneRow($sql, $conn, array($info['xs_id']));
	/*if ($openid) {
		$wx = new JSSDK($appid, $secret);
		$token = $wx->getAccessToken1();
		$url_token = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $token;
		$post_data = array(
				'touser' => $openid['wxid'],//推送给谁,openid
				'template_id' => $jgtz, //微信后台的模板信息id
				"url" => "http://xs.17189.net/api/tz_yd/stu_rk.php?id=".$id,
				"data" => array(
						"first" => array(
								"value" => $info_msg,
								"color" => "#173177"
						),
						"keyword1" => array(
								"value" => $openid['xm'],
								"color" => "#173177"
						),
						"keyword2" => array(
								"value" => $msg,
								"color" => "#173177"
						),
						"keyword3" => array(
								"value" => $now,
								"color" => "#173177"
						),
						"remark" => array(
								"value" => "请及时查看哦！",
								"color" => "#173177"
						),
				)
		);
		$post_data = json_encode($post_data);
		post($url_token, $post_data);
	}*/
}
echo json_encode(array('state'=>'true'));