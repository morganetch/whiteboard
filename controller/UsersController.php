<?php
require_once WWW_ROOT . 'controller' . DS . 'Controller.php';
require_once WWW_ROOT . 'dao' . DS . 'UserDAO.php';

require_once WWW_ROOT . 'phpass' . DS . 'Phpass.php';
require_once WWW_ROOT . 'php-image-resize' . DS . 'ImageResize.php';

class UsersController extends Controller {

	private $userDAO;

	function __construct() {
		$this->userDAO = new UserDAO();
	}

	public function register() {
		if(!empty($_POST)){
			$errors = array();

			if(empty($_POST["username"])){
				$errors["username"] = "Geef een username in aub";
			}else{
				$existing = $this->userDAO->selectByUsername($_POST["username"]);
				if(!empty($existing)){
					$errors ["username"] = "Deze gebruikersnaam is al in gebruik";
				}
			}

			if(empty($_POST["password"])){
				$errors["password"] = "Geef een wachtwoord in aub";
			}
			if($_POST["password"] != $_POST["confirm_password"] || $_POST["password"] == ""){
				$errors["confirm_password"] = "De wachtwoorden zijn niet gelijk";
			}

			if(empty($errors)){
				$hasher = new \Phpass\Hash;
				$user = $this->userDAO->insert(array(
					"username"=>$_POST["username"],
					"password"=>$hasher->hashPassword($_POST["password"])
				));

				if(!empty($user)){
					$_SESSION['user'] = $user;
					$_SESSION["info"] = "Registratie is gelukt, log je nu in";
					$this->redirect("index.php");
				}
			}
			$_SESSION["error"] = "Registratie mislukt";
			$this->set("errors", $errors);
		}
	}

	public function login(){
		$errors = array();
		if(!empty($_POST)){
			if(empty($_POST["username"])){
				$errors["username"] = "Geef uw gebruikersnaam in aub";
			}
			if(empty($_POST["password"])){
				$errors["password"] = "Geef uw wachtwoord in aub";
			}
			if(empty($errors)){
				$user = $this->userDAO->selectByUsername($_POST["username"]);
				if(!empty($user)){
					$hasher = new \Phpass\Hash;
					if($hasher->checkPassword($_POST["password"], $user["password"])){
						$_SESSION["user"] = $user;
					}else{
						$_SESSION["error"] = "Foutieve wachtwoord";
					}
				}else{
					$_SESSION["error"] = "Foutieve gebruikersnaam";
				}
			}else{
				$_SESSION["error"] = "Inloggen is mislukt";
			}
		}
		$this->redirect("index.php");
	}

	public function logout(){
		unset($_SESSION["user"]);
		$this->redirect("index.php");
	}

}