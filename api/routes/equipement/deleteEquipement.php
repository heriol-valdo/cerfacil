<?php // /routes/equipement/deleteEquipement.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/EquipementModel.php';
require_once __DIR__.'/../../models/SalleModel.php';
require_once __DIR__.'/../../models/GestionnaireCentreModel.php';


//========================================================================================
// But : Permet de supprimer l'équipement d'une salle
// Rôles : administrateur, gestionnaireCentre
// Champs obligatoires : {salle_id}, {equipement_id}
// Champs facultatifs :
//========================================================================================

$app->delete('/api/salle/{salle_id}/equipement/{equipement_id}/delete', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);


    $data = $request->getParsedBody();
    $salle = new Salle(); 
    $salle->id = $param['salle_id'];

    // Check => Existence Salle
    $salleExist = $salle->boolId();
    if(!$salleExist){
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

    // Check => Existence Equipement
    $equipement = new Equipement();
    $equipement->id = $param['equipement_id'];
    $equipementExist = $equipement->boolId();
    if(!$equipementExist){
        $response->getBody()->write(json_encode(['erreur' => "L'équipement n'existe pas dans cette salle"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);    
    }

    // Succès
    $equipement->deleteEquipement();
    $response->getBody()->write(json_encode(['valid' => 'Vous avez supprimé l\'équipement']));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                
})->add($auth)->add($checkAdminGestionnaireCentre);