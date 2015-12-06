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
		
	}
?>