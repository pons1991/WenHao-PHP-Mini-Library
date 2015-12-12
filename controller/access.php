<?php

	class AccessController{
		var $dbConnection;

		function __construct($conn) {
			if( $conn != null ){
				$this->dbConnection = $conn;
			}
		}
		
		public function AddNewAccess($accessName,$isAdmin ,$createdby){
			$newAccess = new AccessRole;
			$newAccess->IsAdmin = $isAdmin;
			$newAccess->RoleName = $accessName;
			$newAccess->IsActive = true;
			$newAccess->CreatedDate = date("Y-m-d H:i:s", time());
			$newAccess->CreatedBy = $createdby;
			$newAccess->UpdatedDate = date("Y-m-d H:i:s", time());
			$newAccess->UpdatedBy = $createdby;
			$dbOpt = $newAccess->Add($this->dbConnection, $newAccess);
			
			return $dbOpt;
		}
		
		public function AssignAccessRoleToUser($userid, $roleid, $createdby){
			$accessUserRole = new AccessUserRole;
			$accessUserRole->RoleId = $roleid;
			$accessUserRole->UserId = $userid;
			$accessUserRole->IsActive = true;
			$accessUserRole->CreatedDate = date("Y-m-d H:i:s", time());
			$accessUserRole->CreatedBy = $createdby;
			$accessUserRole->UpdatedDate = date("Y-m-d H:i:s", time());
			$accessUserRole->UpdatedBy = $createdby;
			$dbOpt = $accessUserRole->Add($this->dbConnection, $accessUserRole);
			
			return $dbOpt;
		}
		
		public function GetAccess(){
			$accessRole = new AccessRole;
			return $accessRole->Gets($this->dbConnection, 0, 999, null);
		}
		
		public function GetAccessByUserId($id){
			$additionalParams = array(
				':UserId' => array('value' => $id, 'type' => PDO::PARAM_INT, 'condition' => 'and'),
			);
			$accessUserRole = new AccessUserRole;
			return $accessUserRole->Gets($this->dbConnection, 0, 999, $additionalParams);
		}
		
		public function UpdateAccess($accObj, $roleId, $currentUser){
			$dbOpt = new DbOpt;
			$dbOpt->OptStatus = true;
			$dbOpt->OptMessage = "Done";
			
			if( $accObj != null ){
				$accObj->RoleId = $roleId;
				
				$accObj->UpdatedDate = date("Y-m-d H:i:s", time());
				$accObj->UpdatedBy = $currentUser;
				
				$dbOpt = $accObj->Update($this->dbConnection, $accObj);
			}else{
				$dbOpt->OptStatus = false;
				$dbOpt->OptMessage = "Error when updating the user";
			}
			
			return $dbOpt;
		}
	}

?>