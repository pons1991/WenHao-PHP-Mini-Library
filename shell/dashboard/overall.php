<?php
    $leaveTypeList = $leaveCtrl->GetLeaveTypes();
    $leaveAttributeString = '';
    $leaveAttributeArray = array();
    $proRatedList = $leaveCtrl->GetProRatedLeaveByUserIdAndYear($loginCtrl->GetUserId(), date('Y'));
    $userRoleList = null;
    
    $appliedLeaveList = $leaveCtrl->GetNonRejectedLeaveApplication($loginCtrl->GetUserId(), date('Y'));
    $appliedLeaveSummary_approved = array();
    $appliedLeaveSummary_pending = array();
    
    foreach($appliedLeaveList as $applied){
        $tempTotal = 0;
        if( $applied->Status == 1 ){
            //New status
            if( array_key_exists($applied->LeaveTypeId, $appliedLeaveSummary_pending) ){
                $tempTotal = $appliedLeaveSummary_pending[$applied->LeaveTypeId];
                $tempTotal = $tempTotal + $applied->TotalLeave;
            }
            $appliedLeaveSummary_pending[$applied->LeaveTypeId] = ($tempTotal + $applied->TotalLeave);
        }else{
            //Approved status
            if( array_key_exists($applied->LeaveTypeId, $appliedLeaveSummary_approved) ){
                $tempTotal = $appliedLeaveSummary_approved[$applied->LeaveTypeId];
                $tempTotal = $tempTotal + $applied->TotalLeave;
            }
            $appliedLeaveSummary_approved[$applied->LeaveTypeId] = ($tempTotal + $applied->TotalLeave);
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
                        
                        //Total Leave
                        if( array_key_exists($lv->Id, $leaveAttributeArray) ){
                            echo '<td>'.$leaveAttributeArray[$lv->Id].'</td>';
                        }else{
                            echo '<td>0</td>';
                        }
                        
                        //Pending Approval Leave
                        if( array_key_exists($lv->Id, $appliedLeaveSummary_pending) ){
                            echo '<td>'.$appliedLeaveSummary_pending[$lv->Id].'</td>';
                        }else{
                            echo '<td>0</td>';
                        }
                        
                        //Approved Leave
                        if( array_key_exists($lv->Id, $appliedLeaveSummary_approved) ){
                            echo '<td>'.$appliedLeaveSummary_approved[$lv->Id].'</td>';
                        }else{
                            echo '<td>0</td>';
                        }
                        
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>