<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Method:POST,GET");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS'){
    exit;
}
header("Content-type: text/html; charset=utf-8");
include_once dirname(__FILE__) . '/../../config/setting.php';
include_once($dii_ctx_root_dir . '/include/function.php');
include_once $dii_ctx_root_dir . '/include/class.Database.php';
include_once $dii_ctx_root_dir . '/config/config_upyun.php';
include_once $dii_ctx_root_dir . '/config/upyun.class.php';
if(!isset($_SESSION)){
    session_start();
}
$now=date('Y-m-d H:i:s');
$ii='img';
if(array_key_exists($ii, $_FILES)===false){
    $array=array(
        'result'=>'1',
        'message'=>'系统错误'
    );
    echo json_encode($array);exit;
}else {
    $namefile = explode('.', $_FILES[$ii]['name']);
    $filePath = 'img/' . date('YmdHis') . rand(0000, 9999) . '.' . end($namefile);
    $aa = $_FILES[$ii]['tmp_name'];
    move_uploaded_file($aa, $filePath);

    $upyun = new UpYun($config_file['bucket'], $config_file['user_name'], $config_file['pwd'],UpYun::ED_TELECOM, 6000);
    $upyunpicpath = '/dy_img/'.$filePath;
    if($filePath) {
        $fh = @fopen($filePath, 'rb');
        if ($fh) {
            $rsp = $upyun->writeFile($upyunpicpath, $fh, true);
            @fclose($fh);
            @unlink($filePath);
            if ($rsp) {
                $url = $upyun_host_file . $upyunpicpath;
            }else{
                $array=array(
                    'result'=>'1',
                    'message'=>'系统错误'
                );
                echo json_encode($array);exit;
            }
        }
    }
}
$array=array(
    'result'=>'0',
    'message'=>'',
    'data'=>array(
        'url'=>$url
    )
);
echo json_encode($array);