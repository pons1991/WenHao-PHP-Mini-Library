<?php

	function GetQueryString(){
		$output;
		$qs = $_SERVER['QUERY_STRING'];
		
		parse_str($qs, $output);
		
		return $output;
	}

?>