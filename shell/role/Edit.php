<?php
	if (isset($_POST["submit"])){
		$rolename = $_POST["rolename"];
		$roleleave = $_POST["roleleave"];
		
		if( !empty($rolename) && !empty($roleleave)){
			
			
			$roleCtrl = new RoleController($dbConn);
			$dbOpt = $roleCtrl->NewRole($rolename ,$loginCtrl->GetUserName());
			if( $dbOpt->OptStatus ){
				$roleleaveCtrl = new RoleLeaveController($dbConn);
				$dbOptRoleLeave = $roleleaveCtrl->AddNewLeave($dbOpt->OptObj->Id,$roleleave, $loginCtrl->GetUserName() );
				if( $dbOptRoleLeave->OptStatus ){
					echo 'success';
				}else{
					echo $dbOptRoleLeave->OptMessage;
				}
			}
		}
	}
?>

<p>This role edit page</p>
<form method="post">
	<label>Role Name</label>
	<input type="text" name="rolename" id="rolename" required />
	<br/>
	<label>Leave number</label>
	<input type="number" min="1" max="365" name="roleleave" id="roleleave" required />
	<br/>
	<input type="submit" name="submit" id="submit" />
</form>