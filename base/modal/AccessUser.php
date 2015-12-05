<?php

	class AccessUser extends DBModal{
		var $UserName;
		var $Password;
		var $CustomAttribute;
		
		public function IsUserVerified($dbConn, $obj){
			$additionalParams = array(
				':UserName' => array('value' => $obj->UserName, 'type' => PDO::PARAM_STR, 'condition' => 'and'),
				':Password' => array('value' => $obj->Password, 'type' => PDO::PARAM_STR, 'condition' => 'and')
			);
			
			$returnUser = $this->Gets($dbConn,0, 1, $additionalParams);
			return $returnUser;
		}
	}

?>