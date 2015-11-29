<?php

	//Add new access role
			$newRole = new AccessRole;
			
			$newRole->RoleName = "root";
			$newRole->IsAdmin = true;
			$newRole->IsActive = true;
			$newRole->CreatedDate = date("Y-m-d H:i:s", time());
			$newRole->CreatedBy = "wenhao";
			$newRole->UpdatedDate = date("Y-m-d H:i:s", time());
			$newRole->UpdatedBy = "wenhao";
			$dbOpt = $newRole->Add($dbConn, $newRole);
			
			echo 'Access Role <br/>';
			echo 'Status:'. ($dbOpt->OptStatus == true ? 'true': 'false') ."<br/>";
			echo 'Message:'.$dbOpt->OptMessage;


?>