<?php // /routes/salle/deleteSalle.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/SalleModel.php';
require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
//========================================================================================
// But : Supprimer une salle
// Rôles : Admins, gestionnaire de centre
// champs: salle_id
//========================================================================================

$app->delete('/api/salle/{salle_id}/delete', function (Request $request, Response $response, $param) use ($key) {
    

    $salle = new Salle(); 
    $data = $request->getParsedBody();

    // Check => Format param
    if(empty($param['salle_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ salle_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['salle_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $salle->id = $param['salle_id'];
    
    $idExist = $salle->boolId();
    // Check => Existence Salle
    if(!$idExist){
        $response->getBody()->write(json_encode(['erreur' => "La salle n'existe pas dans la base de données"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    } 

    // Check => GestionnaireCentre => Appartenance Salle 
    if($role == 3){
        $salle_id_centre = $salle->searchCentreForId();
        $gestionnaireCentre = new GestionnaireCentre();
        $gestionnaireCentre->id_users = $token['id'];
        $gestionnaireCentre_id_centre = $gestionnaireCentre->searchCentreForIdUsers();

        if($gestionnaireCentre_id_centre != $salle_id_centre){
            $response->getBody()->write(json_encode(['erreur' => "La salle sélectionnée n'existe pas dans votre centre"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);    
        }
    }

    // Succès
    $salle->deleteSalle();
    $response->getBody()->write(json_encode(['valid' => 'Vous avez supprimé la salle']));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth)->add($checkAdminGestionnaireCentre);