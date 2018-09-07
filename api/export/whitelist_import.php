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
    $msg='完成导入;';
    foreach ($whitelist as $key => $value) {
        if ($value == '') {
            continue;
        }
        $sj = substr($value, 0, strlen($value) - 1);
        $grs = explode(',', $sj);
        $sqladd = '';
        $cl='';
        $name='';
        $bjmc='';
        $tel='';
        foreach ($grs as $k => $al) {
            if($k==7){
                $teacher=$al;
                continue;
            }
            if($k==6){
                $name=$al;
                continue;
            }
            if($k==8){
                $tel=$al;
                continue;
            }
            if($k==3){
                $bjbm=$al;
            }
            if($k==4){
                $bjmc=$al;
            }
            $sqladd .= '"' . $al . '",';
            if($k==0){
                $cl=$al;
            }
        }
        if(!$bjbm){
            alertExit('导入失败，无班级编码');
        }
        if(!$teacher){
            alertExit('导入失败，无教师编码');
        }
        if($teacher!='') {

            $bjsql="SELECT js_bm FROM zjzz_js WHERE find_in_set(?, bjbm)";
            $bjcz=Database::ReadoneStr($bjsql,$conn,array($bjbm));
            if($bjcz){
                if($bjcz!=$teacher) {
                    alertExit('导入失败，' . $bjmc . '已有管理教师');
                }
            }
            $sql="SELECT * FROM zjzz_js WHERE js_bm=?";
            $sfcz=Database::ReadoneRow($sql,$conn,array($teacher));
            if($sfcz){
                if($sfcz['bjbm']==''){
                    $sql="update zjzz_js set bjbm='$bjbm,' where js_bm=?";
                    Database::Update_pre($sql,$conn,array($teacher));
                }else{
                    if(!$bjcz) {
                        $sql = "update zjzz_js set bjbm=CONCAT(bjbm,'$bjbm,','') where js_bm=?";
                        Database::Update_pre($sql, $conn, array($teacher));
                    }
                }
            }else{
                $sql="INSERT INTO zjzz_js VALUES(NULL,?,?,?,?,?,?,?,?,?,?,?,?)";
                Database::InsertOrUpdate($sql,$conn,array($teacher,$name,md5('123456'),$tel,'',$now,'0',1,$bjbm.',',$bjmc,'',''));
            }
        }
        if($cl!='') {
            $sql = "SELECT COUNT(*) FROM zjzz_dhbmd WHERE sjhm=?";
            $count = Database::ReadoneStr($sql, $conn, array($cl));
            if ($count > 0) {
                $msg.='手机号码:'.$cl.'重复，未导入;';
                continue;
            }
        }

        $sql = "INSERT INTO zjzz_dhbmd (sjhm,xm,xh,bjbm,bj_mc,sex,js_bh,grade,cj_time,sf_yz,yzm,yzsj)VALUES($sqladd'','','$now','0','','')";
        $aac = Database::InsertOrUpdate($sql, $conn,array());
        if ($aac === false) {
            alertExit('导入失败，数据格式错误');
        }
    }
}
@unlink($filePath);
if($msg!='完成导入;'){
    echo json_encode(array('state' => 'false', 'msg' => $msg));
}else {
    echo json_encode(array('state' => 'true', 'msg' => $msg));
}