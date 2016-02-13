<?php
    include_once "../../header.php"; 
	RequiredLogin();
    
    $action = '';

	$qsArray = GetQueryString();
	if( array_key_exists("action", $qsArray)){
		$action = strtolower($qsArray["action"]);
	}else{
		$action = 'index.php';
	}
    
	$leaveCtrl = new LeaveController($dbConn);
    $userCtrl = new UserController($dbConn);
	$loginCtrl = new LoginController($dbConn);
    $roleCtrl = new RoleController($dbConn);
    $pageCtrl = new PageController($dbConn);
?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h1 class="page-header">Reporting</h1>
    <?php 
        include_once "leavereporting.php";
    ?>
</div>      
<?php include_once "../../footer.php"; ?>