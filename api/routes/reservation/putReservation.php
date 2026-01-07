<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/ReservationModel.php';
require_once __DIR__.'/../../models/FinanceurModel.php';

//========================================================================================
// But : Modifier une réservation
// Rôles : Admin, financeur
// champs obligatoire : {byReservationId} 
// Champs facultatif : nb_place, message
//========================================================================================

$app->put('/api/reservation/{byReservationId}/update', function (Request $request, Response $response, $param) use ($key) {
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

    if($role == 6){
        if($og_data['id_reservations_statut'] != 1){ // Pour financeur, si statut de la réservation différent de "Envoyé"
            $response->getBody()->write(json_encode(['erreur' => "Vous ne pouvez plus modifier cette réservation"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $financeur = new Financeur();
        $financeur->id_users = $token['id'];
        $financeurEntrepriseId = $financeur->searchEntrepriseForIdUsers();

        if($financeurEntrepriseId != $og_data['financeur_entreprise_id']){
            $response->getBody()->write(json_encode(['erreur' => "Cette réservation ne vous appartient pas"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // Si vide ou identique, reprendre og_data
    if (!empty($data['nb_place']) && $data['nb_place'] != $og_data['nb_place']) {
        $reservation->nb_place = $data['nb_place'];
        $changed_nbPlace = $reservation->updateNbPlace() ? 1 : 0;
    } 

    // Si vide ou identique, reprendre og_data
    if (!empty($data['message']) && $data['message'] != $og_data['message']) {
        $reservation->message = $data['message'];
        $changed_message = $reservation->updateMessage() ? 1 : 0;
    } 

    if($role == 1){ 
        // Si vide ou identique, reprendre og_data
        if (!empty($data['id_reservations_statut']) && $data['id_reservations_statut'] != $og_data['id_reservations_statut']) {
            $reservation->id_reservations_statut = $data['id_reservations_statut'];
            $changed_statut = $reservation->updateStatut() ? 1 : 0;
        } 
    }

    if($changed_nb_place == 0 &&
    $changed_message == 0 &&
    $changed_statut == 0){
        $response->getBody()->write(json_encode(['erreur' => "Veuillez modifier au moins une donnée"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $response->getBody()->write(json_encode(['valid' => "Vous avez mis à jour la réservation avec succès"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    
})->add($auth)->add($checkAdminFinanceur);