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
$sjhm=empty($_REQUEST['phone'])?'':$_REQUEST['phone'];
include_once $dii_ctx_root_dir . '/class/DMemcache.php';
$me = new DMemcache();
$where = 'qx_hq!=0';
if (count($date) != 0) {
    $where .= "AND create_ts >'$date[0] 00:00:00' AND create_ts < '$date[1] 23:59:59'";
}
if(!empty($sjhm)){
    $where.=" and sjhm='$sjhm'";
}
$page_count = 10;
$conn = Database::Connect();
$arr = array();
$count = Database::ReadoneStr("SELECT count(*) FROM user_t WHERE $where ", $conn, $arr);
$qz_count = $page * $page_count;
$jblist = Database::Readall("SELECT * FROM user_t WHERE $where ORDER BY id DESC LIMIT $qz_count,$page_count", $conn, $arr);
if(count($jblist)>0&&$jblist) {
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
}else{
    $data['u_new']=array();
}
echo json_encode(array('state'=>'true','list'=>$data['u_new'],'count'=>$count,'page_size'=>$page_count));