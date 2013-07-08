<?php

class ContestTable {

	private $conn;

	public function __construct() {
		$this->conn = Connection::getConnection();
	}

	/**
	 * Get all contests that are set public
	 * @return Array of Contest objects
	 */
	public function fetchAll($orderby = array('id' => 'DESC')) {
		$orderbyStrings = array();
		foreach ($orderby as $field => $order) {
			$orderbyStrings[] = $field . ' ' . $order;
		}
		$sql = 'SELECT id, title, bracket, game, startdate, submissiondate, enddate 
				FROM contests 
				WHERE public = 1 '
				. 'ORDER BY ' . implode(', ', $orderbyStrings);
		$resultSet = $this->conn->query($sql);
		$contests = array();
		foreach ($resultSet->fetchAll() as $key => $data) {
			$contests[] = new Contest($data);
		}
		return $contests;
	}

	/**
	 * Get all information about the contest
	 * @param int $id - specifies contest
	 */
	public function fetchByID($id) {
		$stmt = $this->conn->prepare('SELECT id, title, bracket, game, startdate, submissiondate, enddate
			FROM contests WHERE id = :id AND public = 1');
		$stmt->bindValue(':id', $id);
		$stmt->execute();
		$data = $stmt->fetch();
		return new Contest($data);
	}
	
	/**
	 * Get all contests that are set public & about the specified game
	 * @param String $game
	 */
	public function fetchByGame($game) {
		$stmt = $this->conn->prepare('SELECT id, title, bracket, game, startdate, submissiondate, enddate
			FROM contests WHERE game = :game AND public = 1');
		$stmt->bindValue(':game', $game);
		$stmt->execute();
		$contests = array();
		foreach ($stmt->fetchAll() as $key => $data) {
			$contests[] = new Contest($data);
		}
		return $contests;
	}

}