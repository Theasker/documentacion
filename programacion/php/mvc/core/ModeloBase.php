<?php
class ModeloBase extends EntidadBase {
	private $table;
	
	public function __construct($table){
		$this->table = (string) $table;
		parent::__construct($table);
	}
	
	public function ejecutrSql($query){
		$query = $this->db()->query($query);
		if($query){
			if($query->num_rows > 1){ // Si el resultado de la consulta da más de un registro
				while ($row = $query->fetch_object()){
					$resultSet[] = $row;
				}
			}else if($query->num_rows == 1){ // si el resultado de la consulta sólo es de un registro
				if ($row = $query->fetch_object()){
					$resultSet = $row;
				}
			}else{
				$resultSet = true;
			}
		}else {
			$resultSet = false;
		}
		
		return $resulstSet;
	}
}