<?php
    function GetPageIndex(){
        $defaultPageIndex = 1;
        
        $qsArray = GetQueryString();
        if( array_key_exists("page", $qsArray)){
            $pageIndex = $qsArray["page"];
            if( is_numeric($pageIndex) ){
                if( $pageIndex >= 1){
                    return $pageIndex;
                }
            }
        }
        return $defaultPageIndex;
    }
    
    function GetPageSize(){
        return $GLOBALS["PAGE_SIZE"];
    }
?>