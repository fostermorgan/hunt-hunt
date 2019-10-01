
<?php

Class Connection {

	private $server = "mysql:host=localhost;dbname=u700389323_morganfk";
	private $user = "u700389323_fost";
	private $pass = "Wisconsin1";
	private $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);

	protected $con;

	public function openConnection() {
		try {
			$this->con = new PDO($this->server, $this->user, $this->pass, $this->options);
			return $this->con;
		} catch (PDOException $e) {
			echo "Database error:" . $e->getMessage();
		}
	}

	public function closeConnection(){
		$this->con = null;
	}

}

?>
