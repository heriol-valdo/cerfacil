<?php // /routes/salle/putSalle.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ .'/../../models/SalleModel.php';
require_once __DIR__ .'/../../models/CentreFormationModel.php';
require_once __DIR__ .'/../../models/GestionnaireCentreModel.php';

//========================================================================================
// But : Permet de modifier une salle
// Rôles : admin, gestionnaireCentre
// Champs obligatoires : id_centres_de_formation
// Champs possibles: newNom, newCapacite
//========================================================================================

$app->put('/api/salle/{salle_id}/update', function (Request $request, Response $response, $param) use ($key) {  
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    // Check => Format param
    if(empty($param['salle_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ salle_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['salle_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }


    $salle = new Salle();
    $centreFormation = new CentreFormation();
    $salle->id = $param['salle_id'];
    $data = $request->getParsedBody();
    

    // Intégrité formulaire
    if(!isset($data['newNom'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nouveau nom doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['newCapacite'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nouvelle capacité d'accueil doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Si Gestionnaire Centre => ID du centre de formation = ID du centre de l'user
    if ($role == 3){
        $gestionnaireCentre = new GestionnaireCentre();
        $gestionnaireCentre->id_users = $token['id'];

        $gestionnaireCentre_id_centre = $gestionnaireCentre->searchCentreForIdUsers();

        // Check => Appartenance Salle au centre
        $salle_id_centre = $salle->searchCentreForId();
        if($salle_id_centre != $gestionnaireCentre_id_centre){
            $response->getBody()->write(json_encode(['erreur' => "La salle n'existe pas dans ce centre"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // Check => Existence Salle
    if(!($salle->boolId())){
        $response->getBody()->write(json_encode(['erreur' => "La salle n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    

    $changedNom = 0;
    $changedCapacite = 0;

    if(!empty($data['newNom'])) {
        $changedNom = 1;
        $salle->nom = $data['newNom'];
    } else {
        $changedNom = 0;
    }

    if(!empty($data['newCapacite'])) {
        $changedCapacite = 1;
        $salle->capacite_accueil = $data['newCapacite'];
    } else {
        $changedCapacite = 0;
    }

    if($changedNom == 0 &&
    $changedCapacite == 0){
        $response->getBody()->write(json_encode(['erreur' => "Veuillez changer au moins une donnée"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $changedNom && $salle->updateNom();
    $changedCapacite && $salle->updateCapacite();
    
    $response->getBody()->write(json_encode(['valid' => "La salle a été mise à jour"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

})->add($auth)->add($checkAdminGestionnaireCentre);