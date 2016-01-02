<?php
    include_once "../../header.php"; 
	RequiredLogin();
    
    $action = '';

	$qsArray = GetQueryString();
	if( $qsArray != null && count($qsArray) > 0 ){
		$action = strtolower($qsArray["action"]);
	}else{
		$action = 'list';
	}
	$loginCtrl = new LoginController($dbConn);
	$roleCtrl = new RoleController($dbConn);
	$userCtrl = new UserController($dbConn);
?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h2 class="sub-header">Users</h2>
          <?php 
            if( $action == "edit" ){
                include_once "edit.php";
            }else if( $action == "profile" ){
                include_once "profile.php";
            }else{
                include_once "list.php";
            }
          ?>
        </div>
      
<?php include_once "../../footer.php"; ?>