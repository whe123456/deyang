<?php
Class Database{
	public function __destruct()    {
		$this->connection = null;
	}
	private function __construct(){
		$databaseName = $GLOBALS['configuration']['db'];
		$driver = $GLOBALS['configuration']['pdoDriver'];
		$serverName = $GLOBALS['configuration']['host'];
		$databaseUser = $GLOBALS['configuration']['user'];
		$databasePassword = $GLOBALS['configuration']['pass'];
		$databasePort = $GLOBALS['configuration']['port'];
		if (!isset($this->connection)){
			$this->connection = new PDO($driver.':host='.$serverName.';port='.$databasePort.';dbname='.$databaseName, $databaseUser, $databasePassword);
			$this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->connection->Query("SET NAMES 'UTF8'");
		}
		if (!$this->connection)
		{
			throw new Exception('I cannot connect to the database. Please edit configuration.php with your database configuration.');
		}
	}



	public static function Connect(){
		static $database = null;
		if (!isset($database)){
			$database = new Database();
		}
		return $database->connection;
	}

	//Updated by David Fu 2010-4-23 for disabled the connect of db
	public static function Close($connection)
	{
		$connection = null;
		return true;
	}

	public static function Read($result)
	{
		try
		{
			return $result->fetch();
		}
		catch (PDOException $e)
		{
			return false;
		}
	}


	public static function ReadoneRow($query, $connection,$data){
		try
		{

			$sth = $connection->prepare($query);

			$sth->setFetchMode(PDO::FETCH_ASSOC);
			$sth->execute($data);
			$result = $sth->fetch();


		}
		catch(PDOException $e)
		{
			//var_dump($e);
			return false;
		}
		return $result;

	}

	public static function ReadallRow($query, $connection,$data){
		try
		{
			$sth = $connection->prepare($query);
			$sth->execute($data);
			$result = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
		}
		catch(PDOException $e)
		{

			return false;
		}
		return $result;

	}

	public static function Readall($query, $connection,$data){
		try
		{
			$sth = $connection->prepare($query);
			$sth->execute($data);
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			return false;
		}
		return $result;
	}


	public static function ReadoneStr($query, $connection,$data){
		try
		{
			//$result = $connection->Query($query)->fetchColumn();

			$sth = $connection->prepare($query);
			$sth->execute($data);
			$result = $sth->fetchColumn();

		}
		catch(PDOException $e)
		{
			
			return false;
		}
		return $result;

	}






	public static function InsertOrUpdate($query,$connection,$data){
		try
		{
			$sth = $connection->prepare($query);
			$res=$sth->execute($data);
			if($res===false){
				return false;
			}else{
				return $connection->lastInsertId();
			}
		}
		catch (PDOException $e)
		{
			var_dump($e);
			return false;
		}
	}

	public static function Update_pre($query,$connection,$data){
		try
		{
			$sth = $connection->prepare($query);
			$sth->execute($data);
			return true;
		}
		catch (PDOException $e)
		{
			//var_dump($e);exit;
			//var_dump($e);
			return false;
		}
	}

}
?>
