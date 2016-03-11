<?php 
    namespace pipeline;
    
    class QueryStringManager{
        public function GetActionQueryString(){
            $action = '';
            $qsArray = GetQueryString();
            
            if( array_key_exists("action", $qsArray) ){
                $action = strtolower($qsArray["action"]);
            }
            
            return $action;
        }
        
        
        public function GetQueryString(){
            $output;
            $qs = $_SERVER['QUERY_STRING'];
            
            parse_str($qs, $output);
            
            return $output;
        }
    }
    
    
?>