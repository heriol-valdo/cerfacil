<?php

require_once __DIR__ . '/../requestFile/authRequet.php';
require_once __DIR__ . '/LogoutController.php';

class HomeController {
    public function index() {


        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];


        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

             // Définition de roleName en fonction du role
             $roleName = '';
             switch($_SESSION['user']['role']){
                 case 1:{
                     $roleName = 'admin';
                     break;
                 }
                 case 2:{
                     $roleName = 'gestionnaire-entreprise';
                     break;
                 }
                 case 3:{
                     $roleName = 'gestionnaire-centre';
                     break;
                 }
                 case 4:{
                     $roleName = 'formateur';
                     break;
                 }
                 case 5:{
                     $roleName = 'etudiant';
                     break;
                 }
                 case 6:{
                     $roleName = 'financeur';
                     break;
                 }
                 default:{
                     if (!headers_sent()) {
                         $_SESSION = array();
                         session_destroy();
                         header("Location: /erp/");
                         exit(); 
                     }
                 }
             }
     
             // Inclus le home-content en fonction du role
             include("Views/$roleName/home-content.php");
        }

      
       
        
    }

    public function askPassword() {

        include("Views/user/ask-password.php");

      
  
    }

    public function resetPassword() {

        include("Views/user/reset-password.php");

      
  
    }

    public function profil() {


        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];


        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

             include("Views/user/profil.php");
        }
  
    }

    public static function getprofil(){
        $getUserProfil = sendRequest([],$_SESSION['userToken'],'user/profile','get');
        $userProfil = json_decode($getUserProfil);

        return $userProfil;

    }
    public function editPassword() {


        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];


        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

             include("Views/user/edit-password.php");
        }
  
    }
    

    public function editPasswordSend() {
        // Récupérer les données JSON de la requête
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        // Si les données ne sont pas au format JSON, utiliser $_POST comme fallback
        if (empty($data)) {
            $data = [
                'oldPassword' => $_POST['oldPassword'] ?? '',
                'newPassword' => $_POST['newPassword'] ?? ''
            ];
        }
        
        // Envoyer la requête au service
        $result = sendRequest($data, $_SESSION['userToken'], 'password/update', 'put');
        
        // Décoder la réponse
        $resultObj = json_decode($result);
        
        // Préparer la réponse
        $response = [];
        
        if (property_exists($resultObj, 'erreur')) {
            $response['erreur'] = $resultObj->erreur;
        }
        
        if (property_exists($resultObj, 'valid')) {
            $response['valid'] = $resultObj->valid;
        }
        
        // Envoyer la réponse au format JSON
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function updateProfil() {


        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];


        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

             include("Views/user/updateProfil.php");
        }
  
    }

    public function updateProfilSend() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = file_get_contents('php://input');
            $dataelement = json_decode($postData, true);
        
            $data = [];


            $userProfil = self::getprofil();
        
            if($_SESSION['user']['role'] == 1 || $_SESSION['user']['role'] == 2) {
                $data = [
                    //'newEmail'=> $dataelement['email'],
                    'newFirstname' => $dataelement['firstname'],
                    'newLastname' => $dataelement['lastname'],
                    'newTelephone'=> $dataelement['telephone'],
                    'newLieuTravail'=>$dataelement['lieu_travail']
                ];
            }
            if($_SESSION['user']['role'] == 3) {
                $data = [
                    //'newEmail'=> $dataelement['email'],
                    //'newFirstname' => $dataelement['firstname'],
                    //'newLastname' => $dataelement['lastname'],
                    'newTelephone'=> $dataelement['telephone'],
                ];
            }
            if($_SESSION['user']['role'] == 4) {
                $data = [
                    //'newEmail'=> $dataelement['email'],
                    //'newFirstname' => $dataelement['firstname'],
                    //'newLastname' => $dataelement['lastname'],
                    'newTelephone'=> $dataelement['telephone'],
                    'newAdressePostale'=>$dataelement['adressePostale'],
                    'newCodePostal'=>$dataelement['codePostal'],
                    'newVille'=>$dataelement['ville'],
                ];
            }
            if($_SESSION['user']['role'] == 5) {
         
                $data = [
                    //'newEmail'=> $dataelement['email'],
                    //'newAdressePostale'=>$dataelement['adressePostale'],
                    //'newCodePostal'=>$dataelement['codePostal'],
                    //'newVille'=>$dataelement['ville'],
                ];
            }
            if($_SESSION['user']['role'] == 6) {
                $data = [
                    //'newEmail'=> $dataelement['email'],
                    //'newFirstname' => $dataelement['firstname'],
                    //'newLastname' =>$dataelement['lastname'],
                ];
            }
        
        
            $result = sendRequest($data,$_SESSION['userToken'],"user/" . $userProfil->data->id_users . "/updateprofil"
            ,'put');
        
        
        
            $result = json_decode($result);
        
          
            
            if (property_exists($result, 'erreur')) {
                echo json_encode([
                    'erreur' => $result->erreur
                ]);
            }
            if (property_exists($result, 'valid')) {
                echo json_encode([
                    'valid' => $result->valid
                ]);
            }
        }
    }


    public function askPasswordSend() {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $postData = file_get_contents('php://input');
            $data = json_decode($postData, true);
            if(!isset($data['email'])){
                echo json_encode([
                    'erreur' => 'Email est obligatoire'
                ]);
                exit; 
            }
        
         
            $result = sendRequest($data, '','password/reset/request','post');
        
            $result = json_decode($result);
        
        
            if (property_exists($result, 'erreur')) {
                 echo json_encode([
                    'erreur' => $result->erreur
                ]);
                exit;
        
              
        
            }   
               
            if (property_exists($result, 'valid')) {
                echo json_encode([
                    'valid' => $result->valid
                ]);
                exit;
            }
        }
    }

    public function resetPasswordSend() {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $postData = file_get_contents('php://input');
            $data = json_decode($postData, true);
            if($data['newPassword'] != $data['confirmPassword']){
                echo json_encode([
                    'erreur' => "Le mot de passe de confirmation n'est pas identique"
                ]);
                exit; 
            }
        
        
            if(!isset($data['reset_token'])){
                echo json_encode([
                    'erreur_token' => "le est token Introuvable"
                ]);
                exit;  
            }
        
            
            
            
            $result = sendRequest($data, '',"password/reset/check",'put');
            $result = json_decode($result);
            
        
            if (property_exists($result, 'erreur')) {
                echo json_encode([
                   'erreur' => $result->erreur
               ]);
               exit;
            
             
           }   
              
           if (property_exists($result, 'valid')) {
               echo json_encode([
                   'valid' => $result->valid
               ]);
               exit;
           }
        }
    }

   

    public static function validToken() {

        $logOutIfTokenExpired = false;
        $logOutUserIfNotConnected = false ;
        // Vérifier l'expiration du token
        if (isset($_SESSION['user']['exp'])) {
            $expirationTime = $_SESSION['user']['exp'];
            $currentTime = time();
            
            if ($currentTime > $expirationTime || !isset($_SESSION) || empty($_SESSION)) {
                // Token expiré ou session invalide
                $_SESSION = array();
                $logOutIfTokenExpired = true;
               
            }
        }
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            $_SESSION = [];
            $logOutUserIfNotConnected = true;
        }
        
        // Si on arrive ici, le token est valide
        return [
            'logOutIfTokenExpired' => $logOutIfTokenExpired,
            'logOutUserIfNotConnected' => $logOutUserIfNotConnected
        ];
    }

}
?>