<?php 
   $dbOptResp = null; 
   $isEditing = false;
   $editingUser = null;
   $editingUserRole = null;
   
   $urlPath = "uploads/profile/";
   $target_dir = "../../uploads/profile/";
   
   
   if( isset($_GET["id"]) && $_GET["id"] !== '0' ){
        //To edit
        $isEditing = true;
        $userList = $userCtrl->GetUserOrgRel($_GET["id"]);
        if( $userList != null && count($userList) == 1){
            $editingUser = $userList[0];
        }
        
        $userRoleList = $roleCtrl->GetRoleLeaveByUserId($_GET["id"]);
        if( $userRoleList != null && count($userRoleList) == 1){
            $editingUserRole = $userRoleList[0];
        } 
    }
   
   if (isset($_POST["submit"])){
		//create new user
        $target_file = $target_dir . basename($_FILES["profileImage"]["name"]);
		$email = $_POST["email"];
		$password = $_POST["password"];
		$userid = $_POST["userid"];
		$role = $_POST["role"];
        $fullName = $_POST["fullName"];
        $contactNumber = $_POST["contactNumber"];
        $userAddress = $_POST["userAddress"];
        $reportingTo = $_POST["reportingTo"];
		
        $target_file = "";
        $target_url = "";
            $imageFileType = pathinfo($_FILES["profileImage"]["name"],PATHINFO_EXTENSION);
            $uId = uniqid();
            $target_file = $target_dir . $uId . "." . $imageFileType;
            $target_url = $urlPath .$uId. "." . $imageFileType;
            move_uploaded_file($_FILES["profileImage"]["tmp_name"], $target_file);
        
		$dbOptResp = $userCtrl->RegisterNewUser($email, $password, $userid,$fullName,$contactNumber, $userAddress,$target_url,$loginCtrl->GetUserName());
		if( $dbOptResp->OptStatus ){
            //Assign role to users
            $dbOptResp = $roleCtrl->AssignRoleToUser($dbOptResp->OptObj->Id, $role, $loginCtrl->GetUserName());
            if( $dbOptResp->OptStatus ){
                //Assign new organization relationship
                $dbOptResp = $userCtrl->NewOrgRel($dbOptResp->OptObj->Id,$reportingTo,$loginCtrl->GetUserName());
            }
        }
	}
    
    //update
    if (isset($_POST["update"])){
        $email = $_POST["email"];
		$password = $_POST["password"];
		$userid = $_POST["userid"];
		$role = $_POST["role"];
        $fullName = $_POST["fullName"];
        $contactNumber = $_POST["contactNumber"];
        $userAddress = $_POST["userAddress"];
        $reportingTo = $_POST["reportingTo"];
        $target_file = "";
        $target_url = "";
        if( isset($_FILES["profileImage"]) ){
            $imageFileType = pathinfo($_FILES["profileImage"]["name"],PATHINFO_EXTENSION);
            $uId = uniqid();
            $target_file = $target_dir . $uId . "." . $imageFileType;
            $target_url = $urlPath .$uId. "." . $imageFileType;
            move_uploaded_file($_FILES["profileImage"]["tmp_name"], $target_file);
        }
        
        
	    $dbOptResp = $userCtrl->UpdateUser($editingUser->User, $email, $password, $userid,$fullName,$contactNumber, $userAddress,$target_url, $loginCtrl->GetUserName());
		if( $dbOptResp->OptStatus ){
            $dbOptResp  = $roleCtrl->UpdateRole($editingUserRole, $role,$loginCtrl->GetUserName() );
		    if( $dbOptResp->OptStatus ){
                $dbOptResp = $userCtrl->UpdateOrgRel($editingUser,$reportingTo , $loginCtrl->GetUserName());
            }
	    }
    }
?>

<form method="post" enctype="multipart/form-data">
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
            <div class="col-sm-2"><label for="userid">User Profile</label></div>
            <div class="col-sm-5">
                <?php 
                    if( $isEditing ){
                        echo '<img src="'.GetFriendlyUrl($editingUser->User->ProfileImagePath).'" width="150" height="150" />';
                        echo '<input type="file" class="form-control" name="profileImage" id="profileImage" accept=".png,.gif,.jpg,.jpeg"/>';
                    }else{
                        echo '<input type="file" class="form-control" name="profileImage" id="profileImage" accept=".png,.gif,.jpg,.jpeg"/>';
                    }
                ?>
            </div>
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
                                echo '<input type="text" class="form-control" name="userid" id="userid" value="'.$customAttributeArray["userid"].'" />';
                            }else{
                                echo '<input type="text" class="form-control" name="userid" id="userid" value="" />';
                            }
                        }else{
                            echo '<input type="text" class="form-control" name="userid" id="userid" value="" />';
                        }
                    }else{
                        echo '<input type="text" class="form-control" name="userid" id="userid" value="" />';
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
                        echo '<input type="email" class="form-control" name="email" id="email" value="'.$editingUser->User->Email.'" />';
                    }else{
                        echo '<input type="email" class="form-control" name="email" id="email" value="" />';
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
                    }else{
                        echo '<input type="password" name="password" id="password" class="form-control" />';
                    }
                ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="fullName">Full Name</label></div>
            <div class="col-sm-5">
                <?php 
                    if( $isEditing ){
                        echo '<input type="text" name="fullName" id="fullName" class="form-control" value="'.$editingUser->User->FullName.'" />';
                    }else{
                        echo '<input type="text" name="fullName" id="fullName" class="form-control" />';
                    }
                ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="fullName">Contact Number</label></div>
            <div class="col-sm-5">
                <?php 
                    if( $isEditing ){
                        echo '<input type="text" name="contactNumber" id="contactNumber" class="form-control" value="'.$editingUser->User->ContactNumber.'" />';
                    }else{
                        echo '<input type="text" name="contactNumber" id="contactNumber" class="form-control" />';
                    }
                ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="fullName">Address</label></div>
            <div class="col-sm-5">
                <?php 
                    if( $isEditing ){
                        echo '<textarea id="contactNumber" name="userAddress" class="form-control">'.$editingUser->User->Address.'</textarea>';
                    }else{
                        echo '<textarea id="contactNumber" name="userAddress" class="form-control"></textarea>';
                    }
                ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 form-group">
            <div class="col-sm-2"><label for="role">Role</label></div>
            <div class="col-sm-5">
                <select name="role" id="role" class="form-control" required>
                    <option value="-1"> -- Please select -- </option>
                    <?php 
                        foreach($roleCtrl->GetRoles($GLOBALS["DEFAULT_PAGE_INDEX"], $GLOBALS["DEFAULT_MAX_PAGE_INDEX"]) as $role ){
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
                <select name="reportingTo" id="reportingTo" class="form-control" required>
                    <option value="-1"> -- Please select -- </option>
                    <?php 
                        foreach($userCtrl->GetUsers($GLOBALS["DEFAULT_PAGE_INDEX"], $GLOBALS["DEFAULT_MAX_PAGE_INDEX"]) as $user ){
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
                    }else{
                        echo '<button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit" onclick="return ValidateUser();">Apply</button>';
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
        var profileImage = $('#profileImage').val();
        var fullName = $('#fullName').val();
        var contactNumber = $('#contactNumber').val();
        var userAddress = $('#userAddress').val();
        var isValidated = false;
        
        userId = userId.trim();
        $('#userid').val(userId);
        email = email.trim();
        $('#email').val(email);
        
        <?php 
            if( !$isEditing ){
                ?>
                    if( profileImage == "" ){
                        ShowErrorMessage('Please select a valid profile image');
                        return isValidated;
                    }
                <?php
            }
        ?>
        
        
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
        
        if( fullName == "" ){
            ShowErrorMessage('Please insert a valid full name');
            return isValidated;
        }
        
        if( contactNumber == "" ){
            ShowErrorMessage('Please insert a valid contact number');
            return isValidated;
        }
        
        if( userAddress == "" ){
            ShowErrorMessage('Please insert a valid address');
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
        TriggerLoadingGif();
        return isValidated;
    }
    
    function ShowErrorMessage(errorMessage){
        $('#userErrorMessage').removeClass('hide');
        $('#userErrorMessage > span').html(errorMessage);
    }
</script>