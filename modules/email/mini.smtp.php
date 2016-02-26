<?php

	class MiniSMTP {
        var $host;
        var $port;
        var $isAuth;
        var $username;
        var $password;
        var $debug;
        var $isHtml;
        
        function __construct() {
            $dataJsonString = file_get_contents(dirname ( __FILE__ ).'/web.json');
            $dataArray = json_decode($dataJsonString, true);
            
            $currentObjInst = new ReflectionClass($this);
            $props  = $currentObjInst->getProperties();
            for ($i = 0 ; $i < count($props); $i++) {
                $prop = $props[$i];
                $propName = $prop->getName();
                if( array_key_exists($propName, $dataArray) ){
                    $prop->setValue($this, $dataArray[$propName]);
                }
            }
        }
	}

?>