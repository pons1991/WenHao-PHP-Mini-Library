<?php 
    include('Logger.php');
    
    class MiniLoggerManager{
        var $log;
        
        function __construct() {
            Logger::configure('mini.logger.config.xml');
            $this->log = Logger::getLogger('mini');
        }
    }
?>