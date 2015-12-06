
<?php 
	include_once "header.php";
	$registerCtrl = null;
	$loginCtrl = null;
	
	if (isset($_POST["submit"])){
		$email = $_POST["email"];
		$password = $_POST["password"];
		$userid = $_POST["userid"];
		
		if( !empty($email) && !empty($password) && !empty($userid)){
			$dbConn = new Connection();
			$dbConn->OpenConnection();
			
			$registerCtrl = new RegisterController($dbConn);
			$dbOpt = $registerCtrl->RegisterNewUser($email, $password, $userid);
			
			if( $dbOpt->OptStatus ){
				//Redirect to login user
				$loginCtrl = new LoginController($dbConn);
				$dbOptLogin = $loginCtrl->VerifyUser($email, $password);
				
				print_r($dbOptLogin);
				
				if( $dbOptLogin->OptStatus ){
					Redirection($GLOBALS["DOMAIN_NAME"]);
				}
			}else{
				echo $dbOpt->OptMessage;
			}
			
			$dbConn->CloseConnection();
		}else{
			echo 'verify data ! please';
		}
		
	}
?>
		<h1>Register !</h1>
		<form method="post" action="register.php">
			<label for="email">Email</label>
			<input type="email" name="email" id="email" />
			<br/>
			<label for="password">Password</label>
			<input type="password" name="password" id="password" />
			<br/>
			<label for="userid">User Id</label>
			<input type="text" name="userid" id="userid" />
			
			<br/>
			<input type="submit" name="submit" id="submit" value="Register" />
		</form>
		
<?php include_once "footer.php"; ?>