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
// But : envoyer une réponse à ticket
// Rôles : Admins, gestionnaire de centre, gestionnaires entreprise, formateurs, financeurs
// champs: objet, description, telephone
//========================================================================================

$app->post('/api/tickets-echanges/send', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    
    // Check si les champs sont remplis
    $data = $request->getParsedBody();
    $ticketEchange = new TicketEchange();
    // Remplissage informations Ticket
    // - Champs obligatoires
    $requiredFields = ['contenu','id_tickets'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            $response->getBody()->write(json_encode(['erreur' => "Le champ $field est obligatoire"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
        if (empty($data[$field])) {
            $response->getBody()->write(json_encode(['erreur' => "Le champ $field est vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // Check : Existence ticket
    $ticket = new Ticket();
    $ticket->id = $data['id_tickets'];
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
    $ticketEchange->contenu = $data['contenu'];
    $ticketEchange->id_tickets = $data['id_tickets'];
    $ticketEchange->id_users = $token['id'];

    // Pour admin : Possibilité de changer le statut du ticket en même temps
    if($role == 1){
        if(isset($data['id_etat_ticket']) && !empty($data['id_etat_ticket'])) {
            $ticket->id_etat_ticket = $data['id_etat_ticket'];
            $ticketUpdateStatus = $ticket->updateEtat();

            if(!$ticketUpdateStatus){
                $response->getBody()->write(json_encode(['erreur' => "Dans la mise à jour de l'état du ticket"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }
    }

    // Succès
    $ticketEchange->addTicketEchange();
    $response->getBody()->write(json_encode(['valid' => "Le message a bien été envoyé"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);    
})->add($auth)->add($checkNotEtudiant);