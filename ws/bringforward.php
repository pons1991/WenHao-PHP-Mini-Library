<?php 
    include "../Base.php";

    $currentYearStr = '2017';//date('Y');
    $currentYear = intval($currentYearStr);
    
    $previousYear = $currentYear - 1;
    
    $bringforwardArray = array();
    $bringforwardAccumulativeArray = array();
    
    //Initiazlie all controller
    $roleCtrl = new RoleController($dbConn);
    $userCtrl = new UserController($dbConn);
    $leaveCtrl = new LeaveController($dbConn);
    
    //Batch process update leave status - start
    $pendingLeaveList = $leaveCtrl->GetLeaveByStatus(1);//$AUTO_LEAVESTATUS
    if( $pendingLeaveList != null && count($pendingLeaveList) > 0 ){
        foreach( $pendingLeaveList as $pdl){
            $pdl->Status = $GLOBALS["AUTO_LEAVESTATUS"];
            $pdl->ApprovedBy = $GLOBALS["SYSTEM_USER_ID"];
            $pdl->SupervisorRemarks = 'SYSTEM APPROVAL';
            $leaveCtrl->UpdateApplicationLeave($pdl, $GLOBALS["SYSTEM_USER_EMAIL"]);
        }
    }
    //Batch process update leave status - end
    
    //Get Leave Type
    $leaveList = $leaveCtrl->GetLeaveTypes($GLOBALS["DEFAULT_PAGE_INDEX"], $GLOBALS["DEFAULT_MAX_PAGE_INDEX"]);
    foreach( $leaveList as $leave ){
        if( $leave->IsAllowToBringForward ){
            array_push($bringforwardArray, $leave->Id);
        }
        
        if( $leave->IsAllowToBringForward && $leave->IsAllowToAccumulate ){
            array_push($bringforwardAccumulativeArray, $leave->Id);
        }
    }
    
    //Get All Role Leave
    $roleLeaveList =  $roleCtrl->GetRoleLeave();
    
    //Get All Users
    $userList = $userCtrl->GetUsers($GLOBALS["DEFAULT_PAGE_INDEX"], $GLOBALS["DEFAULT_MAX_PAGE_INDEX"]);
    
    foreach( $userList as $user ){
        //Get ProRated leave for each user
        $proratedList = $leaveCtrl->GetProRatedLeaveByUserIdAndYear($user->Id, $previousYear);
        $leaveAttributeArray = null;
        $leaveAttributeString = '';
        $toBringforward = array();
        
        
        if( $proratedList != null && count($proratedList) > 0 ){
            $prorate = $proratedList[0];
            $leaveAttributeString = $prorate->ProRatedAttributes;
        }else{
            //Get the role id by user id
            $userRoleList = $roleCtrl->GetRoleLeaveByUserId($user->Id);
            if( $userRoleList != null && count($userRoleList) > 0 ){
                $userRole = $userRoleList[0];
                foreach( $roleLeaveList as $rl){
                    
                    //Match User's RoleId with RoleLeave's RoleId
                    if( $rl->RoleId == $userRole->RoleId ){
                        $leaveAttributeString = $rl->LeaveAttribute;
                        break;
                    }
                }
            }
        }
        
        if( !empty($leaveAttributeString) ){
            $leaveAttributeArray = json_decode($leaveAttributeString, true);
            $arrayKey = array_keys($leaveAttributeArray);
            if( $arrayKey != null && count($arrayKey) > 0 ){
                foreach( $arrayKey as $k ){
                    if( array_key_exists($k,$arrayKey) ){
                        //to bring forward
                        $toBringforward[$k] = $leaveAttributeArray[$k]; //This is the original value which need to be minus from taken leave
                    }
                }
            }
            
            //Get AccumulativeLeave by userid, year, and leave type
            if( $bringforwardAccumulativeArray != null && count($bringforwardAccumulativeArray) > 0 ){
                foreach( $bringforwardAccumulativeArray as $bfaa){
                    $accumulativeLeaveList = $leaveCtrl->GetAccumulativeLeaveByUserIdYearLeaveType($user->Id, $previousYear, $bfaa);
                    
                    foreach( $accumulativeLeaveList as $accLeave){
                        if( array_key_exists($accLeave->LeaveTypeId,$toBringforward) ){
                            $toBringforward[$accLeave->LeaveTypeId] += $accLeave->AccumulativeLeaveNumber;
                        }else{
                            $toBringforward[$accLeave->LeaveTypeId] = $accLeave->AccumulativeLeaveNumber;
                        }
                    }
                }
            }
        }
        
        //Minus out the taken leave
        if( $bringforwardArray != null && count($bringforwardArray) > 0 ){
            foreach( $bringforwardArray as $bfa){
                $appliedLeaveList = $leaveCtrl->GetLeaveByUserIdYearLeaveType($user->Id, $previousYear, $bfa);
                if( $appliedLeaveList != null && count($appliedLeaveList) > 0 ){
                    foreach( $appliedLeaveList as $all ){
                        if( array_key_exists($bfa,$toBringforward) ){
                            $toBringforward[$bfa] -= $all->TotalLeave;
                        }
                    }
                } 
            }
        }
        
        if( $toBringforward != null && count($toBringforward) > 0 ){
            $leaveCtrl->AddBringforwardLeave($user->Id,$previousYear, json_encode($toBringforward), $GLOBALS["SYSTEM_USER_EMAIL"]);
        }
    }
?>