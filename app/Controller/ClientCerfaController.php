<?php

require_once __DIR__ . '/../requestFile/authRequet.php';
require_once __DIR__ . '/LogoutController.php';

class ClientCerfaController {
    public function index() {


        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];


        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

             include("Views/clientCerfa/index.php");
        }

      
       
        
    }

    public static function ListClientCerfa() {
        $result = sendRequest([],$_SESSION['userToken'],"clientCerfa",'get');

        $result = json_decode($result);

        if(!empty($result)){
            if (property_exists($result, 'erreur')) {
                $allclients = "";
            }
            if (property_exists($result, 'valid')) {
                $allclients= $result;
            }
        } 

        return $allclients;
    }

    public function save(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            $postData = file_get_contents('php://input');
            $decoded = json_decode($postData, true);
        
            function genererMotDePasse() {
        
                // Définition des jeux de caractères
                    $lowercase = 'abcdefghijklmnopqrstuvwxyz';
                    $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $numbers = '0123456789';
                    $specialChars = '@#$!';
                    $length = 12;
        
                    // Combinaison de tous les jeux de caractères
                    $allChars = $lowercase . $uppercase . $numbers . $specialChars;
        
                    // S'assurer que le mot de passe contiendra au moins un de chaque type requis
                    $password = [];
                    $password[] = $uppercase[random_int(0, strlen($uppercase) - 1)];
                    $password[] = $numbers[random_int(0, strlen($numbers) - 1)];
                    $password[] = $specialChars[random_int(0, strlen($specialChars) - 1)];
        
                    // Remplir le reste du mot de passe
                    for ($i = 3; $i < $length; $i++) {
                        $password[] = $allChars[random_int(0, strlen($allChars) - 1)];
                    }
        
                    // Mélanger le tableau de caractères pour plus de sécurité
                    shuffle($password);
        
                    // Retourner le mot de passe en tant que chaîne de caractères
                    return implode('', $password);
                
            }
            $motDePasseGenere = genererMotDePasse();
           
            if (!isset($decoded['email']) ||!isset($decoded['firstname']) ||!isset($decoded['lastname']) || !isset($decoded['adressePostale']) ||!isset($decoded['codePostal']) 
                ||!isset($decoded['ville']) ||!isset($decoded['telephone'])) {
                echo json_encode([
                    'erreur' => 'Certains champs ne sont pas remplis'
                ]);
                exit;
            }
        
            $email = trim($decoded['email']);
            $firstname = trim($decoded['firstname']);
            $lastname = trim($decoded['lastname']);
            $adressePostale = trim($decoded['adressePostale']);
            $codePostal = trim($decoded['codePostal']);
            $ville = trim($decoded['ville']);
            $telephone = trim($decoded['telephone']);
        
            $data = [
                'email'=> $email,
                'password' => $motDePasseGenere,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'adressePostale'=> $adressePostale,
                'codePostal'=>$codePostal,
                'ville'=>$ville,
                'telephone'=>$telephone,
                'idCreation'=>1,
                'roleCreation'=>1
               
            ];
            
        
           
            $result = sendRequest($data,$_SESSION['userToken'],"addUser/clientCerfa",'post');
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
        
        
            if (!isset($decoded['id']) || 
            !isset($decoded['firstname']) || 
            !isset($decoded['lastname']) || 
            !isset($decoded['email']) || 
            !isset($decoded['adresse']) || 
            !isset($decoded['codePostal']) || 
            !isset($decoded['ville']) || 
            !isset($decoded['telephone']) 
            ) {
            
            echo json_encode([
                'erreur' => 'Certains champs ne sont pas remplis'
            ]);
            exit;
             }
        
        
            $id = trim($decoded['id']);
            $email = trim($decoded['email']);
            $firstname = trim($decoded['firstname']);
            $lastname = trim($decoded['lastname']);
            $adresse = trim($decoded['adresse']);
            $codePostal = trim($decoded['codePostal']);
            $ville = trim($decoded['ville']);
            $telephone = trim($decoded['telephone']);
           
        
        
           
            $data = [
                'newEmail' => $email,
                'newFirstname' => $firstname,
                'newLastname' => $lastname,
                'newAdressePostale' => $adresse,
                'newCodePostal' => $codePostal,
                'newVille' => $ville,
                'newTelephone' => $telephone,
               
            ];
        
            $url = "admin/clientCerfa/".$id."/update" ;
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

    public function detailsAchatsClientCerfa(){
        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];

        if(isset($_POST['clientId'])) {
            $_SESSION['current_client_id'] = $_POST['clientId'];
        }

        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

             include("Views/clientCerfa/detailsAchatsClientCerfa.php");
        }

    }

    public function detailsCerfasClientCerfa(){
        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];

        if(isset($_POST['clientId'])) {
            $_SESSION['current_client_id'] = $_POST['clientId'];
        }

        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

             include("Views/clientCerfa/detailsCerfasClientCerfa.php");
        }

    }

    public static function ListAchatClientCerfa() {
       
            
        $selectedClient =   $_SESSION['current_client_id'];
        $data = [
            'id_users' => $selectedClient 
            
           
        ];
        $result = sendRequest($data,$_SESSION['userToken'],"achatClientCerfa",'post');
        
        $result = json_decode($result);
        
        
        if(!empty($result)){
            if (property_exists($result, 'erreur')) {
                $allclients = "";
            }
            if (property_exists($result, 'valid')) {
                $allclients= $result;
            }
        } 

        return $allclients;
    }

    public static function ListCerfasClientCerfa() {
        // Vérifie si l'ID du client est défini dans la session
        if (!isset($_SESSION['current_client_id'])) {
            return [
                'error' => true,
                'message' => "ID du client non défini dans la session",
                'cerfas' => []
            ];
        }
    
        $clientId = $_SESSION['current_client_id'];
        
        // Envoie la requête à l'API
        $response = sendRequest(["id"=>$clientId ], $_SESSION['userToken'], "cerfaSearchClient", 'post');
        $result = json_decode($response);
        
        // Initialisation des variables de retour
        $allcerfas = [];
        $error = false;
        $message = "";
        
        // Vérification et traitement de la réponse
        if (!empty($result)) {
            if (property_exists($result, 'valid')) {
                if (property_exists($result, 'data')) {
                    $allcerfas = $result->data;
                } else {
                    $error = true;
                    $message = "La propriété 'data' est manquante ou invalide.";
                }
            } elseif (property_exists($result, 'erreur')) {
                $error = true;
                $message = $result->erreur;
            } else {
                $error = true;
                $message = "La réponse ne contient ni 'valid' ni 'erreur'.";
            }
        } else {
            $allcerfas = [];
        }
        
        // Retourne un tableau structuré avec toutes les informations nécessaires
        return [
            'error' => $error,
            'message' => $message,
            'cerfas' => $allcerfas
        ];
    }

    public static function ListInfoEmployeur($idEntreprise = null, $idformation = null){
        $result = sendRequest(["id" =>$idEntreprise],$_SESSION['userToken'],"entrepriseFind",'post');

          
            
            $result = json_decode($result);
            
                if (!empty($result)) {
        
                if (property_exists($result, 'valid')) {
                    
                    if (property_exists($result, 'data')) {
                        $allels = $result->data;
                       return  $allels;
                    } else {
                        return  "La propriété 'data' est manquante ou invalide.\n";
                    }
                } elseif (property_exists($result, 'erreur')) {
                    return  $result->erreur . "\n";
                } else {
                    return "La réponse ne contient ni 'valid' ni 'erreur'.\n";
                }
            } else {
            
                return   "La réponse est vide ou n'est pas un objet JSON valide.\n";
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