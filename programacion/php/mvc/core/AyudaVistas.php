<?php
class Ayudavistas{
	
	// Dibuja la url con los parámetros que le pasas
	public function url($controlador = CONTROLADOR_DEFECTO,$action = ACCION_DEFECTO){
		$urlString = "index.php?controller=".$controlador."&action=".$accion;
		return $urlString;
	}
}