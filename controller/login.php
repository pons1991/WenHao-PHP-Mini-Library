<?php
	class LoginController{
		var $dbConnection;
		var $UserSessionKey = "USER_SESSION";
        var $UserAccessSessionKey = "USER_ACCESS_SESSION";
		
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
				
				$accessUser->Email = $username;
				$accessUser->Password = $password;
				
				$returnUser = $accessUser->IsUserVerified($this->dbConnection, $accessUser);
				$countUser = count($returnUser);
				if( $countUser == 1 ){
					//Email and password is matched
					//Register user detail in this session
                    
                    $roleController = new RoleController($this->dbConnection);
                    $userRoleList = $roleController->GetRoleLeaveByUserId($returnUser[0]->Id);
                    $userAccessList = $roleController->GetRoleAccessByUserId($userRoleList[0]->RoleId);
                    if( $userRoleList != null && count($userRoleList) == 1 ){
                        $this->RegisterUserInSession($userRoleList[0]);
                    }
                    if( $userAccessList != null && count($userAccessList) == 1 ){
                        $this->RegisterUserAccessInSession($userAccessList[0]);
                    }
				}else if($countUser == 0){
					//email and password is not match
					$resp->OptStatus = false;
					$resp->OptMessage = "Email and password does not match. Please try again.";
				}
			}else{
				$resp->OptStatus = false;
				$resp->OptMessage = "Unable to contact to database";
			}
			
			return $resp;
		}
		
		public function RegisterUserInSession($userObj){
			if( $userObj != null ){
				//Register user into the session
				$_SESSION[$this->UserSessionKey] = $userObj;
			}
		}
        
        public function RegisterUserAccessInSession($userAccessObj){
            if( $userAccessObj != null ){
				//Register user access into the session
				$_SESSION[$this->UserAccessSessionKey] = $userAccessObj;
			}
        }
		
        public function CheckUserAccessSession(){
			if(isset($_SESSION[$this->UserAccessSessionKey]) && !empty($_SESSION[$this->UserAccessSessionKey])) {
				return true;
			}else{
				return false;
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
        
        public function GetUserAccessSession(){
			if($this->CheckUserAccessSession()) {
				$userAccess = $_SESSION[$this->UserAccessSessionKey];
				return $userAccess;
			}else{
				return null;
			}
		}
		
		public function GetUserName(){
            $user = $this->GetUserSession();
			$accessUser = $user->AccessUser;
			if( $accessUser != null ){
				return $accessUser->Email;
			}
		}
        
        public function GetUserId(){
            $user = $this->GetUserSession();
			$accessUser = $user->AccessUser;
			if( $accessUser != null ){
				return $accessUser->Id;
			}
        }
        
        public function GetUserRoleName(){
            $user = $this->GetUserSession();
            if( $user != null ){
                $role = $user->Role;
                if( $role != null ){
                    return $role->RoleName;
                }
            }
        }
        
        public function GetUserRoleId(){
            $user = $this->GetUserSession();
            if( $user != null ){
                $role = $user->Role;
                if( $role != null ){
                    return $role->Id;
                }
            }
        }
        
        public function GetUserAccessPageIds(){
            $userAccess = $this->GetUserAccessSession();
			if( $userAccess != null ){
				$jsonStr = $userAccess->RoleAccessAttributes;
                if( !empty($jsonStr)){
                    $jsonArr = json_decode($jsonStr, true);
                    if( $jsonArr != null && array_key_exists('PageIds', $jsonArr) ){
                        $arrStr = $jsonArr['PageIds'];
                        if( !empty($arrStr) ){
                            return explode(',',$arrStr);
                        }
                    }
                }
			}
            
            return array();
        }
	}
?>
