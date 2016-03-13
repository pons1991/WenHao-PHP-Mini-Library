<?php
    include_once "../../BaseAppHeader.php";
    $sessionManager->RequiredLogin();
    $action = '';
    $qsArray = pipeline\QueryStringManager::GetActionQueryString();
    if( $qsArray != null && array_key_exists("action", $qsArray) ){
		$action = strtolower($qsArray["action"]);
	}else{
		$action = 'list';
	}
    
    $loginCtrl = new Controllers\LoginController(null);
    $userCtrl = new Controllers\UserController($dbConn);
	$leaveCtrl = new Controllers\LeaveController($dbConn);
    $roleCtrl = new Controllers\RoleController($dbConn);
    
?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h2 class="sub-header">Leave Application</h2>
          <?php
                include_once "overall.php";
                include_once "pendingapproval.php";
                include_once "leavestatus.php";
          ?>
        </div>
      
<?php include_once "../../BaseAppFooter.php"; ?>