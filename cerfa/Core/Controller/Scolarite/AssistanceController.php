<?php


namespace Projet\Controller\Scolarite;



use DateTime;
use DOMDocument;
use DOMXPath;
use Projet\Controller\Admin\AdminsController;

use Projet\Database\Produit;
use Projet\Database\Profil;
use Projet\Database\Alternant;
use Projet\Database\Formation;
use Projet\Database\Assistance;
use Projet\Database\Entreprise;
use Projet\Database\Opco;
use Projet\Database\Cerfa;
use Projet\Database\Abonnement;
use Projet\Model\StripeHandler;
use Projet\Model\App;
use Projet\Model\FileHelper;


class AssistanceController extends AdminsController
{
    public function index(){
        //Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        $user = $this->user;
        $nbreParPage = 10;
       
        // nouveau
       // Traitement de la recherche
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $search = isset($_POST['searchassistance']) && !empty($_POST['searchassistance']) ? $_POST['searchassistance'] : null;
        $this->session->write('searchassistance', $search); // Enregistrer la recherche dans la session
        
        // Récupérer la page envoyée et l'enregistrer dans la session
        $pageCourante = isset($_POST['page']) && is_numeric($_POST['page']) ? (int)$_POST['page'] : 1;
        $this->session->write('pageCourante', $pageCourante);
        
        // Rediriger pour éviter la soumission multiple de formulaire
        header("Location: " . App::url('assistance'));
        exit;
    }

    // Vérifier si une recherche est en session
    $search = $this->session->exists('searchassistance') ? $this->session->read('searchassistance') : null;



    // Vérifier si la page courante est en session
    $pageCourante = $this->session->exists('pageCourante') ? $this->session->read('pageCourante') : 1;

    // Calculer le nombre total d'éléments et de pages
    $nbre = Assistance::countBySearchType($search);
    
    $nbrePages = ceil($nbre / $nbreParPage);
        // fin nouveau 
    $items = Assistance::searchType($nbreParPage,$pageCourante,$search);
    

    $this->render('admin.scolarite.assistance',compact('search','user','nbre','nbrePages','items','pageCourante'));
     
    }

    public function details(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
          
            header("Location: " . App::url('assistance'));
            exit;
        }

        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : null;
          
            
        // Rediriger pour éviter la soumission multiple de formulaire
        $resultTicket = Assistance::searchTypeById($id);
        $user = $this->user;

        $bool = isset($resultTicket['ticketExchangeDetails']->valid)? true : false;

        $this->render('admin.scolarite.detail_assistance',compact('user','resultTicket','bool','id'));
    }

    public function assistanceDeleteMessage(){
        //Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        header('content-type: application/json');
        if (isset($_POST['id'])&&!empty($_POST['id'])){
            $id = $_POST['id'];
            $id = trim($id);
            
            $save = Assistance::delete($id);
            if($save['valid']){
                $message = $save['data'];
                $this->session->write('success', $message);
                $return = array("statuts" => 0, "mes" => $message);
            }else{
                $message = $save['error'];
                $return = array("statuts" => 1, "mes" => $message);
            }
        }else{
            $message = "Renseigner l'id SVP !!!";
            $return = array("statuts"=>1, "mes"=>$message);
        }
        echo json_encode($return);
    }

    public function simulateur(){
        $user = $this->user;
        $this->render('admin.scolarite.simulateur',compact('user',));
    }

    public function simulateur_generated_rncp(){
       // Vérifier que la méthode est POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        // Récupérer les données JSON
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['codeRNCP']) || empty($input['codeRNCP'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Code RNCP manquant']);
            exit;
        }

        $codeRNCP = trim($input['codeRNCP']);

        try {
            // Chemin vers le fichier Excel (convertissez-le en CSV pour cette approche)
             $cheminFichier  = PATH_FILE;
             $cheminFichier .=  '/public/' . 'assets/ressources/rncp.csv';
            
            // Vérifier que le fichier existe
            if (!file_exists($cheminFichier)) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Fichier CSV non trouvé']);
                exit;
            }
            
            // Ouvrir le fichier CSV
            $handle = fopen($cheminFichier, 'r');
            if (!$handle) {
                throw new Exception('Impossible d\'ouvrir le fichier CSV');
            }
            
            // Variables pour stocker les résultats
            $resultat = null;
            $trouve = false;
            $numeroLigne = 0;
            
            // Lire ligne par ligne
            while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) { // Utilisez ',' si vos données sont séparées par des virgules
                $numeroLigne++;
                
                // Ignorer la première ligne (en-têtes)
                // if ($numeroLigne === 1) {
                //     continue;
                // }
                
                // Vérifier qu'on a au moins 2 colonnes
                if (count($data) < 2) {
                    continue;
                }
                
                $codeRNCPCellule = trim($data[0]); // Première colonne : Code RNCP
                $plafond = trim($data[1]); // Deuxième colonne : Plafond
                
                // Comparer les codes RNCP (insensible à la casse)
                if (strcasecmp($codeRNCPCellule, $codeRNCP) === 0) {
                    $resultat = [
                        'codeRNCP' => $codeRNCPCellule,
                        'plafond' => floatval($plafond),
                        'ligne' => $numeroLigne
                    ];
                    $trouve = true;
                    break;
                }
            }
            
            // Fermer le fichier
            fclose($handle);
            
            // Retourner la réponse
            if ($trouve) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Code RNCP trouvé',
                    'data' => $resultat
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Code RNCP non trouvé dans le fichier'
                ]);
            }
            
        } catch (Exception $e) {
            // Gestion des erreurs
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors de la lecture du fichier',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function assistanceIA() {
    header('Content-Type: application/json');

    // Vérification méthode POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(["error" => "Méthode non autorisée"]);
        exit;
    }

    // Lecture des données JSON brutes envoyées par jQuery
    $input = json_decode(file_get_contents('php://input'), true);
    $question = isset($input['message']) ? trim($input['message']) : null;

    if (!$question) {
        http_response_code(400);
        echo json_encode(["error" => "Aucune question reçue"]);
        exit;
    }

    $api_key = "dceffa079096423ea73394239672bad9"; // ⚠️ 

    $data = [
        "model" => "gpt-4o",
        "messages" => [
            ["role" => "user", "content" => $question]
        ]
    ];

    $headers = [
        "Content-Type: application/json",
        "Authorization: Bearer $api_key"
    ];

    $ch = curl_init("https://api.aimlapi.com/v1");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        http_response_code(500);
        echo json_encode(["error" => "Erreur cURL : " . curl_error($ch)]);
        curl_close($ch);
        exit;
    }

    curl_close($ch);
    $decoded = json_decode($response, true);

    if (!isset($decoded['choices'][0]['message']['content'])) {
        http_response_code(502);
        echo json_encode(["error" => "Réponse invalide de l'API"]);
        exit;
    }

    $generated_response = $decoded['choices'][0]['text'];

    echo json_encode([
        "reply" => $generated_response
    ]);
}



    public function save()
{
    header('content-type: application/json');
    $return = [];
    $tab = ["add", "edit"];

    if (
        isset($_POST['objet']) && !empty($_POST['objet']) &&
        isset($_POST['action']) && !empty($_POST['action']) &&
        isset($_POST['idElement']) && in_array($_POST["action"], $tab)
    ) {

        $objet = $_POST['objet'];
        $message = $_POST['message'];
        $telephone = $_POST['telephone'];
       
        $action = $_POST['action'];
        $id = $_POST['idElement'];

       


        if ($action == "edit") {
        } else {
      
            try {
                $save = Assistance::save(
                $objet,$telephone,$message,$id = null);
                if ($save['valid']) {
                    $message = $save['data'];
                    $this->session->write('success', "Votre demande a été envoyée avec succès !");
                    $return = array("statuts" => 0, "mes" => $message);
                } else {
                    $message = $save['error'];
                    $return = array("statuts" => 1, "mes" => $message);
                }
            } catch (Exception $e) {
                $message =  $e->getMessage();
                $return = array("statuts" => 1, "mes" => $message);
            }
            
        }
    } else {
        $message = "Veuillez renseigner tous les champs requis";
        $return = array("statuts" => 1, "mes" => $message);
    }

    echo json_encode($return);
}

public function saveMessage()
{
    header('content-type: application/json');
    $return = [];
    $tab = ["add", "edit"];

    if (!isset($_POST['messages']) || empty($_POST['messages']) ||
        !isset($_POST['actions']) || empty($_POST['actions']) ||
        !isset($_POST['idElements']) || !in_array($_POST["actions"], $tab)) {
        echo json_encode(["statuts" => 1, "mes" => "Veuillez renseigner tous les champs requis"]);
        exit;
    }

    $messages = trim($_POST['messages']);
    $action = $_POST['actions'];
    $id = (int)$_POST['idElements'];

    if ($action == "edit") {
        // Logique d'édition si nécessaire
    } else {
        try {
            $save = Assistance::saveMessage($messages, $id);
            
            if ($save['valid']) {
                $this->session->write('success', "Le message a bien été envoyé !");
                echo json_encode(["statuts" => 0, "mes" => $save['data']]);
            } else {
                echo json_encode(["statuts" => 1, "mes" => $save['error'] ?? "Erreur inconnue"]);
            }
        } catch (Exception $e) {
            error_log("Erreur saveMessage: " . $e->getMessage());
            echo json_encode(["statuts" => 1, "mes" => "Une erreur technique est survenue"]);
        }
    }
    exit;
}
   

   


}