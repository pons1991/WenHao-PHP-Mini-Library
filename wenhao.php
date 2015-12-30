<?php

    error_reporting(-1);
			ini_set('display_errors', 'On');
    $ServerName = "localhost";
     $DatabaseName = "bakhache_leave";
     $UserName = "bakhache_leave";
     $Password = "hlKGaRl6DHtH";
    
    $DbLink = new PDO("mysql:host=localhost;dbname=bakhache_leave", "bakhache_leave", "hlKGaRl6DHtH");;
    
    echo print_r($DbLink);
    
?>
