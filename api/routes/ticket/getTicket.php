<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Projet\Database\Assistance;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/TicketModel.php';

//========================================================================================
// But : Liste globale des tickets
// Rôles : Admins
// champs: aucun
//========================================================================================

$app->get('/api/ticket/list', function (Request $request, Response $response) use ($key) {
    // Récupération infos
    $ticket = new Ticket();

    $result = $ticket->searchAll();
    
    // Changement réponse en fonction du résultat
    if($result != null){
        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);  
    } else {
        $response->getBody()->write(json_encode(['valid-null' => "Il n'y a aucun ticket pour le moment"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);  
    }
})->add($auth)->add($checkAdmin);

//========================================================================================
// But : Liste des tickets selon état
// Rôles : Admins
// param: id_etat_ticket
//========================================================================================
$app->get('/api/ticket/list/{id_etat_ticket}', function (Request $request, Response $response, $param) use ($key) {
    if(isset($param["id_etat_ticket"]) && !empty($param["id_etat_ticket"])){
        $validValues = [1, 2, 3, 4];
        if(in_array($param["id_etat_ticket"], $validValues)) {
            $ticket = new Ticket();
            $ticket->id_etat_ticket = $param['id_etat_ticket'];
        } else {
            $response->getBody()->write(json_encode(['erreur' => "L'état choisi pour filtrer les résultats est incorrect"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Le paramètre d'état est obligatoire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    
    //Success    
    $result = $ticket->searchAllForEtat();
    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);  
})->add($auth)->add($checkAdmin);

//========================================================================================
// But : Liste de ses propres tickets
// Rôles : Admins, gestionnaire de centre, gestionnaires entreprise, formateurs, financeurs
// param:  aucun
//========================================================================================
$app->get('/api/ticket/listTickets', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    // Check le rôle de la personne connectée / != 5
    if($role != "5"){
       // Récupération infos
        $ticket = new Ticket();
        $ticket->id_users = $token['id'];
        $result = $ticket->searchAllForUser();
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Les étudiants ne peuvent pas envoyer de ticket']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }   

    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);  
})->add($auth);

//========================================================================================
// But : Afficher UN ticket
// Rôles : Admins, gestionnaire de centre, gestionnaires entreprise, formateurs, financeurs
// param:  ticket_id
//========================================================================================
$app->get('/api/ticket/{ticket_id}', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    $user = new User();
    $user->id = $token['id'];

    // Check le rôle de la personne connectée / != 5
    if($role != "5"){
        $ticket = new Ticket();
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Les étudiants ne peuvent pas consulter de ticket']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    // Check existence du ticket
    $ticket->id = $param['ticket_id'];
    $ticketExist = $ticket->boolId();
    if(!$ticketExist){
        $response->getBody()->write(json_encode(['erreur' => "Le ticket n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    // Check si l'user est bien le même que sur le ticket ou si il est admin
    $ticketInfos = $ticket->searchForId();  

    if($user->id == $ticketInfos['id_users'] || $role == 1){
        // Récupération infos
        $result = $ticket->searchOne();
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Vous n'êtes pas l'auteur du ticket"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    // Success
    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);  
})->add($auth);


$app->post('/api/ticket/listTicketsByuser', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $id = intval($token['id']);
    $search=$data['search'];
    $pageCourante=$data['pageCourante'];
    $nbreParPage=$data['nbreParPage'];

    // Check le rôle de la personne connectée / != 5
    if($role != "5"){
       // Récupération infos
        $ticket = new Ticket();
        $ticket->id_users = $token['id'];
        $result = $ticket->searchAllForUsers($nbreParPage,$pageCourante,$search);

        if (isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else{
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie', 'data' => $result]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }

    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Les étudiants ne peuvent pas envoyer de ticket']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }   

})->add($auth);

$app->post('/api/CountBySearchTypeticket', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $id = intval($token['id']);
    $search = isset($data['search']) ? $data['search'] : null;

   

    if ($role === 7 || $role === 3) {
        $ticket = new Ticket();
        $result = $ticket->countBySearchType($id,$search);
        if (isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else{
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie', 'data' => $result]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
           
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);