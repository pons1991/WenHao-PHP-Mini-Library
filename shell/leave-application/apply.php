<?php

    $dbOptResp = null;
    $isEditing = false;
    $editingLeave = null;
    
    $leaveTypeList = $leaveCtrl->GetLeaveTypes($GLOBALS["DEFAULT_PAGE_INDEX"], $GLOBALS["DEFAULT_MAX_PAGE_INDEX"]);
    
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
        
        $mondayCB = isset($_POST["mondayCB"]) ? true : false;
        $tuesdayCB = isset($_POST["tuesdayCB"]) ? true : false;
        $wednesdayCB = isset($_POST["wednesdayCB"]) ? true : false;
        $thursdayCB = isset($_POST["thursdayCB"]) ? true : false;
        $fridayCB = isset($_POST["fridayCB"]) ? true : false;
        $saturdayCB = isset($_POST["saturdayCB"]) ? true : false;
        $sundayCB = isset($_POST["sundayCB"]) ? true : false;
        
        $offDayRemarkArray = array();
        $offDayRemarkArray["Monday"] = $mondayCB ? 1 : 0;
        $offDayRemarkArray["Tuesday"] = $tuesdayCB ? 1 : 0;
        $offDayRemarkArray["Wednesday"] = $wednesdayCB ? 1 : 0;
        $offDayRemarkArray["Thursday"] = $thursdayCB ? 1 : 0;
        $offDayRemarkArray["Friday"] = $fridayCB ? 1 : 0;
        $offDayRemarkArray["Saturday"] = $saturdayCB ? 1 : 0;
        $offDayRemarkArray["Sunday"] = $sundayCB ? 1 : 0;
        $jsonEncodedOffDayRemarkArray = json_encode($offDayRemarkArray);
        
        $totalOffDay = 0;
        $interval = DateInterval::createFromDateString('1 day');
        $incrementalDate = $fromDateObj;
        for( ; $incrementalDate <= $toDateObj ; $incrementalDate->add($interval) ){
            $dayNumeric = $incrementalDate->format('N');
            switch( $dayNumeric ){
                case 1:
                    $totalOffDay = $mondayCB ? $totalOffDay + 1 : $totalOffDay;
                    break;
                case 2:
                    $totalOffDay = $tuesdayCB ? $totalOffDay + 1 : $totalOffDay;
                    break;
                case 3:
                    $totalOffDay = $wednesdayCB ? $totalOffDay + 1 : $totalOffDay;
                    break;
                case 4:
                    $totalOffDay = $thursdayCB ? $totalOffDay + 1 : $totalOffDay;
                    break;
                case 5:
                    $totalOffDay = $fridayCB ? $totalOffDay + 1 : $totalOffDay;
                    break;
                case 6:
                    $totalOffDay = $saturdayCB ? $totalOffDay + 1 : $totalOffDay;
                    break;
                case 7:
                    $totalOffDay = $sundayCB ? $totalOffDay + 1 : $totalOffDay;
                    break;
                       
            }
        }
        
        $currentYearStr = date('Y');
        $currentYear = intval(date('Y'));
        $previousYear = $currentYear - 1;

        $totalAvailableBringForward = 0.0;

        //Bring forward algorithm 
        $totalAvailableBringForward = GetTotalAvailableBringForward($leaveCtrl, $userId, $previousYear,$leaveType);
        
        //Non rejected leave application algorithm
        $totalAppliedDay;
        $totalUsedBringForwardDay;
        GetNonRejectedLeave($leaveCtrl,$userId, $currentYear, $totalAppliedDay, $totalUsedBringForwardDay);
        
        $totalBringForwardToApply = 0.0;
        $totalCurrentToApply = 0.0;
        $totalBringForwardToApply = $totalAvailableBringForward - $totalUsedBringForwardDay;
        CalculateLeave($totalBringForwardToApply,$totalCurrentToApply,$totalAvailableBringForward,$totalUsedBringForwardDay,$dateDiff,$fromDateObj,$toDateObj, $totalOffDay);
        
        
        if( $totalCurrentToApply > 0 ){
            $leaveTypeNumber = 0;
            //check leave type
            $leaveTypeNumber += GetAccumulativeLeave($leaveTypeList,$leaveCtrl,$userId,$currentYearStr,$leaveType );
            
            //validation on pro rated leave
            $proRatedList = $leaveCtrl->GetProRatedLeaveByUserIdAndYear($userId,$currentYear);
            if( $proRatedList != null && count($proRatedList) == 1 ){
                $proRated = $proRatedList[0];
                $proRatedLeaveArray = json_decode($proRated->ProRatedAttributes, true);
                if( array_key_exists($leaveType, $proRatedLeaveArray) ){
                    $leaveTypeNumber += $proRatedLeaveArray[$leaveType];
                }
                if ( ($totalAppliedDay + $totalCurrentToApply) > $leaveTypeNumber){
                    //error
                    $dbOptResp = new DbOpt;
                    $dbOptResp->OptStatus = false;
                    $dbOptResp->OptMessage = 'Your applied leave has exceed your available leave';
                }else{
                    //proceed
                    $dbOptResp = $leaveCtrl->ApplyLeave($fromDateFormat,$toDateFormat, $totalCurrentToApply,$totalBringForwardToApply, $leaveType,$jsonEncodedOffDayRemarkArray, $remarks,$approvalRemarks,1, $userId, $userEmail);
                }
            }else{
                //validation on role leave
                $userRoleList = $roleCtrl->GetRoleLeaveByUserId($userId);
                if( $userRoleList != null && count($userRoleList) == 1 ){
                    $userRole = $userRoleList[0];
                    $roleLeaveList = $roleCtrl->GetRoleLeaveById($userRole->Role->Id);
                    if( $roleLeaveList != null && count($roleLeaveList) == 1 ){
                        $roleLeave = $roleLeaveList[0];
                        $roleLeaveArray = json_decode($roleLeave->LeaveAttribute, true);
                        if( array_key_exists($leaveType, $roleLeaveArray) ){
                            $leaveTypeNumber += $roleLeaveArray[$leaveType];
                        }
                        if( ($totalAppliedDay + $totalCurrentToApply) > $leaveTypeNumber ){
                            //error
                            $dbOptResp = new DbOpt;
                            $dbOptResp->OptStatus = false;
                            $dbOptResp->OptMessage = 'Your applied leave has exceed your available leave';
                        }else{
                            //proceed
                            $dbOptResp = $leaveCtrl->ApplyLeave($fromDateFormat,$toDateFormat, $totalCurrentToApply,$totalBringForwardToApply, $leaveType,$jsonEncodedOffDayRemarkArray, $remarks,$approvalRemarks,1, $userId, $userEmail);
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
                
                //Send Email - start
                $orgRelList = $userCtrl->GetUserOrgRel($userId);
                if( $orgRelList != null && count($orgRelList) == 1){
                    $orgRel = $orgRelList[0];
                    $subject = 'New leave application - '.$userEmail;
                    $emailCtrl->SendLeaveApplicationEmail($orgRel->SuperiorUser->Email,$userEmail,$subject,$userEmail,$fromDate,$toDate,$leaveTypeName,$jsonEncodedOffDayRemarkArray,$remarks,$totalCurrentToApply,$totalBringForwardToApply,'New','' );
                }
                //Send Email - End
            }
        }else{
            //The code should not reach here
           $dbOptResp = new DbOpt;
           $dbOptResp->OptStatus = false;
           $dbOptResp->OptMessage = 'Total Off Day cannot be greater than Total Applied Leave'; 
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
            <div class="col-sm-2">
                <label for="leaveDate">Off Day</label>
                <p><small>* System will not include selected off day when registering your leave</small></p>
            </div>
            <div class="col-sm-2">
                <!-- Monday -->
                <div class="input-group">
                    <span class="input-group-addon">
                        <input type="checkbox" id="mondayCB" name="mondayCB" aria-label="...">
                    </span>
                    <input type="text" class="form-control" readonly="true" value="Monday" />
                </div>
                
                <!-- Friday -->
                <div class="input-group">
                    <span class="input-group-addon">
                        <input type="checkbox" id="fridayCB" name="fridayCB" aria-label="...">
                    </span>
                    <input type="text" class="form-control" readonly="true" value="Friday" />
                </div>
                
                
                
            </div>
            <div class="col-sm-2">
                <!-- Tuesday -->
                <div class="input-group">
                    <span class="input-group-addon">
                        <input type="checkbox" id="tuesdayCB" name="tuesdayCB" aria-label="...">
                    </span>
                    <input type="text" class="form-control" readonly="true" value="Tuesday" />
                </div>
                
                <!-- Saturday -->
                <div class="input-group">
                    <span class="input-group-addon">
                        <input type="checkbox" id="saturdayCB" name="saturdayCB" aria-label="...">
                    </span>
                    <input type="text" class="form-control" readonly="true" value="Saturday" />
                </div>
                
                
            </div>
            <div class="col-sm-2">
                <!-- Wednesday -->
                <div class="input-group">
                    <span class="input-group-addon">
                        <input type="checkbox" id="wednesdayCB" name="wednesdayCB" aria-label="...">
                    </span>
                    <input type="text" class="form-control" readonly="true" value="Wednesday" />
                </div>
                
                
                <!-- Sunday -->
                <div class="input-group">
                    <span class="input-group-addon">
                        <input type="checkbox" id="sundayCB" name="sundayCB" aria-label="...">
                    </span>
                    <input type="text" class="form-control" readonly="true" value="Sunday" />
                </div>
                
            </div>
            
            <div class="col-sm-2">
                <!-- Thursday -->
                <div class="input-group">
                    <span class="input-group-addon">
                        <input type="checkbox" id="thursdayCB" name="thursdayCB" aria-label="...">
                    </span>
                    <input type="text" class="form-control" readonly="true" value="Thursday" />
                </div>
            </div>
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
                <select id="leaveType" name="leaveType" class="form-control" <?php if($isEditing){ echo 'disabled'; } ?>>
                    <option value="-1"> -- Please select -- </option>
                    <?php
                        $leaveAccessArray = null;
                        $leaveAccessList = $roleCtrl->GetLeaveAccessByRoleId($loginCtrl->GetUserRoleId());
                        if( $leaveAccessList != null && count($leaveAccessList) == 1 ){
                            $leaveAccess = $leaveAccessList[0];
                            if( $leaveAccess != null ){
                                $jsonStr = $leaveAccess->LeaveAccessAttributes;
                                if( !empty($jsonStr)){
                                    $jsonArr = json_decode($jsonStr, true);
                                    if( $jsonArr != null && array_key_exists('LeaveTypeIds', $jsonArr) ){
                                        $arrStr = $jsonArr['LeaveTypeIds'];
                                        if( !empty($arrStr) ){
                                            $leaveAccessArray = explode(',',$arrStr);
                                        }
                                    }
                                }
                            }
                        }
                        
                        if( $leaveAccessArray != null && count($leaveAccessArray) > 0 ){
                            foreach( $leaveTypeList as $lv ){
                                if( $isEditing && $editingLeave->LeaveTypeId == $lv->Id ){
                                    echo '<option value="'.$lv->Id.'" selected>'.$lv->LeaveName.'</option>';
                                }else{
                                    if( in_array($lv->Id,$leaveAccessArray) ){
                                        echo '<option value="'.$lv->Id.'">'.$lv->LeaveName.'</option>';
                                    }
                                }
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
        TriggerLoadingGif();
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