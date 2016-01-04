<?php
namespace Models;

class Db{
	private $connection;
		
	public function __construct($host, $dbname, $username, $password){
		$this->connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
		$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->connection->exec("set names utf8");	
	}
	
	public function queryOne($query, $parametres = array()){
		$output = $this->connection->prepare($query);
		$output->execute($parametres);
		return $output->fetch(PDO::FETCH_ASSOC);		
	}
	
	public function queryAll($query, $parametres = array()){
		$output = $this->connection->prepare($query);
		$output->execute($parametres);
		return $output->fetchAll(PDO::FETCH_ASSOC);		
	}
	
	public function queryRowCount($query, $parametres = array()){
		$output = $this->connection->prepare($query);
		$output->execute($parametres);
		return $output->rowCount();		
	}
}