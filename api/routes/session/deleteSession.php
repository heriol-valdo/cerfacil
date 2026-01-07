<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/SessionModel.php';

//========================================================================================
// But : Supprimer une session
// Rôles : Admins, gestionnaire de centre
// param: idSession
//========================================================================================

$app->delete('/api/session/delete/{idSession}', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];

    $session = new Session();
    $session->id = $param['idSession'];

    if(!$session->boolId()) {
        $response->getBody()->write(json_encode(['erreur' => "Cette session n'existe pas dans la base de donées"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }

    if ($role === 1 || $role === 3) {
        $session->deleteSession($request, $response);
        $response->getBody()->write(json_encode(['valid' => 'La session a bien été supprimée']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }

})->add($auth);