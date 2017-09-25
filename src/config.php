<?php
	namespace eCrawler;
	
	/*
	|---------------------------------------------------------------
	| eCrawler
	|---------------------------------------------------------------
	*/
	
	class db{
		protected $mysqli;

		public function __construct($arg){
			$this->mysqli = @mysqli_connect($arg['dbHost'], $arg['dbUser'], $arg['dbPass'], $arg['dbName']);
			if(mysqli_connect_errno()){
				die(json_encode(array(
					'code'		=> 500,
					'message'	=> 'Error :)'
				)));
			}
		}

		public function query($sql, $cb){
			// echo $sql.'<br>';
			$result = $this->mysqli->query($sql);
			if($this->mysqli->connect_errno == 0){
				if($cb){
					$data = array();
					while($row = mysqli_fetch_assoc($result)){
						$data[] = $row;
					}
					return $data;
				}
			}					
		}
	}
?>