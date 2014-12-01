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
	}

	public function overview() {
	}

	public function view() {
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


		$this->set('items', $items);
	}
}