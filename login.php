<?php 
	include "Base.php";
	EnableError();
	
	$loginCtrl = null;
	
	if (isset($_POST["submit"])){
		$email = $_POST["email"];
		$password = $_POST["password"];
		
		if( !empty($email) && !empty($password)){
			$dbConn = new Connection();
			$dbConn->OpenConnection();
			$loginCtrl = new LoginController($dbConn);
			$loginCtrl->VerifyUser($email,$password);
			
			Redirection($GLOBALS["DOMAIN_NAME"]);
			
			$dbConn->CloseConnection();
		}else{
			echo 'verify data ! please';
		}
		
	}
	
	if( $loginCtrl == null ){
		$loginCtrl = new LoginController(null);
		echo $loginCtrl->GetUserName();
	}
?>

<?php include_once "header.php"; ?>

		<h1>Login page</h1>
		<form method="post" action="login.php">
			<input type="text" name="email" id="email" />
			<input type="password" name="password" id="password" />
			<input type="submit" name="submit" id="submit" value="Login" />
		</form>
		
		
<?php include_once "footer.php"; ?>