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
		
		//To check constraint is take place
		//Default: return true
		//If contraint checking is required, extends child class by overriding this method
		public function ConstraintValid(){
			return true;
		}
		
		//To delete
		public function Delete($dbConn, $obj){
			
			$dbOptResponse = new DbOpt;
			$dbOptResponse->OptStatus = true;
			$dbOptResponse->OptMessage = "Success";
			
			if( $this->ConstraintValid()){
				try{
					$currentObjInst = new ReflectionClass($obj);
					$className = $currentObjInst->getName();
					$queryParamValue = array(
						':IsActive' => array('value' => $obj->IsActive, 'type' => PDO::PARAM_BOOL),
						':Id' => array('value' => $obj->Id, 'type' => PDO::PARAM_INT)
					);
					$updateSql = "Update ".$className." set IsActive=:IsActive where Id=:Id";
					
					$dbConn->ExecutePrepare($updateSql,$queryParamValue);
				}catch(Exception $e){
					$dbOptResponse->OptStatus = false;
					$dbOptResponse->OptMessage = "Error when deleting. Message:".$e->getMessage();
				}
			}else{
				$dbOptResponse->OptStatus = false;
				$dbOptResponse->OptMessage = "Error: Can't delete this item due to constraint takes place.";
			}
			
			
			return $dbOptResponse;
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
			$additionalParams = array(
				':Id' => array('value' => $objId, 'type' => PDO::PARAM_INT, 'condition' => 'and'),
			);
			return $this->Gets($dbConn,0, 1, $additionalParams);
		}
		
		//Get list
		public function Gets($dbConn, $startPage, $recordPerPage, $additionalParams){
			$arrayObject = array();
			
			$currentObjInst = new ReflectionClass($this);
			$className = $currentObjInst->getName();
			
			$queryParamValue = array(
				':start' => array('value' => ($startPage * $recordPerPage), 'type' => PDO::PARAM_INT),
				':end' => array('value' => ($recordPerPage ), 'type' => PDO::PARAM_INT)
			);
			
			$selectQuery = "select * from ".$className; //select statement
			//Select query's where constraint.
			if( $additionalParams != null ){
				$additionalParamIndex = 0;
				foreach($additionalParams as $key => $valueArr ){
					$queryParamValue[$key] = $valueArr; //Add additional params into query param value
					$columnKey = str_replace(":","",$key); //Get column key from key by removing ':'
					
					if( $additionalParamIndex == 0 ){
						$selectQuery = $selectQuery." where ".$columnKey."=".$key; //update select query
					}else{
						$condition = $valueArr["condition"]; //where statement condition (and/or)
						$selectQuery = $selectQuery." ".$condition." ".$columnKey."=".$key; //update select query
					}
					
					$additionalParamIndex++;
				}
			}
			$selectQuery = $selectQuery." limit :start , :end"; //limit constraint
			
			$result = $dbConn->ExecuteSelectPrepare($selectQuery,$queryParamValue);
			if( $result != null ){
				//Loop through the array of records
				foreach($result as $k=>$v){
					$tempObj = $this->Conversion($v);
					array_push($arrayObject, $tempObj);
				}
			}
			
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