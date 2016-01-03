<?php
    $leaveTypeList = $leaveCtrl->GetLeaveTypes();
    $pageList = $pageCtrl->GetPages();
    
    $isEditing = false;
    $editingRoleLeave = null;
    $editingRoleAccess = null;
    $dbOptResp = null;
    
    if( isset($_GET["id"]) && $_GET["id"] !== '0' ){
        //To edit
        $isEditing = true;
        $roleLeaveList = $roleCtrl->GetRoleLeaveById($_GET["id"]);
        if( $roleLeaveList != null && count($roleLeaveList) == 1){
            $editingRoleLeave = $roleLeaveList[0];
        }
        
        $roleAccessList = $roleCtrl->GetRoleAccessByRoleId($_GET["id"]);
        if( $roleAccessList != null && count($roleAccessList) == 1){
            $editingRoleAccess = $roleAccessList[0];
        }
    }
    
    if (isset($_POST["submit"])){
        $roleName = $_POST["roleName"];
        
        if( !empty($roleName) ){
			$dbOpt = $roleCtrl->NewRole($roleName ,$loginCtrl->GetUserName());
			if( $dbOpt->OptStatus ){
				$dbOptResp = $roleCtrl->AddNewRoleLeave($dbOpt->OptObj->Id, PrepareRoleLeaveFromForm($leaveTypeList), $loginCtrl->GetUserName() );
                $dbOptResp = $roleCtrl->AddNewRoleAccess($dbOpt->OptObj->Id, PrepareRoleAccessFromForm($pageList), $loginCtrl->GetUserName() );
			}else{
                $dbOptResp = $dbOpt;
            }
		}
    }
    
    //update
    if (isset($_POST["update"])){
        $roleName = $_POST["roleName"];
        //Update role name
        $editingRole = $editingRoleLeave->Role;
        
        if( $roleName != $editingRole->RoleName){
            $editingRole->RoleName = $roleName;
            $dbOptResp = $roleCtrl->UpdateRole($editingRole,$loginCtrl->GetUserName() );
        }
        
        $editingRoleLeave->LeaveAttribute = PrepareRoleLeaveFromForm($leaveTypeList);
        $dbOptResp = $roleCtrl->UpdateRoleLeave($editingRoleLeave, $loginCtrl->GetUserName() );
        
        $editingRoleAccess->RoleAccessAttributes = PrepareRoleAccessFromForm($pageList);
        $dbOptResp = $roleCtrl->UpdateRoleAccess($editingRoleAccess, $loginCtrl->GetUserName() );
    }
    
    function PrepareRoleLeaveFromForm($leaveTypeList){
        $roleLeaveArray = array();
        foreach( $leaveTypeList as $lv ){
            $roleLeaveTB = $_POST["leaveType".$lv->Id];
            $roleLeaveTB = trim($roleLeaveTB);
            if( !empty($roleLeaveTB) ){
                $roleLeaveArray[$lv->Id] = $roleLeaveTB;
            }
        }
        $jsonEncodedArray = json_encode($roleLeaveArray);
        return $jsonEncodedArray;
    }
    
    function PrepareRoleAccessFromForm($pageList){
        $pageCheckedArray = array();
        foreach( $pageList as $pl ){
            if( isset($_POST["page".$pl->Id]) ){
                array_push($pageCheckedArray, $pl->Id);
            }
        }
        $comma_separated = implode(",", $pageCheckedArray);
        $pageAccessAttributeJsonArray = array();
        $pageAccessAttributeJsonArray["PageIds"] = $comma_separated;
        $pageAccessAttributeJsonString = json_encode($pageAccessAttributeJsonArray);
        return $pageAccessAttributeJsonString;
    }
?>

<form method="post">
    <div class="row" >
        <div class="col-sm-12 form-group">
            <div class="alert alert-danger hide" role="alert" id="prorateErrorMessage">
                <strong>Error: </strong><span></span>
            </div>
            <?php 
                if( $dbOptResp != null ){
                    if( $dbOptResp->OptStatus ){
                        echo '<div class="alert alert-success" role="alert">';
                        echo '<span>'.$dbOptResp->OptMessage.'</span>';
                        echo '</div>';
                    }else{
                        echo '<div class="alert alert-danger" role="alert">';
                        echo '<span>'.$dbOptResp->OptMessage.'</span>';
                        echo '</div>';
                    }
                }
            ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="user">Role</label></div>
            <div class="col-sm-5">
                <?php 
                    if( $isEditing ){
                        echo '<input type="text" class="form-control" id="roleName" name="roleName" value="'.$editingRoleLeave->Role->RoleName.'" />';
                    }else{
                        echo '<input type="text" class="form-control" id="roleName" name="roleName" />';
                    }
                ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="leaveType">Leave Type </label></div>
            <div class="col-sm-5">
                <?php
                    $editingLeaveDecodedArray = null;
                    if( $isEditing ){
                        $editingLeaveDecodedArray = json_decode($editingRoleLeave->LeaveAttribute,true);
                    }
                        foreach( $leaveTypeList as $lv ){
                            echo '<div class="row">';
                            echo '<div class="col-sm-12 form-group">';
                            echo '<div class="col-sm-6"><label for="">'.$lv->LeaveName.'</label></div>';
                            echo '<div class="col-sm-6">';
                            if( $isEditing && array_key_exists($lv->Id,$editingLeaveDecodedArray ) ){
                                $leaveValue = $editingLeaveDecodedArray[$lv->Id];
                                echo '<input type="text" class="form-control leaveTypeTB" value="'.$leaveValue.'" id="leaveType'.$lv->Id.'" name="leaveType'.$lv->Id.'" />';
                            }else{
                                echo '<input type="text" class="form-control leaveTypeTB" id="leaveType'.$lv->Id.'" name="leaveType'.$lv->Id.'" />';
                            }
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="pages">Pages Access</label></div>
            <div class="col-sm-5">
                <?php 
                    $editingPageAccessAttributeString;
                    $editingPageAccessArray = null;
                    if( $isEditing ){
                        $editingPageAccessAttributeString = $editingRoleAccess->RoleAccessAttributes;
                        $tempJsonArray = json_decode($editingPageAccessAttributeString, true);
                        if( array_key_exists("PageIds",$tempJsonArray) ){
                            $tempPageAccessString = $tempJsonArray["PageIds"];
                            if( !empty($tempPageAccessString)){
                                $editingPageAccessArray = explode(",",$tempPageAccessString);
                            }
                        }
                    }
                    foreach($pageList as $pg){
                        echo '<div class="col-sm-12 form-group">';
                        echo '<div class="input-group">';
                        echo '<span class="input-group-addon">';
                        if( $isEditing && $editingPageAccessArray != null && in_array($pg->Id, $editingPageAccessArray)){
                            echo '<input type="checkbox" checked id="page'.$pg->Id.'" name="page'.$pg->Id.'" aria-label="...">';
                        }else{
                            echo '<input type="checkbox" id="page'.$pg->Id.'" name="page'.$pg->Id.'" aria-label="...">';
                        }
                        echo '</span>';
                        echo '<input type="text" class="form-control" value="'.$pg->PageName.'" disabled>';
                        echo '</div>';
                        echo '</div>';
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
                        echo '<button class="btn btn-primary btn-sm" id="update" name="update" type="submit" onclick="return ValidateProRateForm();">Update</button>';
                    }else{
                        echo '<button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit" onclick="return ValidateProRateForm();">Apply</button>';
                    }
                ?>
                <button class="btn btn-danger btn-sm" type="button">Cancel</button>
            </div>
        </div>
    </div>
</form>