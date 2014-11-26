<?php
require_once WWW_ROOT . 'controller' . DS . 'Controller.php';
require_once WWW_ROOT . 'dao' . DS . 'BoardDAO.php';

require_once WWW_ROOT . 'php-image-resize' . DS . 'ImageResize.php';

class BoardsController extends Controller {

	private $userDAO;

	function __construct() {
		$this->boardDAO = new BoardDAO();
	}

	public function add() {
	}

	public function overview() {
	}

	public function view() {
	}
}