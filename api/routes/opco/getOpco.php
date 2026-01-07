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



$app->post('/api/opcoByNom', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $nom = isset($data['nom']) ? $data['nom'] : null;

    if ($role === 7 || $role === 3) {

        $opco = new Opco();
        $opcos = $opco->byNom($nom);
        if (!empty($opcos)) {
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie','data' => $opcos]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "tableau vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

$app->post('/api/opcoFind', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $id = isset($data['id']) ? $data['id'] : null;

    if ($role === 7 || $role === 3 || $role === 5 || $role === 6) {
        $opco = new Opco();
        $opcos = $opco->find($id);
        if (!empty($opcos)) {
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie','data' => $opcos]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "tableau vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

$app->post('/api/opcoCountBySearchType', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $id = $token['id'];
    $data =$request->getParsedBody();
    $search = isset($data['search']) ? $data['search'] : null;

    if ($role === 7 || $role === 3) {

        if($role === 7){
            $clientCerfa = new ClientCerfa();
            $clientCerfa->id_users = $id;
    
            $profilClient = $clientCerfa->getProfil();
    
            $effectiveUserId = $profilClient[0]['roleCreation'] == 2 ? $profilClient[0]['idCreation'] : $id;

        }else{
            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_users = $id;
            $datagestionnairescentre = $gestionnaireCentre->searchForId();
            $effectiveUserId = $datagestionnairescentre['id_centres_de_formation'];
          
        }
       


        $opco = new Opco();
        $result = $opco->countBySearchTypeIdCentre($effectiveUserId,$search);
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

$app->post('/api/opcoSearchType', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $id = $token['id'];
    $data =$request->getParsedBody();
    $search=$data['search'];
    $pageCourante=$data['pageCourante'];
    $nbreParPage=$data['nbreParPage'];

    if ($role === 7 || $role === 3) {
        
        if($role === 7){
            $clientCerfa = new ClientCerfa();
            $clientCerfa->id_users = $id;
    
            $profilClient = $clientCerfa->getProfil();
    
            $effectiveUserId = $profilClient[0]['roleCreation'] == 2 ? $profilClient[0]['idCreation'] : $id;

        }else{
            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_users = $id;
            $datagestionnairescentre = $gestionnaireCentre->searchForId();
            $effectiveUserId = $datagestionnairescentre['id_centres_de_formation'];
          
        }

       

        $opco = new Opco();
        $result = $opco->searchTypeIdCentre($effectiveUserId,$nbreParPage,$pageCourante,$search);
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


