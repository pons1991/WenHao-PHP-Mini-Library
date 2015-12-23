<?php
    function GetFriendlyUrl($relativePath){
        $hostName = "http://localhost:8888/whLibrary";
        
        //Further update to construct full url
        if( !empty($relativePath) && $relativePath[0] === '/' ){
            return $hostName . $relativePath;
        }else{
            return $hostName . $relativePath;
        }
    }
?>