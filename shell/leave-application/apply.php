<?php

    $dbOptResp = null;
    $isEditing = false;
    $editingLeave = null;
    
    $leaveTypeList = $leaveCtrl->GetLeaveTypes();
    
    if( isset($_GET["id"]) && $_GET["id"] !== '0' ){
        //To edit
        $isEditing = true;
        $leaveList = $leaveCtrl->GetLeaveByid($_GET["id"]);
        if( $leaveList != null && count($leaveList) == 1){
            $editingLeave = $leaveList[0];
        }
    }
    
    
    if (isset($_POST["submit"])){
        $fromDate = $_POST["datepickerFrom"];
        $toDate = $_POST["datepickerTo"];
        $leaveType = $_POST["leaveType"];
        $halfDay = isset($_POST["halfDay"]) ? true : false;
        $remarks = empty($_POST["remarks"]) ? "N/A" : $_POST["remarks"];
        $approvalRemarks = "N/A";
        $userId = $loginCtrl->GetUserId();
        $userEmail = $loginCtrl->GetUserName();
        
        $fromDateObj = datetime::createfromformat('m/d/Y',$fromDate);
        $fromDateFormat = $fromDateObj->format('Y-m-d 00:00:00');
        
        $toDateObj = datetime::createfromformat('m/d/Y',$toDate);
        $toDateFormat = $toDateObj->format('Y-m-d 00:00:00');
        
        $dateIntervalDiff = $toDateObj->diff($fromDateObj);
        $dateDiff = $dateIntervalDiff->d + 1;
        
        //if it is half day, total leave day is 0.5
        if( $dateDiff == 1 && $halfDay ){
            $dateDiff = 0.5;
        }
        
        $currentYear = intval(date('Y'));
        $previousYear = $currentYear - 1;

        $totalAvailableBringForward = 0.0;

        //Bring forward algorithm 
        $userBringForwardList = $leaveCtrl->GetBringForwardLeaveByUserId($loginCtrl->GetUserId(), $previousYear);
        if( $userBringForwardList != null && count($userBringForwardList) == 1 ){
            $userBringForward = $userBringForwardList[0];
            $userBringForwardAttributes = $userBringForward->BringForwardAttributes;
            $userBringForwardAttributeArray = json_decode($userBringForwardAttributes, true);
            
            if( array_key_exists($leaveType,$userBringForwardAttributeArray) ){
                $totalAvailableBringForward = $userBringForwardAttributeArray[$leaveType];
            }
        }
        
        //Non rejected leave application algorithm
        $appliedLeave = $leaveCtrl->GetNonRejectedLeaveApplication($loginCtrl->GetUserId(), $currentYear);
        $totalAppliedDay = 0.0;
        $totalUsedBringForwardDay = 0.0;
        if( count($appliedLeave) > 0 ){
            foreach($appliedLeave as $la ){
                $totalAppliedDay += $la->TotalLeave;
                $totalUsedBringForwardDay += $la->TotalBringForwardLeave;
            }
        }
        
        //Expired date object for bring forward leave 
        $expiryDateString = $GLOBALS['BRING_FORWARD_EXPIRE_DATE_MONTH'].'/'.$GLOBALS['BRING_FORWARD_EXPIRE_DATE_DAYS'].'/'.date('Y');
        $expiryDateObj = datetime::createfromformat('m/d/Y',$expiryDateString);
        $from_expire_diff = $expiryDateObj->diff($fromDateObj);
        $total_from_expire_diff = $from_expire_diff->d + 1;
        
        $totalBringForwardToApply = 0.0;
        $totalBringForwardToApply = $totalAvailableBringForward - $totalUsedBringForwardDay;
        $totalCurrentToApply = 0.0;
        
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
        
        //validation on pro rated leave
        $proRatedList = $leaveCtrl->GetProRatedLeaveByUserIdAndYear($loginCtrl->GetUserId(), $currentYear);
        if( $proRatedList != null && count($proRatedList) == 1 ){
            $proRated = $proRatedList[0];
            $proRatedLeaveArray = json_decode($proRated->ProRatedAttributes, true);
            
            $leaveTypeNumber = $proRatedLeaveArray[$leaveType];
            if ( ($totalAppliedDay + $totalCurrentToApply) > $leaveTypeNumber){
                //error
                $dbOptResp = new DbOpt;
                $dbOptResp->OptStatus = false;
                $dbOptResp->OptMessage = 'Your applied leave has exceed your available leave';
            }else{
                //proceed
                $dbOptResp = $leaveCtrl->ApplyLeave($fromDateFormat,$toDateFormat, $totalCurrentToApply,$totalBringForwardToApply, $leaveType, $remarks, $userId, $userEmail);
            }
        }else{
            //validation on role leave
            $userRoleList = $roleCtrl->GetRoleLeaveByUserId($loginCtrl->GetUserId());
            if( $userRoleList != null && count($userRoleList) == 1 ){
                $userRole = $userRoleList[0];
                $roleLeaveList = $roleCtrl->GetRoleLeaveById($userRole->Role->Id);
                if( $roleLeaveList != null && count($roleLeaveList) == 1 ){
                    $roleLeave = $roleLeaveList[0];
                    $roleLeaveArray = json_decode($roleLeave->LeaveAttribute, true);
                    $leaveTypeNumber = $roleLeaveArray[$leaveType];
                    if( ($totalAppliedDay + $totalCurrentToApply) > $leaveTypeNumber ){
                        //error
                        $dbOptResp = new DbOpt;
                        $dbOptResp->OptStatus = false;
                        $dbOptResp->OptMessage = 'Your applied leave has exceed your available leave2';
                    }else{
                        //proceed
                        $dbOptResp = $leaveCtrl->ApplyLeave($fromDateFormat,$toDateFormat, $totalCurrentToApply,$totalBringForwardToApply, $leaveType, $remarks,$approvalRemarks, $userId, $userEmail);
                    }
                }
            }
        }
        
        if( $dbOptResp != null && $dbOptResp->OptStatus && $dbOptResp->OptObj != null ){
            $leaveTypeName = '';
            foreach( $leaveTypeList as $lv ){
                if( $lv->Id == $leaveType ){
                    $leaveTypeName = $lv->LeaveName;
                    break;
                }
            }
            
            $orgRelList = $userCtrl->GetUserOrgRel($userId);
            if( $orgRelList != null && count($orgRelList) == 1){
                $orgRel = $orgRelList[0];
                $emailCtrl->SendLeaveApplicationEmail($orgRel->SuperiorUser->Email,$userEmail,$fromDate,$toDate,$leaveTypeName,$remarks,$totalCurrentToApply,$totalBringForwardToApply,'New','' );
            }
        }
    }
?>


<form method="post">
    <div class="row" >
        <div class="col-sm-12 form-group">
            <div class="alert alert-danger hide" role="alert" id="leaveApplicationErrorMessage">
                <strong>Error: </strong><span></span>
            </div>
            <?php 
                if (isset($_POST["submit"])){
                    if( $dbOptResp != null ){
                        if( $dbOptResp->OptStatus ){
                            echo '<div class="alert alert-success" role="alert">';
                            echo '<span>'.$dbOptResp->OptMessage.'</span>';
                            echo '</div>';
                        }else{
                            echo '<div class="alert alert-danger" role="alert">';
                            echo '<strong>Error: </strong><span>'.$dbOptResp->OptMessage.'</span>';
                            echo '</div>';
                        }
                    }
                }
            ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="leaveDate">Leave Date </label></div>
            <div class="col-sm-5">
                <?php 
                    if( $isEditing ){
                        echo '<input type="text" class="form-control" id="datepickerFrom" name="datepickerFrom" placeholder="From" disabled value="'.datetime::createfromformat('Y-m-d 00:00:00',$editingLeave->LeaveDateFrom)->format('m/d/Y').'" />';
                    }else{
                        echo '<input type="text" class="form-control" id="datepickerFrom" name="datepickerFrom" placeholder="From" />';
                    }
                ?>
            </div>
            <div class="col-sm-5">
                <?php 
                    if( $isEditing ){
                        echo '<input type="text" class="form-control" id="datepickerTo" name="datepickerTo" placeholder="to" disabled value="'.datetime::createfromformat('Y-m-d 00:00:00',$editingLeave->LeaveDateTo)->format('m/d/Y').'"/>';
                    }else{
                        echo '<input type="text" class="form-control" id="datepickerTo" name="datepickerTo" placeholder="to" />';
                    }
                ?>
            </div>
        </div>
    </div>
    
    <?php 
        if( $isEditing ){
            ?>
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-2"><label for="leaveDate">Total Leave</label></div>
                        <div class="col-sm-5">
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    This year leave:
                                </div>
                                <div class="col-sm-6">
                                    <?php 
                                        echo '<input type="text" class="form-control" disabled value="'.$editingLeave->TotalLeave.'"/>';
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    Bringforward leave:
                                </div>
                                <div class="col-sm-6">
                                    <?php 
                                        echo '<input type="text" class="form-control" disabled value="'.$editingLeave->TotalBringForwardLeave.'"/>';
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
        }
    ?>

    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="leaveType">Leave Type </label></div>
            <div class="col-sm-5">
                <select id="leaveType" name="leaveType" class="form-control">
                    <option value="-1"> -- Please select -- </option>
                    <?php
                        foreach( $leaveTypeList as $lv ){
                            if( $isEditing && $editingLeave->LeaveTypeId == $lv->Id ){
                                echo '<option value="'.$lv->Id.'" selected>'.$lv->LeaveName.'</option>';
                            }else{
                                echo '<option value="'.$lv->Id.'">'.$lv->LeaveName.'</option>';
                            }
                        }
                    ?>
                </select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="leaveDate">Half Day</label></div>
            <div class="col-sm-5">
                <?php 
                    if( $isEditing ){
                        if($editingLeave->TotalLeave == 0.5){
                            echo '<input type="checkbox" id="halfDay" name="halfDay" disabled=disabled checked />';
                        }else{
                            echo '<input type="checkbox" id="halfDay" name="halfDay" disabled=disabled />';
                        }
                    }else{
                        echo '<input type="checkbox" id="halfDay" name="halfDay" disabled=disabled />';
                    }
                ?>
                <small> *Applicable when applying same date leave</small>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="remarks">Remarks</label></div>
            <div class="col-sm-5">
                <?php 
                    if( $isEditing ){
                        echo '<textarea id="remarks" name="remarks" class="form-control" disabled>'.$editingLeave->Remarks.'</textarea>';
                    }else{
                        echo '<textarea id="remarks" name="remarks" class="form-control"></textarea>';
                    }
                ?>
                
            </div>
        </div>
    </div>
    
    <?php 
        if( $isEditing ){
            ?>
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-2"><label for="leaveStatus">Status </label></div>
                        <div class="col-sm-5">
                            <select id="leaveStatus" name="leaveStatus" class="form-control" disabled>
                                <option value="-1"> -- Please select -- </option>
                                <?php
                                    foreach( $leaveCtrl->GetLeaveStatus() as $lv ){
                                        if( $editingLeave->Status == $lv->Id ){
                                            echo '<option value="'.$lv->Id.'" selected>'.$lv->StatusName.'</option>';
                                        }else{
                                            echo '<option value="'.$lv->Id.'">'.$lv->StatusName.'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-2"><label for="supervisor-remarks">Supervisor Remarks</label></div>
                        <div class="col-sm-5">
                            <textarea id="supervisorRemarks" name="supervisorRemarks" class="form-control"><?php if( $isEditing ){echo $editingLeave->SupervisorRemarks;}?></textarea>
                        </div>
                    </div>
                </div>
            <?php
        }
    ?>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"></div>
            <div class="col-sm-5">
                <?php 
                    if( $isEditing ){
                        echo '<button class="btn btn-primary btn-sm" id="submit" name="submit" disabled type="submit" onclick="return ValidateLeaveApplicationForm();">Apply</button>';
                    }else{
                        echo '<button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit" onclick="return ValidateLeaveApplicationForm();">Apply</button>';
                    }
                ?>
                
                <button class="btn btn-danger btn-sm" type="button">Cancel</button>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">

    function IsSameDate(){
        var fromDate = $('#datepickerFrom').val();
        var toDate = $('#datepickerTo').val();
        
        if( fromDate == '' && fromDate == undefined ){
            return;
        }
        
        if( toDate == '' && toDate == undefined ){
            return;
        }
        
        //check whether both are same date, if yes, then enabled the half day checkbox
        if( fromDate == toDate ){
            $('#halfDay').prop('disabled', false);
        }else{
            $('#halfDay').attr('checked', false).prop('disabled', true);
        }
    }
    
    function ValidateLeaveApplicationForm(){
        var isValidated = false;
        
        var fromDate = $('#datepickerFrom').val();
        var toDate = $('#datepickerTo').val();
        var leaveType = $('#leaveType').val();
        
        if( fromDate == '' || fromDate == undefined ){
            ShowErrorMessage('Please select start date');
            return isValidated;
        }
        
        if( toDate == '' || toDate == undefined ){
            ShowErrorMessage('Please select end date');
            return isValidated;
        }
        
        if( leaveType == -1 ){
            ShowErrorMessage('Please select leave type');
            return isValidated;
        }
        
        isValidated = true; //Set to true if pass all the required validation
        
        return isValidated; 
    }

    function ShowErrorMessage(errorMessage){
        $('#leaveApplicationErrorMessage').removeClass('hide');
        $('#leaveApplicationErrorMessage > span').html(errorMessage);
    }

    $(document).ready(function(){
        var currentYear = (new Date).getFullYear();;
        $('#datepickerFrom').datepicker({
            minDate: new Date(currentYear, 0, 1),
            maxDate: new Date(currentYear, 11, 31),
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 2,
            onClose: function( selectedDate ) {
                $( "#datepickerTo" ).datepicker( "option", "minDate", selectedDate );
                IsSameDate();
            }
        });
        $('#datepickerTo').datepicker({
            minDate: new Date(currentYear, 0, 1),
            maxDate: new Date(currentYear, 11, 31),
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 2,
            onClose: function( selectedDate ) {
                $( "#datepickerFrom" ).datepicker( "option", "maxDate", selectedDate );
                IsSameDate();
            }
        });
    });
</script>