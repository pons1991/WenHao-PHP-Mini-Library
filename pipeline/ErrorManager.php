<?php 
    namespace pipeline;
    
    class ErrorManager{
        public function DisableError(){
			error_reporting(0);
			ini_set('display_errors', 'Off');
		}
		
		public function EnableError(){
			error_reporting(-1);
			ini_set('display_errors', 'On');
		}
    }
    
    
?>