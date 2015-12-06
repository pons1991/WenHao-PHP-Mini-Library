<?php

	class RegisterController{
		var $dbConnection;
		
		function __construct($conn) {
			if( $conn != null ){
				$this->dbConnection = $conn;
			}
		}
		
		public function RegisterNewUser($email, $password, $userid){
			$dbOpt = new DbOpt;
			$dbOpt->OptStatus = true;
			$dbOpt->OptMessage = "Done";
			
			if( !$this->CheckEmailExist($email) ){
				$accessUser = new AccessUser;
				$accessUser->Email = $email;
				$accessUser->Password = $password;
				
				$outputArray = array();
				$outputArray["userid"] = $userid;
				$jsonEncodedArray = json_encode($outputArray);
				$accessUser->CustomAttribute = $jsonEncodedArray;
				
				$accessUser->IsActive = true;
				$accessUser->CreatedDate = date("Y-m-d H:i:s", time());
				$accessUser->CreatedBy = $email;
				$accessUser->UpdatedDate = date("Y-m-d H:i:s", time());
				$accessUser->UpdatedBy = $email;
				
				$dbOpt = $accessUser->Add($this->dbConnection, $accessUser);
			}else{
				$dbOpt->OptStatus = false;
				$dbOpt->OptMessage = "The email has been registered. Please use other email.";
			}
			
			return $dbOpt;
		}
		
		public function CheckEmailExist($email){
			$accessUser = new AccessUser;
			$accessUser->Email = $email;
			$userArray = $accessUser->FindUserByEmail($this->dbConnection, $accessUser);
			if( count($userArray) > 0 ){
				return true;
			}else{
				return false;
			}
		}
	}

?>