<?php
	//php file
	include_once "base/Error.php";
	include_once "base/Const.php";
	include_once "base/DbOpt.php";
	include_once "base/DBModal.php";
    include_once "controller/base.php";
	
	//Load db connection
	include_once "base/database/Connection.php";
	
    //Load pipeline function
	include_once "pipeline/GetQueryString.php";
	include_once "pipeline/Redirection.php";
	include_once "pipeline/RequiredLogin.php";
    include_once "pipeline/PHPSelf.php";
    include_once "pipeline/LinkManager.php";
    include_once "pipeline/SessionHelper.php";
    
	//Load modal
	include_once "base/modal/AccessUser.php";
	include_once "base/modal/AccessRole.php";
	include_once "base/modal/Role.php";
	include_once "base/modal/RoleLeave.php";
	include_once "base/modal/UserRole.php";
	include_once "base/modal/AccessUserRole.php";
    include_once "base/modal/Page.php";
    include_once "base/modal/OrgRel.php";
    include_once "base/modal/LeaveType.php";
	include_once "base/modal/LeaveApplication.php";
    
	//Load controller
	include_once "controller/login.php";
	include_once "controller/role.php";
	include_once "controller/roleleave.php";
	include_once "controller/access.php";
	include_once "controller/user.php";
    include_once "controller/page.php";
    include_once "controller/leave.php";
	
	//initialize session
	if (session_status() == PHP_SESSION_NONE || session_id() == '') {
		//start the session is not yet start
		session_start();
	}
	
	//Enable error
	EnableError();
    
    //Global variable - start
    $dbConn = new Connection();
    $dbConn->OpenConnection();
    //Global variable - end
?>