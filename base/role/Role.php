<?php
	class Role{
		var $Id;
		var $RoleName;
		var $IsActive;
		var $CreatedDate;
		var $CreatedBy;
		var $UpdatedDate;
		var $UpdatedBy;
		
		public static function Validate($roleObj){
			$isValid = false;
			if( $roleObj != null ){
				if( empty($roleObj->$RoleName) ){
					return $isValid;
				}
				
				$isValid = true;
			}
			return $isValid;
		}
		
		public static function Add($dbConn, $roleObj){
			if( $dbConn != null && $roleObj != null ){
				$currentObjInst = new ReflectionClass($roleObj);
				$props  = $currentObjInst->getProperties();
				$insertColumnNameSqlStr = "";
				$insertColumnSqlStr = "";
				for ($i = 0 ; $i < count($props); $i++) {
					$prop = $props[$i];
					if( $prop->getName() == "Id" ){
						//skip
					}else{
						if( empty($insertColumnSqlStr) ){
							$insertColumnNameSqlStr = $insertColumnNameSqlStr."".$prop->getName();
							$insertColumnSqlStr = $insertColumnSqlStr."'".$prop->getValue($roleObj)."'";
						}else{
							$insertColumnNameSqlStr = $insertColumnNameSqlStr.",".$prop->getName();
							$insertColumnSqlStr = $insertColumnSqlStr.",'".$prop->getValue($roleObj)."'";
						}
					}
				}
				$insertSqlStr = "Insert into ".$currentObjInst->getName()." (".$insertColumnNameSqlStr.") values(".$insertColumnSqlStr.")";
				//echo $insertSqlStr;
				if( !empty($insertSqlStr) && $dbConn->IsConnectionEstablished()){
					$dbConn->ExecuteQuery($insertSqlStr);
				}
			}
		}
	}
?>