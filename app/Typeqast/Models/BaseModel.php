<?php

namespace Typeqast\Models;

class BaseModel
{
	
	private $pdoDriver	= 'mysql';
	private $host;
	private $username;
	private $password;
	private $database;
	private $charset	= 'utf8';
	private $conn		= null;
	
	public function __construct()
	{
		$this->host 		= getenv('DB_HOST');
		$this->username		= getenv('DB_USER');
		$this->password		= getenv('DB_PASSWORD');
		$this->database		= getenv('DB_DATABASE');

		$dsn = $this->pdoDriver . ':host=' . $this->host . ';dbname=' . $this->database . ';charset=' . $this->charset;
		try{
			$this->conn = new \PDO($dsn, $this->username, $this->password);
			$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}catch(\Exception $e){
			echo $e->getMessage();
		}
		
	}
	
	public function getConnection()
	{
		
		return $this->conn;
	}

	
}