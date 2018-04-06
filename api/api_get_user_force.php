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
$date=empty($_REQUEST['value7'])?array():$_REQUEST['value7'];
$where='';
if(count($date)!=0){
    $where.="AND create_ts >'$date[0] 00:00:00' AND create_ts < '$date[1] 23:59:59'";
}
$page_count=10;
$conn=Database::Connect();
$arr=array($_REQUEST['username']);
$count=Database::ReadoneStr("SELECT count(*) FROM coin_log WHERE userid=? $where ",$conn,$arr);
$qz_count=$page*$page_count;
$jblist=Database::Readall("SELECT * FROM coin_log WHERE userid=? $where ORDER BY id DESC LIMIT $qz_count,$page_count",$conn,$arr);
if(count($jblist)>0&&$jblist){
    foreach($jblist as $k=>$v){
        if($v['j_type']=='5'){
            switch ($v['rw_id'])
            {
                case 1:
                    $jblist[$k]['j_type'] =$task[1]['task'][1]['name'];
                    break;
                case 2:
                    $jblist[$k]['j_type'] =$task[1]['task'][2]['name'];
                    break;
                case 3:
                    $jblist[$k]['j_type'] =$task[2]['task'][1]['name'];
                    break;
                case 4:
                    $jblist[$k]['j_type'] =$task[2]['task'][2]['name'];
                    break;
                default:
                    $jblist[$k]['j_type'] =$task[1]['task'][1]['name'];
                    break;
            }
        }else {
            $jblist[$k]['j_type'] = $j_type[$v['j_type']][0];
        }
        $jblist[$k]['coin'] = '+'.$v['coin'];
    }
}
echo json_encode(array('state'=>'true','list'=>$jblist,'count'=>$count,'page_size'=>$page_count));