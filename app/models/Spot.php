<?php
class Spot {
	private $db;

	public function __construct(){
		$this->db = new DataBase;
	}

	public function getSpots(){
		$this->db->query('SELECT * FROM spots ORDER BY date_ajout DESC');
		return $this->db->resultSet();
	}
	public function getSpotsById($id) {
		$this->db->query('SELECT * FROM spots WHERE id = :id');
		$this->db->bind(':id',$id);
		return $this->db->single();
	} 
}
