<?php

require_once __DIR__ . '/../requestFile/authRequet.php';
require_once __DIR__ . '/LogoutController.php';

class CentreFormationController {
    public function index() {


        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];


        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

             include("Views/centre-formation/index.php");
        }

      
       
        
    }

    public static function ListCentreFormation() {
        $result = sendRequest([],$_SESSION['userToken'],"admin/centreFormation/liste",'get');

        $result = json_decode($result);

        if (property_exists($result, 'valid')) {
            if (property_exists($result, 'data') && is_array($result->data)) {
                $allcentres = $result->data;
            } else {
                $erreur =  "La propriété 'data' est manquante ou invalide.\n";
            }
        } elseif (property_exists($result, 'erreur')) {
        
            $erreur = $result->erreur . "\n";
        } else {
            $erreur = "La réponse ne contient ni 'valid' ni 'erreur'.\n";
        }
       
        return $allcentres;

    }

    public function save(){
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $postData = $_POST;
           
            if (!isset($postData['nomCentre']) ||!isset($postData['adresseCentre']) ||!isset($postData['codePostalCentre']) ||!isset($postData['villeCentre']) ||!isset($postData['idEntrepriseCentre'])) {
                echo json_encode([
                    'erreur' => 'Certains champs ne sont pas remplis'
                ]);
                exit;
            }
        
            $data = [
                'nomCentre'=> $postData['nomCentre'],
                'adresseCentre' => $postData['adresseCentre'],
                'codePostalCentre' => $postData['codePostalCentre'],
                'villeCentre' => $postData['villeCentre'],
                'idEntrepriseCentre' => $postData['idEntrepriseCentre'],
                'telephoneCentre' => !empty($postData['telephoneCentre']) ? $postData['telephoneCentre'] : NULL
        
            ];
            
        
           
            $result = sendRequest($data,$_SESSION['userToken'],"admin/centreFormation/add",'post');
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
            $data = $_POST;
        
            $requiredSetFields = ['centreId', 'centreNom', 'centreAdresse', "centreCodePostal", "centreVille",  "centreEntrepriseId"];
            foreach ($requiredSetFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    echo json_encode(['erreur' => "Le champ $field est obligatoire."]);
                    exit;
                }
            }
        
            $centreId = trim($data['centreId']);
            $centreNom = trim($data['centreNom']);
            $centreAdresse = trim($data['centreAdresse']);
            $centreCodePostal = trim($data['centreCodePostal']);
            $centreVille = trim($data['centreVille']);
            $centreTelephone = trim($data['centreTelephone']);
            $id_entreprises = trim($data['centreEntrepriseId']);
        
            $data = [
                'newNom' => $centreNom,
                'newAdresse' => $centreAdresse,
                'newCodePostal' => $centreCodePostal,
                'newVille' => $centreVille,
                'newTelephone' => $centreTelephone,
                'newEntreprise' => $id_entreprises,
            ];
        
            $apiRoute = "admin/centreFormation/" . $centreId . "/update";
        
            try {
                $result = sendRequest($data, $_SESSION['userToken'], $apiRoute, 'PUT');
                $result = json_decode($result);
        
                if (property_exists($result, 'erreur')) {
                    echo json_encode(['erreur' => $result->erreur]);
                } elseif (property_exists($result, 'valid')) {
                    echo json_encode(['valid' => $result->valid]);
                }
                exit;
        
            } catch (ClientException $e) {
                    echo json_encode(['erreur' =>$e->getMessage()]);
                exit;
            }
        }
    }

    public function delete(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $postData = file_get_contents('php://input');
            $decoded = json_decode($postData, true);
        
            $id = trim($decoded['id']);
            
        
            $url = "admin/centreFormation/". $id ."/delete";
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