<?php 
    namespace pipeline;
    
    class QueryStringManager{
        public static function GetActionQueryString(){
            $action = '';
            $qsArray = QueryStringManager::GetQueryString();
            
            if( array_key_exists("action", $qsArray) ){
                $action = strtolower($qsArray["action"]);
            }
            
            return $action;
        }
        
        
        public static function GetQueryString(){
            $output;
            $qs = $_SERVER['QUERY_STRING'];
            
            parse_str($qs, $output);
            
            return $output;
        }
    }
    
    
?>