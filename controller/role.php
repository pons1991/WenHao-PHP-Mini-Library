<?php

	class RoleController{
		var $dbConnection;

		function __construct($conn) {
			if( $conn != null ){
				$this->dbConnection = $conn;
			}
		}
		
        public function GetRoleAccessByRoleId($roleId){
            $newRoleAccess = new RoleAccess;
            
            $additionalParams = array(
                array('table' => 'RoleAccess', 'column' => 'RoleId', 'value' => $roleId, 'type' => PDO::PARAM_INT, 'condition' => 'and')
		    );
			
			$returnRoleAccessList = $newRoleAccess->Gets($this->dbConnection,0, 1, $additionalParams);
			return $returnRoleAccessList;
        }
        
        public function GetRoleLeaveList(){
            $newRoleLeave = new RoleLeave;
            
            $returnRoleLeave = $newRoleLeave->Gets($this->dbConnection,0, 999, null);
			return $returnRoleLeave;
        }
        
        public function AddNewRoleLeave($roleid, $leaveString, $email){
            
            $newRoleLeave = new RoleLeave;
            $newRoleLeave->RoleId = $roleid;
            $newRoleLeave->LeaveAttribute = $leaveString;
            $newRoleLeave->IsActive = true;
            $newRoleLeave->CreatedDate = date("Y-m-d H:i:s", time());
			$newRoleLeave->CreatedBy = $email;
			$newRoleLeave->UpdatedDate = date("Y-m-d H:i:s", time());
			$newRoleLeave->UpdatedBy = $email;
            
            $dbOptResponse = $newRoleLeave->Add($this->dbConnection, $newRoleLeave);
            $dbOptResponse->OptObj = $newRoleLeave;
			
            return $dbOptResponse;
		}
        
        public function AddNewRoleAccess($roleid, $accessString, $email){
            
            $newRoleAccess = new RoleAccess;
            $newRoleAccess->RoleId = $roleid;
            $newRoleAccess->RoleAccessAttributes = $accessString;
            $newRoleAccess->IsActive = true;
            $newRoleAccess->CreatedDate = date("Y-m-d H:i:s", time());
			$newRoleAccess->CreatedBy = $email;
			$newRoleAccess->UpdatedDate = date("Y-m-d H:i:s", time());
			$newRoleAccess->UpdatedBy = $email;
            
            $dbOptResponse = $newRoleAccess->Add($this->dbConnection, $newRoleAccess);
            $dbOptResponse->OptObj = $newRoleAccess;
			
            return $dbOptResponse;
		}
        
		public function NewRole($rolename, $createdby){
			
			$newRole = new Role;
			$newRole->RoleName = $rolename;
			$newRole->IsActive = true;
			$newRole->CreatedDate = date("Y-m-d H:i:s", time());
			$newRole->CreatedBy = $createdby;
			$newRole->UpdatedDate = date("Y-m-d H:i:s", time());
			$newRole->UpdatedBy = $createdby;
			$dbOpt = $newRole->Add($this->dbConnection, $newRole);
			
			if( $dbOpt->OptStatus ){
				$dbOpt->OptObj = $newRole;
			}
			
			return $dbOpt;
		}
		
		public function AssignRoleToUser($userid, $roleid, $createdby){
			$userRole = new UserRole;
			$userRole->RoleId = $roleid;
			$userRole->UserId = $userid;
			$userRole->IsActive = true;
			$userRole->CreatedDate = date("Y-m-d H:i:s", time());
			$userRole->CreatedBy = $createdby;
			$userRole->UpdatedDate = date("Y-m-d H:i:s", time());
			$userRole->UpdatedBy = $createdby;
			$dbOpt = $userRole->Add($this->dbConnection, $userRole);
			$dbOpt->OptObj = $userRole;
			return $dbOpt;
		}
		
		public function GetRoles(){
			$newRole = new Role;
			return $newRole->Gets($this->dbConnection, 0, 999, null);
		}
		
        public function GetRoleLeaveById($id){
            $newRoleLeave = new RoleLeave;
            
            $additionalParams = array(
                array('table' => 'RoleLeave', 'column' => 'Id', 'value' => $id, 'type' => PDO::PARAM_INT, 'condition' => 'and')
		    );
			
			$returnLeave = $newRoleLeave->Gets($this->dbConnection,0, 1, $additionalParams);
			return $returnLeave;
        }
		
        public function GetRoleLeaveByUserId($id){
            $newUserRole = new UserRole;
            
            $additionalParams = array(
                array('table' => 'UserRole', 'column' => 'UserId', 'value' => $id, 'type' => PDO::PARAM_INT, 'condition' => 'and')
		    );
			
			$returnRoleLeave = $newUserRole->Gets($this->dbConnection,0, 1, $additionalParams);
			return $returnRoleLeave;
        }
        
        public function GetRoleAccessByUserId($id){
            $newRoleAccess = new RoleAccess;
            
            $additionalParams = array(
                array('table' => 'RoleAccess', 'column' => 'RoleId', 'value' => $id, 'type' => PDO::PARAM_INT, 'condition' => 'and')
		    );
			
			$returnRoleAccessList = $newRoleAccess->Gets($this->dbConnection,0, 1, $additionalParams);
			return $returnRoleAccessList;
        }
		
		public function UpdateRole($roleObj, $roleId, $currentUser){
			$dbOpt = new DbOpt;
			$dbOpt->OptStatus = true;
			$dbOpt->OptMessage = "Done";
			
			if( $roleObj != null ){
                $roleObj->RoleId = $roleId;
				$roleObj->UpdatedDate = date("Y-m-d H:i:s", time());
				$roleObj->UpdatedBy = $currentUser;
				
				$dbOpt = $roleObj->Update($this->dbConnection, $roleObj);
			}else{
				$dbOpt->OptStatus = false;
				$dbOpt->OptMessage = "Error when updating the user";
			}
			
			return $dbOpt;
		}
        
        public function UpdateRoleLeave($obj, $currentUser){
            $dbOpt = new DbOpt;
			$dbOpt->OptStatus = true;
			$dbOpt->OptMessage = "Done";
			
			if( $obj != null ){
				$obj->UpdatedDate = date("Y-m-d H:i:s", time());
				$obj->UpdatedBy = $currentUser;
				
				$dbOpt = $obj->Update($this->dbConnection, $obj);
			}else{
				$dbOpt->OptStatus = false;
				$dbOpt->OptMessage = "Error when updating role leave";
			}
			
			return $dbOpt;
        }
        
        public function UpdateRoleAccess($obj, $currentUser){
            $dbOpt = new DbOpt;
			$dbOpt->OptStatus = true;
			$dbOpt->OptMessage = "Done";
			
			if( $obj != null ){
				$obj->UpdatedDate = date("Y-m-d H:i:s", time());
				$obj->UpdatedBy = $currentUser;
				
				$dbOpt = $obj->Update($this->dbConnection, $obj);
			}else{
				$dbOpt->OptStatus = false;
				$dbOpt->OptMessage = "Error when updating role leave";
			}
			
			return $dbOpt;
        }
	}
?>