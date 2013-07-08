<?php

class ParticipantTable {

	private $conn;

	public function __construct() {
		$this->conn = Connection::getConnection();
	}

	public function fetchAll() {
		$sql = 'SELECT id, name FROM users';
		$resultSet = $this->conn->query($sql);
		$users = array();
		foreach ($resultSet->fetchAll() as $key => $data) {
			$users[] = new Participant($data);
		}
		return $users;
	}

	public function fetchByID($id) {
		$stmt = $this->conn->prepare('SELECT id, name FROM users WHERE id = :id');
		$stmt->bindValue(':id', $id);
		if ($stmt->execute()) {
			$data = $stmt->fetch();
			return new Participant($data);
		}
		return NULL;
	}

	public function fetchByName($name) {
		$stmt = $this->conn->prepare('SELECT id, name FROM users WHERE name = :name');
		$stmt->bindValue(':name', $name);
		if ($stmt->execute()) {
			$data = $stmt->fetch();
			return new Participant($data);
		}
		return NULL;
	}
		
	public function fetchSubmissions($id) {
		$stmt = $this->conn->prepare('SELECT c.id, c.title, c.game, u.link, u.points FROM contests c, userentries u 
										WHERE u.contestid = c.id AND userid = ?');
		if ($stmt->execute(array($id))) {
			return $stmt->fetchAll();
		}
		return NULL;
	}
	
	public function fetchByContest($id) {
		$stmt = $this->conn->prepare('SELECT id, name, link, points
										FROM users, userentries
										WHERE id = userid AND contestid = :contestid
										ORDER BY points DESC');
		$stmt->bindValue(':contestid', $id);
		$users = array();
		if ($stmt->execute()) {
			foreach ($stmt->fetchAll() as $key => $data) {
				$users[] = new Participant($data);
			}
		}
		return $users;
	}

}