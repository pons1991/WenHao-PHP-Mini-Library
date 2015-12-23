<?php
    class LeaveController extends BaseController{
        var $dbConnection;
        var $UserAccessPageSession = "USER_ACCESS_PAGE_SESSION";
        
		function __construct($conn) {
			if( $conn != null ){
				$this->dbConnection = $conn;
			}
		}
        
        public function GetLeaves(){
			$newLeaveType = new LeaveType;
			return $newLeaveType->Gets($this->dbConnection, 0, 999, null);
		}
    }
?>