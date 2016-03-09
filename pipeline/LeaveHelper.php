<?php 
    function GetTotalAvailableBringForward($leaveCtrl, $userId, $previousYear,$leaveType){
        $totalAvailableBringForward = 0;
        $userBringForwardList = $leaveCtrl->GetBringForwardLeaveByUserId($userId, $previousYear);
        if( $userBringForwardList != null && count($userBringForwardList) > 0 ){
            $userBringForward = $userBringForwardList[0];
            $userBringForwardAttributes = $userBringForward->BringForwardAttributes;
            $userBringForwardAttributeArray = json_decode($userBringForwardAttributes, true);
            
            if( array_key_exists($leaveType,$userBringForwardAttributeArray) ){
                $totalAvailableBringForward = $userBringForwardAttributeArray[$leaveType];
            }
        }
        
        return $totalAvailableBringForward;
    }
    
    function GetNonRejectedLeave($leaveCtrl, $userId, $currentYear, &$totalAppliedDay, &$totalUsedBringForwardDay){
        $appliedLeave = $leaveCtrl->GetNonRejectedLeaveApplication($userId, $currentYear);
        $totalAppliedDay = 0.0;
        $totalUsedBringForwardDay = 0.0;
        if( count($appliedLeave) > 0 ){
            foreach($appliedLeave as $la ){
                $totalAppliedDay += $la->TotalLeave;
                $totalUsedBringForwardDay += $la->TotalBringForwardLeave;
            }
        }
    }
    
    function GetAccumulativeLeave($leaveTypeList,$leaveCtrl, $userId, $currentYear,$leaveType ){
        $leaveTypeNumber = 0;
        foreach( $leaveTypeList as $leave ){
            if( $leave->Id == $leaveType ){
                if( $leave->IsAllowToAccumulate ){
                    $accumulatedLeaveList = $leaveCtrl->GetAccumulativeLeaveByUserIdYearLeaveType($userId,$currentYear,$leave->Id );
                    if( $accumulatedLeaveList != null && count($accumulatedLeaveList) > 0 ){
                        foreach( $accumulatedLeaveList as $acc ){
                            $leaveTypeNumber += $acc->AccumulativeLeaveNumber;
                        }
                    }
                    break;
                }
            }
        }
        return $leaveTypeNumber;
    }
    
    function CalculateLeave(&$totalBringForwardToApply,&$totalCurrentToApply,$totalAvailableBringForward,$totalUsedBringForwardDay,$dateDiff,$fromDateObj,$toDateObj,$totalOffDay){
        
        //Expired date object for bring forward leave 
        $expiryDateString = $GLOBALS['BRING_FORWARD_EXPIRE_DATE_MONTH'].'/'.$GLOBALS['BRING_FORWARD_EXPIRE_DATE_DAYS'].'/'.date('Y');
        $expiryDateObj = datetime::createfromformat('m/d/Y',$expiryDateString);
        $from_expire_diff = $expiryDateObj->diff($fromDateObj);
        $total_from_expire_diff = $from_expire_diff->d + 1;
        
        $today_dt = new DateTime(date("Y-m-d"));
        $from_dt = new DateTime($fromDateObj->format('Y-m-d'));
        $to_dt = new DateTime($toDateObj->format('Y-m-d'));
        $expirty_dt = new DateTime($expiryDateObj->format('Y-m-d'));
        
        if( $from_dt > $expirty_dt || $today_dt > $expirty_dt ){
            //if from date is already pass expiry date
            //if today date is already pass expiry date
            $totalBringForwardToApply = 0.0;
            $totalCurrentToApply = $dateDiff;
        }else{
            if( $to_dt <= $expirty_dt ){    //if last applied date is less than or equal to expiry date
                if( $totalAvailableBringForward < $totalUsedBringForwardDay ){
                    //Validation checking if total used bring forward is more htan available bring forward
                    //In this canse, this code would not be executed
                    $totalBringForwardToApply = 0.0;
                }
                if( $dateDiff > $totalBringForwardToApply ){
                    //if current applied leave is more than available bring forward
                    // 1. use all bring forward leave
                    // 2. find the different that required this year leave
                    $totalCurrentToApply = $dateDiff - $totalBringForwardToApply;
                }else{
                    //if available bring forward is more than or equal to current applied leave
                    //applied all to available bring forward
                    $totalBringForwardToApply = $dateDiff;
                }
            }else{  //if last applied date is greater than expiry date
                if( $totalBringForwardToApply > $total_from_expire_diff ){
                    $totalBringForwardToApply = $total_from_expire_diff;
                }
                $totalCurrentToApply = $dateDiff - $totalBringForwardToApply;
            }
        }
        
        $totalCurrentToApply = $totalCurrentToApply - $totalOffDay;
    }
?>