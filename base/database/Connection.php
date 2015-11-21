<?php
	class Connection{
		private $ConfigFile = "/config/connectionstring.json";
		
		private $ServerName;
		private $DatabaseName;
		private $UserName;
		private $Password;
		private $DbLink;
		
		function __construct() {
			$this->GetConfig();
		}
		
		function GetHost(){
			return "mysql:host=".$this->ServerName.";dbname=".$this->DatabaseName;
		}
		
		function GetConfig(){
			$string = file_get_contents($GLOBALS["DOMAIN_NAME"].$this->ConfigFile);
			$json = json_decode($string, true);

			$this->ServerName = $json["ServerName"];
			$this->DatabaseName = $json["DatabaseName"];
			$this->UserName = $json["UserName"];
			$this->Password = $json["Password"];
		}
		
		function OpenConnection(){
			$this->DbLink = new PDO($this->GetHost(), $this->UserName, $this->Password);
		}
		
		function CloseConnection(){
			$this->DbLink = null;
		}
		
		function IsConnectionEstablished(){
			return $this->DbLink != null ? true : false;
		}
	}

?>