<?php
if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS'){
    exit;
}
$dii_ctx_root_dir = dirname(__FILE__).'/..';
date_default_timezone_set('Asia/Chongqing');
$now_time=date('Y-m-d H:i:s');

if(!isset($_SESSION)){
    session_start();
}

// $configuration = array(
//    'pdoDriver'=>'mysql',
//    'user'		=> 'root',
//    'pass'	=> '',
//    'db'	=> 'Deyang_Occupation',
//    'host'		=> 'localhost',
//    'port'		=> '3306'
// );
//  $configuration = array(
//      'pdoDriver'=>'mysql',
//      'user'		=> 'root',
//      'pass'	=> 'diipo_dev8',
//      'db'	=> 'Deyang_Occupation',
//      'host'		=> '192.168.0.195',
//      'port'		=> '3306'
//  );
 $configuration = array(
     'pdoDriver'=>'mysql',
     'user'   => 'root',
     'pass' => 'Dyzj@1258521',
     'db' => 'Deyang_Occupation',
     'host'   => 'localhost',
     'port'   => '3306'
 );

$allow_upload_type_arry_all= array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/vnd.ms-excel'
);

//png    //gif   rar   //doc,xls zip//xlxs   //jpg
$all_upload_file_type_arry =array("13780","7173","8297","208207","8075","255216");

$allowedExts = array("xls","xlsx");


$appid="wx53b80126f0f8ab16";
$secret="e5202a5aff3bf1827172b469d2664fab";
$url='http://192.168.0.188:8880/';