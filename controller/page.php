<?php
    class PageController extends BaseController{
        var $dbConnection;
        var $UserAccessPageSession = "USER_ACCESS_PAGE_SESSION";
        
		function __construct($conn) {
			if( $conn != null ){
				$this->dbConnection = $conn;
			}
		}
        
        public function GetPages(){
			$newPage = new Pages;
			return $newPage->Gets($this->dbConnection, 0, 999, null);
		}
        
        public function GetUserAccessPages(){
            $userPages = $this->GetSession($this->UserAccessPageSession);
            if( $userPages == null ){
                $userPages = $this->GetPages();
                $this->SetUserAccessPages($userPages);
            }
            
            return $userPages;
        }
        
        public function SetUserAccessPages($userPages){
			if( $userPages != null ){
				$this->SetSession($userPages,$this->UserAccessPageSession);
			}
		}
    }
?>