<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/EntrepriseModel.php';
require_once __DIR__.'/../../models/EntrepriseTypeModel.php';
require_once __DIR__.'/../../models/GestionnaireEntrepriseModel.php';


//========================================================================================
// But : Affiche liste des entreprises
// Rôles : Admins
// champs :aucun
//========================================================================================

$app->get('/api/admin/entreprise/liste', function (Request $request, Response $response) use ($key) {
    $entreprise = new Entreprise(); 

    $result = $entreprise->searchAll();
    if(!$result){
        $response->getBody()->write(json_encode(['erreur' => "Il n'a pas d'entreprise à afficher"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth)->add($checkAdmin);

//========================================================================================
// But : Affiche les informations d'une entreprise
// Rôles : Admins
// param: entreprise_id
//========================================================================================
$app->get('/api/admin/entreprise/{entreprise_id}', function (Request $request, Response $response, $param) use ($key) {
    $entreprise = new Entreprise(); 
    $entreprise->id = $param['entreprise_id'];

    // Check existence de l'entreprise
    $entrepriseExist = $entreprise->boolId();
    if(!$entrepriseExist){
        $response->getBody()->write(json_encode(['erreur' => "L'entreprise n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $resultEntreprise =  $entreprise->searchOneEntreprise();
    $nbCentre = $entreprise->countCentre();

    $data = [
        "dataEntreprise" => $resultEntreprise
    ];

    if($nbCentre > 0){
        $resultCentre = $entreprise->searchCentreForOne();
        $data["dataCentre"] = $resultCentre;
    }

    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $data ]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth)->add($checkAdmin);


//========================================================================================
// But : Affiche les informations de son entreprise
// Rôles : Gestionnaire d'entreprise
// champs: aucun
//========================================================================================
$app->get('/api/entreprise/info', function (Request $request, Response $response) use ($key) {
    $token=$request->getAttribute('user');
    $entreprise = new Entreprise(); 
    $gestionnaireEntreprise = new GestionnaireEntreprise();
    $gestionnaireEntreprise->id_users = $token['id'];

    $userInfo = $gestionnaireEntreprise->searchForId();

    $entreprise->id = $userInfo['id_entreprises'];
    // Check existence de l'entreprise
    $entrepriseExist = $entreprise->boolId();
    if(!$entrepriseExist){
        $response->getBody()->write(json_encode(['erreur' => "L'entreprise n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $resultEntreprise =  $entreprise->searchOneEntreprise();
    $nbCentre = $entreprise->countCentre();

    $data = [
        "dataEntreprise" => $resultEntreprise
    ];

    if($nbCentre > 0){
        $resultCentre = $entreprise->searchCentreForOne();
        $data["dataCentre" ] = $resultCentre;
    }

    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $data ]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth)->add($checkGestionnaireEntreprise);

$app->get('/api/getEntreprise', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];


    if ($role === 1 ) {
        $entreprise = new Entreprise();
        $entreprises =$entreprise->getEntrepriseForAdmin();


        if (!empty( $entreprises)) {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $entreprises]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "liste des entreprises vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
        
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

//========================================================================================
// But : Affiche la liste des entreprises en fonction du type
// Rôles : Admins, Gestionnaire de centre
// param: type
//========================================================================================

$app->get('/api/entreprise/liste/{type}', function (Request $request, Response $response, $param) use ($key) {

    if(!isset($param['type']) || empty($param['type']) ){
        $response->getBody()->write(json_encode(['erreur' => "Type absent"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    if($param['type']!= "financeur" && $param['type']!= "accueil" && $param['type']!= "centre"){
        $response->getBody()->write(json_encode(['erreur' => "Type invalide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $entreprise = new EntrepriseType();
    $entreprises = $entreprise->getEntrepriseByType($param['type']);
    if(!empty( $entreprises)){
        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $entreprises]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
    else {
        $response->getBody()->write(json_encode(['erreur' => "Aucune entreprise de type " . $param['type'] . " dans la base de données"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }

})->add($auth);