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
                    if (strpos($prop->getName(),'_IGNORE') !== false) {
                        //skip if contain _IGNORE custom attribute
                    }else{
                        $propValue = $prop->getValue($obj);
                        if( empty($propValue)){
                            $isTrue = false;
                            break;
                        }
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
			$dbOptResponse = new DbOpt;
			$dbOptResponse->OptStatus = true;
			$dbOptResponse->OptMessage = "Success";
			
			$isValidated = $this->Validate($obj);
			if( $isValidated ){
				if( $dbConn != null && $obj != null  ){
					$currentObjInst = new ReflectionClass($obj);
					$props  = $currentObjInst->getProperties();
					$updateSqlStr = "update ".$currentObjInst->getName()." set ";
					$updateSqlFieldStr = "";
					
					for ($i = 0 ; $i < count($props); $i++) {
						$prop = $props[$i];
						if( $prop->getName() == "Id" ){
							//skip
						}else{
                            if (strpos($prop->getName(),'_IGNORE') !== false) {
                                //skip if contain _IGNORE custom attribute
                            }else{
                                if( !empty($updateSqlFieldStr)){
                                    $updateSqlFieldStr = $updateSqlFieldStr.",";
                                }
                                $updateSqlFieldStr = $updateSqlFieldStr." ".$prop->getName()."='".$prop->getValue($obj)."'";
                            }
						}
					}
					
					//Combine update query with field query
					$updateSqlStr = $updateSqlStr.$updateSqlFieldStr." where Id=".$obj->Id;
					
					if( $dbConn->IsConnectionEstablished()){
						$dbConn->ExecuteQuery($updateSqlStr);
					}
					
				}
			}else{
				$dbOptResponse->OptStatus = false;
				$dbOptResponse->OptMessage = "The record has emtpy record";
			}
			
			return $dbOptResponse;
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
                            if (strpos($prop->getName(),'_IGNORE') !== false) {
                                //skip if contain _IGNORE custom attribute
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
					}
					$insertSqlStr = "Insert into ".$currentObjInst->getName()." (".$insertColumnNameSqlStr.") values(".$insertColumnSqlStr.")";
					
					if( !empty($insertSqlStr) && $dbConn->IsConnectionEstablished()){
						$dbConn->ExecuteQuery($insertSqlStr);
						$obj->Id = $dbConn->GetLastInsertedId();
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
		
        //Get with custom query string
        public function GetByQuery($dbConn, $startPage, $recordPerPage, $additionalParams){
            
            $queryParamValue = array(
				':start' => array('value' => ($startPage * $recordPerPage), 'type' => PDO::PARAM_INT),
				':end' => array('value' => ($recordPerPage ), 'type' => PDO::PARAM_INT)
			);
            
            $currentObjInst = new ReflectionClass($this);
			$className = $currentObjInst->getName();
            $props  = $currentObjInst->getProperties();
            
            $referenceByList = array();
            $referenceByListMeta = array();
            $joinStatement = "select ";
            $selectColumn = "";
            $fromStatement = "";
            $whereStatement = "";
            $asciiIndex = 97;
            
            $queryMeta = array();
            
            //construct default class query string 
            for ($i = 0 ; $i < count($props); $i++) {
				$prop = $props[$i];
				if( $prop != null ){
					$propName = $prop->getName();
                    if (strpos($propName,'_META') !== false) {
                        $explodeToken = explode("_",$propName);
                        $jsonString = $prop->getValue($this);
                        $jsonMeta = json_decode($jsonString, true);
                        array_push($referenceByList, $explodeToken[0]);
                        $referenceByListMeta[$explodeToken[0]] = $jsonMeta;
                    }else{
                        //check the prop name exists in reference by list?
                        //direct process if not exists 
                        if( !in_array($propName, $referenceByList) ){
                            if(!empty($selectColumn)){
                                $selectColumn .= ",";
                            }
                            $selectColumn .= chr($asciiIndex).".".$propName." as ".chr($asciiIndex)."_".$propName;
                        }else {
                            //if meta exists but not reference class
                            //process if not reference class
                            $jsonMeta = $referenceByListMeta[$propName];
                            if( !array_key_exists("ReferenceBy",$jsonMeta ) ){
                                if(!empty($selectColumn)){
                                    $selectColumn .= ",";
                                }
                                $selectColumn .= chr($asciiIndex).".".$propName." as ".chr($asciiIndex)."_".$propName;
                            }
                        }
                    }
				}
			}
            
            $queryMeta[$className] = chr($asciiIndex);
            $fromStatement .= " from ".$className." as ".chr($asciiIndex);
            
            //construct reference class query string 
            for($i = 0 ; $i < count($referenceByList); $i++){
                $asciiIndex = $asciiIndex + 1;
                
                $referenceClassName = $referenceByList[$i];
                $referenceByName = $referenceByListMeta[$referenceClassName]["ReferenceBy"];
                
                $referenceReflectionClass = new ReflectionClass($referenceClassName);
                $referenceProps  = $referenceReflectionClass->getProperties();
                $referenceClassReferenceByList = array();
                $referenceClassReferenceByListMeta = array();
                
                for ($j = 0 ; $j < count($referenceProps); $j++) {
                    $prop = $referenceProps[$j];
                    if( $prop != null ){
                        $propName = $prop->getName();
                        if (strpos($propName,'_META') !== false) {
                            $explodeToken = explode("_",$propName);
                            $jsonString = $prop->getValue($this);
                            $jsonMeta = json_decode($jsonString, true);
                            array_push($referenceClassReferenceByList, $explodeToken[0]);
                            $referenceClassReferenceByListMeta[$explodeToken[0]] = $jsonMeta;
                        }else{
                            //check the prop name exists in reference by list?
                            //direct process if not exists 
                            if( !in_array($propName, $referenceClassReferenceByList) ){
                                if(!empty($selectColumn)){
                                    $selectColumn .= ",";
                                }
                                $selectColumn .= chr($asciiIndex).".".$propName." as ".chr($asciiIndex)."_".$propName;
                            }else {
                                //if meta exists but not reference class
                                //process if not reference class
                                $jsonMeta = $referenceClassReferenceByListMeta[$propName];
                                if( !array_key_exists("ReferenceBy",$jsonMeta ) ){
                                    if(!empty($selectColumn)){
                                        $selectColumn .= ",";
                                    }
                                    $selectColumn .= chr($asciiIndex).".".$propName." as ".chr($asciiIndex)."_".$propName;
                                }
                            }
                        }
                    }
                }
                $queryMeta[$referenceClassName] = chr($asciiIndex);
                $fromStatement .= " inner join ".$referenceClassName." as ".chr($asciiIndex)." on a.".$referenceByName." = ".chr($asciiIndex).".Id";
            }
            
            $joinStatement .= $selectColumn." ".$fromStatement;
            
            //construct where statement 
            if( $additionalParams != null ){
				$additionalParamIndex = 0;
				foreach($additionalParams as $valueArr ){
                    $tableKey = $valueArr["table"];
                    $asciiLabel = $queryMeta[$tableKey];
                    
                    $columnName = $valueArr["column"];
                    $condition = $valueArr["condition"]; //where statement condition (and/or) (further enhance by not)
                    $dynamicParamKey = ":".$asciiLabel."_".$columnName;
                    $queryParamValue[$dynamicParamKey] = $valueArr;
                    
					if( !empty($whereStatement)){
						$whereStatement .= " ".$condition;
					}
                    $whereStatement .= " ".$columnName."=".$dynamicParamKey; //update select query
					$additionalParamIndex++;
				}
                
                if( !empty($whereStatement)){
                    $joinStatement .= " where ".$whereStatement; //joining where statement
                }
			}
            
            $joinStatement .= " limit :start , :end"; //limit constraint
            echo print_r($joinStatement);
            $result = $dbConn->ExecuteSelectPrepare($joinStatement,$queryParamValue);
            return $result;
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
                    if (strpos($propName,'_IGNORE') !== false) {
                        //skip if contain _IGNORE custom attribute
                    }else{
                        $propValue = $pdoRecord[$propName];
                        //reflection set value to object
                        $prop->setValue($tempObj, $propValue);
                    }
				}
			}
			
			return $tempObj;
		}
	}
?>