<?php
	if (isset($_POST["submit"])){
		$accessName = $_POST["accessname"];
		$isAdmin = false;
		if(isset($_POST["isadmin"])){
			$isAdmin = true;
		} 
		
		if( !empty($accessName)){
			$dbOpt = $accessCtrl->AddNewAccess($accessName, $isAdmin ,$loginCtrl->GetUserName());
			if( $dbOpt->OptStatus ){
				echo 'success';
			}else{
				echo $dbOpt->OptMessage;
			}
		}
	}
?>

<p>This role edit page</p>
<form method="post">
	<label>Access Name</label>
	<input type="text" name="accessname" id="accessname" required />
	<br/>
	<label>Is Admin</label>
	<input type="checkbox" name="isadmin" id="isadmin" />
	<br/>
	<input type="submit" name="submit" id="submit" />
</form>