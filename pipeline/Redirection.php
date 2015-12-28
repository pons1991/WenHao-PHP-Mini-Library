<?php

	function Redirection($url){
		echo '<script> location.replace("'.$GLOBALS["DOMAIN_NAME"].$url.'"); </script>';
	}

?>