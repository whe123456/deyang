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
$where='';
if(count($date)!=0){
    $where.=" AND create_ts >'$date[0] 00:00:00' AND create_ts < '$date[1] 23:59:59'";
}
if(isset($_REQUEST['type'])){
    if($_REQUEST['type']!=='') {
        $where .= " AND j_type=" . $_REQUEST['type'];
    }
}
$page_count=10;
$conn=Database::Connect();
$arr=array($_REQUEST['username']);
$count=Database::ReadoneStr("SELECT count(*) FROM jb_log WHERE userid=? $where ",$conn,$arr);
$qz_count=$page*$page_count;
$jblist=Database::Readall("SELECT * FROM jb_log WHERE userid=? $where ORDER BY id DESC LIMIT $qz_count,$page_count",$conn,$arr);
if(count($jblist)>0&&$jblist){
    foreach($jblist as $k=>$v){
        $jblist[$k]['j_type'] = $jb_type[$v['j_type']];
    }
}
echo json_encode(array('state'=>'true','list'=>$jblist,'count'=>$count,'page_size'=>$page_count));