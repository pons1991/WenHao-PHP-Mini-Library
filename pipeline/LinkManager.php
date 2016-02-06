<?php
    function GetFriendlyUrl($relativePath){
        $hostName = $GLOBALS["DOMAIN_NAME"];
        
        //Further update to construct full url
        if( !empty($relativePath) && $relativePath[0] === '/' ){
            return $hostName . $relativePath;
        }else{
            return $hostName . $relativePath;
        }
    }
    
    function GetQueryString(){
		$output;
		$qs = $_SERVER['QUERY_STRING'];
		
		parse_str($qs, $output);
		
		return $output;
	}
    
    //minimum version of PHP5, PHP7
    function BuildQueryString($arr){
        return http_build_query($arr) . "\n";
    }
    
?>