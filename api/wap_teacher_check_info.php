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
include_once $dii_ctx_root_dir . '/config/config_upyun.php';
include_once $dii_ctx_root_dir . '/config/upyun.class.php';
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
$filePath=scerweima('id='.$id);
$upyun = new UpYun($config_file['bucket'], $config_file['user_name'], $config_file['pwd'],UpYun::ED_TELECOM, 6000);
$upyunpicpath = '/dy_ewm/'.$filePath;
if($filePath) {
    $fh = @fopen($filePath, 'rb');
    if ($fh) {
        $rsp = $upyun->writeFile($upyunpicpath, $fh, true);
        @fclose($fh);
        @unlink($filePath);
        if ($rsp) {
            $url = $upyun_host_file . $upyunpicpath;
        }else{
            alertExitHtml("系统错误");
        }
    }
}
$sql="UPDATE zjzz_qj SET sf_ty=?,sh_yj=?,ewm_url=?,sh_sj=? WHERE id=?";
$user=Database::Update_pre($sql,$conn,array($zt,$yj,$url,$now,$id));
if(!$user){
    alertExitHtml("无此学号信息");
}
$info['stu_xm']=$user;
echo json_encode(array('state'=>'true','info'=>$info));