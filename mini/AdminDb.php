<?php
	include "../Base.php";
	EnableError();
	
	$tableConfigFolder = "/config/tables/";
?>
<!doctype>
<html>
	<head></head>
	<body>
		<h1>これはMini Admin DB</h1>
		<?php
			$dbConnection = new Connection;
			$dbConnection->OpenConnection();
			
			if( $dbConnection->IsConnectionEstablished() ){
				echo "<p>Database Connection がいいですよ</p>";
				
				$allFiles =  scandir("../config/tables");
				
				//drop database always
				$dbConnection->ExecuteQuery("DROP DATABASE ".$GLOBALS["DATABASE_NAME"]);
				//create database after drop
				$dbConnection->ExecuteQuery("CREATE DATABASE ".$GLOBALS["DATABASE_NAME"]);
				//use database
				$dbConnection->ExecuteQuery("use ".$GLOBALS["DATABASE_NAME"]);
				
				foreach( $allFiles as $f ){
					$file_parts = pathinfo($f);
					if($f != '.' && $f != '..' && !empty($file_parts['extension']) && $file_parts['extension'] == "json"){
						$tableJsonString = file_get_contents($GLOBALS["DOMAIN_NAME"].$tableConfigFolder.$f);
						$tableJson = json_decode($tableJsonString, true);
						
						$tableName = $tableJson["TableName"];
						echo '<br/><br/>Table name: '.$tableName.'<br/>';
						
						$tablePropertiesJsonArray = $tableJson["Properties"];
						
						for($i = 0 ; $i < count($tablePropertiesJsonArray); $i++){
							$jsonAtom = $tablePropertiesJsonArray[$i];
							$jsonAtomAttribute = $jsonAtom["Attributes"]; //Column attribute array
							$jsonAtomConstraint = $jsonAtom["Constraints"]; //Column constraint array
							
							$sqlQueryColumnStr = $jsonAtom["Name"]." ".$jsonAtom["Type"]." ";
							
							for($j = 0 ; $j < count($jsonAtomAttribute); $j++){
								$sqlQueryColumnStr = $sqlQueryColumnStr." ".$jsonAtomAttribute[$j];
							}
							
							$sqlQueryStr = "";
							$sqlQueryConstraintStr = "";
							
							if( $i == 0 ){
								$sqlQueryStr = "create table ".$tableName."(".$sqlQueryColumnStr.")";
							}else{
								//alter table to add in column
								$sqlQueryStr = "alter table ".$tableName;
								$sqlQueryStr = $sqlQueryStr." add ".$sqlQueryColumnStr;
								echo $sqlQueryStr."<br/>";
							}
							
							$dbConnection->ExecuteQuery($sqlQueryStr);
							
							//Execute constraint if available :)
							if( $jsonAtomConstraint != null ){
								for( $j = 0 ; $j < count($jsonAtomConstraint); $j++){
									$sqlQueryConstraintStr = "alter table ".$tableName;
									$sqlQueryConstraintStr = $sqlQueryConstraintStr." add ".$jsonAtomConstraint[$j];
									$dbConnection->ExecuteQuery($sqlQueryConstraintStr);
								}
							}
						}
					}
				}
				
			}else{
				echo "<p>Database Connection が悪いですよ。<b>後で再試行よ！</b></p>";
			}
			
			$dbConnection->CloseConnection();
		?>
	</body>
</html>