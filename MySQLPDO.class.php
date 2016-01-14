<?php
class MySQLPDO{
	private $dbConfig = array(
		'db'=>'mysql',
		'host'=>'localhost',
		'user'=>'root',
		'pwd'=>'eb',
		'charset'=>'utf8',
		'dbname'=>'',
	);
	private static $instance;
	private $data = array();
	private $db;

	private function __construct($params){
		$this->dbConfig = array_merge($this->dbConfig,$params);
		$dsn = "{$this->dbConfig['db']}:host={$this->dbConfig['host']};dbname={$this->dbConfig['dbname']};charset={$this->dbConfig['charset']}";
		try{
			$this->db = new PDO($dsn,$this->dbConfig['user'],$this->dbConfig['pwd'],array(PDO::ATTR_ERRMODE=>2));
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}
	private function __clone(){}

	public static function getInstance($params=array()){
		if(!self::$instance instanceof self){
			self::$instance = new self($params);
		}
		return self::$instance;
	}
	public function data($data){
		$this->data = $data;
		return $this;
	}
	public function query($sql,$batch=false){
		$data = $batch? $this->data:array($this->data);
		$this->data = array();
		try{
			$stmt = $this->db->prepare($sql);
			foreach($data as $v){
				$stmt->execute($v);
			}
		}catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
		return $stmt;
	}
}