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
			
			//Testing 123
			$newRole = new Role;
			$newRole->RoleName = "Hello worlld";
			$newRole->IsActive = true;
			$newRole->CreatedDate = date("Y-m-d H:i:s", time());
			$newRole->CreatedBy = "wenhao";
			$newRole->UpdatedDate = date("Y-m-d H:i:s", time());
			$newRole->UpdatedBy = "wenhao";
			$newRole->Add($dbConn, $newRole);
			
			$dbConn->CloseConnection();
		?>
	</body>
</html>