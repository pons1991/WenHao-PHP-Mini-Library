<?php 
    function GetCurrentPage(){
        return basename($_SERVER['PHP_SELF']);;
    }
?>