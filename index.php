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
			
			$newRole->Id = 1;
			$newRole->RoleName = "hehehe";
			$newRole->IsActive = true;
			$newRole->CreatedDate = date("Y-m-d H:i:s", time());
			$newRole->CreatedBy = "wenhao";
			$newRole->UpdatedDate = date("Y-m-d H:i:s", time());
			$newRole->UpdatedBy = "wenhao";
			$newRole->Update($dbConn, $newRole);
			
			$dbConn->CloseConnection();
			
		?>
	</body>
</html>