<?php
    class LeaveController extends BaseController{
        var $dbConnection;
        var $UserAccessPageSession = "USER_ACCESS_PAGE_SESSION";
        
		function __construct($conn) {
			if( $conn != null ){
				$this->dbConnection = $conn;
			}
		}
        
        public function AddNewProRatedLeave($userid,$year,$proratedString, $email){
            $newProRatedLeave = new ProRatedLeave;
            $newProRatedLeave->UserId = $userid;
            $newProRatedLeave->ProRatedYear = $year;
            $newProRatedLeave->ProRatedAttributes = $proratedString;
            $newProRatedLeave->IsActive = true;
            $newProRatedLeave->CreatedDate = date("Y-m-d H:i:s", time());
			$newProRatedLeave->CreatedBy = $email;
			$newProRatedLeave->UpdatedDate = date("Y-m-d H:i:s", time());
			$newProRatedLeave->UpdatedBy = $email;
            
            $dbOptResponse = $newProRatedLeave->Add($this->dbConnection, $newProRatedLeave);
            $dbOptResponse->OptObj = $newProRatedLeave;
			
            return $dbOptResponse;
        }
        
        public function GetProRatedLeave($id){
            $newProRatedLeave = new ProRatedLeave;
            
            $additionalParams = array(
                array('table' => 'ProRatedLeave', 'column' => 'Id', 'value' => $id, 'type' => PDO::PARAM_INT, 'condition' => 'and')
		    );
            
            $returnProRatedLeave = $newProRatedLeave->Gets($this->dbConnection,0, 1, $additionalParams);
			return $returnProRatedLeave;
        }
        
        public function UpdateProRatedLeave($obj, $currentUser){
            $dbOpt = new DbOpt;
			$dbOpt->OptStatus = true;
			$dbOpt->OptMessage = "Done";
			
			if( $obj != null ){
				$obj->UpdatedDate = date("Y-m-d H:i:s", time());
				$obj->UpdatedBy = $currentUser;
				
				$dbOpt = $obj->Update($this->dbConnection, $obj);
			}else{
				$dbOpt->OptStatus = false;
				$dbOpt->OptMessage = "Error when updating pro rated leave";
			}
			
			return $dbOpt;
        }
        
        public function GetLeaveTypes(){
			$newLeaveType = new LeaveType;
			return $newLeaveType->Gets($this->dbConnection, 0, 999, null);
		}
        
        public function GetLeaveByUserId($userId){
            $newLeaveApplication = new LeaveApplication;
            
            $additionalParams = array(
                array('table' => 'LeaveApplication', 'column' => 'UserId', 'value' => $userId, 'type' => PDO::PARAM_INT, 'condition' => 'and')
		    );
			
			$returnLeave = $newLeaveApplication->Gets($this->dbConnection,0, 999, $additionalParams);
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