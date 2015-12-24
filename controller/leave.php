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
        
        public function GetLeaveByUserId($userId){
            $newLeaveApplication = new LeaveApplication;
            
            $additionalParams = array(
                array('table' => 'LeaveApplication', 'column' => 'UserId', 'value' => $userId, 'type' => PDO::PARAM_INT, 'condition' => 'and')
		    );
			
			$returnLeave = $newLeaveApplication->GetByQuery($this->dbConnection,0, 999, $additionalParams);
            
            echo print_r($returnLeave);
            
			return $returnLeave;
        }
        
        public function ApplyLeave($from, $to, $diff, $type, $remarks, $userId, $email){
            
            $dbOptResponse = new DbOpt;
            
            $newLeave = new LeaveApplication;
            $newLeave->UserId = $userId;
            $newLeave->LeaveTypeId = $type;
		    $newLeave->Remarks = $remarks;
			$newLeave->LeaveDateFrom = $from;
            $newLeave->LeaveDateTo = $to;
            $newLeave->TotalLeave = $diff;
			$newLeave->Status = 1;
            $newLeave->ApprovedBy = $userId;
			$newLeave->IsActive = true;
			$newLeave->CreatedDate = date("Y-m-d H:i:s", time());
			$newLeave->CreatedBy = $email;
			$newLeave->UpdatedDate = date("Y-m-d H:i:s", time());
			$newLeave->UpdatedBy = $email;

            $dbOptResponse = $newLeave->Add($this->dbConnection, $newLeave);
            $dbOptResponse->OptObj = $newLeave;
			
            
            return $dbOptResponse;
        }
        
        public function CheckDuplicateLeave($from, $userId){
            $newLeaveType = new LeaveType;
			return $newLeaveType->IsLeaveDateValid($this->dbConnection, 0, 999, null);
        }
    }
?>