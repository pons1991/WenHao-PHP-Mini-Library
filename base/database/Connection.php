<?php
	class Connection{
		private $ConfigFile;
		
		private $ServerName;
		private $DatabaseName;
		private $UserName;
		private $Password;
		private $DbLink;
		
		function __construct() {
            $this->ConfigFile = $GLOBALS["CONNECTION_CONFIG_NAME"];
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
			try{
				$this->DbLink = new PDO($this->GetHost(), $this->UserName, $this->Password);
				$this->DbLink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $e){
				//Log this exception into log file
			}
		}
		
		function ExecutePrepare($sqlQuery,$sqlMap){
            if( $this->DbLink != null ){
                $prepareStatement = $this->DbLink->prepare($sqlQuery);
			
                if( count($sqlMap) > 0 ){
                    foreach($sqlMap as $key => $valueArr ){
                        $prepareStatement->bindParam($key, $valueArr["value"], $valueArr["type"]);
                    }
                }
                
                $prepareStatement->execute();
                return $prepareStatement;
            }else{
                return null;
            }
		}
		
		function ExecuteSelectPrepare($sqlQuery,$sqlMap){
			$prepareStatement = $this->ExecutePrepare($sqlQuery,$sqlMap);
			if( $prepareStatement != null ){
				$prepareStatement->setFetchMode(PDO::FETCH_ASSOC);
				$result = $prepareStatement->fetchAll();
				return $result;
			}
		}
		
		function ExecuteQuery($sqlQuery){
			if( $this->DbLink != null ){
				$this->DbLink->exec($sqlQuery);
			}else{
				return false;
			}
		}
		
		function CloseConnection(){
			$this->DbLink = null;
		}
		
		function IsConnectionEstablished(){
			return $this->DbLink != null ? true : false;
		}
		
		function GetLastInsertedId(){
			return $this->DbLink->lastInsertId();
		}
	}

?>