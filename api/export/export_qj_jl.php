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
$sf_ty=!isset($_REQUEST['sf_ty'])?'':$_REQUEST['sf_ty'];
$kq_sj=empty($_REQUEST['kq_sj'])?array():$_REQUEST['kq_sj'];
$xm=empty($_REQUEST['xm'])?'':$_REQUEST['xm'];
$where=' FROM zjzz_qj zk,zjzz_dhbmd zb,zjzz_js zj WHERE zk.xs_id=zb.xh and zk.js_bm=zj.js_bm';
if($bj_mc!=''){
    $where.=" AND zb.bj_mc like '%$bj_mc%'";
}
if($xm!=''){
    $where.=" AND zb.xm like '%$xm%'";
}
if($sf_ty!=''){
    $where.=" AND zk.sf_ty='$sf_ty'";
}
if(count($kq_sj)>0){
    $where.=" AND zk.create_ts>'".$kq_sj[0]."' AND zk.create_ts<'".$kq_sj[1]."'";
}
$conn=Database::Connect();
$sql="SELECT zk.*,zb.xm,zb.bj_mc,zj.xm as js_xm  $where ORDER BY zk.id DESC";
$user_list=Database::Readall($sql,$conn,array());

$excelData=array();
$excel = new PHPExcel();
$letter = array('A','B','C','D','E','F','F','G','H','I');
foreach ($user_list as $key => $value) {
    if($value['sf_ty']==0){
        $sf_ty='等待审核';
    }elseif($value['sf_ty']==1){
        $sf_ty='同意';
    }else{
        $sf_ty='不同意';
    }
    $excelData[$key]=array($value['id'] ,$value['bj_mc'],$value['xm'],$value['js_xm'],$value['qj_yy'],$sf_ty,$value['sh_yj'],$value['qj_sj'],$value['create_ts'],$value['sh_sj']);
}
$tableheader = array('序号','班级','姓名','教师姓名','请假原因','审核状态','审核意见','请假时间','申请时间','审核时间');

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




