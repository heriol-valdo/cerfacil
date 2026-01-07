<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
    
require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__.'/../../models/ProduitCerfaModel.php';
require_once __DIR__.'/../../models/ClientCerfaModel.php';







$app->post('/api/produitCerfa', function (Request $request, Response $response, $args) use ($key) {  
    $token=$request->getAttribute('user');
    $id = $token['id'];
    $role =intval($token['role']);
    $idbyrole =$token['idByRole'];

   if(empty($token)){
    $response->getBody()->write(json_encode(['erreur' => "token invalide"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }


   if($role === 1  || $role === 7 || $role === 3){

        $produitCerfa = new Produit(); 
       
        $allproduitCerfa  = $produitCerfa->produitCerfa();

        if(!empty( $allproduitCerfa)){
            $response->getBody()->write(json_encode(['valid' => $allproduitCerfa ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "Listes des produit cerfa vides"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }

   }
    else{
        $response->getBody()->write(json_encode(['erreur' => "Vous n'aves pas les droits pour acceder a cette ressource"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }


   
})->add($auth);


// $app->post('/api/produitCerfaFind', function (Request $request, Response $response) use ($key) {
//     $token = $request->getAttribute('user');
//     $role = intval($token['role']);
//     $data =$request->getParsedBody();
//     $userconnected = $token['id'];
//     $id = isset($data['id']) ? $data['id'] : null;

//     if ($role === 7 || $role === 3) {
      
//         $produitCerfa = new Produit();
//         $result = $produitCerfa->getProduitCerfaDatasByIdAbonnement($id);
//         if (isset($result['erreur'])) {
//             $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
//             return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
//         } else{
//             $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie', 'data' => $result]));
//             return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
//         }
//     } else {
//         $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
//         return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
//     }
// })->add($auth);


$app->post('/api/produitCerfaFind', function (Request $request, Response $response) use ($key) { 
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    $data =$request->getParsedBody();

    $id = isset($data['id']) ? $data['id'] : null;

    if ($role === 7 || $role === 3) {
        $produitCerfa = new Produit();
        $produitCerfa->id = $id;
        $result = $produitCerfa->getProduitCerfaDatasById();

        if (isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } else {
            $response->getBody()->write(json_encode([
                'valid' => 'Récupération des données réussie',
                'data' => $result 
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);


