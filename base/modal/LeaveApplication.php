<?php
    class LeaveApplication extends DBModal{
        var $UserId;
        var $LeaveTypeId;
        var $Remarks;
        var $SupervisorRemarks;
        var $LeaveDateFrom;
        var $LeaveDateTo;
        var $TotalLeave;
        var $Status;
        var $ApprovedBy;
        
        //Reference class which need will be ignored when constructing the query :)
        var $LeaveStatus_META = '{"ReferenceBy":"Status","Ignore":"true"}';
        var $LeaveStatus;
        
        var $LeaveType_META = '{"ReferenceBy":"LeaveTypeId","Ignore":"true"}';
        var $LeaveType;
        
        var $AccessUser_META = '{"ReferenceBy":"UserId","Ignore":"true"}';
        var $AccessUser;
        
        var $ApprovedByUser_META = '{"ReferenceBy":"ApprovedBy","Ignore":"true", "table":"AccessUser"}';
        var $ApprovedByUser;
        
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