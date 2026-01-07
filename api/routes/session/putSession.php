<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/SessionModel.php';
require_once __DIR__.'/../../models/FormationModel.php';


//========================================================================================
// But : Permet de modifier les infos d'une session
// Rôles : admin, gestionnaireCentre
// Champs possibles : dateDebut, dateFin, nbPlace
//========================================================================================
$app->put('/api/session/{session_id}/update', function (Request $request, Response $response, $param) use ($key) { 
    $token=$request->getAttribute('user');
    $role=intval($token['role']);
    $data = $request->getParsedBody();
    // Check => Format param
    if(empty($param['session_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ session_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['session_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $session = new Session();
    $session->id = $param['session_id'];
    $session_id_centre = $session->searchCentreForId();

    // Check => GestionnaireCentre => Appartenance Session
    if($role == 3){
        $gestionnaireCentre = new GestionnaireCentre();
        $gestionnaireCentre->id_users = $token['id'];
        $user_id_centre = $gestionnaireCentre->searchCentreForIdUsers();
       
        if ($session_id_centre != $user_id_centre){
            $response->getBody()->write(json_encode(['erreur' => ["Vous n'avez pas accès à cette session"]]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }


    // Check => Intégrité du formulaire
    $requiredFields = ['dateDebut', 'dateFin', 'nbPlace'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            $response->getBody()->write(json_encode(['error' => "Le champ $field est manquant dans le formulaire"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // Check => Remplissage champs
    $changedDateDebut = 0;
    $changedDateFin = 0;
    $changedNbPlace = 0;

    $og_infos = $session->searchForId();

    $sessionDebut = $og_infos['dateDebut'];
    $sessionFin = $og_infos['dateFin'];

    $currentDate = new DateTime();
  
    if(!empty($data['dateDebut']) && ($data['dateDebut'] != $og_infos['dateDebut'])){
        $changedDateDebut = 1;
        $session->dateDebut = $data['dateDebut'];
        $sessionDebut = new DateTime($data['dateDebut']);
    }

    if(!empty($data['dateFin']) && ($data['dateFin'] != $og_infos['dateFin'])){
        $changedDateFin = 1;
        $session->dateFin = $data['dateFin'];
        $sessionFin = new DateTime($data['dateFin']);
    }

    if(!empty($data['nbPlace']) && ($data['nbPlace'] != $og_infos['nbPlace'])){
        $changedNbPlace = 1;
        $session->nbPlace = $data['nbPlace'];
    }

    // Check => Dates cohérentes
    if($sessionDebut > $sessionFin){
        $response->getBody()->write(json_encode(['erreur' => "La date de début doit être avant la date de fin"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check => Zero changement : Erreur
    if($changedDateDebut == 0 &&
    $changedDateFin == 0 &&
    $changedNbPlace == 0){
        $response->getBody()->write(json_encode(['erreur' => "Veuillez changer au moins une donnée"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check => Si changement > Update
    $changedDateDebut && $session->updateDateDebut();
    $changedDateFin && $session->updateDateFin();
    $changedNbPlace && $session->updateNbPlace();

    // Succès
    $response->getBody()->write(json_encode(['valid' => "La session a bien été mise à jour"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

})->add($auth)->add($checkAdminGestionnaireCentre);