<?php
	class DBModal{
		
		//Properties
        var $Id_META = '{"PrimaryKey":"true"}';
		var $Id;
        
		var $IsActive;
		var $CreatedDate;
		var $CreatedBy;
		var $UpdatedDate;
		var $UpdatedBy;
		
		//To validate
		//Default validate every field except field Id
		//If there is a special requirement to validate certain field, extends override this method at the child class
		public function Validate($obj, &$metaValue){
			$currentObjInst = new ReflectionClass($obj);
			$props  = $currentObjInst->getProperties();
			$isTrue = true;
            
			for ($i = 0 ; $i < count($props); $i++) {
				$prop = $props[$i];
                $propName = $prop->getName();
                if (strpos($propName,'_META') !== false) {
                    $explodeToken = explode("_", $propName);
                    $jsonStr = $prop->getValue($this);
                    $jsonMeta = json_decode($jsonStr, true);
                    $metaValue[$explodeToken[0]] = $jsonMeta;
                }else{
                    //if not exists, then process as normal
                    if( !array_key_exists($propName,$metaValue ) ){
                        $propValue = $prop->getValue($obj);
                        if( empty($propValue) && !isset($propValue)){
                            $isTrue = false;
                            break;
                        }
                    }else{
                        $jsonMeta = $metaValue[$propName];
                        if( array_key_exists("ReferenceBy",$jsonMeta) ){
                            //skip
                        }else if(array_key_exists("PrimaryKey",$jsonMeta) ){
                            //skip
                        }else{
                            $propValue = $prop->getValue($obj);
                            if( empty($propValue) && !isset($propValue) ){
                                $isTrue = false;
                                break;
                            }
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
			$metaValue = array();
            
			$isValidated = $this->Validate($obj,$metaValue);
			if( $isValidated ){
				if( $dbConn != null && $obj != null  ){
					$currentObjInst = new ReflectionClass($obj);
					$props  = $currentObjInst->getProperties();
					$updateSqlStr = "update ".$currentObjInst->getName()." set ";
					$updateSqlFieldStr = "";
					
					for ($i = 0 ; $i < count($props); $i++) {
						$prop = $props[$i];
                        
                        $propName = $prop->getName();
                        if (strpos($propName,'_META') !== false) {
                            //skip if contain _META custom attribute
                        }else{
                            if( !array_key_exists($propName,$metaValue) ){
                                if( !empty($updateSqlFieldStr)){
                                    $updateSqlFieldStr = $updateSqlFieldStr.",";
                                }
                                $updateSqlFieldStr = $updateSqlFieldStr." ".$prop->getName()."='".$prop->getValue($obj)."'";
                            }else{
                                $jsonMeta = $metaValue[$propName];
                                if( array_key_exists("ReferenceBy",$jsonMeta) ){
                                    //skip
                                }else if(array_key_exists("PrimaryKey",$jsonMeta) ){
                                    //skip
                                }else{
                                    if( !empty($updateSqlFieldStr)){
                                        $updateSqlFieldStr = $updateSqlFieldStr.",";
                                    }
                                    $updateSqlFieldStr = $updateSqlFieldStr." ".$prop->getName()."='".$prop->getValue($obj)."'";
                                }
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
		
		//Add function will only add current object back to database
        //Except reference class, need to manually trigger one by one
		public function Add($dbConn, $obj){
			$dbOptResponse = new DbOpt;
			$dbOptResponse->OptStatus = true;
			$dbOptResponse->OptMessage = "Success";
            $metaValue = array();
            
            //Upon validate, reuse the generated meta value from validate function - to save some processing time
			$isValidated = $this->Validate($obj,$metaValue);
			if( $isValidated ){
				if( $dbConn != null && $obj != null  ){
					$currentObjInst = new ReflectionClass($obj);
					$props  = $currentObjInst->getProperties();
					$insertColumnNameSqlStr = "";
					$insertColumnSqlStr = "";
					for ($i = 0 ; $i < count($props); $i++) {
						$prop = $props[$i];
                        $propName = $prop->getName();
                        if (strpos($propName,'_META') !== false) {
                            //skip if contain _META custom attribute
                        }else{
                            if( !array_key_exists($propName,$metaValue) ){
                                if( empty($insertColumnSqlStr) ){
                                    $insertColumnNameSqlStr = $insertColumnNameSqlStr."".$prop->getName();
                                    $insertColumnSqlStr = $insertColumnSqlStr."'".$prop->getValue($obj)."'";
                                }else{
                                    $insertColumnNameSqlStr = $insertColumnNameSqlStr.",".$prop->getName();
                                    $insertColumnSqlStr = $insertColumnSqlStr.",'".$prop->getValue($obj)."'";
                                } 
                            }else{
                                $jsonMeta = $metaValue[$propName];
                                if( array_key_exists("ReferenceBy",$jsonMeta) ){
                                    //skip
                                }else if(array_key_exists("PrimaryKey",$jsonMeta) ){
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

        public function ConstructReferenceQueryString($asciiIndex,$referenceClassName,$referenceByName){
            $referenceReflectionClass = new ReflectionClass($referenceClassName);
                $referenceProps  = $referenceReflectionClass->getProperties();
                $referenceClassReferenceByList = array();
                $referenceClassReferenceByListMeta = array();
                $selectColumn = '';
                
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
            return $selectColumn;
        }
        
        //We can ignore $queryMeta (variable_table) in where statement
        //Because we are filtering the column in the parent table, so it would not affect the reference table
        public function ConstructWhere($additionalParams,$queryMeta,&$queryParamValue){
            $whereStatement = "";
            $previousColumnName = "";
            $additionalParamIndex = 0;
				foreach($additionalParams as $valueArr ){
                    $tableKey = $valueArr["table"];
                    $asciiLabel = $queryMeta[$tableKey];
                    
                    $columnName = $valueArr["column"];
                    $condition = $valueArr["condition"]; //where statement condition (and/or) (further enhance by not)
                    $dynamicParamKey = ":".$asciiLabel."_".$columnName."_".$additionalParamIndex; //to ensure each dynamic key is uniquely generated
                    $queryParamValue[$dynamicParamKey] = $valueArr;
                    
					if( !empty($whereStatement)){
                        if( ($asciiLabel.".".$columnName) != $previousColumnName){
                            $whereStatement .= " ) ".$condition;
                        }else{
                            $whereStatement .= " ".$condition;
                        }
					}
                    
                    $operator = '=';
                    if( array_key_exists("operator",$valueArr ) ){
                        $operator = $valueArr["operator"];
                    }
                    
                    if( ($asciiLabel.".".$columnName) != $previousColumnName){
                        $whereStatement .= " ( ".$asciiLabel.".".$columnName." ".$operator." ".$dynamicParamKey; //update select query
                    }else{
                        $whereStatement .= " ".$asciiLabel.".".$columnName." ".$operator." ".$dynamicParamKey; //update select query
                    }
                    
                    $previousColumnName = $asciiLabel.".".$columnName;
                    
					$additionalParamIndex++;
				}
                
                $whereStatement .= " )";
                
            return $whereStatement;
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
            $arrayObject = array();
            
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
                if( array_key_exists("ReferenceBy",$referenceByListMeta[$referenceClassName] ) ){
                    
                    //reference table is used to hold the meta reference table
                    //in fact, when store into $queryMeta, use the original variable name and table name (original variable name _ table name)
                    //this is to avoid further code changes :)
                    $referenceTable = $referenceClassName;
                    if( array_key_exists("table",$referenceByListMeta[$referenceClassName] ) ){
                        $referenceTable = $referenceByListMeta[$referenceClassName]["table"];
                        $queryMeta[$referenceClassName.'_'.$referenceTable] = chr($asciiIndex);
                    }else{
                        $queryMeta[$referenceTable] = chr($asciiIndex);
                    }
                    
                    $referenceByName = $referenceByListMeta[$referenceClassName]["ReferenceBy"];
                    $tempSelectStr = $this->ConstructReferenceQueryString($asciiIndex,$referenceTable,$referenceByName);
                    $selectColumn = empty($selectColumn) ? ($tempSelectStr) : ($selectColumn.",".$tempSelectStr);
                    
                    //Variable name can different from table name
                    $fromStatement .= " inner join ".$referenceTable." as ".chr($asciiIndex)." on a.".$referenceByName." = ".chr($asciiIndex).".Id";
                }else{
                    $asciiIndex = $asciiIndex - 1;
                }
            }

            $joinStatement .= $selectColumn." ".$fromStatement;
            
            //construct where statement 
            if( $additionalParams != null ){
                $whereStatement = $this->ConstructWhere($additionalParams,$queryMeta,$queryParamValue);
                if( !empty($whereStatement)){
                    $joinStatement .= " where ".$whereStatement; //joining where statement
                }
			}
            
            $joinStatement .= " order by ".$queryMeta[$className].".Id desc ";
            $joinStatement .= " limit :start , :end"; //limit constraint
            
            $result = $dbConn->ExecuteSelectPrepare($joinStatement,$queryParamValue);
            
            if( $result != null ){
				//Loop through the array of records
				foreach($result as $k=>$v){
                    
                    //$referenceByListMeta stores all the meta value
                    //$queryMeta stores all the ascii index
					$tempObj = $this->Conversion($v,$referenceByList,$referenceByListMeta,$queryMeta);
					array_push($arrayObject, $tempObj);
				}
			}
            
            return $arrayObject;
		}
		
		//To convert raw db value into obj instance
		public function Conversion($pdoRecord, $metaList, $metaValue, $queryMeta){
			$currentObjInst = new ReflectionClass($this);
			$className = $currentObjInst->getName();
			$props  = $currentObjInst->getProperties();
			$tempObj = new $className;
			
			//Loop through record's column for base table (primary table)
			for ($i = 0 ; $i < count($props); $i++) {
				$prop = $props[$i];
				if( $prop != null ){
					$propName = $prop->getName();
                    if (strpos($propName,'_META') !== false) {
                        //Skip the process of registering into array
                    }else{
                        if( in_array($propName,$metaList ) ){
                            if( !array_key_exists("ReferenceBy",$metaValue[$propName] ) ){
                                $pdoTitle = $queryMeta[$className]."_".$propName;
                                $propValue = $pdoRecord[$pdoTitle];
                                //reflection set value to object
                                $prop->setValue($tempObj, $propValue);
                            }else{
                                
                                if( !array_key_exists("table",$metaValue[$propName] ) ){
                                    $tempReflectionObj = $this->ReferenceConversion($pdoRecord,$propName,$queryMeta, '');
                                    //reference object assignment to the base object
                                    $prop->setValue($tempObj, $tempReflectionObj);
                                }else{
                                    $tempPropName = $metaValue[$propName]["table"];
                                    $tempReflectionObj = $this->ReferenceConversion($pdoRecord,$tempPropName,$queryMeta,$propName.'_'.$tempPropName );
                                    //reference object assignment to the base object
                                    $prop->setValue($tempObj, $tempReflectionObj);
                                }
                            }
                        }else{
                            $pdoTitle = $queryMeta[$className]."_".$propName;
                            $propValue = $pdoRecord[$pdoTitle];
                            //reflection set value to object
                            $prop->setValue($tempObj, $propValue);
                        }
                    }
				}
			}
			return $tempObj;
		}
        
        public function ReferenceConversion($pdoRecord,$propName,$queryMeta, $queryMetaUniqueKey){
            $referenceReflectionClass = new ReflectionClass($propName);
            $referenceReflectionClassProps  = $referenceReflectionClass->getProperties();
            $referenceReflectionClassName = $referenceReflectionClass->getName();
            
            //query meta unique key will hold variable_table name
            $queryMetaUniqueKey = empty($queryMetaUniqueKey) ? $referenceReflectionClassName : $queryMetaUniqueKey;
                            
            $tempReflectionObj = new $referenceReflectionClassName;
            $tempMetaList = array();
            $tempMetaValue = array();
            for ($i = 0 ; $i < count($referenceReflectionClassProps); $i++) {
                $referenceReflectionClassProp = $referenceReflectionClassProps[$i];
                if( $referenceReflectionClassProp != null ){
                    $referenceReflectionClassPropName = $referenceReflectionClassProp->getName();
                    if (strpos($referenceReflectionClassPropName,'_META') !== false) {
                        $explodeToken = explode("_",$referenceReflectionClassPropName);
                        array_push($tempMetaList,$explodeToken[0]);
                                        
                        $jsonString = $referenceReflectionClassProp->getValue($this);
                        $jsonMeta = json_decode($jsonString, true);
                        $tempMetaValue[$explodeToken[0]] = $jsonMeta;
                    }else{
                        if( in_array($referenceReflectionClassPropName,$tempMetaList) ){
                            $jsonMeta = $tempMetaValue[$referenceReflectionClassPropName];
                            if( !array_key_exists("ReferenceBy",$jsonMeta ) ){
                                $pdoTitle = $queryMeta[$queryMetaUniqueKey]."_".$referenceReflectionClassPropName;
                                $propValue = $pdoRecord[$pdoTitle];
                                //reflection set value to object
                                $referenceReflectionClassProp->setValue($tempReflectionObj, $propValue);
                            }else{
                                //if exists then ignore - since the code only can support 1 level deep of reference
                            }
                      }else{
                        $pdoTitle = $queryMeta[$queryMetaUniqueKey]."_".$referenceReflectionClassPropName;
                        $propValue = $pdoRecord[$pdoTitle];
                        //reflection set value to object
                        $referenceReflectionClassProp->setValue($tempReflectionObj, $propValue);
                      }
                    }
                }
            }
            return $tempReflectionObj;
        }
	}
?>