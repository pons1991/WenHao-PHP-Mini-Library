<?php
	class LoginController{
		var $dbConnection;
		var $UserSessionKey = "USER_SESSION";
		
		function __construct($conn) {
			if( $conn != null ){
				$this->dbConnection = $conn;
			}
		}
		
		public function VerifyUser($username, $password){
			$resp = new DbOpt;
			$resp->OptStatus = true;
			$resp->OptMessage = "Verified";
		
			if( $this->dbConnection != null ){
				$accessUser = new AccessUser;
				
				$accessUser->UserName = $username;
				$accessUser->Password = $password;
				$returnUser = $accessUser->IsUserVerified($this->dbConnection, $accessUser);
				
				$countUser = count($returnUser);
				if( $countUser == 1 ){
					//Email and password is matched
					//Register user detail in this session
					$this->RegisterUserInSession($returnUser[0]);
				}else if($countUser == 0){
					//email and password is not match
					$resp->OptStatus = false;
					$resp->OptMessage = "Email and password does not match. Please try again.";
				}
			}else{
				$resp->OptStatus = false;
				$resp->OptMessage = "Unable to contact to database";
			}
		}
		
		public function RegisterUserInSession($userObj){
			if( $userObj != null ){
				//Register user into the session
				$_SESSION[$this->UserSessionKey] = $userObj;
			}
		}
		
		public function CheckUserSession(){
			if(isset($_SESSION[$this->UserSessionKey]) && !empty($_SESSION[$this->UserSessionKey])) {
				return true;
			}else{
				return false;
			}
		}
		
		public function GetUserSession(){
			if($this->CheckUserSession()) {
				$accessUser = $_SESSION[$this->UserSessionKey];
				return $accessUser;
			}else{
				return null;
			}
		}
		
		public function GetUserName(){
			$accessUser = $this->GetUserSession();
			if( $accessUser != null ){
				return $accessUser->UserName;
			}
		}
	}
?>
