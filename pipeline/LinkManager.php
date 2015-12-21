<?php
    function GetFriendlyUrl($relativePath){
        //Further update to construct full url
        if( $relativePath[0] === '/' ){
            return 'http://localhost:8888/whLibrary' . $relativePath;
        }else{
            return 'http://localhost:8888/whLibrary/' . $relativePath;
        }
    }
?>