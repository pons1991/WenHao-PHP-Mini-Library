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
			//Testing 123
			$dbCon = new Connection;
			$dbCon->OpenConnection();
			$dbCon->CloseConnection();
		?>
	</body>
</html>