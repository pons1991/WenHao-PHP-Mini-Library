<?php 
	include "Base.php";
	EnableError();
	
	$loginCtrl = null;
	$loginPageErrorMessage = '';
    
	if (isset($_POST["submit"])){
		$email = $_POST["email"];
		$password = $_POST["password"];
		
		if( !empty($email) && !empty($password)){
			
			$loginCtrl = new LoginController($dbConn);
			$loginResp = $loginCtrl->VerifyUser($email,$password);
			
            if( $loginResp-> OptStatus){
                Redirection($GLOBALS["MAIN_PAGE"]);
            }else{
                $loginPageErrorMessage = $loginResp->OptMessage;
            }
		}else{
			$loginPageErrorMessage = 'Invalid credential';
		}
	}
	
	if( $loginCtrl == null ){
		$loginCtrl = new LoginController(null);
		//echo $loginCtrl->GetUserName();
	}
?>

<?php include_once "header.php"; ?>

<div class="container">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-3">
            <?php echo '<img class="img-responsive" src="'.GetFriendlyUrl("/Themes/img/compLogo.jpg").'" />'; ?>
        </div>
        <div class="col-md-6">
            <form class="form-signin" method="post" action="login.php">
                <h2 class="form-signin-heading">Bakhache Luxuries System Login</h2>
                <label for="inputEmail" class="sr-only">Email address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Email address" required="" autofocus="">
                <label for="inputPassword" class="sr-only">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="">
                
                <?php
                    if( !empty($loginPageErrorMessage) ){
                        ?>
                        <div class="alert alert-danger" role="alert">
                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                            <span class="sr-only">Error:</span>
                            <?php echo $loginPageErrorMessage; ?>
                        </div>
                        <?php
                    }
                ?>
                

                <button class="btn btn-lg btn-primary btn-block" name="submit" id="submit" type="submit">Sign in</button>
            </form>
        </div>
        <div class="col-md-2"></div>
    </div>
    </div>
		
<?php include_once "BaseAppFooter.php"; ?>