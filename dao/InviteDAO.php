<?php
require_once WWW_ROOT . 'dao' . DS . 'DAO.php';

class InviteDAO extends DAO {

	public function selectInvitesByBoardId($id) {
		$sql = "SELECT * FROM `wb_invites` LEFT JOIN `wb_users` ON wb_invites.user_id = wb_users.id WHERE wb_invites.board_id = :id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':id', $id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

}