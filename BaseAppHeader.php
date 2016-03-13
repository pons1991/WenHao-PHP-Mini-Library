<?php
	include "BaseApp.php";
?>

<!doctype>
<html>
	<head>
		<title>Wenhao Mini Library</title>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
        
        <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	
        <link rel="stylesheet" href="<?php echo $GLOBALS["DOMAIN_NAME"]; ?>/Themes/css/signin.css" />
        <link rel="stylesheet" href="<?php echo $GLOBALS["DOMAIN_NAME"]; ?>/Themes/css/dashboard.css" />
        
        <script src="<?php echo $GLOBALS["DOMAIN_NAME"]; ?>/Themes/js/index.js"></script>
    </head>
	<body>
 
 <!--<div class="loadingOverlay">
       <div class="overlayContent">
           
       </div>
    </div>-->
 
    <?php 
        include "TopNavigation.php";
    ?>
    
    <!-- boostrap placeholder - start -->
<div class="container-fluid">
      <div class="row">
          <?php 
            include_once "SideNavigation.php"; 
            ?>