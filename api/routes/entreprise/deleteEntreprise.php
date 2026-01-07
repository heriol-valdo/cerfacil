<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/EntrepriseModel.php';

//========================================================================================
// But : Supprimer une entreprise
// Rôles : Admins
// param : entreprise_id
//========================================================================================
$app->delete('/api/admin/entreprise/{entreprise_id}/delete', function (Request $request, Response $response, $param) use ($key) {


    if (!isset($param['entreprise_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le paramètre entreprise_id est manquant"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
      // Check => Format param
      if(empty($param['entreprise_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ entreprise_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['entreprise_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    
    $entreprise = new Entreprise();
    $entreprise->id = $param['entreprise_id'];


    if(!$entreprise->boolId()) {
        $response->getBody()->write(json_encode(['erreur' => "Cette entreprise n'existe pas dans la base données"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Succès
    $entreprise->deleteEntreprise();
    $response->getBody()->write(json_encode(['valid' => "L'entreprise a bien été supprimée"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth)->add($checkAdmin);