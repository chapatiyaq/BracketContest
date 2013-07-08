<?php

class Participant {

	/**
	 * Internal ID, nothing to do with the ID on LP
	 * @var int
	 */
	public $id;
	
	/**
	 * Name of the Participant
	 * @var String
	 */
	public $name;
	
	/**
	 *Full link to the bracket on LP
	 * @var String
	 */
	public $link;
	
	/**
	 * Points in the competition
	 * @var int
	 */
	public $points;
	

	public function __construct($data) {
		$this->exchangeArray($data);
	}

	public function exchangeArray($data) {
		$this->id = (isset($data['id'])) ? $data['id'] : null;
		$this->name = (isset($data['name'])) ? $data['name'] : null;
		$this->link = (isset($data['link'])) ? $data['link'] : null;
		$this->points = (isset($data['points'])) ? $data['points'] : null;
	}
	
}