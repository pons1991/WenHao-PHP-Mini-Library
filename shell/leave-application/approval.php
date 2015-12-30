<?php

    $dbOptResp = null;
    $isEditing = false;
    $editingLeave = null;
    if( isset($_GET["id"]) && $_GET["id"] !== '0' ){
        //To edit
        $isEditing = true;
        $leaveList = $leaveCtrl->GetLeaveByid($_GET["id"]);
        if( $leaveList != null && count($leaveList) == 1){
            $editingLeave = $leaveList[0];
        }
    }
    
    if (isset($_POST["approve"]) || isset($_POST["reject"]) ){
        $applicationStatus;
        if(isset($_POST["approve"])){
            $applicationStatus = 2;
        }
        
        if(isset($_POST["reject"])){
            $applicationStatus = 3;
        }
        
        $supervisorRemarks = $_POST["supervisorRemarks"];
        
        $editingLeave->Status = $applicationStatus;
        $editingLeave->ApprovedBy = $loginCtrl->GetUserId();
        $editingLeave->SupervisorRemarks = $supervisorRemarks;
        
        $dbOptResp = $leaveCtrl->UpdateApplicationLeave($editingLeave, $loginCtrl->GetUserName());
    }
?>

<form method="post">
    <div class="row" >
        <div class="col-sm-12 form-group">
            <div class="alert alert-danger hide" role="alert" id="leaveApplicationErrorMessage">
                <strong>Error: </strong><span></span>
            </div>
            <?php 
                if (isset($_POST["approve"]) || isset($_POST["reject"])){
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
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="leaveType">Leave Type </label></div>
            <div class="col-sm-5">
                <select id="leaveType" name="leaveType" class="form-control" disabled>
                    <option value="-1"> -- Please select -- </option>
                    <?php
                        foreach( $leaveCtrl->GetLeaveTypes() as $lv ){
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
                <textarea id="remarks" name="remarks" class="form-control" disabled><?php if( $isEditing ){echo $editingLeave->Remarks;}?></textarea>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="leaveStatus">Status </label></div>
            <div class="col-sm-5">
                <select id="leaveStatus" name="leaveStatus" class="form-control" disabled>
                    <option value="-1"> -- Please select -- </option>
                    <?php
                        
                        foreach( $leaveCtrl->GetLeaveStatus() as $lv ){
                            if( $isEditing && $editingLeave->Status == $lv->Id ){
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
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"></div>
            <div class="col-sm-5">
                <?php 
                    if( $isEditing && $editingLeave->Status == 1 ){
                        echo '<button class="btn btn-primary btn-sm" id="approve" name="approve" type="submit" onclick="return ValidateApproveLeaveApplicationForm();">Approve</button>';
                        echo '<button class="btn btn-danger btn-sm" id="reject" name="reject" type="submit" onclick="return ValidateApproveLeaveApplicationForm();">Reject</button>';
                    }else{
                        echo '<button class="btn btn-primary btn-sm" id="approve" name="approve" type="submit" onclick="return ValidateApproveLeaveApplicationForm();" disabled>Approve</button>';
                        echo '<button class="btn btn-danger btn-sm" id="reject" name="reject" type="submit" onclick="return ValidateApproveLeaveApplicationForm();" disabled>Reject</button>';
                    }
                ?>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    function ValidateApproveLeaveApplicationForm(){
        var isValidated = false;
        
        var supervisorRemarks = $('#supervisorRemarks').val();
        
        if( supervisorRemarks == '' || supervisorRemarks == undefined ){
            ShowErrorMessage('Please fill in supervisor remarks');
            return isValidated;
        }
        
        isValidated = true; //Set to true if pass all the required validation
        
        return isValidated; 
    }

    function ShowErrorMessage(errorMessage){
        $('#leaveApplicationErrorMessage').removeClass('hide');
        $('#leaveApplicationErrorMessage > span').html(errorMessage);
    }
</script>