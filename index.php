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
			
			/*
			$newRole = new Role;
			$roleList = $newRole->Gets($dbConn,0,2, null);
			print_r($roleList);
			*/
			
			$newRole = new Role;
			$newRole->IsActive = false;
			$newRole->Id = 2;
			$newRole->Delete($dbConn,$newRole);
			
			$dbConn->CloseConnection();
			
		?>
	</body>
</html>