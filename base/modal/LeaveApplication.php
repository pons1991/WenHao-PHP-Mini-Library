<?php
    class LeaveApplication extends DBModal{
        var $UserId;
        var $IsFullDay;
        var $Remarks;
        var $LeaveDate;
        var $Status;
        var $ApprovedBy;
        
        public function IsLeaveDateValid($dbConn, $obj){
			$additionalParams = array(
				':UserId' => array('value' => $obj->UserId, 'type' => PDO::PARAM_INT, 'condition' => 'and'),
				':LeaveDate' => array('value' => $obj->LeaveDate, 'type' => PDO::PARAM_STR, 'condition' => 'and'),
                ':Status' => array('value' => $obj->LeaveDate, 'type' => PDO::PARAM_STR, 'condition' => 'and'),
                ':Status' => array('value' => $obj->LeaveDate, 'type' => PDO::PARAM_STR, 'condition' => 'or')
			);
			
			$returnLeave = $this->Gets($dbConn,0, 1, $additionalParams);
			return $returnLeave;
		}
    }
?>