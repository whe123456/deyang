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
if(!isset($_SESSION)){
    session_start();
}
checkRequestKeyHtml("id", "id不能为空");
checkRequestKeyHtml("type", "类型不能为空");
$id=$_REQUEST['id'];
$type=$_REQUEST['type'];
if($type!='sm'){
    alertExit('非法访问');
}
$conn=Database::Connect();
$sql="SELECT zq.*,zdb.xm,zdb.bj_mc,zdb.sex,zdb.photo,zdb.`grade` FROM zjzz_dhbmd zdb,zjzz_qj zq where zq.id=? and zdb.sjhm=zq.xs_id";
$info=Database::ReadoneRow($sql,$conn,array($id));
if(!$info){
    alertExit('非法访问');
}
if($info['sf_ty']!='1'){
    alertExit('此申请未被同意');
}
if($info['jdc_ty']!='1'){
    alertExit('此申请未被同意');
}
if($info['state']==2){
    alertExit('此申请已完成');
}
if($info['fx_url']!=''){
    alertExit('该二维码已失效');
}
$now=date('Y-m-d H:i:s');
// $qj_ts=str_replace(".","至",$info['qj_sj']);
$qj_ts=explode(".", $info['qj_sj']);
$teacher=Database::ReadoneStr("select xm from zjzz_js where js_bm=?",$conn,array($info['js_bm']));
$jdc=Database::ReadoneStr("select xm from zjzz_js where js_bm=?",$conn,array($info['jdc_teacher']));

$msg=$info['grade']." ".$info['bj_mc']." 学生".$info['xm']." 手机号码".$info['xs_id']." 因 ".$info['qj_yy']." 需请假外出 预计出校时间".$qj_ts[0]." 预计返校时间".$qj_ts[1]." 班主任:".$teacher."已同意 学生处:".$jdc."已同意 离校记录成功！";
$sql="INSERT INTO saoma_list VALUES (?,?,?,?)";
Database::InsertOrUpdate($sql,$conn,array(NULL,$id,$now,1));
// $ewm_str = $url . "api/mw_yz_ewm.php?id=$id&type=sm";//周末签到二维码字符串
// $str = base64_encode($des->encrypt($ewm_str));
$str="ccc".$id;
$filePath = scerweima($str);
$url = 'http://xs.17189.net/api/' . $filePath;
$sql = "UPDATE zjzz_qj SET fx_url=? WHERE id=?";
$user = Database::Update_pre($sql, $conn, array($url, $id));
echo json_encode(array('state'=>0,'msg'=>$msg,'stu_info'=>array('name'=>$info['xm'],'sex'=>$info['sex'],'photo'=>$info['photo'],'bj_mc'=>$info['bj_mc'])));