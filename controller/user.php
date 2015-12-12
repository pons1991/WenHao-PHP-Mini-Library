<?php

	class UserController{
		var $dbConnection;

		function __construct($conn) {
			if( $conn != null ){
				$this->dbConnection = $conn;
			}
		}
		
		public function GetUsers(){
			$accessUser = new AccessUser;
			return $accessUser->Gets($this->dbConnection, 0, 999, null);
		}
		
		public function GetUserById($id){
			$accessUser = new AccessUser;
			return $accessUser->Get($this->dbConnection,$id);
		}
		
		public function UpdateUser($usrObj, $email, $password, $userid, $currentUser){
			$dbOpt = new DbOpt;
			$dbOpt->OptStatus = true;
			$dbOpt->OptMessage = "Done";
			
			if( $usrObj != null ){
				
				$usrObj->Email = $email;
				$usrObj->Password = $password;
				
				$outputArray = array();
				$outputArray["userid"] = $userid;
				$jsonEncodedArray = json_encode($outputArray);
				$usrObj->CustomAttribute = $jsonEncodedArray;
				
				$usrObj->UpdatedDate = date("Y-m-d H:i:s", time());
				$usrObj->UpdatedBy = $currentUser;
				
				$dbOpt = $usrObj->Update($this->dbConnection, $usrObj);
			}else{
				$dbOpt->OptStatus = false;
				$dbOpt->OptMessage = "Error when updating the user";
			}
			
			return $dbOpt;
		}
	}

?>