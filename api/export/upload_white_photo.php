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
if(!isset($_SESSION)){
    session_start();
}
$ii='file';
if(array_key_exists($ii, $_FILES)===false){
    exit;
}else {
    foreach ($_FILES as $f) {
        $aa = $f["name"];
        $aa = explode(".", $aa);
        $extension = end($aa);
        $file = fopen($f['tmp_name'], "rb");
        $bin = fread($file, 2); //只读2字节
        fclose($file);
        $strInfo = @unpack("C2chars", $bin);
        $typeCode = intval($strInfo['chars1'] . $strInfo['chars2']);
        if (!in_array($typeCode, $all_upload_file_type_arry)) {
            alertExit('上传文件类型不合法');
        }
    }
    $namefile = explode('.', $_FILES[$ii]['name']);


    $filePath = 'user_photo/' . date('YmdHis') . rand(0000, 9999) . '.' . end($namefile);
    $aa = $_FILES[$ii]['tmp_name'];
    move_uploaded_file($aa, $filePath);
    echo json_encode(array('state'=>'true','url'=>'http://xs.17189.net/api/export/'.$filePath));
}