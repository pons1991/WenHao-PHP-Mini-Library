<?php 
    require_once "mini.smtp.php";
    require_once "mini.email.php";
    require_once "class.smtp.php";
    require_once "class.phpmailer.php";
    
    
    class MiniEmailManager {
        
        var $smtp;
        var $mail;
        
        function __construct() {
            $this->smtp = new MiniSMTP;
            $this->mail = new PHPMailer;
            
            // Enable verbose debug output
            $this->mail->SMTPDebug = $this->smtp->debug;
            
            // Set mailer to use SMTP
            $this->mail->isSMTP(); 
            
            // Specify main and backup SMTP servers
            $this->mail->Host = $this->smtp->host;
            
            // Enable SMTP authentication
            $this->mail->SMTPAuth = $this->smtp->isAuth;
            
            // SMTP username             
            $this->mail->Username = $this->smtp->username;
            
            // SMTP password
            $this->mail->Password = $this->smtp->password;
            
            // Enable TLS encryption, `ssl` also accepted
            $this->mail->SMTPSecure = $this->smtp->secure;
            
            // TCP port to connect to
            $this->mail->Port = $this->smtp->port;
            
            // Set email format to HTML
            $this->mail->isHTML($this->smtp->isHtml);
            
            //Set the original email as sender
            $this->mail->setFrom($this->smtp->username);
        }
        
        function SendEmail($email){
            if($email != null && is_a($email, 'MiniEmail')){
                if( is_array($email->toAddressList) && count($email->toAddressList) > 0 ){
                    //Add the list of address into mailer address list
                    foreach($email->toAddressList as $address){
                        $this->mail->addAddress($address);
                    }
                    
                    $this->mail->Subject = $email->subject;
                    $this->mail->Body    = $email->message;
                    
                    if(!$this->mail->send()) {
                        //will further log into log file
                        //echo 'Mailer Error: ' . $mail->ErrorInfo;
                    } else {
                        return true;
                    }
                }
            }
            
            return false;
        }
    }
?>