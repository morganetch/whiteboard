<?php
require_once WWW_ROOT . 'dao' . DS . 'DAO.php';

class BoardDAO extends DAO {

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
		$errors = $this->getValidationErrors($data, 1);
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

	public function update($data){

		$errors = $this->getValidationErrors($data, 2);

		if(empty($errors)) {
			$sql = "UPDATE `wb_boards` SET `name` = :name WHERE `id` = :id";
			$stmt = $this->pdo->prepare($sql);
			$stmt->bindValue(':name', $data['name']);
			$stmt->bindValue(':id', $data['id']);
			if($stmt->execute()) {
				return $this->selectBoardById($data['id']);
			}
		}
		return false;
	}

	public function getValidationErrors($data, $form){
		$errors = array();

		switch ($form) {
			case 1:
				if(empty($data["name"])){
					$errors["name"] = "Vul aub een naam in";
				}
				if(empty($data["user_id"])){
					$errors["user_id"] = "Geef een user_id aub";
				}
				if(empty($data["creation_date"])){
					$errors["creation_date"] = "Geef een datum";
				}
				break;
			
			case 2:
				if(empty($data["name"])){
					$errors["name"] = "Vul aub een naam in";
				}
				if(empty($data["id"])){
					$errors["id"] = "Vul aub een id in";
				}
				break;
		}
		return $errors;
	}

}