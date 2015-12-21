<?php
    class PageController{
        var $dbConnection;

		function __construct($conn) {
			if( $conn != null ){
				$this->dbConnection = $conn;
			}
		}
        
        public function GetPages(){
			$newPage = new Pages;
			return $newPage->Gets($this->dbConnection, 0, 999, null);
		}
    }
?>