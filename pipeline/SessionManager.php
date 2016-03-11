<?php 
    namespace pipeline;
    use Controllers as Controller;
    
    class SessionManager{
        public function InitializeUserSession(){
            if (session_status() == PHP_SESSION_NONE || session_id() == '') {
                //start the session is not yet start
                session_start();
            }
        }
        
        public function ClearUserSession(){
            session_unset();
        }
        
        static public function RequiredLogin(){
            $loginController = new Controller\LoginController(null);
            $isLogin = $loginController->CheckUserSession();
            if( !$isLogin ){
                $redirectURL = 'login.php';
                RedirectionManager::Redirection($redirectURL);
            }
        }
    }
    
?>