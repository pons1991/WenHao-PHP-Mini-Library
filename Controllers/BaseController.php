<?php

    namespace Controllers;

    class BaseController{
        public function SetSession($obj, $key){
			if( $obj != null && !empty($key)){
				$_SESSION[$key] = $obj;
			}
		}
		
		public function CheckSession($key){
            if( !empty($key)){
                if(isset($_SESSION[$key]) && !empty($_SESSION[$key])) {
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
		}
		
		public function GetSession($key){
            if( !empty($key)){
                if($this->CheckSession($key)) {
                    $sessonObj = $_SESSION[$key];
                    return $sessonObj;
                }else{
                    return null;
                }
            }else{
                return null;
            }
		}
    }
?>