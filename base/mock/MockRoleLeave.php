<?php

	//To add new role leave
			$newRoleLeave = new RoleLeave;
			$newRoleLeave->RoleId = 1;
			$newRoleLeave->NumberOfLeave = 10;
			$newRoleLeave->IsActive = true;
			$newRoleLeave->CreatedDate = date("Y-m-d H:i:s", time());
			$newRoleLeave->CreatedBy = "wenhao";
			$newRoleLeave->UpdatedDate = date("Y-m-d H:i:s", time());
			$newRoleLeave->UpdatedBy = "wenhao";
			$dbOpt = $newRoleLeave->Add($dbConn, $newRoleLeave);
			
			echo 'Role Leave <br/>';
			echo 'Status:'. ($dbOpt->OptStatus == true ? 'true': 'false') ."<br/>";
			echo 'Message:'.$dbOpt->OptMessage;

?>