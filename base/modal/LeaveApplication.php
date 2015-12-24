<?php
    class LeaveApplication extends DBModal{
        var $UserId;
        var $LeaveTypeId;
        var $Remarks;
        var $LeaveDateFrom;
        var $LeaveDateTo;
        var $TotalLeave;
        var $Status;
        var $ApprovedBy;
        
        //Reference class which need will be ignored when constructing the query :)
        //The naming convention is based on {class name}_IGNORE_REFERENCEBY_{foreign key name}
        var $LeaveStatus_IGNORE_REFERENCEBY_Status;
        var $LeaveType_IGNORE_REFERENCEBY_LeaveTypeId;
        var $AccessUser_IGNORE_REFERENCEBY_UserId;
        
        public function IsLeaveDateValid($dbConn, $obj){
            //Temporary comment out for not being used at the moment :)
			// $additionalParams = array(
			// 	':UserId' => array('value' => $obj->UserId, 'type' => PDO::PARAM_INT, 'condition' => 'and'),
			// 	':LeaveDate' => array('value' => $obj->LeaveDate, 'type' => PDO::PARAM_STR, 'condition' => 'and'),
            //     ':Status' => array('value' => $obj->LeaveDate, 'type' => PDO::PARAM_STR, 'condition' => 'and'),
            //     ':Status' => array('value' => $obj->LeaveDate, 'type' => PDO::PARAM_STR, 'condition' => 'or')
			// );
			// 
			// $returnLeave = $this->Gets($dbConn,0, 1, $additionalParams);
			// return $returnLeave;
		}
    }
?>