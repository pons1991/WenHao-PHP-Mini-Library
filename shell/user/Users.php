<?php
	include_once "../../Base.php";
	RequiredLogin();
	$action = '';

	$qsArray = GetQueryString();
	if( $qsArray != null && count($qsArray) > 0 ){
		$action = strtolower($qsArray["action"]);
	}else{
		$action = 'list';
	}
	
	
	$dbConn = new Connection();
	$dbConn->OpenConnection();
	$loginCtrl = new LoginController($dbConn);
	$roleCtrl = new RoleController($dbConn);
	$accessCtrl = new AccessController($dbConn);
	$userCtrl = new UserController($dbConn);
	
	if( $action == "edit" ){
		include_once "edit.php";
	}else{
		include_once "list.php";
	}
	
	$dbConn->CloseConnection();
?>