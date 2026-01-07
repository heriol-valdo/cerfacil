<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
    
require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__.'/../../models/ClientCerfaModel.php';

require_once __DIR__ .'/../../models/AdminModel.php';

require_once __DIR__.'/../../models/CerfaModel.php';


//========================================================================================
// But : Récupérer la liste des client cerfa
// Rôles : Admins
// param :user_id
//========================================================================================



$app->get('/api/clientCerfa', function (Request $request, Response $response, $args) use ($key) {  
    $token=$request->getAttribute('user');
    $id = $token['id'];
    $role =$token['role'];
    $idbyrole =$token['idByRole'];

   if(empty($token)){
    $response->getBody()->write(json_encode(['erreur' => "token invalide"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

   if($role == 1 || $role == 7 || $role === 3){
        $clientCerfa = new ClientCerfa(); 

        $allclientCerfa =  $clientCerfa->searchAllClientCerfaForAdmin();

        if(!empty( $allclientCerfa)){
            $response->getBody()->write(json_encode(['valid' => $allclientCerfa ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "Listes des clients cerfa vides"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

   }
    else{
        $response->getBody()->write(json_encode(['erreur' => "Vous n'aves pas les droits pour acceder a cette ressource"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }


   
})->add($auth);


$app->post('/api/achatClientCerfa', function (Request $request, Response $response, $args) use ($key) {  
    $token=$request->getAttribute('user');
    $id = $token['id'];
    $role =$token['role'];
    $idbyrole =$token['idByRole'];
    $data =$request->getParsedBody();

   if(empty($token)){
    $response->getBody()->write(json_encode(['erreur' => "token invalide"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

   if($role == 1 || $role == 7 || $role === 3){
        $clientCerfa = new ClientCerfa(); 
        $clientCerfa->id_users = $data['id_users'];
        $allclientCerfa =  $clientCerfa->getAllAchatByIdClient();

        if(!empty( $allclientCerfa)){
            $response->getBody()->write(json_encode(['valid' => $allclientCerfa ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "Liste des Achats vide pour ce client vides".$data['id_users']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

   }
    else{
        $response->getBody()->write(json_encode(['erreur' => "Vous n'aves pas les droits pour acceder a cette ressource"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }


   
})->add($auth);

$app->get('/api/clientCerfaSearchType', function (Request $request, Response $response) use ($key) {

    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    $search=$data['search'];
    $pageCourante=$data['pageCourante'];
    $nbreParPage=$data['nbreParPage'];
   
    if ($role === 7 || $role === 3) {
        $clientCerfa = new ClientCerfa(); 

        $result =   $clientCerfa->searchType($userConnected,$nbreParPage,$pageCourante,$search);
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

$app->get('/api/clientCerfaCountBySearchType', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $search=$data['search'];
    $id = $token['id'];

    if ($role === 7 || $role === 3) {
       
        $clientCerfa = new ClientCerfa(); 
        $result = $clientCerfa->countBySearchType($id,$search);
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

$app->post('/api/cerfaSearchClient', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $id=$data['id'];
        $cerfa = new Cerfa();
        $cerfas = $cerfa->findByIdClient($id);
        if (!empty($cerfas)) {
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie','data' => $cerfas]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "tableau vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    
});


