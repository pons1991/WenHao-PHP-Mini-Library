<?php
	if (isset($_POST["submit"])){
		$email = $_POST["email"];
		$password = $_POST["password"];
		$userid = $_POST["userid"];
		$position = $_POST["position"];
		$accessRole = $_POST["accessrole"];
		
		if( !empty($email) && !empty($password) && !empty($userid) && !empty($position) && !empty($accessRole)){
			$registerCtrl = new RegisterController($dbConn);
			$dbOpt = $registerCtrl->RegisterNewUser($email, $password, $userid);
			
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
		}else{
			echo 'verify data ! please';
		}
		
	}
?>

<p>This is to edit user</p>
<form method="post" >
			<label for="email">Email</label>
			<input type="email" name="email" id="email" />
			<br/>
			<label for="password">Password</label>
			<input type="password" name="password" id="password" />
			<br/>
			<label for="userid">User Id</label>
			<input type="text" name="userid" id="userid" />
			<br/>
			<label for="accessrole">Access Role</label>
			<select name="accessrole" id="accessrole" required>
				<?php 
					
					foreach($accessCtrl->GetAccess() as $access ){
						echo '<option value="'.$access->Id.'">'.$access->RoleName.'</option>';	
					}
				?>
			</select>
			<br/>
			<label for="position">Position</label>
			<select name="position" id="position" required>
				<?php 
					foreach($roleCtrl->GetRoles() as $role ){
						echo '<option value="'.$role->Id.'">'.$role->RoleName.'</option>';	
					}
				?>
			</select>
			<br/>
			<input type="submit" name="submit" id="submit" value="Register" />
		</form>