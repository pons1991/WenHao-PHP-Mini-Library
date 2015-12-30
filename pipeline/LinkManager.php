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
?>