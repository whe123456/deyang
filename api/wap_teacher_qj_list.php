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
checkRequestKeyHtml("wxid", "用户信息不能为空");
$wxid = $_REQUEST['wxid'];
$conn=Database::Connect();
$sql="SELECT js_bm,js_id FROM zjzz_js WHERE wxid=?";
$user=Database::ReadoneRow($sql,$conn,array($wxid));
$type=empty($_REQUEST['type'])?0:$_REQUEST['type'];
if(!$user){
    alertExitHtml("无此教师信息");
}
$where='';
if($type==0) {
    if($user['js_id']==4){
        $where .= "  AND (sf_ty=0 OR sf_ty=1) AND (jdc_teacher='' OR jdc_teacher IS NULL) AND jdc_ty=0";
    }else{
        $where .= " and sf_ty =0";
    }
}else{
    if($user['js_id']==4){
        $where .= " AND sf_ty!=0 AND jdc_teacher='{$user['js_bm']}' AND jdc_ty!=0";
    }else{
        $where .= " and sf_ty !=0";
    }
}
if(!empty($_REQUEST['minid'])){
    $minid=$_REQUEST['minid'];
    $where.=" and zq.id<$minid";
}
$count_arr=Database::ReadoneStr("SELECT MIN(zq.id) FROM zjzz_qj zq WHERE zq.js_bm=? $where",$conn,array($user['js_bm']));
$sql="SELECT zq.*,zj.xm FROM zjzz_qj zq,zjzz_js zj WHERE zq.js_bm=? AND zq.js_bm=zj.js_bm $where order by zq.id desc limit 0,3";
$arr=Database::Readall($sql,$conn,array($user['js_bm']));
$more=true;
$minid='';
$list=array();
if(count($arr)>0){
    foreach ($arr as $key => $value) {
        if($value['id']==$count_arr){
            $more=false;
        }
        $minid=$value['id'];
        $date=explode('-', $value['qj_sj']);
        $list[$key]['title']=$value['qj_yy'];
        $list[$key]['id']=$value['id'];
        $list[$key]['list'][0]['label']='审批人';
        $list[$key]['list'][1]['label']='审批状态';
        $list[$key]['list'][2]['label']='教导处审批人';
        $list[$key]['list'][3]['label']='教导处审批状态';
        $list[$key]['list'][4]['label']='请假标题';
        $list[$key]['list'][5]['label']='请假开始时间';
        $list[$key]['list'][6]['label']='请假结束时间';
        $list[$key]['list'][0]['value']=$value['xm'];
        $list[$key]['list'][1]['value']=$sf_ty[$value['sf_ty']];
        $spr='未审核';
        if($value['jdc_teacher']){
            $spr=Database::ReadoneStr("SELECT xm FROM zjzz_js WHERE js_bm=?",$conn,array($value['jdc_teacher']));
        }
        $list[$key]['list'][2]['value']=$spr;
        $list[$key]['list'][3]['value']=$sf_ty[$value['jdc_ty']];
        $list[$key]['list'][4]['value']=$value['qj_nr'];
        $list[$key]['list'][5]['value']=$date[0];
        $list[$key]['list'][6]['value']=$date[1];
    }
}
echo json_encode(array('state'=>'true','more'=>$more,'list'=>$list,'minid'=>$minid));