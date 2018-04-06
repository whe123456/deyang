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
$xm=empty($_REQUEST['xm'])?'':$_REQUEST['xm'];
$bh=empty($_REQUEST['bh'])?'':$_REQUEST['bh'];
$where=' zjzz_js zj,zjzz_juese zjs where zj.js_id=zjs.id';
if($xm!=''){
    $where.=" AND zj.xm LIKE '%$xm%'";
}
if($bh!=''){
    $where.=" AND zj.js_bm LIKE '%$bh%'";
}
$conn=Database::Connect();
$sql="SELECT zj.*,zjs.`name` FROM $where ORDER BY zj.id DESC";
$user_list=Database::Readall($sql,$conn,array());
$excelData=array();
$excel = new PHPExcel();
$letter = array('A','B','C','D','E','F','F','G');
foreach ($user_list as $key => $value) {
    $excelData[$key]=array($value['id'] ,$value['js_bm'],$value['xm'],$value['sjhm'],$value['name'],$value['zc_ts']);
}
$tableheader = array('序号','教师编号','姓名','手机号码','角色','注册时间');

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




