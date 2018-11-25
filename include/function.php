<?php

/**
 * author:wangjie
 * 提示并退出
 * @param $msg 提示信息
 *
 */
function alertExit($msg){
    $retarray['state']="false";
    $retarray['msg']=$msg;
    echo json_encode($retarray);
    exit;
}

/**
 * author:wangjie
 * post方法
 * @param $url  访问的地址
 * @param null $data post传的参数
 * @return mixed|string  返回的访问结果
 */
function post($url,$data=null) {
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url);
    curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
    curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)' );
    curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
    curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 );
    if($data!=null){
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
    }
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    $tmpInfo = curl_exec ( $ch );
    if (curl_errno ( $ch )) {
        return curl_error ( $ch );
    }
    curl_close ( $ch );
    return $tmpInfo;
}

function posts($url,$data=null) {
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url);
    curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)' );
    curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
    curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 );
    $headers = array(
        'Content-Type'    => 'application/json'
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt ( $ch, CURLOPT_POSTFIELDS,  http_build_query($data) );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    $tmpInfo = curl_exec ( $ch );
    //var_dump(curl_getinfo($ch, CURLINFO_HEADER_OUT));exit;
    if (curl_errno ( $ch )) {
        return curl_error ( $ch );
    }
    curl_close ( $ch );
    return $tmpInfo;
}

/**
 * author:hepeng
 * post方法
 *证书验证
 * @param $url 地址
 * @param $vars post传的参数
 * @param int $second
 * @param array $aHeader
 * @return bool|mixed
 */
function curl_post_ssl($url, $vars, $second=30,$aHeader=array())
{
    $ch = curl_init();
    //超时时间
    curl_setopt($ch,CURLOPT_TIMEOUT,$second);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    //这里设置代理，如果有的话
    //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
    //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);

    //以下两种方式需选择一种

    //第一种方法，cert 与 key 分别属于两个.pem文件
    //默认格式为PEM，可以注释
    curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
    curl_setopt($ch,CURLOPT_SSLCERT,$_SERVER['CONTEXT_DOCUMENT_ROOT'].'/wxpay/cert/apiclient_cert.pem');
    //默认格式为PEM，可以注释
    curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
    curl_setopt($ch,CURLOPT_SSLKEY,$_SERVER['CONTEXT_DOCUMENT_ROOT'].'/wxpay/cert/apiclient_key.pem');
    curl_setopt($ch,CURLOPT_CAINFO,$_SERVER['CONTEXT_DOCUMENT_ROOT'].'/wxpay/cert/rootca.pem');
    //第二种方式，两个文件合成一个.pem文件
    // curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');

    if( count($aHeader) >= 1 ){
        curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
    }

    curl_setopt($ch,CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
    $data = curl_exec($ch);
    if($data){
        curl_close($ch);
        return $data;
    }
    else {
        $error = curl_errno($ch);
        echo "call faild, errorCode:$error\n";
        curl_close($ch);
        return false;
    }
}

/**
 * 返回报错页面
 * @param $title,$msg
 */
function alertHtml($title,$msg){
    echo '
        <!doctype html>
        <html lang="en">
        <head>
        <meta charset="UTF-8">
        <title>'.$title.'</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0"/>
        <script src="http://'.$_SERVER["HTTP_HOST"].'/js/jquery-3.1.1.js"></script>
        <script type="text/javascript">
            //移动端屏幕适配
            var html = document.querySelector("html");
            var rem = html.offsetWidth / 20;
            html.style.fontSize = rem + "px";
            $(window).on("change, resize",function(){
                var html = document.querySelector("html");
                var rem = html.offsetWidth / 20;
                html.style.fontSize = rem + "px";
            });
        </script>
    </head>
    <body class="vl_body">
    <div class="tk_cover" style="display:block;background:#000;opacity: 0.7;filter: alpha(opacity=70);position: fixed;left:0;top:0;z-index: 888;width:100%;height:100%;}"></div><div class="tankuang" style="width:80%;overflow: hidden;background: #fff;border-radius:5px;position: fixed;left:10%;top:35%;z-index: 999;"><div class="tk" style="width:100%;box-sizing:border-box;padding:0 0.8rem;overflow: hidden;"><div class="tk_head" style="width:100%;line-height: 2.88rem;border-bottom: 1px solid #fa7f2b;text-align: center;font-size:0.91rem;color:#fa7f2b;letter-spacing: 1px;">提示</div><div class="tk_content" style="width:100%;line-height: 1.19rem;padding:1.19rem 0;text-align: center;font-size: 0.8rem;color:#2e2e2e;">'.$msg.'</div></div><div class="tk_btn" style="width:100%;line-height:2.29rem;text-align: center;border-top:1px solid #e0e0e0;background: #ccc;color:#fff;font-size: 0.91rem;letter-spacing: 3px;" onclick="javascript :history.back(-1)">确定</div></div>
       
    </body>
    </html>';
    exit;
}


/**
 * 返回指定页面
 * @param string $title
 * @param string $msg
 * @param string $url
 * @auther 晏华兴
 */
function alertHtmlGo($title,$msg,$url){
    echo '
        <!doctype html>
        <html lang="en">
        <head>
        <meta charset="UTF-8">
        <title>'.$title.'</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0"/>
        <script src="http://'.$_SERVER["HTTP_HOST"].'/js/jquery-3.1.1.js"></script>
        <script type="text/javascript">
            //移动端屏幕适配
            var html = document.querySelector("html");
            var rem = html.offsetWidth / 20;
            html.style.fontSize = rem + "px";
            $(window).on("change, resize",function(){
                var html = document.querySelector("html");
                var rem = html.offsetWidth / 20;
                html.style.fontSize = rem + "px";
            });
        </script>
    </head>
    <body class="vl_body">
    <div class="tk_cover" style="display:block;background:#000;opacity: 0.7;filter: alpha(opacity=70);position: fixed;left:0;top:0;z-index: 888;width:100%;height:100%;}"></div><div class="tankuang" style="width:80%;overflow: hidden;background: #fff;border-radius:5px;position: fixed;left:10%;top:35%;z-index: 999;"><div class="tk" style="width:100%;box-sizing:border-box;padding:0 0.8rem;overflow: hidden;"><div class="tk_head" style="width:100%;line-height: 2.88rem;border-bottom: 1px solid #fa7f2b;text-align: center;font-size:0.91rem;color:#fa7f2b;letter-spacing: 1px;">提示</div><div class="tk_content" style="width:100%;line-height: 1.19rem;padding:1.19rem 0;text-align: center;font-size: 0.8rem;color:#2e2e2e;">'.$msg.'</div></div><div class="tk_btn" style="width:100%;line-height:2.29rem;text-align: center;border-top:1px solid #e0e0e0;background: #ccc;color:#fff;font-size: 0.91rem;letter-spacing: 3px;" onclick="javascript:location.href=\''.$url.'\'">确定</div></div>
       
    </body>
    </html>';
    exit;
}


 function checkMobile($str)
 {
     $pattern = "/^1[3,4,5,6,7,8,9]\d{9}$/";
     if (!preg_match($pattern,$str))
     {
         alertExit("请输入正确的以13,14,15,17,18开头的11位手机号");
     }
}

 function tranTime($time)
{
    $rtime = date("m-d H:i",$time);
    $ytime = date("Y",$time);
    $ntime = date("Y");
    $time2 = time() - $time;
    if ($time2 < 60)
    { $str = '刚刚'; }
    elseif ($time2 < 60 * 60)
    { $min = floor($time2/60); $str = $min.'分钟前'; }
    elseif ($time2 < 60 * 60 * 24)
    { $h = floor($time2/(60*60)); $str = $h.'小时前 '; }
    /*elseif ($time2 < 60 * 60 * 24 * 3)
    { $d = floor($time2/(60*60*24));
        if($d==1)
            $str = '昨天 ';
        else $str = '前天 ';
    }*/
    else
    {
        if($ytime==$ntime){
            $str = date("m-d",$time);
        }else{
            $str = date("Y-m-d",$time);
        }
    }
    return $str;
}


/**
*file_put_contents 写入文件
*@param $filename 文件名 
*/
function fileputTxt($filename,$content){
    $ipp=GetIP();
    $fenge = "******************我是华丽的分割线******************";
    $str="请求时间：".date("Y-m-d H:i:s",time()).PHP_EOL."发送数据:".PHP_EOL.$content.PHP_EOL."IP地址: ".$ipp.PHP_EOL.PHP_EOL.$fenge;
    file_put_contents($filename,$str.PHP_EOL,FILE_APPEND);
}

function GetIP(){
    $cip='';
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        $cip = $_SERVER["HTTP_CLIENT_IP"];
    }
    elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
        $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    elseif(!empty($_SERVER["REMOTE_ADDR"])){
        $cip = $_SERVER["REMOTE_ADDR"];
    }
    else{
        $cip = "无法获取！";
    }
    return $cip;
}

//请求的参数拼装组成数组
function getRequest($data){
    $i=0;
    $return=array();
    if(count($data)>0){
        foreach ($data as $k => $v) {
            $return[$i]=$k;
            $i++;
        }
    }
    return $return;
}

function writeLog($userid,$coin,$c_type){
    $conn=Database::Connect();
    $arr=array($userid,$coin,$c_type,date('Y-m-d H:i:s'));
    @Database::InsertOrUpdate("insert into coin_log values ('',?,?,?,?)",$conn,$arr);
    if($c_type!=1){
        @Database::InsertOrUpdate("update user_t set coin=coin+".$coin." where id=? ",$conn,array($userid));
    }
    return true;
}


function writeJbLog($userid,$coin,$jb,$j_type=1){
    $conn=Database::Connect();
    $arr=array($userid,$coin,$j_type,date('Y-m-d H:i:s'));
    @Database::InsertOrUpdate("insert into coin_log values ('',?,?,?,?)",$conn,$arr);
    if($j_type!=1){
        @Database::InsertOrUpdate("update user_t set jb=jb+".$jb." where id=? ",$conn,array($userid));
    }
    return true;
}

function getUserIdByToken($token){
    $url=TOKEN_URL.'/user_api/panda_gas/get_user_info_by_token.php?token='.$token;
    $aa=Post($url);
    return $aa;
}

function getInvite_code( ) {
    // 密码字符集，可任意添加你需要的字符
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXY';
    $invite_code ='';
    for ( $i = 0; $i < 2; $i++ )
    {
        $invite_code .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }

    $chars2='3456789';
    for ( $i = 0; $i < 4; $i++ )
    {
        $invite_code .= $chars2[ mt_rand(0, strlen($chars2) - 1) ];
    }
    return $invite_code;
}
function checkRequestKeyHtml($key,$msg){
    $retarray =array();
    if(!isset($_REQUEST[$key])||$_REQUEST[$key]==""){
        alertExitHtml($msg);
    }
}
function alertExitHtml($msg){
    echo json_encode(array('state'=>'false','msg'=>$msg));exit;
}

function birthday2($birthday){
    list($year,$month,$day) = explode("-",$birthday);
    $year_diff = date("Y") - $year;
    $month_diff = date("m") - $month;
    $day_diff  = date("d") - $day;
    if ($day_diff < 0 || $month_diff < 0)
        $year_diff--;
    return $year_diff;
}
/**对excel里的日期进行格式转化*/
function GetData($val)
{
    $n = intval(($val - 25569) * 3600 * 24);
    return gmdate('Y-m-d H:i:s', $n);
}
//生成二维码引用phpqrcode
function scerweima($value=''){
    $errorCorrectionLevel = 'L';    //容错级别
    $matrixPointSize = 8;           //生成图片大小

    //生成二维码图片
    $filename = 'qrcode/'.date('YmdHis').rand(0,999999).'.png';
    QRcode::png($value,$filename , $errorCorrectionLevel, $matrixPointSize, 2);

    return $filename;
}

//微信随机数
function getRandstr($length = 16){
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
}
function send($tel,$msg){
    $tel=18308465172;
    $appid='IfkkPZPcO0PerGnIFwDfsyWlhUBy7Jey';
    $appkey='UeLPqY4YglhtBbFiIkV8XuO9MCNKaNIj';
    $sign=md5($appid.$tel.$msg.$appkey);
    $msg=urlencode($msg);
    $url="https://sms.189ek.com/yktsms/send?appid=$appid&mobile=$tel&msg=$msg&sign=$sign";
    $info=file_get_contents($url);
    return $info;
}