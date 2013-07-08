<?php 

interface BCT
{
	/**
	 * Returns an array that contains all contests
	 */
	public function getContests($orderby);
	
	/**
	 * Returns a Contest object of the specified contest
	 * @param int $id
	 */
	public function getContest($contestid);
	
	/**
	 * Returns an array that contains all contests of that game
	 * Games are "StarCraft II", "StarCraft:Brood War", "Dota 2"
	 * @param int $id
	 */
	public function getContestsByGame($game);
	
	/**
	 * Returns an array of Participant objects
	 * @param unknown_type $contestid
	 */
	public function getParticipants($contestid);
}


class Controller implements BCT {
	
	public function getContests($orderby, $publicOnly = true) {
		$contests = new ContestTable();
		$resultSet = $contests->fetchAll($orderby, $publicOnly);
		return $resultSet;
	}
	
	public function getContest($id, $publicOnly = true) {
		$contests = new ContestTable();
		$result = $contests->fetchByID($id, $publicOnly);
		return $result;
	}
	
	public function getContestsByGame($game) {
		$contests = new ContestTable();
		$resultSet = $contests->fetchByGame($game);
		return $resultSet;
	}
	
	public function getParticipants($id) {
		$userTable = new ParticipantTable();
		$resultSet = $userTable->fetchByContest($id);
		return $resultSet;
	}
	
	public function getParticipantByName($name) {
		$userTable = new ParticipantTable();
		$resultSet = $userTable->fetchByName($name);
		return $resultSet;
	}
	
	public function getSubmissions($id) {
		$userTable = new ParticipantTable();
		$resultSet = $userTable->fetchSubmissions($id);
		return $resultSet;
	}
	
}