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
// But : envoyer un ticket
// Rôles : Admins, gestionnaire de centre, gestionnaires entreprise, formateurs, financeurs
// champs: objet, description, telephone
//========================================================================================

$app->post('/api/ticket/send', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    // Check le rôle de la personne connectée / != 5
    if($role == "5"){
        $response->getBody()->write(json_encode(['error' => 'Les étudiants ne peuvent pas envoyer de ticket']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check si l'user existe en se basant sur le token
    $user = new User();
    $user->id = $token['id'];
    
    // Check si les champs sont remplis
    $data = $request->getParsedBody();
    $ticket = new Ticket();
    // Remplissage informations Ticket
    // - Champs obligatoires
    // -- Objet
    if(isset($data['objet'])){
        if(!empty($data['objet'])){
            $ticket->objet = $data['objet'];
        } else {
            $response->getBody()->write(json_encode(['error' => "Le champ Objet est vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);    
        }
    } else {
        $response->getBody()->write(json_encode(['error' => "Le champ Objet est obligatoire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // -- Description
    if(isset($data['description'])){
        if(!empty($data['description'])){
            $ticket->description = $data['description'];
        } else {
            $response->getBody()->write(json_encode(['error' => "Le champ Description est vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);    
        }
    } else {
        $response->getBody()->write(json_encode(['error' => "Le champ Description est obligatoire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // - Champs facultatifs
    // -- Telephone
    if(isset($data['telephone']) && !empty($data['telephone'])){
        $ticket->telephone = $data['telephone'];
    } else {
        $ticket->telephone = '';
    }

    // Remplissage autres champs
    $ticket->id_etat_ticket = 1;
    $ticket->dateCreation = date("Y-m-d H:i:s");
    $ticket->id_users = $user->id;

    // Succès
    $ticket->addTicket();
    $response->getBody()->write(json_encode(['valid' => "Le ticket a bien été envoyé"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);    
})->add($auth);