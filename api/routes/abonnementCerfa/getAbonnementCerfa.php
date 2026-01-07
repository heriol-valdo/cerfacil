<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


require_once __DIR__.'/../../models/ClientCerfaModel.php';
require_once __DIR__.'/../../models/AbonnementCerfaModel.php';

require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';




$app->post('/api/abonnementCerfaFind', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $userconnected = $token['id'];
    $id = isset($data['id']) ? $data['id'] : null;

    if ($role === 7 || $role === 3) {
        $abonementCerfa = new AbonnementCerfa();

        if($role === 7){
            $clientCerfa = new ClientCerfa();
            $clientCerfa->id_users = $userconnected;
    
            $profilClient = $clientCerfa->getProfil();
    
            $effectiveUserId = $profilClient[0]['roleCreation'] == 2 ? $profilClient[0]['idCreation'] :  $userconnected;
            $result = $abonementCerfa->find($effectiveUserId,$id);

        }else{
            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_users =  $userconnected;
            $datagestionnairescentre = $gestionnaireCentre->searchForId();
            $effectiveUserId = $datagestionnairescentre['id_centres_de_formation'];
            $result = $abonementCerfa->findIdCentre($effectiveUserId,$id);

        }
       


      
      
        if (isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Conte bnt-Type', 'application/json')->withStatus(400);
        } else{
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie', 'data' => $result]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);



$app->post('/api/abonnementCerfaSearchType', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $id = $token['id'];
    $data =$request->getParsedBody();

    if ($role === 7 || $role === 3) {
        $abonementCerfa = new AbonnementCerfa();

        if($role === 7){
            $clientCerfa = new ClientCerfa();
            $clientCerfa->id_users = $id;
    
            $profilClient = $clientCerfa->getProfil();
    
            $effectiveUserId = $profilClient[0]['roleCreation'] == 2 ? $profilClient[0]['idCreation'] : $id;
            $result = $abonementCerfa->searchType($effectiveUserId);

        }else{

            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_users = $id;
            $datagestionnairescentre = $gestionnaireCentre->searchForId();
            $effectiveUserId = $datagestionnairescentre['id_centres_de_formation'];
            $result = $abonementCerfa->searchTypeIdCentre($effectiveUserId);


        }

       

      
      
        if (isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } else{
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie', 'data' => $result]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);


