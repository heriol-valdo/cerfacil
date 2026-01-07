<?php


namespace Projet\Controller\Scolarite;



use DOMDocument;
use DOMXPath;
use Projet\Controller\Admin\AdminsController;

use Projet\Database\Cerfa;
use Projet\Database\Entreprise;
use Projet\Database\Formation;
use Projet\Database\Opco;
use Projet\Model\App;

use Exception;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\Tcpdf\Fpdi;


use Projet\Database\Produit;
use Projet\Database\Profil;
use Projet\Database\Alternant;
use Projet\Database\Abonnement;
use Projet\Model\StringHelper;
use Projet\Model\Paginator;
use Projet\Model\JWTHandler;
use DateTime;


class CerfaController extends AdminsController
{
    public function index(){
        //Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        $user = $this->user;
        
        $nbreParPage = 10;
        // nouveau
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $search = isset($_POST['searchcerfas']) && !empty($_POST['searchcerfas']) ? $_POST['searchcerfas'] : null;
            $this->session->write('searchcerfas', $search); // Enregistrer la recherche dans la session
            
            // Récupérer la page envoyée et l'enregistrer dans la session
            $pageCourante = isset($_POST['page']) && is_numeric($_POST['page']) ? (int)$_POST['page'] : 1;
            $this->session->write('pageCourantecerfas', $pageCourante);
            
            // Rediriger pour éviter la soumission multiple de formulaire
            header("Location: " . App::url('cerfas'));
            exit;
        }
        
        // Vérifier si une recherche est en session
        $search = $this->session->exists('searchcerfas') ? $this->session->read('searchcerfas') : null;
        
        
        
        // Vérifier si la page courante est en session
        $pageCourante = $this->session->exists('pageCourantecerfas') ? $this->session->read('pageCourantecerfas') : 1;

        $nbre = Cerfa::countBySearchType($search);
        $nbrePages = ceil($nbre / $nbreParPage);
       
        $items = Cerfa::searchType($nbreParPage,$pageCourante,$search);
        $employeurs = Entreprise::searchType(null);
        $formations = Formation::searchType(null);
       

        
        $abonnements = Abonnement::searchType();
        $abonnements = $abonnements['data'];
        
        $hasType1Product = false;
    
        if (!empty($abonnements)) {
            foreach ($abonnements as $abonnement) {
                $tableauproduits = Produit::find($abonnement->id_produit);
                $tableauproduit = $tableauproduits['data'];
    
                if ($tableauproduit->type == 1) {
                    $dateCourante  = date("Y-m-d");
                    $hasType1Product = ($dateCourante > $abonnement->date_fin)?false : true;
                    break; 
                }
            }
        }
    
        if ($hasType1Product) {
            $this->render('admin.scolarite.cerfa',compact('search','user','nbre','nbrePages','items','employeurs','formations','pageCourante'));
        } else {
            $nbreadmins = Profil::countBySearchType($search);
            $nbreformations = Formation::countBySearchType($search);
            $nbreemployeurs = Entreprise::countBySearchType($search);
            $nbreopco = Opco::countBySearchType($search);
            $nbrecerfas = Cerfa::countBySearchType($search);
            $nbreproduit = Produit::searchType();
            $produits = $nbreproduit['data'];
            $nbreproduits =  count($produits);
            $_SESSION['page_active'] = 'home';
            $current = date(DATE_FORMAT);
            $this->render('admin.user.index',compact('user','current','nbreadmins','nbrecerfas','nbreemployeurs','nbreformations','nbreopco','nbreproduits'));
        }
    }

   
    public function indexDetails() {
        $user = $this->user;
        $nbreParPage = 10;
        $search = isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : null;
        $nbre = Cerfa::countBySearchType($search);
        $nbrePages = ceil($nbre / $nbreParPage);
        $pageCourante = isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbrePages ? $_GET['page'] : 1;
        $params['page'] = $pageCourante;
    
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = isset($_POST['data']) && !empty($_POST['data']) ? $_POST['data'] : null;
            $this->session->write('data', $data);
            
            // Redirect to avoid resubmitting the form on page reload
            header("Location: " . App::url('cerfasdetails'));
            exit; // Important to prevent further script execution
        } else {
            $data = $this->session->read('data');
        }
    
        // Retrieve data as before
        $id = $data;
        $items = Cerfa::find($id);
        $items = $items['data'];
        $allemployeurs = Entreprise::searchType(null);
        $allformations = Formation::searchType(null);
        $employeurs = Entreprise::find($items->idemployeur);
        $employeurs = $employeurs['data'];
        $result = Formation::find($items->idformation);
        $formations = $result['valid'] ? $result['data'] : "";
        $resultopco = Opco::find($employeurs->idopco);
        $opco = $resultopco['valid'] ? $resultopco['data'] : "";
    
        $this->render('admin.scolarite.cerfa_detail', compact('search', 'user', 'nbre', 'opco', 'nbrePages', 'items', 'employeurs', 'formations','allemployeurs','allformations'));
        exit;
    }

    public function changeEntreprise() {
        //Privilege::hasPrivilege(Privilege::$AllView, $this->user->privilege);
        header('Content-Type: application/json');
    
        if (isset($_POST['idElementchangeentreprise']) && !empty($_POST['idElementchangeentreprise'])) {
            $id = $_POST['idElementchangeentreprise'];
            $idEntreprise = $_POST['idchangeentreprise'];
            $save = Cerfa::setEntreprise($id,$idEntreprise);
           
            if ($save['valid']){
                $message = $save['data'];
                //$this->session->write('success',$message);
                $return = array("statuts"=>0, "mes"=>$message);
                
            }else{
                $message = $save['error'];
                $return = array("statuts"=>1, "mes"=>$message);
            }
        } else {
            $message = "Veuillez renseigner l'ID.";
            $return = array("statuts"=>1, "mes"=>$message);
        }
    
        echo json_encode($return);
    }

    public function changeFormation() {
        //Privilege::hasPrivilege(Privilege::$AllView, $this->user->privilege);
        header('Content-Type: application/json');
    
        if (isset($_POST['idElementchangeformation']) && !empty($_POST['idElementchangeformation'])) {
            $id = $_POST['idElementchangeformation'];
            $idFormation = $_POST['idchangeformation'];
            $save = Cerfa::setFormation($id,$idFormation);
           
            if ($save['valid']){
                $message = $save['data'];
                //$this->session->write('success',$message);
                $return = array("statuts"=>0, "mes"=>$message);
                
            }else{
                $message = $save['error'];
                $return = array("statuts"=>1, "mes"=>$message);
            }
        } else {
            $message = "Veuillez renseigner l'ID.";
            $return = array("statuts"=>1, "mes"=>$message);
        }
    
        echo json_encode($return);
    }

    
    // new fonction 

    function getNumeroDeca($numeroInterne,$opco){
        // Initialiser cURL
        $ch = curl_init();
  
        // Données pour obtenir le token
        $post_data = [
            'grant_type' => 'client_credentials',
            'client_id' => $opco['clid'],
            'client_secret' => $opco['clse'],
            'scope' => ($opco['nom'] !== "EP") ? 'api.read api.write' : null
        ];

        if (isset($post_data['scope']) && $post_data['scope'] === null) {
            unset($post_data['scope']);
        }
    
        // Configurer cURL pour obtenir le token
        curl_setopt($ch, CURLOPT_URL, $opco['lienT']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($ch, CURLOPT_POST, true);
    
        // Exécuter la requête pour obtenir le token
        $response = curl_exec($ch);
    
       

        

        
        if($opco['nom'] === "AFDAS"){
            $access_token = $this->obtenirTokenCaches($opco['clid'],$opco['clse'],$opco['lienT']);
        }else{ // Vérifier les erreurs cURL
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
        }
    
        // Configurer cURL pour obtenir les informations du dossier
     
        curl_setopt($ch, CURLOPT_URL, $opco['lienE']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'accept: application/json',
            'EDITEUR: LGX-CREATION',
            'LOGICIEL: LGX-CERFA',
            'VERSION: 1.0.0',
            "Authorization: Bearer $access_token",
            "X-API-KEY: " .$opco['cle']
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
        $responseJsons = json_decode($response, true);
        //var_dump( $responseJsons);
        switch ($httpCode) {
            case 200:
                // Vérifier si $responseJsons est un tableau
                if (!is_array($responseJsons)) {
                    return "Erreur : La réponse n'est pas un tableau.";
                }

                $response = "";
                foreach ($responseJsons as $responseJson) {
                    if (is_array($responseJson) && isset($responseJson['numeroInterne']) && $responseJson['numeroInterne'] == $numeroInterne) {
                        $response = $responseJson['numeroDeca'] ?? null;
                        break;
                    }
                }

                // Traitement spécifique pour AFDAS
                if ($opco["nom"] === "AFDAS") {
                    $response = isset($responseJson['numeroDeca']) ? $responseJson['numeroDeca'] : null;
                } else {
                    return $response;
                }
                //var_dump( $response);
               
            case 400:
            case 401:
            case 404:
            case 403:
            case 500:
                $error = isset($responseJsons['errors']) ? $responseJsons['errors'] : "Erreur : " . $httpCode;
                return $error;
            default:
                return "Erreur inattendue : " . $httpCode;
        }
  
  }
  
  //generer token DPOP pour AFDAS
  function genererDPoPToken($url, $method) {

    $filePath  = PATH_FILE;
    $filePath .=  '/public/' . 'assets/pdf/private-key.pem';
    // En-tête du JWT pour DPoP
    $header = [
        "alg" => "RS256", 
        "typ" => "dpop+jwt"
          
    ];

    // Création du payload (charge utile) du DPoP token
    $payload = [
        "jti" => bin2hex(random_bytes(16)), // ID unique du token
        "htm" => $method,  // Méthode HTTP (GET, POST, etc.)
        "htu" => $url,     // URI cible
        "iat" => time()    // Horodatage de la création du token
    ];

    // Lire la clé privée en format PEM pour signer le JWT
    $privateKey = file_get_contents($filePath);
    if (!$privateKey) {
        throw new Exception("La clé privée n'a pas pu être lue.");
    }

   // Créer le token en appelant la méthode de la classe JWTHandler
    try {
        return JWTHandler::createToken($payload, $privateKey, $header);
    } catch (\Exception $e) {
        throw new \Exception('Erreur lors de la création du token : ' . $e->getMessage());
    }
}

// obtenir le token pour AFDAS
function obtenirTokenCaches($clid, $clse, $lienT) {
    static $tokens = []; // Cache pour les tokens par client_id

    // Vérifier si un token est déjà en cache pour ce client
    if (isset($tokens[$clid])) {
        return $tokens[$clid]; // Retourner le token en cache
    }

    // Générer le DPoP token
    $dpopToken = $this->genererDPoPToken($lienT, 'POST');
    //var_dump($result); // Debug pour voir la réponse complète

  

    // Initialiser la requête cURL pour obtenir le token d'accès
    $ch = curl_init();
    $post_data = [
        'grant_type' => 'client_credentials',
        'client_id' => $clid,
        'client_secret' => $clse
      
    ];

    // Configurer cURL avec les bonnes options
    curl_setopt($ch, CURLOPT_URL, $lienT); // URL du serveur d'authentification
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retourner la réponse sous forme de chaîne
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data)); // Paramètres du POST
    curl_setopt($ch, CURLOPT_POST, true); // Type de la requête : POST
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
        "DPoP: $dpopToken" // Ajouter le token DPoP dans l'en-tête
    ]);
    curl_setopt($ch, CURLOPT_VERBOSE, true);


    // Exécution de la requête cURL
    $response = curl_exec($ch);
    curl_close($ch);

    // Vérifier si une erreur cURL s'est produite
    if ($response === false) {
        throw new Exception("Erreur cURL : " . curl_error($ch));
    }

    // Décoder la réponse JSON
    $result = json_decode($response, true);
    //var_dump($result); // Debug pour voir la réponse complète

    // Vérifier la présence du token d'accès dans la réponse
    $access_token = $result['access_token'] ?? null;

    // Si un token est trouvé, le stocker dans le cache
    if ($access_token) {
        $tokens[$clid] = $access_token;
    }

    return $access_token; // Retourner le token d'accès
}

  function etats($numeroInterne, $opco,$type) {
      // Initialiser cURL
      $ch = curl_init();
  
      // Données pour obtenir le token
      $post_data = [
          'grant_type' => 'client_credentials',
          'client_id' => $opco['clid'],
          'client_secret' => $opco['clse'],
          'scope' => ($opco['nom'] !== "EP") ? 'api.read api.write' : null
      ];
      if (isset($post_data['scope']) && $post_data['scope'] === null) {
        unset($post_data['scope']);
    }
  
      // Configurer cURL pour obtenir le token
      curl_setopt($ch, CURLOPT_URL, $opco['lienT']);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
      curl_setopt($ch, CURLOPT_POST, true);
  
      // Exécuter la requête pour obtenir le token
      $response = curl_exec($ch);
  
     

      if($opco['nom'] == "AFDAS"){
        $access_token = $this->obtenirTokenCaches($opco['clid'],$opco['clse'],$opco['lienT']);
      }else{
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
      }
     
     
  
      // Configurer cURL pour obtenir les informations du dossier
   
      $url = $opco['lienCe'] . "?numeroInterne=" .$numeroInterne ;
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
          'Content-Type: application/json',
          'accept: application/json',
          'EDITEUR: LGX-CREATION',
          'LOGICIEL: LGX-CERFA',
          'VERSION: 1.0.0',
          "Authorization: Bearer $access_token",
          "X-API-KEY:". $opco['cle']
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
      //var_dump($responseJson );
  
      switch ($httpCode) {
          case 200:
              if($type==1){ return $responseJson['echeances'];}
              elseif($type==2){ return $responseJson['detailsFacturation'];}
              elseif($type==3){ return $responseJson['engagementsFraisAnnexe'];}
              elseif($type==4){ return $responseJson['cerfa']['etat'];}
             
          case 400:
          case 401:
          case 404:
          case 403:
          case 500:
            if($opco['nom'] == "EP"){
                if($httpCode === 400){
                    $error = "NONTROUVERS";
                    return $error;
                }else{
                    $error = isset($responseJson['errors']) ? $responseJson['errors'] : "Erreur : " . $responseJson['comment']; 
                    return $error;
                }

            }else{
                $error = isset($responseJson['errors']) ? $responseJson['errors'] : "Erreur : " . $httpCode;
                return $error;
            }
             
          default:
              return "Erreur inattendue : " . $httpCode;
      }
  }

 
  
  function getEtatLabels($numeroInterne, $opco,$type) {
      
      if (empty($numeroInterne)) {
          return StringHelper::$tabetatcerfa[''];
      }
      try {
          $etat = $this->etats($numeroInterne, $opco,$type);
          
  
          if($type === 1){
              if (is_array($etat) && isset($etat[0]['dateOuverture'])) {
             
                  return  $etat;
              }
      
              if (is_array($etat) && !isset($etat[0]['dateOuverture'])) {
                  // Convertir les informations des échéances en chaîne de caractères lisible
                  
                  return json_encode($etat);
              }
              
          }elseif($type === 2){
  
              if (is_array($etat) && isset($etat['fraisPremierEquipementRegles'])) {
             
                  return  $etat;
              }
              
              if (is_array($etat) && !isset($etat['fraisPremierEquipementRegles'])) {
                  // Convertir les informations des échéances en chaîne de caractères lisible
                  
                  return json_encode($etat);
              }
              
              
          }elseif($type === 3){
              if (is_array($etat) && isset($etat[0]['natureFrais'])) {
             
                  return  $etat;
              }
      
              if (is_array($etat) && !isset($etat[0]['natureFrais'])) {
                  // Convertir les informations des échéances en chaîne de caractères lisible
                  
                  return json_encode($etat);
              }
  
          }elseif($type === 4){
              if (is_array($etat)) {
                  // Log l'erreur pour le débogage
                  error_log("Erreur lors de la récupération de l'état pour le numéro interne $numeroInterne : " . print_r($etat, true));
                  return '<span class="label label-danger">Erreurs' . htmlspecialchars(json_encode($etat)) . '</span>';
              }
              
              // Vérifier si l'état retourné est une erreur
              if (is_string($etat) && strpos($etat, 'Erreur') === 0) {
                  // Log l'erreur pour le débogage
                  error_log("Erreur lors de la récupération de l'état pour le numéro interne $numeroInterne : $etat");
                  return '<span class="label label-danger">ERREUR: ' . htmlspecialchars($etat) . '</span>';
              }
              
              return StringHelper::$tabetatcerfa[$etat] ?? '<span class="label label-default">ÉTAT INCONNU: ' . htmlspecialchars($etat) . '</span>';
          }  
      } catch (Exception $e) {
          error_log("Exception lors de la récupération de l'état pour le numéro interne $numeroInterne : " . $e->getMessage());
          return '<span class="label label-danger">ERREUR SYSTÈME</span>';
      }
  }

  public function getNumeroDecas() {
    //Privilege::hasPrivilege(Privilege::$AllView, $this->user->privilege);
    header('Content-Type: application/json');

    if (isset($_POST['numeroInterne']) && !empty($_POST['numeroInterne'])) {
        $numeroInterne = $_POST['numeroInterne'];
        $opco = $_POST['opco'];
    
        // Appel de la fonction PHP pour obtenir le numeroDeca
        $numeroDeca = $this->getNumeroDeca($numeroInterne, $opco);
    
        // Retourner la réponse JSON
        $return = array('numeroDeca' => $numeroDeca);
    } else {
        $message = "Veuillez renseigner numeroInternes.";
        $return = array("statuts"=>1, "mes"=>$message);
    }

    echo json_encode($return);
}

public function getEtatLabelss() {
    //Privilege::hasPrivilege(Privilege::$AllView, $this->user->privilege);
    header('Content-Type: application/json');
    
    if (isset($_POST['numeroInterne']) && !empty($_POST['numeroInterne'])) {
        $numeroInterne = $_POST['numeroInterne'];
        $opco = $_POST['opco'];
        $type = $_POST['type'];

       
        // Appel de la fonction PHP pour obtenir l'état
        $etatLabel = $this->etats($numeroInterne, $opco, $type);
    
        //var_dump(  $etatLabel);
        
        $return = array("statuts"=>0,"mes"=> $etatLabel);
    } else {
        $message = "Veuillez renseigner numeroInterne.";
        $return = array("statuts"=>0, "mes"=>"");
    }

    echo json_encode($return);
}

public function updateNumeroDeca() {
    header('Content-Type: application/json');

    $numeroDeca = $_POST['numeroDeca'] ?? null;
    $itemId = $_POST['itemId'] ?? null;

    if (!$numeroDeca || !$itemId) {
        echo json_encode(['success' => false, 'message' => 'Paramètres manquants.']);
        return;
    }

    $save = Cerfa::setNumeroDeca($numeroDeca, $itemId);

    if ($save['valid']) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $save['error']]);
    }
}

  // details cerfa plus haut 
  // list cerfa plus bas

    public function obtenirTokenCache($clid, $clse, $lienT) {
        static $tokens = []; // Cache pour les tokens par client_id
    
        if (isset($tokens[$clid])) {
            return $tokens[$clid];
        }
    
        // Initialiser cURL
        $ch = curl_init();
        $post_data = [
            'grant_type' => 'client_credentials',
            'client_id' => $clid,
            'client_secret' => $clse,
            'scope' => 'api.read api.write'
        ];
        curl_setopt($ch, CURLOPT_URL, $lienT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($ch, CURLOPT_POST, true);
    
        // Exécuter la requête
        $response = curl_exec($ch);
        curl_close($ch);
    
        // Extraire le token
        $result = json_decode($response, true);
        $access_token = $result['access_token'] ?? null;
        if ($access_token) {
            $tokens[$clid] = $access_token;
        }
        return $access_token;
    }
    
    public  function etat($numeroInterne, $token, $lienE, $cle) {
        // Initialiser cURL
        $ch = curl_init();
        $url = $lienE . "?numeroInterne=" . $numeroInterne;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'accept: application/json',
            'EDITEUR: LGX-CREATION',
            'LOGICIEL: LGX-CERFA',
            'VERSION: 1.0.0',
            "Authorization: Bearer $token",
            "X-API-KEY: $cle"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        $responseJson = json_decode($response, true);
        if ($httpCode === 200 && $responseJson['cerfa']["numeroInterne"] == $numeroInterne) {
            return $responseJson['cerfa']['etat'];
        }
        return ($httpCode === 404) ? "NONTROUVERS" : "Erreur : $httpCode";

            // if ($httpCode === 200 && $responseJson['cerfa']["numeroInterne"] == $numeroInterne) {
            //     return $responseJson['cerfa']['etat'];
            // }
            // $errorMessages = [];
            // if (isset($responseJson['errors']) && is_array($responseJson['errors'])) {
            //     foreach ($responseJson['errors'] as $error) {
            //         // Si $error est un tableau, on le convertit en chaîne de caractères JSON
            //         if (is_array($error)) {
            //             $errorMessages[] = json_encode($error);
            //         } else {
            //             $errorMessages[] = $error; // Sinon, on l'ajoute directement
            //         }
            //     }
            //     // Combinaison des messages d'erreur en une seule chaîne
            //     $error = "Erreur $httpCode : " . implode(', ', $errorMessages);
            // } else {
            //     $error = "Erreur $httpCode";
            // }
            // return $error;
    }
    
    public function getEtatLabel($numeroInterne, $ligneopco) {
        if (empty($numeroInterne)) {
            return StringHelper::$tabetatcerfa[''];
        }
    
        $token = $this->obtenirTokenCache($ligneopco['data']->clid, $ligneopco['data']->clse, $ligneopco['data']->lienT);
        if (!$token) {
            return '<span class="label label-danger">ERREUR: Impossible d\'obtenir le token</span>';
        }
    
        $etat = etat($numeroInterne, $token, $ligneopco['data']->lienCe, $ligneopco['data']->cle);
        return StringHelper::$tabetatcerfa[$etat] ?? '<span class="label label-default">ÉTAT INCONNU: ' . htmlspecialchars($etat) . '</span>';
    }

    public function getControllerEtat(){
        //Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        header('content-type: application/json');
        if (isset($_POST['numeroInterne'])&&!empty($_POST['numeroInterne'])){
            $numeroInterne = $_POST['numeroInterne'];
            $id = $_POST['id'];
            $lignecerfa = Cerfa::find($id);
            $ligneentreprise = Entreprise::find($lignecerfa['data']->idemployeur);
            $ligneopco = Opco::find($ligneentreprise['data']->idopco); // Obtenir les informations opco nécessaires
            $token = $this->obtenirTokenCache($ligneopco['data']->clid, $ligneopco['data']->clse, $ligneopco['data']->lienT);
            if ($token) {
                $etat = $this->etat($numeroInterne, $token, $ligneopco['data']->lienCe, $ligneopco['data']->cle);
                $etat_label = StringHelper::$tabetatcerfa[$etat] ?? '<span class="label label-warning" style="border-radius: 5px;border: 0px solid #ccc;">Statut : ÉTAT INCONNU: ' . htmlspecialchars($etat) . '</span>';
                echo json_encode(['etat_label' => $etat_label]);
            } else {
                echo json_encode(['etat_label' => '<span class="label label-warning" style="border-radius: 5px;border: 0px solid #ccc;">Statut :Erreur de token</span>']);
            }
            
        }else{
            echo json_encode(['etat_label' => StringHelper::$tabetatcerfa[""] ]);
        }
       
    }

    // fin new function 


    public function updateCerfaContrat()
    {
        header('content-type: application/json');
        $return = [];
        $tab = ["add", "edit"];
    
        if (
            isset($_POST['action']) && !empty($_POST['action']) &&
            isset($_POST['id']) && in_array($_POST["action"], $tab)
        ) {
            $nomM = $_POST['nomM'];
            $prenomM = $_POST['prenomM'];
            $naissanceM = $_POST['naissanceM'];
            $securiteM = $_POST['securiteM'];
            $emailM = $_POST['emailM'];
            $emploiM = $_POST['emploiM'];
            $diplomeM = $_POST['diplomeM'];
            $niveauM = $_POST['niveauM'];
    
            $nomM1 = $_POST['nomM1'];
            $prenomM1 = $_POST['prenomM1'];
            $naissanceM1 = $_POST['naissanceM1'];
            $securiteM1 = $_POST['securiteM1'];
            $emailM1 = $_POST['emailM1'];
            $emploiM1 = $_POST['emploiM1'];
            $diplomeM1 = $_POST['diplomeM1'];
            $niveauM1 = $_POST['niveauM1'];
    
            $travailC = $_POST['travailC'];
            $modeC = $_POST['modeC'];
            $derogationC = $_POST['derogationC'];
            $numeroC = $_POST['numeroC'];
            $conclusionC = $_POST['conclusionC'];
            $debutC = $_POST['debutC'];
            $finC = $_POST['finC'];
            $avenantC = $_POST['avenantC'];
            $executionC = $_POST['executionC'];
            $dureC = $_POST['dureC'];
            $dureCM = $_POST['dureCM'];
            $typeC = $_POST['typeC'];
            $rdC = $_POST['rdC'];
            $raC = $_POST['raC'];
            $rpC = $_POST['rpC'];
            $rsC = $_POST['rsC'];
            $rdC1 = $_POST['rdC1'];
            $raC1 = $_POST['raC1'];
            $rpC1 = $_POST['rpC1'];
            $rsC1 = $_POST['rsC1'];
    
            $rdC2 = $_POST['rdC2'];
            $raC2 = $_POST['raC2'];
            $rpC2 = $_POST['rpC2'];
            $rsC2 = $_POST['rsC2'];
    
            $salaireC = $_POST['salaireC'];
            $caisseC = $_POST['caisseC'];
            $logementC = $_POST['logementC'];
            $avantageC = $_POST['avantageC'];
            $autreC = $_POST['autreC'];
    
            $lieuO = $_POST['lieuO'];
            $priveO = $_POST['priveO'];
            $attesteO = $_POST['attesteO'];
    
    
    
            
            $action = $_POST['action'];
            $id = $_POST['id'];
    
            if ($action == "edit") {
                if (!empty($id)) {
                    $cerfa = Cerfa::find($id);
    
                    if ($cerfa['valid']) {
                        $bool = true;
    
                        if(!empty($naissanceA) && empty($salaireC)){
                            $smic = $this->smic();
                            $smic = str_replace(',', '.', $smic);
    
    
                            
    
                            $dateAujourdhui = date("Y-m-d");
    
                            $dateNaissanceObj = date_create($naissanceA);
                            $dateAujourdhuiObj = date_create($dateAujourdhui);
                        
                            // Calcul de l'âge
                            $diff = date_diff($dateNaissanceObj, $dateAujourdhuiObj);
                            $age = $diff->y;
                        
                            if ($age < 18) {
                                if(  $dateAujourdhui >= $rdC && $dateAujourdhui <= $raC){
                                    $salaireC = 0.27 * $smic;
                                    $rpC =27;
                                    $rpC1 ='';
                                    $rpC2 ='';
                                }elseif($dateAujourdhui >= $rdC1 && $dateAujourdhui <= $raC1 ){
                                    $salaireC = 0.39 * $smic;
                                    $rpC1 =39;
                                    $rpC ='';
                                    $rpC2 ='';
                                }elseif( $dateAujourdhui >= $rdC2 && $dateAujourdhui <= $raC2){
                                    $salaireC = 0.55 * $smic;
                                    $rpC2 =55;
                                    $rpC1 ='';
                                    $rpC ='';
                                }else{
                                    $salaireC = 0.27 * $smic;
                                    $rpC =27;
                                    $rpC1 ='';
                                    $rpC2 ='';
                                }
                             
                            } elseif ($age >= 18 && $age <= 20) {
                                if(  $dateAujourdhui >= $rdC && $dateAujourdhui <= $raC){
                                    $salaireC = 0.43 * $smic;
                                    $rpC =43;
                                    $rpC1 ='';
                                    $rpC2 ='';
                                }elseif($dateAujourdhui >= $rdC1 && $dateAujourdhui <= $raC1 ){
                                    $salaireC = 0.51 * $smic;
                                    $rpC1 =51;
                                    $rpC ='';
                                    $rpC2 ='';
                                }elseif( $dateAujourdhui >= $rdC2 && $dateAujourdhui <= $raC2){
                                    $salaireC = 0.67 * $smic;
                                    $rpC2 =67;
                                    $rpC1 ='';
                                    $rpC ='';
                                }else{
                                    $salaireC = 0.43 * $smic;
                                    $rpC =43;
                                    $rpC1 ='';
                                    $rpC2 ='';
                                }   
                             
                                
                            } elseif ($age >= 21 && $age <= 25) {
    
                                if( $dateAujourdhui >= $rdC && $dateAujourdhui <= $raC){
                                    $salaireC = 0.53 * $smic;
                                    $rpC =53;
                                    $rpC1 ='';
                                    $rpC2 ='';
                                }elseif($dateAujourdhui >= $rdC1 && $dateAujourdhui <= $raC1 ){
                                    $salaireC = 0.61 * $smic;
                                    $rpC1 =61;
                                    $rpC ='';
                                    $rpC2 ='';
                                }elseif( $dateAujourdhui >= $rdC2 && $dateAujourdhui <= $raC2 ){
                                    $salaireC = 0.78 * $smic;
                                    $rpC2 =78;
                                    $rpC1 ='';
                                    $rpC ='';
                                }else{
                                    $rpC =53;
                                    $salaireC = 0.53 * $smic;
                                    $rpC1 ='';
                                    $rpC2 ='';
                                }
                               
                            } else {
                                $salaireC = $smic;
                                $rpC =100;
                                $rpC1 =100;
                                $rpC2 =100;
                            }
                        }
    
    
    
    
                        if ($bool) {
                            try {
                                $save=Cerfa::updateCerfaContrat(
                                $nomM,$prenomM,$naissanceM,$securiteM,$emailM,$emploiM,$diplomeM,$niveauM, 
                                $nomM1,$prenomM1,$naissanceM1,$securiteM1,$emailM1,$emploiM1,$diplomeM1,$niveauM1, 
                                $travailC,$derogationC,$numeroC,$conclusionC,$debutC,$finC,$avenantC,$executionC,$dureC,$typeC,
                                $rdC,$raC,$rpC,$rsC,
                                $rdC1,$raC1,$rpC1,$rsC1,
                                $rdC2,$raC2,$rpC2,$rsC2,
                                $salaireC,$caisseC,$logementC,$avantageC,$autreC,
                                $lieuO,$priveO,$attesteO,$modeC,$dureCM,
                                $id);
                               
                                if ($save['valid']) {
                                    $message = "La cerfa a été mise à jour avec succès";
                                    $this->session->write('success', $message);
                                    $return = array("statuts" => 0, "mes" => $message);
                                } else {
                                    $message = $save['error'];
                                    $return = array("statuts" => 1, "mes" => $message);
                                }
                            } catch (Exception $e) {
                                $message = $e->getMessage();
                                $return = array("statuts" => 1, "mes" => $message);
                            }
                        } else {
                            $message = "Le cerfa avec cet email existe déjà";
                            $return = array("statuts" => 1, "mes" => $message);
                        }
                    } else {
                        $message = $cerfa['error'];
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                } else {
                    $message = $this->error;
                    $return = array("statuts" => 1, "mes" => $message);
                }
            }
        } else {
            $message = "Veuillez renseigner tous les champs requis ";
            $return = array("statuts" => 1, "mes" => $message);
        }
    
        echo json_encode($return);
    }

    public function updateCerfaEtudiant()
    {
        header('content-type: application/json');
        $return = [];
        $tab = ["add", "edit"];
    
        if (
            isset($_POST['emailA']) && !empty($_POST['emailA']) &&
            isset($_POST['action']) && !empty($_POST['action']) &&
            isset($_POST['id']) && in_array($_POST["action"], $tab)
        ) {
    
    
            $nomA = $_POST['nomA'];
            $nomuA = $_POST['nomuA'];
            $prenomA = $_POST['prenomA'];
            $sexeA = $_POST['sexeA'];
            $naissanceA = $_POST['naissanceA'];
            $departementA = $_POST['departementA'];
            $communeNA = $_POST['communeNA'];
            $nationaliteA = $_POST['nationaliteA'];
            $regimeA = $_POST['regimeA'];
            $situationA = $_POST['situationA'];
            $titrePA = $_POST['titrePA'];
            $derniereCA = $_POST['derniereCA'];
            $securiteA = $_POST['securiteA'];
            $intituleA = $_POST['intituleA'];
            $titreOA = $_POST['titreOA'];
            $declareSA = $_POST['declareSA'];
            $declareHA = $_POST['declareHA'];
            $declareRA = $_POST['declareRA'];
            $rueA = $_POST['rueA'];
            $voieA = $_POST['voieA'];
            $complementA = $_POST['complementA'];
            $postalA = $_POST['postalA'];
            $communeA = $_POST['communeA'];
            $numeroA = $_POST['numeroA'];
            $emailA = $_POST['emailA'];
    
            $nomR = $_POST['nomR'];
            $prenomR = $_POST['prenomR'];
            $emailR = $_POST['emailR'];
            $rueR = $_POST['rueR'];
            $voieR = $_POST['voieR'];
            $complementR = $_POST['complementR'];
            $postalR = $_POST['postalR'];
            $communeR = $_POST['communeR'];

            $action = $_POST['action'];
            $id = $_POST['id'];
    
            if ($action == "edit") {
                if (!empty($id)) {
                    $cerfa = Cerfa::find($id);
    
                    if ($cerfa['valid']) {
                        $bool = true;
                        $result = Cerfa::byEmail($emailA);
                        if ($emailA != $cerfa['data']->emailA) {
                           
                            if ($result['valid']) {
                                $bool = false;
                            }
                        }
    
                        if ($bool) {
                            try {
                                $save=Cerfa::updateCerfaEtudiant(
                                $nomA, $nomuA, $prenomA, $sexeA,
                                $naissanceA, $departementA, $communeNA, 
                                $nationaliteA , $regimeA , $situationA, $titrePA,
                                $derniereCA, $securiteA, $intituleA, $titreOA , 
                                $declareSA , $declareHA, $declareRA, $rueA, 
                                $voieA , $complementA, $postalA, $communeA, $numeroA , $emailA,
                                $nomR,$prenomR,$emailR,$rueR,$voieR,$complementR,$postalR,$communeR, 
                                $id);
                               
                                if ($save['valid']) {
                                    $message = "La cerfa a été mise à jour avec succès";
                                    $this->session->write('success', $message);
                                    $return = array("statuts" => 0, "mes" => $message);
                                } else {
                                    $message = $save['error'];
                                    $return = array("statuts" => 1, "mes" => $message);
                                }
                            } catch (Exception $e) {
                                $message = $e->getMessage();
                                $return = array("statuts" => 1, "mes" => $message);
                            }
                        } else {
                            $message = "Le cerfa avec cet email existe déjà";
                            $return = array("statuts" => 1, "mes" => $message);
                        }
                    } else {
                        $message = $cerfa['error'];
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                } else {
                    $message = $this->error;
                    $return = array("statuts" => 1, "mes" => $message);
                }
            }
        } else {
            $message = "Veuillez renseigner tous les champs requis ";
            $return = array("statuts" => 1, "mes" => $message);
        }
    
        echo json_encode($return);
    }
    public function save()
    {
        header('content-type: application/json');
        $return = [];
        $tab = ["add", "edit"];
    
        if (
            isset($_POST['idemployeur']) && !empty($_POST['idemployeur']) &&
            isset($_POST['emailA']) && !empty($_POST['emailA']) &&
            isset($_POST['action']) && !empty($_POST['action']) &&
            isset($_POST['id']) && in_array($_POST["action"], $tab)
        ) {
    
            $idemployeur = $_POST['idemployeur'];
            $idformation = $_POST['idformation'];
    
    
            $nomA = $_POST['nomA'];
            $nomuA = $_POST['nomuA'];
            $prenomA = $_POST['prenomA'];
            $sexeA = $_POST['sexeA'];
            $naissanceA = $_POST['naissanceA'];
            $departementA = $_POST['departementA'];
            $communeNA = $_POST['communeNA'];
            $nationaliteA = $_POST['nationaliteA'];
            $regimeA = $_POST['regimeA'];
            $situationA = $_POST['situationA'];
            $titrePA = $_POST['titrePA'];
            $derniereCA = $_POST['derniereCA'];
            $securiteA = $_POST['securiteA'];
            $intituleA = $_POST['intituleA'];
            $titreOA = $_POST['titreOA'];
            $declareSA = $_POST['declareSA'];
            $declareHA = $_POST['declareHA'];
            $declareRA = $_POST['declareRA'];
            $rueA = $_POST['rueA'];
            $voieA = $_POST['voieA'];
            $complementA = $_POST['complementA'];
            $postalA = $_POST['postalA'];
            $communeA = $_POST['communeA'];
            $numeroA = $_POST['numeroA'];
            $emailA = $_POST['emailA'];
    
            $nomR = $_POST['nomR'];
            $prenomR = $_POST['prenomR'];
            $emailR = $_POST['emailR'];
            $rueR = $_POST['rueR'];
            $voieR = $_POST['voieR'];
            $complementR = $_POST['complementR'];
            $postalR = $_POST['postalR'];
            $communeR = $_POST['communeR'];
    
    
            $nomM = $_POST['nomM'];
            $prenomM = $_POST['prenomM'];
            $naissanceM = $_POST['naissanceM'];
            $securiteM = $_POST['securiteM'];
            $emailM = $_POST['emailM'];
            $emploiM = $_POST['emploiM'];
            $diplomeM = $_POST['diplomeM'];
            $niveauM = $_POST['niveauM'];
    
            $nomM1 = $_POST['nomM1'];
            $prenomM1 = $_POST['prenomM1'];
            $naissanceM1 = $_POST['naissanceM1'];
            $securiteM1 = $_POST['securiteM1'];
            $emailM1 = $_POST['emailM1'];
            $emploiM1 = $_POST['emploiM1'];
            $diplomeM1 = $_POST['diplomeM1'];
            $niveauM1 = $_POST['niveauM1'];
    
            $travailC = $_POST['travailC'];
            $modeC = $_POST['modeC'];
            $derogationC = $_POST['derogationC'];
            $numeroC = $_POST['numeroC'];
            $conclusionC = $_POST['conclusionC'];
            $debutC = $_POST['debutC'];
            $finC = $_POST['finC'];
            $avenantC = $_POST['avenantC'];
            $executionC = $_POST['executionC'];
            $dureC = $_POST['dureC'];
            $dureCM = $_POST['dureCM'];
            $typeC = $_POST['typeC'];
            $rdC = $_POST['rdC'];
            $raC = $_POST['raC'];
            $rpC = $_POST['rpC'];
            $rsC = $_POST['rsC'];
            $rdC1 = $_POST['rdC1'];
            $raC1 = $_POST['raC1'];
            $rpC1 = $_POST['rpC1'];
            $rsC1 = $_POST['rsC1'];
    
            $rdC2 = $_POST['rdC2'];
            $raC2 = $_POST['raC2'];
            $rpC2 = $_POST['rpC2'];
            $rsC2 = $_POST['rsC2'];
    
            $salaireC = $_POST['salaireC'];
            $caisseC = $_POST['caisseC'];
            $logementC = $_POST['logementC'];
            $avantageC = $_POST['avantageC'];
            $autreC = $_POST['autreC'];
    
            $lieuO = $_POST['lieuO'];
            $priveO = $_POST['priveO'];
            $attesteO = $_POST['attesteO'];
    
    
    
            
            $action = $_POST['action'];
            $id = $_POST['id'];
    
            if ($action == "edit") {
                if (!empty($id)) {
                    $cerfa = Cerfa::find($id);
    
                    if ($cerfa['valid']) {
                        $bool = true;
                        $result = Cerfa::byEmail($emailA);
                        if ($emailA != $cerfa['data']->emailA) {
                           
                            if ($result['valid']) {
                                $bool = false;
                            }
                        }
    
                        if(!empty($naissanceA) && empty($salaireC)){
                            $smic = $this->smic();
                            $smic = str_replace(',', '.', $smic);
    
    
                            
    
                            $dateAujourdhui = date("Y-m-d");
    
                            $dateNaissanceObj = date_create($naissanceA);
                            $dateAujourdhuiObj = date_create($dateAujourdhui);
                        
                            // Calcul de l'âge
                            $diff = date_diff($dateNaissanceObj, $dateAujourdhuiObj);
                            $age = $diff->y;
                        
                            if ($age < 18) {
                                if(  $dateAujourdhui >= $rdC && $dateAujourdhui <= $raC){
                                    $salaireC = 0.27 * $smic;
                                    $rpC =27;
                                    $rpC1 ='';
                                    $rpC2 ='';
                                }elseif($dateAujourdhui >= $rdC1 && $dateAujourdhui <= $raC1 ){
                                    $salaireC = 0.39 * $smic;
                                    $rpC1 =39;
                                    $rpC ='';
                                    $rpC2 ='';
                                }elseif( $dateAujourdhui >= $rdC2 && $dateAujourdhui <= $raC2){
                                    $salaireC = 0.55 * $smic;
                                    $rpC2 =55;
                                    $rpC1 ='';
                                    $rpC ='';
                                }else{
                                    $salaireC = 0.27 * $smic;
                                    $rpC =27;
                                    $rpC1 ='';
                                    $rpC2 ='';
                                }
                             
                            } elseif ($age >= 18 && $age <= 20) {
                                if(  $dateAujourdhui >= $rdC && $dateAujourdhui <= $raC){
                                    $salaireC = 0.43 * $smic;
                                    $rpC =43;
                                    $rpC1 ='';
                                    $rpC2 ='';
                                }elseif($dateAujourdhui >= $rdC1 && $dateAujourdhui <= $raC1 ){
                                    $salaireC = 0.51 * $smic;
                                    $rpC1 =51;
                                    $rpC ='';
                                    $rpC2 ='';
                                }elseif( $dateAujourdhui >= $rdC2 && $dateAujourdhui <= $raC2){
                                    $salaireC = 0.67 * $smic;
                                    $rpC2 =67;
                                    $rpC1 ='';
                                    $rpC ='';
                                }else{
                                    $salaireC = 0.43 * $smic;
                                    $rpC =43;
                                    $rpC1 ='';
                                    $rpC2 ='';
                                }   
                             
                                
                            } elseif ($age >= 21 && $age <= 25) {
    
                                if( $dateAujourdhui >= $rdC && $dateAujourdhui <= $raC){
                                    $salaireC = 0.53 * $smic;
                                    $rpC =53;
                                    $rpC1 ='';
                                    $rpC2 ='';
                                }elseif($dateAujourdhui >= $rdC1 && $dateAujourdhui <= $raC1 ){
                                    $salaireC = 0.61 * $smic;
                                    $rpC1 =61;
                                    $rpC ='';
                                    $rpC2 ='';
                                }elseif( $dateAujourdhui >= $rdC2 && $dateAujourdhui <= $raC2 ){
                                    $salaireC = 0.78 * $smic;
                                    $rpC2 =78;
                                    $rpC1 ='';
                                    $rpC ='';
                                }else{
                                    $rpC =53;
                                    $salaireC = 0.53 * $smic;
                                    $rpC1 ='';
                                    $rpC2 ='';
                                }
                               
                            } else {
                                $salaireC = $smic;
                                $rpC =100;
                                $rpC1 =100;
                                $rpC2 =100;
                            }
                        }
    
    
    
    
                        if ($bool) {
                            try {
                                $save=Cerfa::save($idemployeur, $idformation,
                                $nomA, $nomuA, $prenomA, $sexeA,
                                $naissanceA, $departementA, $communeNA, 
                                $nationaliteA , $regimeA , $situationA, $titrePA,
                                $derniereCA, $securiteA, $intituleA, $titreOA , 
                                $declareSA , $declareHA, $declareRA, $rueA, 
                                $voieA , $complementA, $postalA, $communeA, $numeroA , $emailA ,
                                $nomR,$emailR,$rueR,$voieR,$complementR,$postalR,$communeR, 
                                $nomM,$prenomM,$naissanceM,$securiteM,$emailM,$emploiM,$diplomeM,$niveauM, 
                                $nomM1,$prenomM1,$naissanceM1,$securiteM1,$emailM1,$emploiM1,$diplomeM1,$niveauM1, 
                                $travailC,$derogationC,$numeroC,$conclusionC,$debutC,$finC,$avenantC,$executionC,$dureC,$typeC,
                                $rdC,$raC,$rpC,$rsC,
                                $rdC1,$raC1,$rpC1,$rsC1,
                                $rdC2,$raC2,$rpC2,$rsC2,
                                $salaireC,$caisseC,$logementC,$avantageC,$autreC,
                                $lieuO,$priveO,$attesteO,$modeC,$prenomR,$dureCM,
                                $id);
                               
                                if ($save['valid']) {
                                    $message = "La cerfa a été mise à jour avec succès";
                                    //$this->session->write('success', $message);
                                    $return = array("statuts" => 0, "mes" => $message);
                                } else {
                                    $message = $save['error'];
                                    $return = array("statuts" => 1, "mes" => $message);
                                }
                            } catch (Exception $e) {
                                $message = $e->getMessage();
                                $return = array("statuts" => 1, "mes" => $message);
                            }
                        } else {
                            $message = "Le cerfa avec cet email existe déjà";
                            $return = array("statuts" => 1, "mes" => $message);
                        }
                    } else {
                        $message = $cerfa['error'];
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                } else {
                    $message = $this->error;
                    $return = array("statuts" => 1, "mes" => $message);
                }
            }
        } else {
            $message = "Veuillez renseigner tous les champs requis ";
            $return = array("statuts" => 1, "mes" => $message);
        }
    
        echo json_encode($return);
    }

   
    

     public  function smic()
{
    $url = 'https://entreprendre.service-public.fr/vosdroits/F2300';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    // if (curl_errno($ch)) {
    //     echo 'Erreur cURL : ' . curl_error($ch);
    //     exit;
    // }
    curl_close($ch);
    $dom = new DOMDocument;
    @$dom->loadHTML($response);
    $xpath = new DOMXPath($dom);
    $smicMensuelBrut = $xpath->query('//tr[th="Smic mensuel"]/td[1]/p/span[@class="sp-prix"]');
    if ($smicMensuelBrut->length > 0) {
        $montantBrutMensuel = $smicMensuelBrut->item(0)->textContent;
        return preg_replace('/[^\d,]/', '', $montantBrutMensuel);
    } else {
        return  1747.20;
    }
}

    public function savenew()
    {
        header('content-type: application/json');
        $return = [];
            $idemployeur = $_POST['idemployeur'];
            $idformation = $_POST['idformation'];
            $emailA = $_POST['emailAA'];
             $result = Cerfa::byEmail($emailA);
              
               

                if (!$result['valid']) {
                    try {
                        $save = Cerfa::save(
                            $idemployeur, $idformation,
                            null, null, null, null,
                            null, null, null,
                            null, null, null, null,
                            null, null, null, null,
                            null, null, null, null,
                            null, null, null, null,
                            null, $emailA,
                            null, null, null, null, null, null, null,
                            null, null, null, null, null, null, null, null,
                            null, null, null, null, null, null, null, null,
                            null, null, null, null, null, null, null, null, null, null,
                            null, null, null, null, null, null, null, null, null, null, null, null, null,
                            null, null, null,null,null,null,'oui',null,null,null,
                            null
                        );
                        if ($save['valid']) {
                            $message = $save['data'];
                            $this->session->write('success', $message);
                            //App::url('cerfas');
                            $return = array("statuts" => 0, "mes" => $message);
                        } else {
                            $message = $save['error'];
                            $return = array("statuts" => 1, "mes" => $message);
                        }   
                    } catch (Exception $e) {
                        $message =$e->getMessage();
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                } else {
                 
                  
                   // Version alternative plus claire
                    $contratsEnCours = false;
                    $messageErreur = "";

                    // Parcourir tous les contrats
                    foreach ($result['data'] as $contrat) {
                        if (empty($contrat->finC) || $contrat->finC == null || $contrat->finC > date('Y-m-d')) {
                            $contratsEnCours = true;
                            $messageErreur = "Un contrat est en cours pour cet étudiant, veuillez utiliser un autre email";
                            break;
                        }
                    }

                    if ($contratsEnCours) {
                        // Il y a un contrat en cours
                        $return = array("statuts" => 1, "mes" => $messageErreur);
                    } else {
                        // Tous les contrats sont terminés, on peut enregistrer
                        $save = Cerfa::save(
                            $idemployeur, $idformation,
                            null, null, null, null,
                            null, null, null,
                            null, null, null, null,
                            null, null, null, null,
                            null, null, null, null,
                            null, null, null, null,
                            null, $emailA,
                            null, null, null, null, null, null, null,
                            null, null, null, null, null, null, null, null,
                            null, null, null, null, null, null, null, null,
                            null, null, null, null, null, null, null, null, null, null,
                            null, null, null, null, null, null, null, null, null, null, null, null, null,
                            null, null, null,null,null,null,'oui',null,null,null,
                            null
                        );
                        
                        if ($save['valid']) {
                            $message = $save['data'];
                            $this->session->write('success', $message);
                            $return = array("statuts" => 0, "mes" => $message);
                        } else {
                            $message = $save['error'];
                            $return = array("statuts" => 1, "mes" => $message);
                        }
                    }
                   
                }
        
    

        echo json_encode($return);
    }

       
    public function delete() {
        //Privilege::hasPrivilege(Privilege::$AllView, $this->user->privilege);
        header('Content-Type: application/json');
    
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = $_POST['id'];
            $cerfa = Cerfa::find($id);
            $basePath = $_SERVER['DOCUMENT_ROOT'] . '/cerfa/public/';
            if ($cerfa['valid']) {
                $filesToDelete = ['cerfaOpco','conventionOpco', 'factureOpco', 'signatureApprenti', 'signatureEmployeur','signatureEcole','signatureConventionEcole','signatureConventionEmployeur'];
                $allFilesDeleted = true;
    
                foreach ($filesToDelete as $file) {
                    if (!empty($cerfa->$file)) {
                        $urlSegments = explode('/', $cerfa->$file);
                        $relativePath = implode('/', array_slice($urlSegments, 5));
                        $fullPath =  $basePath.$relativePath;
                        if (!unlink($fullPath)) {
                            $allFilesDeleted = false;
                        }
                    }
                }
    
                if ($allFilesDeleted) {
                    Cerfa::delete($id);
                    $message = "Le cerfa et ses fichiers ont été supprimés avec succès.";
                    $this->session->write('success',$message);
                    $return = array("statuts"=>0, "mes"=>$message);
                } else {
                    $message = "impossible de supprimer tous les fichiers du cerfa.";
                    $return = array("statuts"=>1, "mes"=>$message.$fullPath);
                } 
            } else {
                $message = "Le cerfa n'existe plus.";
                $return = array("statuts"=>1, "mes"=>$message);
            }
        } else {
            $message = "Veuillez renseigner l'ID.";
            $return = array("statuts"=>1, "mes"=>$message);
        }
    
        echo json_encode($return);
    }
    

    public function send(){
        //Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        header('content-type: application/json');
        if (isset($_POST['id'])&&!empty($_POST['id'])){
            $id = $_POST['id'];
            $cerfa = Cerfa::find($id);
            if ($cerfa['valid']){
                if(!empty($cerfa['data']->emailA)){
                    $save = Cerfa::sendEmailApprenti($cerfa['data']->emailA, $id);
                    if($save['valid']){
                        $message = $save['data'];
                        //$this->session->write('success',$message);
                        $return = array("statuts"=>0, "mes"=>$message);
                    }else{
                        $message = "Erreur lors de l'envoie de l'email";
                        $return = array("statuts"=>1, "mes"=>$message);
                    }
                }else{
                    $message = "Renseigner l'email de l'apprenti(e)";
                    $return = array("statuts"=>1, "mes"=>$message);
                }
                
             
            }else{
                $message = $cerfa['error'];
                $return = array("statuts"=>1, "mes"=>$message);
            }
        }else{
            $message = "Renseigner l'id SVP !!!";
            $return = array("statuts"=>1, "mes"=>$message);
        }
        echo json_encode($return);
    }
    public function sendEmployeur(){
        //Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        header('content-type: application/json');
        if (isset($_POST['id'])&&!empty($_POST['id'])){
            $id = $_POST['id'];
            $cerfa = Cerfa::find($id);
            $ligneemployeur = Entreprise :: find($cerfa['data']->idemployeur);
            if ($ligneemployeur['valid']){
                if(!empty($ligneemployeur['data']->emailE)){
                    $save = Cerfa::sendEmailEmployeur($ligneemployeur['data']->emailE, $id);
                    if($save['valid']){
                        $message = $save['data'];
                        //$this->session->write('success',$message);
                        $return = array("statuts"=>0, "mes"=>$message);
                    }else{
                        $message = "Erreur lors de l'envoie de l'email";
                        $return = array("statuts"=>1, "mes"=>$message);
                    }
                }else{
                    $message = "Renseigner l'email de l'employeur";
                    $return = array("statuts"=>1, "mes"=>$message);
                }
                
             
            }else{
                $message = $ligneemployeur['error'];
                $return = array("statuts"=>1, "mes"=>$message);
            }
        }else{
            $message = "Renseigner l'id SVP !!!";
            $return = array("statuts"=>1, "mes"=>$message);
        }
        echo json_encode($return);
    }

    public function sendContratEmployeur(){
        //Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        header('content-type: application/json');
        if (isset($_POST['id'])&&!empty($_POST['id'])){
            $id = $_POST['id'];
            $cerfa = Cerfa::find($id);
            $ligneemployeur = Entreprise :: find($cerfa['data']->idemployeur);
            if ($ligneemployeur['valid']){
                if(!empty($ligneemployeur['data']->emailE)){
                    $save = Cerfa::sendEmailContratEmployeur($ligneemployeur['data']->emailE, $id);
                    if($save['valid']){
                        $message = $save['data'];
                        //$this->session->write('success',$message);
                        $return = array("statuts"=>0, "mes"=>$message);
                    }else{
                        $message = "Erreur lors de l'envoie du formulaire";
                        $return = array("statuts"=>1, "mes"=>$message);
                    }
                }else{
                    $message = "Renseigner l'email de l'employeur";
                    $return = array("statuts"=>1, "mes"=>$message);
                }
                
             
            }else{
                $message = $ligneemployeur['error'];
                $return = array("statuts"=>1, "mes"=>$message);
            }
        }else{
            $message = "Renseigner l'id SVP !!!";
            $return = array("statuts"=>1, "mes"=>$message);
        }
        echo json_encode($return);
    }


    public function sendSignatureEntreprise(){
        //Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        header('content-type: application/json');
        if (isset($_POST['id'])&&!empty($_POST['id'])){
            $id = $_POST['id'];
            $cerfa = Cerfa::find($id);
            $ligneemployeur = Entreprise :: find($cerfa['data']->idemployeur);
            $ligneformation = Formation :: find($cerfa['data']->idformation);
            if ( $ligneemployeur['valid'] && $ligneformation['valid']){
                if(!empty($ligneemployeur['data']->emailE)){
                    $save = Cerfa::sendEmailSignatureEmployeur($ligneemployeur['data']->emailE, $id);
                    if($save['valid']){
                        $message = $save['data'];
                        //$this->session->write('success',$message);
                        $return = array("statuts"=>0, "mes"=>$message);
                    }else{
                        $message = "Erreur lors de l'envoie de l'email";
                        $return = array("statuts"=>1, "mes"=>$message);
                    }
                }else{
                    $message = "Renseigner l'email de l'employeur";
                    $return = array("statuts"=>1, "mes"=>$message);
                }
                
             
            }else{
                $message = "veuillez renseigner les informations de l'enployeur et de la formation";
                $return = array("statuts"=>1, "mes"=>$message);
            }
        }else{
            $message = "Renseigner l'id SVP !!!";
            $return = array("statuts"=>1, "mes"=>$message);
        }
        echo json_encode($return);
    }

    public function sendSignatureApprenti(){
        //Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        header('content-type: application/json');
        if (isset($_POST['id'])&&!empty($_POST['id'])){
            $id = $_POST['id'];
            $cerfa = Cerfa::find($id);
            $ligneemployeur = Entreprise :: find($cerfa['data']->idemployeur);
            $ligneformation = Formation :: find($cerfa['data']->idformation);
            if ( $ligneemployeur['valid'] && $ligneformation['valid']){
                $save = Cerfa::sendEmailSignatureApprenti($cerfa['data']->emailA, $id);
                if($save["valid"]){
                    $message = $save['data'];
                    //$this->session->write('success',$message);
                    $return = array("statuts"=>0, "mes"=>$message);
                }else{
                    $message = "Erreur lors de l'envoie de l'email";
                    $return = array("statuts"=>1, "mes"=>$message);
                }    
             
            }else{
                $message = "veuillez renseigner les informations de l'enployeur et de la formation";
                $return = array("statuts"=>1, "mes"=>$message);
            }
        }else{
            $message = "Renseigner l'id SVP !!!";
            $return = array("statuts"=>1, "mes"=>$message);
        }
        echo json_encode($return);
    }

    public function sendSignatureApprentiRepresentant(){
        //Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        header('content-type: application/json');
        if (isset($_POST['id'])&&!empty($_POST['id'])){
            $id = $_POST['id'];
            $cerfa = Cerfa::find($id);
            $ligneemployeur = Entreprise :: find($cerfa['data']->idemployeur);
            $ligneformation = Formation :: find($cerfa['data']->idformation);
           if(!empty($cerfa['data']->emailR)){
            if ( $ligneemployeur['valid'] && $ligneformation['valid']){
                $save = Cerfa::sendEmailSignatureApprentiRepresentant($cerfa['data']->emailR, $id);
                if($save["valid"]){
                    $message = $save['data'];
                    //$this->session->write('success',$message);
                    $return = array("statuts"=>0, "mes"=>$message);
                }else{
                    $message = "Erreur lors de l'envoie de l'email";
                    $return = array("statuts"=>1, "mes"=>$message);
                }    
             
            }else{
                $message = "veuillez renseigner les informations de l'enployeur et de la formation";
                $return = array("statuts"=>1, "mes"=>$message);
            }
           }else{
                $message = "veuillez renseigner les informations du représentant légal";
                $return = array("statuts"=>1, "mes"=>$message);
           }
        }else{
            $message = "Renseigner l'id SVP !!!";
            $return = array("statuts"=>1, "mes"=>$message);
        }
        echo json_encode($return);
    }


    public function sendSignatureConventionEntreprise(){
        //Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        header('content-type: application/json');
        if (isset($_POST['id'])&&!empty($_POST['id'])){
            $id = $_POST['id'];
            $cerfa = Cerfa::find($id);
            $ligneemployeur = Entreprise :: find($cerfa['data']->idemployeur);
            $ligneformation = Formation :: find($cerfa['data']->idformation);
            if ( $ligneemployeur['valid'] && $ligneformation['valid']){
                $opco = Opco::find($ligneemployeur['data']->idopco);
                if (!$opco['valid']) {
                        $message = "Veuillez rattacher cette entreprise à un Opco";
                        $return = array("statuts" => 1, "mes" => $message);
                } else {
                        if(!empty($ligneemployeur['data']->emailE)){
                            $save = Cerfa::sendEmailSignatureConventionEmployeur($ligneemployeur['data']->emailE, $id);
                            if($save['valid']){
                                $message = $save['data'];
                                //$this->session->write('success',$message);
                                $return = array("statuts"=>0, "mes"=>$message);
                            }else{
                                $message = "Erreur lors de l'envoie de l'email";
                                $return = array("statuts"=>1, "mes"=>$message);
                            }
                        }else{
                            $message = "Renseigner l'email de l'employeur";
                            $return = array("statuts"=>1, "mes"=>$message);
                        }
                } 
             
            }else{
                $message = "veuillez renseigner les informations de l'enployeur et de la formation";
                $return = array("statuts"=>1, "mes"=>$message);
            }
        }else{
            $message = "Renseigner l'id SVP !!!";
            $return = array("statuts"=>1, "mes"=>$message);
        }
        echo json_encode($return);
    }

    public function signatureManuelleEcole(){

        //Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        header('content-type: application/json');
        $id = $_POST['idsignatureManuelleEcole'];
        if (isset($id)&&!empty($id)){
            $cerfa = Cerfa::find($id);
            $ligneemployeur = Entreprise :: find($cerfa['data']->idemployeur);
            $ligneformation = Formation :: find($cerfa['data']->idformation);
            if ( $ligneemployeur['valid'] && $ligneformation['valid']){

                if (isset($_FILES['fileSignatureManuelleEcole']) && $_FILES['fileSignatureManuelleEcole']['error'] == 0) {

                    $allowed = ['jpg', 'jpeg', 'png'];
                    $filePath = $_FILES['fileSignatureManuelleEcole']['tmp_name'];
                    $fileType = mime_content_type($filePath);
                    $fileName = basename($_FILES['fileSignatureManuelleEcole']['name']);
                    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

                    if (!in_array($fileExt, $allowed) || !file_exists($filePath)) {
                        $message = "Type de fichier non autorisé ou fichier introuvable.";
                        $return = array("statuts" => 1, "mes" => $message);

                    }else{
                        $fileName = preg_replace('/[^a-zA-Z0-9.]/', '', $fileName);
                        $fileName = str_replace(' ', '', $fileName);
                        $unique_id = uniqid(rand(), true);
                      

                        $path = PATH_FILE;
                        $path .= '/public/' . 'assets/signatureEcole' . '/' . str_replace('\\', '/', $unique_id.$fileName);
                        $path1 = 'public/' . 'assets/signatureEcole' . '/' . str_replace('\\', '/', $unique_id.$fileName);

                        if (!move_uploaded_file($filePath, $path)) {
                            $message = 'Erreur lors de la sauvegarde du fichier';
                            $return = array("statuts" => 1, "mes" => $message);
                        } else {
                           
                            $result = Cerfa::setPathSignature(ROOT_URL.$path1,$id);
                            if (!$result['valid']) {
                                $message = 'Le formulaire a été signer avec succès.';
                                $return = array("statuts" => 0, "mes" => $message);
                            } else {
                                $message = $result['error']. "Une erreur s'est produite lors de la signature.";
                                $return = array("statuts" => 1, "mes" => $message);
                            }
                        }
                       

                    }
                    
                }else{
                    $message = "Erreur lors du chargement du fichier veuillez ressayer .";
                    $return = array("statuts" => 1, "mes" => $message);

                }
                                                 
            }else{
                $message = "veuillez renseigner les informations de l'enployeur et de la formation";
                $return = array("statuts"=>1, "mes"=> $message);
            }
         
        }else{
            $message = "Renseigner l'id SVP !!!";
            $return = array("statuts"=>1, "mes"=> $message);
        }
      
        echo json_encode($return);

    }

    public function sendSignatureEcole(){
        //Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        header('content-type: application/json');
        if (isset($_POST['id'])&&!empty($_POST['id'])){
            $id = $_POST['id'];
            $cerfa = Cerfa::find($id);
            $ligneemployeur = Entreprise :: find($cerfa['data']->idemployeur);
            $ligneformation = Formation :: find($cerfa['data']->idformation);
            if ($ligneemployeur['valid'] && $ligneformation['valid']){
                $save = Cerfa::sendEmailSignatureEcole($ligneformation['data']->emailF, $id);
                if($save["valid"]){
                    $message = $save['data'];
                    //$this->session->write('success',$message);
                    $return = array("statuts"=>0, "mes"=>$message);
                }else{
                    $message = "Erreur lors de l'envoie de l'email";
                    $return = array("statuts"=>1, "mes"=>$message);
                }    
             
            }else{
                $message = "veuillez renseigner les informations de l'enployeur et de la formation";
                $return = array("statuts"=>1, "mes"=>$message);
            }
        }else{
            $message = "Renseigner l'id SVP !!!";
            $return = array("statuts"=>1, "mes"=>$message);
        }
        echo json_encode($return);
    }

    public function sendSignatureConventionEcole(){
        //Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        header('content-type: application/json');
        if (isset($_POST['id'])&&!empty($_POST['id'])){
            $id = $_POST['id'];
            $cerfa = Cerfa::find($id);
            $ligneemployeur = Entreprise :: find($cerfa['data']->idemployeur);
            $ligneformation = Formation :: find($cerfa['data']->idformation);
            if ( $ligneemployeur['valid'] && $ligneformation['valid']){
                $opco = Opco::find($ligneemployeur['data']->idopco);
                if (!$opco['valid']) {
                    $message = "Veuillez rattacher cette entreprise à un Opco";
                    $return = array("statuts" => 1, "mes" => $message);
                } else {
                    if(!empty($ligneformation['data']->emailF)){
                        $save = Cerfa::sendEmailSignatureConventionEcole($ligneformation['data']->emailF, $id);
                        if($save['valid']){
                            $message = $save['data'];
                            //$this->session->write('success',$message);
                            $return = array("statuts"=>0, "mes"=>$message);
                        }else{
                            $message = "Erreur lors de l'envoie de l'email";
                            $return = array("statuts"=>1, "mes"=>$message);
                        }
                    }else{
                        $message = "Renseigner l'email de l'ecole";
                        $return = array("statuts"=>1, "mes"=>$message);
                    }
                }
             
            }else{
                $message = "veuillez renseigner les informations de l'enployeur et de la formation";
                $return = array("statuts"=>1, "mes"=>$message);
            }
        }else{
            $message = "Renseigner l'id SVP !!!";
            $return = array("statuts"=>1, "mes"=>$message);
        }
        echo json_encode($return);
    }

    public function signatureManuelleConventionEcole(){

        //Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        header('content-type: application/json');
        $id = $_POST['idsignatureManuelleConventionEcole'];
        if (isset($id)&&!empty($id)){
            $cerfa = Cerfa::find($id);
            $ligneemployeur = Entreprise :: find($cerfa['data']->idemployeur);
            $ligneformation = Formation :: find($cerfa['data']->idformation);
            if ( $ligneemployeur['valid'] && $ligneformation['valid']){
                $opco = Opco::find($ligneemployeur['data']->idopco);
                if (!$opco['valid']) {
                    $message = "Veuillez rattacher cette entreprise à un Opco";
                    $return = array("statuts" => 1, "mes" => $message);
                } else {
                    if (isset($_FILES['fileSignatureManuelleConventionEcole']) && $_FILES['fileSignatureManuelleConventionEcole']['error'] == 0) {

                        $allowed = ['jpg', 'jpeg', 'png'];
                        $filePath = $_FILES['fileSignatureManuelleConventionEcole']['tmp_name'];
                        $fileType = mime_content_type($filePath);
                        $fileName = basename($_FILES['fileSignatureManuelleConventionEcole']['name']);
                        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

                        if (!in_array($fileExt, $allowed) || !file_exists($filePath)) {
                            $message = "Type de fichier non autorisé ou fichier introuvable.";
                            $return = array("statuts" => 1, "mes" => $message);

                        }else{
                            $fileName = preg_replace('/[^a-zA-Z0-9.]/', '', $fileName);
                            $fileName = str_replace(' ', '', $fileName);
                            $unique_id = uniqid(rand(), true);
                        

                            $path = PATH_FILE;
                            $path .= '/public/' . 'assets/signatureConventionEcole' . '/' . str_replace('\\', '/', $unique_id.$fileName);
                            $path1 = 'public/' . 'assets/signatureConventionEcole' . '/' . str_replace('\\', '/', $unique_id.$fileName);

                            if (!move_uploaded_file($filePath, $path)) {
                                $message = 'Erreur lors de la sauvegarde du fichier';
                                $return = array("statuts" => 1, "mes" => $message);
                            } else {
                            
                                $result = Cerfa::setPathSignatureManuelleConvention(ROOT_URL.$path1,$id);
                                if (!$result['valid']) {
                                    $message = 'La convention a été signer avec succès.';
                                    $return = array("statuts" => 0, "mes" => $message);
                                } else {
                                    $message = $result['error']. "Une erreur s'est produite lors de la signature.";
                                    $return = array("statuts" => 1, "mes" => $message);
                                }
                            }
                        

                        }
                        
                    }else{
                        $message = "Erreur lors du chargement du fichier veuillez ressayer .";
                        $return = array("statuts" => 1, "mes" => $message);
                    }
               } 
                                                 
            }else{
                $message = "veuillez renseigner les informations de l'enployeur et de la formation";
                $return = array("statuts"=>1, "mes"=> $message);
            }
         
        }else{
            $message = "Renseigner l'id SVP !!!";
            $return = array("statuts"=>1, "mes"=> $message);
        }
      
        echo json_encode($return);

    }

    public function sendOpco(){
        //Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        header('content-type: application/json');
        $id = $_POST['idCerfa'];
        $idProduitCerfa = $_POST['idProduitCerfa'];

       
     
        if (isset($id) && !empty($id)  && isset($idProduitCerfa) && !empty($idProduitCerfa)){
            $cerfa = Cerfa::find($id);
            $ligneemployeur = Entreprise :: find($cerfa['data']->idemployeur);
            $ligneformation = Formation :: find($cerfa['data']->idformation);
            if ( $ligneemployeur['valid'] && $ligneformation['valid']){
                    $opco = Opco :: find( $ligneemployeur['data']->idopco); 
                if(!$opco['valid']){
                    $message = "Veuillez rattacher cette entreprise a un Opco";
                    $return = array("statuts"=>1, "mes"=>$message);
                }else{
                        $opco = $opco['data'];
                        $ligneemployeur = $ligneemployeur['data'];
                        $ligneformation = $ligneformation['data'];
                        $cerfa = $cerfa['data'];
                      
                        $reconnaissancehandicap = $cerfa->declareHA == "oui" ? true : false ;
                        $repriseactivite = $cerfa->declareRA == "oui" ? true : false ;
                        $inscritsport = $cerfa->declareSA == "oui" ? true : false ;
                        $Machine = $cerfa->travailC == "oui" ? true : false ;
                        $avantage = $cerfa->autreC== "oui" ? true : false ;
                        $formationinterne = $ligneformation->entrepriseF== "oui" ? true : false ;
                        $lieuformation = $ligneformation->responsableF== "oui" ? true : false ;
                        $attestationpieces = $cerfa->attesteO == "oui" ? true : false ;
                                
                    // Créez des données JSON à envoyer à l'API
                    $cerfaData = [
                        "cerfa"=> [
                            "employeur"=> [
                                "nom"=>$ligneemployeur->nomE,
                                "prenom"=>$ligneemployeur->nomE,
                                "typeEmployeur"=>$ligneemployeur->typeE,
                                "employeurSpecifique"=>$ligneemployeur->specifiqueE,
                                "caisseComplementaire"=>$cerfa->caisseC,
                                "regimeSpecifique"=>false,
                                "attestationEligibilite"=>true,
                                "attestationPieces"=>$attestationpieces,
                                "denomination"=>$ligneemployeur->nomE,
                                "siret"=>$ligneemployeur->siretE,
                                "naf"=>$ligneemployeur->codeaE,
                                "nombreDeSalaries"=>$ligneemployeur->totalE,
                                "codeIdcc"=>$ligneemployeur->codeiE,
                                "libelleIdcc"=>"",
                                "telephone"=>$ligneemployeur->numeroE,
                                "courriel"=>$ligneemployeur->emailE,
                                "adresse"=>[
                                    "adresse1"=>$ligneemployeur->rueE.' '.$ligneemployeur->voieE,
                                    "adresse2"=>$ligneemployeur->complementE,
                                    "codePostal"=>$ligneemployeur->postalE,
                                    "commune"=>$ligneemployeur->communeE
                                    ]
                                ],
                                "apprenti"=>[
                                    "nom"=>$cerfa->nomA,
                                    "nomUsage"=>$cerfa->nomuA,
                                    "prenom"=>$cerfa->prenomA,
                                    "sexe"=>$cerfa->sexeA,
                                    "nationalite"=>$cerfa->nationaliteA,
                                    "dateNaissance"=>(new DateTime($cerfa->naissanceA))->format('Y-m-d\TH:i:sP'),
                                    "departementNaissance"=>$cerfa->departementA,
                                    "communeNaissance"=>$cerfa->communeNA,
                                    "nir"=>$cerfa->securiteA,
                                    "regimeSocial"=>$cerfa->regimeA,
                                    "handicap"=>$reconnaissancehandicap,
                                    "situationAvantContrat"=>$cerfa->situationA,
                                    "diplome"=>$cerfa->titreOA,
                                    "derniereClasse"=>$cerfa->derniereCA,
                                    "diplomePrepare"=>$cerfa->titrePA,
                                    "intituleDiplomePrepare"=>$cerfa->intituleA,
                                    "telephone"=>$cerfa->numeroA,
                                    "courriel"=>$cerfa->emailA,
                                    "adresse"=>[
                                        "adresse1"=>$cerfa->rueA.' '.$cerfa->voieA,
                                        "adresse2"=>$cerfa->complementA,
                                        "codePostal"=>$cerfa->postalA,
                                        "commune"=>$cerfa->communeA
                                    ],

                                    "responsableLegal"=>[
                                        "nom"=> empty($cerfa->nomR)? $cerfa->nomA : $cerfa->nomR,
                                        "prenom"=>empty($cerfa->prenomR)?$cerfa->prenomA : $cerfa->prenomR,
                                        "courriel"=>empty($cerfa->emailR)?$cerfa->emailA :$cerfa->emailR,
                                        "adresse"=>[
                                          "adresse1"=>empty($cerfa->rueR)? $cerfa->rueA.' '.$cerfa->voieA : $cerfa->rueR.' '.$cerfa->voieR,
                                          "adresse2"=>empty($cerfa->complementR)? $cerfa->complementA: $cerfa->complementR,
                                          "codePostal"=>empty($cerfa->postalR)? $cerfa->postalA: $cerfa->postalR,
                                          "commune"=>empty($cerfa->communeR)? $cerfa->communeA: $cerfa->communeR
                                          ]
                                        ],
                                    "inscriptionSportifDeHautNiveau"=>$inscritsport,
                                    "projetCreationRepriseEntreprise"=>$repriseactivite

                                ],
                            

                                "maitre1"=>[
                                    "nom"=>$cerfa->nomM,
                                    "prenom"=>$cerfa->prenomM,
                                    "dateNaissance"=>(new DateTime($cerfa->naissanceM))->format('Y-m-d\TH:i:sP'),
                                    "nir"=>$cerfa->securiteM,
                                    "courriel"=>$cerfa->emailM,
                                    "emploiOccupe"=>$cerfa->emploiM,
                                    "intituleDiplomeObtenu"=>$cerfa->diplomeM,
                                    "niveauDiplomeObtenu"=>$cerfa->niveauM
                                ],
                                

                                "formation"=>[
                                    "rncp"=>"RNCP".$ligneformation->rnF,
                                    "codeDiplome"=>$ligneformation->codeF,
                                    "typeDiplome"=>$ligneformation->diplomeF,
                                    "intituleQualification"=>$ligneformation->intituleF,
                                    "dateDebutFormation"=>(new DateTime($ligneformation->debutO))->format('Y-m-d\TH:i:sP'),
                                    "dateFinFormation"=>(new DateTime($ligneformation->prevuO))->format('Y-m-d\TH:i:sP'),
                                    "dureeFormation"=>$ligneformation->dureO
                                ],

                                "contrat" => [
                                    "modeContractuel"=>$cerfa->modeC,
                                    "typeContratApp"=>$cerfa->typeC,
                                    "numeroContratPrecedent"=>$cerfa->numeroC,
                                    "noContrat"=>0,
                                    "noAvenant"=>0,
                                    "dateConclusion"=> (new DateTime($cerfa->conclusionC))->format('Y-m-d\TH:i:sP'),
                                    "dateDebutContrat"=>(new DateTime($cerfa->executionC ))->format('Y-m-d\TH:i:sP'),
                                    "dateFormationPratiqueEmployeur"=>(new DateTime($cerfa->debutC))->format('Y-m-d\TH:i:sP'),
                                    "dateFinContrat"=> (new DateTime($cerfa->finC))->format('Y-m-d\TH:i:sP'),
                                    "dateEffetAvenant"=> empty($cerfa->avenantC)? "" : (new DateTime($cerfa->avenantC))->format('Y-m-d\TH:i:sP'),
                                    "dateRupture"=> "",
                                    "lieuSignatureContrat"=>$cerfa->lieuO,
                                    "typeDerogation"=>$cerfa->derogationC,
                                    "dureeTravailHebdoHeures"=>$cerfa->dureC,
                                    "dureeTravailHebdoMinutes"=>$cerfa->dureCM,
                                    "travailRisque"=>$Machine,
                                    "salaireEmbauche"=>$cerfa->salaireC,
                                    "avantageNourriture"=>empty($cerfa->avantageC)? 0 : (int)$cerfa->avantageC,
                                    "avantageLogement"=> empty($cerfa->logementC)? 0 : (int)$cerfa->logementC,
                                    "autreAvantageEnNature"=>$avantage,
                                    "remunerationsAnnuelles"=>[
                                         [
                                            "dateDebut"=>(new DateTime($cerfa->rdC))->format('Y-m-d\TH:i:sP'),
                                            "dateFin"=>(new DateTime($cerfa->raC))->format('Y-m-d\TH:i:sP'),
                                            "taux"=>$cerfa->rpC,
                                            "typeSalaire"=>$cerfa->rsC,
                                            "ordre"=>"1.1"
                                         ]
                                        
                                    ]
                                    ],

                                "organismeFormation" => [
                                    "denomination"=>$ligneformation->nomF,
                                    "formationInterne"=>$formationinterne,
                                    "siret"=>$ligneformation->siretF,
                                    "uaiCfa"=>$ligneformation->numeroF,
                                    "visaCfa"=>true,
                                    "adresse"=>[
                                        "adresse1"=>$ligneformation->rueF." ".$ligneformation->voieF,
                                        "adresse2"=>$ligneformation->complementF,
                                        "codePostal"=>$ligneformation->postalF,
                                        "commune"=>$ligneformation->communeF
                                    ],
                                    "lieuFormationIdentique"=>$lieuformation
                                ],

                                "organismeFormationLieuFormationPrincipal"=> [
                                    "denomination"=>empty($ligneformation->nomO)?$ligneformation->nomF :$ligneformation->nomO,
                                    "siret"=>empty($ligneformation->siretO)?$ligneformation->siretF : $ligneformation->siretO ,
                                    "uaiCfa"=>empty($ligneformation->numeroO)?$ligneformation->numeroF : $ligneformation->numeroO,
                                    "adresse"=>[
                                        "adresse1"=>empty($ligneformation->rueO)?$ligneformation->rueF." ".$ligneformation->voieF : $ligneformation->rueO." ".$ligneformation->voieO,
                                        "adresse2"=>empty($ligneformation->complementO)?$ligneformation->complementF :$ligneformation->complementO ,
                                        "codePostal"=>empty($ligneformation->postalO)?$ligneformation->postalF : $ligneformation->postalO,
                                        "commune"=>empty($ligneformation->communeO)?$ligneformation->communeF:$ligneformation->communeO
                                        ]
                                ],

                                "etat"=>"TRANSMIS",
                                "commentaireEtat"=>"",
                                "versionCERFA"=>"10103*10",
                                "CERFAsignatureProbante"=>true
                        ]
                    ];

                    // Ajout conditionnel de "maitre2"
                        if (!empty($cerfa->nomM1)) {
                            $cerfaData["maitre2"] = [
                                "nom" => $cerfa->nomM1,
                                "prenom" => $cerfa->prenomM1,
                                "dateNaissance" =>(new DateTime($cerfa->naissanceM1))->format('Y-m-d\TH:i:sP'),
                                "nir" => $cerfa->securiteM1,
                                "courriel" => $cerfa->emailM1,
                                "emploiOccupe" => $cerfa->emploiM1,
                                "intituleDiplomeObtenu" => $cerfa->diplomeM1,
                                "niveauDiplomeObtenu" => $cerfa->niveauM1
                            ];
                        } else {
                            $cerfaData["maitre2"] = null;
                        }

                  
                    // Convertissez les données en JSON
                    $jsonData=json_encode($cerfaData);

                    //var_dump($jsonData);

                    if (isset($_FILES['cerfa']) && $_FILES['cerfa']['error'] == 0) {

                        $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
                        $filePath = $_FILES['cerfa']['tmp_name'];
                        $fileType = mime_content_type($filePath);
                        $fileName = basename($_FILES['cerfa']['name']);
                        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    
                        if (!in_array($fileExt, $allowed) || !file_exists($filePath)) {
                            $message = "Type de fichier non autorisé ou fichier introuvable.";
                            $return = array("statuts" => 1, "mes" => $message);
                        } else {

                            // Créez un handle cURL
                    $ch = curl_init();

                    $client_id = $opco->clid;
                    $client_secret =$opco->clse;

                    $post_data = [
                        'grant_type' => 'client_credentials',
                        'client_id' => $client_id,
                        'client_secret' => $client_secret,
                        'scope' => ($opco->nom !== "EP") ? 'api.read api.write' : null
                    ];
                    if (isset($post_data['scope']) && $post_data['scope'] === null) {
                        unset($post_data['scope']);
                    }

                    // Configurez cURL pour envoyer une requête POST avec des données JSON
                    curl_setopt($ch, CURLOPT_URL, $opco->lienT);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
                    curl_setopt($ch, CURLOPT_POST, true);


                    // Exécution de la requête pour obtenir le token
                    $response = curl_exec($ch);

                    // Traitement de la réponse pour récupérer le token
                   
                    if($opco->nom == "AFDAS"){
                        $access_token = $this->obtenirTokenCaches($opco->clid,$opco->clse,$opco->lienT);
                    }else{
                        $result = json_decode($response, true);
                        $access_token = $result['access_token'];
                    }

                    $postFields = [
                        'cerfa' => $jsonData,
                        'fichier' => new \CurlFile($filePath, $fileType, $fileName)
                    ];

                    curl_setopt($ch, CURLOPT_URL, $opco->lienCe);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'accept:  application/json',
                        'EDITEUR: CerFacil',
                        'LOGICIEL: CerFacil',
                        'VERSION: 1.0.0',
                        "Authorization: Bearer $access_token",
                        "X-API-KEY:$opco->cle"

                    ]);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

                    

                    // Exécutez la requête et obtenez la réponse
                    $response = curl_exec($ch);

                   

                    // Gérez les erreurs cURL
                    if (curl_errno($ch)) {
                        $message = 'Erreur cURLs :'. $response ;
                        $return = array("statuts"=>1, "mes"=>$message);
                    } else {
                        // Obtenez le code de statut HTTP
                        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                        // Analysez la réponse
                        $responseJson = json_decode($response, true);

                        //var_dump($responseJson);
                        switch ($httpCode) {
                            case 200:
                                $numeroExterne = isset($responseJson['numeroExterne']) ? $responseJson['numeroExterne'] : '' ;
                                $numeroInterne = isset($responseJson['numeroInterne']) ? $responseJson['numeroInterne'] : '' ;
                                //$numeroDeca = isset($responseJson['numeroDeca']) ? $responseJson['numeroDeca'] : '' ;

                                $unique_id = uniqid(rand(), true);
                                $path = PATH_FILE;
                                $path .= '/public/' . 'assets/cerfaOpco' . '/' . str_replace('\\', '/', $unique_id.$fileName);
                                $path1 = 'public/' . 'assets/cerfaOpco' . '/' . str_replace('\\', '/', $unique_id.$fileName);

                                if (!move_uploaded_file($filePath, $path)) {
                                    $message = 'Erreur lors de la sauvegarde du fichier';
                                    $return = array("statuts" => 1, "mes" => $message);
                                } else {
                                    $saveNumeroInterne = Cerfa :: setNumeroInterne($numeroInterne,$cerfa->id);
                                    $saveNumeroExterne = Cerfa :: setNumeroExterne($numeroExterne,$cerfa->id);
                                    //$saveNumeroDeca = Cerfa :: setNumeroDeca($numeroDeca ,$cerfa->id);
                                    $saveCerfaOpco = Cerfa :: setCerfaOpco(ROOT_URL.$path1,$id);
                                    $tableauAbonnement = Abonnement::updateAbonnementById($idProduitCerfa);

                                    if($saveCerfaOpco['valid'] && $saveNumeroInterne['valid'] && $saveNumeroExterne['valid']  && $tableauAbonnement['valid']){
                                        $message = "Succès ! Contrat d'apprentissage enregistré.";
                                        $this->session->write('success',$message);
                                        $return = array("statuts"=>0, "mes"=>$message);
                                    }else{
                                        $message = $saveCerfaOpco['error']. $saveNumeroInterne['error'].$saveNumeroExterne['error'].$tableauAbonnement['error']." Succès ! Contrat d'apprentissage enregistré.";
                                        $return = array("statuts"=>0, "mes"=>$message);
                                    }   
                                   
                                }
                               
                                break;
                            case 400:
                                if($opco->nom == "Atlas"){
                                    $message = "Erreur : " . $responseJson['description'];
                                    $return = array("statuts"=>1, "mes"=>$message);
                                }else{
                                    if($opco->nom == "EP"){
                                        if(isset($responseJson['errors']) && !$responseJson['errors'] == null){
                                            foreach ($responseJson['errors'] as $error) {
                                                $message = "Code : " . $error['code'] . " - " . $error['description'];
                                                $return = array("statuts"=>1, "mes"=>$message);
                                            }
                                        }else{
                                            $message = "Code : " . $httpCode . " - " . $responseJson['comment'];
                                            $return = array("statuts"=>1, "mes"=>$message);
                                        }
                                        
                                    }else{
                                        foreach ($responseJson['errors'] as $error) {
                                            $message = "Code : " . $error['code'] . " - " . $error['description'];
                                            $return = array("statuts"=>1, "mes"=>$message);
                                        }
                                    }
                                   
                                }
                               
                               
                                break;
                            case 401:
                                $errors = array();
                                $message = "Erreur : " . $responseJson['description'];
                                $return = array("statuts"=>1, "mes"=>$message);
                                foreach ($responseJson['errors'] as $error) {
                                    $message = "Code : " . $error['code'] . " - " . $error['description'];
                                    $return = array("statuts"=>1, "mes"=>$message);
                                }
                                break;
                            case 403:
                                $errors = array();
                                foreach ($responseJson['errors'] as $error) {
                                    $errors[] = "Code : " . $error['code'] . " - " . $error['description'];
                                }
                                $message = "Erreurs : " . implode(", ", $errors);
                                $return = array("statuts"=>1, "mes"=>$message);
                                break;
                            case 500:
                                $errors = array();
                                foreach ($responseJson['errors'] as $error) {
                                    $errors[] ="Code : " .$error['code']. " - " .$error['description'];
                                }
                                $message = "Erreurs : " . implode(", ", $errors);
                                $return = array("statuts"=>1, "mes"=>$message);
                                break;
                            default:
                            
                            $message = "Erreur inattendue.";
                             $return = array("statuts"=>1, "mes"=>$message.$httpCode);
                                break;
                        }
                    }

                
                               curl_close($ch);

                        }

                    }else{
                        $message = "Fichier non valide ou manquant.";
                        $return = array("statuts" => 1, "mes" => $message);

                    }


                    
                }
                                                 

            }else{
                $message = "veuillez renseigner les informations de l'enployeur et de la formation";
                $return = array("statuts"=>1, "mes"=> $message);
            }
         
        }else{
            $message = "Renseigner l'id SVP !!!";
            $return = array("statuts"=>1, "mes"=> $message);
        }
      
        echo json_encode($return);
    }




    // envoie de la convention a l'opco
    public function sendOpcoConvention() {
        //Privilege::hasPrivilege(Privilege::$AllView, $this->user->privilege);
        header('content-type: application/json');
        $id = $_POST['idConvention'];
        $date = $_POST['dateConvention'];
        $montantRQTHAnnee1 = empty($_POST['montantRQTHAnnee1'])? 0 :$_POST['montantRQTHAnnee1'];
        $montantRQTHAnnee2 =  empty($_POST['montantRQTHAnnee2'])? 0 :$_POST['montantRQTHAnnee2']; 
        $montantRQTHAnnee3 =   empty($_POST['montantRQTHAnnee3'])? 0 :$_POST['montantRQTHAnnee3'];  
        $montantRQTHAnnee4 = empty($_POST['montantRQTHAnnee4'])? 0 :$_POST['montantRQTHAnnee4']; 
        $nombreRepasTotaux= empty($_POST['nombreRepasTotaux'])? 0 :$_POST['nombreRepasTotaux'];
        $nombreHebergementTotaux = empty($_POST['nombreHebergementTotaux'])? 0 :$_POST['nombreHebergementTotaux']; 
        $montantPremierEquipement = empty($_POST['montantPremierEquipement'])? 0 :$_POST['montantPremierEquipement']; 

        $mentionMobilitéInternationale = $_POST['mentionMobilitéInternationale']; 
        $accompagnementDROM = $_POST['accompagnementDROM']; 


        if (isset($id) && !empty($id)) {
            $cerfa = Cerfa::find($id);
            $ligneemployeur = Entreprise::find($cerfa['data']->idemployeur);
            $ligneformation = Formation::find($cerfa['data']->idformation);
            if ($ligneemployeur['valid'] && $ligneformation['valid']) {
                $opco = Opco::find($ligneemployeur['data']->idopco);
                $coutTotalPedagogieCFA = $ligneformation['data']->prix;
                if (!$opco['valid'] || empty($coutTotalPedagogieCFA) ) {
                    $message = "Veuillez rattacher cette entreprise à un Opco  et verifier que les montants  Pedagogique sont remplis ";
                    $return = array("statuts" => 1, "mes" => $message);
                } else {
                    $opco = $opco['data'];
                    $ligneemployeur = $ligneemployeur['data'];
                    $ligneformation = $ligneformation['data'];
                    $cerfa = $cerfa['data'];
                    // Créez des données JSON à envoyer à l'API
                    $conventionData = [
                        "numeroInterneDossier" => $cerfa->numeroInterne,
                        "employeur" => [
                            "siret" => $ligneemployeur->siretE
                        ],
                        "apprenti" => [
                            "nom" => $cerfa->nomA,
                            "nomUsage" => $cerfa->nomuA,
                            "prenom" => $cerfa->prenomA
                        ],
                        "contrat" => [
                            "dateDebutContrat" =>(new DateTime($cerfa->executionC))->format('Y-m-d\TH:i:sP'),
                            "dateFinContrat" =>(new DateTime($cerfa->finC))->format('Y-m-d\TH:i:sP')
                        ],
                        "organismeFormation" => [
                            "siret" => $ligneformation->siretF,
                            "uaiCfa" => $ligneformation->numeroF
                        ],
                        "formation" => [
                            "rncp" => 'RNCP'.$ligneformation->rnF,
                            "intituleQualification" => $ligneformation->intituleF,
                            "dateDebutFormation" => (new DateTime($ligneformation->debutO))->format('Y-m-d\TH:i:sP'),
                            "dateFinFormation" => (new DateTime( $ligneformation->prevuO))->format('Y-m-d\TH:i:sP'),
                            "dureeFormation" => $ligneformation->dureO
                        ],
                        "couts" => [
                            "coutTotalPedagogieCFA" => $coutTotalPedagogieCFA,
                            "montantRQTHAnnee1" => $montantRQTHAnnee1,
                            "montantRQTHAnnee2" => $montantRQTHAnnee2,
                            "montantRQTHAnnee3" => $montantRQTHAnnee3,
                            "montantRQTHAnnee4" => $montantRQTHAnnee4,
                            "nombreRepasTotaux" => $nombreRepasTotaux,
                            "nombreHebergementTotaux" => $nombreHebergementTotaux,
                            "montantPremierEquipement" => $montantPremierEquipement,
                            "mentionMobilitéInternationale" => $mentionMobilitéInternationale,
                            "accompagnementDROM" => $accompagnementDROM
                        ],
                        "mandatEmployeur" => true,
                        "dateSignature" =>(new DateTime($date))->format('Y-m-d\TH:i:sP'),
                        "attestationConventionSigneeEntreprise" => true,
                        "attestationConventionSigneeCFA" => true,
                        "attestationConventionSignatureProbante" => true,
                        "attestationConventionConformeJSON" => true
                    ];
    
                    // Convertissez les données en JSON
                    $jsonData = json_encode($conventionData);
    
                    // Préparez le fichier à envoyer
                    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                        $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
                        $filePath = $_FILES['file']['tmp_name'];
                        $fileType = mime_content_type($filePath);
                        $fileName = basename($_FILES['file']['name']);
                        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    
                        if (!in_array($fileExt, $allowed) || !file_exists($filePath)) {
                            $message = "Type de fichier non autorisé ou fichier introuvable.";
                            $return = array("statuts" => 1, "mes" => $message);
                        } else {
                            // Créez un handle cURL
                            $ch = curl_init();
    
                            $client_id = $opco->clid;
                            $client_secret = $opco->clse;
    
                            $post_data = [
                                'grant_type' => 'client_credentials',
                                'client_id' => $client_id,
                                'client_secret' => $client_secret,
                                'scope' => ($opco->nom !== "EP") ? 'api.read api.write' : null
                            ];
                            if (isset($post_data['scope']) && $post_data['scope'] === null) {
                                unset($post_data['scope']);
                            }
    
                            // Configurez cURL pour envoyer une requête POST avec des données JSON
                            curl_setopt($ch, CURLOPT_URL, $opco->lienT);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
                            curl_setopt($ch, CURLOPT_POST, true);
    
                            // Exécution de la requête pour obtenir le token
                            $response = curl_exec($ch);
    
                            // Traitement de la réponse pour récupérer le token
                           
                            if($opco->nom == "AFDAS"){
                                $access_token = $this->obtenirTokenCaches($opco->clid,$opco->clse,$opco->lienT);
                            }else{
                                $result = json_decode($response, true);
                                $access_token = $result['access_token'];
                            }               
    
                            $postFields = [
                                'convention' => $jsonData,
                                'fichier' => new \CurlFile($filePath, $fileType, $fileName)
                            ];
                           
                            curl_setopt($ch, CURLOPT_URL, $opco->lienCo);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                'EDITEUR: CerFacil',
                                'LOGICIEL: CerFacil',
                                'VERSION: 1.0.0',
                                "Authorization: Bearer $access_token",
                                "X-API-KEY: $opco->cle"
                            ]);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    
                            // Activer le verbose pour obtenir plus d'informations
                            curl_setopt($ch, CURLOPT_VERBOSE, true);

    
                            // Augmenter le timeout
                            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    
                            // Exécutez la requête et obtenez la réponse
                            $response = curl_exec($ch);

                          
                            // Gérez les erreurs cURL
                            if (curl_errno($ch)) {
                                $message = 'Erreur cURL : ' . curl_error($ch);
                                $return = array("statuts" => 1, "mes" => $message);
                            } else {
                                // Obtenez le code de statut HTTP
                                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
                                // Analysez la réponse
                                $responseJson = json_decode($response, true);
    
                                switch ($httpCode) {
                                    case 200:
                                        $unique_id = uniqid(rand(), true);
                                        $path = PATH_FILE;
                                        $path .= '/public/' . 'assets/conventionOpco' . '/' . str_replace('\\', '/', $unique_id.$fileName);
                                        $path1 = 'public/' . 'assets/conventionOpco' . '/' . str_replace('\\', '/', $unique_id.$fileName);
    
                                        if (!move_uploaded_file($filePath, $path)) {
                                            $message = 'Erreur lors de la sauvegarde du fichier';
                                            $return = array("statuts" => 1, "mes" => $message);
                                        } else {
                                            $saveConventionOpco  = Cerfa :: setConventionOpco(ROOT_URL.$path1,$id);
                                            if($saveConventionOpco['valid']){
                                                $message = "Succès ! Convention d'apprentissage enregistrée.";
                                                $this->session->write('success', $message);
                                                $return = array("statuts" => 0, "mes" => $message);
                                            }else{
                                                $message = $saveConventionOpco['error']." Convention d'apprentissage enregistrée mais l'url n'est pas  mis a jour";
                                                $return = array("statuts" => 1, "mes" => $message);
                                            }
                                          
                                        }
                                        break;
                                    case 400:
                                        $message = "Erreur : " . $responseJson['description'];
                                        $return = array("statuts" => 1, "mes" => $message);
                                        foreach ($responseJson['errors'] as $error) {
                                            $message = "Code : " . $error['code'] . " - " . $error['description'];
                                            $return = array("statuts" => 1, "mes" => $message);
                                        }
                                        break;
                                    case 401:
                                        $errors = array();
                                        $message = "Erreur : " . $responseJson['description'];
                                        $return = array("statuts" => 1, "mes" => $message);
                                        foreach ($responseJson['errors'] as $error) {
                                            $message = "Code : " . $error['code'] . " - " . $error['description'];
                                            $return = array("statuts" => 1, "mes" => $message);
                                        }
                                        break;
                                    case 403:
                                        $errors = array();
                                        foreach ($responseJson['errors'] as $error) {
                                            $errors[] = "Code : " . $error['code'] . " - " . $error['description'];
                                        }
                                        $message = "Erreurs : " . implode(", ", $errors);
                                        $return = array("statuts" => 1, "mes" => $message);
                                        break;
                                    case 500:
                                        $errors = array();
                                        foreach ($responseJson['errors'] as $error) {
                                            $errors[] = "Code : " . $error['code'] . " - " . $error['description'];
                                        }
                                        $message = "Erreurs : " . implode(", ", $errors);
                                        $return = array("statuts" => 1, "mes" => $message);
                                        break;
                                    default:
                                        $message = "Erreur inattendue.";
                                        $return = array("statuts" => 1, "mes" => $message . $httpCode);
                                        break;
                                }
                            }
    
                            curl_close($ch);
                        }
                    } else {
                        $message = "Fichier non valide ou manquant.";
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                }
            } else {
                $message = "Veuillez renseigner les informations de l'employeur et de la formation";
                $return = array("statuts" => 1, "mes" => $message);
            }
        } else {
            $message = "Renseignez l'id SVP !!!";
            $return = array("statuts" => 1, "mes" => $message);
        }
    
        echo json_encode($return);
    }

    // remplissage total de la convention
    public function remplirConvention() {
        //Privilege::hasPrivilege(Privilege::$AllView, $this->user->privilege);
        header('content-type: application/json');
        $id = $_POST['idRemplirConvention'];
      
        $dateRemplirConvention = $_POST['dateRemplirConvention'];
        $RepresentantEmployeur = $_POST['RepresentantEmployeur'];
        $NumeroActivite = $_POST['NumeroActivite'];
        $RegionActivite = $_POST['RegionActivite'];
        $RepresentantCerfa = $_POST['RepresentantCerfa'];
        $Mission1 = $_POST['Mission1'];
        $Mission2 = $_POST['Mission2'];
        $Mission3 = $_POST['Mission3'];

        $prisechargeentreprise = $_POST['prisechargeentreprise'];

        $dureepresentiel =  $_POST['dureepresentiel'];

        $dureedistenticiel = $_POST['dureedistenticiel'];


        if (isset($id) && !empty($id)) {
            $cerfa = Cerfa::find($id);
            $ligneemployeur = Entreprise::find($cerfa['data']->idemployeur);
            $ligneformation = Formation::find($cerfa['data']->idformation);
            if ($ligneemployeur['valid'] && $ligneformation['valid']) {
                $opco = Opco::find($ligneemployeur['data']->idopco);
                if (!$opco['valid']) {
                    $message = "Veuillez rattacher cette entreprise à un Opco";
                    $return = array("statuts" => 1, "mes" => $message);
                } else {
                    $opco = $opco['data'];
                    $ligneemployeur = $ligneemployeur['data'];
                    $ligneformation = $ligneformation['data'];
                    $cerfa = $cerfa['data'];
                    // Préparez le fichier à envoyer
                   
                    $filePath  = PATH_FILE;
                    $filePath .= ($prisechargeentreprise =="oui")? '/public/' . 'assets/pdf/convention5-1.pdf': '/public/' . 'assets/pdf/convention4-1.pdf';
                    $outputPath  = PATH_FILE;
                    $outputPath  .= '/public/' . 'assets/conventionOpco' . '/' . str_replace('\\', '/', $id.$cerfa->nomA.'.pdf');
                    $path= 'public/' . 'assets/conventionOpco' . '/' . str_replace('\\', '/', $id.$cerfa->nomA.'.pdf');
                        
                    ob_start();
                    try {
                        // Crée une instance de FPDI
                        $pdf = new Fpdi();
                    
                        // Ajoute une page du PDF existant
                        $pageCount = $pdf->setSourceFile(StreamReader::createByFile($filePath));
                    
                        for ($i = 1; $i <= $pageCount; $i++) {
                            $tplIdx = $pdf->importPage($i);
                            $pdf->addPage();
                            $pdf->useTemplate($tplIdx, 0, 0);
                            $pdf->SetFillColor(255, 255, 255); // Couleur blanche
                            $pdf->Rect(0, 0, $pdf->GetPageWidth(), 10, 'F'); // Masque en haut
                            $pdf->Rect(0, $pdf->GetPageHeight() - 10, $pdf->GetPageWidth(), 10, 'F'); // Masque en bas
                    
                            // Définir la police
                            $pdf->SetFont('helvetica', '', 12);
                    
                            // Ajouter du texte spécifique à chaque page
                            switch ($i) {
                                case 1:
                                    if (!empty($ligneformation->logo)) {
                                        $imageUrl = str_replace(' ', '', $ligneformation->logo);
                                       
                                        $imagePath = tempnam(sys_get_temp_dir(), 'logo3_');
                                        file_put_contents($imagePath, file_get_contents($imageUrl));
                                        $pdf->Image($imageUrl, 80, 0, 55, 37, '', '', '', false, 200, '', false, false, 0);
                                        unlink($imagePath); // Supprime le fichier temporaire
                                    }

                                    $pdf->SetFont('helvetica', '', 7.5);
                                    $pdf->SetXY(20, 50.7);
                
                                    // Définir la largeur maximale du cadre (ajustez selon vos besoins)
                                    $maxWidth = 70; // par exemple, pour laisser une marge à droite
                
                                    // Utiliser MultiCell avec une largeur définie
                                    $pdf->MultiCell($maxWidth, 5, $ligneformation->nomF, 0, 'L');
                
                
                                    $pdf->SetFont('helvetica', '', 7);
                                    $pdf->SetXY(53.6,50);
                                    $pdf->Cell(0, 5,  $ligneformation->rueF.' '.$ligneformation->voieF.' '.$ligneformation->postalF.' '.$ligneformation->communeF, 0,  'L');
                                     
                                    $pdf->SetFont('helvetica', '', 10);
                                    $pdf->SetXY(108.6, 47.3);
                                    $pdf->Cell(0, 10,  $ligneformation->siretF, 0, 1, 'L');
                
                                    $pdf->SetFont('helvetica', '', 10);
                                    $pdf->SetXY(147.6, 47.3);
                                    $pdf->Cell(0, 10,  $ligneformation->numeroF, 0, 1, 'L');
                
                                    //numero Activite  cerfa
                                    $pdf->SetFont('helvetica', '', 9);
                                    $pdf->SetXY(106.5, 51.7);
                                    $pdf->Cell(0, 10,  $NumeroActivite, 0, 1, 'L');

                                    //Region  cerfa
                                    $pdf->SetFont('helvetica', '', 9);
                                    $pdf->SetXY(32, 55.7);
                                    $pdf->Cell(0, 10,   $RegionActivite, 0, 1, 'L');

                                    //representant cerfa
                                    $pdf->SetFont('helvetica', '', 9);
                                    $pdf->SetXY(100, 55.7);
                                    $pdf->Cell(0, 10,  $RepresentantCerfa, 0, 1, 'L');
 
                
                
                                    $pdf->SetFont('helvetica', '', 7.5);
                                    $pdf->SetXY(37.6, 63);
                                    $pdf->Cell(0, 5,  $ligneemployeur->nomE, 0,  'L');

                                    $pdf->SetFont('helvetica', '', 7.3);
                                    $pdf->SetXY(74, 64);
                                    $pdf->MultiCell(60, 5,  ",".$ligneemployeur->rueE.' '.$ligneemployeur->voieE.' '.$ligneemployeur->postalE.' '.$ligneemployeur->communeE, 0,  'L');
                
                                    $pdf->SetFont('helvetica', '', 10);
                                    $pdf->SetXY(145.6, 60.3);
                                    $pdf->Cell(0, 10,  $ligneemployeur->siretE, 0, 1, 'L');
                
                                    $pdf->SetFont('helvetica', '', 10);
                                    $pdf->SetXY(182.6, 60.3);
                                    $pdf->Cell(0, 10,  $ligneemployeur->codeiE, 0, 1, 'L');
                

                                    //representant Employeur
                                    $pdf->SetFont('helvetica', '', 9);
                                    $pdf->SetXY(40, 64.3);
                                    $pdf->Cell(0, 10,  $RepresentantEmployeur, 0, 1, 'L');

                                   
                                    $pdf->SetFont('helvetica', '', 10);
                                    $pdf->SetXY(143, 64.3);
                                    $pdf->Cell(0, 10,  $opco->nom, 0, 1, 'L');


                                    
                
                                    $pdf->SetFont('helvetica', '', 7.5);
                                    $pdf->SetXY(27, 86.7);
                
                                    // Définir la largeur maximale du cadre (ajustez selon vos besoins)
                                    $maxWidth = 70; // par exemple, pour laisser une marge à droite
                
                                    // Utiliser MultiCell avec une largeur définie
                                    $pdf->MultiCell($maxWidth, 5, $ligneformation->nomF, 0, 'L');
                
                
                                    $pdf->SetFont('helvetica', '', 8);
                                    $pdf->SetXY(127, 87.6);
                                    $pdf->Cell(0, 10, $ligneformation->intituleF, 0, 1, 'L');
                
                                    $pdf->SetFont('helvetica', '', 10);
                                    $pdf->SetXY(187, 87.6);
                                    $pdf->Cell(0, 10, $ligneformation->rnF, 0, 1, 'L');

                                    // Mission Apprentissage

                                    // Mission1
                                    $pdf->SetFont('helvetica', '', 8);
                                    $pdf->SetXY(20, 98.5);
                                    $pdf->MultiCell(0, 5, '1) ' . $Mission1, 0, 'L');

                                    //Mission2
                                    $pdf->SetFont('helvetica', '', 8);
                                    $pdf->SetXY(20, 117.6);
                                    $pdf->MultiCell(0, 5, '2) ' . $Mission2, 0, 'L');

                                    //Mission3
                                    $pdf->SetFont('helvetica', '', 8);
                                    $pdf->SetXY(20, 137.6);
                                    $pdf->MultiCell(0, 5, '3) ' .$Mission3, 0, 1, 'L');

                
                
                                    $pdf->SetFont('helvetica', '', 9);
                                    $pdf->SetXY(69.8, 157.8);
                                    $date_formateD = date("d/m/Y", strtotime($ligneformation->debutO));
                                    $pdf->Cell(0, 10, $date_formateD, 0, 1, 'L');
                
                                    $pdf->SetFont('helvetica', '', 9);
                                    $pdf->SetXY(91.5, 157.8);
                                    $date_formateF = date("d/m/Y", strtotime($ligneformation->prevuO));
                                    $pdf->Cell(0, 10, $date_formateF, 0, 1, 'L');
                
                                    $pdf->SetFont('helvetica', '', 9);
                                    $pdf->SetXY(114.8, 157.8);
                                    $pdf->Cell(0, 10, $ligneformation->dureO, 0, 1, 'L');
                
                                    $pdf->SetFont('helvetica', '', 9);
                                    $pdf->SetXY(69.8, 161.9);
                                    $pdf->Cell(0, 10, $ligneformation->rueF.' '.$ligneformation->voieF.' '.$ligneformation->postalF.' '.$ligneformation->communeF, 0, 1, 'L');


                                    $pdf->SetFont('helvetica', '', 9);
                                    $pdf->SetXY(77.4, 179.8);
                                    $pdf->Cell(0, 10, $dureepresentiel, 0, 1, 'L');

                                    $pdf->SetFont('helvetica', '', 9);
                                    $pdf->SetXY(118, 179.8);
                                    $pdf->Cell(0, 10, $dureedistenticiel, 0, 1, 'L');
                
                                    $pdf->SetFont('helvetica', '', 8);
                                    $pdf->SetXY(17,  240.5);
                                    $pdf->Cell(0, 10,$cerfa->nomA.' '.$cerfa->prenomA, 0, 1, 'L');
                
                
                                    $pdf->SetFont('helvetica', '', 9);
                                    $pdf->SetXY(69.8,   240.5);
                                    $date_formateD = date("d/m/Y", strtotime($ligneformation->debutO));
                                    $pdf->Cell(0, 10, $date_formateD, 0, 1, 'L');
                
                                    $pdf->SetFont('helvetica', '', 9);
                                    $pdf->SetXY(102.5,  240.5);
                                    $date_formateF = date("d/m/Y", strtotime($ligneformation->prevuO));
                                    $pdf->Cell(0, 10, $date_formateF, 0, 1, 'L');

                                    $pdf->SetMargins(10, 10, 10); // Définit des marges (gauche, haut, droite) de 10 mm
                                    $pdf->SetAutoPageBreak(true, 0); // Définit une marge inférieure de 0 mm
                                    
                                    // Ligne 1
                                    $pdf->SetXY(10, 270); // Position initiale du texte
                                    $pdf->Cell(0, 10, "$ligneformation->nomF - $ligneformation->rueF $ligneformation->voieF $ligneformation->postalF  $ligneformation->communeF", 0, 1, 'C');

                                    // Ligne 2
                                    $pdf->SetXY(10, 275); // Décalage de 5 mm vers le bas
                                    $pdf->Cell(0, 10, "N° UAI $ligneformation->numeroF - Courriel $ligneformation->emailF", 0, 1, 'C');

                                    // Ligne 3
                                    $pdf->SetXY(10, 280); // Décalage de 5 mm vers le bas
                                    $pdf->Cell(0, 10, "Siret $ligneformation->siretF", 0, 1, 'C');

                                    
                                    break;
                                
                                case 2:

                                    if (!empty($ligneformation->logo)) {
                                        $imageUrl = str_replace(' ', '', $ligneformation->logo);
                                       
                                        $imagePath = tempnam(sys_get_temp_dir(), 'logo3_');
                                        file_put_contents($imagePath, file_get_contents($imageUrl));
                                        $pdf->Image($imageUrl, 80, 0, 55, 37, '', '', '', false, 200, '', false, false, 0);
                                        unlink($imagePath); // Supprime le fichier temporaire
                                    }


                                    if($prisechargeentreprise =="oui"){
                                        $pdf->SetFont('helvetica', '', 9);
                                        $pdf->SetXY(80.5,  42.5);
                                        $pdf->Cell(0, 10,$ligneformation ->prix, 0, 1, 'L');
                    
                                        $pdf->SetFont('helvetica', '', 9);
                                        $pdf->SetXY(130.5,  42.5);
                                        $pdf->Cell(0, 10,$ligneformation ->prix, 0, 1, 'L');

                                    }else{
                                        $pdf->SetFont('helvetica', '', 9);
                                        $pdf->SetXY(80.5,  42.5);
                                        $pdf->Cell(0, 10,$ligneformation ->prix, 0, 1, 'L');
                    
                                        $pdf->SetFont('helvetica', '', 9);
                                        $pdf->SetXY(130.5,  42.5);
                                        $pdf->Cell(0, 10,$ligneformation ->prix, 0, 1, 'L');                
                                       
                                        $pdf->SetFont('helvetica', '', 9);
                                        $pdf->SetXY(60.5,  188.5);
                                        $pdf->Cell(0, 10,$cerfa->lieuO, 0, 1, 'L');
    
                                        $pdf->SetFont('helvetica', '', 9);
                                        $date_formateS = date("d/m/Y", strtotime($dateRemplirConvention));
                                        $pdf->SetXY(100.5,  188.5);
                                        $pdf->Cell(0, 10,$date_formateS , 0, 1, 'L');

                                    }
                                    $pdf->SetMargins(10, 10, 10); // Définit des marges (gauche, haut, droite) de 10 mm
                                    $pdf->SetAutoPageBreak(true, 0); // Définit une marge inférieure de 0 mm
                                    
                                    // Ligne 1
                                    $pdf->SetXY(10, 270); // Position initiale du texte
                                    $pdf->Cell(0, 10, "$ligneformation->nomF - $ligneformation->rueF $ligneformation->voieF $ligneformation->postalF  $ligneformation->communeF", 0, 1, 'C');

                                    // Ligne 2
                                    $pdf->SetXY(10, 275); // Décalage de 5 mm vers le bas
                                    $pdf->Cell(0, 10, "N° UAI $ligneformation->numeroF - Courriel $ligneformation->emailF", 0, 1, 'C');

                                    // Ligne 3
                                    $pdf->SetXY(10, 280); // Décalage de 5 mm vers le bas
                                    $pdf->Cell(0, 10, "Siret $ligneformation->siretF", 0, 1, 'C');

                                    break; 

                                case 3:
                                    if (!empty($ligneformation->logo)) {
                                        $imageUrl = str_replace(' ', '', $ligneformation->logo);
                                       
                                        $imagePath = tempnam(sys_get_temp_dir(), 'logo3_');
                                        file_put_contents($imagePath, file_get_contents($imageUrl));
                                        $pdf->Image($imageUrl, 80, 0, 55, 37, '', '', '', false, 200, '', false, false, 0);
                                        unlink($imagePath); // Supprime le fichier temporaire
                                    }

                                        $pdf->SetFont('helvetica', '', 9);
                                        $pdf->SetXY(62, 101);
                                        $pdf->Cell(0, 10,$cerfa->lieuO, 0, 1, 'L');

                                        $pdf->SetFont('helvetica', '', 9);
                                        $date_formateS = date("d/m/Y", strtotime($dateRemplirConvention));
                                        $pdf->SetXY(102, 101);
                                        $pdf->Cell(0, 10,$date_formateS , 0, 1, 'L');

                                        $pdf->SetMargins(10, 10, 10); // Définit des marges (gauche, haut, droite) de 10 mm
                                        $pdf->SetAutoPageBreak(true, 0); // Définit une marge inférieure de 0 mm
                                        
                                        // Ligne 1
                                        $pdf->SetXY(10, 270); // Position initiale du texte
                                        $pdf->Cell(0, 10, "$ligneformation->nomF - $ligneformation->rueF $ligneformation->voieF $ligneformation->postalF  $ligneformation->communeF", 0, 1, 'C');

                                        // Ligne 2
                                        $pdf->SetXY(10, 275); // Décalage de 5 mm vers le bas
                                        $pdf->Cell(0, 10, "N° UAI $ligneformation->numeroF - Courriel $ligneformation->emailF", 0, 1, 'C');

                                        // Ligne 3
                                        $pdf->SetXY(10, 280); // Décalage de 5 mm vers le bas
                                        $pdf->Cell(0, 10, "Siret $ligneformation->siretF", 0, 1, 'C');

                                  
                                    break; 
                              
                                default:
                                    break;
                            }
                        }
                        ob_clean();
                        $pdf->Output($outputPath, 'F');
                        $saveConventionOpco = Cerfa::setConventionOpco(ROOT_URL.$path,$id);
                        if (file_exists($outputPath) && filesize($outputPath) > 0) {
                            if($saveConventionOpco['valid']){
                                $message = "La convention  a été Remplie avec succès.";
                                $return = array("statuts" => 0, "mes" => $message);
                            }else{
                                $message = $saveConventionOpco['error']." Le fichier PDF n'a pas été Remplie .";
                                $return = array("statuts" => 1, "mes" => $message);
                            }
                        }else{
                            $message = "Le fichier PDF n'a pas été créé correctement..";
                            $return = array("statuts" => 1, "mes" => $message);
                          
                        }
                       
                        
                    } catch (Exception $e) {
                        // Gestion des erreurs
                        $message = "Erreur lors de la modification du fichier PDF: " . $e->getMessage();
                        $return = array("statuts" => 1, "mes" => $message);
                        exit;
                    }
                           
                        
                    
                }
            } else {
                $message = "Veuillez renseigner les informations de l'employeur et de la formation";
                $return = array("statuts" => 1, "mes" => $message);
            }
        } else {
            $message = "Renseignez l'id SVP !!!";
            $return = array("statuts" => 1, "mes" => $message);
        }
    
        echo json_encode($return);
    }


    
    public static function verifierPDF($cheminFichier) {
        try {
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($cheminFichier);
            return true; // Le fichier est valide
        } catch (Exception $e) {
            return false; // Le fichier est endommagé ou non valide
        }
    }

    //remplissage de la facture 
    public function sendOpcoFacture() {
        //Privilege::hasPrivilege(Privilege::$AllView, $this->user->privilege);
        header('content-type: application/json');
        $id = $_POST['idFacture'];
        $lieuF = $_POST['lieuF'];
        $dateF = $_POST['dateF'];
        $numeroOF = $_POST['numeroOF'];
        $numeroClient = $_POST['numeroClient'];
        $ibanF = $_POST['ibanF'];
        $repreF = $_POST['repreF'];
        $emploiRF = $_POST['emploiRF'];
        $coutAB = $_POST['coutAB'];

        $motif = $_POST['motif'];
        $montant = $_POST['montant'];

        $motif1 = $_POST['motif1'];
        $montant1 = $_POST['montant1'];

        $motif2 = $_POST['motif2'];
        $montant2 = $_POST['montant2'];

        $motif3 = $_POST['motif3'];
        $montant3 = $_POST['montant3'];

        $motif4 = $_POST['motif4'];
        $montant4 = $_POST['montant4'];

        $motif5 = $_POST['motif5'];
        $montant5 = $_POST['montant5'];


        $echeance1 = $_POST['echeance1'];
        $echeance2 = $_POST['echeance2'];
        $echeance3 = $_POST['echeance3'];
        $echeance4 = $_POST['echeance4'];
        

        $date1 = $_POST['date1'];
        $date2 = $_POST['date2'];
        $date3 = $_POST['date3'];
        $date4 = $_POST['date4'];

        // $CBE1 = $_POST['CBE1'];
        // $CBE2 = $_POST['CBE2'];
        // $CBE3 = $_POST['CBE3'];
        // $CBE4 = $_POST['CBE4'];

        $ht1 = $_POST['ht1'];
        $ht2 = $_POST['ht2'];
        $ht3 = $_POST['ht3'];
        $ht4 = $_POST['ht4'];

       
        $montant = !empty($montant) ? floatval($montant) : 0;
        $montant1 = !empty($montant1) ? floatval($montant1) : 0;
        $montant2 = !empty($montant2) ? floatval($montant2) : 0;
        $montant3 = !empty($montant3) ? floatval($montant3) : 0;
        $montant4 = !empty($montant4) ? floatval($montant4) : 0;
        $montant5 = !empty($montant5) ? floatval($montant5) : 0;

        // Additionner les montants
        $montantTotal = $montant + $montant1 + $montant2 + $montant3 + $montant4 + $montant5;

         
       



        if (isset($id) && !empty($id)) {
            $cerfa = Cerfa::find($id);
            $ligneemployeur = Entreprise::find($cerfa['data']->idemployeur);
            $ligneformation = Formation::find($cerfa['data']->idformation);
            if ($ligneemployeur['valid'] && $ligneformation['valid']) {
                $opco = Opco::find($ligneemployeur['data']->idopco);
                if (!$opco['valid']) {
                    $message = "Veuillez rattacher cette entreprise à un Opco";
                    $return = array("statuts" => 1, "mes" => $message);
                } else {
                    $opco = $opco['data'];
                    $ligneemployeur = $ligneemployeur['data'];
                    $ligneformation = $ligneformation['data'];
                    $cerfa = $cerfa['data'];
                    // Préparez le fichier à envoyer
                   
                        $filePath  = PATH_FILE;
                        $filePath .=  '/public/' . 'assets/pdf/dossier_prise.pdf';
                        
                       
                        $outputPath  = PATH_FILE;
                        $outputPath  .= '/public/' . 'assets/factureOpco' . '/' . str_replace('\\', '/', $id."dossier_prise_en_charge.pdf");
                        $path= 'public/' . 'assets/factureOpco' . '/' . str_replace('\\', '/', $id."dossier_prise_en_charge.pdf");


                        $fichierValide = self::verifierPDF( $filePath);
                        if (!$fichierValide) {
                            $message = "Le fichier PDF Renseigner est Endommagé Veuillez Le reparer.";
                            $return = array("statuts" => 1, "mes" => $message);
                        }else{
                            
                                try {
                                // Crée une instance de FPDI
                                    $pdf = new Fpdi();
                                
                                    // Ajoute une page du PDF existant
                                    $pageCount = $pdf->setSourceFile(StreamReader::createByFile($filePath));
                                
                                    for ($i = 1; $i <= $pageCount; $i++) {
                                        $tplIdx = $pdf->importPage($i);
                                        $pdf->addPage();
                                        $pdf->useTemplate($tplIdx, 0, 0);
                                
                                        // Définir la police
                                        $pdf->SetFont('helvetica', '', 12);
                                
                                        // Ajouter du texte spécifique à chaque page
                                        switch ($i) {
                                            case 1:
                                                // numero dossier numero Externe
                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(50, 120.4);
                                                $pdf->Cell(0, 10,$cerfa->numeroExterne );

                                                // nermero client
                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(54, 126.7);
                                                $pdf->Cell(0, 10, $numeroClient);

                                                // numero Deca
                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(46, 133);
                                                $pdf->Cell(0, 10, $cerfa->numeroDeca);




                                                    // Intitulé de la formation + RNCP
                                                    $pdf->SetFont('helvetica', '', 9);
                                                    $pdf->SetXY(67, 188);
                                                    $pdf->Cell(0, 10, 'RNCP'.$ligneformation->rnF.'  TITRE PROFESSIONNEL  '.$ligneformation->intituleF);

                                                // Diplôme ou titre visé
                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(67, 192);
                                                $pdf->Cell(0, 10, $ligneformation->diplomeF);




                                                // Cout annuel de la branche  
                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(67, 196);
                                                $pdf->Cell(0, 10, $coutAB);

                                                // Date de contrat de debut de contrat et date de fin 
                                                $date_debutC = date("d/m/Y", strtotime($cerfa->debutC));
                                                $date_finC = date("d/m/Y", strtotime($cerfa->finC));
                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(67, 201);
                                                $pdf->Cell(0, 10, 'Du'." ". $date_debutC." "."au"." ".$date_finC);

                                                // Date de formation de debut de contrat et date de fin 
                                                $date_debutO = date("d/m/Y", strtotime($ligneformation->debutO));
                                                $date_prevuO = date("d/m/Y", strtotime($ligneformation->prevuO));
                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(67, 205);
                                                $pdf->Cell(0, 10, 'Du'." ".$date_debutO." "."au"." ".$date_prevuO);

                                                // duree formation en heures
                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(67, 209);
                                                $pdf->Cell(0, 10, $ligneformation->dureO." "."heures");

                                                // nom employeurs
                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(67, 213);
                                                $pdf->Cell(0, 10, $ligneemployeur->nomE);

                                                // Apprenti
                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(67, 217);
                                                $pdf->Cell(0, 10, $cerfa->nomA." ".$cerfa->prenomA." , ".$cerfa->rueA." ".$cerfa->voieA." ".$cerfa->postalA." ".$cerfa->communeA);

                                                break;
                                            case 2:
                                                // cout1 selectionner paraport au motif
                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(134, 13.5);
                                                $pdf->Cell(0, 10, $montant);
                                                
                                                
                                                if($montant1!=0){
                                                    // cout2 selectionner paraport au motif
                                                    $pdf->SetFont('helvetica', '', 9);
                                                    $pdf->SetXY(134, 18.5);
                                                    $pdf->Cell(0, 10, $montant1);
                                                }
                                                
                                                if($montant2!=0){
                                                    // cout3 selectionner paraport au motif
                                                    $pdf->SetFont('helvetica', '', 9);
                                                    $pdf->SetXY(134, 23.5);
                                                    $pdf->Cell(0, 10, $montant2);
                                                }

                                                if($montant3!=0){
                                                    // cout4 selectionner paraport au motif
                                                    $pdf->SetFont('helvetica', '', 9);
                                                    $pdf->SetXY(134, 28.5);
                                                    $pdf->Cell(0, 10, $montant3);
                                                }
                                                
                                                if($montant4!=0){
                                                    // cout5 selectionner paraport au motif
                                                    $pdf->SetFont('helvetica', '', 9);
                                                    $pdf->SetXY(134, 33.5);
                                                    $pdf->Cell(0, 10, $montant4);
                                                }
                                            
                                                if($montant5!=0){
                                                    // cout6 selectionner paraport au motif
                                                    $pdf->SetFont('helvetica', '', 9);
                                                    $pdf->SetXY(134, 38.5);
                                                    $pdf->Cell(0, 10, $montant5);
                                                }
                                                

                                                
                                                // cout Total HT 
                                                if( empty($motif1) && empty($motif2) &&empty($motif3) && empty($motif4) && empty($motif5) ){
                                                    $pdf->SetFont('helvetica', '', 9);
                                                    $pdf->SetXY(174, 18.5);
                                                    $pdf->Cell(0, 10, $montantTotal);
                                                    }elseif(empty($motif2) &&empty($motif3) && empty($motif4) && empty($motif5) ){
                                                    $pdf->SetFont('helvetica', '', 9);
                                                    $pdf->SetXY(174, 23.5);
                                                    $pdf->Cell(0, 10, $montantTotal);
                                                    }elseif(empty($motif3) && empty($motif4) && empty($motif5) ){
                                                    $pdf->SetFont('helvetica', '', 9);
                                                    $pdf->SetXY(174, 28.5);
                                                    $pdf->Cell(0, 10, $montantTotal);
                                                    }elseif(empty($motif4) && empty($motif5) ){
                                                    $pdf->SetFont('helvetica', '', 9);
                                                    $pdf->SetXY(174, 33.5);
                                                    $pdf->Cell(0, 10, $montantTotal);
                                                    }elseif(empty($motif5) ){
                                                    $pdf->SetFont('helvetica', '', 9);
                                                    $pdf->SetXY(174, 38.5);
                                                    $pdf->Cell(0, 10,$montantTotal);
                                                    }else{
                                                    $pdf->SetFont('helvetica', '', 9);
                                                    $pdf->SetXY(174, 43.5);
                                                    $pdf->Cell(0, 10,$montantTotal);
                                                    }
                                                
                                                break;
                                            
                                            case 4:
                                                //numero dossier numero Externe 
                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(50, 77.8);
                                                $pdf->Cell(0, 10, $cerfa->numeroExterne);

                                                // numero Deca
                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(46, 80.5);
                                                $pdf->Cell(0, 10, $cerfa->numeroDeca.'deca');

                                                // cout de la branche engage 
                                                //ligne une  echeance1 + dat1 + CBE1 + ht1

                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(30, 137);
                                                $pdf->Cell(0, 10, $date1);

                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(75, 137);
                                                $pdf->Cell(0, 10, $echeance1);

                                                // $pdf->SetFont('helvetica', '', 9);
                                                // $pdf->SetXY(120, 137);
                                                // $pdf->Cell(0, 10, $CBE1);

                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(158, 137);
                                                $pdf->Cell(0, 10, $ht1);

                                                //ligne une CBE2 + ht2 +date2  + echeance2

                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(30, 148);
                                                $pdf->Cell(0, 10, $date2);

                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(75, 148);
                                                $pdf->Cell(0, 10, $echeance2);

                                                // $pdf->SetFont('helvetica', '', 9);
                                                // $pdf->SetXY(120, 148);
                                                // $pdf->Cell(0, 10, $CBE2);
                        
                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(158, 148);
                                                $pdf->Cell(0, 10, $ht2);

                                                //ligne une CBE3 + ht3 + date3 + echeance3

                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(30, 160);
                                                $pdf->Cell(0, 10, $date3);

                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(75, 160);
                                                $pdf->Cell(0, 10, $echeance3);



                                                // $pdf->SetFont('helvetica', '', 9);
                                                // $pdf->SetXY(120, 160);
                                                // $pdf->Cell(0, 10, $CBE3);
                        
                                                $pdf->SetFont('helvetica', '', 9);
                                                $pdf->SetXY(158, 160);
                                                $pdf->Cell(0, 10, $ht3);

                                                
                                                // if(!empty($CBE4)){
                                                // //ligne une CBE4 + ht4 + date4 + echeance4

                                                // $pdf->SetFont('helvetica', '', 9);
                                                // $pdf->SetXY(30, 171);
                                                // $pdf->Cell(0, 10, $date4);

                                                // $pdf->SetFont('helvetica', '', 9);
                                                // $pdf->SetXY(75, 171);
                                                // $pdf->Cell(0, 10, $echeance4);

                                                // // $pdf->SetFont('helvetica', '', 9);
                                                // // $pdf->SetXY(120, 171);
                                                // // $pdf->Cell(0, 10, $CBE4);
                        
                                                // $pdf->SetFont('helvetica', '', 9);
                                                // $pdf->SetXY(158, 171);
                                                // $pdf->Cell(0, 10, $ht4);
                                                // }

                                                break; 
                                            // case 5:
                                            //     //numero OF formation
                                            //     $pdf->SetFont('helvetica', '', 9);
                                            //     $pdf->SetXY(43.5, 29);
                                            //     $pdf->Cell(0, 10, $numeroOF);

                                            //     //numero de siret formation
                                            //     $pdf->SetFont('helvetica', '', 9);
                                            //     $pdf->SetXY(50, 32);
                                            //     $pdf->Cell(0, 10, $ligneformation->siretF);

                                            //     //numero UAI formation
                                            //     $pdf->SetFont('helvetica', '', 9);
                                            //     $pdf->SetXY(43, 35.4);
                                            //     $pdf->Cell(0, 10, $ligneformation->numeroF);

                                            //     //lieu signature
                                            //     $pdf->SetFont('helvetica', '', 9);
                                            //     $pdf->SetXY(163.5, 19.5);
                                            //     $pdf->Cell(0, 10, $lieuF);

                                            //     //Date signature
                                            //     $date_dateF = date("d/m/Y", strtotime($dateF));
                                            //     $pdf->SetFont('helvetica', '', 9);
                                            //     $pdf->SetXY(160, 23.5);
                                            //     $pdf->Cell(0, 10, $date_dateF);

                                            //     //numero Facture
                                            //     $rang= str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                                            //     $pdf->SetFont('helvetica', '', 9);
                                            //     $pdf->SetXY(115, 75);
                                            //     $pdf->Cell(0, 10, ' FERP'.$rang);

                                            //     //Iban 
                                            //     $pdf->SetFont('helvetica', '', 9);
                                            //     $pdf->SetXY(51.5, 89.5);
                                            //     $pdf->Cell(0, 10, $ibanF);

                                            //     //reference dossier qui correspond au numero Externe du dossier 
                                            //     $pdf->SetFont('helvetica', '', 10);
                                            //     $pdf->SetXY(52.5, 102.5);
                                            //     $pdf->Cell(0, 10, $cerfa->numeroExterne);

                                            //     //montant echeance 1(0) 
                                            //     $pdf->SetFont('helvetica', '', 10);
                                            //     $pdf->SetXY(160, 111);
                                            //     $pdf->Cell(0, 10, $ht1);

                                            //     //prenom Apprenant 
                                            //     $pdf->SetFont('helvetica', '', 10);
                                            //     $pdf->SetXY(40.5, 135.5);
                                            //     $pdf->Cell(0, 10, $cerfa->prenomA);

                                            //     //nom Apprenant 
                                            //     $pdf->SetFont('helvetica', '', 10);
                                            //     $pdf->SetXY(35, 139.3);
                                            //     $pdf->Cell(0, 10, $cerfa->nomA);

                                            //     //Total montant echeance 1(0) 
                                            //     $pdf->SetFont('helvetica', '', 10);
                                            //     $pdf->SetXY(140, 154);
                                            //     $pdf->Cell(0, 10,  $ht1);

                                            //     //representant cfa 
                                            //     $pdf->SetFont('helvetica', '', 9);
                                            //     $pdf->SetXY(80, 188.2);
                                            //     $pdf->Cell(0, 10, $repreF);

                                            //     //poste representant cfa 
                                            //     $pdf->SetFont('helvetica', '', 9);
                                            //     $pdf->SetXY(30, 192.2);
                                            //     $pdf->Cell(0, 10, $emploiRF);

                                            //     break;
                                            // Ajouter d'autres cas pour les pages suivantes
                                            default:
                                                break;
                                        }
                                    }
                                    
                                    $pdf->Output($outputPath, 'F');
                                    $saveFactureOpco = Cerfa::setFactureOpco(ROOT_URL.$path,$id);
                                    $saveFactureEcheance = Cerfa::setFactureEcheance($numeroOF, $lieuF, $ibanF, $repreF, $emploiRF, $motif, $motif1, $motif2, $motif3, $motif4, $motif5, $montant,
                                    $montant1, $montant2, $montant3, $montant4, $montant5, $echeance1, $echeance2, $echeance3, $echeance4, $date1, $date2,
                                    $date3, $date4, $ht1, $ht2, $ht3, $ht4, $id);
                                    if (file_exists($outputPath) && filesize($outputPath) > 0) {
                                            if($saveFactureOpco['valid'] && $saveFactureEcheance['valid']){
                                                $message = "Le fichier PDF a été Remplie avec succès.";
                                                $return = array("statuts" => 0, "mes" => $message);
                                            }else{
                                                $message = $saveFactureOpco['error']." Le fichier PDF n'a pas été Remplie .";
                                                $return = array("statuts" => 1, "mes" => $message);
                                            }

                                    }else{
                                            $message = "Le fichier PDF n'a pas été créé correctement..";
                                            $return = array("statuts" => 1, "mes" => $message);
                                        
                                    }
                                        
                                } catch (Exception $e) {
                                    // Gestion des erreurs
                                    $message = "Erreur lors de la modification du fichier PDF: " . $e->getMessage();
                                    $return = array("statuts" => 1, "mes" => $message);
                                    exit;
                                }
                        }
                        
                            
                              
                           
                        }
                    
                
            } else {
                $message = "Veuillez renseigner les informations de l'employeur et de la formation";
                $return = array("statuts" => 1, "mes" => $message);
            }
        } else {
            $message = "Renseignez l'id SVP !!!";
            $return = array("statuts" => 1, "mes" => $message);
        }
    
        echo json_encode($return);
    }

    // remplissage d'une echeance 

   
    
        public function remplirEcheance() {
        header('content-type: application/json');
        $id = $_POST['idremplirEcheance'];    
        $date = $_POST['dateEmissionEcheance'];
        $selectEcheance = $_POST['selectEcheance'];
        $numeroFacture = $_POST['numeroFacture']; 
    
       
        $dateDebutCertificat = isset($_POST['dateDebutCertificat']) ? $_POST['dateDebutCertificat'] : null;
        $dateFinCertificat = isset($_POST['dateFinCertificat']) ? $_POST['dateFinCertificat'] : null;
        $duree = isset($_POST['duree']) ? $_POST['duree'] : null;
    
        if (isset($id) && !empty($id)) {
            $cerfa = Cerfa::find($id);
            $factureCerfa = Cerfa::findFactureByIdCerfa($id);
            $ligneemployeur = Entreprise::find($cerfa['data']->idemployeur);
            $ligneformation = Formation::find($cerfa['data']->idformation);
    
            if ($ligneemployeur['valid'] && $ligneformation['valid']) {
                $opco = Opco::find($ligneemployeur['data']->idopco);
                if (!$opco['valid']) {
                    $message = "Veuillez rattacher cette entreprise à un Opco";
                    $return = array("statuts" => 1, "mes" => $message);
                } else {
                    $factureCerfa = $factureCerfa['data'];
                    $opco = $opco['data'];
                    $ligneemployeur = $ligneemployeur['data'];
                    $ligneformation = $ligneformation['data'];
                    $cerfa = $cerfa['data'];
    
                  
    
                       
                            $outputPath = PATH_FILE . '/public/assets/echeance/' . str_replace('\\', '/', $id."facture.pdf");
                            $path = 'public/assets/echeance/' . str_replace('\\', '/', $id."facture.pdf");

                            $filePath  = PATH_FILE;
                            $filePath .=  '/public/' . 'assets/pdf/facture.pdf';

                            $filePathCertificat  = PATH_FILE;
                            $filePathCertificat .=  '/public/' . 'assets/pdf/certificat_realisation.pdf';
    
                            $fichierFacture = self::verifierPDF($filePath);
                           
                            
                            $fichierCertificat = self::verifierPDF($filePathCertificat);
                            if (!$fichierCertificat) {
                                $message = "Le fichier Certificat PDF renseigné est endommagé. Veuillez le réparer.";
                                $return = array("statuts" => 1, "mes" => $message);
                                echo json_encode($return);
                                exit;
                            }
                            
    
                            if (!$fichierFacture) {
                                $message = "Le fichier PDF renseigné est endommagé. Veuillez le réparer.";
                                $return = array("statuts" => 1, "mes" => $message);
                            } else {
                                switch ($selectEcheance) {
                                    case 1:
                                        $ht = $factureCerfa->ht1;
                                        $dateF = $factureCerfa->date1;
                                        break;
                                    case 2:
                                        $ht = $factureCerfa->ht2;
                                        $dateF = $factureCerfa->date2;
                                        break;
                                    case 3:
                                        $ht = $factureCerfa->ht3;
                                        $dateF = $factureCerfa->date3;
                                        break;
                                    case 4:
                                        $ht = $factureCerfa->ht4;
                                        $dateF = $factureCerfa->date4;
                                        break;
                                    default:
                                        $message = "Sélection d'échéance invalide.";
                                        $return = array("statuts" => 1, "mes" => $message);
                                        echo json_encode($return);
                                        exit;
                                }
    
                                try {
                                    $pdf = new Fpdi();
    
                                    // Ajout des pages du certificat
                                    if ($selectEcheance != "1") {
                                        $pageCount1 = $pdf->setSourceFile(StreamReader::createByFile($filePathCertificat));
                                        for ($i = 1; $i <= $pageCount1; $i++) {
                                            $tplIdx = $pdf->importPage($i);
                                            $pdf->addPage();
                                            $pdf->useTemplate($tplIdx, 0, 0);

                                            $pdf->SetFillColor(255, 255, 255); // Couleur blanche
                                            $pdf->Rect(0, 0, $pdf->GetPageWidth(), 10, 'F'); // Masque en haut
                                            $pdf->Rect(0, $pdf->GetPageHeight() - 10, $pdf->GetPageWidth(), 10, 'F'); // Masque en bas

                                            
                                            $pdf->SetDrawColor(0, 0, 255); // Couleur bleue
                                            $pdf->Rect(10, 10, 193, 278); // Dessine une boîte de 10 mm de marges autour du contenu principal


                                           
                                            // Définir la police
                                           $pdf->SetFont('helvetica', '', 12);

                                           if ($i == 1) {

                                            if (!empty($ligneformation->logo)) {
                                                $imageUrl = str_replace(' ', '', $ligneformation->logo);
                                               
                                                $imagePath = tempnam(sys_get_temp_dir(), 'logo3_');
                                                file_put_contents($imagePath, file_get_contents($imageUrl));
                                                $pdf->Image($imageUrl, 80, 5, 55, 42, '', '', '', false, 200, '', false, false, 0);
                                                unlink($imagePath); // Supprime le fichier temporaire
                                            }
                                            

                                           
                                           
                                            // Représentant CFA
                                            $pdf->SetFont('helvetica', '', 9);
                                            $pdf->SetXY(37, 51);
                                            $pdf->Cell(0, 10, $factureCerfa->repreF);
                                            
                                            //  centre de formation
                                            $pdf->SetFont('helvetica', '', 10);
                                            $pdf->SetXY(46.5, 56);
                                            $pdf->Cell(0, 10, $ligneformation->nomF);
    
                                           
                                             // nom Etudiant
                                             $pdf->SetFont('helvetica', '', 9);
                                             $pdf->SetXY(20, 70);
                                             $pdf->Cell(0, 10, $cerfa->nomA . ' '. $cerfa->prenomA);
    
                                             // nom Entreprise
                                             $pdf->SetFont('helvetica', '', 9);
                                             $pdf->SetXY(56, 82);
                                             $pdf->Cell(0, 10, $ligneemployeur->nomE);
                                            
    
                                            // intituleF
                                            $pdf->SetFont('helvetica', '', 9);
                                            $pdf->SetXY(40, 89.5);
                                            $pdf->Cell(0, 10, $ligneformation->intituleF);
    
    
                                            // Date de debut 
                                            $date_dateD = date("d/m/Y", strtotime($dateDebutCertificat));
                                            $pdf->SetFont('helvetica', '', 8.8);
                                            $pdf->SetXY(51.7, 132);
                                            $pdf->Cell(0, 10, $date_dateD);

                                             // Date de fin 
                                             $date_dateF = date("d/m/Y", strtotime($dateFinCertificat));
                                             $pdf->SetFont('helvetica', '', 9);
                                             $pdf->SetXY(73, 132);
                                             $pdf->Cell(0, 10, $date_dateF);
    
                                            
    
                                            // duree
                                            $pdf->SetFont('helvetica', '', 8);
                                            $pdf->SetXY(48, 137);
                                            $pdf->Cell(0, 10, $duree);
    
                                             // lieu creation
                                             $pdf->SetFont('helvetica', '', 9);
                                             $pdf->SetXY(30, 177);
                                             $pdf->Cell(0, 10, $factureCerfa->lieuF);
                                           
                                            // date creation
                                            $date_dateC = date("d/m/Y", strtotime($date));
                                            $pdf->SetFont('helvetica', '', 9);
                                            $pdf->SetXY(25, 183);
                                            $pdf->Cell(0, 10, $date_dateC );

                                            // signature du certificat 
                                            if(!empty($cerfa->signatureEcole)){
                                                $imageUrl =$cerfa->signatureEcole;
                                                $urlSegments = explode('/', $imageUrl);
                                                $relativePath = implode('/', array_slice($urlSegments, 7));
                                              
                                                $path = PATH_FILE;
                                                $path .= '/public/' . 'assets/signatureEcole' . '/' . str_replace('\\', '/', $relativePath);
                                                $fileContent = file_get_contents($path);
                                                if(empty($fileContent)){
                                                    var_die("Le fichier est vide : " . $imageUrl);
                                                }else{
                                                $imagePath = tempnam(sys_get_temp_dir(), 'image5_');
                                                if (file_exists($path)) {
                                                    file_put_contents($imagePath, file_get_contents( $path));
                                                    $pdf->Image($imagePath, 150, 208, 50, 22, '', '', '', false, 200, '', false, false, 0);
                                                } else {
                                                    var_die("Le fichier de signature n'existe pas : " . $imageUrl);
                                                }
                                              }
                                            }

                                            

                                            //echo "Largeur: $pageWidth, Hauteur: $pageHeight\n"; // Par défaut, A4 = 210mm x 297mm
                                            $pdf->SetMargins(10, 10, 10); // Définit des marges (gauche, haut, droite) de 10 mm
                                            $pdf->SetAutoPageBreak(true, 0); // Définit une marge inférieure de 0 mm
                                            
                                            // Ligne 1
                                            $pdf->SetXY(10, 270); // Position initiale du texte
                                            $pdf->Cell(0, 10, "$ligneformation->nomF - $ligneformation->rueF $ligneformation->voieF $ligneformation->postalF  $ligneformation->communeF", 0, 1, 'C');

                                            // Ligne 2
                                            $pdf->SetXY(10, 275); // Décalage de 5 mm vers le bas
                                            $pdf->Cell(0, 10, "N° UAI $ligneformation->numeroF - Courriel $ligneformation->emailF", 0, 1, 'C');

                                            // Ligne 3
                                            $pdf->SetXY(10, 280); // Décalage de 5 mm vers le bas
                                            $pdf->Cell(0, 10, "Siret $ligneformation->siretF", 0, 1, 'C');




                                            
    
    
                                            }
                                        }
                                    }
    
                                    

                                    $montantTotal = (double)$ht;


                                    // Ajout des pages de la facture
                                    $pageCount = $pdf->setSourceFile(StreamReader::createByFile($filePath));
                                    for ($i = 1; $i <= $pageCount; $i++) {
                                        $tplIdx = $pdf->importPage($i);
                                        $pdf->addPage();
                                        $pdf->useTemplate($tplIdx, 0, 0);
                                        $pdf->SetFillColor(255, 255, 255); // Couleur blanche
                                        $pdf->Rect(0, 0, $pdf->GetPageWidth(), 10, 'F'); // Masque en haut
                                        $pdf->Rect(0, $pdf->GetPageHeight() - 10, $pdf->GetPageWidth(), 10, 'F'); // Masque en bas
    
                                        // Définir la police
                                        $pdf->SetFont('helvetica', '', 12);
    
                                        // Ajouter du texte spécifique à chaque page
                                        if ($i == 1) {
                                            // Nom formation
                                            $pdf->SetFont('helvetica', '', 10);
                                            $pdf->SetXY(24.2, 21.7);
                                            $pdf->Cell(0, 10, $ligneformation->nomF);
    
                                            // Numéro OF formation
                                            $pdf->SetFont('helvetica', '', 9);
                                            $pdf->SetXY(43.5, 29);
                                            $pdf->Cell(0, 10, $factureCerfa->numeroOF);
    
                                            // Numéro de SIRET formation
                                            $pdf->SetFont('helvetica', '', 9);
                                            $pdf->SetXY(50, 32);
                                            $pdf->Cell(0, 10, $ligneformation->siretF);
    
                                            // Numéro UAI formation
                                            $pdf->SetFont('helvetica', '', 9);
                                            $pdf->SetXY(43, 35.4);
                                            $pdf->Cell(0, 10, $ligneformation->numeroF);
    
                                            // Adresse formation
                                            $pdf->SetFont('helvetica', '', 10);
                                            $pdf->SetXY(24.2, 43);
                                            $pdf->Cell(0, 10, $ligneformation->rueF . ' ' . $ligneformation->voieF);
                                            $pdf->SetXY(24.2, 47);
                                            $pdf->Cell(0, 10, $ligneformation->postalF . ' ' . $ligneformation->communeF);
    
                                            // Lieu signature
                                            $pdf->SetFont('helvetica', '', 9);
                                            $pdf->SetXY(163.5, 19.5);
                                            $pdf->Cell(0, 10, $factureCerfa->lieuF);
    
                                            // Date signature
                                            $date_dateF = date("d/m/Y", strtotime($date));
                                            $pdf->SetFont('helvetica', '', 9);
                                            $pdf->SetXY(160, 23.5);
                                            $pdf->Cell(0, 10, $date_dateF);

                                            //Adresse opco 
                                            if($opco->nom == "Akto"){
                                                $nom = "Akto";
                                                $adresse= "14 rue Riquet";
                                                $ville = "75019 Paris";
                                            }elseif($opco->nom == "Atlas"){
                                                $nom = "Atlas";
                                                $adresse= "25 quai Panhard et Levassor";
                                                $ville = "75019 Paris";
                                            }elseif($opco->nom == "AFDAS"){
                                                $nom = "AFDAS";
                                                $adresse= "66, rue Stendhal – CS 32016";
                                                $ville = "75990 Paris Cedex 20";
                                            }
                                            $pdf->SetFont('helvetica', '', 10);
                                            $pdf->SetXY(153, 43);
                                            $pdf->Cell(0, 10, $nom);
                                            $pdf->SetXY(153, 47);
                                            $pdf->Cell(0, 10, $adresse);
                                            $pdf->SetXY(153, 51);
                                            $pdf->Cell(0, 10, $ville);


    
                                            // Numéro Facture
                                            $rang = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                                            $numeroFactureInsert =  empty($numeroFacture)? 'FERP' . $rang : $numeroFacture;
                                            // Définir la couleur du texte en blanc
                                            $pdf->SetTextColor(255, 255, 255);
                                            $pdf->SetFont('helvetica', '', 9);
                                            $pdf->SetXY(115, 75);
                                            $pdf->Cell(0, 10,$numeroFactureInsert);
    
                                            // IBAN
                                            $pdf->SetTextColor(0, 0, 0);
                                            $pdf->SetFont('helvetica', '', 9);
                                            $pdf->SetXY(51.5, 89.5);
                                            $pdf->Cell(0, 10, $factureCerfa->ibanF);
    
                                            // Référence dossier
                                            $pdf->SetFont('helvetica', '', 10);
                                            $pdf->SetXY(52.5, 102.5);
                                            $pdf->Cell(0, 10, $cerfa->numeroExterne);
    
                                            // Numéro de l'échéance
                                            $pdf->SetFont('helvetica', '', 10);
                                            $pdf->SetXY(65, 111);
                                            $pdf->Cell(0, 10, $selectEcheance);

                                            // Date de l'échéance
                                            $date_dateE = date("d/m/Y", strtotime($dateF));
                                            $pdf->SetFont('helvetica', '', 10);
                                            $pdf->SetXY(105, 111);
                                            $pdf->Cell(0, 10, "Du ".$date_dateE);

                                            // Montant de l'échéance
                                            $pdf->SetFont('helvetica', '', 10);
                                            $pdf->SetXY(160, 111);
                                            $pdf->Cell(0, 10, $ht);


                                          

                                            if (intval($selectEcheance) === 1 && !empty($factureCerfa->motif1)) {

                                            // Nom Premier equipement
                                            $pdf->SetFont('helvetica', '', 10);
                                            $pdf->SetXY(24, 117);
                                            $pdf->Cell(0, 10, "Premier Equipement ");

                                             // Montant Premier equipement
                                             $montantTotal += (double)$factureCerfa->montant1;
                                             $pdf->SetFont('helvetica', '', 10);
                                             $pdf->SetXY(160, 117);
                                             $pdf->Cell(0, 10,(double)$factureCerfa->montant1);
                                           }

                                           $motifRend2 = $_POST['motifRemplir2'];
                                           $motifRend3 = $_POST['motifRemplir3'];
                                           $motifRend4 = $_POST['motifRemplir4'];
                                           $motifRend5 = $_POST['motifRemplir5'];
       
       
                                           $motifsRend = [
                                            'motifRend2' => $motifRend2,
                                            'motifRend3' => $motifRend3,
                                            'motifRend4' => $motifRend4,
                                            'motifRend5' => $motifRend5
                                        ];
                                        
                                        // Position de départ pour l'affichage des motifs
                                        $yPosition = 117;
                                        
                                        // Boucle sur les motifs récupérés
                                        foreach ($motifsRend as $motifRendKey => $motifRendValue) {
                                            if (intval($selectEcheance) !== 1 && !empty($motifRendValue)) {
                                                // Boucle sur les motifs disponibles dans la base de données
                                                for ($i = 2; $i <= 5; $i++) {
                                                    $motifKey = 'motif' . $i;
                                                    $montantKey = 'montant' . $i;
                                                    
                                                    // Vérifier si le motif récupéré correspond au motif dans la base de données
                                                    if ($motifRendValue == $factureCerfa->$motifKey) {
                                                        $montantTotal += (double)$factureCerfa->$montantKey; // Ajouter le montant au montant total
                                        
                                                        // Afficher le nom sélectionné dans le PDF
                                                        $pdf->SetFont('helvetica', '', 10);
                                                        $pdf->SetXY(24, $yPosition); // Utiliser la variable $yPosition
                                                        $pdf->Cell(0, 10, $motifRendValue);
                                        
                                                        // Afficher le montant correspondant
                                                        $pdf->SetFont('helvetica', '', 10);
                                                        $pdf->SetXY(160, $yPosition); // Utiliser la variable $yPosition
                                                        $pdf->Cell(0, 10, $factureCerfa->$montantKey);
                                        
                                                        // Déplacer la position Y vers le bas pour la prochaine ligne
                                                        $yPosition += 15; // Ajuster la valeur de l'incrément si nécessaire
                                        
                                                        break; // Arrêter la boucle si une correspondance a été trouvée
                                                    }
                                                }
                                            }
                                        }
                                        
                                        


    
                                           
    
                                            // Prénom Apprenant
                                            $pdf->SetFont('helvetica', '', 10);
                                            $pdf->SetXY(40.5, 142);
                                            $pdf->Cell(0, 10, $cerfa->prenomA);
    
                                            // Nom Apprenant
                                            $pdf->SetFont('helvetica', '', 10);
                                            $pdf->SetXY(35, 147.6);
                                            $pdf->Cell(0, 10, $cerfa->nomA);
    
                                            // Total montant échéance
                                            $pdf->SetFont('helvetica', '', 10);
                                            $pdf->SetXY(140, 154);
                                            $pdf->Cell(0, 10, $montantTotal);
                                           
    
                                            // Représentant CFA
                                            $pdf->SetFont('helvetica', '', 9);
                                            $pdf->SetXY(80, 188.2);
                                            $pdf->Cell(0, 10, $factureCerfa->repreF);
    
                                            // Poste représentant CFA
                                            $pdf->SetFont('helvetica', '', 9);
                                            $pdf->SetXY(30, 192.2);
                                            $pdf->Cell(0, 10, $factureCerfa->emploiRF);
    
                                            // Nom Formation
                                            $pdf->SetFont('helvetica', '', 8);
                                            $pdf->SetXY(81.5, 196.5);
    
                                            // Utiliser MultiCell avec une largeur définie
                                            $maxWidth = 50;
                                            $pdf->MultiCell($maxWidth, 5, $ligneformation->nomF, 0, 'L');
                                        }
                                    }
    
                                    $fileName = ($cerfa->nomA == "") ?
                                        $selectEcheance.$id.$cerfa->emailA . '_Pdf_factures' . date('Y-m-d_i:s') . '_' . time() . '.pdf' :
                                        $selectEcheance.$id.$cerfa->nomA . '_Pdf_factures' . date('Y-m-d_i:s') . '_' . time() . '.pdf';
    
                                    $outputPath = PATH_FILE . '/public/assets/echeance/' . str_replace('\\', '/', $fileName);
                                    $pdf->Output($outputPath, 'F');
    
                                    $url = ROOT_URL . 'public/assets/echeance/' . str_replace('\\', '/', $fileName);
                                    $return = array("statuts" => 0, "mes" => "Le fichier PDF a été rempli avec succès.", "url" => $url);
                                } catch (Exception $e) {
                                    $message = "Erreur lors de la modification du fichier PDF: " . $e->getMessage();
                                    $return = array("statuts" => 1, "mes" => $message.$filePath);
                                }
                            }
                        
                    
                        }
            } else {
                $message = "Veuillez renseigner les informations de l'employeur et de la formation";
                $return = array("statuts" => 1, "mes" => $message);
            }
        } else {
            $message = "Renseignez l'id SVP !!!";
            $return = array("statuts" => 1, "mes" => $message);
        }
    
        echo json_encode($return);
    }
    
    


   // envoie de la facture de l'echeance

    public function sendFacture() {
        //Privilege::hasPrivilege(Privilege::$AllView, $this->user->privilege);
        header('content-type: application/json');
        $id = $_POST['idFactureSend'];
        $idProduitCerfas = $_POST['idProduitCerfas'];
        $selectSendEcheance = $_POST['selectSendEcheance'];
        $dateEmissionFactureEcheance = $_POST['dateEmissionFactureEcheance'];
        $numeroFactureEcheance = $_POST['numeroFactureEcheance'];

       

        if (isset($id) && !empty($id) && isset($idProduitCerfas) && !empty($idProduitCerfas) ) {
            $cerfa = Cerfa::find($id);
            $factureCerfa = Cerfa::findFactureByIdCerfa($id);
            $ligneemployeur = Entreprise::find($cerfa['data']->idemployeur);
            $ligneformation = Formation::find($cerfa['data']->idformation);
            if ($ligneemployeur['valid'] && $ligneformation['valid']) {
                $opco = Opco::find($ligneemployeur['data']->idopco);
                if (!$opco['valid']) {
                    $message = "Veuillez rattacher cette entreprise à un Opco";
                    $return = array("statuts" => 1, "mes" => $message);
                } else {
                    $factureCerfa = $factureCerfa['data'];
                    $opco = $opco['data'];
                    $ligneemployeur = $ligneemployeur['data'];
                    $ligneformation = $ligneformation['data'];
                    $cerfa = $cerfa['data'];


                    if (isset($_FILES['fileFactureEcheanceSend']) && $_FILES['fileFactureEcheanceSend']['error'] == 0) {
                        $allowed = ['pdf'];
                        $filePath = $_FILES['fileFactureEcheanceSend']['tmp_name'];
                      
                        $fileType = mime_content_type($filePath);
                        $fileName = basename($_FILES['fileFactureEcheanceSend']['name']);
                        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    
                        if (!in_array($fileExt, $allowed) || !file_exists($filePath)) {
                            $message = "Type de fichier non autorisé ou fichier introuvable.";
                            $return = array("statuts" => 1, "mes" => $message);
                        } else {
                            
                            $fichierFacture = self::verifierPDF($filePath);
    
                            if (!$fichierFacture) {
                                $message = "Le fichier PDF renseigné est endommagé. Veuillez le réparer.";
                                $return = array("statuts" => 1, "mes" => $message);
                            } else {

                           // Voici les valeurs des motifs récupérées, chacune pouvant être égale à MOBILITE, HEBERGEMENT, RESTAURATION ou MAJORATION_RQTH
                            $motifRend2 = $_POST['motifSend2'];
                            $motifRend3 = $_POST['motifSend3'];
                            $motifRend4 = $_POST['motifSend4'];
                            $motifRend5 = $_POST['motifSend5'];

                            // Sélectionner les montants et échéances en fonction de l'option choisie
                            switch ($selectSendEcheance) {
                                case 1:
                                    $ht = $factureCerfa->ht1;
                                    $echeance = $factureCerfa->echeance1;
                                    break;
                                case 2:
                                    $ht = $factureCerfa->ht2;
                                    $echeance = $factureCerfa->echeance2;
                                    break;
                                case 3:
                                    $ht = $factureCerfa->ht3;
                                    $echeance = $factureCerfa->echeance3;
                                    break;
                                case 4:
                                    $ht = $factureCerfa->ht4;
                                    $echeance = $factureCerfa->echeance4;
                                    break;
                                default:
                                    $message = "Sélection d'échéance invalide.";
                                    $return = array("statuts" => 1, "mes" => $message);
                                    echo json_encode($return);
                                    exit;
                            }

                            // Créer les lignes de facture de base
                            $lignes = [];

                            // Initialiser le montant total avec le montant HT de la facture principale
                            $montantTotal = (double)$ht;

                            // Ajouter la ligne principale
                            $lignes[] = [
                                "montant" => $ht,
                                "natureLigne" => "PEDAGOGIE",
                                "quantite" => 1,
                                "numeroEcheance" => $echeance,
                                "numeroDeca" => $cerfa->numeroDeca,
                                "codificationEcheance" => ($echeance == "4")? 99 : $echeance,
                                "numeroDossier" => $cerfa->numeroExterne
                            ];

                            // Ajouter la première ligne si l'échéance est 1 et que le motif correspondant n'est pas vide
                            if (intval($selectSendEcheance) === 1 && !empty($factureCerfa->motif1)) {
                                $montantTotal += (double)$factureCerfa->montant1; // Ajouter le montant1 au montant total
                                $lignes[] = [
                                    "montant" => (double)$factureCerfa->montant1,
                                    "natureLigne" => "PREMIEREQUIPEMENT",
                                    "quantite" => 1,
                                    "numeroEcheance" => $echeance,
                                    "numeroDeca" => $cerfa->numeroDeca,
                                    "codificationEcheance" => $echeance,
                                    "numeroDossier" => $cerfa->numeroExterne
                                ];
                            }

                            $motifsRend = [
                                'motifRend2' => $motifRend2,
                                'motifRend3' => $motifRend3,
                                'motifRend4' => $motifRend4,
                                'motifRend5' => $motifRend5
                            ];
                            
                            foreach ($motifsRend as $motifRendKey => $motifRendValue) {
                                if (intval($selectSendEcheance) !== 1 && !empty($motifRendValue)) {
                                    
                                    for ($i = 2; $i <= 5; $i++) {
                                        $motifKey = 'motif' . $i;
                                        $montantKey = 'montant' . $i;
                            
                                        
                                        if ($motifRendValue == $factureCerfa->$motifKey) {
                                            $montantTotal += (double)$factureCerfa->$montantKey; 
                            
                                            // Ajouter la ligne à la facture
                                            $lignes[] = [
                                                "montant" => (double)$factureCerfa->$montantKey,
                                                "natureLigne" => $motifRendValue,
                                                "quantite" => 1,
                                                "numeroEcheance" => $echeance,
                                                "numeroDeca" => $cerfa->numeroDeca,
                                                "codificationEcheance" => ($echeance == "4")? 99 : $echeance,
                                                "numeroDossier" => $cerfa->numeroExterne
                                            ];
                            
                                            break; 
                                        }
                                    }
                                }
                            }
                            
                            
                            

                            // Construire les données de la facture
                            $factureData = [
                                "montantTotal" => $montantTotal,
                                "numero" => $numeroFactureEcheance,
                                "lignes" => $lignes,
                                "dateEmission" =>  (new DateTime( $dateEmissionFactureEcheance))->format('Y-m-d\TH:i:sP'),
                                "ibanEmetteur" => 'FR'.$factureCerfa->ibanF,
                                "siretEmetteur" => $ligneformation->siretF,
                                "estCertificatRealisation" => (intval($selectSendEcheance) === 1) ? false : true
                              
                            ];

                            // Convertir les données en JSON
                            $jsonData = json_encode($factureData);
                            //var_dump($jsonData);




                                 
                                
                                
                                $ch = curl_init();

                                $client_id = $opco->clid;
                                $client_secret = $opco->clse;

                                $post_data = [
                                    'grant_type' => 'client_credentials',
                                    'client_id' => $client_id,
                                    'client_secret' => $client_secret,
                                    'scope' => ($opco->nom !== "EP") ? 'api.read api.write' : null
                                ];
                                if (isset($post_data['scope']) && $post_data['scope'] === null) {
                                    unset($post_data['scope']);
                                }

                                // Configurez cURL pour envoyer une requête POST avec des données JSON
                                curl_setopt($ch, CURLOPT_URL, $opco->lienT);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
                                curl_setopt($ch, CURLOPT_POST, true);

                                // Exécution de la requête pour obtenir le token
                                $response = curl_exec($ch);
                

                                if($opco->nom == "AFDAS"){
                                    $access_token = $this->obtenirTokenCaches($opco->clid,$opco->clse,$opco->lienT);
                                }else{
                                    $result = json_decode($response, true);
                                    $access_token = $result['access_token'];
                                }  

                                

                                $postFields = [
                                    'facture' => $jsonData,
                                    'fichier' => new \CurlFile($filePath, $fileType, $numeroFactureEcheance)
                                ];
                                
                                curl_setopt($ch, CURLOPT_URL, $opco->lienF);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                    'EDITEUR: CerFacil',
                                    'LOGICIEL: CerFacil',
                                    'VERSION: 1.0.0',
                                    "Authorization: Bearer $access_token",
                                    "X-API-KEY: $opco->cle"
                                ]);
                                curl_setopt($ch, CURLOPT_POST, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

                                // Activer le verbose pour obtenir plus d'informations
                                curl_setopt($ch, CURLOPT_VERBOSE, true);


                                // Augmenter le timeout
                                curl_setopt($ch, CURLOPT_TIMEOUT, 60);

                                // Exécutez la requête et obtenez la réponse
                                $response = curl_exec($ch);

                                
                                // Gérez les erreurs cURL
                                if (curl_errno($ch)) {
                                    $message = 'Erreur cURL : ' . curl_error($ch);
                                    $return = array("statuts" => 1, "mes" => $message);
                                } else {
                                    // Obtenez le code de statut HTTP
                                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                                    // Analysez la réponse
                                    $responseJson = json_decode($response, true);
                                    //var_dump($responseJson);

                                    switch ($httpCode) {
                                        case 200:
                                            $numeroInterneFacture = isset($responseJson['numeroInterneFacture']) ? $responseJson['numeroInterneFacture'] : '' ;
                                            $numeroInterneDocument = isset($responseJson['numeroInterneDocument']) ? $responseJson['numeroInterneDocument'] : '' ;
                                            $tableau = Abonnement :: updateAbonnementById($idProduitCerfas);
                                            if($tableau['valid']){
                                                if(intval($selectSendEcheance) === 2 || intval($selectSendEcheance) === 3){
                                                    $savenumeroInterneFacture = Cerfa :: setnumeroInterneFacture($numeroInterneFacture,$selectSendEcheance,$cerfa->id);
                                                    $savenumeroInterneDocument = Cerfa :: setnumeroInterneDocument($numeroInterneDocument,$selectSendEcheance,$cerfa->id);

                                                    if($savenumeroInterneFacture['valid'] && $savenumeroInterneDocument['valid']){
                                                        $message = "Succès ! Facture Envoyé avec succes ";
                                                        $this->session->write('success',$message);
                                                        $return = array("statuts"=>0, "mes"=>$message);
                                                    }else{
                                                        $message = $savenumeroInterneFacture['error']. $savenumeroInterneDocument['error'];
                                                        $return = array("statuts"=>0, "mes"=>$message);
                                                    }  



                                                }else{
                                                    $message = "Succès ! Facture Envoyé avec succes .";
                                                    $this->session->write('success', $message);
                                                    $return = array("statuts" => 0, "mes" => $message);
                                                }
                                               
                                            }else{
                                                $message = $tableau['error'];
                                                $return = array("statuts" => 1, "mes" => $message);
                                            }
                                            break;
                                        case 400:
                                            $errors = array();
                                            // $message = "Erreur : " . $responseJson['description'];
                                            // $return = array("statuts" => 1, "mes" => $message);
                                            foreach ($responseJson['errors'] as $error) {
                                                $errorDetails = "Erreur : ";
                                                
                                                foreach ($error as $key => $value) {
                                                    $errorDetails .= ucfirst($key) . " : " . $value . " - ";
                                                }
                                                
                                                // Retire le dernier " - " pour éviter une terminaison incorrecte de la chaîne
                                                $errorDetails = rtrim($errorDetails, " - ");
                                                $errors[] = $errorDetails;
                                            }
                                            
                                            $message = "Erreurs : " . implode(", ", $errors);
                                            $return = array("statuts" => 1, "mes" => $message);
                                            break;
                                        case 401:
                                            $errors = array();
                                            foreach ($responseJson['errors'] as $error) {
                                                $errorDetails = "Erreur : ";
                                                
                                                foreach ($error as $key => $value) {
                                                    $errorDetails .= ucfirst($key) . " : " . $value . " - ";
                                                }
                                                
                                                // Retire le dernier " - " pour éviter une terminaison incorrecte de la chaîne
                                                $errorDetails = rtrim($errorDetails, " - ");
                                                $errors[] = $errorDetails;
                                            }
                                            
                                            $message = "Erreurs : " . implode(", ", $errors);
                                            $return = array("statuts" => 1, "mes" => $message);
                                            break;
                                        case 403:
                                            $errors = array();
                                            foreach ($responseJson['errors'] as $error) {
                                                $errorDetails = "Erreur : ";
                                                
                                                foreach ($error as $key => $value) {
                                                    $errorDetails .= ucfirst($key) . " : " . $value . " - ";
                                                }
                                                
                                                // Retire le dernier " - " pour éviter une terminaison incorrecte de la chaîne
                                                $errorDetails = rtrim($errorDetails, " - ");
                                                $errors[] = $errorDetails;
                                            }
                                            
                                            $message = "Erreurs : " . implode(", ", $errors);
                                            $return = array("statuts" => 1, "mes" => $message);
                                            break;
                                        case 500:
                                            $errors = array();
                                            foreach ($responseJson['errors'] as $error) {
                                                $errorDetails = "Erreur : ";
                                                
                                                foreach ($error as $key => $value) {
                                                    $errorDetails .= ucfirst($key) . " : " . $value . " - ";
                                                }
                                                
                                                // Retire le dernier " - " pour éviter une terminaison incorrecte de la chaîne
                                                $errorDetails = rtrim($errorDetails, " - ");
                                                $errors[] = $errorDetails;
                                            }
                                            
                                            $message = "Erreurs : " . implode(", ", $errors);
                                            $return = array("statuts" => 1, "mes" => $message);
                                            break;
                                        default:
                                            $message = "Erreur inattendue.";
                                            $return = array("statuts" => 1, "mes" => $message . $httpCode);
                                            break;
                                    }
                                }

                                curl_close($ch);
                                
                                
                            }
                        }
                    } else {
                        $message = "Fichier non valide ou manquant.";
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                   
            
                }
            } else {
                $message = "Veuillez renseigner les informations de l'employeur et de la formation";
                $return = array("statuts" => 1, "mes" => $message);
            }
        } else {
            $message = "Renseignez l'id SVP !!!";
            $return = array("statuts" => 1, "mes" => $message);
        }
    
        echo json_encode($return);
    }
    
// envoie d'un document Externe a l'opco 
public function sendOpcoDocument() {
    //Privilege::hasPrivilege(Privilege::$AllView, $this->user->privilege);
    header('content-type: application/json');
    $id = $_POST['idDocument'];
    $document_type = $_POST['document_type'];
    $echeance = $_POST['echeance'];
   

    if (isset($id) && !empty($id)) {
        $cerfa = Cerfa::find($id);
        $factureCerfa = Cerfa::findFactureByIdCerfa($id);
        $ligneemployeur = Entreprise::find($cerfa['data']->idemployeur);
        $ligneformation = Formation::find($cerfa['data']->idformation);
        if ($ligneemployeur['valid'] && $ligneformation['valid']) {
            $opco = Opco::find($ligneemployeur['data']->idopco);
            $coutTotalPedagogieCFA = $ligneformation['data']->prix;
            if (!$opco['valid'] || empty($coutTotalPedagogieCFA) ) {
                $message = "Veuillez rattacher cette entreprise à un Opco  et verifier que les montants  Pedagogique sont remplis ";
                $return = array("statuts" => 1, "mes" => $message);
            } else {
                $opco = $opco['data'];
                $factureCerfa = $factureCerfa['data'];
                $ligneemployeur = $ligneemployeur['data'];
                $ligneformation = $ligneformation['data'];
                $cerfa = $cerfa['data'];
                // Créez des données JSON à envoyer à l'API

                switch ($echeance) {
                    case 2:
                        $documentData = [
                            "typeFichier" => $document_type,
                            "objets" => [
                                [
                                    "typeObjet" => "FACTURE",
                                    "idObjet" => $cerfa->numeroInterneFacture2
                                ],
                                [
                                    "typeObjet" => "DOSSIER_APPRENTISSAGE",
                                    "idObjet" => $cerfa->numeroInterne
                                ],
                            ]
                            
                        ];
                        break;
                    case 3:
                        if(!empty($factureCerfa->ht4)){
                            $documentData = [
                                "typeFichier" => $document_type,
                                "objets" => [
                                    [
                                        "typeObjet" => "FACTURE",
                                        "idObjet" => $cerfa->numeroInterneFacture3
                                    ],
                                    [
                                        "typeObjet" => "DOSSIER_APPRENTISSAGE",
                                        "idObjet" => $cerfa->numeroInterne
                                    ],
                                ]
                                
                            ];
                        }else{

                            $documentData = [
                                "typeFichier" => $document_type,
                                "objets" => [
                                    [
                                        "typeObjet" => "DOSSIER_APPRENTISSAGE",
                                        "idObjet" => $cerfa->numeroInterne
                                    ],
                                ]
                                
                            ];

                        }
                        
                        break;
                    case 4:
                        
                        $documentData = [
                            "typeFichier" => $document_type,
                            "objets" => [
                                [
                                    "typeObjet" => "DOSSIER_APPRENTISSAGE",
                                    "idObjet" => $cerfa->numeroInterne
                                ],
                            ]
                            
                        ];
                        break;
                    default:
                        $message = "Sélection d'échéance invalide.";
                        $return = array("statuts" => 1, "mes" => $message);
                        echo json_encode($return);
                        exit;
                }

               

                

                // Convertissez les données en JSON
                $jsonData = json_encode($documentData);
                //var_dump($jsonData);

                // Préparez le fichier à envoyer
                if (isset($_FILES['fileDocument']) && $_FILES['fileDocument']['error'] == 0) {
                    $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
                    $filePath = $_FILES['fileDocument']['tmp_name'];
                    $fileType = mime_content_type($filePath);
                    $fileName = basename($_FILES['fileDocument']['name']);
                    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

                    if (!in_array($fileExt, $allowed) || !file_exists($filePath)) {
                        $message = "Type de fichier non autorisé ou fichier introuvable.";
                        $return = array("statuts" => 1, "mes" => $message);
                    } else {
                        // Créez un handle cURL
                        $ch = curl_init();

                        $client_id = $opco->clid;
                        $client_secret = $opco->clse;

                        $post_data = [
                            'grant_type' => 'client_credentials',
                            'client_id' => $client_id,
                            'client_secret' => $client_secret,
                            'scope' => ($opco->nom !== "EP") ? 'api.read api.write' : null
                        ];
                        if (isset($post_data['scope']) && $post_data['scope'] === null) {
                            unset($post_data['scope']);
                        }

                        // Configurez cURL pour envoyer une requête POST avec des données JSON
                        curl_setopt($ch, CURLOPT_URL, $opco->lienT);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
                        curl_setopt($ch, CURLOPT_POST, true);

                        // Exécution de la requête pour obtenir le token
                        $response = curl_exec($ch);

                       
                        
                        if($opco->nom == "AFDAS"){
                            $access_token = $this->obtenirTokenCaches($opco->clid,$opco->clse,$opco->lienT);
                        }else{
                            $result = json_decode($response, true);
                            $access_token = $result['access_token'];
                        }  

                        $postFields = [
                            'infosFichier' => $jsonData,
                            'fichier' => new \CurlFile($filePath, $fileType, $fileName)
                        ];
                        if($opco->nom == "Akto"){
                            $url = "https://cfa-ws.akto.fr/SorApiEchangeCFA/v1/documents";
                        }elseif($opco->nom == "Atlas"){
                            $url="https://cfa-ws.opco-atlas.org/SorApiEchangeCFA/v1/documents";
                        }
                       
                       
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                            'EDITEUR: CerFacil',
                            'LOGICIEL: CerFacil',
                            'VERSION: 1.0.0',
                            "Authorization: Bearer $access_token",
                            "X-API-KEY: $opco->cle"
                        ]);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

                        // Activer le verbose pour obtenir plus d'informations
                        curl_setopt($ch, CURLOPT_VERBOSE, true);


                        // Augmenter le timeout
                        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

                        // Exécutez la requête et obtenez la réponse
                        $response = curl_exec($ch);

                      
                        // Gérez les erreurs cURL
                        if (curl_errno($ch)) {
                            $message = 'Erreur cURL : ' . curl_error($ch);
                            $return = array("statuts" => 1, "mes" => $message);
                        } else {
                            // Obtenez le code de statut HTTP
                            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                            // Analysez la réponse
                            $responseJson = json_decode($response, true);

                            switch ($httpCode) {
                                case 200:
                                    $message = "Succès ! Document envoyé.";
                                    $this->session->write('success', $message);
                                    $return = array("statuts" => 0, "mes" => $message);
                                    break;
                                case 400:
                                    $message = "Erreur : " . $responseJson['Description'];
                                    $return = array("statuts" => 1, "mes" => $message);
                                    foreach ($responseJson['errors'] as $error) {
                                        $message = "Code : " . $error['code'] . " - " . $error['Description'];
                                        $return = array("statuts" => 1, "mes" => $message);
                                    }
                                    break;
                                case 401:
                                    $errors = array();
                                    $message = "Erreur : " . $responseJson['description'];
                                    $return = array("statuts" => 1, "mes" => $message);
                                    foreach ($responseJson['errors'] as $error) {
                                        $message = "Code : " . $error['code'] . " - " . $error['Description'];
                                        $return = array("statuts" => 1, "mes" => $message);
                                    }
                                    break;
                                case 403:
                                    $errors = array();
                                    foreach ($responseJson['errors'] as $error) {
                                        $errors[] = "Code : " . $error['code'] . " - " . $error['Description'];
                                    }
                                    $message = "Erreurs : " . implode(", ", $errors);
                                    $return = array("statuts" => 1, "mes" => $message);
                                    break;
                                case 500:
                                    $errors = array();
                                    foreach ($responseJson['errors'] as $error) {
                                        $errors[] = "Code : " . $error['code'] . " - " . $error['Description'];
                                    }
                                    $message = "Erreurs : " . implode(", ", $errors);
                                    $return = array("statuts" => 1, "mes" => $message);
                                    break;
                                default:
                                    $message = "Erreur inattendue.";
                                    $return = array("statuts" => 1, "mes" => $message . $httpCode);
                                    break;
                            }
                        }

                        curl_close($ch);
                    }
                } else {
                    $message = "Fichier non valide ou manquant.";
                    $return = array("statuts" => 1, "mes" => $message);
                }
            }
        } else {
            $message = "Veuillez renseigner les informations de l'employeur et de la formation";
            $return = array("statuts" => 1, "mes" => $message);
        }
    } else {
        $message = "Renseignez l'id SVP !!!";
        $return = array("statuts" => 1, "mes" => $message);
    }

    echo json_encode($return);
}
  
    

}