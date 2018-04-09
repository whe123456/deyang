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
$now=date('Y-m-d H:i:s');
$ii='file';
if(array_key_exists($ii, $_FILES)===false){
    exit;
}else {
    foreach ($_FILES as $f) {
        $aa = $f["name"];
        $aa = explode(".", $aa);
        $extension = end($aa);
        if (!in_array($extension, $allowedExts)) {
            alertExit('上传文件类型不合法');
        }
        $file = fopen($f['tmp_name'], "rb");
        $bin = fread($file, 2); //只读2字节
        fclose($file);
        $strInfo = @unpack("C2chars", $bin);
        $typeCode = intval($strInfo['chars1'] . $strInfo['chars2']);
        if (!in_array($typeCode, $all_upload_file_type_arry)) {
            alertExit('上传文件类型不合法');
        }
        if (!in_array($f['type'], $allow_upload_type_arry_all)) {
            alertExit('上传文件类型不合法');
        }
    }
    $namefile = explode('.', $_FILES[$ii]['name']);


    $filePath = 'upload/' . date('YmdHis') . rand(0000, 9999) . '.' . end($namefile);
    $aa = $_FILES[$ii]['tmp_name'];
    move_uploaded_file($aa, $filePath);
    include './phpExcel/Classes/PHPExcel.php';
    $PHPExcel = new PHPExcel();
    /**默认用excel2007读取excel，若格式不对，则用之前的版本进行读取*/
    $PHPReader = new PHPExcel_Reader_Excel2007();
    if (!$PHPReader->canRead($filePath)) {
        $PHPReader = new PHPExcel_Reader_Excel5();
        if (!$PHPReader->canRead($filePath)) {
            echo 'no Excel';
            return;
        }
    }

    $PHPExcel = $PHPReader->load($filePath);
    /**读取excel文件中的第一个工作表*/
    $currentSheet = $PHPExcel->getSheet(0);
    /**取得最大的列号*/
    $allColumn = $currentSheet->getHighestColumn();
    /**取得一共有多少行*/
    $allRow = $currentSheet->getHighestRow();
    /**从第二行开始输出，因为excel表中第一行为列名*/
    $aa = '';
    for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
        /**从第A列开始输出*/
        for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {
            $val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65, $currentRow)->getValue();
            /**ord()将字符转为十进制数*/
            $aa .= $val . ",";
            /**如果输出汉字有乱码，则需将输出内容用iconv函数进行编码转换，如下将gb2312编码转为utf-8编码输出*/
            // echo iconv('utf-8','gb2312', $val)."\t";
        }
        $aa .= ";";
    }
    $conn = Database::Connect();
    $whitelist = explode(';', $aa);
    $now=date('Y-m-d H:i:s');
    foreach ($whitelist as $key => $value) {
        if ($value == '') {
            continue;
        }
        $sj = substr($value, 0, strlen($value) - 1);
        $grs = explode(',', $sj);
        $sqladd = '';
        $cl='';
        foreach ($grs as $k => $al) {
            $sqladd .= '"' . $al . '",';
            if($k==0){
                $cl=$al;
            }
        }
        $sql="SELECT COUNT(*) FROM zjzz_bj WHERE bj_bm=?";
        $count=Database::ReadoneStr($sql,$conn,array($cl));
        if($count>0){
            continue;
        }
        $sql = "INSERT INTO zjzz_bj (bj_bm,bj_mc,js_bh,js_bm,bz)VALUES($sqladd'')";
        $aac = Database::InsertOrUpdate($sql, $conn,array());
        if ($aac === false) {
            alertExit('导入失败，数据格式错误');
        }
    }
}
@unlink($filePath);
echo json_encode(array('state'=>'true','msg'=>'完成导入'));