<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';
require_once __DIR__.'/../../models/EntrepriseModel.php';
require_once __DIR__.'/../../models/FinanceurModel.php';
require_once __DIR__.'/../../models/EtudiantModel.php';

//========================================================================================
// But : Affiche liste des centres de formation, l'entreprise en lien et le nombre de formations proposées
// Rôles : Admins
// champs :aucun
//========================================================================================

$app->get('/api/admin/centreFormation/liste', function (Request $request, Response $response) use ($key) {
    $centreFormation = new CentreFormation(); 

    $result = $centreFormation->searchAll();
    if(!$result){
        $response->getBody()->write(json_encode(['erreur' => "Il n'a pas de centre à afficher"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth)->add($checkAdmin);


//========================================================================================
// But : Affiche les informations d'un centre
// Rôles : Admins
// param :centre_id
//========================================================================================
$app->get('/api/centreFormation/centreDetails/{centre_id}', function (Request $request, Response $response, $param) use ($key) {
    $data = $request->getParsedBody();
    $token=$request->getAttribute('user');
    $userConnected = $token['id'];
    $role=intval($token['role']);
    $idByRole=$token['idByRole'];


    if ($role !== 1 && $role !== 6) {
        $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits requis"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    
    $centreFormation = new CentreFormation(); 
    $centreFormation->id = $param['centre_id'];

    $resultCentre =  $centreFormation->searchOneCentre();
    $resultFormation = $centreFormation->searchFormationForOne();
    if(!$resultCentre){
        $response->getBody()->write(json_encode(['erreur' => "Il n'a pas de centre à afficher"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $entreprise = new Entreprise();
    $entreprise->id = $resultCentre[0]['id_entreprises'];
    $resultEntreprise = $entreprise->searchOneEntreprise();

    if($role == 1){
        $data = [
            "dataEntreprise" => $resultEntreprise,
            "dataCentre" => $resultCentre,
            "dataFormation" => $resultFormation
        ];
    
    }

    if($role == 6){
        $financeur = new Financeur();
        $financeur->id=$idByRole;
        $entrepriseFinanceuse = $financeur->getFinanceurDatas()[0]['id_entreprises'];

        $etudiant = new Etudiant();
        $etudiant->id_centres_de_formation = $centreFormation->id;
        $etudiant->id_entreprises = $entrepriseFinanceuse;

        $etudiant_id_centres_de_formation = $etudiant->id_centres_de_formation;
        $etudiant_id_entreprises = $etudiant->id_entreprises;
        $etudiantsFinanced = $etudiant->searchFinancedFromCentre();
        if(empty($etudiantsFinanced)){
            $response->getBody()->write(json_encode(['erreur' => "$etudiant_id_centres_de_formation Vous ne financez aucun étudiant dans ce centre $etudiant_id_entreprises"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        
        $data = [
            "dataEntreprise" => $resultEntreprise,
            "dataCentre" => $resultCentre,
            "dataFormation" => $resultFormation,
            "dataFinanced" => $etudiantsFinanced
        ];
    
    }

    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $data ]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth);

//========================================================================================
// But : Affiche les informations d'un centre
// Rôles : gestionnaires de centre
// champs :aucun
//========================================================================================

$app->get('/api/centreFormation/info', function (Request $request, Response $response, $param) use ($key) {
    $token=$request->getAttribute('user');

    $centreFormation = new CentreFormation(); 
    $gestionnaireCentre = new GestionnaireCentre();

    $gestionnaireCentre->id_users = $token['id']; 
    $resultUser = $gestionnaireCentre->searchForId();

    $centreFormation->id = $resultUser['id_centres_de_formation'];

    $resultCentre =  $centreFormation->searchOneCentre();
    $resultFormation = $centreFormation->searchFormationForOne();
    if(!$resultCentre){
        $response->getBody()->write(json_encode(['erreur' => "Il n'a pas de centre à afficher"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $data = [
        "dataCentre" => $resultCentre,
        "dataFormation" => $resultFormation
    ];

    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $data ]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth)->add($checkGestionnaireCentre);




//========================================================================================
// But : Affiche liste des centres de formation financés
// Rôles : Financeurs
// champs :aucun
//========================================================================================

$app->get('/api/centreFormation/liste/financed', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $financeur = new Financeur();
    $financeur->id_users = $token['id'];

    $userInfo = $financeur->searchForId();

    $financeur->id_entreprises = $userInfo['id_entreprises'];

    $result = $financeur->searchCentreForEntreprise();

    if(!$result){
        $response->getBody()->write(json_encode(['erreur' => "Il n'y a pas de centre à afficher"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth)->add($checkFinanceur);