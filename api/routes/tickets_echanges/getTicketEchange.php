<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/TicketModel.php';
require_once __DIR__.'/../../models/TicketsEchangesModel.php';

//========================================================================================
// But : Liste globale des tickets
// Rôles : Admins
// champs: aucun
//========================================================================================

$app->get('/api/tickets-echanges/ticket/{id_tickets}', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    // Check : Existence ticket
    $ticket = new Ticket();
    $ticket->id = $param['id_tickets'];
    $ticketExist = $ticket->boolId();
    if(!$ticketExist){
        $response->getBody()->write(json_encode(['erreur' => "Le champ ticket sélectionné n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check : Appartenance ticket si le rôle n'est pas admin
    if($role != 1){
        $ticketInfos = $ticket->searchForId();
        if($ticketInfos['id_users'] != $token['id']){
            $response->getBody()->write(json_encode(['erreur' => "Ce ticket ne vous appartient pas"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // Remplissage champs
    $ticketEchange = new TicketEchange();
    $ticketEchange->id_tickets = $ticket->id;

    // Succès
    $result = $ticketEchange->getListTicketEchangeByIdTickets();
    if(empty($result)){
        $response->getBody()->write(json_encode(['erreur' => "Aucun message associé à ce ticket"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
    $response->getBody()->write(json_encode(['valid' => "Récupération des messages réussie", 'data' => $result]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);    
})->add($auth)->add($checkNotEtudiant);