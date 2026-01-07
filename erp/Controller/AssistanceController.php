<?php

require_once __DIR__ . '/../requestFile/authRequet.php';
require_once __DIR__ . '/LogoutController.php';

class AssistanceController{
    public function index() {


        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];


        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

             include("Views/assistance/index.php");
        }

      
       
        
    }

    public function VoirDemande() {


        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];


        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

             $_SESSION["ticketId"]  = $_POST['ticketId'];

             include("Views/assistance/voir-demande.php");
        }

    }

    public static function details(){
      
        $selectedTicket  =  $_SESSION["ticketId"];

        // Check user role before fetching ticket details
        if ($_SESSION['user']['role'] != 5) {
            $getTicketDetails = sendRequest([], $_SESSION['userToken'], "ticket/" . $selectedTicket, 'get');
            $ticketDetails = json_decode($getTicketDetails);
            if (property_exists($ticketDetails, 'erreur')) {
                $_SESSION['error-msg'] = $ticketDetails->erreur;
            }

            // Formatage date à la FR
            $dateCreation = new DateTime($ticketDetails->data->dateCreation);
            $dateCreation = $dateCreation->format('d-m-Y H:i'); 
        }

        // Check user role before fetching ticket details
        if ($_SESSION['user']['role'] != 5) {
            $getTicketExchangeDetails = sendRequest([], $_SESSION['userToken'], "tickets-echanges/ticket/" . $selectedTicket, 'get');
            $ticketExchangeDetails = json_decode($getTicketExchangeDetails);
            if (property_exists($ticketDetails, 'erreur')) {
                $_SESSION['error-msg'] = $ticketExchangeDetails->erreur;
            }
        }

        return [
            'ticketDetails' => $ticketDetails,
            'dateCreation' => $dateCreation,
            'ticketExchangeDetails' => $ticketExchangeDetails
        ];
    }

    public function repondreTicket() {

        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];
    
        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        } else {
    
            // Récupérer le contenu du message envoyé
            $contenu = trim($_POST['contenu']);
            $selectedTicket = $_POST['ticketId'];  // Ticket ID provenant du formulaire
    
            $data = [];
    
            // Vérification du rôle de l'utilisateur pour voir si l'état doit être envoyé
            if ($_SESSION['user']['role'] == 1) {
                if (isset($_POST['etat']) && !empty($_POST['etat'])) {
                    $etat = trim($_POST['etat']);
                    $data = [
                        'contenu' => $contenu,
                        'id_etat_ticket' => $etat,
                        'id_tickets' => $selectedTicket
                    ];
                } else { // Si aucun état n'est spécifié par l'admin
                    $data = [
                        'contenu' => $contenu,
                        'id_tickets' => $selectedTicket
                    ];
                }
            } else { // Si l'utilisateur n'est pas un administrateur
                $data = [
                    'contenu' => $contenu,
                    'id_tickets' => $selectedTicket
                ];
            }
    
            // Envoi de la requête pour mettre à jour le ticket
            $response = sendRequest($data, $_SESSION['userToken'], "tickets-echanges/send", 'post');
            $responseDecoded = json_decode($response);
    
            // Vérifier si la réponse du serveur est valide
            if ($responseDecoded === null) {
                // Réponse JSON invalide
                echo json_encode([
                    'valid' => false,
                    'erreur' => "Erreur lors de l'envoi du message"
                ]);
                exit();
            } else {
                // Réponse JSON valide, message envoyé avec succès
                echo json_encode([
                    'valid' => true,
                    'message' => "Message envoyé avec succès"
                ]);
                exit();
            }
        }
    }
    

    public function deleteMassageTicket(){
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $postData = file_get_contents('php://input');
            $decoded = json_decode($postData, true);

            $id = trim($decoded['id']);
            

            $url = "tickets-echanges/message/". $id ."/delete";
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

   

    public static function ticket() {
        // Vérifier si l'utilisateur est connecté et a le bon rôle
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role']) || $_SESSION['user']['role'] != 1) {
            // Retourner un tableau vide ou une erreur si l'utilisateur n'a pas les droits
            return [
                'ticketList' => null,
                'ticketsEnCours' => [],
                'ticketsHistorique' => [],
                'error' => 'Accès non autorisé'
            ];
        }
        
        // Initialisation des variables
        $ticketsEnCours = [];
        $ticketsHistorique = [];
        $resultErreur = null;
        
        // Appel à l'API
        $getTicketList = sendRequest([], $_SESSION['userToken'], 'ticket/list', 'get');
        $ticketList = json_decode($getTicketList);
        
        if (property_exists($ticketList, 'erreur')) {
            $resultErreur = $ticketList->erreur;
            $_SESSION['error-msg'] = $resultErreur;
        } elseif (property_exists($ticketList, 'valid')) {
            foreach ($ticketList->data as $ticket) {
                if ($ticket->etat == "En cours de traitement" || $ticket->etat == "Envoyé") {
                    $ticketsEnCours[] = $ticket;
                } else {
                    $ticketsHistorique[] = $ticket;
                }
            }
    
            // Tri des arrays par date
            usort($ticketsEnCours, function($a, $b) {
                return strtotime($b->dateCreation) - strtotime($a->dateCreation);
            });
            usort($ticketsHistorique, function($a, $b) {
                return strtotime($b->dateCreation) - strtotime($a->dateCreation);
            });
    
            // Limite le nombre d'item dans le tableau à 50 max
            $maxItems = 50;
            $ticketsEnCours = array_slice($ticketsEnCours, 0, $maxItems);
            $ticketsHistorique = array_slice($ticketsHistorique, 0, $maxItems);
        } elseif (property_exists($ticketList, 'valid-null')) {
            $_SESSION['valid-msg'] = $ticketList->{'valid-null'};
        }
    
        // Retourne toujours un tableau structuré avec les données disponibles
        return [
            'ticketList' => $ticketList,
            'ticketsEnCours' => $ticketsEnCours,
            'ticketsHistorique' => $ticketsHistorique, 
            'error' => $resultErreur
        ];
    }

    public static function getTicket(){
        if($_SESSION['user']['role'] == 1){
        
            $getTicketEnvoye = sendRequest([],$_SESSION['userToken'],'ticket/list/1','get');
            $ticketEnvoyeList = json_decode($getTicketEnvoye);
        
            $getTicketEnCours = sendRequest([],$_SESSION['userToken'],'ticket/list/2','get');
            $ticketEnCoursList = json_decode($getTicketEnCours);
        
            $hasNewTicket = false;
            if ($ticketEnvoyeList->data != NULL || $ticketEnCoursList->data != NULL) {
                $hasNewTicket = true;
                // $notifCount = 3000;
                $notifCount = $ticketEnvoyeList->data[0]->nbEtat + $ticketEnCoursList->data[0]->nbEtat;
                if($notifCount > 99){
                    $notifCount = "99+";
                }
                
            } 

            return [
                'hasNewTicket' => $hasNewTicket,
                'notifCount' => $notifCount
            ];
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