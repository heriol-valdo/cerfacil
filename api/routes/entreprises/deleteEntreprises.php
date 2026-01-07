<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


require_once __DIR__.'/../../models/EntreprisesModel.php';


$app->delete('/api/deleteEntreprise', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data = $request->getParsedBody();

    // Liste des clés requises
    $requiredKeys = ['id'];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['error' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // Récupération des valeurs
    $id = $data['id'];
   
    // Vérification du rôle
    if ($role === 7 || $role === 3) {
        $entreprise = new Entreprises();
        $result = $entreprise->delete($id);
        if (!isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['valid' => "L'entreprise a été supprimée avec succès"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }

})->add($auth);