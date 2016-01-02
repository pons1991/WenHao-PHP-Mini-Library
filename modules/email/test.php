<?php 

    require_once "mini.emailmanager.php";
    require_once "mini.email.php";
    
    $em = new MiniEmailManager;
    
    $miniEmail = new MiniEmail;
    
    $addressArray = array();
    array_push($addressArray, 'programmerpig@gmail.com');
    
    $miniEmail->toAddressList = $addressArray;
    $miniEmail->subject = 'Test email from EM';
    $miniEmail->message = 'Another test message only, no worry';
    
    $em->SendEmail($miniEmail);
?>