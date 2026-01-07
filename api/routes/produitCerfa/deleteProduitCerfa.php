<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/ProduitCerfaModel.php';
require_once __DIR__.'/../../models/AbonnementCerfaModel.php';

//========================================================================================
// But : Supprimer un utilisateur
// Rôles : Admins
// param: user_id
//========================================================================================
$app->delete('/api/produitCerfa/{id}/delete', function (Request $request, Response $response, $param) use ($key) {
    // Check => Format param
    if(empty($param['id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $token=$request->getAttribute('user');
    $id = $token['id'];
    $role =$token['role'];
    $idbyrole =$token['idByRole'];
    $data = $request->getParsedBody();

    $produitCerfa = new Produit();

    $produitCerfa->id = $param['id'];
    
    $idExist = $produitCerfa->boolId();
    

    if($role == 1 ){
        if($idExist){
            $abonnementCerfa = new AbonnementCerfa();
            $tableauabonnementCerfa =  $abonnementCerfa->searchAllForIdProduit($param['id']);
           

            if(empty($tableauabonnementCerfa)){
                $produitCerfa->deleteProduit();
                $response->getBody()->write(json_encode(['valid' => "Le produit  est supprimé"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
            }else{
                $response->getBody()->write(json_encode(['erreur' => "ce produit ne peut pas etre supprimer car il est utiliser par un client"]));
               return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }

           

        } else {
            $response->getBody()->write(json_encode(['erreur' => "ce produit n'existe pas"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } 
       
    }else{
        $response->getBody()->write(json_encode(['erreur' => "Vous n'aves pas les droits pour acceder a cette ressource"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($checkAdmin);