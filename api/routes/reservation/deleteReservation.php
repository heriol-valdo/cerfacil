<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/ReservationModel.php';
require_once __DIR__.'/../../models/FinanceurModel.php';

//========================================================================================
// But : Supprimer une réservation
// Rôles : Admin, financeur
// champs obligatoire : {byReservationId} 
// Champs facultatif : 
//========================================================================================

$app->delete('/api/reservation/{byReservationId}/delete', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data = $request->getParsedBody();

    if (filter_var($param['byReservationId'], FILTER_VALIDATE_INT) === false) {
        $response->getBody()->write(json_encode(['erreur' => "Format du paramètre invalide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $reservation = new Reservation();
    $reservation->id = $param['byReservationId'];
    $og_data = $reservation->getReservationDetails();

    if($role == 6){ // Financeur
        if($og_data['id_reservations_statut'] != 1){ // Si statut de la réservation différent de "Envoyé"
            $response->getBody()->write(json_encode(['erreur' => "Vous ne pouvez plus modifier cette réservation"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $financeur = new Financeur();
        $financeur->id_users = $token['id'];
        $financeurEntrepriseId = $financeur->searchEntrepriseForIdUsers();

        if($financeurEntrepriseId != $og_data['financeur_entreprise_id']){
            $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas accès à cette réservation"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    if($reservation->delete()){
        $response->getBody()->write(json_encode(['valid' => "Vous avez mis à jour la réservation avec succès"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Erreur dans l'exécution de la requête, veuillez contacter un administrateur"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($checkAdminFinanceur);