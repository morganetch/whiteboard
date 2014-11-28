<?php
require_once WWW_ROOT . 'dao' . DS . 'DAO.php';

class BoardDAO extends DAO {

	public function selectItemsByBoardId($id) {
		$sql = "SELECT * FROM `wb_items` WHERE wb_items.board_id = :id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':id', $id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

}