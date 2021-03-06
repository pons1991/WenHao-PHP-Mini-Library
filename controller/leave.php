<?php

class LeaveController extends BaseController {

    var $dbConnection;
    var $UserAccessPageSession = "USER_ACCESS_PAGE_SESSION";

    function __construct($conn) {
        if ($conn != null) {
            $this->dbConnection = $conn;
        }
    }

    public function GetProRatedLeaveList($pageIndex, $pageSize) {
        $newProRatedLeave = new ProRatedLeave;

        $returnProRatedLeave = $newProRatedLeave->Gets($this->dbConnection, $pageIndex-1, $pageSize, null);
        return $returnProRatedLeave;
    }

    public function AddNewProRatedLeave($userid, $year, $proratedString, $email) {
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

    public function GetProRatedLeave($id) {
        $newProRatedLeave = new ProRatedLeave;

        $additionalParams = array(
            array('table' => 'ProRatedLeave', 'column' => 'Id', 'value' => $id, 'type' => PDO::PARAM_INT, 'condition' => 'and')
        );

        $returnProRatedLeave = $newProRatedLeave->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["PAGE_SINGLE_ITEM"], $additionalParams);
        return $returnProRatedLeave;
    }

    public function GetProRatedLeaveByUserId($id) {
        $newProRatedLeave = new ProRatedLeave;

        $additionalParams = array(
            array('table' => 'ProRatedLeave', 'column' => 'UserId', 'value' => $id, 'type' => PDO::PARAM_INT, 'condition' => 'and')
        );

        $returnProRatedLeave = $newProRatedLeave->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["PAGE_SINGLE_ITEM"], $additionalParams);
        return $returnProRatedLeave;
    }

    public function GetProRatedLeaveByUserIdAndYear($id, $year) {
        $newProRatedLeave = new ProRatedLeave;

        $additionalParams = array(
            array('table' => 'ProRatedLeave', 'column' => 'UserId', 'value' => $id, 'type' => PDO::PARAM_INT, 'condition' => 'and'),
            array('table' => 'ProRatedLeave', 'column' => 'ProRatedYear', 'value' => $year, 'type' => PDO::PARAM_STR, 'condition' => 'and')
        );

        $returnProRatedLeave = $newProRatedLeave->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["PAGE_SINGLE_ITEM"], $additionalParams);
        return $returnProRatedLeave;
    }

    public function UpdateProRatedLeave($obj, $currentUser) {
        $dbOpt = new DbOpt;
        $dbOpt->OptStatus = true;
        $dbOpt->OptMessage = "Done";

        if ($obj != null) {
            $obj->UpdatedDate = date("Y-m-d H:i:s", time());
            $obj->UpdatedBy = $currentUser;

            $dbOpt = $obj->Update($this->dbConnection, $obj);
        } else {
            $dbOpt->OptStatus = false;
            $dbOpt->OptMessage = "Error when updating pro rated leave";
        }

        return $dbOpt;
    }

    public function AddLeaveType($leaveName, $bringForward, $accumulative, $email) {
        $newLeaveType = new LeaveType;
        $newLeaveType->LeaveName = $leaveName;
        $newLeaveType->IsAllowToBringForward = $bringForward;
        $newLeaveType->IsAllowToAccumulate = $accumulative;
        $newLeaveType->IsActive = true;
        $newLeaveType->CreatedDate = date("Y-m-d H:i:s", time());
        $newLeaveType->CreatedBy = $email;
        $newLeaveType->UpdatedDate = date("Y-m-d H:i:s", time());
        $newLeaveType->UpdatedBy = $email;

        $dbOptResponse = $newLeaveType->Add($this->dbConnection, $newLeaveType);
        $dbOptResponse->OptObj = $newLeaveType;

        return $dbOptResponse;
    }

    public function UpdateLeaveType($obj, $email) {
        $dbOpt = new DbOpt;
        $dbOpt->OptStatus = true;
        $dbOpt->OptMessage = "Success";

        if ($obj != null) {
            $obj->UpdatedDate = date("Y-m-d H:i:s", time());
            $obj->UpdatedBy = $email;

            $dbOpt = $obj->Update($this->dbConnection, $obj);
        } else {
            $dbOpt->OptStatus = false;
            $dbOpt->OptMessage = "Error when updating leave type";
        }

        return $dbOpt;
    }

    public function GetLeaveTypeById($id) {
        $newLeaveType = new LeaveType;

        $additionalParams = array(
            array('table' => 'LeaveType', 'column' => 'Id', 'value' => $id, 'type' => PDO::PARAM_INT, 'condition' => 'and')
        );

        $returnLeaveTypeList = $newLeaveType->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["PAGE_SINGLE_ITEM"], $additionalParams);
        return $returnLeaveTypeList;
    }

    public function GetAccumulativeLeaveType() {
        $newLeaveType = new LeaveType;

        $additionalParams = array(
            array('table' => 'LeaveType', 'column' => 'IsAllowToAccumulate', 'value' => 1, 'type' => PDO::PARAM_INT, 'condition' => 'and')
        );

        $returnLeaveTypeList = $newLeaveType->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["PAGE_SINGLE_ITEM"], $additionalParams);
        return $returnLeaveTypeList;
    }

    public function GetLeaveTypes($pageIndex, $pageSize) {
        $newLeaveType = new LeaveType;
        return $newLeaveType->Gets($this->dbConnection, $pageIndex-1, $pageSize, null);
    }

    public function GetLeaveAccess() {
        $newLeaveAccess = new LeaveAccess;
        return $newLeaveAccess->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["DEFAULT_MAX_PAGE_INDEX"], null);
    }

    public function GetLeaveByStatus($statusId) {
        $newLeaveApplication = new LeaveApplication;

        $additionalParams = array(
            array('table' => 'LeaveApplication', 'column' => 'Status', 'value' => $statusId, 'type' => PDO::PARAM_INT, 'condition' => 'and')
        );

        $returnLeave = $newLeaveApplication->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["DEFAULT_MAX_PAGE_INDEX"], $additionalParams);
        return $returnLeave;
    }

    public function GetLeaveByUserId($userId) {
        $newLeaveApplication = new LeaveApplication;

        $additionalParams = array(
            array('table' => 'LeaveApplication', 'column' => 'UserId', 'value' => $userId, 'type' => PDO::PARAM_INT, 'condition' => 'and')
        );

        $returnLeave = $newLeaveApplication->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["DEFAULT_MAX_PAGE_INDEX"], $additionalParams);
        return $returnLeave;
    }
    
    public function GetLeaveByUserIdAndYear($userId, $year) {
        $currentYear = intval($year);
        $nextYear = $currentYear + 1;
        $startDate = new DateTime('1/1/'.$currentYear);
        $endDate = new DateTime('1/1/'.$nextYear);
        
        $newLeaveApplication = new LeaveApplication;

        $additionalParams = array(
            array('table' => 'LeaveApplication', 'column' => 'UserId', 'value' => $userId, 'type' => PDO::PARAM_INT, 'condition' => 'and'),
            array('table' => 'LeaveApplication', 'column' => 'LeaveDateFrom', 'value' => $startDate->format('Y-m-d 00:00:00'), 'type' => PDO::PARAM_STR, 'condition' => 'and', 'operator' => '>='),
            array('table' => 'LeaveApplication', 'column' => 'LeaveDateTo', 'value' => $endDate->format('Y-m-d 00:00:00'), 'type' => PDO::PARAM_STR, 'condition' => 'and', 'operator' => '<')
        );

        $returnLeave = $newLeaveApplication->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["DEFAULT_MAX_PAGE_INDEX"], $additionalParams);
        return $returnLeave;
    }
    
    public function GetLeaveByUserIdYearLeaveType($userId, $year, $leaveType) {
        $currentYear = intval($year);
        $nextYear = $currentYear + 1;
        $startDate = new DateTime('1/1/'.$currentYear);
        $endDate = new DateTime('1/1/'.$nextYear);
        
        $newLeaveApplication = new LeaveApplication;

        $additionalParams = array(
            array('table' => 'LeaveApplication', 'column' => 'UserId', 'value' => $userId, 'type' => PDO::PARAM_INT, 'condition' => 'and'),
            array('table' => 'LeaveApplication', 'column' => 'LeaveDateFrom', 'value' => $startDate->format('Y-m-d 00:00:00'), 'type' => PDO::PARAM_STR, 'condition' => 'and', 'operator' => '>='),
            array('table' => 'LeaveApplication', 'column' => 'LeaveDateTo', 'value' => $endDate->format('Y-m-d 00:00:00'), 'type' => PDO::PARAM_STR, 'condition' => 'and', 'operator' => '<'),
            array('table' => 'LeaveApplication', 'column' => 'LeaveTypeId', 'value' => $leaveType, 'type' => PDO::PARAM_INT, 'condition' => 'and', 'operator' => '=')
        );

        $returnLeave = $newLeaveApplication->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["DEFAULT_MAX_PAGE_INDEX"], $additionalParams);
        return $returnLeave;
    }

    public function GetLeaveByid($id) {
        $newLeaveApplication = new LeaveApplication;

        $additionalParams = array(
            array('table' => 'LeaveApplication', 'column' => 'Id', 'value' => $id, 'type' => PDO::PARAM_INT, 'condition' => 'and')
        );

        $returnLeave = $newLeaveApplication->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["PAGE_SINGLE_ITEM"], $additionalParams);
        return $returnLeave;
    }

    //$userIds is an array of user id
    public function GetPendingLeaveByUserIds($userIds) {
        if (count($userIds) > 0) {
            $newLeaveApplication = new LeaveApplication;

            $additionalParams = array();

            //get leave application with new status
            array_push($additionalParams, array('table' => 'LeaveApplication', 'column' => 'Status', 'value' => '1', 'type' => PDO::PARAM_INT, 'condition' => 'and'));


            $i = 0;
            $operator = 'and';
            foreach ($userIds as $id) {
                if ($i > 0) {
                    $operator = 'or';
                }
                array_push($additionalParams, array('table' => 'LeaveApplication', 'column' => 'UserId', 'value' => $id, 'type' => PDO::PARAM_INT, 'condition' => $operator));
                $i += 1;
            }

            $returnLeave = $newLeaveApplication->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["DEFAULT_MAX_PAGE_INDEX"], $additionalParams);
            return $returnLeave;
        } else {
            return null;
        }
    }

    public function ApplyLeave($from, $to, $diff, $totalBringFoward, $type, $offDayRemarks, $remarks, $approvalRemarks, $status ,$userId, $email) {

        $dbOptResponse = new DbOpt;

        $newLeave = new LeaveApplication;
        $newLeave->UserId = $userId;
        $newLeave->LeaveTypeId = $type;
        $newLeave->OffDayRemarks = $offDayRemarks;
        $newLeave->Remarks = $remarks;
        $newLeave->SupervisorRemarks = $approvalRemarks;
        $newLeave->LeaveDateFrom = $from;
        $newLeave->LeaveDateTo = $to;
        $newLeave->TotalLeave = $diff;
        $newLeave->TotalBringForwardLeave = $totalBringFoward;
        $newLeave->Status = $status;
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

    public function UpdateApplicationLeave($obj, $currentUser) {
        $dbOpt = new DbOpt;
        $dbOpt->OptStatus = true;
        $dbOpt->OptMessage = "Success";

        if ($obj != null) {
            $obj->UpdatedDate = date("Y-m-d H:i:s", time());
            $obj->UpdatedBy = $currentUser;

            $dbOpt = $obj->Update($this->dbConnection, $obj);
        } else {
            $dbOpt->OptStatus = false;
            $dbOpt->OptMessage = "Error when updating pro rated leave";
        }

        return $dbOpt;
    }

    public function GetNonRejectedLeaveApplication($userid, $year) {
        $newLeaveApplication = new LeaveApplication;

        $additionalParams = array(
            array('table' => 'LeaveApplication', 'column' => 'UserId', 'value' => $userid, 'type' => PDO::PARAM_INT, 'condition' => 'and'),
            array('table' => 'LeaveApplication', 'column' => 'Status', 'value' => 1, 'type' => PDO::PARAM_INT, 'condition' => 'and'),
            array('table' => 'LeaveApplication', 'column' => 'Status', 'value' => 2, 'type' => PDO::PARAM_INT, 'condition' => 'or'),
            array('table' => 'LeaveApplication', 'column' => 'LeaveDateFrom', 'value' => '%' . $year . '%', 'operator' => 'like', 'type' => PDO::PARAM_STR, 'condition' => 'and')
        );

        $returnLeave = $newLeaveApplication->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["DEFAULT_MAX_PAGE_INDEX"], $additionalParams);
        return $returnLeave;
    }

    public function CheckDuplicateLeave($from, $userId) {
        $newLeaveType = new LeaveType;
        return $newLeaveType->IsLeaveDateValid($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["DEFAULT_MAX_PAGE_INDEX"], null);
    }

    public function GetLeaveStatus() {
        $newLeaveStatus = new LeaveStatus;
        $returnLeaveStatus = $newLeaveStatus->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["DEFAULT_MAX_PAGE_INDEX"], null);
        return $returnLeaveStatus;
    }

    public function GetBringForwardLeaveByUserId($id, $year) {
        $newBringForwardLeave = new BringForwardLeave;

        $additionalParams = array(
            array('table' => 'BringForwardLeave', 'column' => 'UserId', 'value' => $id, 'type' => PDO::PARAM_INT, 'condition' => 'and'),
            array('table' => 'BringForwardLeave', 'column' => 'BringForwardFromYear', 'value' => $year, 'type' => PDO::PARAM_STR, 'condition' => 'and')
        );

        $bringForward = $newBringForwardLeave->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["DEFAULT_MAX_PAGE_INDEX"], $additionalParams);
        return $bringForward;
    }

    //Accumulative Leave Section
    public function AddAccumulativeLeave($userid, $leaveTypeId, $remarks, $year, $leaveNumber, $email) {
        $newAccumulativeLeave = new AccumulativeLeave;
        $newAccumulativeLeave->UserId = $userid;
        $newAccumulativeLeave->ExpiredYear = $year;
        $newAccumulativeLeave->LeaveTypeId = $leaveTypeId;
        $newAccumulativeLeave->Remarks = $remarks;
        $newAccumulativeLeave->AccumulativeLeaveNumber = $leaveNumber;

        $newAccumulativeLeave->IsActive = true;
        $newAccumulativeLeave->CreatedDate = date("Y-m-d H:i:s", time());
        $newAccumulativeLeave->CreatedBy = $email;
        $newAccumulativeLeave->UpdatedDate = date("Y-m-d H:i:s", time());
        $newAccumulativeLeave->UpdatedBy = $email;

        $dbOptResponse = $newAccumulativeLeave->Add($this->dbConnection, $newAccumulativeLeave);
        $dbOptResponse->OptObj = $newAccumulativeLeave;

        return $dbOptResponse;
    }

    public function GetAccumulativeLeave($pageIndex, $pageSize) {
        $newAccumulativeLeave = new AccumulativeLeave;

        $accumulativeLeaveList = $newAccumulativeLeave->Gets($this->dbConnection, $pageIndex-1, $pageSize, null);
        return $accumulativeLeaveList;
    }

    public function GetAccumulativeLeaveById($id) {
        $newAccumulativeLeave = new AccumulativeLeave;

        $additionalParams = array(
            array('table' => 'AccumulativeLeave', 'column' => 'Id', 'value' => $id, 'type' => PDO::PARAM_INT, 'condition' => 'and')
        );

        $accumulativeList = $newAccumulativeLeave->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["DEFAULT_MAX_PAGE_INDEX"], $additionalParams);
        return $accumulativeList;
    }

    public function GetAccumulativeLeaveByUserIdAndYear($userid, $year) {
        $newAccumulativeLeave = new AccumulativeLeave;

        $additionalParams = array(
            array('table' => 'AccumulativeLeave', 'column' => 'UserId', 'value' => $userid, 'type' => PDO::PARAM_INT, 'condition' => 'and'),
            array('table' => 'AccumulativeLeave', 'column' => 'ExpiredYear', 'value' => $year, 'type' => PDO::PARAM_STR, 'condition' => 'and')
        );

        $accumulativeList = $newAccumulativeLeave->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["DEFAULT_MAX_PAGE_INDEX"], $additionalParams);
        return $accumulativeList;
    }

    public function GetAccumulativeLeaveByUserIdYearLeaveType($userid, $year, $leaveType) {
        $newAccumulativeLeave = new AccumulativeLeave;

        $additionalParams = array(
            array('table' => 'AccumulativeLeave', 'column' => 'UserId', 'value' => $userid, 'type' => PDO::PARAM_INT, 'condition' => 'and'),
            array('table' => 'AccumulativeLeave', 'column' => 'ExpiredYear', 'value' => $year, 'type' => PDO::PARAM_STR, 'condition' => 'and'),
            array('table' => 'AccumulativeLeave', 'column' => 'LeaveTypeId', 'value' => $leaveType, 'type' => PDO::PARAM_INT, 'condition' => 'and')
        );

        $accumulativeList = $newAccumulativeLeave->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["DEFAULT_MAX_PAGE_INDEX"], $additionalParams);
        return $accumulativeList;
    }

    public function UpdateAccumulativeLeave($obj, $email) {
        $dbOpt = new DbOpt;
        $dbOpt->OptStatus = true;
        $dbOpt->OptMessage = "Success";

        if ($obj != null) {
            $obj->UpdatedDate = date("Y-m-d H:i:s", time());
            $obj->UpdatedBy = $email;

            $dbOpt = $obj->Update($this->dbConnection, $obj);
        } else {
            $dbOpt->OptStatus = false;
            $dbOpt->OptMessage = "Error when updating leave type";
        }

        return $dbOpt;
    }

    //Accumulative Leave Section
    
    
    //Bringforward leave section
    public function AddBringforwardLeave($userid,$year, $bringforwardAttribute, $email) {
        $newBringforward = new BringForwardLeave;
        $newBringforward->UserId = $userid;
        $newBringforward->BringForwardFromYear = $year;
        $newBringforward->BringForwardAttributes = $bringforwardAttribute;

        $newBringforward->IsActive = true;
        $newBringforward->CreatedDate = date("Y-m-d H:i:s", time());
        $newBringforward->CreatedBy = $email;
        $newBringforward->UpdatedDate = date("Y-m-d H:i:s", time());
        $newBringforward->UpdatedBy = $email;

        $dbOptResponse = $newBringforward->Add($this->dbConnection, $newBringforward);
        $dbOptResponse->OptObj = $newBringforward;

        return $dbOptResponse;
    }
}

?>