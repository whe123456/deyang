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
$bm=empty($_REQUEST['bj_bm'])?'':$_REQUEST['bj_bm'];
$where=" bjbm!=''";
if($bm!=''){
    $where.=" AND bjbm = '$bm'";
}
$page_count=10;
$conn=Database::Connect();
$count=Database::ReadoneStr("select count(distinct bjbm) from zjzz_dhbmd where $where",$conn,array());
$qz_count=$page*$page_count;
$sql="select distinct bjbm from zjzz_dhbmd where $where ORDER BY id DESC LIMIT $qz_count,$page_count";
$bjbm=Database::Readall($sql,$conn,array());
$user_list=array();
if(count($bjbm)>0) {
    $user_list=$bjbm;
    $i=0;
    foreach ($bjbm as $k=>$v){
        $sql="select * from zjzz_js where find_in_set(?, bjbm)";
        $t_info=Database::Readall($sql,$conn,array($v['bjbm']));
        if(count($t_info)>0){
            foreach($t_info as $val){
                $user_list[$i]['js_bm']=$val['js_bm'];
                $user_list[$i]['xm']=$val['xm'];
                $user_list[$i]['bj_mc']=$val['bj_mc'];
                $i++;
            }
        }else{
            $sql="select bjbm,bj_mc from zjzz_dhbmd where bjbm=? limit 1";
            $info=Database::ReadoneRow($sql,$conn,array($v['bjbm']));
            if($info){
                $user_list[$i]['bj_mc']=$info['bj_mc'];
                $user_list[$i]['js_bm']='';
                $user_list[$i]['xm']='';
                $i++;
            }
        }
    }
}
echo json_encode(array('state'=>'true','list'=>$user_list,'count'=>$count,'page_size'=>$page_count));