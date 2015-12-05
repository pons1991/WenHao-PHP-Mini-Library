<?php
	//php file
	include_once "Error.php";
	include_once "Const.php";
	include_once "DbOpt.php";
	include_once "DBModal.php";
	
	//Load db connection
	include_once "database/Connection.php";
	
	//Load modal
	include_once "base/modal/AccessUser.php";
	include_once "base/modal/AccessRole.php";
	include_once "base/modal/Role.php";
	include_once "base/modal/RoleLeave.php";
	
	//Load controller
	include_once "controller/login.php";
	
	//Load pipeline function
	include_once "pipeline/RequiredLogin.php";
	
	//initialize session
	session_start();
	
?>