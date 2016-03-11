<?php
    namespace Models\Database;
    use PDO;
    
	class AccessUser extends DBModal{
		var $Email;
		var $Password;
        var $FullName;
        var $ContactNumber;
        var $Address;
        var $ProfileImagePath;
		var $CustomAttribute;
		
		public function IsUserVerified($dbConn, $obj){
            $additionalParams = array(
                array('table' => 'AccessUser', 'column' => 'Email', 'value' => $obj->Email, 'type' => PDO::PARAM_STR, 'condition' => 'and'),
                array('table' => 'AccessUser', 'column' => 'Password', 'value' => $obj->Password, 'type' => PDO::PARAM_STR, 'condition' => 'and')
		    );
            
			$returnUser = $this->Gets($dbConn,0, 1, $additionalParams);
			return $returnUser;
		}
		
		public function FindUserByEmail($dbConn, $obj){
            $additionalParams = array(
                array('table' => 'AccessUser', 'column' => 'Email', 'value' => $obj->Email, 'type' => PDO::PARAM_STR, 'condition' => 'and')
		    );
            
			// $additionalParams = array(
			// 	':Email' => array('value' => $obj->Email, 'type' => PDO::PARAM_STR, 'condition' => 'and')
			// );
			
			$returnUser = $this->Gets($dbConn,0, 1, $additionalParams);
			return $returnUser;
		}
	}

?>