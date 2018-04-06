<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Method:POST,GET");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Content-type: text/html; charset=utf-8");
include_once dirname(__FILE__) . '/../config/setting.php';
include_once($dii_ctx_root_dir . '/include/function.php');
include_once $dii_ctx_root_dir . '/include/class.Database.php';
include_once $dii_ctx_root_dir . '/include/code.php';
if(!isset($_SESSION)){
    session_start();
}
checkRequestKeyHtml("username", "用户名不能为空");
$page=empty($_REQUEST['page'])?0:$_REQUEST['page']-1;
$date=empty($_REQUEST['time'])?array():$_REQUEST['time'];
$where='al.`banner_id`=bl.id AND al.state=1 ';
if(count($date)!=0){
    $where.="AND al.create_ts >'$date[0] 00:00:00' AND al.create_ts < '$date[1] 23:59:59'";
}
$page_count=10;
$conn=Database::Connect();
$arr=array();
$count=Database::ReadoneStr("SELECT count(*) FROM authorization_list al,banner_list bl WHERE $where ",$conn,$arr);
$qz_count=$page*$page_count;
$jblist=Database::Readall("SELECT *,al.id AS id FROM authorization_list al,banner_list bl WHERE $where ORDER BY al.id DESC LIMIT $qz_count,$page_count",$conn,$arr);
echo json_encode(array('state'=>'true','list'=>$jblist,'count'=>$count,'page_size'=>$page_count));