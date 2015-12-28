<?php

	class RoleController{
		var $dbConnection;

		function __construct($conn) {
			if( $conn != null ){
				$this->dbConnection = $conn;
			}
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
			
			$returnLeave = $newRoleLeave->Gets($this->dbConnection,0, 999, $additionalParams);
			return $returnLeave;
        }
		
        public function GetRoleLeaveByUserId($id){
            $newUserRole = new UserRole;
            
            $additionalParams = array(
                array('table' => 'UserRole', 'column' => 'UserId', 'value' => $id, 'type' => PDO::PARAM_INT, 'condition' => 'and')
		    );
			
			$returnRoleLeave = $newUserRole->Gets($this->dbConnection,0, 999, $additionalParams);
			return $returnRoleLeave;
        }
        
		public function GetRoleByUserId($id){
			$additionalParams = array(
				':UserId' => array('value' => $id, 'type' => PDO::PARAM_INT, 'condition' => 'and'),
			);
			$userRole = new UserRole;
			return $userRole->Gets($this->dbConnection,0, 1, $additionalParams);
		}
		
		public function UpdateRole($roleObj, $currentUser){
			$dbOpt = new DbOpt;
			$dbOpt->OptStatus = true;
			$dbOpt->OptMessage = "Done";
			
			if( $roleObj != null ){
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
	}
?>