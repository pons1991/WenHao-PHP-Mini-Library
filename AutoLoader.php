<?php
    class Autoloader {
        static public function loader($className) {
            $fileName = str_replace('\\', '//', $className).'.php';
            if (file_exists($fileName)) {
                include_once $fileName;
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