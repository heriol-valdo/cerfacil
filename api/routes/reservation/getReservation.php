<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/ReservationModel.php';
require_once __DIR__.'/../../models/SessionModel.php';

//========================================================================================
// But : Récupère liste des réservations faites de l'entreprise d'un financeur
// Rôles : Admin, Gestionnaire Centre, Financeur
// champs obligatoire : byReservationId (int)
//========================================================================================

$app->get('/api/reservation/{byReservationId}/details', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    if (filter_var($param['byReservationId'], FILTER_VALIDATE_INT) === false) {
        $response->getBody()->write(json_encode(['erreur' => "Format du paramètre invalide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $reservation = new Reservation();
    $reservation->id = $param['byReservationId'];
    $data = $reservation->getReservationDetails();

    switch($role){
        case 3: // Gestionnaire centre
            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_users = $token['id'];
            $centreId = $gestionnaireCentre->searchCentreForIdUsers();

            if($centreId != $data['centres_de_formation_id']){
                $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas accès à ces données"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
            break;


        case 6: // Financeur
            $financeur = new Financeur();
            $financeur->id_users = $token['id'];
            $financeurEntrepriseId = $financeur->searchEntrepriseForIdUsers();

            if($financeurEntrepriseId != $data['financeur_entreprise_id']){
                $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas accès à ces données"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
            break;
    }
   
    if (empty($data)) {
        $response->getBody()->write(json_encode(['erreur' => "Il n'y a aucun résultat"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    } else {
        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $data]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
})->add($auth)->add($checkGCentreFinanceur);

//========================================================================================
// But : Récupère les réservations pour une session et les informations de la session
// Rôles : Admin, Gestionnaire centre
// champs : Aucun
//========================================================================================

$app->get('/api/reservation/liste/session/{bySessionId}', function (Request $request, Response $response, $param) use ($key) {
    $bySessionId = "all";

    // Check => Si param est un chiffre
    if (filter_var($param['bySessionId'], FILTER_VALIDATE_INT) === false) {
        $response->getBody()->write(json_encode(['erreur' => "Format du paramètre invalide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    } else {
        $bySessionId = $param['bySessionId'];
    }

    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    $reservation = new Reservation();
    $reservation->id_session = $bySessionId;
    $sessionInfos = $reservation->getSessionInfos();
    $reservationInfos = $reservation->getReservationListeForSession();

    if (empty($sessionInfos)) {
        $response->getBody()->write(json_encode(['erreur' => "La session n'existe plus"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    if (empty($reservationInfos)) {
        $response->getBody()->write(json_encode(['erreur' => "Il n'y a aucune réservation pour cette session"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    switch ($role) {
        case 1:
            $hasAccess = true;
            break;
        case 3:  
            $roleTable = new GestionnaireCentre();
            $roleTable->id_users = $token['id'];

            $roleTable->id_centres_de_formation = $roleTable->getCentreIdForIdUsers();
            $hasAccess = $roleTable->boolHasSession($reservation->id_session);
            break;
        default:
            $hasAccess = false;
            break;
    }

    $data["sessionInfos"] = $hasAccess ? $sessionInfos : null;
    $data["reservationsInfos"] = $hasAccess ? $reservationInfos : null;
 
    if (empty($data)) {
        $response->getBody()->write(json_encode(['erreur' => "Il n'y a aucun résultat"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    } else {
        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $data]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
})->add($auth)->add($checkAdminGestionnaireCentre);


//========================================================================================
// But : Récupère liste des réservations faites de l'entreprise d'un financeur
// Rôles : Admin, Gestionnaire Centre, Financeur
// champs : (Admin, GestionnaireCentre) byFinanceurEntrepriseId (int) / si rôle financeur laisser "default"
//========================================================================================

$app->get('/api/reservation/liste/financeur-entreprise/{byFinanceurEntrepriseId}', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    $byFinanceurEntrepriseId = '';

    switch($role){
        case 1: // Remplissage param obligatoire :
        case 3: // Admin, gestionnaire centre
            if (filter_var($param['byFinanceurEntrepriseId'], FILTER_VALIDATE_INT) === false) {
                $response->getBody()->write(json_encode(['erreur' => "Format du paramètre invalide"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }

            if($role == 3){
                $gestionnaireCentre = new GestionnaireCentre();
                $gestionnaireCentre->id_users = $token['id'];
                $centreId = $gestionnaireCentre->searchCentreForIdUsers();
            }

            $byFinanceurEntrepriseId = $param['byFinanceurEntrepriseId'];
            break;


        case 6: // Financeur
            $financeur = new Financeur();
            $financeur->id_users = $token['id'];

            $byFinanceurEntrepriseId = $financeur->searchEntrepriseForIdUsers();
            break;

        default: 
            $response->getBody()->write(json_encode(['erreur' => "Erreur de rôle"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            break;
    }
   
    $reservation = new Reservation();
    $reservation->financeur_entreprise_id = $byFinanceurEntrepriseId;
   
    $data = isset($centreId) ? $reservation->getReservationListeForFinanceurEntreprise_byCentre($centreId) : $reservation->getReservationListeForFinanceurEntreprise();

    if (empty($data)) {
        $response->getBody()->write(json_encode(['erreur' => "Il n'y a aucun résultat"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    } else {
        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $data]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
})->add($auth)->add($checkGCentreFinanceur);