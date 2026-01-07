<?php

require_once __DIR__ . '/../requestFile/authRequet.php';
require_once __DIR__ . '/LogoutController.php';

class GestionnaireCentreFormationController{
    public function index() {


        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];


        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

             include("Views/gestionnaire-centre/index.php");
        }

    }

    public static function ListGestionnaireCentreFormation() {
        $result = sendRequest([],$_SESSION['userToken'],"getGestionnaire",'get');

        $result = json_decode($result);

        if(!empty($result)){
            if (property_exists($result, 'erreur')) {
                $allgestionnaires = "";
            }
            if (property_exists($result, 'valid')) {
                $allgestionnaires =$result->data;
            }
        } 

        return $allgestionnaires ;
    }

    public function save(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            $postData = file_get_contents('php://input');
            $decoded = json_decode($postData, true);
        
            function genererMotDePasse() {
        
                $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                $longueur = 10;
                $motDePasse = '';
        
                for ($i = 0; $i < $longueur; $i++) {
                    $motDePasse .= $caracteres[rand(0, strlen($caracteres) - 1)];
                }
                return $motDePasse;
            }
            $motDePasseGenere = genererMotDePasse();
        
            $requiredFields = ['email', 'firstname', 'lastname', 'id_centres_de_formation'];
            $fieldTranslations = [
                'email' => 'Adresse email',
                'firstname' => 'Prénom',
                'lastname' => 'Nom de famille',
                'id_centres_de_formation' => 'Centre de formation'
            ];
            
            $error_empty = "Champs requis : ";
            $is_error_empty = false;
            foreach ($requiredFields as $field){
                if(empty($_POST[$field])){
                    $translatedField = $fieldTranslations[$field] ?? $field;
                    $error_empty .=  "$translatedField, ";
                    $is_error_empty = true;
                }   
            }
            if($is_error_empty){
                $error_empty = rtrim($error_empty, ', ');
                echo json_encode([
                    'erreur' => $error_empty
                ]);
                exit;
            }
        
            // Trim + regex si besoin
            $email = trim($_POST['email']);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode([
                    'erreur' => "L'email n'est pas au bon format"
                ]);
                exit;
            } 
        
            $firstname = trim($_POST['firstname']);
            if(empty($firstname) ||  !preg_match("/^([a-zA-ZÀ-ÿ' -]+)$/u",$firstname)){
                echo json_encode([
                    'erreur' => "Veuillez entrer un prénom valide"
                ]);
                exit;
            }
        
            $lastname = trim($_POST['lastname']);
            if(empty($lastname) ||  !preg_match("/^([a-zA-ZÀ-ÿ' -]+)$/u",$lastname)){
                echo json_encode([
                    'erreur' => "Veuillez entrer un nom valide"
                ]);
                exit;
            }
        
            $telephone = trim($_POST['telephone']);
            if(!empty($telephone) && !preg_match("/^[0-9 ]+$/",$telephone)){
                echo json_encode([
                    'erreur' => "Veuillez entrer un téléphone valide"
                ]);
                exit;
            }
        
            $data = [
                'email'=> $_POST['email'],
                'password' => $motDePasseGenere,
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'id_centres_de_formation'=>$_POST['id_centres_de_formation'],
                'telephone' =>$_POST['telephone'],
            ];
            
        
           
            $result = sendRequest($data,$_SESSION['userToken'],"addUser/gestionnaireCentre",'post');
            $result = json_decode($result);
        
            if (property_exists($result, 'erreur')) {
             
                echo json_encode([
                    'erreur' => $result->erreur
                ]);
                exit;
            }
        
        
            if (property_exists($result, 'valid')) {
                echo json_encode([
                    'valid' => $result->valid,
                    
                ]);
                exit;
            }
        }
    }

    public function update(){
       
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            
            $postData = file_get_contents('php://input');
            $decoded = json_decode($postData, true);


            if (!isset($decoded['id']) || !isset($decoded['email']) || !isset($decoded['firstname']) || !isset($decoded['lastname']) || !isset($decoded['telephone']) || !isset($decoded['centre'])) {
                echo json_encode([
                    'erreur' => 'Certains champs ne sont pas remplis'
                ]);
                exit;
            }

            $id = trim($decoded['id']);
            $email = trim($decoded['email']);
            $firstname = trim($decoded['firstname']);
            $lastname = trim($decoded['lastname']);
            $telephone = trim($decoded['telephone']);
            $centre = trim($decoded['centre']);


            $data = [
                'newEmail' => $email,
                'newFirstname' => $firstname,
                'newLastname' => $lastname,
                'newTelephone' => $telephone,
                'newCentre' => $centre,
            ];
        
            $url = "admin/gestionnaireCentre/". $id ."/update";
            $result = sendRequest($data, $_SESSION['userToken'], $url, 'PUT');
            $result = json_decode($result);


            if (property_exists($result, 'erreur')) {
                echo json_encode([
                    'erreur' => $result->erreur,
                ]);
                exit;
            }

            if (property_exists($result, 'valid')) {
                echo json_encode([
                    'valid' => $result->valid,
                ]);
                exit;
            }

        }
    }

    public function delete(){
        $postData = file_get_contents('php://input');
        $decoded = json_decode($postData, true);

        $id = trim($decoded['id']);
        
        $url = "user/". $id ."/delete";
        $result = sendRequest([], $_SESSION['userToken'], $url, 'DELETE');
        $result = json_decode($result);

        if (property_exists($result, 'erreur')) {
            echo json_encode([
                'erreur' => $result->erreur,
            ]);
            exit;
        }

        if (property_exists($result, 'valid')) {
            echo json_encode([
                'valid' => $result->valid,
            ]);
            exit;
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