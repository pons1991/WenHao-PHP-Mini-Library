<?php
    class LeaveApplicationController extends BaseController{
        var $dbConnection;
        var $UserAccessPageSession = "";
        
		function __construct($conn) {
			if( $conn != null ){
				$this->dbConnection = $conn;
			}
		}
        
        // public function GetLeaves(){
		// 	$newLeaveType = new LeaveType;
		// 	return $newLeaveType->Gets($this->dbConnection, 0, 999, null);
		// }
        
    }
?>