<?php

	function RequiredLogin(){
		$loginController = new LoginController(null);
		$isLogin = $loginController->CheckUserSession();
		if( !$isLogin ){
			//is not login - redirect to login page
			//construct login redirect url
			$redirectURL = 'login.php';
			//header("Location: ".$redirectURL);
			//echo '<script> location.replace("'.$redirectURL.'"); </script>';
			Redirection($redirectURL);
		}
	}

?>