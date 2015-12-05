<?php 
	include "base/Base.php";
	EnableError();
?>

<?php include_once "header.php"; ?>

		<h1>Harlo world!</h1>
		<?php
		
			$dbConn = new Connection();
			$dbConn->OpenConnection();
			
			
			
			
			$dbConn->CloseConnection();
			
		?>
<?php include_once "footer.php"; ?>