<?php
require_once WWW_ROOT . 'controller' . DS . 'Controller.php';
require_once WWW_ROOT . 'dao' . DS . 'BoardDAO.php';
require_once WWW_ROOT . 'dao' . DS . 'ItemDAO.php';
require_once WWW_ROOT . 'dao' . DS . 'InviteDAO.php';

require_once WWW_ROOT . 'php-image-resize' . DS . 'ImageResize.php';

class BoardsController extends Controller {

	private $boardDAO;
	private $itemDAO;
	private $inviteDAO;

	function __construct() {
		$this->boardDAO = new BoardDAO();
		$this->itemDAO = new ItemDAO();
		$this->inviteDAO = new InviteDAO();
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
			$board = $this->boardDAO->selectBoardById($_GET['id']);

			if(empty($board)){
				$_SESSION['error'] = 'Ongeldig bord geselecteerd';
				$this->redirect('index.php');
			} else {
				$items = $this->itemDAO->selectItemsByBoardId($_GET['id']);

				if(!empty($_POST)){

					// die("post");
					if($_POST['action'] == 'image'){
						$this->uploadImage();
					} elseif ($_POST['action'] == 'video') {
						$this->uploadVideo();
					} elseif ($_POST['action'] == 'text') {
						$this->makeText();
					}

					if($_POST['action'] == 'Verwijder'){

						$errors = $this->getValidationErrors($_POST, 1);

						if(empty($errors)){
							$delete = $this->itemDAO->deleteItem($_POST['id']);

							if(!empty($delete)){
								$this->redirect("index.php?page=view&id=" . $_GET['id']);
							} else {
								$_SESSION['error'] = 'Er is iets misgelopen bij het verwijderen.';
							}
						}

					}

		

					if($_POST['action'] == 'Wijzig'){

						switch ($_POST['type']) {
							case 1:


								
								break;
							
							case 2:
								
								break;

							case 3:

								$errors = [];

								if(empty($_POST['title'])){
									$errors['title'] = 'Gelieve een titel in te vullen';
								}

								if(empty($_POST['content'])){
									$errors['content'] = 'Gelieve tekst in te vullen';
								}

								if(empty($_POST['desc'])){
									$errors['desc'] = 'Gelieve een omschrijving in te vullen';
								}

								if(empty($_POST['id'])){
									$errors['id'] = 'Gelieve een id in te vullen';
								}

								if(empty($errors)){
									$update = $this->itemDAO->updateContent($_POST);

									if(!empty($update)){
										$this->redirect("index.php?page=view&id=" . $_GET['id']);
									} else {
										$this->set('errors', $errors);
									}
								}

								break;
						}

					}
					
				}
				$this->set('board', $board);
				$this->set('items', $items);
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
			//$this->redirect("index.php?page=view&id=" . $_POST['boardId']);
		}
	}

	public function settings() {

		if(empty($_SESSION['user'])){
			$_SESSION['error'] = 'U moet ingelogd zijn voor uw whiteboard te zien';
			$this->redirect('index.php');
		} else {

			$board = $this->boardDAO->selectBoardById($_GET['id']);
			$invites = $this->inviteDAO->selectInvitesByBoardId($_GET['id']);

			if(empty($board)){
				$_SESSION['error'] = 'Ongeldig bord geselecteerd';
				$this->redirect('index.php');
			}
		}
		
		if(!empty($_POST)){
			$errors = array();

			if(empty($_POST["name"])){
				$errors["name"] = "Geef een naam in aub";
			}
			
			if(empty($errors)){
				$whiteboard = $this->boardDAO->update(array(
					"name"=>$_POST["name"],
					"id"=>$_GET["id"]
				));

				if(!empty($whiteboard)){
					$_SESSION["info"] = "Je hebt een whiteboard aangepast";
					$this->redirect("index.php?page=view&id=".$whiteboard["id"]);
				}
			}

			$_SESSION["error"] = "Er is iets misgelopen, vul de gegevens in aub";
			$this->set("errors", $errors);
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
			        // $errors = $this->pictureDAO->getValidationErrors($data);
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
		        // $errors = $this->pictureDAO->getValidationErrors($data);
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
		        // $errors = $this->pictureDAO->getValidationErrors($data);
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

	public function deleteItem(){
		if($_POST){

			// if(!empty($_POST['id'])){
			// 	$errors['id'] = 'Gelieve id mee te delen';
			// }

			header('Content-Type: application/json');

			// if(!empty($errors)){
				$itemId = $_POST['id'];
				$delete = $this->itemDAO->deleteItem($itemId);
				echo json_encode(array('result' => true));
					die();

			// 	if(!empty($delete)){
			// 		echo json_encode(array('result' => true));
			// 		die();
			// 	}
			// } else {
			// 	$this->set('errors', $errors);
			// }
			// echo json_encode(array('result' => false));
			// die();
			//$this->redirect("index.php?page=view&id=" . $_POST['boardId']);
		}
	}
}