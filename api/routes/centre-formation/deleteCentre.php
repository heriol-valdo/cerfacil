<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';

//========================================================================================
// But : Supprimer un centre de formation
// Rôles : Admins
// param : centre_id
//========================================================================================
$app->delete('/api/admin/centreFormation/{centre_id}/delete', function (Request $request, Response $response, $param) use ($key) {

    if (!isset($param['centre_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le paramètre centre_id est manquant"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($param['centre_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ centre_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['centre_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $centreFormation = new CentreFormation();
    $centreFormation->id = $param['centre_id'];

    if(!$centreFormation->boolId()) {
        $response->getBody()->write(json_encode(['erreur' => "Ce centre de formation n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Succès
    $centreFormation->deleteCentre();
    $response->getBody()->write(json_encode(['valid' => "Le centre a bien été supprimé"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);    
})->add($auth)->add($checkAdmin);
