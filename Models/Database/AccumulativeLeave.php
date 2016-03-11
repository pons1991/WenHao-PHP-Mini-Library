<?php
    namespace Models\Database;
	class AccumulativeLeave extends DBModal{
		var $UserId;
        var $ExpiredYear;
        var $LeaveTypeId;
        var $Remarks;
        var $AccumulativeLeaveNumber;
        
        var $AccessUser_META = '{"ReferenceBy":"UserId","Ignore":"true"}';
        var $AccessUser;
        
        var $LeaveType_META = '{"ReferenceBy":"LeaveTypeId","Ignore":"true"}';
        var $LeaveType;
	}
?>