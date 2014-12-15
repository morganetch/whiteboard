<?php
require_once WWW_ROOT . 'controller' . DS . 'Controller.php';
require_once WWW_ROOT . 'dao' . DS . 'BoardDAO.php';
require_once WWW_ROOT . 'dao' . DS . 'ItemDAO.php';
require_once WWW_ROOT . 'dao' . DS . 'InviteDAO.php';
require_once WWW_ROOT . 'dao' . DS . 'UserDAO.php';

require_once WWW_ROOT . 'php-image-resize' . DS . 'ImageResize.php';

class BoardsController extends Controller {

	private $boardDAO;
	private $itemDAO;
	private $inviteDAO;
	private $userDAO;

	function __construct() {
		$this->boardDAO = new BoardDAO();
		$this->itemDAO = new ItemDAO();
		$this->inviteDAO = new InviteDAO();
		$this->userDAO = new UserDAO();
	}

	public function add() {

		if(!empty($_POST)){
			$errors = array();

			if(empty($_POST["name"])){
				$errors["name"] = "Geef een naam in aub";
			}

			if(empty($errors)){
				$whiteboard = $this->boardDAO->insert(array(
					"name"=>$_POST["name"],
					"creation_date"=>date("Y-m-d H:i:s"),
					"user_id"=>$_SESSION["user"]["id"]
				));

				if(!empty($whiteboard)){
					
					$_SESSION["info"] = "Je hebt een whiteboard aangemaakt";
					$this->redirect("index.php?page=view&id=".$whiteboard["id"]);
				}
			}

			$_SESSION["error"] = "Er is iets misgelopen, uw whiteboard werd niet aangemaakt";
			$this->set("errors", $errors);
		}
	}

	public function overview() {
		if(empty($_SESSION['user'])){
			$_SESSION['error'] = 'U moet ingelogd zijn voor uw whiteboards te zien';
			$this->redirect('index.php');
		} else {
			if(!empty($_POST)){
				if($_POST['action'] == 'new'){
					$this->redirect('index.php?page=add');
				}
			}
			
			$ownBoards = $this->boardDAO->selectBoardsByUserId($_SESSION['user']['id']);
			$invitedBoards = $this->boardDAO->selectInvitedBoardsByUserId($_SESSION['user']['id']);
		}
		$this->set('ownBoards', $ownBoards);
		$this->set('invitedBoards', $invitedBoards);
	}

	public function view() {

		if(empty($_SESSION['user'])){
			$_SESSION['error'] = 'U moet ingelogd zijn voor uw whiteboard te zien';
			$this->redirect('index.php');
		} else {

			if($this->permission()){
				$board = $this->boardDAO->selectBoardById($_GET['id']);
				$items = $this->itemDAO->selectItemsByBoardId($_GET['id']);
				if(!empty($_POST)){

					switch ($_POST['action']) {
						case 'image':
							$this->uploadImage();
							break;

						case 'video':
							$this->uploadVideo();
							break;

						case 'text':
							$this->makeText();
							break;

						case 'Verwijder':
							$this->deleteItem($_POST);
							break;

						case 'Wijzig':
							$this->editItem($_POST);
							break;
					}
				}
				$this->set('board', $board);
				$this->set('items', $items);
			} else {
				$_SESSION['error'] = 'Ongeldig bord geselecteerd';
				$this->redirect('index.php');
			}
		}
	}

	public function save(){

		if($_POST){

			$errors = [];

			if(!empty($_POST['id'])){
				$errors['id'] = 'Gelieve id mee te delen';
			}
			if(!empty($_POST['x'])){
				$errors['x'] = 'Gelieve x waarde mee te delen';
			}
			if(!empty($_POST['y'])){
				$errors['y'] = 'Gelieve y waarde mee te delen';
			}
			if(!empty($_POST['z'])){
				$errors['z'] = 'Gelieve z waarde mee te delen';
			}

			header('Content-Type: application/json');

			if(!empty($errors)){
				$update = $this->itemDAO->updatePositions($_POST);

				if(!empty($update)){
					echo json_encode(array('result' => true));
					die();
				}
			} else {
				$this->set('errors', $errors);
			}
			echo json_encode(array('result' => false));
			die();
		}
	}

	public function settings() {

		if(empty($_SESSION['user'])){
			$_SESSION['error'] = 'U moet ingelogd zijn voor uw whiteboard te zien';
			$this->redirect('index.php');
		} else {

			if($this->permission()){
				$board = $this->boardDAO->selectBoardById($_GET['id']);
				$invites = $this->inviteDAO->selectInvitesByBoardId($_GET['id']);

				if(!empty($_GET['q'])){
					$this->set('users', $this->userDAO->searchUsers($_GET['q'], $_SESSION['user']['id'], $_GET['id']));
				}

				if(!empty($_POST)){
					switch ($_POST['action']) {
						case 'Wijzigen':
							$this->changeBoardTitle($_POST);
							break;

						case 'Toevoegen':
							$this->addInvite($_POST);
							break;
					}
				}
			} else {
				$_SESSION['error'] = 'Ongeldig bord geselecteerd';
				$this->redirect('index.php');
			}			
		}

		$this->set('board', $board);
		$this->set('invites', $invites);
	}

	private function getValidationErrors($data, $type){

		$errors = [];
		switch($type){

			case 1:
				if(empty($data['id'])){
					$errors['id'] = 'Gelieve id mee te geven';
				}
				break;

			case 2:
				if(empty($data['content'])){
					$errors['content'] = 'Gelieve tekst in te vullen';
				}
				if(empty($data['id'])){
					$errors['id'] = 'Gelieve een id in te vullen';
				}
				break;

			case 3:
				if(empty($data['id'])){
					$errors['id'] = 'Gelieve een id in te vullen';
				}
				break;

			case 4:
				if(empty($data['name'])){
					$errors['name'] = "Geef een naam in aub";
				}
				break;
		}

		return $errors;
	}

	private function uploadImage(){

		if(!empty($_FILES['image']['error'])) {
			$_SESSION['error'] = 'Er is een probleem met uw foto';
		} else {
			if(empty(getimagesize($_FILES['image']['tmp_name']))){
				$_SESSION['error'] = 'Dit is geen foto';
			} else {
				$sourceFile = $_FILES['image']['tmp_name'];
				$destFile = WWW_ROOT . 'uploads' . DS . $_FILES['image']['name'];
				move_uploaded_file($sourceFile, $destFile);
				$dotPos = strrpos($_FILES['image']['name'], '.');
				$name = $_FILES['image']['name'];

				$image = new Eventviva\ImageResize(WWW_ROOT . 'uploads' . DS . $_FILES['image']['name']); 
				$image->resizeToWidth(240);
				$image->save(WWW_ROOT . 'uploads' . DS . $name); 

				$z = $this->getHighestZIndex();

				$data = array();
				$data['user_id'] = $_SESSION['user']['id'];
				$data['board_id'] = $_GET['id'];
				$data['type'] = 1;
				$data['title'] = 'Foto';
				$data['content'] = $name;
				$data['description'] = 'Toegevoegd op ' . date('d/m/Y');
				$data['x'] = 100;
				$data['y'] = 100;
				$data['z'] = $z;

				$result = $this->itemDAO->insertItem($data);
				if(!empty($result)){
					$_SESSION['info'] = 'Item toegevoegd!';
					$this->redirect('index.php?page=view&id='.$_GET['id']);
				} else {
			        $_SESSION['error'] = 'Er is iets fout gelopen bij het uploaden van je foto.';
			        $errors = $this->itemDAO->getValidationErrors($data, 4);
			    }
				
			}

		}
	}

	private function uploadVideo(){

		if(!empty($_FILES['video']['error'])) {
			$_SESSION['error'] = 'Er is een probleem met uw video';
		} else {
			$sourceFile = $_FILES['video']['tmp_name'];
			$destFile = WWW_ROOT . 'uploads' . DS . $_FILES['video']['name'];
			move_uploaded_file($sourceFile, $destFile);
			$name = $_FILES['video']['name'];

			$z = $this->getHighestZIndex();

			$data = array();
			$data['user_id'] = $_SESSION['user']['id'];
			$data['board_id'] = $_GET['id'];
			$data['type'] = 2;
			$data['title'] = 'Video';
			$data['content'] = $name;
			$data['description'] = 'Toegevoegd op ' . date('d/m/Y');
			$data['x'] = 100;
			$data['y'] = 100;
			$data['z'] = $z;

			$result = $this->itemDAO->insertItem($data);
			if(!empty($result)){
				$_SESSION['info'] = 'Item toegevoegd!';
				$this->redirect('index.php?page=view&id='.$_GET['id']);
			} else {
		        $_SESSION['error'] = 'Er is iets fout gelopen bij het uploaden van je foto.';
		    }
		}
	}

	private function makeText(){

			$z = $this->getHighestZIndex();
			$data = array();
			$data['user_id'] = $_SESSION['user']['id'];
			$data['board_id'] = $_GET['id'];
			$data['type'] = 3;
			$data['title'] = 'Titel';
			$data['content'] = 'Voor deze tekst aan te passen klik je rechtsbovenaan op het instellingen-icoontje.';
			$data['description'] = 'Toegevoegd op ' . date('d/m/Y');
			$data['x'] = 100;
			$data['y'] = 100;
			$data['z'] = $z;

			$result = $this->itemDAO->insertItem($data);
			if(!empty($result)){
				$_SESSION['info'] = 'Item toegevoegd!';
				$this->redirect('index.php?page=view&id='.$_GET['id']);
			} else {
		        $_SESSION['error'] = 'Er is iets fout gelopen bij het uploaden van je foto.';
		    }
	}

	private function deleteItem($data){

		$errors = $this->getValidationErrors($data, 1);

		if(empty($errors)){
			$delete = $this->itemDAO->deleteItem($data['id']);

			if(!empty($delete)){
				$this->redirect("index.php?page=view&id=" . $_GET['id']);
			} else {
				$_SESSION['error'] = 'Er is iets misgelopen bij het verwijderen.';
			}
		}

	}

	private function editItem($data){

		if($data['type'] == 1 || $data['type'] == 2){
			$errors = $this->getValidationErrors($data, 3);

			if(empty($errors)){

				$update = $this->itemDAO->updateDescription($data);
				if(!empty($update)){
					$this->redirect("index.php?page=view&id=" . $_GET['id']);
				} else {
					$this->set('errors', $errors);
				}
			}

		} else {
			$errors = $this->getValidationErrors($data, 2);

			if(empty($errors)){

				$update = $this->itemDAO->updateTextItem($data);
				if(!empty($update)){
					$this->redirect("index.php?page=view&id=" . $_GET['id']);
				} else {
					$this->set('errors', $errors);
				}
			}
		}
	}

	private function changeBoardTitle($data){

		$errors = $this->getValidationErrors($data, 4);

		if(empty($errors)){
			$whiteboard = $this->boardDAO->update(array(
				"name"=>$_POST["name"],
				"id"=>$_GET["id"]
			));

			if(!empty($whiteboard)){
				$_SESSION["info"] = "Je hebt een whiteboard aangepast";
				$this->redirect("index.php?page=view&id=".$whiteboard["id"]);
			}
		} else {
			$_SESSION["error"] = "Er is iets misgelopen, vul de gegevens in aub";
			$this->set("errors", $errors);
		}
	}

	private function addInvite($data){
		$ids = [];

		foreach ($data as $key => $value) {
			$find = explode('-', $key);
			if($find[0] == 'user'){
				array_push($ids, $value);
			}
		}

		if(!empty($ids)){
			foreach ($ids as $id) {
				$add = $this->inviteDAO->insert(array('user_id'=>$id, 'board_id' => $_GET['id']));

				if(!empty($whiteboard)){
					$_SESSION["info"] = "Je hebt een user toegevoegd";
				} else {
					$_SESSION["error"] = "Er is iets misgelopen";
				}
			}
			$this->redirect("index.php?page=settings&id=".$_GET["id"]);
		}
	}

	private function permission(){
		$board = $this->boardDAO->selectBoardById($_GET['id']);
		$checkOwnBoard = $this->boardDAO->selectBoardByIdAndUserId($_GET['id'], $_SESSION['user']['id']);
		$checkInvitedBoard = $this->inviteDAO->selectInviteByBoardIdAndUserId($_GET['id'], $_SESSION['user']['id']);

		if($board && (!empty($checkOwnBoard)||!empty($checkInvitedBoard))){
			return true;
		} else {
			return false;
		}
	}

	private function getHighestZindex(){
		$highestZ = $this->itemDAO->getHighestZIndexOnBoard($_GET['id']);
		$newZ = $highestZ['z']+1;
		return $newZ;
	}

	public function image(){
		die("image die");
	}
}