<?php

require_once __DIR__ . '/../requestFile/authRequet.php';
require_once __DIR__ . '/LogoutController.php';

class AdminsController {
    public function index() {


        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];


        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

             include("Views/admin/index.php");
        }

      
       
        
    }

    public static function ListAdmins() {
        $result = sendRequest([],$_SESSION['userToken'],"getAdmin",'get');

        $result = json_decode($result);

        if (property_exists($result, 'valid')) {
            if (property_exists($result, 'data') && is_array($result->data)) {
                $alladmins = $result->data;
            } else {
                $erreur =  "La propriété 'data' est manquante ou invalide.\n";
            }
        } elseif (property_exists($result, 'erreur')) {
        
            $erreur = $result->erreur . "\n";
        } else {
            $erreur = "La réponse ne contient ni 'valid' ni 'erreur'.\n";
        }
        


        return $alladmins;
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
           
            if (!isset($decoded['email']) ||!isset($decoded['firstname']) ||!isset($decoded['lastname'])||!isset($decoded['telephone']) ||!isset($decoded['lieu_travail'])) {
                echo json_encode([
                    'erreur' => 'Certains champs requis ne sont pas présents'
                ]);
                exit;
            }
        
            if (empty($decoded['email']) || empty($decoded['firstname']) || empty($decoded['lastname'])) {
                echo json_encode([
                    'erreur' => 'Veuillez remplir tous les champs obligatoires'
                ]);
                exit;
            }
        
            // Trim + Regex si besoin
            $lieu_travail = trim($decoded['lieu_travail']);
            $email = trim($decoded['email']);
            $firstname = trim($decoded['firstname']);
            $lastname = trim($decoded['lastname']);
            $telephone = trim($decoded['telephone']);
            
            $data = [
                'email'=> $email,
                'password' => $motDePasseGenere,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'lieu_travail'=>$lieu_travail,
                'telephone' => $telephone,
            ];
            
        
           
            $result = sendRequest($data,$_SESSION['userToken'],"addUser/admin",'post');
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
        
        
            if (!isset($decoded['id']) || !isset($decoded['email']) || !isset($decoded['firstname']) || !isset($decoded['lastname']) || !isset($decoded['telephone']) || !isset($decoded['lieu_travail'])) {
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
            $lieu_travail = trim($decoded['lieu_travail']);
        
        
            $data = [
                'newEmail' => $email,
                'newFirstname' => $firstname,
                'newLastname' => $lastname,
                'newTelephone' => $telephone,
                'newLieuTravail' => $lieu_travail,
            ];
        
            $url = "admin/". $id ."/updateAdmin";
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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