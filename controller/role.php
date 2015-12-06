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
	}
?>