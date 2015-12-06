<?php 
	include_once "header.php"; 
	RequiredLogin();
?>

		<h1>Harlo world!</h1>
		<?php
			
			$dbConn = new Connection();
			$dbConn->OpenConnection();
			
			
			
			
			$dbConn->CloseConnection();
			
		?>
<?php include_once "footer.php"; ?>