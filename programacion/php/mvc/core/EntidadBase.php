<?php
class EntidadBase{
	private $table,$db,$conectar;
	
	public function __construct(){
		$this->table = (string) $table;
		require_once 'Conectar.php';
		$this->conectar = new Conectar();
		$this->db = $this->conectar->conexion();
	}
	
	public function getConectar(){
		return $this->conectar;
	}
	
	public function db(){
		return $this->db;
	}
	
	public function getAll(){
		$query = $this->db->query("SELECT * FROM $this->table ORDER BY id DESC");
		while($row = $query->fetch_object()){
			$resultSet[] = $row;
		}
		return $resultSet;
	}
	
	public function getById(){
		$query = $this->db->query("SELECT * FROM $this->table WHERE id=$id");
		if($row = $query->fetch_objetct()){
			$resultSet = $row;
		}
		return $resultSet;
	}
	
	public function getBy($column,$value){
		$query = $this->db->query("SELECT * FROM $this->table WHERE id=$id");
	}
	
	public function deleteByeId($id){
		$query = $this->db->query("DELETE FROM $this->table WHERE id=$id");
		return $query;
	}
	
	public function deleteBy($column,$value){
		$query = $this->db->query("DELETE FROM $this->table WHERE $column='$value'");
		return $query;
	}
}
