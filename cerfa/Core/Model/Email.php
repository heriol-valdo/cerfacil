<?php


namespace Projet\Model;
use PHPMailer\PHPMailer\PHPMailer;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;



class Email {





    public static function sendEmailUser($emailDestinateur, $nameDestinateur,$codeDestinateur) {
          $mail = new PHPMailer(true);

           
              
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                    
                $mail->isSMTP();                                         
                $mail->Host       = 'smtp.hostinger.com';                     
                $mail->SMTPAuth   = true;                                
                $mail->Username   = 'contact@lgx-solution.fr';                  
                $mail->Password   = 'R5yhyv62!EgRw0!';                              
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
                $mail->Port       = 465;    
                
           

             
                $mail->setFrom('contact@lgx-solution.fr', 'Admins LGX ');
                //$mail->addAddress($emailDestinateur, 'Joe User');   
                $mail->addAddress($emailDestinateur);              
                $mail->addReplyTo('contact@lgx-solution.fr', 'Admins LGX');
                $mail->addCC('contact@lgx-solution.fr');
                $mail->addBCC('contact@lgx-solution.fr');

                $message = "<html>
                <body>
               

                Bonjour $nameDestinateur,<br/>

                votre mot de passe lgx a été réinitialisé.<br>Nouveau mot de passe :  $codeDestinateur <br/>

                Lien: https://lgx-solution.fr/preprodcerfa/  <br/>
               

                Cordialement l'equipe LGX.
              
                <body/>
                <html/>";

            

              
                $mail->isHTML(true);                                 
                $mail->Subject = 'Réinitialisation du mot de passe';
                $mail->Body    = $message;
                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                
               
               
                return  $mail->send();
      
    }



    public static function sendEmailUserActive($emailDestinateur, $nameDestinateur) {
        $mail = new PHPMailer(true);

           
              
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                    
                $mail->isSMTP();                                         
                $mail->Host       = 'smtp.hostinger.com';                     
                $mail->SMTPAuth   = true;                                
                $mail->Username   = 'contact@lgx-solution.fr';                  
                $mail->Password   = 'R5yhyv62!EgRw0!';                              
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
                $mail->Port       = 465;    
                
           

             
                $mail->setFrom('contact@lgx-solution.fr', 'Admins LGX ');
                //$mail->addAddress($emailDestinateur, 'Joe User');   
                $mail->addAddress($emailDestinateur);              
                $mail->addReplyTo('contact@lgx-solution.fr', 'Admins LGX');
                $mail->addCC('contact@lgx-solution.fr');
                $mail->addBCC('contact@lgx-solution.fr');

                $message = "<html>
                <body>
                <p>

                Bonjour $nameDestinateur,<br/>

                votre compte lgx a été activé.<br>Vous pouvez à nouveau bénéficier des services  lgx. <br/>
               

                Cordialement l'equipe LGX. 
                </p>
                <body/>
                <html/>";

            

              
                $mail->isHTML(true);                                 
                $mail->Subject = 'Activation du compte lgx';
                $mail->Body    = $message;
                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                
               
               
                return  $mail->send();
      
    }

    public static function sendEmailUserDesactive($emailDestinateur, $nameDestinateur) {
               $mail = new PHPMailer(true);

           
              
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                    
                $mail->isSMTP();                                         
                $mail->Host       = 'smtp.hostinger.com';                     
                $mail->SMTPAuth   = true;                                
                $mail->Username   = 'contact@lgx-solution.fr';                  
                $mail->Password   = 'R5yhyv62!EgRw0!';                              
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
                $mail->Port       = 465;    
                
           

             
                $mail->setFrom('contact@lgx-solution.fr', 'Admins LGX ');
                //$mail->addAddress($emailDestinateur, 'Joe User');   
                $mail->addAddress($emailDestinateur);              
                $mail->addReplyTo('contact@lgx-solution.fr', 'Admins LGX');
                $mail->addCC('contact@lgx-solution.fr');
                $mail->addBCC('contact@lgx-solution.fr');

                $message = "<html>
                <body>
                <p>

                Bonjour $nameDestinateur,<br/>

                votre compte lgx a été désactivé.
                <br>Vous ne pouvez plus bénéficier des services  lgx. 
                <br>Contacter-nous pour quelque réclammation.
               

                Cordialement l'equipe LGX.
                </p>
                <body/>
                <html/>";

            

              
                $mail->isHTML(true);                                 
                $mail->Subject = 'Désactivation de votre compte lgx';
                $mail->Body    = $message;
                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                
               
               
                return  $mail->send();
      
    }

    
    

    public static function sendEmailApprenti($emailDestinateur, $data) {
        $encodedData = base64_encode(json_encode($data));
        $link = 'https://lgx-solution.fr/cerfa/form/index.php?data=' . urlencode($encodedData);
        $mail = new PHPMailer(true);

           
              
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                    
                $mail->isSMTP();                                         
                $mail->Host       = 'smtp.hostinger.com';                     
                $mail->SMTPAuth   = true;                                
                $mail->Username   = 'contact@lgx-solution.fr';                  
                $mail->Password   = 'R5yhyv62!EgRw0!';                              
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
                $mail->Port       = 465;    
                
           

             
                $mail->setFrom('contact@lgx-solution.fr', 'Scolarite LGX ');
                //$mail->addAddress($emailDestinateur, 'Joe User');   
                $mail->addAddress($emailDestinateur);              
                $mail->addReplyTo('contact@lgx-solution.fr', 'Scolarite LGX');
                $mail->addCC('contact@lgx-solution.fr');
                $mail->addBCC('contact@lgx-solution.fr');

                $message = "<html>
                    <body>
                  

                    Bonjour,<br/>

                    Nous vous invitons à compléter ce formulaire en fournissant vos informations <br>
                    pour l'établissement de votre Cerfa.<br>
   
                    Lien sécurisé : $link <br>
                    
                   

                    Cordialement l'equipe LGX.
                    </p>
                    <body/>
                    <html/>";

            

              
                $mail->isHTML(true);                                 
                $mail->Subject = 'Information Apprenti(e)';
                $mail->Body    = $message;
                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                
               
               
                return  $mail->send();
            
                
    }
    public static function sendEmailEmployer($emailDestinateur, $data) {
        $encodedData = base64_encode(json_encode($data));
        $link = 'https://lgx-solution.fr/cerfa/formEmployeur/index.php?data=' . urlencode($encodedData);
        $mail = new PHPMailer(true);

           
              
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                    
                $mail->isSMTP();                                         
                $mail->Host       = 'smtp.hostinger.com';                     
                $mail->SMTPAuth   = true;                                
                $mail->Username   = 'contact@lgx-solution.fr';                  
                $mail->Password   = 'R5yhyv62!EgRw0!';                              
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
                $mail->Port       = 465;    
                
           

             
                $mail->setFrom('contact@lgx-solution.fr', 'Scolarite LGX ');
                //$mail->addAddress($emailDestinateur, 'Joe User');   
                $mail->addAddress($emailDestinateur);              
                $mail->addReplyTo('contact@lgx-solution.fr', 'Scolarite LGX');
                $mail->addCC('contact@lgx-solution.fr');
                $mail->addBCC('contact@lgx-solution.fr');

                $message = "<html>
                    <body>
                  

                    Bonjour,<br/>

                    Nous vous invitons à compléter ce formulaire en fournissant vos informations <br>
                    pour l'établissement du cerfa de votre apprenti(e).<br>
   
                    Lien sécurisé : $link <br>
                    
                   

                    Cordialement l'equipe LGX.
                    </p>
                    <body/>
                    <html/>";

            

              
                $mail->isHTML(true);                                 
                $mail->Subject = 'Information Apprenti(e) / Maître de stage';
                $mail->Body    = $message;
                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                
               
               
                return  $mail->send();
            
                
    }

    public static function sendEmailEmployerSignature($emailDestinateur, $data) {
        $encodedData = base64_encode(json_encode($data));
        $link = 'https://lgx-solution.fr/preprodcerfa/formEmployeurSignature/index.php?data=' . urlencode($encodedData);
        $mail = new PHPMailer(true);

           
              
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                    
                $mail->isSMTP();                                         
                $mail->Host       = 'smtp.hostinger.com';                     
                $mail->SMTPAuth   = true;                                
                $mail->Username   = 'contact@lgx-solution.fr';                  
                $mail->Password   = 'R5yhyv62!EgRw0!';                              
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
                $mail->Port       = 465;    
                
           

             
                $mail->setFrom('contact@lgx-solution.fr', 'Scolarite LGX ');
                //$mail->addAddress($emailDestinateur, 'Joe User');   
                $mail->addAddress($emailDestinateur);              
                $mail->addReplyTo('contact@lgx-solution.fr', 'Scolarite LGX');
                $mail->addCC('contact@lgx-solution.fr');
                $mail->addBCC('contact@lgx-solution.fr');

                $message = "<html>
                    <body>
                  

                    Bonjour,<br/>

                    Nous vous invitons à compléter ce formulaire en fournissant vos informations <br>
                    pour l'établissement du cerfa de votre apprenti(e).<br>
   
                    Lien sécurisé : $link <br>
                    
                   

                    Cordialement l'equipe LGX.
                    </p>
                    <body/>
                    <html/>";

            

              
                $mail->isHTML(true);                                 
                $mail->Subject = 'Signature Cerfa';
                $mail->Body    = $message;
                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                
               
               
                return  $mail->send();
            
                
    }

    public static function sendEmailApprentiSignature($emailDestinateur, $data) {
        $encodedData = base64_encode(json_encode($data));
        $link = 'https://lgx-solution.fr/preprodcerfa/formSignature/index.php?data=' . urlencode($encodedData);
        $mail = new PHPMailer(true);

           
              
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                    
                $mail->isSMTP();                                         
                $mail->Host       = 'smtp.hostinger.com';                     
                $mail->SMTPAuth   = true;                                
                $mail->Username   = 'contact@lgx-solution.fr';                  
                $mail->Password   = 'R5yhyv62!EgRw0!';                              
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
                $mail->Port       = 465;    
                
           

             
                $mail->setFrom('contact@lgx-solution.fr', 'Scolarite LGX ');
                //$mail->addAddress($emailDestinateur, 'Joe User');   
                $mail->addAddress($emailDestinateur);              
                $mail->addReplyTo('contact@lgx-solution.fr', 'Scolarite LGX');
                $mail->addCC('contact@lgx-solution.fr');
                $mail->addBCC('contact@lgx-solution.fr');

                $message = "<html>
                    <body>
                  

                    Bonjour,<br/>

                    Nous vous invitons à compléter ce formulaire en fournissant vos informations <br>
                    pour l'établissement de votre cerfa.<br>
   
                    Lien sécurisé : $link <br>
                    
                   

                    Cordialement l'equipe LGX.
                    </p>
                    <body/>
                    <html/>";

            

              
                $mail->isHTML(true);                                 
                $mail->Subject = 'Signature Cerfa';
                $mail->Body    = $message;
                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                
               
               
                return  $mail->send();
            
                
    }
}

