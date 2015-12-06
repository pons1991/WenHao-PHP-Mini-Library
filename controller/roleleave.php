<?php

	class RoleLeaveController{
		var $dbConnection;

		function __construct($conn) {
			if( $conn != null ){
				$this->dbConnection = $conn;
			}
		}
		
		public function AddNewLeave($roleid, $leave, $createdby){
			$newRoleLeave = new RoleLeave;
			$newRoleLeave->RoleId = $roleid;
			$newRoleLeave->NumberOfLeave = $leave;
			$newRoleLeave->IsActive = true;
			$newRoleLeave->CreatedDate = date("Y-m-d H:i:s", time());
			$newRoleLeave->CreatedBy = $createdby;
			$newRoleLeave->UpdatedDate = date("Y-m-d H:i:s", time());
			$newRoleLeave->UpdatedBy = $createdby;
			$dbOpt = $newRoleLeave->Add($this->dbConnection, $newRoleLeave);
			
			return $dbOpt;
		}
	}

?>