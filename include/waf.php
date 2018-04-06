<?php
// ini_set('display_errors','On');
// error_reporting(E_ALL);
header("Content-Type:application/json; charset=utf-8");



$url_arr=array(
'xss'=>"\\=\\+\\/v(?:8|9|\\+|\\/)|\\%0acontent\\-(?:id|location|type|transfer\\-encoding)",
);

$args_arr=array(
'xss'=>"[\\'\\\"\\;\\*\\<\\>].*\\bon[a-zA-Z]{3,15}[\\s\\r\\n\\v\\f]*\\=|\\b(?:expression)\\(|\\<script[\\s\\\\\\/]|\\<\\!\\[cdata\\[|\\b(?:eval|alert|prompt|msgbox)\\s*\\(|url\\((?:\\#|data|javascript)",

'sql'=>"[^\\{\\s]{1}(\\s|\\b)+(?:select\\b|update\\b|insert(?:(\\/\\*.*?\\*\\/)|(\\s)|(\\+))+into\\b).+?(?:from\\b|set\\b)|[^\\{\\s]{1}(\\s|\\b)+(?:create|delete|drop|truncate|rename|desc)(?:(\\/\\*.*?\\*\\/)|(\\s)|(\\+))+(?:table\\b|from\\b|database\\b)|into(?:(\\/\\*.*?\\*\\/)|\\s|\\+)+(?:dump|out)file\\b|\\bsleep\\([\\s]*[\\d]+[\\s]*\\)|benchmark\\(([^\\,]*)\\,([^\\,]*)\\)|(?:declare|set|select)\\b.*@|union\\b.*(?:select|all)\\b|(?:select|update|insert|create|delete|drop|grant|truncate|rename|exec|desc|from|table|database|set|where)\\b.*(charset|ascii|bin|char|uncompress|concat|concat_ws|conv|export_set|hex|instr|left|load_file|locate|mid|sub|substring|oct|reverse|right|unhex)\\(|(?:master\\.\\.sysdatabases|msysaccessobjects|msysqueries|sysmodules|mysql\\.db|sys\\.database_name|information_schema\\.|sysobjects|sp_makewebtask|xp_cmdshell|sp_oamethod|sp_addextendedproc|sp_oacreate|xp_regread|sys\\.dbms_export_extension)",

'other'=>"\\.\\.[\\\\\\/].*\\%00([^0-9a-fA-F]|$)|%00[\\'\\\"\\.]");

$referer=empty($_SERVER['HTTP_REFERER']) ? array() : array($_SERVER['HTTP_REFERER']);
$query_string=empty($_SERVER["QUERY_STRING"]) ? array() : array($_SERVER["QUERY_STRING"]);
check_a_data($query_string,$url_arr);
check_a_data($_GET,$args_arr);
check_a_data($_POST,$args_arr);
check_a_data($_COOKIE,$args_arr);
check_a_data($referer,$args_arr);

function check_a_data($arr,$v) {
	foreach($arr as $key=>$value)
	{
		if(!is_array($key))
		{
			check_s($key,$v);
		}
		else
		{ check_a_data($key,$v);
		}
	
		if(!is_array($value))
		{
			check_s($value,$v);
		}
		else
		{ check_a_data($value,$v);
		}
	}

}

function check_s($str,$v)
{
	foreach($v as $key=>$value)
	{

		if (preg_match("/".$value."/is",$str)==1||preg_match("/".$value."/is",urlencode($str))==1)
		{
			print "您的提交带有不合法参数,谢谢合作";
			///echo "111";
			//header("Location:/error.php");
			exit();
		}
	}
}

