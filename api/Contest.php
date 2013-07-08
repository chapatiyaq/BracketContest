<?php


class Contest {

	/**
	 * Unique ID of the contest
	 * @var Integer $id
	 */
	public $id;
	
	/**
	 * Title of the contest
	 * @var String $title
	 */
	public $title;
	
	/**
	 * Actual results of the contest as submitted by an admin (in Liquipedia template form)
	 * @var String $bracket
	 */
	public $bracket;
	
	/**
	 * StarCraft:Brood War, StarCraft II, Dota 2
	 * @var String $game
	 */
	public $game;

	public $startdate;
	public $submissiondate;
	public $enddate;

	public function __construct($data) {
		$this->exchangeArray($data);
	}

	public function exchangeArray($data) {
		$this->id = (isset($data['id'])) ? $data['id'] : null;
		$this->title = (isset($data['title'])) ? $data['title'] : null;
		$this->bracket = (isset($data['bracket'])) ? $data['bracket'] : null;
		$this->game = (isset($data['game'])) ? $data['game'] : null;
		$this->startdate = (isset($data['startdate'])) ? $data['startdate'] : null;
		$this->submissiondate = (isset($data['submissiondate'])) ? $data['submissiondate'] : null;
		$this->enddate = (isset($data['enddate'])) ? $data['enddate'] : null;
	}
	
}