<?php

	class hunt	{
		private $id;
		private $name;
		private $locationName;
		private $animalName;
		private $lat;
		private $lng;
		private $conn;
		private $tableName = "hunts";
		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setName($name) { $this->name = $name; }
		function getName() { return $this->name; }
		function setAddress($locationName) { $this->$locationName = $locationName; }
		function getAddress() { return $this->$locationName; }
		function setType($animalName) { $this->$animalName = $animalName; }
		function getType() { return $this->$animalName; }
		function setLat($lat) { $this->lat = $lat; }
		function getLat() { return $this->lat; }
		function setLng($lng) { $this->lng = $lng; }
		function getLng() { return $this->lng; }
		public function __construct() {
			require_once('db_connection.php');
			$conn = new Connection;
			$this->conn = $conn->openConnection();
		}
		public function getAllHunts($query) {
			$sql = "$query";
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
	}
?>
