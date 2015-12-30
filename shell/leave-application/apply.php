<?php

    $dbOptResp = null;

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
        
        //validation on pro rated leave
        $appliedLeave = $leaveCtrl->GetNonRejectedLeaveApplication($loginCtrl->GetUserId(), date('Y'));
        $totalAppliedDay = 0.0;
        if( count($appliedLeave) > 0 ){
            foreach($appliedLeave as $la ){
                $totalAppliedDay += $la->TotalLeave;
            }
        }
        
        $proRatedList = $leaveCtrl->GetProRatedLeaveByUserIdAndYear($loginCtrl->GetUserId(), date('Y'));
        if( $proRatedList != null && count($proRatedList) == 1 ){
            $proRated = $proRatedList[0];
            $proRatedLeaveArray = json_decode($proRated->ProRatedAttributes, true);
            
            $leaveTypeNumber = $proRatedLeaveArray[$leaveType];
            if ( ($totalAppliedDay + $dateDiff) > $leaveTypeNumber){
                //error
                $dbOptResp = new DbOpt;
                $dbOptResp->OptStatus = false;
                $dbOptResp->OptMessage = 'Your applied leave has exceed your available leave';
            }else{
                //proceed
                $dbOptResp = $leaveCtrl->ApplyLeave($fromDateFormat,$toDateFormat, $dateDiff, $leaveType, $remarks, $userId, $userEmail);
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
                    if( ($totalAppliedDay + $dateDiff) > $leaveTypeNumber ){
                        //error
                        $dbOptResp = new DbOpt;
                        $dbOptResp->OptStatus = false;
                        $dbOptResp->OptMessage = 'Your applied leave has exceed your available leave';
                    }else{
                        //proceed
                        $dbOptResp = $leaveCtrl->ApplyLeave($fromDateFormat,$toDateFormat, $dateDiff, $leaveType, $remarks,$approvalRemarks, $userId, $userEmail);
                    }
                }
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
            <div class="col-sm-5"><input type="text" class="form-control" id="datepickerFrom" name="datepickerFrom" placeholder="From" /></div>
            <div class="col-sm-5"><input type="text" class="form-control" id="datepickerTo" name="datepickerTo" placeholder="to" /></div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="leaveType">Leave Type </label></div>
            <div class="col-sm-5">
                <select id="leaveType" name="leaveType" class="form-control">
                    <option value="-1"> -- Please select -- </option>
                    <?php
                        foreach( $leaveCtrl->GetLeaveTypes() as $lv ){
                            echo '<option value="'.$lv->Id.'">'.$lv->LeaveName.'</option>';
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
                <input type="checkbox" id="halfDay" name="halfDay" disabled=disabled />
                <small> *Applicable when applying same date leave</small>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="remarks">Remarks</label></div>
            <div class="col-sm-5">
                <textarea id="remarks" name="remarks" class="form-control"></textarea>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"></div>
            <div class="col-sm-5">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit" onclick="return ValidateLeaveApplicationForm();">Apply</button>
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