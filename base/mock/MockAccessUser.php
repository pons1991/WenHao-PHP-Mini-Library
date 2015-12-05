<?php

	//to add new access user
			$accessUser = new AccessUser;
			$accessUser->UserName = "wenhao";
			$accessUser->Password = "wenhao";
			$accessUser->CustomAttribute = "wenhao";
			$accessUser->IsActive = true;
			$accessUser->CreatedDate = date("Y-m-d H:i:s", time());
			$accessUser->CreatedBy = "wenhao";
			$accessUser->UpdatedDate = date("Y-m-d H:i:s", time());
			$accessUser->UpdatedBy = "wenhao";
			
			$dbOpt = $accessUser->Add($dbConn, $accessUser);

?>