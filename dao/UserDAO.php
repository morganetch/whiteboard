<?php
require_once WWW_ROOT . 'dao' . DS . 'DAO.php';

class UserDAO extends DAO {

	public function selectAll(){
		$sql = "SELECT * FROM `wb_users`";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function searchUsers($search, $ownId, $board_id){
		$sql = "SELECT * FROM `wb_users` WHERE `username` LIKE :username AND wb_users.id != :ownId AND `id` NOT IN (SELECT `user_id` FROM `wb_invites` WHERE board_id = :board_id) LIMIT 10";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':username', '%' . $search . '%');
		$stmt->bindValue(":ownId", $ownId);
		$stmt->bindValue(":board_id", $board_id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function selectById($id){
		$sql = "SELECT * FROM `wb_users` WHERE `id` = :id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(":id", $id);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function insert($data){
		$errors = $this->getValidationErrors($data);
		if(empty($errors)){
			$sql = "INSERT INTO `wb_users` (`username`, `password`)
					VALUES (:username, :password)";
			$stmt = $this->pdo->prepare($sql);
			$stmt->bindValue(":username", $data["username"]);
			$stmt->bindValue(":password", $data["password"]);
			if($stmt->execute()){
				$lastInsertId = $this->pdo->lastInsertId();
				return $this->selectById($lastInsertId);
			}
		}
	}

	public function getValidationErrors($data){
		$errors = array();

		if(empty($data["username"])){
			$errors["username"] = "please fill in an username";
		}

		if(empty($data["password"])){
			$errors["password"] = "please fill in a password";
		}

		return $errors;
	}

	public function selectByUsername($username){
		$sql = "SELECT * FROM `wb_users` WHERE `username` = :username";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue("username", $username);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
}