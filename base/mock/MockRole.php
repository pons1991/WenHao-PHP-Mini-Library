<?php

	//Add new role
			$newRole = new Role;
			
			$newRole->RoleName = "Another Hello worlld 123";
			$newRole->IsActive = true;
			$newRole->CreatedDate = date("Y-m-d H:i:s", time());
			$newRole->CreatedBy = "wenhao";
			$newRole->UpdatedDate = date("Y-m-d H:i:s", time());
			$newRole->UpdatedBy = "wenhao";
			$dbOpt = $newRole->Add($dbConn, $newRole);
			
			echo 'Status:'. ($dbOpt->OptStatus == true ? 'true': 'false') ."<br/>";
			echo 'Message:'.$dbOpt->OptMessage;

	//Get list of roles
			$newRole = new Role;
			$roleList = $newRole->Gets($dbConn,3,2, null);
			print_r($roleList);
			
	//Get single role
			$newRole = new Role;
			$roleList = $newRole->Get($dbConn,1);
			print_r($roleList);

	//Soft delete role
			$newRole = new Role;
			$newRole->IsActive = false;
			$newRole->Id = 2;
			$newRole->Delete($dbConn,$newRole);
			
	//Update role
			$newRole = new Role;
			
			$newRole->Id = 1;
			$newRole->RoleName = "hehehe";
			$newRole->IsActive = true;
			$newRole->CreatedDate = date("Y-m-d H:i:s", time());
			$newRole->CreatedBy = "wenhao";
			$newRole->UpdatedDate = date("Y-m-d H:i:s", time());
			$newRole->UpdatedBy = "wenhao";
			$newRole->Update($dbConn, $newRole);

?>