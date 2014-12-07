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

	public function selectBoardById($id) {
		$sql = "SELECT * FROM `wb_boards` WHERE wb_boards.id = :id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':id', $id);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function selectBoardsByUserId($id) {
		$sql = "SELECT * FROM `wb_boards` WHERE wb_boards.user_id = :id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':id', $id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function selectInvitedBoardsByUserId($id) {
		$sql = "SELECT * FROM `wb_boards` WHERE wb_boards.user_id = :id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':id', $id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function insert($data){
		$errors = $this->getValidationErrors($data);
		if(empty($errors)){
			$sql = "INSERT INTO `wb_boards` (`name`, `user_id`, `creation_date`)
					VALUES (:name, :user_id, :creation_date)";
			$stmt = $this->pdo->prepare($sql);
			$stmt->bindValue(":name", $data["name"]);
			$stmt->bindValue(":user_id", $data["user_id"]);
			$stmt->bindValue(":creation_date", $data["creation_date"]);
			if($stmt->execute()){
				$lastInsertId = $this->pdo->lastInsertId();
				return $this->selectBoardById($lastInsertId);
			}
		}
	}

	public function getValidationErrors($data){
		$errors = array();
		if(empty($data["name"])){
			$errors["name"] = "please fill in an name";
		}
		if(empty($data["user_id"])){
			$errors["user_id"] = "Geef een user_id";
		}
		if(empty($data["creation_date"])){
			$errors["creation_date"] = "Geef een datum";
		}
		return $errors;
	}

	public function update($data){
		die("in update");
		$errors = $this->getValidationErrors($data);
		if(empty($errors)) {
			$sql = "UPDATE `wb_items` SET `x` = :x, `y` = :y WHERE `id` = :id";
			$stmt = $this->pdo->prepare($sql);
			$stmt->bindValue(':x', $data['x']);
			$stmt->bindValue(':y', $data['y']);
			$stmt->bindValue(':id', $data['id']);
			if($stmt->execute()) {
				$selectId=$this->pdo->lastInsertId();
				return $this->selectByIdItem($selectId);
			}
		}
		return false;
	}

	public function getValidationErrorsUpdate($data) {
		$errors = array();
		if(!isset($data['id'])) {
			$errors['id'] = "Gelieve id in te vullen";
		}
		if(!isset($data['x'])) {
			$errors['x'] = "Gelieve x waarde in te vullen";
		}
		if(!isset($data['y'])) {
			$errors['y'] = "Gelieve y waarde in te vullen";
		}
		return $errors;
	}

}