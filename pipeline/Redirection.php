<?php

	function Redirection($url){
		echo '<script> location.replace("'.$GLOBALS["DOMAIN_NAME"].$url.'"); </script>';
	}
    
    function RedirectExternal($url){
        echo '<script> location.replace("'.$url.'"); </script>';
    }

?>