<?php

	class UserController{
		var $dbConnection;

		function __construct($conn) {
			if( $conn != null ){
				$this->dbConnection = $conn;
			}
		}
		
		public function RegisterNewUser($email, $password, $userid, $profileImagePath, $createdBy){
			$dbOpt = new DbOpt;
			$dbOpt->OptStatus = true;
			$dbOpt->OptMessage = "Done";
			
			if( !$this->CheckEmailExist($email) ){
				$accessUser = new AccessUser;
				$accessUser->Email = $email;
				$accessUser->Password = $password;
				$accessUser->ProfileImagePath = $profileImagePath;
                
				$outputArray = array();
				$outputArray["userid"] = $userid;
				$jsonEncodedArray = json_encode($outputArray);
				$accessUser->CustomAttribute = $jsonEncodedArray;
				
				$accessUser->IsActive = true;
				$accessUser->CreatedDate = date("Y-m-d H:i:s", time());
				$accessUser->CreatedBy = $createdBy;
				$accessUser->UpdatedDate = date("Y-m-d H:i:s", time());
				$accessUser->UpdatedBy = $createdBy;
				
				$dbOpt = $accessUser->Add($this->dbConnection, $accessUser);
				$dbOpt->OptObj = $accessUser;
			}else{
				$dbOpt->OptStatus = false;
				$dbOpt->OptMessage = "The email has been registered. Please use other email.";
			}
			
			return $dbOpt;
		}
		
        public function NewOrgRel($userid, $managerid, $createdby){
            
            $dbOpt = new DbOpt;
			$dbOpt->OptStatus = true;
			$dbOpt->OptMessage = "Success";
            
            $newOrgRel = new OrgRel;
            $newOrgRel->SuperiorUserId = $managerid;
            $newOrgRel->UserId = $userid;
            
            $newOrgRel->IsActive = true;
	        $newOrgRel->CreatedDate = date("Y-m-d H:i:s", time());
			$newOrgRel->CreatedBy = $createdby;
			$newOrgRel->UpdatedDate = date("Y-m-d H:i:s", time());
			$newOrgRel->UpdatedBy = $createdby;
            
            $dbOpt = $newOrgRel->Add($this->dbConnection, $newOrgRel);
		    $dbOpt->OptObj = $newOrgRel;
            
            return $dbOpt;
        }
        
        public function UpdateOrgRel($obj, $reportingTo, $updated){
            $dbOpt = new DbOpt;
			$dbOpt->OptStatus = true;
			$dbOpt->OptMessage = "Success";
			
			if( $obj != null ){
                $obj->SuperiorUserId = $reportingTo;
                $obj->UpdatedDate = date("Y-m-d H:i:s", time());
                $obj->UpdatedBy = $updated;
                
				$dbOpt = $obj->Update($this->dbConnection, $obj);
			}else{
				$dbOpt->OptStatus = false;
				$dbOpt->OptMessage = "Error when updating the reporting supervisor";
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
		
		public function GetUsers($pageIndex, $pageSize){
			$accessUser = new AccessUser;
			return $accessUser->Gets($this->dbConnection, $pageIndex-1, $pageSize, null);
		}
		
		public function GetUserById($id){
            
            $additionalParams = array(
                array('table' => 'AccessUser', 'column' => 'Id', 'value' => $id, 'type' => PDO::PARAM_INT, 'condition' => 'and')
		    );
            
			$accessUser = new AccessUser;
			return $accessUser->Gets($this->dbConnection,$GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["PAGE_SINGLE_ITEM"], $additionalParams);
		}
        
        public function GetUserOrgRel($id){
            $additionalParams = array(
                array('table' => 'OrgRel', 'column' => 'UserId', 'value' => $id, 'type' => PDO::PARAM_INT, 'condition' => 'and')
		    );
            
			$newOrgRel = new OrgRel;
			return $newOrgRel->Gets($this->dbConnection,$GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["PAGE_SINGLE_ITEM"], $additionalParams);
        }
		
        public function GetOrgRel(){
            $newOrgRel = new OrgRel;
            return $newOrgRel->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["DEFAULT_MAX_PAGE_INDEX"],null);
        }
        
        public function GetOrgRelBySupervisorId($id){
            $additionalParams = array(
                array('table' => 'OrgRel', 'column' => 'SuperiorUserId', 'value' => $id, 'type' => PDO::PARAM_INT, 'condition' => 'and')
		    );
            
			$newOrgRel = new OrgRel;
			return $newOrgRel->Gets($this->dbConnection,$GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["DEFAULT_MAX_PAGE_INDEX"], $additionalParams);
        }
        
		public function UpdateUser($usrObj, $email, $password, $userid, $profileImage, $currentUser){
			$dbOpt = new DbOpt;
			$dbOpt->OptStatus = true;
			$dbOpt->OptMessage = "Done";
			
			if( $usrObj != null ){
                $usrObj->Password = $password;
                if( $profileImage != "" ){
                    $usrObj->ProfileImagePath = $profileImage;
                }
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