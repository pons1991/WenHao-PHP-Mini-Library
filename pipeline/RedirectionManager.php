<?php 
    namespace pipeline;
    
    class RedirectionManager{
        static public function Redirection($url){
		  echo '<script> location.replace("'.$GLOBALS["DOMAIN_NAME"].$url.'"); </script>';
        }
        
        static public function RedirectExternal($url){
            echo '<script> location.replace("'.$url.'"); </script>';
        }
    }
?>