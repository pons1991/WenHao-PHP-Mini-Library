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
			$newRole->Gets($dbConn,0,0);
			
			
			/*$classname = "Role";
			$role = new $classname();
			print_r($role);*/
			
			$dbConn->CloseConnection();
		?>
	</body>
</html>