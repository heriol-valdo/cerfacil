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
// But : Supprimer un message par rapport à un ticket
// Rôles : Admins, gestionnaire de centre, gestionnaires entreprise, formateurs, financeurs
// param: id_ticket
//========================================================================================
$app->delete('/api/tickets-echanges/message/{id}/delete', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    // Récupération infos sur requête suppression
    $ticketMessage = new TicketEchange();
    $ticketMessage->id = $param['id'];

    // Check si le message existe
    $ticketMessageExist = $ticketMessage->boolId();
    if(!$ticketMessageExist){
        $response->getBody()->write(json_encode(['erreur' => "Le message sélectionné n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400); 
    }

    // Pour tous les non-admins
    if($role != 1){
        $ticketMessageInfos = $ticketMessage->searchAllForId();
        $ticketMessageAuthor = $ticketMessageInfos['id_users'];

        // Croise l'auteur du message avec l'utilisateur connecté
        if($ticketMessageAuthor != $token['id']){
            $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas accès à ce message"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400); 
        }
    }

    // Succès : Suppression du message
    if($ticketMessage->deleteOneTicketEchange()){
        $response->getBody()->write(json_encode(['valid' => "Le message a bien été supprimé"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200); 
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Il y a eu une erreur dans la suppression du message"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($checkNotEtudiant);