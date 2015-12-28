<?php

    echo '<p>'.print_r($userCtrl->GetOrgRel()).'</p>';
	$editingUser = false;
	$editingUserId = 0;
	$getUser = null;
	$getUserRole = null;
	$getAccessRole = null;
	
	if( isset($_GET["id"]) && $_GET["id"] !== '0' ){
		//edit user
		$editingUser = true;
		$editingUserId = $_GET["id"];
		
		$getUser = $userCtrl->GetUserById($editingUserId);	//get user by id
		if( count($getUser) == 1 ){
			$getUser = $getUser[0];
			
			$getUserRole = $roleCtrl->GetRoleByUserId($getUser->Id); //get user role by user id
			if( count($getUserRole) == 1 ){
				$getUserRole = $getUserRole[0];
			}
			
			$getAccessRole = $accessCtrl->GetAccessByUserId($getUser->Id); //get user access role by user id
			if( count($getAccessRole) == 1 ){
				$getAccessRole = $getAccessRole[0];
			}
		}else{
			//error message, user not found
		}
	}
	
	if (isset($_POST["submit"])){
		//create new user
		$email = $_POST["email"];
		$password = $_POST["password"];
		$userid = $_POST["userid"];
		$position = $_POST["position"];
		$accessRole = $_POST["accessrole"];
			
		if( !empty($email) && !empty($password) && !empty($userid) && !empty($position) && !empty($accessRole)){	
			if( $getUser != null && isset($_GET["id"]) && $_GET["id"] !== '0' ){
				//updating existing user
				$currentUser = $loginCtrl->GetUserName();
				$usrDbOpt = $userCtrl->UpdateUser($getUser, $email, $password, $userid, $currentUser);
				if( $usrDbOpt->OptStatus ){
					echo '<p>Success update user profile</p>';
				}else{
					echo '<p>'.$usrDbOpt->OptMessage.'</p>';
				}
				
				//updating role id
				$roleDbOpt  = $roleCtrl->UpdateRole($getUserRole, $position,$currentUser );
				if( $roleDbOpt->OptStatus ){
					echo '<p>Success update user role</p>';
				}else{
					echo '<p>'.$roleDbOpt->OptMessage.'</p>';
				}
				
				//updating access role id
				$accDbOpt = $accessCtrl->UpdateAccess($getAccessRole, $accessRole, $currentUser);
				if( $accDbOpt->OptStatus ){
					echo '<p>Success update user access</p>';
				}else{
					echo '<p>'.$accDbOpt->OptMessage.'</p>';
				}
				
				
			}else{
				$dbOpt = $userCtrl->RegisterNewUser($email, $password, $userid);
				
				if( $dbOpt->OptStatus ){
					//Assign role to users
					$dbOptRole = $roleCtrl->AssignRoleToUser($dbOpt->OptObj->Id, $position, $loginCtrl->GetUserName());
					if( $dbOptRole->OptStatus ){
						echo 'Success assign role <br/>';
					}else{
						echo $dbOptRole->OptMessage;
					}
					
					$dbOptAccessRole = $accessCtrl->AssignAccessRoleToUser($dbOpt->OptObj->Id, $accessRole, $loginCtrl->GetUserName());
					if( $dbOptRole->OptStatus ){
						echo 'Success assign access role';
					}else{
						echo $dbOptRole->OptMessage;
					}
					
				}else{
					echo $dbOpt->OptMessage;
				}
			}	
		}else{
			echo 'verify data ! please';
		}
	}
?>

<form method="post" >
			<label for="email">Email</label>
			<input type="email" name="email" id="email" 
				value=<?php echo ($getUser != null ? $getUser->Email : "")  ?>
			></input>
			<br/>
			<label for="password">Password</label>
			<input type="password" name="password" id="password"
				value=<?php echo ($getUser != null ? $getUser->Password : "")  ?>
			></input>
			<br/>
			<label for="userid">User Id</label>
			<input type="text" name="userid" id="userid"
				value=<?php
					if( $getUser != null && !empty($getUser->CustomAttribute) ){
						$jsonDecodedArray = json_decode($getUser->CustomAttribute, true);
						if( !empty($jsonDecodedArray["userid"]) ){
							echo $jsonDecodedArray["userid"];
						}else{
							echo "";
						}
					}else{
						echo "";
					}
				?>
			></input>
			<br/>
			<label for="accessrole">Access Role</label>
			<select name="accessrole" id="accessrole" required>
				<?php 
					foreach($accessCtrl->GetAccess() as $access ){
						if( $getUser != null && $getAccessRole != null && $access->Id == $getAccessRole->RoleId ){
							echo '<option selected value="'.$access->Id.'">'.$access->RoleName.'</option>';	
						}else{
							echo '<option value="'.$access->Id.'">'.$access->RoleName.'</option>';	
						}
					}
				?>
			</select>
			<br/>
			<label for="position">Position</label>
			<select name="position" id="position" required>
				<?php 
					foreach($roleCtrl->GetRoles() as $role ){
						if( $getUser != null && $getUserRole != null && $role->Id == $getUserRole->RoleId ){
							echo '<option selected value="'.$role->Id.'">'.$role->RoleName.'</option>';
						}else{
							echo '<option value="'.$role->Id.'">'.$role->RoleName.'</option>';
						}
						
					}
				?>
			</select>
			<br/>
            <?php 
                if( isset($_GET["id"]) && $_GET["id"] !== '0' ){
                    echo '<input type="submit" name="submit" id="submit" value="Update" />';
                }else{
                    echo '<input type="submit" name="submit" id="submit" value="Register" />';
                }
            ?>
			<a href="?action=list">Cancel</a>
		</form>