<?php
    $leaveTypeList = $leaveCtrl->GetLeaveTypes($GLOBALS["DEFAULT_PAGE_INDEX"], $GLOBALS["DEFAULT_MAX_PAGE_INDEX"]);
    $currentYear = intval(date('Y'));
    $previousYear = $currentYear - 1;
    $leaveAttributeString = '';
    $leaveAttributeArray = array();
    $proRatedList = $leaveCtrl->GetProRatedLeaveByUserIdAndYear($loginCtrl->GetUserId(), date('Y'));
    $userRoleList = null;
    
    $appliedLeaveList = $leaveCtrl->GetNonRejectedLeaveApplication($loginCtrl->GetUserId(), date('Y'));
    $accumulatedLeaveList = $leaveCtrl->GetAccumulativeLeaveByUserIdAndYear($loginCtrl->GetUserId(), date('Y'));
    
    $appliedLeaveSummary_approved = array();
    $forwardLeaveSummary_approved = array();
    $appliedLeaveSummary_pending = array();
    $forwardLeaveSummary_pending = array();
    
    $userBringForwardAttributeArray = array();
    
    $userBringForwardList = $leaveCtrl->GetBringForwardLeaveByUserId($loginCtrl->GetUserId(), $previousYear);
    if( $userBringForwardList != null && count($userBringForwardList) > 0 ){
        $userBringForward = $userBringForwardList[0];
        $userBringForwardAttributes = $userBringForward->BringForwardAttributes;
        $userBringForwardAttributeArray = json_decode($userBringForwardAttributes, true);
    }
    
    foreach($appliedLeaveList as $applied){
        $tempTotal = 0;
        $tempTotalForward = 0;
        if( $applied->Status == 1 ){
            //New status
            if( array_key_exists($applied->LeaveTypeId, $appliedLeaveSummary_pending) ){
                $tempTotal = $appliedLeaveSummary_pending[$applied->LeaveTypeId];
            }
            
            if( array_key_exists($applied->LeaveTypeId, $forwardLeaveSummary_pending) ){
                $tempTotalForward = $forwardLeaveSummary_pending[$applied->LeaveTypeId];
            }
            
            $appliedLeaveSummary_pending[$applied->LeaveTypeId] = ($tempTotal + $applied->TotalLeave);
            $forwardLeaveSummary_pending[$applied->LeaveTypeId] = ($tempTotalForward + $applied->TotalBringForwardLeave);
        }else{
            
            //Approved status
            if( array_key_exists($applied->LeaveTypeId, $appliedLeaveSummary_approved) ){
                $tempTotal = $appliedLeaveSummary_approved[$applied->LeaveTypeId];
            }
            if( array_key_exists($applied->LeaveTypeId, $forwardLeaveSummary_approved) ){
                $tempTotalForward = $forwardLeaveSummary_approved[$applied->LeaveTypeId];
            }
            
            $appliedLeaveSummary_approved[$applied->LeaveTypeId] = ($tempTotal + $applied->TotalLeave);
            $forwardLeaveSummary_approved[$applied->LeaveTypeId] = ($tempTotalForward + $applied->TotalBringForwardLeave);
        }
    }
    
    
    
    if( $proRatedList != null && count($proRatedList) > 0 ){
        $proRate = $proRatedList[0];
        $leaveAttributeString = $proRate->ProRatedAttributes;
    }else{
        $userRoleList = $roleCtrl->GetRoleLeaveByUserId($loginCtrl->GetUserId());
        if( $userRoleList != null && count($userRoleList) > 0 ){
            $userRole = $userRoleList[0];
            $roleLeaveList = $roleCtrl->GetRoleLeaveById($userRole->Role->Id);
            if( $roleLeaveList != null && count($roleLeaveList) > 0 ){
                $roleLeave = $roleLeaveList[0];
                $leaveAttributeString = $roleLeave->LeaveAttribute;
            }
        }
    }
    
    if( !empty($leaveAttributeString) ){
        $leaveAttributeArray = json_decode($leaveAttributeString, true);
    }
    
    if( $accumulatedLeaveList != null && count($accumulatedLeaveList) > 0 ){
        foreach($accumulatedLeaveList as $acc){
            if( array_key_exists($acc->LeaveTypeId, $leaveAttributeArray) ){
                $leaveAttributeArray[$acc->LeaveTypeId] = $leaveAttributeArray[$acc->LeaveTypeId] + $acc->AccumulativeLeaveNumber;
            }else{
                $leaveAttributeArray[$acc->LeaveTypeId] = $acc->AccumulativeLeaveNumber;
            }
        }
    }
    
?>

<div class="panel panel-success">
    <div class="panel-heading"><h3 class="panel-title">Overall Leave</h3> </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Leave Type</th>
                    <th>Total Leave</th>
                    <th>Pending Approval Leave</th>
                    <th>Approved Leave</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach( $leaveTypeList as $lv ){
                        echo '<tr>';
                        echo '<td>'.$lv->LeaveName.'</td>';
                        
                        $current_total_leave = 0;
                        $forward_total_leave = 0;
                        //Total Leave
                        if( array_key_exists($lv->Id, $leaveAttributeArray) ){
                            $current_total_leave = $leaveAttributeArray[$lv->Id];
                        }
                        
                        if( array_key_exists($lv->Id, $userBringForwardAttributeArray) ){
                            $forward_total_leave = $userBringForwardAttributeArray[$lv->Id];
                        }
                        
                        if( $lv->IsAllowToBringForward ){
                            echo '<td>';
                            echo '<p> Current: '.$current_total_leave.'</p>';
                            echo '<p> Forward: '.$forward_total_leave.'</p>';
                            echo '</td>';
                        }else{
                            echo '<td>';
                            echo '<p>'.$current_total_leave.'</p>';
                            echo '</td>';
                        }
                        
                        $current_pending_leave = 0;
                        $forward_pending_leave = 0;
                        //Pending Approval Leave
                        if( array_key_exists($lv->Id, $appliedLeaveSummary_pending) ){
                            $current_pending_leave = $appliedLeaveSummary_pending[$lv->Id];
                        }
                        
                        if( array_key_exists($lv->Id, $forwardLeaveSummary_pending) ){
                            $forward_pending_leave = $forwardLeaveSummary_pending[$lv->Id];
                        }
                        
                        if( $lv->IsAllowToBringForward ){
                            echo '<td>';
                            echo '<p> Current: '.$current_pending_leave.'</p>';
                            echo '<p> Forward: '.$forward_pending_leave.'</p>';
                            echo '</td>';
                        }else{
                            echo '<td>';
                            echo '<p>'.$current_pending_leave.'</p>';
                            echo '</td>';
                        }
                        
                        
                        //Approved Leave
                        $current_approved_leave = 0;
                        $forward_approved_leave = 0;
                        if( array_key_exists($lv->Id, $appliedLeaveSummary_approved) ){
                            $current_approved_leave = $appliedLeaveSummary_approved[$lv->Id];
                        }
                        
                        if( array_key_exists($lv->Id, $forwardLeaveSummary_approved) ){
                            $forward_approved_leave = $forwardLeaveSummary_approved[$lv->Id];
                        }
                        
                        if( $lv->IsAllowToBringForward ){
                            echo '<td>';
                            echo '<p> Current: '.$current_approved_leave.'</p>';
                            echo '<p> Forward: '.$forward_approved_leave.'</p>';
                            echo '</td>';
                        }else{
                            echo '<td>';
                            echo '<p>'.$current_approved_leave.'</p>';
                            echo '</td>';
                        }
                        
                        
                        
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>