<?php
class Spot {
	private $db;

	public function __construct(){
		$this->db = new DataBase;
	}

	public function getAll(): array {
		$this->db->query('SELECT * FROM spots ORDER BY id ASC');
		return $this->db->resultSet();
	}


	public function getByFloor(string $floor) {
		$this->db->query('SELECT id, label, floor, x_percent, y_percent, url FROM spots WHERE floor = :floor');
		$this->db->bind(':floor',$floor);
		return $this->db->resultSet();
	} 
}
