<?php
	class DBModal{
		
		//Properties
		var $Id;
		var $IsActive;
		var $CreatedDate;
		var $CreatedBy;
		var $UpdatedDate;
		var $UpdatedBy;
		
		//To validate
		//Default validate every field except field Id
		//If there is a special requirement to validate certain field, extends override this method at the child class
		public function Validate($obj){
			$currentObjInst = new ReflectionClass($obj);
			$props  = $currentObjInst->getProperties();
			$isTrue = true;
			
			for ($i = 0 ; $i < count($props); $i++) {
				$prop = $props[$i];
				if( $prop->getName() == "Id" ){
					//skip
				}else{
					$propValue = $prop->getValue($obj);
					if( empty($propValue)){
						$isTrue = false;
						break;
					}
				}
			}
			
			return $isTrue;
		}
		
		//To delete
		public function Delete($dbConn, $obj){
			
		}
		
		//To update
		public function Update($dbConn, $obj){
			
		}
		
		//To add
		public function Add($dbConn, $obj){
			$dbOptResponse = new DbOpt;
			$dbOptResponse->OptStatus = true;
			$dbOptResponse->OptMessage = "Success";
			
			
			$isValidated = $this->Validate($obj);
			if( $isValidated ){
				if( $dbConn != null && $obj != null  ){
					$currentObjInst = new ReflectionClass($obj);
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
								$insertColumnSqlStr = $insertColumnSqlStr."'".$prop->getValue($obj)."'";
							}else{
								$insertColumnNameSqlStr = $insertColumnNameSqlStr.",".$prop->getName();
								$insertColumnSqlStr = $insertColumnSqlStr.",'".$prop->getValue($obj)."'";
							}
						}
					}
					$insertSqlStr = "Insert into ".$currentObjInst->getName()." (".$insertColumnNameSqlStr.") values(".$insertColumnSqlStr.")";
					
					if( !empty($insertSqlStr) && $dbConn->IsConnectionEstablished()){
						$dbConn->ExecuteQuery($insertSqlStr);
					}
				}
			}else{
				$dbOptResponse->OptStatus = false;
				$dbOptResponse->OptMessage = "The record has emtpy record";
			}

			return $dbOptResponse;
		}
		
		//To arrange
		public function Reorder(){
			
		}
		
		//Get single
		public function Get($dbConn, $objId){
			$currentObjInst = new ReflectionClass($this);
			$selectQuery = "select * from ".$currentObjInst->getName();
			echo $selectQuery;
			$result = $dbConn->ExecutePrepare($selectQuery);
			
			if ($result->num_rows > 0) {
				echo 'Number of record:'.$result->num_rows.'<br/>';
			}else{
				echo 'No record <br/>';
			}
		}
		
		//Get list
		public function Gets($dbConn, $start, $recordPerPage){
			$arrayObject = array();
			
			$currentObjInst = new ReflectionClass($this);
			$className = $currentObjInst->getName();
			
			$queryParamValue = array(
				':start' => array('value' => 0, 'type' => PDO::PARAM_INT),
				':end' => array('value' => 2, 'type' => PDO::PARAM_INT)
			);
			
			$selectQuery = "select * from ".$className." limit :start , :end";
			
			$result = $dbConn->ExecutePrepare($selectQuery,$queryParamValue);
			if( $result != null ){
				//Loop through the array of records
				foreach($result as $k=>$v){
					$tempObj = $this->Conversion($v);
					array_push($arrayObject, $tempObj);
				}
			}
			print_r($arrayObject);
			return $arrayObject;
		}
		
		//To convert raw db value into obj instance
		public function Conversion($pdoRecord){
			$currentObjInst = new ReflectionClass($this);
			$className = $currentObjInst->getName();
			$props  = $currentObjInst->getProperties();
			$tempObj = new $className;
			
			//Loop through record's column
			for ($i = 0 ; $i < count($props); $i++) {
				$prop = $props[$i];
				if( $prop != null ){
					$propName = $prop->getName();
					$propValue = $pdoRecord[$propName];
					//reflection set value to object
					$prop->setValue($tempObj, $propValue);
				}
			}
			
			return $tempObj;
		}
	}
?>