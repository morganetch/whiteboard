<?php
require_once WWW_ROOT . 'dao' . DS . 'DAO.php';

class InviteDAO extends DAO {

	public function selectById($id){
		$sql = "SELECT * FROM `wb_invites` WHERE `id` = :id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(":id", $id);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function selectInvitesByBoardId($id) {
		$sql = "SELECT * FROM `wb_invites` LEFT JOIN `wb_users` ON wb_invites.user_id = wb_users.id WHERE wb_invites.board_id = :id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':id', $id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function selectInviteByBoardIdAndUserId($board_id, $user_id){
		$sql = "SELECT * FROM `wb_invites` WHERE `board_id` = :board_id AND `user_id` = :user_id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(":board_id", $board_id);
		$stmt->bindValue(":user_id", $user_id);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function insert($data){
		$errors = $this->getValidationErrors($data);
		if(empty($errors)){
			$sql = "INSERT INTO `wb_invites` (`board_id`, `user_id`)
					VALUES (:board_id, :user_id)";
			$stmt = $this->pdo->prepare($sql);
			$stmt->bindValue(":board_id", $data["board_id"]);
			$stmt->bindValue(":user_id", $data["user_id"]);
			if($stmt->execute()){
				$lastInsertId = $this->pdo->lastInsertId();
				return $this->selectById($lastInsertId);
			}
		}
	}

	public function getValidationErrors($data) {
		
		$errors = array();

		if(empty($data['board_id'])){
			$errors['board_id'] = 'Gelieve board id mee te geven';
		}

		if(empty($data['user_id'])){
			$errors['user_id'] = 'Gelieve user id mee te geven';
		}

		return $errors;

	}

}