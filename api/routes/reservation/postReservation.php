<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/ReservationModel.php';
require_once __DIR__.'/../../models/FinanceurModel.php';

//========================================================================================
// But : Ajouter une réservation de formation
// Rôles : Financeur
// champs obligatoire : {bySessionId}, nb_place
// Champs facultatif : message
//========================================================================================

$app->post('/api/reservation/{bySessionId}/add', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data = $request->getParsedBody();

    $financeur = new Financeur();
    $financeur->id_users = $token['id'];
    $financeurInfos = $financeur->getRoleInfosForId();
    if(empty($financeurInfos)){
        $response->getBody()->write(json_encode(['erreur' => "Erreur dans la récupération des données de l'utilisateur"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $requiredFields = ['nb_place'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            $response->getBody()->write(json_encode(['erreur' => "Le champ $field doit figurer sur le formulaire"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        if (empty($data[$field])) {
            $response->getBody()->write(json_encode(['erreur' => "Le champ $field est obligatoire"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    $reservation = new Reservation();
    $reservation->id_session = $param['bySessionId'];
    $nbPlaceRestantes = $reservation->getPlacesRestantes();

    if($data['nb_place'] > $nbPlaceRestantes){
        $response->getBody()->write(json_encode(['erreur' => "Il n'y a que $nbPlaceRestantes places restantes"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Chargement des informations de la réservation
    $reservation->nb_place = $data['nb_place'];
    $reservation->message = $data['message'];
    $reservation->financeur_entreprise_id = $financeurInfos['id_entreprises'];
    $reservation->financeur_entreprise_nom = $financeurInfos['nomEntreprise'];
    $reservation->id_conseillers_financeurs = $token['idByRole']['id'];
    $reservation->id_reservations_statut = 1;

    if($reservation->addReservation()){
        $response->getBody()->write(json_encode(['valid' => "Vous avez envoyé votre réservation avec succès"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Il y a eu une erreur dans la réservation, veuillez contacter un administrateur"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($checkFinanceur);