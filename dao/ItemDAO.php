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

	public function updateContent($data){

		$errors = $this->getValidationErrors($data, 1);

		if(empty($errors)) {
			$sql = "UPDATE `wb_items` SET `title` = :title, `content` = :content, `description`= :desc WHERE `id` = :id";
			$stmt = $this->pdo->prepare($sql);
			$stmt->bindValue(':title', $data['title']);
			$stmt->bindValue(':content', $data['content']);
			$stmt->bindValue(':desc', $data['desc']);
			$stmt->bindValue(':id', $data['id']);
			if($stmt->execute()) {
				$selectId=$this->pdo->lastInsertId();
				return $this->selectItemById($selectId);
			}
		}
		return false;
	}

	public function updatePositions($data){
		$errors = $this->getValidationErrors($data, 2);
		if(empty($errors)) {
			$sql = "UPDATE `wb_items` SET `x` = :x, `y` = :y WHERE `id` = :id";
			$stmt = $this->pdo->prepare($sql);
			$stmt->bindValue(':x', $data['x']);
			$stmt->bindValue(':y', $data['y']);
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

				if(empty($data['title'])){
					$errors['title'] = 'Gelieve een titel in te vullen';
				}

				if(empty($data['content'])){
					$errors['content'] = 'Gelieve tekst in te vullen';
				}

				if(empty($data['desc'])){
					$errors['desc'] = 'Gelieve een omschrijving in te vullen';
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
				

				break;
		}

		return $errors;
	}

}