<?php 
    $dbOptResp = null;
    $isEditing = false;
    $editingAccumulativeLeave = null;
    
    $accumulativeLeaveList = $leaveCtrl->GetAccumulativeLeaveType();
    $userList = $userCtrl->GetUsers();
    
    if( isset($_GET["id"]) && $_GET["id"] !== '0' ){
        //To edit
        $isEditing = true;
        $accumList = $leaveCtrl->GetAccumulativeLeaveById($_GET["id"]);
        if( $accumList != null && count($accumList) == 1){
            $editingAccumulativeLeave = $accumList[0];
        }
    }
    
    if (isset($_POST["submit"])){
        $userid = $_POST["userid"];
        $leaveType = $_POST["leaveType"];
        $remark = isset($_POST["remark"]) ? $_POST["remark"] : 'N/A' ;
        $expiredYear = date('Y');
        $leaveNumber = $_POST["leaveNumber"];
        
        $dbOptResp = $leaveCtrl->AddAccumulativeLeave($userid,$leaveType,$remark,$expiredYear,$leaveNumber,$loginCtrl->GetUserId());
    }
    
    if (isset($_POST["update"])){
        $userid = $_POST["userid"];
        $leaveType = $_POST["leaveType"];
        $remark = isset($_POST["remark"]) ? $_POST["remark"] : 'N/A' ;
        $expiredYear = date('Y');
        $leaveNumber = $_POST["leaveNumber"];
        
        $editingAccumulativeLeave->UserId = $userid;
        $editingAccumulativeLeave->LeaveTypeId = $leaveType;
        $editingAccumulativeLeave->Remarks = $remark;
        $editingAccumulativeLeave->ExpiredYear = $expiredYear;
        $editingAccumulativeLeave->AccumulativeLeaveNumber = $leaveNumber;
        
        $dbOptResp = $leaveCtrl->UpdateAccumulativeLeave($editingAccumulativeLeave, $loginCtrl->GetUserName());
    }
    
?>

<form method="post">
    <div class="row" >
        <div class="col-sm-12 form-group">
            <div class="alert alert-danger hide" role="alert" id="accumulativeLeaveError">
                <strong>Error: </strong><span></span>
            </div>
            <?php 
                if (isset($_POST["submit"]) || isset($_POST["update"])){
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
            <div class="col-sm-2"><label for="userid">Users</label></div>
            <div class="col-sm-5">
                <select id="userid" name="userid" class="form-control">
                    <option value="-1"> -- Please select -- </option>
                    <?php
                        foreach( $userList as $usr ){
                            if( $isEditing && $editingAccumulativeLeave->UserId == $usr->Id ){
                                echo '<option value="'.$usr->Id.'" selected>'.$usr->Email.'</option>';
                            }else{
                                echo '<option value="'.$usr->Id.'">'.$usr->Email.'</option>';
                            }
                        }
                    ?>
                </select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="leaveType">Leave Type</label></div>
            <div class="col-sm-5">
                <select id="leaveType" name="leaveType" class="form-control">
                    <option value="-1"> -- Please select -- </option>
                    <?php
                        foreach( $accumulativeLeaveList as $leave ){
                            if( $isEditing && $editingAccumulativeLeave->LeaveTypeId == $leave->Id ){
                                echo '<option value="'.$leave->Id.'" selected>'.$leave->LeaveName.'</option>';
                            }else{
                                echo '<option value="'.$leave->Id.'">'.$leave->LeaveName.'</option>';
                            }
                        }
                    ?>
                </select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="leaveNumber">Leave Number</label></div>
            <div class="col-sm-5">
                <?php 
                    if( $isEditing ){
                        echo '<input type="text" id="leaveNumber" name="leaveNumber" class="form-control" value="'.$editingAccumulativeLeave->AccumulativeLeaveNumber.'" />';
                    }else{
                        echo '<input type="text" id="leaveNumber" name="leaveNumber" class="form-control" />';
                    }
                ?>
                
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="remark">Remarks</label></div>
            <div class="col-sm-5">
                <textarea id="remark" name="remark" class="form-control"><?php if( $isEditing ){ echo $editingAccumulativeLeave->Remarks; } ?></textarea>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"></div>
            <div class="col-sm-5">
                <?php 
                    if($isEditing){
                        echo '<button class="btn btn-primary btn-sm" id="update" name="update" type="submit" onclick="return ValidateAccumulativeLeaveForm();">Update</button>';
                    }else{
                        echo '<button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit" onclick="return ValidateAccumulativeLeaveForm();">Apply</button>';
                    }
                ?>
                <button class="btn btn-danger btn-sm" type="button">Cancel</button>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    function ValidateAccumulativeLeaveForm(){
        
        var integerRegex = /^\+?(0|[1-9]\d*)$/;
        
        var isValidated = false;
        var userid = $('#userid').val();
        var leaveType = $('#leaveType').val();
        var remark = $('#remark').val();
        var leaveNumber = $('#leaveNumber').val();
        
        remark = remark.trim();
        $('#remark').val(remark);
        
        leaveNumber = leaveNumber.trim();
        $('#leaveNumber').val(leaveNumber);
        
        if( userid == -1 ){
            ShowErrorMessage('Please select a valid user');
            return isValidated;
        }
        
        if( leaveType == -1 ){
            ShowErrorMessage('Please select a valid leave');
            return isValidated;
        }
        
        if( leaveNumber == '' || leaveNumber == undefined ){
            ShowErrorMessage('Please enter a valid leave number');
            return isValidated;
        }
        
        if( !integerRegex.test(leaveNumber) ){
            ShowErrorMessage('Please enter a valid leave number');
            return isValidated;
        }
        
        isValidated = true;
        return isValidated;
    }
    
    function ShowErrorMessage(errorMessage){
        $('#accumulativeLeaveError').removeClass('hide');
        $('#accumulativeLeaveError > span').html(errorMessage);
    }
    
</script>