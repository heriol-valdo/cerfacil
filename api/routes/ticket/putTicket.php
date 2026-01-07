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
// But : Modifier un ticket
// Rôles : Admins
// param: ticket_id
// champs: id_etat_ticket
//========================================================================================
$app->put('/api/ticket/{ticket_id}/update', function (Request $request, Response $response, $param) use ($key) {
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

    // Check si l'user existe en se basant sur le token
    $user = new User();
    $user->id = $token['id'];

    // Récupération infos sur requête update
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

    // Récupération infos OG du ticket
    $og_infos = $ticket->searchOne();

    $og_etat = $og_infos['etat'];
    $changed_etat = false;
    $og_reponse = $og_infos['reponse'];
    $changed_reponse = false;
    
    // Check si l'id_etat_ticket est valide
    $ticketInfos = $ticket->searchForId();
    if(isset($data["id_etat_ticket"]) && !empty($data["id_etat_ticket"])){
        $validValues = [1, 2, 3, 4];
        if(!in_array($data["id_etat_ticket"], $validValues)) {
            $response->getBody()->write(json_encode(['erreur' => "Veuillez sélectionner un état valide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } 
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Le champ état du ticket doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    if(!isset($data["reponse"])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ réponse doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check si changement
    if($og_etat != $data["id_etat_ticket"]){
        $ticket->id_etat_ticket = $data["id_etat_ticket"];
        $ticket->updateEtat();
        $changed_etat = true;
    }

    if($og_reponse != $data["reponse"]){
        $ticket->reponse = $data["reponse"];
        $changed_etat = true;
        $ticket->updateReponse();
    }

    // Check si au moins 1 changement
    if(!$changed_etat && !$changed_reponse){
        $response->getBody()->write(json_encode(['erreur' => "Veuillez changer au moins une information"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Succès
    $response->getBody()->write(json_encode(['valid' => "L'état du ticket a été mis à jour"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);    
})->add($auth)->add($checkAdmin);