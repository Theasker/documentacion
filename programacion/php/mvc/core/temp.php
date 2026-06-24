<?php
$dir = glob("*.*");
$prueba = array('uno'=> 'el numero uno','dos'=>'el numero dos');

view($prueba);
//$arrray = array();

function view($datos){
	foreach($datos as $id_assoc => $valor){
		var_dump($id_assoc);
		var_dump($valor);
		
		${$id_asoc} = $valor;
	}
}
${'siete'} = 7;
var_dump ($siete);
var_dump($array);


?>