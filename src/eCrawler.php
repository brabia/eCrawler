<?php
	namespace eCrawler;
	
	/*
	|---------------------------------------------------------------
	| StarredCrawler
	|---------------------------------------------------------------
	*/
	
	ini_set('display_errors', '1');
	require('config.php');
	require('crawler.php');
	
	class sAPI{
		private $dbSettings = array(
			'dev' => array(
				'dbUser' => 'root',
				'dbPass' => '',
				'dbHost' => 'localhost',
				'dbName' => 'starred'
			),
			'prod' => array(
				'dbUser' => '--',
				'dbPass' => '--',
				'dbHost' => 'localhost',
				'dbName' => 'eCrawler'
			)
		);
		private $db;
		
		function __construct($isProd){
			if($isProd){
				$this->db = new db($this->dbSettings['prod']);
			}else{$this->db = new db($this->dbSettings['dev']);}			
		}
		
		public function getContent($url){
			try{
				if(!isset($_GET['pageUrl']) OR empty($_GET['pageUrl'])){
					$this->clearRequest(array(
						'code' => 100,
						'message' => 'Page Url is missing',
					));
				}
				new getContent();
			}catch(Exception $e){
				echo 'Message: ' .$e->getMessage();
			}
		}
		
		public function setEmailsList(){
			try{
				if($_SERVER['REQUEST_METHOD'] !== 'POST'){
					$this->clearRequest(array(
						'code'		=> 101,
						'message'	=> 'Request method is not allowed by the server!',
					));
				}
				if(!isset($_POST['emailsList']) OR empty($_POST['emailsList'])){
					$this->clearRequest(array(
						'code'		=> 102,
						'message'	=> 'No data to save',
					));
				}
				
				$emails		= json_decode($_POST['emailsList'], true);
				
				$hostName	= $emails['hostName'];
				if(empty($hostName)){
					$this->clearRequest(array(
						'code'		=> 103,
						'message'	=> 'Host Name cannot be empty',
					));
				}
				
				$emailsList = $emails['emailsList'];
				$sql		= "select * from emails where host = '$hostName' limit 0, 1";
				$arr		= $this->db->query($sql, true);
				if(count(json_decode($emailsList, true)) == 0){
					$this->clearRequest(array(
						'code' => 109,
						'message' => 'Emails list is missing or empty !'
					));
				}else{
					$sql = "UPDATE emails set emails = '$emailsList' where host = '$hostName'";
					if(count($arr) == 0){
						$sql = "INSERT into emails (host, emails) values ('$hostName', '$emailsList')";
					}
					// echo $sql;
					$arr		= $this->db->query($sql, false);
					$this->clearRequest(array(
						'code' => 200,
						'message' => count(json_decode($emailsList, true)).' email(s) saved into db!'
					));
				}				
			}catch(Exception $e){
				echo 'Message: ' .$e->getMessage();
			}
		}
		
		public function getEmailsList(){
			try{
				$hostName	= $_GET['hostName'];
				$sql		= "select * from emails where host = '$hostName'";
				$arr		= $this->db->query($sql, true);
				// echo '<pre>';print_r($arr[0]['emails']);echo '</pre>';
				if(count($arr) == 0){					
					$this->clearRequest(array(
						'code' => 201,
						'hostName' => $hostName,
						'emails' => 'No Datas'
					));
				}else{					
					$this->clearRequest(array(
						'code' => 200,
						'hostName' => $hostName,
						'emails' => $arr[0]['emails']
					));
				}
			}catch(Exception $e){
				echo 'Message: ' .$e->getMessage();
			}
		}
		
		public function doAction(){
			try{
				if(isset($_GET['action'])){
					switch($_GET['action']){
						case 'getEmails':
							$this->getEmailsList();
						break;
						case 'saveToDb':
							$this->setEmailsList();
						break;
						case 'getContent': 
							$this->getContent($_GET['pageUrl']);
						break;
					}
				}
			}catch(Exception $e){
				echo 'Message: ' .$e->getMessage();
			}
		}
		
		public function clearRequest($arr){
			die(json_encode($arr));
		}
	}
	
	$sAPI = new sAPI(true); // true for production mode
	$sAPI->doAction();		
?>