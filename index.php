<?php 
	include "base/Base.php";
	
	EnableError();
?>

<!doctype>
<html>
	<head>
		<title>Wenhao Mini Library</title>
	</head>
	<body>
		<h1>Harlo world!</h1>
		<?php
		
			$dbConn = new Connection();
			$dbConn->OpenConnection();
			
			
			$newRole = new Role;
			
			$newRole->RoleName = "Another Hello worlld 123";
			$newRole->IsActive = true;
			$newRole->CreatedDate = date("Y-m-d H:i:s", time());
			$newRole->CreatedBy = "wenhao";
			$newRole->UpdatedDate = date("Y-m-d H:i:s", time());
			$newRole->UpdatedBy = "wenhao";
			$dbOpt = $newRole->Add($dbConn, $newRole);
			
			echo 'Role <br/>';
			echo 'Status:'. ($dbOpt->OptStatus == true ? 'true': 'false') ."<br/>";
			echo 'Message:'.$dbOpt->OptMessage;
			
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
			
			$dbConn->CloseConnection();
			
		?>
	</body>
</html>