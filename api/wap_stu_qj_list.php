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
$sql="SELECT zdb.sjhm FROM zjzz_xs zx,zjzz_dhbmd zdb where zx.wxid=? and zx.dhbmd_id=zdb.id";
$user=Database::ReadoneStr($sql,$conn,array($wxid));
if(!$user){
	alertExitHtml("无此学生信息");
}
$where='';
if(!empty($_REQUEST['minid'])){
	$minid=$_REQUEST['minid'];
	$where.=" and zq.id<$minid";
}
$count_arr=Database::ReadoneStr("SELECT MIN(zq.id) FROM zjzz_qj zq WHERE zq.xs_id=? $where",$conn,array($user));
$arr=Database::Readall("SELECT zq.*,zj.xm FROM zjzz_qj zq,zjzz_js zj WHERE zq.xs_id=? AND zq.js_bm=zj.js_bm $where order by zq.id desc limit 0,3",$conn,array($user));
$more=true;
$minid='';
$list=array();
if(count($arr)>0){
	foreach ($arr as $key => $value) {
		if($value['id']==$count_arr){
			$more=false;
		}
		$minid=$value['id'];
		$date=explode('.', $value['qj_sj']);
		$list[$key]['title']=$value['qj_yy'];
		$list[$key]['id']=$value['id'];
		$list[$key]['list'][0]['label']='审批人';
		$list[$key]['list'][1]['label']='审批状态';
		$list[$key]['list'][2]['label']='请假标题';
		$list[$key]['list'][3]['label']='请假开始时间';
		$list[$key]['list'][4]['label']='请假结束时间';
		$list[$key]['list'][0]['value']=$value['xm'];
		$list[$key]['list'][1]['value']=$sf_ty[$value['sf_ty']];
		$list[$key]['list'][2]['value']=$value['qj_nr'];
		$list[$key]['list'][3]['value']=$date[0];
		$list[$key]['list'][4]['value']=$date[1];
	}
}
echo json_encode(array('state'=>'true','more'=>$more,'list'=>$list,'minid'=>$minid));