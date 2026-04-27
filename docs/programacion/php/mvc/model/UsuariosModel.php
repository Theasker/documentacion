<?php

class UsuariosModel extends ModeloBase{
	private $table;
	
	public function __construct($table){
		$this->table = "usuarios";
		parent::__construct($this->table);
	}
	
	public function getUnUsuario(){
		$query = "SELECT * FROM usuarios WHERE email='theasker@gmail.com'";
		$usuario = $this->ejectutarSql($query);
		return $usuario;
	}
}