<?php

	class UserController{
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
				$dbOpt->OptObj = $accessUser;
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
		
		public function GetUsers(){
			$accessUser = new AccessUser;
			return $accessUser->Gets($this->dbConnection, 0, 999, null);
		}
		
		public function GetUserById($id){
			$accessUser = new AccessUser;
			return $accessUser->Get($this->dbConnection,$id);
		}
		
        public function GetOrgRel(){
            $newOrgRel = new OrgRel;
            return $newOrgRel->Gets($this->dbConnection, 0,999,null);
        }
        
		public function UpdateUser($usrObj, $email, $password, $userid, $currentUser){
			$dbOpt = new DbOpt;
			$dbOpt->OptStatus = true;
			$dbOpt->OptMessage = "Done";
			
			if( $usrObj != null ){
                $usrObj->Password = $password;
			    $outputArray = array();
                $outputArray["userid"] = $userid;
                $jsonEncodedArray = json_encode($outputArray);
                $usrObj->CustomAttribute = $jsonEncodedArray;
						
                $usrObj->UpdatedDate = date("Y-m-d H:i:s", time());
                $usrObj->UpdatedBy = $currentUser;
                
				if( $usrObj->Email != $email ){
					if( !$this->CheckEmailExist($email) ){
						$usrObj->Email = $email;
						$dbOpt = $usrObj->Update($this->dbConnection, $usrObj);
					}else{
						$dbOpt->OptStatus = false;
						$dbOpt->OptMessage = "The email has been registered. Please use other email.";
					}
				}else{
                    $dbOpt = $usrObj->Update($this->dbConnection, $usrObj);
                }
			}else{
				$dbOpt->OptStatus = false;
				$dbOpt->OptMessage = "Error when updating the user";
			}
			
			return $dbOpt;
		}
	}

?>