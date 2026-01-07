<?php

require_once __DIR__ . '/../requestFile/authRequet.php';
require_once __DIR__ . '/LogoutController.php';

class CerfaController {

    public   static $tabetatcerfa = [
        'TRANSMIS' => '<span class="label label-success">Statut : TRANSMIS</span>',
        'EN_COURS_INSTRUCTION' => '<span class="label label-info">Statut : EN COURS D\'INSTRUCTION</span>',
        'ENGAGE' => '<span class="label label-primary">Statut : ENGAGÉ</span>',
        'ANNULE' => '<span class="label label-danger">Statut : ANNULÉ</span>',
        'SOLDE' => '<span class="label label-default">Statut : SOLDÉ</span>',
        'REFUSE' => '<span class="label label-warning">Statut : REFUSÉ</span>',
        'NONTROUVERS' => '<span class="label label-danger">Statut : NUMÉRO INTERNE NON TROUVÉ</span>',
        '' => '<span class="label label-danger">Statut : PAS ENVOYÉ</span>',
        'RUPTURE' => '<span class="label label-danger">Statut : RUPTURE</span>',
        'AUCUN' => '<span class="label label-warning">Statut : AUCUN_OPCO</span>',
    ];

    public function update_cerfa(){
        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];
        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Vérifier si c'est une requête AJAX
                    $postData = file_get_contents('php://input');
                    $data = json_decode($postData, true);
                    
                    // Format de réponse attendu
                    // Récupération des données du formulaire (par exemple via AJAX ou $_POST)
                    $formData = $data['data'] ?? [];

                    if (empty($formData)) {
                         echo json_encode([
                            'success' => false,
                            'message' => 'Aucune donnée de formulaire reçue',
                            'timestamp' => date('Y-m-d H:i:s'),
                        ]);
                        exit;
                    }
                    
                    // Stockage en session
                    $_SESSION['idDossier'] =$formData['id'];

                    // Champs obligatoires à valider (tu peux en ajouter selon ton besoin)
                    $requiredFields = ['nomA', 'prenomA','id', 'sexeA', 'naissanceA', 'departementA', 'communeNA', 'nationaliteA', 'regimeA', 'situationA', 
                    'titrePA', 'derniereCA', 'securiteA', 'intituleA', 'titreOA', 'declareSA', 'declareHA', 'declareRA',  'voieA', 'postalA', 'communeA','numeroA',
                    ];
                    $missingFields = [];

                    foreach ($requiredFields as $field) {
                        if (empty($formData[$field])) {
                            $missingFields[] = $field;
                        }
                    }

                    if (!empty($missingFields)) {
                        echo json_encode([
                            'success' => false,
                            'message' => 'Champs manquants : ' . implode(', ', $missingFields),
                            'timestamp' => date('Y-m-d H:i:s'),
                        ]);
                         exit;
                    }

                // Construction du tableau $data à partir des données reçues
                    $data = [
                        'id' => $formData['id'],
                        'nomA' => $formData['nomA'] ?? '',
                        'nomuA' => $formData['nomuA'] ?? '',
                        'prenomA' => $formData['prenomA'] ?? '',
                        'sexeA' => $formData['sexeA'] ?? '',
                        'naissanceA' => $formData['naissanceA'] ?? '',
                        'departementA' => $formData['departementA'] ?? '',
                        'communeNA' => $formData['communeNA'] ?? '',
                        'nationaliteA' => $formData['nationaliteA'] ?? '',
                        'regimeA' => $formData['regimeA'] ?? '',
                        'situationA' => $formData['situationA'] ?? '',
                        'titrePA' => $formData['titrePA'] ?? '',
                        'derniereCA' => $formData['derniereCA'] ?? '',
                        'securiteA' => $formData['securiteA'] ?? '',
                        'intituleA' => $formData['intituleA'] ?? '',
                        'titreOA' => $formData['titreOA'] ?? '',
                        'declareSA' => $formData['declareSA'] ?? '',
                        'declareHA' => $formData['declareHA'] ?? '',
                        'declareRA' => $formData['declareRA'] ?? '',
                        'rueA' => $formData['rueA'] ?? '',
                        'voieA' => $formData['voieA'] ?? '',
                        'complementA' => $formData['complementA'] ?? '',
                        'postalA' => $formData['postalA'] ?? '',
                        'communeA' => $formData['communeA'] ?? '',
                        'numeroA' => $formData['numeroA'] ?? '',

                        'nomR' => $formData['nomR'] ?? '',
                        'prenomR' => $formData['prenomR'] ?? '',
                        'emailR' => $formData['emailR'] ?? '',
                        'rueR' => $formData['rueR'] ?? '',
                        'voieR' => $formData['voieR'] ?? '',
                        'complementR' => $formData['complementR'] ?? '',
                        'postalR' => $formData['postalR'] ?? '',
                        'communeR' => $formData['communeR'] ?? ''
                    ];


                    $result = sendRequest($data, $_SESSION['userToken'],'updateCerfaByFormInformationApprenti', 'post');
                    $result = json_decode($result);

                    // Préparation de la réponse
                    $response = [
                        'valid' => false,
                        'data' => null,
                        'error' => null
                    ];

                    if (property_exists($result, 'erreur')) {
                        $response['error'] = $result->erreur;
                    }

                    if (property_exists($result, 'valid')) {
                        $response['valid'] = true;
                        $response['data'] = $result->valid;
                    }

                    // Réponse JSON finale
                    echo json_encode([
                        'success' => $response['valid'],
                        'message' => $response['valid'] ? 'Données sauvegardées avec succès' : 'Erreur lors de la sauvegardes',
                        'data' => $data,
                        'timestamp' => date('Y-m-d H:i:s'),
                        'error' => $response['error']
                    ]);

                
            } else {
                 header('Location: /app/');
                 exit;
            }
            
        }

    }

   
    public static function getCerfas() {


        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];


        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

             $getUserProfil = sendRequest([],$_SESSION['userToken'],'user/profile','get');
             $userProfil = json_decode($getUserProfil);

          

               $result = sendRequest(["emailA"=>$userProfil->data->email],$_SESSION['userToken'],'cerfaByEmail', 'post');
               $result= json_decode( $result);


                if (property_exists($result, 'erreur')) {
                    return [];
                  }else if(property_exists($result, 'valid')) {
                    return   $result->data;

            }
             
        }
  
    }

      public static function getCerfasEntreprise() {


        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];


        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

             $getUserProfil = sendRequest([],$_SESSION['userToken'],'user/profile','get');
             $userProfil = json_decode($getUserProfil);

          

               $result = sendRequest(["emailE"=>$userProfil->data->email],$_SESSION['userToken'],'entrepriseByEmail', 'post');
               $result= json_decode( $result);


                if (property_exists($result, 'erreur')) {
                    return $result->debug;
                  }else if(property_exists($result, 'valid')) {
                    return   $result->data;

            }
             
        }
  
    }

    public static function getCerfasbyId($id){

        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];


        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

               $result = sendRequest(["id"=>$id],$_SESSION['userToken'],'cerfaFind', 'post');
               $result= json_decode( $result);


                if (property_exists($result, 'erreur')) {
                    return [];
                  }else if(property_exists($result, 'valid')) {
                    return   $result->data;

            }
             
        }
    }



    public  static  function getEtatCerfa($numeroInterne, $idopco){

        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];  
        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

                if(empty($numeroInterne)){
                    return self::$tabetatcerfa[''];

                }else{
                     if(!empty($numeroInterne) && $idopco== null || $idopco == 'null'  || $idopco == 0 || $idopco == '0' || $idopco == ''){
                        return self::$tabetatcerfa['AUCUN'];
                    }else{
                        $opco = self::getOpco($idopco);
                        if (property_exists( $opco, 'erreur')) {
                            return self::$tabetatcerfa['AUCUN'];
                        }else if(property_exists( $opco, 'valid')) {
                            $etat = etat($numeroInterne,  $opco->data,4);
                            return self::$tabetatcerfa[$etat] ?? '<span class="label label-default">Statut :INCONNU'.$etat.'</span>';
                        }
                    }
                    
                }
              

            }
             
    }
    public static function getOpco($idopco){
        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];
        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{
            $result = sendRequest(["id"=>$idopco],$_SESSION['userToken'],'opcoFind', 'post');
            $result= json_decode( $result); 
            
            return $result;
        } 

    }

    
   public  function etat($numeroInterne, $opco,$type) {
    // Initialiser cURL
    $ch = curl_init();

    // Données pour obtenir le token
    $post_data = [
        'grant_type' => 'client_credentials',
        'client_id' => $opco->clid,
        'client_secret' => $opco->clse,
        'scope' => 'api.read api.write'
    ];

    // Configurer cURL pour obtenir le token
    curl_setopt($ch, CURLOPT_URL, $opco->lienT);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_POST, true);

    // Exécuter la requête pour obtenir le token
    $response = curl_exec($ch);

    // Vérifier les erreurs cURL
    if (curl_errno($ch)) {
        $error = 'Erreur cURL lors de l\'obtention du token : ' . curl_error($ch);
        curl_close($ch);
        return $error;
    }

    // Décoder la réponse pour récupérer le token
    $result = json_decode($response, true);
    if (!isset($result['access_token'])) {
        curl_close($ch);
        return 'Erreur : Impossible d\'obtenir le token d\'accès';
    }
    $access_token = $result['access_token'];

    // Configurer cURL pour obtenir les informations du dossier
 
    $url = $opco->lienE . "?numeroInterne=" .$numeroInterne ;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'accept: application/json',
        'EDITEUR: LGX-CREATION',
        'LOGICIEL: LGX-CERFA',
        'VERSION: 1.0.0',
        "Authorization: Bearer $access_token",
        "X-API-KEY: $opco->cle"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    // Exécuter la requête pour obtenir les informations du dossier
    $response = curl_exec($ch);

    // Vérifier les erreurs cURL
    if (curl_errno($ch)) {
        $error = 'Erreur cURL lors de l\'obtention des informations du dossier : ' . curl_error($ch);
        curl_close($ch);
        return $error;
    }

    // Obtenir le code de statut HTTP
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Fermer cURL
    curl_close($ch);

    // Analyser la réponse
    $responseJson = json_decode($response, true);

    switch ($httpCode) {
        case 200:
            if($type===1){ return $responseJson['echeances'];}
            elseif($type===2){ return $responseJson['detailsFacturation'];}
            elseif($type===3){ return $responseJson['engagementsFraisAnnexe'];}
            elseif($type===4){ return $responseJson['cerfa']['etat'] ?? 'INCONNU';}
           
        case 400:
        case 401:
        case 404:
        case 403:
        case 500:
            $error = isset($responseJson['errors']) ? $responseJson['errors'] : "Erreur : " . $httpCode;
            return $error;
        default:
            return "Erreur inattendue : " . $httpCode;
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