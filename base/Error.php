<?php

	function DisableError(){
			error_reporting(0);
			ini_set('display_errors', 'Off');
		}
		
		function EnableError(){
			error_reporting(-1);
			ini_set('display_errors', 'On');
		}

?>