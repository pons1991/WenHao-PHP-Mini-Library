<?php 
    include_once("Const.php");
    include_once("AutoLoader.php");
    
    $errorManager = new pipeline\ErrorManager;
    $errorManager->EnableError();
    
    //Initialize User Session
    $sessionManager = new pipeline\SessionManager;
    $sessionManager->InitializeUserSession();
    
    //DB Connection open
    $dbConn = new Database\Connection\Connection();
    //$dbConn->OpenConnection();
    
    
    //Model Response
    //$dbOpt = new Models\Response\DbOpt;
    
    //Database Model
    //$dbModel = new Models\Database\DBModal;
    //$accessUser = new Models\Database\AccessUser;
    
    //Controller
    //$announcementCtrl = new Controllers\AnnouncementController;
    
    //DB Connection close
    //$dbConn->CloseConnection();
?>