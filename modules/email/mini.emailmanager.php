<?php 
    require_once "mini.smtp.php";
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

            // TCP port to connect to
            $this->mail->Port = $this->smtp->port;
            
            // Set email format to HTML
            $this->mail->isHTML($this->smtp->isHtml);
            
            //Set the original email as sender
            $this->mail->setFrom($this->smtp->username);
        }
        
        function SendEmail($toList, $ccList, $subject, $body){
            if( $toList != null && is_array($toList) && count($toList) > 0 ){
                    //Add the list of address into mailer address list
                    foreach($toList as $address){
                        $this->mail->addAddress($address);
                    }
                    
                    if( $ccList != null && is_array($ccList) && count($ccList) > 0 ){
                        foreach($ccList as $ccaddress){
                            $this->mail->addCC($ccaddress);
                        }
                        
                    }
                    
                    $this->mail->Subject = $subject;
                    $this->mail->Body    = $body;
                    
                    if(!$this->mail->send()) {
                        ;
                    } else {
                        return true;
                    }
                }
            
            return false;
        }
    }
?>