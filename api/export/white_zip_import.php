<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Method:POST,GET");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS'){
    exit;
}
header("Content-type: text/html; charset=utf-8");
include_once dirname(__FILE__) . '/../../config/setting.php';
include_once($dii_ctx_root_dir . '/include/function.php');
include_once $dii_ctx_root_dir . '/include/class.Database.php';
if(!isset($_SESSION)){
    session_start();
}
// $ii='file';
// if(array_key_exists($ii, $_FILES)===false){
//     exit;
// }else {
//     foreach ($_FILES as $f) {
//         $aa = $f["name"];
//         $aa = explode(".", $aa);
//         $extension = end($aa);
//         if ($extension!='zip') {
//             alertExit('请上传zip文件');
//         }
//         $file = fopen($f['tmp_name'], "rb");
//         $bin = fread($file, 2); //只读2字节
//         fclose($file);
//         $strInfo = @unpack("C2chars", $bin);
//         $typeCode = intval($strInfo['chars1'] . $strInfo['chars2']);
//         if (!in_array($typeCode, $all_upload_file_type_arry)) {
//             alertExit('上传文件类型不合法');
//         }
//     }
//     $namefile = explode('.', $_FILES[$ii]['name']);


//     $filePath = 'upload/' . date('YmdHis') . rand(0000, 9999) . '.' . end($namefile);
//     $aa = $_FILES[$ii]['tmp_name'];
//     move_uploaded_file($aa, $filePath);
	// $info=get_zip_originalsize($filePath,"user_photo/");
	// @unlink($filePath);
	// $conn=Database::Connect();
	$info=get_zip_originalsize('open.zip',"user_photo/");
	if($info['state']=='true'){
		foreach ($info['img'] as $key => $value) {
			$url='http://xs.17189.net/api/export/user_photo/'.$value;
			$name=reset(explode('.',$value));
			$sql="update zjzz_dhbmd set photo=? where sjhm=?";

			echo $name;exit;
		}
	}
	echo json_encode($info);
// }

function get_zip_originalsize($filename, $path) {
  //先判断待解压的文件是否存在
  $str='';
  $img=array();
  if(!file_exists($filename)){
  	$str="文件 $filename 不存在！";
  	return array('state'=>'false','msg'=>$str);
  }
  $starttime = explode(' ',microtime()); //解压开始的时间

  //将文件名和路径转成windows系统默认的gb2312编码，否则将会读取不到
  $filename = iconv("utf-8","gb2312",$filename);
  $path = iconv("utf-8","gb2312",$path);
  //打开压缩包
  $resource = zip_open($filename);
  $i = 1;
  //遍历读取压缩包里面的一个个文件
  while ($dir_resource = zip_read($resource)) {
    //如果能打开则继续
    if (zip_entry_open($resource,$dir_resource)) {
      //获取当前项目的名称,即压缩包里面当前对应的文件名
      
      $file_name = zip_entry_name($dir_resource);
      //以最后一个“/”分割,再用字符串截取出路径部分
      $real_file=substr($file_name,strrpos($file_name, "/")+1,strlen($file_name));
      $file_path = $path.$real_file;
      //如果路径不存在，则创建一个目录，true表示可以创建多级目录
      //如果不是目录，则写入文件
      if(!is_dir($file_path)){
        //读取这个文件
        $file_size = zip_entry_filesize($dir_resource);
        //最大读取6M，如果文件过大，跳过解压，继续下一个
        if($file_size<(1024*1024*30)){
          $file_content = zip_entry_read($dir_resource,$file_size);
          array_push($img,$real_file);
          file_put_contents($file_path,$file_content);
        }else{
        	$str.=$file_name.'文件大于6m已跳过';
        }
      }
      //关闭当前
      zip_entry_close($dir_resource);
    }
  }
  //关闭压缩包
  zip_close($resource);
  $endtime = explode(' ',microtime()); //解压结束的时间
  $thistime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
  $thistime = round($thistime,3); //保留3为小数
  if($str==''){
  	return array('state'=>'true','msg'=>"解压完毕！，本次解压花费：$thistime 秒",'img'=>$img);
  }else{
  	return array('state'=>'false','msg'=>$str);
  }
}