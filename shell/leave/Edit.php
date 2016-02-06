<?php 
    $dbOptResp = null;
    $isEditing = false;
    $editingLeaveType = null;
    
    $roleList = $roleCtrl->GetRoles($GLOBALS["DEFAULT_PAGE_INDEX"], $GLOBALS["DEFAULT_MAX_PAGE_INDEX"]);
    
    if( isset($_GET["id"]) && $_GET["id"] !== '0' ){
        //To edit
        $isEditing = true;
        $leaveTypeList = $leaveCtrl->GetLeaveTypeById($_GET["id"]);
        if( $leaveTypeList != null && count($leaveTypeList) == 1){
            $editingLeaveType = $leaveTypeList[0];
        }
    }
    
    if (isset($_POST["submit"])){
        $leaveName = $_POST["leaveName"];
        $bringForward = isset($_POST["bringForward"]) ? 1 : 0;
        $accumulative = isset($_POST["accumulate"]) ? 1 : 0;
        $dbOptResp = $leaveCtrl->AddLeaveType($leaveName,$bringForward,$accumulative,$loginCtrl->GetUserId());
    }
    
    if (isset($_POST["update"])){
        $leaveName = $_POST["leaveName"];
        $bringForward = isset($_POST["bringForward"]) ? 1 : 0;
        $accumulative = isset($_POST["accumulate"]) ? 1 : 0;
        
        $editingLeaveType->LeaveName = $leaveName;
        $editingLeaveType->IsAllowToBringForward = $bringForward;
        $editingLeaveType->IsAllowToAccumulate = $accumulative;

        $dbOptResp = $leaveCtrl->UpdateLeaveType($editingLeaveType,$loginCtrl->GetUserId());
    }
?>

<form method="post">
    <div class="row" >
        <div class="col-sm-12 form-group">
            <div class="alert alert-danger hide" role="alert" id="leaveTypeErrorMessage">
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
            <div class="col-sm-2"><label for="leaveName">Leave Date </label></div>
            <div class="col-sm-5">
                <?php 
                    if( $isEditing ){
                        echo '<input type="text" class="form-control" id="leaveName" name="leaveName" placeholder="Leave Name" value="'.$editingLeaveType->LeaveName.'" />';
                    }else{
                        echo '<input type="text" class="form-control" id="leaveName" name="leaveName" placeholder="Leave Name" />';
                    }
                ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="bringForward">Is Allowed To Bring Forward</label></div>
            <div class="col-sm-5">
                <?php 
                    if( $isEditing && $editingLeaveType->IsAllowToBringForward ){
                        echo '<input type="checkbox" id="bringForward" name="bringForward" checked />';
                    }else{
                        echo '<input type="checkbox" id="bringForward" name="bringForward" />';
                    }
                ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="accumulate">Is Allowed To Accumulate</label></div>
            <div class="col-sm-5">
                <?php 
                    if( $isEditing && $editingLeaveType->IsAllowToAccumulate ){
                        echo '<input type="checkbox" id="accumulate" name="accumulate" checked />';
                    }else{
                        echo '<input type="checkbox" id="accumulate" name="accumulate" />';
                    }
                ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"></div>
            <div class="col-sm-5">
                <?php 
                    if($isEditing){
                        echo '<button class="btn btn-primary btn-sm" id="update" name="update" type="submit" onclick="return ValidateLeaveApplicationForm();">Update</button>';
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

    function ValidateLeaveTypeForm(){
        var isValidated = false;
        
        var leaveName = $('#leaveName').val();
        leaveName = leaveName.trim();
        $('#leaveName').val(leaveName);
        
        if( leaveName == '' || leaveName == undefined ){
            ShowErrorMessage('Plese insert a valid leave name');
            return isValidated;
        }
        
        isValidated = true;
        TriggerLoadingGif();
        return isValidated;
    }

    function ShowErrorMessage(errorMessage){
        $('#leaveTypeErrorMessage').removeClass('hide');
        $('#leaveTypeErrorMessage > span').html(errorMessage);
    }
</script>