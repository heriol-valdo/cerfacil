<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/FormationModel.php';
require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
//========================================================================================
// But : Supprimer une formation
// Rôles : Admins, gestionnaire de centre
// champs: formation_id
//========================================================================================

$app->delete('/formation/{formation_id}/delete', function (Request $request, Response $response, $param) use ($key) {
    
    $formation = new Formation(); 
    $data = $request->getParsedBody();

    // Check => Format param
    if(empty($param['formation_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ formation_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $formation->id = $param['formation_id'];
    
    $formationExist = $formation->searchForId();
    // Check => Existence Salle
    if(!$formationExist){
        $response->getBody()->write(json_encode(['erreur' => "La formation n'existe pas dans la base de données"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    } 

    // Succès
    $formation->deleteFormation();
    $response->getBody()->write(json_encode(['valid' => 'Vous avez supprimé la formation']));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth)->add($checkAdminGestionnaireCentre);