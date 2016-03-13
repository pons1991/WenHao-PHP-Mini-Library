<?php

    namespace Controllers;
    use PDO;
    use Models\Database as DbModel;
    use Models\Response as DbResponse;
    
    class PageController extends BaseController{
        var $dbConnection;
        var $UserAccessPageSession = "USER_ACCESS_PAGE_SESSION";
        
		function __construct($conn) {
			if( $conn != null ){
				$this->dbConnection = $conn;
			}
		}
        
        public function GetPages(){
			$newPage = new DbModel\Pages;
			return $newPage->Gets($this->dbConnection, $GLOBALS["DEFAULT_PAGE_INDEX"]-1, $GLOBALS["DEFAULT_MAX_PAGE_INDEX"], null);
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