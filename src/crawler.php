<?php
	namespace eCrawler;
	
	/*
	|---------------------------------------------------------------
	| eCrawler
	|---------------------------------------------------------------
	*/

	class getContent{
		private $url;
		private $fakeBots;
		private $headers;
		private $postParams;

		function __construct(){			
			$this->url = $_GET['pageUrl'];
			$this->fakeBots = $this->fakeBots();
			$this->headers = isset($_GET['pageHeaders']) ? $_GET['pageHeaders']:array();
			$this->postParams = file_get_contents("php://input");

			if(!$this->url){
				echo 'Missing Parameters';
			}
			$this->sendCurl();
		}
		
		public function fakeBots(){
			/* -----------------
				to avoid any kind of block due to number of ajax request,
				I am using a random bot while sending request.
				For now, just one bot, for demonstration purposes only m using this bot
			 ----------------- */
			$a = array(
				'Accelatech RSSCrawler/0.4'
			);
			return $a[0];
		}

		public function setHeader(){
			if(isset($_SERVER['HTTP_ORIGIN'])){
				header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
				header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN'].'');
				header('Access-Control-Allow-Credentials: true');
				header('Access-Control-Max-Age: 86400');
			}
			
			if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
				if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
					header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
				if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
					header('Access-Control-Allow-Headers: '.$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'].'}');
			}
		}

		public function sendCurl(){			
			$this->setHeader();
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_USERAGENT, $this->fakeBots);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array($this->headers));
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, ($this->postParams)?1:0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postParams);

			$result = curl_exec($ch);
			curl_close($ch);
			echo $result;
		}
	}
?>