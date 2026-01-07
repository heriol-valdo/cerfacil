<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/FormationsModel.php';
require_once __DIR__.'/../../models/ClientCerfaModel.php';

require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';



$app->post('/api/formationByNom', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $nom = isset($data['nomF']) ? $data['nomF'] : null;

    if ($role === 7 || $role === 3) {
        $formation = new Formations();
        $formations = $formation->byNom($nom);
        if (!empty($formations)) {
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie','data' => $formations]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "tableau vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

$app->post('/api/formationFind', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $id=$data['id'];
   
    $formation = new Formations();
    $formations = $formation->find($id);
    if (!empty($formations)) {
        $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie','data' => $formations]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } else {
        $response->getBody()->write(json_encode(['erreur' => "tableau vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
    
});

$app->post('/api/formationCountBySearchType', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $id = intval($token['id']);
    $search = isset($data['search']) ? $data['search'] : null;

    if ($role === 7 || $role === 3) {
        $formation = new Formations();
        if($role === 7){
            $clientCerfa = new ClientCerfa();
            $clientCerfa->id_users = $id;
    
            $profilClient = $clientCerfa->getProfil();
    
            $effectiveUserId = $profilClient[0]['roleCreation'] == 2 ? $profilClient[0]['idCreation'] : $id;
            $result = $formation->countBySearchType($effectiveUserId,$search);

        }else{
            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_users = $id;
            $datagestionnairescentre = $gestionnaireCentre->searchForId();
            $effectiveUserId = $datagestionnairescentre['id_centres_de_formation'];
            $result = $formation->countBySearchTypeIdCentre($effectiveUserId,$search);
          
        }

      
    
        if (isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } else{
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie', 'data' => $result]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);


$app->post('/api/formationSearchType', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $id = intval($token['id']);
    $search=$data['search'];
    $pageCourante=$data['pageCourante'];
    $nbreParPage=$data['nbreParPage'];

    if ($role === 7 || $role === 3) {
        $formation = new Formations();
        if($role === 7){
            $clientCerfa = new ClientCerfa();
            $clientCerfa->id_users = $id;
    
            $profilClient = $clientCerfa->getProfil();
    
            $effectiveUserId = $profilClient[0]['roleCreation'] == 2 ? $profilClient[0]['idCreation'] : $id;
            $result = $formation->searchType($effectiveUserId,$nbreParPage,$pageCourante,$search);

        }else{
            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_users = $id;
            $datagestionnairescentre = $gestionnaireCentre->searchForId();
            $effectiveUserId = $datagestionnairescentre['id_centres_de_formation'];
            $result = $formation->searchTypeIdCentre($effectiveUserId,$nbreParPage,$pageCourante,$search);
          
        }


      
      
        if (isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } else{
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie', 'data' => $result]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);


