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
$bj_mc=empty($_REQUEST['bj_mc'])?'':$_REQUEST['bj_mc'];
$kq_lx=!isset($_REQUEST['kq_lx'])?'':$_REQUEST['kq_lx'];
$kq_sj=empty($_REQUEST['kq_sj'])?array():$_REQUEST['kq_sj'];
$xm=empty($_REQUEST['xm'])?'':$_REQUEST['xm'];
$sjhm=empty($_REQUEST['sjhm'])?'':$_REQUEST['sjhm'];
$where=' zk.xs_id=zb.id';
if($bj_mc!=''){
    $where.=" AND zb.bj_mc like '%$bj_mc%'";
}
if($xm!=''){
    $where.=" AND zb.xm like '%$xm%'";
}
if($sjhm!=''){
    $where.=" AND zb.sjhm LIKE '%$sjhm%'";
}
if($kq_lx!=''){
    $where.=" AND zk.kq_lx='$kq_lx'";
}
if(count($kq_sj)>0){
    $where.=" AND zk.create_ts>'".$kq_sj[0]."' AND zk.create_ts<'".$kq_sj[1]."'";
}
$conn=Database::Connect();
$sql="SELECT zk.*,zb.xm,zb.bj_mc from zjzz_kq zk,zjzz_dhbmd zb where $where ORDER BY zk.id DESC";
$user_list=Database::Readall($sql,$conn,array());

$excelData=array();
$excel = new PHPExcel();
$letter = array('A','B','C','D','E','F','F','G');
foreach ($user_list as $key => $value) {
    if($value['kq_lx']==0){
        $kq_lx='日常考勤';
    }else{
        $kq_lx='周末考勤';
    }
    $excelData[$key]=array($value['id'] ,$value['bj_mc'],$value['xm'],$kq_lx,$value['create_ts'],$value['kq_dz']);
}
$tableheader = array('序号','班级','姓名','考勤类型','考勤时间','考勤地址');

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




