<?php
include_once '../../config/setting.php';
include_once $dii_ctx_root_dir.'/include/function.php';
include_once $dii_ctx_root_dir.'/include/class.Database.php';
if(empty($_SESSION['wx_oauth2'])){
    require_once 'get_weixinid.php';
}
if(!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['user_id']=='') {
    $wx=$_SESSION['wx_oauth2'];
    $wx=json_decode($wx,true);
    $wxid=$wx['openid'];
    $wxnc=$wx['nickname'];
    $now=date('Y-m-d H:i:s');
    $conn = Database::Connect();
    $arr=array($wxid);
    $user_id = Database::ReadoneRow("SELECT * FROM wxid_b WHERE wxid=?", $conn,$arr);
    if (!$user_id) {
        $arr=array(NULL,$wxid,$wxnc,$now);
        $wid = Database::InsertOrUpdate("INSERT INTO wxid_b VALUES (?,?,?,?)", $conn,$arr);
    }else{
        $wid=$user_id['id'];
        if($user_id['wxnc']!=$wxnc){
            Database::Update_pre("UPDATE wxid_b SET wxnc=? WHERE wxid=?",$conn,array($wxnc,$wxid));
        }
    }
    $_SESSION['user_id']=$wid;
}else{
    $wid=$_SESSION['user_id'];
}