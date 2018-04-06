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
$sjhm=empty($_REQUEST['phone'])?'':$_REQUEST['phone'];
include_once $dii_ctx_root_dir . '/class/DMemcache.php';
$me = new DMemcache();
$where = 'qx_hq!=0';
if ($date!='') {
    $date=explode(',',$date);
    $where .= " AND create_ts >'$date[0] 00:00:00' AND create_ts < '$date[1] 23:59:59'";
}
if(!empty($sjhm)){
    $where.=" and sjhm='$sjhm'";
}
$conn = Database::Connect();
$arr = array();
$sql="SELECT * FROM user_t WHERE $where ORDER BY id DESC";
$jblist = Database::Readall($sql, $conn, $arr);
if(!$jblist||count($jblist)==0){
    echo '无数据';exit;
}
$user = '';
foreach ($jblist as $k => $v) {
    $user .= "'" . $v['userid'] . "',";
}
$user = substr($user, 0, strlen($user) - 1);
$data = $me->get("user_register" . $user);
//$data=false;
if ($data === false) {
    $url = TOKEN_URL . "api50/icl/get_user_list_new.php";
    $data = post($url, array('u_list' => $user));
    $data = json_decode($data, true);
    if ($data['state'] != 'true') {
        alertExit($data['msg']);
    }
    foreach ($data['u_new'] as $key => $val) {
        $new = Database::ReadoneRow("SELECT id,create_ts FROM user_t WHERE userid=?", $conn, array($val['userid']));
        $data['u_new'][$key]['create_ts'] = $new['create_ts'];
        $data['u_new'][$key]['id'] = $new['id'];
        if ($val['clhp'] != '' && $val['clhp'] != null) {
            $data['u_new'][$key]['clhp'] = '川' . $val['clhp'];
        }
        if ($val['sex'] == null && $val['sex'] == '') {
            $val['sex'] = '1';
        }
        $data['u_new'][$key]['sex'] = $val['sex'] == '1' ? '男' : '女';
        if ($val['sr'] != null && $val['sr'] != '') {
            $data['u_new'][$key]['sr'] = birthday2(date('Y-m-d', strtotime($val['sr'])));
        }
    }
    $me->set("user_register" . $user, $data, 0, 0);
}
$excelData=array();
$excel = new PHPExcel();
$letter = array('A','B','C','D','E','F','F','G');
foreach ($data['u_new'] as $key => $value) {
    $excelData[$key]=array($value['id'] ,$value['userid'],$value['sjhm'],$value['clhp'],$value['sex'],$value['sr'],$value['region'],$value['create_ts']);
}
$tableheader = array('序号','云驾号','手机号码','车牌','性别','年龄','区域','创建时间');

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




