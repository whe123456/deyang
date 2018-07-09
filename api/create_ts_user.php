<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Method:POST,GET");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Content-type: text/html; charset=utf-8");
include_once dirname(__FILE__) . '/../config/setting.php';
include_once($dii_ctx_root_dir . '/include/function.php');
include_once $dii_ctx_root_dir . '/include/class.Database.php';
include_once $dii_ctx_root_dir . '/include/code.php';
include_once $dii_ctx_root_dir . '/include/phpqrcode.php';	
if(!isset($_SESSION)){
    session_start();
}
$conn=Database::Connect();
$now=date('Y-m-d H:i:s');
for ($i=0; $i <200 ; $i++) {
	$rand=rand(00000000,100000000);
	$name='CC'.$rand;
	$sql="INSERT INTO zjzz_dhbmd VALUES (NULL,'18888888888','$name','$now','$now','1','123456','$now','AA001','$name','AABJ001','AABJ001','1','男','')";
	$id=Database::InsertOrUpdate($sql,$conn,array());
	$str="aaa".$rand;
	$filePath=scerweima($str);
	$url = 'http://xs.17189.net/api/' . $filePath;
	$sql="INSERT INTO zjzz_xs VALUES (?,?,?,?,?)";
	@Database::InsertOrUpdate($sql,$conn,array(NULL,$id,$rand,$now,$url));
	$sql="INSERT INTO user_kq_ts VALUES (?,?,?)";
	@Database::InsertOrUpdate($sql,$conn,array(NULL,$rand,$now));
	exit;
}
echo 'wc';