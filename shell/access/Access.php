<?php
	include_once "../../Base.php";
	RequiredLogin();

	$qsArray = GetQueryString();
	$action = strtolower($qsArray["action"]);
	
	$dbConn = new Connection();
	$dbConn->OpenConnection();
	$loginCtrl = new LoginController($dbConn);
	$roleCtrl = new RoleController($dbConn);
	$accessCtrl = new AccessController($dbConn);
	
	if( $action == "edit" ){
		include_once "edit.php";
	}else{
		include_once "list.php";
	}
	
	$dbConn->CloseConnection();
?>