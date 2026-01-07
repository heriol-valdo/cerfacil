<?php

require_once __DIR__ . '/../requestFile/authRequet.php';
require_once __DIR__ . '/LogoutController.php';

class EntrepriseController {


     public static function getEntreprises($identreprise) {


        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];


        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{


               $result = sendRequest(["id"=>$identreprise],$_SESSION['userToken'],'entrepriseFind', 'post');
               $result= json_decode( $result);


                if (property_exists($result, 'erreur')) {
                    return [];
                  }else if(property_exists($result, 'valid')) {
                    return   $result->data;

            }
             
        }

      
       
        
    }

    public function index() {


        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];


        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

             include("Views/entreprise/index.php");
        }

      
       
        
    }

    public static function ListEntreprise() {
        $result = sendRequest([],$_SESSION['userToken'],"getEntreprise",'get');

        $result = json_decode($result);

        $erreur ="";

            if (property_exists($result, 'valid')) {
                if (property_exists($result, 'data') && is_array($result->data)) {
                    $allentreprises = $result->data;
                } else {
                    $erreur =  "La propriété 'data' est manquante ou invalide.\n";
                }
            } elseif (property_exists($result, 'erreur')) {
            
                $erreur = $result->erreur . "\n";
            } else {
                $erreur = "La réponse ne contient ni 'valid' ni 'erreur'.\n";
            }
        

        return $allentreprises;

    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $files = $_FILES;
        
            $requiredFields = [
                'siret', 'nomEntreprise', 'nomDirecteur', 'adressePostale', 'codePostal', 
                'ville', 'telephone', 'ape', 'intracommunautaire', 'soumis_tva', 
                'domaineActivite', 'formeJuridique', 'email'
            ];
        
            // Validation des champs obligatoires
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || trim($data[$field]) === '') {
                    echo json_encode(['erreur' => "Le champ $field est obligatoire."]);
                    exit;
                }
            }
        
            // Conversion des checkboxes en booléens
            $is_financeur =  isset($data['is_financeur']) && !empty($data['is_financeur']) ? 1 : 0;
            $is_accueil =  isset($data['is_accueil']) && !empty($data['is_accueil']) ? 1 : 0;
        
           

          
        
            try {
                // Préparation des données multipart
                $multipartData = [
                    ['name' => 'siret', 'contents' => $data['siret']],
                    ['name' => 'nomEntreprise', 'contents' => $data['nomEntreprise']],
                    ['name' => 'nomDirecteur', 'contents' => $data['nomDirecteur']],
                    ['name' => 'adressePostale', 'contents' => $data['adressePostale']],
                    ['name' => 'codePostal', 'contents' => $data['codePostal']],
                    ['name' => 'ville', 'contents' => $data['ville']],
                    ['name' => 'telephone', 'contents' => $data['telephone']],
                    ['name' => 'ape', 'contents' => $data['ape']],
                    ['name' => 'intracommunautaire', 'contents' => $data['intracommunautaire']],
                    ['name' => 'soumis_tva', 'contents' => $data['soumis_tva']],
                    ['name' => 'domaineActivite', 'contents' => $data['domaineActivite']],
                    ['name' => 'formeJuridique', 'contents' => $data['formeJuridique']],
                    ['name' => 'siteWeb', 'contents' => $data['siteWeb'] ?? ''],
                    ['name' => 'fax', 'contents' => $data['fax'] ?? ''],
                    ['name' => 'email', 'contents' => $data['email']],
                    ['name' => 'is_accueil', 'contents' => $is_accueil],
                    ['name' => 'is_financeur', 'contents' => $is_financeur],
                    ['name' => 'id_centres_de_formation', 'contents' => $data['id_centres_de_formation'] ?? '0']
                ];
        
                // Gestion du logo
                $logoData = [
                    'name' => 'logo', 
                    'contents' => ''
                ];
        
                if (!empty($files['logo']) && $files['logo']['error'] == 0) {
                    $filePath = $files['logo']['tmp_name'];
                    $fileName = basename($files['logo']['name']);
        
                    if (file_exists($filePath)) {
                        $logoData = [
                            'name' => 'logo', 
                            'contents' => fopen($filePath, 'r'), 
                            'filename' => $fileName
                        ];
                    }
                }
        
                $multipartData[] = $logoData;

                $result = sendRequests($multipartData,$_SESSION['userToken'],"admin/entreprise/add",'post');

                
                // Décodage sécurisé du JSON
                $result = json_decode($result, true);
        
        
                // Gestion de la réponse
                if (isset($result['erreur'])) {
                    echo json_encode(['erreur' => $result['erreur']]);
                }
        
                if (isset($result['valid'])) {
                    echo json_encode(['valid' => $result['valid']]);
                }
        
             } catch (Exception $e) { 
                 echo json_encode([
                    'erreur' => 'Erreur lors de la communication avec le serveur : ' . $e->getMessage()
                ]);
            }
        }
    }

    public function update() {
        // Validate request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->sendErrorResponse('Méthode de requête non autorisée');
        }
    
        // Collect input data
        $data = $_POST;
        $files = $_FILES;
    
        // Define required fields
        $requiredFields = [
            'siret', 'nomEntreprise', 'nomDirecteur', 'adressePostale', 'codePostal', 
            'ville', 'telephone', 'ape', 'intracommunautaire', 
            'domaineActivite', 'formeJuridique', 'email'
        ];
    
        // Validate required fields
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return $this->sendErrorResponse("Le champ $field ne peut pas être vide");
            }
        }
    
        // Prepare optional flags
        $is_financeur = isset($data['is_financeur']) && !empty($data['is_financeur']) ? 1 : 0;
        $is_accueil = isset($data['is_accueil']) && !empty($data['is_accueil']) ? 1 : 0;
    
        // Prepare multipart data
        $multipartData = $this->prepareMultipartData($data, $is_financeur, $is_accueil);
    
        // Handle logo upload
        if (!empty($files['logo']) && $files['logo']['error'] === UPLOAD_ERR_OK) {
            $logoPath = $files['logo']['tmp_name'];
            $logoName = basename($files['logo']['name']);
    
            if (!file_exists($logoPath)) {
                return $this->sendErrorResponse('Fichier logo introuvable');
            }
    
            $multipartData[] = [
                'name' => 'logo', 
                'contents' => fopen($logoPath, 'r'), 
                'filename' => $logoName
            ];
        } elseif (empty($files) || $files['logo']['error'] === UPLOAD_ERR_NO_FILE) {
            // No logo uploaded, add empty logo field
            $multipartData[] = [
                'name' => 'logo', 
                'contents' => ''
            ];
        }
    
        try {
            // Send update request
            $result = sendRequests(
                $multipartData, 
                $_SESSION['userToken'], 
                "admin/entreprise/{$data['entrepriseId']}/update", 
                'post'
            );
    
            // Decode and process result
            $resultData = json_decode($result, true);
    
            if (isset($resultData['erreur'])) {
                return $this->sendErrorResponse($resultData['erreur']);
            }
    
            if (isset($resultData['valid'])) {
                return $this->sendSuccessResponse($resultData['valid']);
            }
    
            // Unexpected response
            return $this->sendErrorResponse('Réponse inattendue du serveur');
    
        } catch (Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }
    
    // Helper method to send error responses
    private function sendErrorResponse($message) {
        header('Content-Type: application/json');
        echo json_encode(['erreur' => $message]);
        exit;
    }
    
    // Helper method to send success responses
    private function sendSuccessResponse($message) {
        header('Content-Type: application/json');
        echo json_encode(['valid' => $message]);
        exit;
    }
    
    // Prepare multipart data for request
    private function prepareMultipartData($data, $is_financeur, $is_accueil) {
        return [
            ['name' => 'siret', 'contents' => $data['siret']],
            ['name' => 'nomEntreprise', 'contents' => $data['nomEntreprise']],
            ['name' => 'nomDirecteur', 'contents' => $data['nomDirecteur']],
            ['name' => 'adressePostale', 'contents' => $data['adressePostale']],
            ['name' => 'codePostal', 'contents' => $data['codePostal']],
            ['name' => 'ville', 'contents' => $data['ville']],
            ['name' => 'telephone', 'contents' => $data['telephone']],
            ['name' => 'ape', 'contents' => $data['ape']],
            ['name' => 'intracommunautaire', 'contents' => $data['intracommunautaire']],
            ['name' => 'soumis_tva', 'contents' => $data['soumis_tva']],
            ['name' => 'domaineActivite', 'contents' => $data['domaineActivite']],
            ['name' => 'formeJuridique', 'contents' => $data['formeJuridique']],
            ['name' => 'siteWeb', 'contents' => $data['siteWeb'] ?? ''],
            ['name' => 'fax', 'contents' => $data['fax'] ?? ''],
            ['name' => 'email', 'contents' => $data['email']],
            ['name' => 'is_accueil', 'contents' => $is_accueil],
            ['name' => 'is_financeur', 'contents' => $is_financeur],
            ['name' => 'id_centres_de_formation', 'contents' => $data['id_centres_de_formation'] ?? '']
        ];
    }

    public function delete(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $postData = file_get_contents('php://input');
            $decoded = json_decode($postData, true);
        
            $id = trim($decoded['id']);
            
        
            $url = "admin/entreprise/". $id ."/delete";
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