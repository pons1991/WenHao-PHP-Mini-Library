<?php
    include_once "../../header.php"; 
	RequiredLogin();
    
    $action = '';

	$qsArray = GetQueryString();
	if( $qsArray != null && count($qsArray) > 0 ){
		$action = strtolower($qsArray["action"]);
	}else{
		$action = 'list';
	}
    
	$leaveCtrl = new LeaveController($dbConn);
?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h2 class="sub-header">Leave Application</h2>
          <?php
            include_once "edit.php";
            // if( $action == "edit" ){
            //     include_once "edit.php";
            // }else{
            //     include_once "list.php";
            // }
          ?>
        </div>
      
<?php include_once "../../footer.php"; ?>