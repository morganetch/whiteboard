<?php
require_once WWW_ROOT . 'controller' . DS . 'Controller.php';
require_once WWW_ROOT . 'dao' . DS . 'BoardDAO.php';

require_once WWW_ROOT . 'php-image-resize' . DS . 'ImageResize.php';

class BoardsController extends Controller {

	private $boardDAO;

	function __construct() {
		$this->boardDAO = new BoardDAO();
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
				$items = $this->boardDAO->selectItemsByBoardId($_GET['id']);

				if(!empty($_POST)){

					if($_POST['action'] == 'update'){

						if(!empty($_POST['id'])){
							$errors['id'] = 'Gelieve id mee te delen';
						}

						if(!empty($_POST['x'])){
							$errors['x'] = 'Gelieve x waarde mee te delen';
						}

						if(!empty($_POST['y'])){
							$errors['y'] = 'Gelieve y waarde mee te delen';
						}

						if(!empty($errors)){
							$update = $this->boardDAO->update($_POST);

							if(!empty($update)){
								$this->redirect("index.php");
							}
						} else {
							$this->set('errors', $errors);
						}
					}
				}
				$this->set('board', $board);
				$this->set('items', $items);
			}
			
		}

	}
}