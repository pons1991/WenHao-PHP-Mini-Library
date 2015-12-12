<?php

	class RoleController{
		var $dbConnection;

		function __construct($conn) {
			if( $conn != null ){
				$this->dbConnection = $conn;
			}
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
		
		public function GetRoleById($id){
			$newRole = new Role;
			return $newRole->Get($this->dbConnection,$id);
		}
		
		public function GetRoleByUserId($id){
			$additionalParams = array(
				':UserId' => array('value' => $id, 'type' => PDO::PARAM_INT, 'condition' => 'and'),
			);
			$userRole = new UserRole;
			return $userRole->Gets($this->dbConnection,0, 1, $additionalParams);
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
	}
?>