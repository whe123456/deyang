<?php
if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS'){
    exit;
}
$dii_ctx_root_dir = dirname(__FILE__).'/..';
date_default_timezone_set('Asia/Chongqing');
$now_time=date('Y-m-d H:i:s');

$configuration = array(
   'pdoDriver'=>'mysql',
   'user'		=> 'root',
   'pass'	=> '',
   'db'	=> 'Deyang_Occupation',
   'host'		=> 'localhost',
   'port'		=> '3306'
);
  $configuration = array(
      'pdoDriver'=>'mysql',
      'user'		=> 'root',
      'pass'	=> 'diipo_dev8',
      'db'	=> 'Deyang_Occupation',
      'host'		=> '192.168.0.195',
      'port'		=> '3306'
  );

$allow_upload_type_arry_all= array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/vnd.ms-excel'
);

//png    //gif   rar   //doc,xls zip//xlxs   //jpg
$all_upload_file_type_arry =array("13780","7173","8297","208207","8075","255216");

$allowedExts = array("xls","xlsx");


$appid="wxd56ee972e11b21a6";
$secret="f98d599cb80e68dfed81da27ef1a4b79";