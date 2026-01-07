<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';
require_once __DIR__.'/../../models/FormationModel.php';

//========================================================================================
// But : Ajouter une formation
// Rôles : Admins, gestionnaire de centre
// champs : nom, prix, lienFranceCompetence
//========================================================================================

$app->post('/addformation', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];

    function addFormation($idCentre, $request, $response) {
        $parsedBody = $request->getParsedBody();

        
        $requiredFields = ['nom', 'prix', 'lienFranceCompetence'];
        foreach ($requiredFields as $field) {
            if (!isset($parsedBody[$field])) {
                $response->getBody()->write(json_encode(['erreur' => 'Tous les champs doivent être remplis']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        $formation = new Formation();
        $formation->id_centres_de_formation = $idCentre;
        $formation->nom = $parsedBody['nom'];
        $formation->prix = $parsedBody['prix'];
        $formation->lienFranceCompetence = $parsedBody['lienFranceCompetence'];

        if ($formation->addFormation()) {
            $response->getBody()->write(json_encode(['valid' => 'Vous avez ajouté une formation']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Échec, il y a eu un problème lors de l'ajout de cette formation"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    if ($role === 1 || $role === 3) {
        $idCentre = $role === 1 ? $request->getParsedBody()['idCentre'] : null;
        if ($role === 3) {
            $gestionnaire = new GestionnaireCentre();
            $gestionnaire->id_users = $userConnected;
            $gestionnaireDatas = $gestionnaire->getProfilGestionnaireCentre();
            $idCentre = $gestionnaireDatas[0]['id_centres_de_formation'];
        }
        
        return addFormation($idCentre, $request, $response);
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

