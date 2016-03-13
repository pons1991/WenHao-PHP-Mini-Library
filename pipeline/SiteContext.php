<?php 
    namespace pipeline;
    
    class SiteContext{
        public static function GetCurrentPage(){
            return basename($_SERVER['PHP_SELF']);;
        }
    }

?>