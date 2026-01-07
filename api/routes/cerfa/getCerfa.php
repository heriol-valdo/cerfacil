<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/CerfaModel.php';
require_once __DIR__.'/../../models/FactureModel.php';
require_once __DIR__.'/../../models/ClientCerfaModel.php';

require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';


$app->post('/api/cerfaByIdFormation', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $idformation=$data['idformation'];

    if ($role === 7 || $role === 3) {
        $cerfa= new Cerfa();
        $cerfas = $cerfa->findbyformation($idformation);
        if (!empty($cerfas)) {
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie','data' => $cerfas]));
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

$app->post('/api/cerfaByIdEmployeur', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $idemployeur=$data['idemployeur'];

    if ($role === 7 || $role === 3) {
        $cerfa= new Cerfa();
        $cerfas = $cerfa->findbyentreprise($idemployeur);
        if (!empty($cerfas)) {
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie','data' => $cerfas]));
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

$app->post('/api/cerfaByNom', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $nom=$data['nomA'];

    if ($role === 7 || $role === 3) {
        $cerfa= new Cerfa();
        $cerfas = $cerfa->byNom($nom);
        if (!empty($cerfas)) {
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie','data' => $cerfas]));
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
$app->post('/api/cerfaByEmail', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $email=$data['emailA'];

    if ($role === 7 || $role === 3 || $role === 5 || $role === 6) {
        $cerfa= new Cerfa();
        $cerfas = $cerfa->byEmail($email);

        if (!empty($cerfas)) {
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie','data' => $cerfas]));
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

$app->post('/api/cerfaFind', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $id=$data['id'];
        $cerfa = new Cerfa();
        $cerfas = $cerfa->find($id);
        if (!empty($cerfas)) {
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie','data' => $cerfas]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "tableau vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    
});

$app->post('/api/cerfaCountBySearchType', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $search=$data['search'];
    $id = intval($token['id']);

    if ($role === 7 || $role === 3) {

        $cerfa = new Cerfa();
        if($role === 7){
            $clientCerfa = new ClientCerfa();
            $clientCerfa->id_users = $id;
    
            $profilClient = $clientCerfa->getProfil();
    
            $effectiveUserId = $profilClient[0]['roleCreation'] == 2 ? $profilClient[0]['idCreation'] : $id;

            $result = $cerfa->countBySearchType($effectiveUserId,$search);

        }else{
            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_users = $id;
            $datagestionnairescentre = $gestionnaireCentre->searchForId();
            $effectiveUserId = $datagestionnairescentre['id_centres_de_formation'];

            $result = $cerfa->countBySearchTypeIdCentre($effectiveUserId,$search);
          
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

$app->post('/api/cerfaSearchType', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $search=$data['search'];
    $pageCourante=$data['pageCourante'];
    $nbreParPage=$data['nbreParPage'];
    $id = intval($token['id']);
   
    if ($role === 7 || $role === 3) {
        $cerfa = new Cerfa();

        if($role === 7){
            $clientCerfa = new ClientCerfa();
            $clientCerfa->id_users = $id;
    
            $profilClient = $clientCerfa->getProfil();
    
            $effectiveUserId = $profilClient[0]['roleCreation'] == 2 ? $profilClient[0]['idCreation'] : $id;

            $result = $cerfa->searchType($effectiveUserId,$nbreParPage,$pageCourante,$search);

        }else{
            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_users = $id;
            $datagestionnairescentre = $gestionnaireCentre->searchForId();
            $effectiveUserId = $datagestionnairescentre['id_centres_de_formation'];

            $result = $cerfa->searchTypeIdCentre($effectiveUserId,$nbreParPage,$pageCourante,$search);
          
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

$app->post('/api/findFactureByIdCerfa', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $id=$data['id'];
        $facture = new Facture();
        $factures= $facture->find($id);
        if (!empty($factures)) {
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie','data' => $factures]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "tableau vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    
});


