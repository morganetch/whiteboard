<?php
require_once WWW_ROOT . 'dao' . DS . 'DAO.php';

class ItemDAO extends DAO {

	public function selectItemsByBoardId($id) {
		$sql = "SELECT * FROM `wb_items` WHERE wb_items.board_id = :id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':id', $id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function selectItemById($id) {
		$sql = "SELECT * FROM `wb_items` WHERE wb_items.id = :id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':id', $id);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function getHighestZIndexOnBoard($id) {
		$sql = "SELECT `z` FROM `wb_items` WHERE wb_items.board_id = :id ORDER BY `z` DESC LIMIT 1";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':id', $id);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function insertItem($data){

		$errors = $this->getValidationErrors($data, 4);
		if(empty($errors)){
			$sql = "INSERT INTO `wb_items` (`board_id`, `user_id`, `type`, `title`, `content`, `description`, `x`, `y`, `z`)
					VALUES (:board_id, :user_id, :type, :title, :content, :description, :x, :y, :z)";
			$stmt = $this->pdo->prepare($sql);
			$stmt->bindValue(":board_id", $data["board_id"]);
			$stmt->bindValue(":user_id", $data["user_id"]);
			$stmt->bindValue(":type", $data["type"]);
			$stmt->bindValue(":title", $data["title"]);
			$stmt->bindValue(":content", $data["content"]);
			$stmt->bindValue(":description", $data["description"]);
			$stmt->bindValue(":x", $data["x"]);
			$stmt->bindValue(":y", $data["y"]);
			$stmt->bindValue(":z", $data["z"]);
			if($stmt->execute()){
				$lastInsertId = $this->pdo->lastInsertId();
				return $this->selectItemById($lastInsertId);
			}
		}
	}

	public function deleteItem($id){
		// $errors = $this->getValidationErrors($data, 4);
		// if(empty($errors)){
			$sql = "DELETE FROM `wb_items` WHERE id = :id";
			$stmt = $this->pdo->prepare($sql);
			$stmt->bindValue(":id", $id);
			return $stmt->execute();
		// }
	}

	public function updateTextItem($data){

		$errors = $this->getValidationErrors($data, 1);

		if(empty($errors)) {
			$sql = "UPDATE `wb_items` SET `title` = :title, `content` = :content, `description`= :desc WHERE `id` = :id";
			$stmt = $this->pdo->prepare($sql);
			$stmt->bindValue(':title', $data['title']);
			$stmt->bindValue(':content', $data['content']);
			$stmt->bindValue(':desc', $data['desc']);
			$stmt->bindValue(':id', $data['id']);
			if($stmt->execute()) {
				return $this->selectItemById($data['id']);
			}
		}
		return false;
	}

	public function updateDescription($data){

		$errors = $this->getValidationErrors($data, 3);

		if(empty($errors)) {
			$sql = "UPDATE `wb_items` SET `title` = :title, `description`= :desc WHERE `id` = :id";
			$stmt = $this->pdo->prepare($sql);
			$stmt->bindValue(':title', $data['title']);
			$stmt->bindValue(':desc', $data['desc']);
			$stmt->bindValue(':id', $data['id']);
			if($stmt->execute()) {
				return $this->selectItemById($data['id']);
			}
		}
		return false;
	}

	public function updatePositions($data){
		$errors = $this->getValidationErrors($data, 2);
		if(empty($errors)) {
			$sql = "UPDATE `wb_items` SET `x` = :x, `y` = :y, `z` = :z WHERE `id` = :id";
			$stmt = $this->pdo->prepare($sql);
			$stmt->bindValue(':x', $data['x']);
			$stmt->bindValue(':y', $data['y']);
			$stmt->bindValue(':z', $data['z']);
			$stmt->bindValue(':id', $data['id']);
			if($stmt->execute()) {
				return $this->selectItemById($data['id']);
			}
		}
		return false;
	}

	public function getValidationErrors($data, $type) {
		
		$errors = array();

		switch($type){

			case 1:

				if(empty($data['content'])){
					$errors['content'] = 'Gelieve tekst in te vullen';
				}

				if(empty($data['id'])){
					$errors['id'] = 'Gelieve een id in te vullen';
				}

				break;

			case 2:

				if(!isset($data['id'])) {
					$errors['id'] = "Gelieve id in te vullen";
				}

				if(!isset($data['x'])) {
					$errors['x'] = "Gelieve x waarde in te vullen";
				}

				if(!isset($data['y'])) {
					$errors['y'] = "Gelieve y waarde in te vullen";
				}

				if(!isset($data['z'])) {
					$errors['z'] = "Gelieve z waarde in te vullen";
				}
				

				break;


				case 3:

					if(empty($data['id'])){
						$errors['id'] = 'Gelieve een id in te vullen';
					}

					break;

				case 4:

					if(empty($data['board_id'])) {
						$errors['board_id'] = "Gelieve board_id in te vullen";
					}

					if(empty($data['user_id'])) {
						$errors['user_id'] = "Gelieve user_id waarde in te vullen";
					}

					if(empty($data['type'])) {
						$errors['type'] = "Gelieve type waarde in te vullen";
					}

					if(empty($data['title'])) {
						$errors['title'] = "Gelieve title waarde in te vullen";
					}

					if(empty($data['content'])) {
						$errors['content'] = "Gelieve content waarde in te vullen";
					}

					if(empty($data['description'])) {
						$errors['description'] = "Gelieve description waarde in te vullen";
					}

					if(!isset($data['x'])) {
						$errors['x'] = "Gelieve x waarde in te vullen";
					}

					if(!isset($data['y'])) {
						$errors['y'] = "Gelieve y waarde in te vullen";
					}

					if(!isset($data['z'])) {
						$errors['z'] = "Gelieve z waarde in te vullen";
					}

					break;
		}

		return $errors;
	}

}