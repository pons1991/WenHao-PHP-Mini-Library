<?php
    class Autoloader {
        static public function loader($className) {
            $fileName = str_replace("\\", "//", $className).'.php';
            $fullFilePath = dirname(__FILE__).'/'.$fileName;
            if (file_exists($fullFilePath)) {
                
                include_once $fullFilePath;
                if (class_exists($className)) {
                    return true;
                }else{
                    $errorMessage = $className.' class is not found.';
                    throw new Exception($errorMessage);
                }
            }else{
                $errorMessage = $fileName.' file is not found.';
                throw new Exception($errorMessage);
            }
        }
    }
    spl_autoload_register('Autoloader::loader');
?>