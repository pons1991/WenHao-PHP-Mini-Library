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
        
        public function ApplyLeave($from, $type, $halfDay, $remarks, $userId, $email){
            
            $dbOptResponse = new DbOpt;
            
            $newLeave = new LeaveApplication;
            $newLeave->UserId = $userId;
		    $newLeave->IsFullDay = $halfDay;
		    $newLeave->Remarks = $remarks;
			$newLeave->LeaveDate = $from;
			$newLeave->Status = 1;
            $newLeave->ApprovedBy = $userId;
			$newLeave->IsActive = true;
			$newLeave->CreatedDate = date("Y-m-d H:i:s", time());
			$newLeave->CreatedBy = $email;
			$newLeave->UpdatedDate = date("Y-m-d H:i:s", time());
			$newLeave->UpdatedBy = $email;
		  
            $leaveList = $newLeave->IsLeaveDateValid($this->dbConnection, $newLeave);
            if( count($leaveList) == 0){
                $dbOptResponse = $newLeave->Add($this->dbConnection, $newLeave);
                $dbOptResponse->OptObj = $newLeave;
            }else{
                $dbOptResponse->OptStatus = false;
                $dbOptResponse->OptMessage = "Error: Duplicated leave";
            }
			
            
            return $dbOptResponse;
        }
        
        public function CheckDuplicateLeave($from, $userId){
            $newLeaveType = new LeaveType;
			return $newLeaveType->IsLeaveDateValid($this->dbConnection, 0, 999, null);
        }
    }
?>