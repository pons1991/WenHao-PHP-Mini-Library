<?php 
   $dbOptResp = null; 
   $isEditing = false;
   $editingUser = null;
   $editingUserRole = null;
   
   //Get current user via session
   $editingUserId = $loginCtrl->GetUserId();
   
   if( isset($editingUserId) && $editingUserId !== '0' ){
        //To edit
        $isEditing = true;
        $userList = $userCtrl->GetUserOrgRel($editingUserId);
        if( $userList != null && count($userList) == 1){
            $editingUser = $userList[0];
        }
        
        $userRoleList = $roleCtrl->GetRoleLeaveByUserId($editingUserId);
        if( $userRoleList != null && count($userRoleList) == 1){
            $editingUserRole = $userRoleList[0];
        }
    }
    
    //update
    if (isset($_POST["update"])){
        $email = $_POST["email"];
		$password = $_POST["password"];
		$userid = $_POST["userid"];
		$role = $_POST["role"];
        $reportingTo = $_POST["reportingTo"];
        
	    $dbOptResp = $userCtrl->UpdateUser($editingUser->User, $email, $password, $userid, $loginCtrl->GetUserName());
		if( $dbOptResp->OptStatus ){
            $dbOptResp  = $roleCtrl->UpdateRole($editingUserRole, $role,$loginCtrl->GetUserName() );
		    if( $dbOptResp->OptStatus ){
                $dbOptResp = $userCtrl->UpdateOrgRel($editingUser,$reportingTo , $loginCtrl->GetUserName());
            }
	    }
    }
?>

<form method="post">
    <div class="row" >
        <div class="col-sm-12 form-group">
            <div class="alert alert-danger hide" role="alert" id="userErrorMessage">
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
            <div class="col-sm-2"><label for="userid">User Id</label></div>
            <div class="col-sm-5">
                <?php 
                    if( $isEditing ){
                        $customAttributeArray = json_decode($editingUser->User->CustomAttribute, true);
                        if( $customAttributeArray != null ){
                            if( array_key_exists("userid", $customAttributeArray) ){
                                echo '<input type="text" class="form-control" value="'.$customAttributeArray["userid"].'" disabled/>';
                                echo '<input type="hidden" class="form-control" id="userid" name="userid" value="'.$customAttributeArray["userid"].'"/>';
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
                        echo '<input type="email" class="form-control" value="'.$editingUser->User->Email.'" disabled/>';
                        echo '<input type="hidden" class="form-control" name="email" id="email" value="'.$editingUser->User->Email.'"/>';
                    }
                ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="password">Password</label></div>
            <div class="col-sm-5">
                <?php 
                    if( $isEditing ){
                        echo '<input type="password" name="password" id="password" class="form-control" value="'.$editingUser->User->Password.'" />';
                    }
                ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="role">Role</label></div>
            <div class="col-sm-5">
                <?php 
                    echo '<input type="hidden" class="form-control" name="role" id="role" value="'.$editingUserRole->RoleId.'"/>';
                ?>
                <select class="form-control" required disabled>
                    <option value="-1"> -- Please select -- </option>
                    <?php 
                        foreach($roleCtrl->GetRoles() as $role ){
                            if( $isEditing && $editingUserRole->RoleId == $role->Id ){
                                echo '<option value="'.$role->Id.'" selected>'.$role->RoleName.'</option>';
                            }else{
                                echo '<option value="'.$role->Id.'">'.$role->RoleName.'</option>';
                            }
                        }
                    ?>
			</select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="reportingTo">Reporting to</label></div>
            <div class="col-sm-5">
                <?php 
                    echo '<input type="hidden" class="form-control" name="reportingTo" id="reportingTo" value="'.$editingUser->SuperiorUserId.'"/>';
                ?>
                <select  class="form-control" required disabled>
                    <option value="-1"> -- Please select -- </option>
                    <?php 
                        foreach($userCtrl->GetUsers() as $user ){
                            if( $isEditing && $editingUser->SuperiorUserId == $user->Id ){
                                echo '<option value="'.$user->Id.'" selected>'.$user->Email.'</option>';
                            }else if($user->Id == $editingUser->UserId){
                                //skip if current user is editing, avoid showing current user as reporting to
                            }else{
                                echo '<option value="'.$user->Id.'">'.$user->Email.'</option>';
                            }
                        }
                    ?>
			</select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"></div>
            <div class="col-sm-5">
                <?php 
                    if($isEditing){
                        echo '<button class="btn btn-primary btn-sm" id="update" name="update" type="submit" onclick="return ValidateUser();">Update</button>';
                    }
                ?>
                <button class="btn btn-danger btn-sm" type="button">Cancel</button>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    function ValidateUser(){
        var emailRegex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        
        var userId = $('#userid').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var roleId = $('#role').val();
        var reportingToId = $('#reportingTo').val();
        
        var isValidated = false;
        
        userId = userId.trim();
        $('#userid').val(userId);
        email = email.trim();
        $('#email').val(email);
        
        if( userId == "" ){
            ShowErrorMessage('Please insert a valid user id');
            return isValidated;
        }
        
        if( email == "" ){
            ShowErrorMessage('Please insert a valid email');
            return isValidated;
        }
        
        if( !emailRegex.test(email) ){
            ShowErrorMessage('Please insert a valid email');
            return isValidated;
        }
        
        if( password == "" ){
            ShowErrorMessage('Please insert a valid password');
            return isValidated;
        }
        
        if( roleId == -1 ){
            ShowErrorMessage('Please select a role');
            return isValidated;
        }
        
        if( reportingToId == -1 ){
            ShowErrorMessage('Please select a reporting person');
            return isValidated;
        }
        
        isValidated = true;
        
        return isValidated;
    }
    
    function ShowErrorMessage(errorMessage){
        $('#userErrorMessage').removeClass('hide');
        $('#userErrorMessage > span').html(errorMessage);
    }
</script>