<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__.'/../../models/FinanceurModel.php';
require_once __DIR__.'/../../models/EntrepriseModel.php';
require_once __DIR__.'/../../models/GestionnaireCentreModel.php';

//========================================================================================
// But : Récupérer les données d'un financeur
// Rôles : Admins, conseillers financeurs
// param: user_id
//========================================================================================
$app->get('/api/financeur/{user_id}/profil', function (Request $request, Response $response, $param) use ($key) {

    $token=$request->getAttribute('user');
    $role=intval($token['role']);
    $userConnected = $token['id'];  

    // Check => Format param
    if(empty($param['user_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ user_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['user_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    if($role === 1 || $userConnected === $param['user_id']){

        $financeur = new Financeur(); 
        $financeur->id_users = $param['user_id'];

        if($financeur->boolId() === false){
            $response->getBody()->write(json_encode(['erreur' => "Cet utilisateur n'existe pas"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $financeurDatas = $financeur->getProfilFinanceur();

        $entreprise = new Entreprise();
        $idEntreprise = $financeurDatas[0]['id_entreprises'];
        $entreprise->id=$idEntreprise;
        $entrepriseDatas = $entreprise->getEntrepriseDatas();

        $financeursDatasFiltered = array_filter($financeurDatas[0], function($value, $key) {
            return ($key !== 'id' && $key !== 'id_entreprises');
        }, ARRAY_FILTER_USE_BOTH);   
        
        $entrepriseDatasFiltered = array_filter($entrepriseDatas[0], function($value, $key) {
            return ($key !== 'id' && $key !== 'dateCreation' );
        }, ARRAY_FILTER_USE_BOTH); 

        $profilDatas = [
            'financeurDatas'=>$financeursDatasFiltered,
            'entrepriseDatas'=>$entrepriseDatasFiltered,
        ];

        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $profilDatas]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

    }else{
        $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits suffisants"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

})->add($auth);



//========================================================================================
// But : Récupérer la liste des etudiants d'un financeur
// Rôles : conseillers financeurs
// champs : aucun
//========================================================================================
$app->get('/api/financeur/listeFinancement', function (Request $request, Response $response, $param) use ($key) {
    $token=$request->getAttribute('user');
    $role=intval($token['role']);
    $userConnected = $token['id'];  

    $financeur = new Financeur(); 
    $financeur->id_users = $token['id'];

    $result = $financeur->searchFinancedForEntreprise();

    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

})->add($auth)->add($checkFinanceur);

$app->get('/api/getFinanceur', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];


    if ($role === 1 ) {
        $financeur = new Financeur();
        $financeurs =$financeur->getFinanceurForAdmin();


        if (!empty( $financeurs)) {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $financeurs]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "liste des financeurs vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
        
    } 
    
    else if($role === 3){
        $gestionnaireCentre = new GestionnaireCentre();
        $idCentre= $gestionnaireCentre->searchCentreForId($userConnected);

        $financeur = new Financeur();
        $financeurs = $financeur->getFinanceurForCentre($idCentre);

        if (!empty( $financeurs)) {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $financeurs]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "liste des financeurs vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);