<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__.'/../vendor/autoload.php';

class Email {

    protected static $host = 'cerfa.heriolvaldo.com';
    protected static $username = 'ne-pas-repondre@cerfa.heriolvaldo.com';
    protected static $password = 'KWVr-hGsV-Bj8!';
    protected static $entreprise = "CerFacil";

    protected static $usernames = 'contact@cerfa.heriolvaldo.com';



    protected static $lien = "https://cerfa.heriolvaldo.com/cerfa";

    protected static $lienApp = "https://cerfa.heriolvaldo.com/app";

    protected static $lienErp = "https://cerfa.heriolvaldo.com/erp";



    public static function sendEmailNewLetterCerFacil($email) {  
        // Configuration du mail
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0; //SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host       = self::$host;
        $mail->SMTPAuth   = true;
        $mail->Username   = self::$username;
        $mail->Password   = self::$password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->setFrom(self::$username, 'Admins '.self::$entreprise);
        $mail->addAddress($email);
    
        $mail->addReplyTo(self::$username, 'Admins '.self::$entreprise);                 
        
    
        
        // Contenu du mail
        $emailContent = "<html><body>
                        Bonjour ,<br/><br/>
                        Nous confirmons votre inscription à la newsletter.<br/><br/>
                        Nous vous remercions pour votre intérêt et nous nous réjouissons de vous rencontrer.<br/><br/>
                        Cordialement,<br/>
                        L'équipe ".self::$entreprise."
                        </body></html>";
        
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = 'Confirmation de votre Inscription ';
        $mail->Body = $emailContent;                
        $mail->send();
        return true;
    }
    public static function sendEmailContactCerFacil($name, $email, $phone, $message, $company, $selectedDate, $selectedTime) {
        // Formater la date en français
        $formatter = new IntlDateFormatter(
            'fr_FR',
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            'Europe/Paris',
            IntlDateFormatter::GREGORIAN,
            'd MMMM yyyy'
        );
        
        $formattedDate = $formatter->format(strtotime($selectedDate));
        
        // Configuration du mail
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0; //SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host       = self::$host;
        $mail->SMTPAuth   = true;
        $mail->Username   = self::$usernames;
        $mail->Password   = self::$password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->setFrom(self::$usernames, 'Admins '.self::$entreprise);
        $mail->addAddress($email, $name);
    
        $mail->addReplyTo(self::$usernames, 'Admins '.self::$entreprise);                 
        $mail->addCC(self::$usernames);                 
        $mail->addBCC(self::$usernames);
        
        // Gérer les champs qui peuvent être vides
        $companyInfo = !empty($company) ? "Société : $company<br/>" : "";
        $messageInfo = !empty($message) ? "Message : $message<br/><br/>" : "";
        
        // Contenu du mail
        $emailContent = "<html><body>
                        Bonjour $name,<br/><br/>
                        Nous confirmons votre rendez-vous prévu le  $formattedDate à $selectedTime.<br/><br/>
                        <strong>Détails de votre demande :</strong><br/>
                        Nom : $name<br/>
                        Email : $email<br/>
                        Téléphone : $phone<br/>
                        $companyInfo
                        $messageInfo
                        Nous vous remercions pour votre intérêt et nous nous réjouissons de vous rencontrer.<br/><br/>
                        Cordialement,<br/>
                        L'équipe ".self::$entreprise."
                        </body></html>";
        
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = 'Confirmation de votre rendez-vous du ' .  $formattedDate;
        $mail->Body = $emailContent;                
        $mail->send();
        return true;
    }

    public static function sendEmailUser($emailReceiver, $nameDestinateur, $lien) {
        $formatter = new IntlDateFormatter(
            'fr_FR',
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            'Europe/Paris',
            IntlDateFormatter::GREGORIAN,
            'd MMMM yyyy'
        );

        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host       = self::$host;
        $mail->SMTPAuth   = true;
        $mail->Username   = self::$username;
        $mail->Password   = self::$password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->setFrom(self::$username, 'Admins '.self::$entreprise);
        //$mail->addAddress($emailDestinateur, 'Joe User');
        $mail->addAddress($emailReceiver);

        $mail->addReplyTo(self::$username, 'Admins '.self::$entreprise);                 
        $mail->addCC(self::$username);                 
        $mail->addBCC(self::$username);                  
        $message = "<html><body>Bonjour $nameDestinateur,<br/>
                    Vous avez fait une demande de réinitialisation de mot de passe. <br/>
                    Pour la finaliser, veuillez suivre ce lien valable 15 minutes  : $lien <br/>
                    Cordialement l'equipe ".self::$entreprise.".<body/><html/>";
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = 'Réinitialisation du mot de passe';
        $mail->Body = $message;                
        $mail->send();
        return true;
    } 

    public static function sendEmailFormApprenti($emailDestinateur, $data) {
        $encodedData = base64_encode(json_encode($data));
        $link = self::$lien.'/form/index.php?data=' . urlencode($encodedData);
        try{
        $mail = new PHPMailer(true);     
        $mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;                  
        $mail->isSMTP();                                         
        $mail->Host       = self::$host;                     
        $mail->SMTPAuth   = true;                                
        $mail->Username   = self::$username;                  
        $mail->Password   = self::$password;                              
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
        $mail->Port       = 465;    
        $mail->setFrom(self::$username, 'Scolarite '.self::$entreprise);
        $mail->addAddress($emailDestinateur);              
        $mail->addReplyTo(self::$username, 'Scolarite '.self::$entreprise);
        $mail->addCC(self::$username);
        $mail->addBCC(self::$username);

        $message = "<html>
            <body>
            

            Bonjour,<br/>

            Nous vous invitons à compléter ce formulaire en fournissant vos informations <br>
            pour l'établissement de votre Cerfa.<br>

            Lien sécurisé : $link <br>
            


            Cordialement l'equipe ".self::$entreprise.".
            </p>
            <body/>
            <html/>";
        $mail->isHTML(true);                                 
        $mail->Subject = 'Information Apprenti(e)';
        $mail->Body    = $message;
        $mail->send();
        return true;  
        }catch(Exception $e){
          return $e->getMessage();
        }  
    }

    public static function sendEmailFormRepresentantApprenti($emailDestinateur, $data) {
        $encodedData = base64_encode(json_encode($data));
        $link = self::$lien.'/formRepresentantSignature/index.php?data=' . urlencode($encodedData);
        try{
        $mail = new PHPMailer(true);     
        $mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;                  
        $mail->isSMTP();                                         
        $mail->Host       = self::$host;                     
        $mail->SMTPAuth   = true;                                
        $mail->Username   = self::$username;                  
        $mail->Password   = self::$password;                              
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
        $mail->Port       = 465;    
        $mail->setFrom(self::$username, 'Scolarite '.self::$entreprise);
        $mail->addAddress($emailDestinateur);              
        $mail->addReplyTo(self::$username, 'Scolarite '.self::$entreprise);
        $mail->addCC(self::$username);
        $mail->addBCC(self::$username);

        $message = "<html>
            <body>
            

            Bonjour,<br/>

           Nous vous invitons à signer ce document 
           pour l'établissement du contrat de votre enfant.<br>

            Lien sécurisé : $link <br>
            


            Cordialement l'equipe ".self::$entreprise.".
            </p>
            <body/>
            <html/>";
        $mail->isHTML(true);                                 
        $mail->Subject = 'Signature Représentnat';
        $mail->Body    = $message;
        $mail->send();
        return true;  
        }catch(Exception $e){
          return $e->getMessage();
        }  
    }

    public static function sendEmailFormEmployeur($emailDestinateur, $data) {
        $encodedData = base64_encode(json_encode($data));
        $link = self::$lien.'/formEmployeur/index.php?data=' . urlencode($encodedData);
        try{
        $mail = new PHPMailer(true);     
        $mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;                  
        $mail->isSMTP();                                         
        $mail->Host       =  self::$host;                   
        $mail->SMTPAuth   = true;                                
        $mail->Username   =  self::$username;                  
        $mail->Password   = self::$password;                              
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
        $mail->Port       = 465;    
        $mail->setFrom( self::$username, 'Scolarite '.self::$entreprise);
        $mail->addAddress($emailDestinateur);              
        $mail->addReplyTo(self::$username, 'Scolarite '.self::$entreprise);
        $mail->addCC(self::$username);
        $mail->addBCC(self::$username);

        $message = "<html>
        <body>
      

        Bonjour,<br/>

        Nous vous invitons à compléter ce formulaire en fournissant vos informations <br>
        pour l'établissement du cerfa de votre apprenti(e).<br>

        Lien sécurisé : $link <br>
        
       

        Cordialement l'equipe ".self::$entreprise.".
        </p>
        <body/>
        <html/>";
        $mail->isHTML(true);                                 
        $mail->Subject = 'Information Apprenti(e)';
        $mail->Body    = $message;
        $mail->send();
        return true;  
        }catch(Exception $e){
          return $e->getMessage();
        }  
    }

    public static function sendEmailFormDataEmployeur($emailDestinateur, $data) {
        $encodedData = base64_encode(json_encode($data));
        $link = self::$lien.'/formEmployeurs/index.php?data=' . urlencode($encodedData);
        try{
        $mail = new PHPMailer(true);     
        $mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;                  
        $mail->isSMTP();                                         
        $mail->Host       =  self::$host;                   
        $mail->SMTPAuth   = true;                                
        $mail->Username   =  self::$username;                  
        $mail->Password   = self::$password;                              
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
        $mail->Port       = 465;    
        $mail->setFrom( self::$username, 'Scolarite '.self::$entreprise);
        $mail->addAddress($emailDestinateur);              
        $mail->addReplyTo(self::$username, 'Scolarite '.self::$entreprise);
        $mail->addCC(self::$username);
        $mail->addBCC(self::$username);

        $message = "<html>
        <body>
      

        Bonjour,<br/>

        Nous vous invitons à compléter ce formulaire en fournissant vos informations <br>
        pour l'établissement du cerfa de votre apprenti(e).<br>

        Lien sécurisé : $link <br>
        
       

        Cordialement l'equipe ".self::$entreprise.".
        </p>
        <body/>
        <html/>";
        $mail->isHTML(true);                                 
        $mail->Subject = 'Information Entreprise';
        $mail->Body    = $message;
        $mail->send();
        return true;  
        }catch(Exception $e){
          return $e->getMessage();
        }  
    }

    public static function sendEmailFormSignatureEmployeur($emailDestinateur, $data) {
        $encodedData = base64_encode(json_encode($data));
        $link = self::$lien.'/formEmployeurSignature/index.php?data=' . urlencode($encodedData);
        try{
        $mail = new PHPMailer(true);     
        $mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;                  
        $mail->isSMTP();                                         
        $mail->Host       = self::$host;                     
        $mail->SMTPAuth   = true;                                
        $mail->Username   = self::$username;                  
        $mail->Password   = self::$password;                              
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
        $mail->Port       = 465;    
        $mail->setFrom(self::$username, 'Scolarite '.self::$entreprise);
        $mail->addAddress($emailDestinateur);              
        $mail->addReplyTo(self::$username, 'Scolarite '.self::$entreprise);
        $mail->addCC(self::$username);
        $mail->addBCC(self::$username);

        $message = "<html>
        <body>
      

        Bonjour,<br/>

        Nous vous invitons à signer ce document 
        pour l'établissement du contrat de votre apprenti(e).<br>

        Lien sécurisé : $link <br>
        
       

        Cordialement l'equipe ".self::$entreprise.".
        </p>
        <body/>
        <html/>";
        $mail->isHTML(true);                                 
        $mail->Subject = 'Signature Cerfa';
        $mail->Body    = $message;
        $mail->send();
        return true;  
        }catch(Exception $e){
          return $e->getMessage();
        }  
    }

    public static function sendEmailFormSignatureConventionEmployeur($emailDestinateur, $data) {
      $encodedData = base64_encode(json_encode($data));
      $link = self::$lien.'/formEmployeurConventionSignature/index.php?data=' . urlencode($encodedData);
      try{
      $mail = new PHPMailer(true);     
      $mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;                  
      $mail->isSMTP();                                         
      $mail->Host       = self::$host;                     
      $mail->SMTPAuth   = true;                                
      $mail->Username   = self::$username;                  
      $mail->Password   = self::$password;                              
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
      $mail->Port       = 465;    
      $mail->setFrom(self::$username, 'Scolarite '.self::$entreprise);
      $mail->addAddress($emailDestinateur);              
      $mail->addReplyTo(self::$username, 'Scolarite '.self::$entreprise);
      $mail->addCC(self::$username);
      $mail->addBCC(self::$username);

      $message = "<html>
      <body>
    

      Bonjour,<br/>

      Nous vous invitons à signer ce document ( convention )
      pour l'établissement du contrat de votre apprenti(e).<br>

      Lien sécurisé : $link <br>
      
     

      Cordialement l'equipe ".self::$entreprise.".
      </p>
      <body/>
      <html/>";
      $mail->isHTML(true);                                 
      $mail->Subject = 'Signature Convention';
      $mail->Body    = $message;
      $mail->send();
      return true;  
      }catch(Exception $e){
        return $e->getMessage();
      }  
  }

  public static function sendEmailFormContratEmployeur($emailDestinateur, $data) {
    $encodedData = base64_encode(json_encode($data));
    $link = self::$lien.'/formContratEmployeurs/index.php?data=' . urlencode($encodedData);
    try{
    $mail = new PHPMailer(true);     
    $mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;                  
    $mail->isSMTP();                                         
    $mail->Host       = self::$host;                     
    $mail->SMTPAuth   = true;                                
    $mail->Username   = self::$username;                  
    $mail->Password   = self::$password;                              
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
    $mail->Port       = 465;    
    $mail->setFrom(self::$username, 'Scolarite '.self::$entreprise);
    $mail->addAddress($emailDestinateur);              
    $mail->addReplyTo(self::$username, 'Scolarite '.self::$entreprise);
    $mail->addCC(self::$username);
    $mail->addBCC(self::$username);

    $message = "<html>
    <body>
  

    Bonjour,<br/>

    Nous vous invitons à remplir ce document ( cerfa )
    pour l'établissement du contrat de votre apprenti(e).<br>

    Lien sécurisé : $link <br>
    
   

    Cordialement l'equipe ".self::$entreprise.".
    </p>
    <body/>
    <html/>";
    $mail->isHTML(true);                                 
    $mail->Subject = 'Information Contrat';
    $mail->Body    = $message;
    $mail->send();
    return true;  
    }catch(Exception $e){
      return $e->getMessage();
    }  
}

    public static function sendEmailFormSignatureApprenti($emailDestinateur, $data) {
        $encodedData = base64_encode(json_encode($data));
        $link = self::$lien.'/formApprentiSignature/index.php?data=' . urlencode($encodedData);
        try{
        $mail = new PHPMailer(true);     
        $mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;                  
        $mail->isSMTP();                                         
        $mail->Host       = self::$host;                     
        $mail->SMTPAuth   = true;                                
        $mail->Username   = self::$username;                  
        $mail->Password   = self::$password;                              
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
        $mail->Port       = 465;    
        $mail->setFrom(self::$username, 'Scolarite '.self::$entreprise);
        $mail->addAddress($emailDestinateur);              
        $mail->addReplyTo(self::$username, 'Scolarite '.self::$entreprise);
        $mail->addCC(self::$username);
        $mail->addBCC(self::$username);

        $message = "<html>
        <body>
      

        Bonjour,<br/>

        Nous vous invitons à signer ce document 
        pour l'établissement de votre  contrat.<br>

        Lien sécurisé : $link <br>
        
       

        Cordialement l'equipe ".self::$entreprise.".
        </p>
        <body/>
        <html/>";
        $mail->isHTML(true);                                 
        $mail->Subject = 'Signature Cerfa';
        $mail->Body    = $message;
        $mail->send();
        return true;  
        }catch(Exception $e){
          return $e->getMessage();
        }  
    }

    public static function sendEmailFormSignatureEcole($emailDestinateur, $data) {
      $encodedData = base64_encode(json_encode($data));
      $link = self::$lien.'/formEcoleSignature/index.php?data=' . urlencode($encodedData);
      try{
      $mail = new PHPMailer(true);     
      $mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;                  
      $mail->isSMTP();                                         
      $mail->Host       = self::$host;                     
      $mail->SMTPAuth   = true;                                
      $mail->Username   = self::$username;                  
      $mail->Password   = self::$password;                              
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
      $mail->Port       = 465;    
      $mail->setFrom(self::$username, 'Scolarite '.self::$entreprise);
      $mail->addAddress($emailDestinateur);              
      $mail->addReplyTo(self::$username, 'Scolarite '.self::$entreprise);
      $mail->addCC(self::$username);
      $mail->addBCC(self::$username);

      $message = "<html>
      <body>
    

      Bonjour,<br/>

      Nous vous invitons à signer ce document 
      pour l'établissement du  contrat de votre Apprenti.<br>

      Lien sécurisé : $link <br>
      
     

      Cordialement l'equipe ".self::$entreprise.".
      </p>
      <body/>
      <html/>";
      $mail->isHTML(true);                                 
      $mail->Subject = 'Signature Cerfa';
      $mail->Body    = $message;
      $mail->send();
      return true;  
      }catch(Exception $e){
        return $e->getMessage();
      }  
  }

    public static function sendEmailFormSignatureConventionEcole($emailDestinateur, $data) {
      $encodedData = base64_encode(json_encode($data));
      $link = self::$lien.'/formEcoleConventionSignature/index.php?data=' . urlencode($encodedData);
      try{
      $mail = new PHPMailer(true);     
      $mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;                  
      $mail->isSMTP();                                         
      $mail->Host       = self::$host;                     
      $mail->SMTPAuth   = true;                                
      $mail->Username   = self::$username;                  
      $mail->Password   = self::$password;                              
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
      $mail->Port       = 465;    
      $mail->setFrom(self::$username, 'Scolarite '.self::$entreprise);
      $mail->addAddress($emailDestinateur);              
      $mail->addReplyTo(self::$username, 'Scolarite '.self::$entreprise);
      $mail->addCC(self::$username);
      $mail->addBCC(self::$username);

      $message = "<html>
      <body>
    

      Bonjour,<br/>

      Nous vous invitons à signer ce document ( convention )
      pour l'établissement du contrat de votre apprenti(e).<br>

      Lien sécurisé : $link <br>
      
    

      Cordialement l'equipe ".self::$entreprise.".
      </p>
      <body/>
      <html/>";
      $mail->isHTML(true);                                 
      $mail->Subject = 'Signature Convention';
      $mail->Body    = $message;
      $mail->send();
      return true;  
      }catch(Exception $e){
        return $e->getMessage();
      }  
  }

    public static function sendEmailAbonement($email, $firstname, $date_debut, $date_fin, $quantite, $nameProduit, $prixUnitaireDossier, $prixUnitaireAbonement,
     $totalFacture, $totalDossier, $stripe,$totalAbonement = null) {
      try {
          $mail = new PHPMailer(true);     
          $mail->SMTPDebug = 0;                  
          $mail->isSMTP();                                         
          $mail->Host       = self::$host;                     
          $mail->SMTPAuth   = true;                                
          $mail->Username   = self::$username;                  
          $mail->Password   = self::$password;                             
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
          $mail->Port       = 465;    
          $mail->setFrom(self::$username, 'Administration '.self::$entreprise);
          $mail->addAddress($email);              
          $mail->addReplyTo(self::$username, 'Administration '.self::$entreprise);
          $mail->addCC(self::$username);
          $mail->addBCC(self::$username);

          $tva = $totalFacture - ($totalFacture / 1.2);
          $datedebut = date("d/m/Y", strtotime($date_debut));
          $dateFin = date("d/m/Y", strtotime($date_fin));
          $abonementRow = '';
          if ($totalAbonement !== null) {
              $abonementRow = "
              <tr>
                  <td>Abonnement (Du $datedebut au $dateFin)</td>
                  <td>$prixUnitaireAbonement €</td>
                  <td>$totalAbonement €</td>
              </tr>";
          }

          $message = "
          <html>
          <body>
          <p>Bonjour $firstname,</p>
          <p>Vous avez effectué un Achat sur notre plateforme.</p>
          <p>Vous trouverez ci-dessous le détail de votre Achat $stripe:</p>
          <table border='1' cellpadding='5' cellspacing='0'>
              <tr>
                  <th>Détail</th>
                  <th>Prix Unitaire</th>
                  <th>Total</th>
              </tr>
              <tr>
                  <td>Quantite De Dossiers ($quantite)</td>
                  <td>$prixUnitaireDossier €</td>
                  <td>$totalDossier €</td>
              </tr>
              $abonementRow
              <tr>
                  <td colspan='2'><strong>TVA(20%)</strong></td>
                  <td><strong>$tva €</strong></td>
              </tr>
              <tr>
                  <td colspan='2'><strong>Total</strong></td>
                  <td><strong>$totalFacture €</strong></td>
              </tr>
          </table>
          <p>Cordialement,<br>L'équipe ".self::$entreprise."</p>
          </body>
          </html>";

          $mail->isHTML(true);                                 
          $mail->Subject = "Achat $nameProduit";
          $mail->Body    = $message;
          $mail->send();
          return true;  
      } catch (Exception $e) {
          return $e->getMessage();
      }  
  }


  public static function sendEmailReAbonement($email, $firstname, $date_debut, $date_fin, $quantite, $nameProduit, $prixUnitaireDossier, 
  $prixUnitaireAbonement, $totalFacture, $totalDossier,$type,$stripe, $totalAbonement = null) {
    try {
      
        $mail = new PHPMailer(true);     
        $mail->SMTPDebug = 0;                  
        $mail->isSMTP();                                         
        $mail->Host       = self::$host;                     
        $mail->SMTPAuth   = true;                                
        $mail->Username   = self::$username;                  
        $mail->Password   = self::$password;                              
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
        $mail->Port       = 465;    
        $mail->setFrom(self::$username, 'Administration '.self::$entreprise);
        $mail->addAddress($email);              
        $mail->addReplyTo(self::$username, 'Administration '.self::$entreprise);
        $mail->addCC(self::$username);
        $mail->addBCC(self::$username);

        $dateCourante = date("Y-m-d");

        $tva = $totalFacture - ($totalFacture / 1.2);
        $datedebut = date("d/m/Y", strtotime($date_debut));
        $dateFin = date("d/m/Y", strtotime($date_fin));
        $abonementRow = '';
        $ReabonementRow = '';

        if(intval($quantite) !== 0){
          $ReabonementRow = "
            <tr>
                <td>Quantite De Dossiers ($quantite)</td>
                <td>$prixUnitaireDossier €</td>
                <td>$totalDossier €</td>
            </tr>
          ";
        }
        if ($totalAbonement !== null) {
            if(intval($type) === 1){
              $abonementRow = "";
            }else{
              $abonementRow = "
              <tr>
                  <td>ReAbonnement (Du $datedebut au $dateFin)</td>
                  <td>$prixUnitaireAbonement €</td>
                  <td>$totalAbonement €</td>
              </tr>";
            }
            
        }

        $message = "
        <html>
        <body>
        <p>Bonjour $firstname,</p>
        <p>Vous avez effectué un Achat sur notre plateforme.</p>
        <p>Vous trouverez ci-dessous le détail de votre Achat $stripe:</p>
        <table border='1' cellpadding='5' cellspacing='0'>
            <tr>
                <th>Détail</th>
                <th>Prix Unitaire</th>
                <th>Total</th>
            </tr>
          
            $abonementRow
            $ReabonementRow

             <tr>
                  <td colspan='2'><strong>TVA(20%)</strong></td>
                  <td><strong>$tva €</strong></td>
              </tr>
             <tr>
                <td colspan='2'><strong>Total</strong></td>
                <td><strong>$totalFacture €</strong></td>
            </tr>
           
        </table>
        <p>Cordialement,<br>L'équipe ".self::$entreprise."</p>
        </body>
        </html>";

        $mail->isHTML(true);                                 
        $mail->Subject = "Achat $nameProduit";
        $mail->Body    = $message;
        $mail->send();
        return true;  
    } catch (Exception $e) {
        return $e->getMessage();
    }  
}


  public static function sendEmailCreationClientCerfa($firstname,$email,$password){
    $link = self::$lien;

    try {
        
        $mail = new PHPMailer(true);     
        $mail->SMTPDebug = 0;                  
        $mail->isSMTP();                                         
        $mail->Host       = self::$host;                     
        $mail->SMTPAuth   = true;                                
        $mail->Username   = self::$username;                  
        $mail->Password   = self::$password;                              
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
        $mail->Port       = 465;    
        $mail->setFrom(self::$username, 'Administration '.self::$entreprise);
        $mail->addAddress($email);              
        $mail->addReplyTo(self::$username, 'Administration '.self::$entreprise);
        $mail->addCC(self::$username);
        $mail->addBCC(self::$username);

      
        

        $message = "
        <html>
        <body>
        <p>Bonjour $firstname,</p>
        <p>Vous nous accordez votre confiance et nous vous en remercions.</p>
        <p>Vous trouverez ci-dessous le détail de votre compte :</p>

        Email: $email <br>
        Mot de passe : $password <br>

        Lien sécurisé vers notre plateforme: $link <br>
        
        <p>Cordialement,<br>L'équipe ".self::$entreprise."</p>
        </body>
        </html>";

        $mail->isHTML(true);                                 
        $mail->Subject = "Information de gestion";
        $mail->Body    = $message;
        $mail->send();
        return true;  
    } catch (Exception $e) {
        return $e->getMessage();
    } 
  }

  public static function sendEmailCreationCompteERP($firstname,$nomRole,$email,$password){
    $link = self::$lienErp;
    setlocale(LC_TIME, 'fr_FR.UTF-8');

    try {
        $mail = new PHPMailer(true);     
        $mail->SMTPDebug = 0;                  
        $mail->isSMTP();                                         
        $mail->Host       = self::$host;                     
        $mail->SMTPAuth   = true;                                
        $mail->Username   = self::$username;                  
        $mail->Password   = self::$password;                              
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
        $mail->Port       = 465;    
        $mail->setFrom(self::$username, 'Administration '.self::$entreprise);
        $mail->addAddress($email);              
        $mail->addReplyTo(self::$username, 'Administration '.self::$entreprise);
        $mail->addCC(self::$username);
        $mail->addBCC(self::$username);

      
        

        $message = "
        <html>
        <body>
        <p>Bonjour $firstname,</p>
        <p>Vous nous accordez votre confiance et nous vous en remercions.</p>
        <p>Vous trouverez ci-dessous le détail de votre compte $nomRole pour accéder à l'ERP :</p>

        Email: $email<br>
        Mot de passe : $password<br>

        Lien sécurisé vers notre plateforme: $link <br>

        
        <p>Cordialement,<br>L'équipe ".self::$entreprise."</p>
        </body>
        </html>";

        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);                                 
        $mail->Subject = "Création de vos accès à l'ERP";
        $mail->Body    = $message;
        $mail->send();
        return true;  
    } catch (Exception $e) {
        return $e->getMessage();
    } 
  }

   public static function sendEmailCreationCompteCerFacilApp($firstname,$nomRole,$email,$password){
    $link = self::$lienApp;
    setlocale(LC_TIME, 'fr_FR.UTF-8');

    try {
        $mail = new PHPMailer(true);     
        $mail->SMTPDebug = 0;                  
        $mail->isSMTP();                                         
        $mail->Host       = self::$host;                     
        $mail->SMTPAuth   = true;                                
        $mail->Username   = self::$username;                  
        $mail->Password   = self::$password;                              
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
        $mail->Port       = 465;    
        $mail->setFrom(self::$username, 'Administration '.self::$entreprise);
        $mail->addAddress($email);              
        $mail->addReplyTo(self::$username, 'Administration '.self::$entreprise);
        $mail->addCC(self::$username);
        $mail->addBCC(self::$username);

      
        

        $message = "
        <html>
        <body>
        <p>Bonjour,</p>
        <p>Vous nous accordez votre confiance et nous vous en remercions.</p>
        <p>Vous trouverez ci-dessous le détail de votre compte $nomRole pour accéder à CerFacil :</p>

        Email: $email<br>
        Mot de passe : $password<br>

        Lien sécurisé vers notre plateforme: $link <br>

        
        <p>Cordialement,<br>L'équipe ".self::$entreprise."</p>
        </body>
        </html>";

        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);                                 
        $mail->Subject = "Création de vos accès à CerFacil";
        $mail->Body    = $message;
        $mail->send();
        return true;  
    } catch (Exception $e) {
        return $e->getMessage();
    } 
  }

   public static function sendEmailAjoutCerFacilApp($email){
    $link = self::$lienApp;
    setlocale(LC_TIME, 'fr_FR.UTF-8');

    try {
        $mail = new PHPMailer(true);     
        $mail->SMTPDebug = 0;                  
        $mail->isSMTP();                                         
        $mail->Host       = self::$host;                     
        $mail->SMTPAuth   = true;                                
        $mail->Username   = self::$username;                  
        $mail->Password   = self::$password;                              
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
        $mail->Port       = 465;    
        $mail->setFrom(self::$username, 'Administration '.self::$entreprise);
        $mail->addAddress($email);              
        $mail->addReplyTo(self::$username, 'Administration '.self::$entreprise);
        $mail->addCC(self::$username);
        $mail->addBCC(self::$username);

      
        

        $message = "
        <html>
        <body>
        <p>Bonjour,</p>
        <p>Vous nous accordez votre confiance et nous vous en remercions.</p>
        <p> Un nouveau dossier a été ajouté à votre compte CerFacil:</p>

        

        Lien sécurisé vers notre plateforme: $link <br>

        
        <p>Cordialement,<br>L'équipe ".self::$entreprise."</p>
        </body>
        </html>";

        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);                                 
        $mail->Subject = "Ajout de dossiers à votre compte CerFacil";
        $mail->Body    = $message;
        $mail->send();
        return true;  
    } catch (Exception $e) {
        return $e->getMessage();
    } 
  }



  
  public static function sendEventRecap($is_update, $email, $firstname, $event_nom, $debut, $fin, $modalites, $salle, $url, $description, $is_cours, $formateur_nom, $formateur_prenom, $matiere_nom){
    $link = self::$lien;
    $formatter = new IntlDateFormatter(
      'fr_FR',
      IntlDateFormatter::FULL,
      IntlDateFormatter::NONE,
      'Europe/Paris', 
      IntlDateFormatter::GREGORIAN,
      'd MMMM yyyy'
  );

  setlocale(LC_TIME, 'fr_FR.UTF-8');
  
    $dateTimeDebut = new DateTime($debut);
    $dateTimeFin = new DateTime($fin);
    
    $debutDate = strftime('%d %B %Y', $dateTimeDebut->getTimestamp());
    $finDate = strftime('%d %B %Y', $dateTimeFin->getTimestamp());

    $debutShortDate = $dateTimeDebut->format('d-m-Y');
    $finShortDate = $dateTimeFin->format('d-m-Y');
    $debutTime = $dateTimeDebut->format('H:i');
    $finTime = $dateTimeFin->format('H:i');

    $short_period = "$debutShortDate $debutTime - $finTime";

    $period = "$debutDate $debutTime - $finTime" ;

    $salle_msg = $modalites != "Distanciel" ? "<strong>Salle</strong> : $salle <br>" : "";

    $description_msg = $description != null ? "<strong>Description</strong> : $description <br>" : "";

    $url_msg = $url != null ? "<strong>Lien</strong> : $url <br>" : ""; 

    $en_tete = $is_update == true ? "Modifié" : "Invitation";
    $en_tete_details = $is_cours == true ? "Détails du cours" : "Détails de l'évènement";
    $txt_intro = $is_update == true ? "Un évènement a été mis à jour." : "Un nouvel évènement a été créé.";

    $matiere_msg = $is_cours == true ? "<p><strong>Matière :</strong> $matiere_nom</p>" : "";
    $formateur_msg = $is_cours == true ? "<p><strong>Formateur :</strong> $formateur_nom $formateur_prenom</p>" : "";

    try {
        $mail = new PHPMailer(true);     
        $mail->SMTPDebug = 0;                  
        $mail->isSMTP();                                         
        $mail->Host       = self::$host;                     
        $mail->SMTPAuth   = true;                                
        $mail->Username   = self::$username;                  
        $mail->Password   = self::$password;                              
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
        $mail->Port       = 465;    
        $mail->setFrom(self::$username, 'Administration '.self::$entreprise);
        $mail->addAddress($email);              
        $mail->addReplyTo(self::$username, 'Administration '.self::$entreprise);
        $mail->addCC(self::$username);
        $mail->addBCC(self::$username);

      
        $message = "
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    color: #333;
                    line-height: 1.6;
                }
                .container {
                    width: 100%;
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 0 20px 20px 20px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    background-color: #f9f9f9;
                }
                .header {
                    font-size: 20px;
                    font-weight: bold;
                    margin-bottom: 10px;
                }
                .content p {
                    margin: 10px 0;
                }
                .footer {
                    margin-top: 20px;
                    font-size: 14px;
                    color: #777;
                }
            </style>
        </head>
        <body>
            <p>Bonjour $firstname,</p>
            <p>$txt_intro</p>
            <div class='container'>
                <h3>$en_tete_details</h3>
                <p><strong>Nom de l'évènement :</strong> $event_nom</p>
                $matiere_msg
                $formateur_msg
                <p><strong>Date :</strong> $period</p>
                <p><strong>Modalités :</strong> $modalites</p>
                $salle_msg
                $url_msg
                $description_msg
            </div>
            <p class='footer'>Cordialement,<br>L'équipe ".self::$entreprise."</p>
        </body>
        </html>";


        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);                                 
        $mail->Subject = "$en_tete : $event_nom - $period";
        $mail->Body    = $message;
        $mail->send();
        return true;  
    } catch (Exception $e) {
        return $e->getMessage();
    } 
  }

  // nom,  id_types_event, id_centres_de_formation (pour admin, pour autres chargement auto), id_modalites
    // dateDebut, dateFin ou nbOccurences, heureDebut, heureFin, [jours] (format : 0, 1,2,4,etc. | 0=lundi, 1=mardi, etc.), [frequence] (nombre, week/month)
    // Champs facultatifs :  id_salles, url, description (pour admin : id_users), [event_sessions] (array des id_session), [event_users] (array des id_users)

  public static function sendEventRecurrentRecap($is_update, $email, $firstname, $event_nom, $dateDebut, $dateFin, $nbOccurences, $heureDebut, $heureFin, $jours, $frequence, $modalites, $salle, $url, $description, $is_cours, $formateur, $matiere){
    $link = self::$lien;
    $formatter = new IntlDateFormatter(
      'fr_FR',
      IntlDateFormatter::FULL,
      IntlDateFormatter::NONE,
      'Europe/Paris',
      IntlDateFormatter::GREGORIAN,
      'd MMMM yyyy'
    );

    $dateDebut = new DateTime($dateDebut);
    $debutDate = $formatter->format($dateDebut);
    $debutShortDate = $dateDebut->format('d-m-Y');

    $intervalle = "";
    if (!empty($dateFin)) {
        $dateFin = new DateTime($dateFin);
        $finDate = $formatter->format($dateFin);
        $finShortDate = $dateFin->format('d-m-Y');

        $intervalle = "À partir du $debutDate jusqu'au $finDate";
    }
    
    if(!empty($nbOccurences)){
      $recurrence_mode = "occurence";
      $intervalle = "À partir du $debutDate";
    }

    $horaires = "$heureDebut - $heureFin";
    $long_horaires = "De $heureDebut à $heureFin";

    $daysOfWeek = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
    $selectedDays = array_map(function($dayIndex) use ($daysOfWeek) {
      return $daysOfWeek[$dayIndex];
    }, $jours);
    $joursTxt = implode(', ', $selectedDays);
    $joursTxt = array_reduce($selectedDays, function($carry, $item) {
      return $carry === '' ? $item : $carry . ', ' . $item;
    }, '');

    $frequence_trad = "";
    if ($frequence[1] == "week" && $frequence[0] == 1) {
      $frequence_trad = 'Toutes les semaines';
      $frequence_avec_jours = 'Tous les '. $joursTxt;

    } elseif ($frequence[1] == "week" && $frequence[0] > 1){
      $frequence_trad = 'Toutes les '. $frequence[0] . ' semaines';
      $frequence_avec_jours = 'Les '.$joursTxt. ' toutes les '. $frequence[0]. ' semaines';

    } elseif ($frequence[1] == "month" && $frequence[0] == 1) {
        $frequence_trad = 'Tous les mois';
        $frequence_avec_jours = ucfirst($joursTxt) . ", une fois par mois";
    } elseif ($frequence[1] == "month" && $frequence[0] > 1) {
      $frequence_trad = 'Tous les '. $frequence[0] . ' mois';
      $frequence_avec_jours = ucfirst($joursTxt) . ' tous les '. $frequence[0] . ' mois';

  }

    $salle_msg = $modalites != "Distanciel" ? "<strong>Salle</strong> : $salle <br>" : "";
    $description_msg = $description != null ? "<strong>Description</strong> : $description <br>" : "";
    $url_msg = $url != null ? "<strong>Lien</strong> : $url <br>" : ""; 

    $en_tete = $is_update == true ? "Modifié" : "Invitation";
    $en_tete_details = $is_cours == true ? "Détails du cours" : "Détails de l'évènement";
    $txt_intro = $is_update == true ? "Un évènement a été mis à jour." : "Un nouvel évènement a été créé.";

    $matiere_msg = $is_cours == true ? "<p><strong>Matière :</strong> $matiere</p>" : "";
    $formateur_msg = $is_cours == true ? "<p><strong>Formateur :</strong> $formateur</p>" : "";

    try {
        $mail = new PHPMailer(true);     
        $mail->SMTPDebug = 0;                  
        $mail->isSMTP();                                         
        $mail->Host       = self::$host;                     
        $mail->SMTPAuth   = true;                                
        $mail->Username   = self::$username;                  
        $mail->Password   = self::$password;                              
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
        $mail->Port       = 465;    
        $mail->setFrom(self::$username, 'Administration '.self::$entreprise);
        $mail->addAddress($email);              
        $mail->addReplyTo(self::$username, 'Administration '.self::$entreprise);
        $mail->addCC(self::$username);
        $mail->addBCC(self::$username);

      
        $message = "
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    color: #333;
                    line-height: 1.6;
                }
                .container {
                    width: 100%;
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 0 20px 20px 20px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    background-color: #f9f9f9;
                }
                .header {
                    font-size: 20px;
                    font-weight: bold;
                    margin-bottom: 10px;
                }
                .content p {
                    margin: 10px 0;
                }
                .footer {
                    margin-top: 20px;
                    font-size: 14px;
                    color: #777;
                }
            </style>
        </head>
        <body>
            <p>Bonjour $firstname,</p>
            <p>$txt_intro</p>
            <div class='container'>
                <h3>$en_tete_details</h3>
                <p><strong>Nom de l'évènement :</strong> $event_nom</p>
                $matiere_msg
                $formateur_msg
                <p><strong>Date :</strong></p>
                <p>$intervalle</p>
                <p>$frequence_avec_jours</p>
                <p>$long_horaires</p>
                <p><strong>Modalités :</strong> $modalites</p>
                $salle_msg
                $url_msg
                $description_msg
            </div>
            <p class='footer'>Cordialement,<br>L'équipe ".self::$entreprise."</p>
        </body>
        </html>";




        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);                                 
        $mail->Subject = "$en_tete : $event_nom - $horaires - $frequence_trad";
        $mail->Body    = $message;
        $mail->send();
        return true;  
    } catch (Exception $e) {
        return $e->getMessage();
    } 
  }

  public static function sendCourseRecap($email, $firstname, $courseName, $dateDebut, $dateFin, $heureDebut, $heureFin, $jours, $frequence, $modalites, $salle, $url, $description, $formateur, $matiere, $isRecurrent) {
    $formatter = new IntlDateFormatter(
        'fr_FR',
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE,
        'Europe/Paris',
        IntlDateFormatter::GREGORIAN,
        'd MMMM yyyy'
    );

    $dateDebut = new DateTime($dateDebut);
    $debutDate = $formatter->format($dateDebut);
    $finDate = $dateFin ? $formatter->format(new DateTime($dateFin)) : '';

    $horaires = "$heureDebut - $heureFin";
    $periode = $isRecurrent ? "Du $debutDate au $finDate" : "Le $debutDate";

    $joursStr = $isRecurrent ? implode(', ', array_map(function($jour) {
        return ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'][$jour-1];
    }, $jours)) : '';

    $frequenceStr = $isRecurrent ? ($frequence[1] == 'week' ? "Toutes les {$frequence[0]} semaine(s)" : "Tous les {$frequence[0]} mois") : "";

    $salle_msg = $modalites != "Distanciel" ? "<strong>Salle</strong> : $salle <br>" : "";
    $url_msg = $url ? "<strong>Lien</strong> : $url <br>" : "";

    try {
        $mail = new PHPMailer(true);     
        $mail->SMTPDebug = 0;                  
        $mail->isSMTP();                                         
        $mail->Host       = self::$host;                     
        $mail->SMTPAuth   = true;                                
        $mail->Username   = self::$username;                  
        $mail->Password   = self::$password;                              
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
        $mail->Port       = 465;    
        $mail->setFrom(self::$username, 'Administration '.self::$entreprise);
        $mail->addAddress($email);              
        $mail->addReplyTo(self::$username, 'Administration '.self::$entreprise);
        $mail->addCC(self::$username);
        $mail->addBCC(self::$username);

        $message = "
        <html>
        <body>
            <p>Bonjour $firstname,</p>
            <p>Un nouveau cours a été ajouté à votre emploi du temps.</p>
            <div style='background-color: #f0f0f0; padding: 15px; border-radius: 5px;'>
                <h3>Détails du cours</h3>
                <p><strong>Nom du cours :</strong> $courseName</p>
                <p><strong>Matière :</strong> $matiere</p>
                <p><strong>Formateur :</strong> $formateur</p>
                <p><strong>Période :</strong> $periode</p>
                " . ($isRecurrent ? "<p><strong>Jours :</strong> $joursStr</p>" : "") . "
                <p><strong>Horaires :</strong> $horaires</p>
                " . ($isRecurrent ? "<p><strong>Fréquence :</strong> $frequenceStr</p>" : "") . "
                <p><strong>Modalités :</strong> $modalites</p>
                $salle_msg
                $url_msg
                <p><strong>Description :</strong> $description</p>
            </div>
            <p>Cordialement,<br>L'équipe ".self::$entreprise."</p>
        </body>
        </html>";

        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);                                 
        $mail->Subject = "Nouveau cours : $courseName";
        $mail->Body    = $message;
        $mail->send();
        return true;  
    } catch (Exception $e) {
        return $e->getMessage();
    } 
}

  // Non terminée pour le moment
public static function sendPointageRecap($email, $result){
    $link = self::$lien;
    $formatter = new IntlDateFormatter(
      'fr_FR',
      IntlDateFormatter::FULL,
      IntlDateFormatter::NONE,
      'Europe/Paris',
      IntlDateFormatter::GREGORIAN,
      'd MMMM yyyy'
    );

    $total_hours = $result['total_hours'];
    $monthly_hours = $result['monthly'];

    try {
        $mail = new PHPMailer(true);     
        $mail->SMTPDebug = 0;                  
        $mail->isSMTP();                                         
        $mail->Host       = self::$host;                     
        $mail->SMTPAuth   = true;                                
        $mail->Username   = self::$username;                  
        $mail->Password   = self::$password;                              
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
        $mail->Port       = 465;    
        $mail->setFrom(self::$username, 'Administration '.self::$entreprise);
        $mail->addAddress($email);              
        $mail->addReplyTo(self::$username, 'Administration '.self::$entreprise);
        $mail->addCC(self::$username);
        $mail->addBCC(self::$username);

      
        $message = "
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    color: #333;
                    line-height: 1.6;
                }
                .container {
                    width: 100%;
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 0 20px 20px 20px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    background-color: #f9f9f9;
                }
                .header {
                    font-size: 20px;
                    font-weight: bold;
                    margin-bottom: 10px;
                }
                .content p {
                    margin: 10px 0;
                }
                .footer {
                    margin-top: 20px;
                    font-size: 14px;
                    color: #777;
                }
            </style>
        </head>
        <body>
            <p>Bonjour $firstname,</p>
            <div class='container'>
                <h3>Récapitulatif de pointage</h3>
                <p><strong>Nom de l'évènement :</strong> $event</p>
               
            </div>
            <p class='footer'>Cordialement,<br>L'équipe LGX</p>
        </body>
        </html>";




        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);                                 
        $mail->Subject = "Récapitulatif du pointage de : ";
        $mail->Body    = $message;
        $mail->send();
        return true;  
    } catch (Exception $e) {
        return $e->getMessage();
    } 
  }
}