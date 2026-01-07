<?php

namespace Projet\Database;


use Projet\Model\Guzzle;
use DateTime;

class Assistance extends Guzzle{


    public static function save(
      $objet,$telephone= null,$message= null,$id = null){


        $data = [ 
        'id' => $id,
        'objet' => $objet,
        'telephone' => $telephone,
        'description' => $message 
         ];

        $result =  self::sendRequest($data,'ticket/send','post');

        $result = json_decode($result);

        $response = [
            'valid' => false,
            'data' => null,
            'error' => null
        ];

        if (property_exists($result, 'erreur')) {
                $response['error'] =  $result->erreur;
        }
        if (property_exists($result, 'valid')) {
            $response['valid'] = true;
            $response['data'] = $result->valid;
        }

        return $response;
    }

    public static function saveMessage($message, $id)
{
    $data = [
        'contenu' => htmlspecialchars($message, ENT_QUOTES),
        'id_tickets' => $id
    ];

    try {
        $result = self::sendRequest($data, 'tickets-echanges/send', 'post');
        $result = json_decode($result);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Erreur de décodage JSON");
        }

        $response = ['valid' => false, 'data' => null, 'error' => null];

        if ($result === null) {
            $response['error'] = "Erreur lors de l'envoi du message";
        } elseif (property_exists($result, 'erreur')) {
            $response['error'] = $result->erreur;
        } elseif (property_exists($result, 'valid') && $result->valid) {
            $response['valid'] = true;
            $response['data'] = "Message envoyé avec succès";
        } else {
            $response['error'] = "Réponse inattendue de l'API";
        }

        return $response;
    } catch (Exception $e) {
        error_log("Erreur saveMessage API: " . $e->getMessage());
        return ['valid' => false, 'error' => "Erreur de communication avec le serveur"];
    }
}

   

    public static function find($id){
        $data = ['id' => $id];
        $result = self::sendRequest($data, 'formationFind', 'post');
        $result = json_decode($result);
        $response = [
            'valid' => false,
            'data' => null,
            'error' => null
        ];
        if (!empty($result) && is_object($result)) {
            if (property_exists($result, 'valid')) {
                if (property_exists($result, 'data') && is_object($result->data)) {
                    $response['valid'] = true;
                    $response['data'] = $result->data;
                } else {
                    $response['error'] = "La propriété 'data' est manquante ou invalide.";
                }
            } elseif (property_exists($result, 'erreur')) {
                $response['error'] = $result->erreur;
            } else {
                $response['error'] = "La réponse ne contient ni 'valid' ni 'erreur'.";
            }
        } else {
            $response['error'] = "La réponse est vide ou n'est pas un objet JSON valide.";
        }
    
        return $response;
    }

    

    public static function delete($id){
        $data = ['id' => $id];
        

        $url = "tickets-echanges/message/". $id ."/delete";
        $result =  self::sendRequest($data,$url,'delete');

        $result = json_decode($result);

        $response = [
            'valid' => false,
            'data' => null,
            'error' => null
        ];

        if (property_exists($result, 'erreur')) {
                $response['error'] =  $result->erreur;
        }
        if (property_exists($result, 'valid')) {
            $response['valid'] = true;
            $response['data'] = $result->valid;
        }

        return $response;
    }

    public static function countBySearchType($search = null){
        $data = [
            'search'=> $search
        ]; 
        try {
        $result =  self::sendRequest($data,'CountBySearchTypeticket','post');
        $result = json_decode($result);
        $response = [
         'valid' => false,
         'data' => null,
         'error' => null
        ];
        if (!empty($result) && is_object($result)) {
             if (property_exists($result, 'valid')) {
                 if (property_exists($result, 'data') && is_object($result->data)) {
                     $response['valid'] = true;
                     $response['data'] = $result->data;
                 } else {
                     $response['error'] = "La propriété 'data' est manquante ou invalide.";
                 }
             } elseif (property_exists($result, 'erreur')) {   
                 $response['error'] = $result->erreur;
             } else {
                 $response['error'] = "La réponse ne contient ni 'valid' ni 'erreur'.";
             }
         } else {
                 $response['error'] = "La réponse est vide ou n'est pas un objet JSON valide.";
         }
 
            if ($response['valid'] && isset($response['data'])) {
               // var_die($response['data']);
                return $response['data']->total;
               
            }

            //var_die($response);
            return 0;

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Afficher les détails de l'erreur
            $responseBody = $e->getResponse()->getBody(true);
            echo 'Request Error: ' . $e->getMessage() . ' Response Body: ' . $responseBody;
            return [
                'valid' => false,
                'error' => $responseBody
            ];
        }
    }

    public static function searchType($nbreParPage=null,$pageCourante=null,$search = null){
        $data = [
            'search'=> $search,
            'pageCourante' => $pageCourante,
            'nbreParPage' => $nbreParPage
        ]; 
        $result =  self::sendRequest($data,'ticket/listTicketsByuser','post');
        $result = json_decode($result);
        $response = [
         'valid' => false,
         'data' => null,
         'error' => null
        ];
        if (!empty($result) && is_object($result)) {
             if (property_exists($result, 'valid')) {
                 if (property_exists($result, 'data') && is_array($result->data)) {
                     $response['valid'] = true;
                     $response['data'] = $result->data;
                 } else {
                     $response['error'] = "La propriété 'data' est manquante ou invalide.";
                 }
             } elseif (property_exists($result, 'erreur')) {   
                 $response['error'] = $result->erreur;
             } else {
                 $response['error'] = "La réponse ne contient ni 'valid' ni 'erreur'.";
             }
         } else {
                 $response['error'] = "La réponse est vide ou n'est pas un objet JSON valide.";
         }

         if ($response['valid']) {
            return $response['data'];
         }
 
         return $response['error'];
    }


    public static function searchTypeById($id){
        $selectedTicket  =  $id;


        // Check user role before fetching ticket details
        
        $getTicketDetails =   self::sendRequest([], "ticket/" . $selectedTicket,'get');  
        $ticketDetails = json_decode($getTicketDetails);
        if (property_exists($ticketDetails, 'erreur')) {
            $_SESSION['error-msg'] = $ticketDetails->erreur;
        }

        // Formatage date à la FR
        $dateCreation = new DateTime($ticketDetails->data->dateCreation);
        $dateCreation = $dateCreation->format('d-m-Y H:i'); 
        

        // Check user role before fetching ticket details
      
        $getTicketExchangeDetails =  self::sendRequest([],"tickets-echanges/ticket/" . $selectedTicket,'get'); 
        $ticketExchangeDetails = json_decode($getTicketExchangeDetails);
        if (property_exists($ticketDetails, 'erreur')) {
            $_SESSION['error-msg'] = $ticketExchangeDetails->erreur;
        }
        

        return [
            'ticketDetails' => $ticketDetails,
            'dateCreation' => $dateCreation,
            'ticketExchangeDetails' => $ticketExchangeDetails
        ];
    }




}