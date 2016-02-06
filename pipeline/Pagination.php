<?php
    function GetPageIndex($relativePath){
        $qsArray = GetQueryString();
        return $qsArray["page"];
    }
    
    function GetPageSize(){
        return $GLOBALS["PAGE_SIZE"];
    }
?>