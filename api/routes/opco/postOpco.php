<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/OpcoModel.php';
require_once __DIR__.'/../../models/ClientCerfaModel.php';

require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';


$app->post('/api/addupdateOpco', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data = $request->getParsedBody();

    // Liste des clés requises
    $requiredKeys = [
        'nom', 'cle', 'lienE', 'lienCe', 'lienCo', 'lienF', 'lienT', 'clid', 'clse'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['error' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // Récupération des valeurs
    $id = $data['id'];
    $nom = $data['nom'];
    $cle = $data['cle'];
    $lienE = $data['lienE'];
    $lienCe = $data['lienCe'];
    $lienCo = $data['lienCo'];
    $lienF = $data['lienF'];
    $lienT = $data['lienT'];
    $clid = $data['clid'];
    $clse = $data['clse'];

    // Vérification du rôle
    if ($role === 7 || $role === 3) {
        $opco = new Opco();

        if($role === 7){
            $clientCerfa = new ClientCerfa();
            $clientCerfa->id_users =  $userConnected ;

            $profilClient = $clientCerfa->getProfil();

            $effectiveUserId = $profilClient[0]['roleCreation'] == 2 ? $profilClient[0]['idCreation'] :$userConnected;
            $result = $opco->save(null,$effectiveUserId,$nom, $cle, $lienE, $lienCe, $lienCo, $lienF, $lienT, $clid, $clse, $id);

        }else{
            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_users = $userConnected;
            $datagestionnairescentre = $gestionnaireCentre->searchForId();
            $effectiveUserId = $datagestionnairescentre['id_centres_de_formation'];
            $result = $opco->save($effectiveUserId,null,$nom, $cle, $lienE, $lienCe, $lienCo, $lienF, $lienT, $clid, $clse, $id);
          
        }


     
      
        if (!isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['valid' => 'Vous avez ajouté un opco']));
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


