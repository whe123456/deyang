<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Method:POST,GET");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Content-type: text/html; charset=utf-8");
include_once dirname(__FILE__) . '/../../config/setting.php';
include_once($dii_ctx_root_dir . '/include/function.php');
include_once $dii_ctx_root_dir . '/include/class.Database.php';
include_once $dii_ctx_root_dir . '/include/code.php';
include_once $dii_ctx_root_dir . '/api/export/phpExcel/Classes/PHPExcel.php';
if(!isset($_SESSION)){
    session_start();
}
if(empty($_REQUEST['username'])){
    echo '用户名不能为空';exit;
}
$date=empty($_REQUEST['time'])?'':$_REQUEST['time'];
$rw_id=$task['1']['task']['1']['rw_id'];
$where="ut.`userid`=cl.`userid` AND cl.rw_id=$rw_id ";
if($date!=''){
    $where.="AND cl.create_ts LIKE '$date%'";
}
$conn=Database::Connect();
$arr=array();
$jblist=Database::Readall("SELECT cl.id,cl.`userid`,ut.`sjhm` FROM coin_log cl,user_t ut WHERE $where order by cl.id desc",$conn,$arr);
$excelData=array();
$excel = new PHPExcel();
$letter = array('A','B','C','D','E','F','F','G');
foreach ($jblist as $key => $value) {
    $excelData[$key]=array($value['id'] ,$value['userid'],$value['sjhm']);
}
$tableheader = array('序号','云驾号','手机号码');

for($i = 0;$i < count($tableheader);$i++) {
    $excel->getActiveSheet()->setCellValue("$letter[$i]1","$tableheader[$i]");
}


//表格数组
$data = $excelData;
//填充表格信息
for ($i = 2;$i <= count($data) + 1;$i++) {
    $j = 0;
    foreach ($data[$i - 2] as $key=>$value) {
        $excel->getActiveSheet()->setCellValue("$letter[$j]$i","$value");
        $j++;

    }
}

//创建Excel输入对象

$write = new PHPExcel_Writer_Excel5($excel);
header("Pragma: public");
header("Expires: 0");
header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
header("Content-Type:application/force-download");
header("Content-Type:application/vnd.ms-execl");
header("Content-Type:application/octet-stream");
header("Content-Type:application/download");;
header('Content-Disposition:attachment;filename="'.time().'.xls"');
header("Content-Transfer-Encoding:binary");
$write->save('php://output');

exit;