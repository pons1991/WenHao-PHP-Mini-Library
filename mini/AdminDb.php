<?php
	include "../base/Base.php";
	EnableError();
	
	$tableConfigFolder = "/config/tables/";
	$tableConfigPath = "/config/tables/Role.json";
?>
<!doctype>
<html>
	<head></head>
	<body>
		<h1>これはMini Admin DB</h1>
		<?php
			
			$allFiles =  scandir("../config/tables");
			foreach( $allFiles as $f ){
				$file_parts = pathinfo($f);
				if($f != '.' && $f != '..' && !empty($file_parts['extension']) && $file_parts['extension'] == "json"){
					$tableJsonString = file_get_contents($GLOBALS["DOMAIN_NAME"].$tableConfigFolder.$f);
					$tableJson = json_decode($tableJsonString, true);
					
					echo '<br/><br/>Table name: '.$tableJson["TableName"].'<br/>';
					
					$tablePropertiesJsonArray = $tableJson["Properties"];
					for($i = 0 ; $i < count($tablePropertiesJsonArray); $i++){
						$jsonAtom = $tablePropertiesJsonArray[$i];
						$jsonAtomAttribute = $jsonAtom["Attributes"];
						echo $i.' ';
						echo $jsonAtom["Name"].' ';
						echo $jsonAtom["Type"].' ';
						for($j = 0 ; $j < count($jsonAtomAttribute); $j++){
							echo $jsonAtomAttribute[$j].' ';
						}
						echo '<br/>';
					}	
				}
			}
			
			$dbConnection = new Connection;
			$dbConnection->OpenConnection();
			if( $dbConnection->IsConnectionEstablished() ){
				echo "<p>Database Connection がいいですよ</p>";
				
				
			}else{
				echo "<p>Database Connection が悪いですよ。<b>後で再試行よ！</b></p>";
			}
			$dbConnection->CloseConnection();
		?>
	</body>
</html>