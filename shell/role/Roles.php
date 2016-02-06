<?php
    include_once "../../header.php"; 
	RequiredLogin();
    
    $action = '';

	$qsArray = GetQueryString();
	if( array_key_exists("action", $qsArray)){
		$action = strtolower($qsArray["action"]);
	}else{
		$action = 'list';
	}
    
	$leaveCtrl = new LeaveController($dbConn);
    $userCtrl = new UserController($dbConn);
	$loginCtrl = new LoginController($dbConn);
    $roleCtrl = new RoleController($dbConn);
    $pageCtrl = new PageController($dbConn);
?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h1 class="page-header">Roles</h1>
    <?php 
        if( $action == "edit" ){
            include_once "edit.php";
        }else{
            include_once "list.php";
        }
    ?>
</div>      
<?php include_once "../../footer.php"; ?>