<?php
// error_reporting(0);

class DMemcache {
	private $host="192.168.0.195";
	private $port="11211";
	// private $host="127.0.0.1";
	// private $port="18089";	
	function set($key,$value,$flag=0,$expire=600) {
		try{
			$mem = new Memcache;
			$s=$mem->connect($this->host , $this->port);
			if($s){
				$mem->set($key, $value, $flag, $expire);
				$mem->close();
				return true;
			}else{
			 return false;	
			}
		}catch(Exception $e){
			return false;
		}
	}

	function add($key,$value,$flag=0,$expire=600)
	{
		try{
			$mem = new Memcache;
			$s=$mem->connect($this->host , $this->port);
			if($s){
				$rs=$mem->add($key, $value,$flag, $expire);
				$mem->close();
				return $rs;
			}else{
			 return false;	
			}
		}catch(Exception $e){
			return false;
		}
	}

	function get($key) {
		try{
			$mem = new Memcache;
			$s=$mem->connect($this->host , $this->port);
			if($s){
				$val = $mem->get($key);
				$mem->close();
				// echo "======";
				return $val;
			}else{
				return false;
			}
		}catch(Exception $e){
			return false;
		}
	}
	
	function delete($key) {
		try{
			$mem = new Memcache;
			$s=$mem->connect($this->host , $this->port);
			if($s){
				 $mem->delete($key);
				 $mem->close();
				return true;
			}else{
				return false;
			}
		}catch(Exception $e){
			return false;
		}
	}

	function inc($key,$value) {
		try{
			$mem = new Memcache;
			$s=$mem->connect($this->host , $this->port);
			if($s){
				$rs=$mem->increment($key,$value);
				$mem->close();
				return $rs;
			}else{
				return false;
			}
		}catch(Exception $e){
			return false;
		}
	}	

}

?>