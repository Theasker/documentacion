<?php
class UsuariosController extends ControladorBase{
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$usuario = new usuario();
		$allusers = $usuario->getAll();
		$this->view("index",array(
			"allusers"=>$allusers,
			"Hola"=>"Video EJEMPLO MVC PHP Victor Robles"
		));
	}
	
	public function crear(){
		if(isset($_POST['nombre'])){
			$usuario = new Usuario($table);
			
			$nombre = $_POST['nombre'];
			$apellido = $_POST['apellido'];
			$email = $_POST['email'];
			$password = sha1($_POST['password']);
			
			$usuario->setNombre($nombre);
			$usuario->setApellido($apellido);
			$usuario->setEmail($email);
			$usuario->setPassword($password);
			
			$save = $usuario->save();
		}
		
		$this->redirect("Usuarios","index");
	}
	
	public function borrar(){
		if (isset($_GET['id'])){
			$id = (int)$_GET['id'];
			
			$usuario = new Usuario();
			$usuario->deleteById($id);
			
			$this->redirect();
		}
	}
	
}