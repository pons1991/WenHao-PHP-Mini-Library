<?php
    $hostName = "http://localhost:8888/whLibrary";
    function GetFriendlyUrl($relativePath){
        //Further update to construct full url
        if( !empty($relativePath) && $relativePath[0] === '/' ){
            return $hostName . $relativePath;
        }else{
            return $hostName . $relativePath;
        }
    }
?>