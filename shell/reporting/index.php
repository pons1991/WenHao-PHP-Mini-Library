<?php 
    $isEditing = false;
    $editingUser = null;
    $editingUserRole = null;
    $editingRoleLeave = null;
    $editingRoleAccess = null;
    $editingLeaveAccess = null;
    
    $leaveTypeList = null;
    $pageList = $pageCtrl->GetPages();
    
    if (isset($_POST["searchButton"])){
        $isEditing = true;
        $userList = $userCtrl->GetUserOrgRel($_POST["email"]);
        if( $userList != null && count($userList) == 1){
            $editingUser = $userList[0];
        }
        
        $userRoleList = $roleCtrl->GetRoleLeaveByUserId($_POST["email"]);
        if( $userRoleList != null && count($userRoleList) == 1){
            $editingUserRole = $userRoleList[0];
        }
        
        $roleLeaveList = $roleCtrl->GetRoleLeaveById($editingUserRole->Role->Id);
        if( $roleLeaveList != null && count($roleLeaveList) == 1){
            $editingRoleLeave = $roleLeaveList[0];
        }
        
        $roleAccessList = $roleCtrl->GetRoleAccessByRoleId($editingUserRole->Role->Id);
        if( $roleAccessList != null && count($roleAccessList) == 1){
            $editingRoleAccess = $roleAccessList[0];
        }
        
        $leaveAccessList = $roleCtrl->GetLeaveAccessByRoleId($editingUserRole->Role->Id);
        if( $leaveAccessList != null && count($leaveAccessList) == 1){
            $editingLeaveAccess = $leaveAccessList[0];
        }
        
        $leaveTypeList = $leaveCtrl->GetLeaveTypes($GLOBALS["DEFAULT_PAGE_INDEX"], $GLOBALS["DEFAULT_MAX_PAGE_INDEX"]);
        $pageList = $pageCtrl->GetPages();
    }
?>

<form method="post">
    <div class="row" >
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="email">Email</label></div>
            <div class="col-sm-5">
                <select id="email" name="email" class="form-control">
                    <?php 
                        foreach( $userCtrl->GetUsers($GLOBALS["DEFAULT_PAGE_INDEX"], $GLOBALS["DEFAULT_MAX_PAGE_INDEX"]) as $usr ){
                            echo '<option value="'.$usr->Id.'">'.$usr->Email.'</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
        
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"></div>
            <div class="col-sm-5">
                <button type="submit" class="btn btn-default" name="searchButton" id="searchButton">Search</button>
            </div>
        </div>
    </div>
</form>


<div>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist" style="margin-bottom: 15px;">
        <li role="presentation" class="active"><a href="#user" aria-controls="user" role="tab" data-toggle="tab">User</a></li>
        <li role="presentation"><a href="#role" aria-controls="role" role="tab" data-toggle="tab">Role</a></li>
        <li role="presentation"><a href="#pageAccess" aria-controls="role" role="tab" data-toggle="tab">Page Access</a></li>
        <li role="presentation"><a href="#leaveAccess" aria-controls="leave" role="tab" data-toggle="tab">Leave Access</a></li>
    </ul>
<?php 
    if( $isEditing ){
        ?>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- User Tab start -->
            <div role="tabpanel" class="tab-pane active" id="user">
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-2"><label for="email">User Id</label></div>
                        <div class="col-sm-5">
                            <?php 
                                if( $isEditing ){
                                    $customAttributeArray = json_decode($editingUser->User->CustomAttribute, true);
                                    if( $customAttributeArray != null ){
                                        if( array_key_exists("userid", $customAttributeArray) ){
                                            echo '<label class="form-control">'.$customAttributeArray["userid"].'</label>';
                                        }
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-2"><label for="email">Email</label></div>
                        <div class="col-sm-5">
                            <?php 
                                if( $isEditing ){
                                    echo '<label class="form-control">'.$editingUser->User->Email.'</label>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-2"><label for="email">Role</label></div>
                        <div class="col-sm-5">
                            <?php
                                if( $isEditing ){
                                    echo '<label class="form-control">'.$editingUserRole->Role->RoleName.'</label>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-2"><label for="email">Reporting To</label></div>
                        <div class="col-sm-5">
                            <?php
                                if( $isEditing ){
                                    echo '<label class="form-control">'.$editingUser->SuperiorUser->Email.'</label>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- User Tab End -->
            
            <!-- Role Leave Tab Start -->
            <div role="tabpanel" class="tab-pane" id="role">
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-2"><label for="email">Role</label></div>
                        <div class="col-sm-5">
                            <?php 
                                if( $isEditing ){
                                    echo '<label class="form-control">'.$editingRoleLeave->Role->RoleName.'</label>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-2"><label for="leaveType">Leave (Days)</label></div>
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
                                            echo '<label class="form-control">'.$leaveValue.'</label>';
                                        }else{
                                            echo '<label class="form-control">0</label>';
                                        }
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                    }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Role Leave Tab End -->
            
            <!-- Page Access Tab Start -->
            <div role="tabpanel" class="tab-pane" id="pageAccess">
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-2"><label for="email">Page Access</label></div>
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
                                        echo '<input type="checkbox" disabled="disabled" checked id="page'.$pg->Id.'" name="page'.$pg->Id.'" aria-label="...">';
                                    }else{
                                        echo '<input type="checkbox" disabled="disabled" id="page'.$pg->Id.'" name="page'.$pg->Id.'" aria-label="...">';
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
            </div>
            <!-- Page Access Tab End -->
            
            <!-- Leave Access Tab Start -->
            <div role="tabpanel" class="tab-pane" id="leaveAccess">
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-2"><label for="email">Accessed By</label></div>
                        <div class="col-sm-5">
                            <?php 
                            
                                $editingLeaveAccessAttributeString;
                                $editingLeaveAccessArray = null;
                                if( $isEditing ){
                                    $editingLeaveAccessAttributeString = $editingLeaveAccess->LeaveAccessAttributes;
                                    $tempJsonArray = json_decode($editingLeaveAccessAttributeString, true);
                                    if( array_key_exists("LeaveTypeIds",$tempJsonArray) ){
                                        $tempLeaveAccessString = $tempJsonArray["LeaveTypeIds"];
                                        if( !empty($tempLeaveAccessString)){
                                            $editingLeaveAccessArray = explode(",",$tempLeaveAccessString);
                                        }
                                    }
                                }
                                
                                foreach($leaveTypeList as $lt){
                                    echo '<div class="col-sm-12 form-group">';
                                    echo '<div class="input-group">';
                                    echo '<span class="input-group-addon">';
                                    if( $isEditing  && $editingLeaveAccessArray != null && in_array($lt->Id, $editingLeaveAccessArray) ){
                                        echo '<input type="checkbox" disabled="disabled" checked id="leave'.$lt->Id.'" name="leave'.$lt->Id.'" aria-label="...">';
                                    }else{
                                        echo '<input type="checkbox" disabled="disabled" id="leave'.$lt->Id.'" name="leave'.$lt->Id.'" aria-label="...">';
                                    }
                                    echo '</span>';
                                    echo '<input type="text" class="form-control" value="'.$lt->LeaveName.'" disabled>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Leave Access Tab End -->
        </div>
        <?php
    }
?>
</div>