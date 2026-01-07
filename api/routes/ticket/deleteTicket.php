<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/TicketModel.php';


//========================================================================================
// But : Supprimer un ticket
// Rôles : Admins, gestionnaire de centre, gestionnaires entreprise, formateurs, financeurs
// param: id_ticket
//========================================================================================
$app->delete('/api/ticket/{ticket_id}/delete', function (Request $request, Response $response, $param) use ($key) {
    // Check => Format param
    if(empty($param['ticket_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ ticket_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['ticket_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    // Check => Si Etudiant = Erreur
    if($role == 5){
        $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas accès à cette fonctionnalité"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $user = new User();
    $user->id = $token['id'];

    // Récupération infos sur requête suppression
    $ticket = new Ticket();
    $data = $request->getParsedBody();

    if(isset($param['ticket_id'])){
        if(!empty($param['ticket_id'])){
            $ticket->id = $param['ticket_id'];
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Veuillez sélectionner un ticket"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Le champ ID ticket est obligatoire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check si le ticket existe
    $ticketExist = $ticket->boolId();
    if(!$ticketExist){
        $response->getBody()->write(json_encode(['erreur' => "Le ticket n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    
    // Check si l'user est bien le même que sur le ticket
    $ticketInfos = $ticket->searchForId();
    if($role != 1){
        if($user->id != $ticketInfos['id_users']){
            $response->getBody()->write(json_encode(['erreur' => "Vous n'êtes pas l'auteur du ticket"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // Succès
    $ticket->deleteTicket();
    $response->getBody()->write(json_encode(['valid' => "Le ticket a bien été supprimé"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);    
})->add($auth);