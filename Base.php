<?php
	//php file
	include_once "base/Error.php";
	include_once "base/Const.php";
	include_once "base/DbOpt.php";
	include_once "base/DBModal.php";
	
	//Load db connection
	include_once "base/database/Connection.php";
	
	//Load modal
	include_once "base/modal/AccessUser.php";
	include_once "base/modal/AccessRole.php";
	include_once "base/modal/Role.php";
	include_once "base/modal/RoleLeave.php";
	include_once "base/modal/UserRole.php";
	include_once "base/modal/AccessUserRole.php";
	
	//Load controller
	include_once "controller/login.php";
	include_once "controller/register.php";
	include_once "controller/role.php";
	include_once "controller/roleleave.php";
	include_once "controller/access.php";
	include_once "controller/user.php";
	
	//Load pipeline function
	include_once "pipeline/GetQueryString.php";
	include_once "pipeline/Redirection.php";
	include_once "pipeline/RequiredLogin.php";
	
	//initialize session
	if (session_status() == PHP_SESSION_NONE || session_id() == '') {
		//start the session is not yet start
		session_start();
	}
	
	//Enable error
	EnableError();
?>