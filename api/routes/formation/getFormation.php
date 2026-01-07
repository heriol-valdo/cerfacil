<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/GestionnaireEntrepriseModel.php';
require_once __DIR__.'/../../models/FormateurModel.php';
require_once __DIR__.'/../../models/AdminModel.php';
require_once __DIR__.'/../../models/FinanceurModel.php';
require_once __DIR__.'/../../models/FormationModel.php';
require_once __DIR__.'/../../models/SessionModel.php';

//========================================================================================
// But : Récupérer formations d'un centre de formation
// Rôles : Admins, gestionnaire de centre
// champs: idCentre
//========================================================================================

$app->get('/getFormationsFromCentre', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];

    function getFormationsFromCentre($idCentre, $request, $response) {
        $formation = new Formation();
        $formation->id_centres_de_formation = $idCentre;
        $formations = $formation->getFormationsFromCentre();

        if ($formations) {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $formations]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "La récupération des données a échoué"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    if ($role === 1 || $role === 3) {

        if(!isset($request->getParsedBody()['idCentre'])) {
            $response->getBody()->write(json_encode(['erreur' => "paramètre manquant : idCentre"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $idCentre = $role === 1 ? $request->getParsedBody()['idCentre'] : null;

        if ($role === 3) {
            $gestionnaire = new GestionnaireCentre();
            $gestionnaire->id_users = $userConnected;
            $gestionnaireDatas = $gestionnaire->getProfilGestionnaireCentre();
            $idCentre = $gestionnaireDatas[0]['id_centres_de_formation'];
        }
        
        return getFormationsFromCentre($idCentre, $request, $response);
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

//========================================================================================
// But : Récupérer les infos d'une formation d'un centre
// Rôles : Admins, gestionnaire de centre
// champs : idFormation
//========================================================================================

$app->get('/getOneFormationFromCentre', function (Request $request,Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];

    function getOneFormationFromCentre($idFormation, $request, $response) {
        $formation = new Formation();
        $formation->id = $idFormation;
        $datas = $formation->getOneFormationFromCentre();

        if ($datas) {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $datas]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "La récupération des données a échoué"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    if ($role === 1 || $role === 3) {

        if(!isset($request->getParsedBody()['idFormation'])) {
            $response->getBody()->write(json_encode(['erreur' => "paramètre manquant : idFormation"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        // Si gestionnaireCentre
        if($role === 3){
            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_users = $token['id'];
            $gestionnaireCentre_id_centre = $gestionnaireCentre->searchCentreForIdUsers();
            $formation_id_centre = $formation->searchCentreForId();

            if ($gestionnaireCentre_id_centre != $formation_id_centre){
                $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas accès aux informations de cette formation"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        $idFormation = $request->getParsedBody()['idFormation'];
        
        return getOneFormationFromCentre($idFormation, $request, $response);
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

$app->get('/getFormation', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];


    if ($role === 1 ) {
        $formation = new Formation();
        $formations =$formation->getFormationForAdmin();

    } elseif($role === 2){
        $gestionnaire  = new GestionnaireEntreprise();
        $gestionnaire->id_users = intval($token['id']);
        $idEntreprise= $gestionnaire->searchEntrepriseForIdUsers();

        if (empty($idEntreprise)) {
            $response->getBody()->write(json_encode(['erreur' => "Ce gestionnaire n'est pas rattaché à une entreprise"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $gestionnaire->id_entreprises = $idEntreprise;
        $formations = $gestionnaire->getAllFormations_gEntreprise();

    } elseif($role === 3){
        $gestionnaire  = new GestionnaireCentre();
        $idcentreformation= $gestionnaire->searchCentreForId($userConnected);

        if (empty($idcentreformation)) {
            $response->getBody()->write(json_encode(['erreur' => "ce gestionnaire n'est pas rattacher a un centre de formation"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $formation = new Formation();
        $formations =$formation->getFormationForGestionnaire($idcentreformation);

    }  elseif($role === 4){
        $formateur  = new Formateur();
        $formateur->id = $token['idByRole'];
        $formations = $formateur->getAllFormations_formateur();
        
    } elseif($role === 6){
        $financeur  = new Financeur();
        $financeur->id_users = $token['id'];
        $financeur->id_entreprises = $financeur->searchEntrepriseForIdUsers();
        $formations = $financeur->getAllFormations_financeur();
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }

    // Post requete SQL
    if (!empty($formations)) {
        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' =>$formations]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Liste des formations vide pour ce centre de formation"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);


//========================================================================================
// But : Récupérer formations d'un centre de formation (no body)
// Rôles : Admins, gestionnaire de centre
// champs: idCentre
//========================================================================================

$app->get('/centre/{idCentre}/formations', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];

    $selected = "all";

    try {
        switch ($role){
            case 1:
                $selected = filter_var($param['idCentre'], FILTER_VALIDATE_INT) == true ? $param['idCentre'] : "all";
                break;

            case 3:
            case 4:
                $logged = new User();
                $logged->id = $token['id'];
                $logged->id_role = $role;
                $logged_id_centre = $logged->searchIdCentreForIdUsers();
                if(empty($logged_id_centre) || filter_var($logged_id_centre, FILTER_VALIDATE_INT) == false){
                    throw new Exception("Impossible de retrouver le centre de l'utilisateur");
                }
                    
                $selected = $logged_id_centre;
                break;

            default:
                throw new Exception('Rôle inattendu');
        }

        $formation = new Formation();
        $formation->id_centres_de_formation = $selected;
        $formations = $formation->getFormationsFromCentre();
        
        if ($formations != null) {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $formations]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Aucun résultat"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth);


//========================================================================================
// But : Récupérer formations et sessions associées d'un centre de formation (no body)
// Rôles : Admins, gestionnaire de centre, formateurs
// champs: byCentreId
//========================================================================================

$app->get('/formations/sessions/centre/{byCentreId}', function (Request $request, Response $response, $param) use ($key) {
    $byCentreId = "all";
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];


    if($role === 1){
        if(!empty($param['byCentreId']) && filter_var($param['byCentreId'], FILTER_VALIDATE_INT)){
            $byCentreId = $param['byCentreId'];
        }
    }
   
    // Si GestionnaireCentre
    if($role === 3 || $role === 4){
        if($role === 3){
            $userRole  = new GestionnaireCentre();
        } elseif ($role === 4) {
            $userRole  = new Formateur();
        }

        $userRole->id_users = $token['id'];
        $byCentreId = '';
        $byCentreId = $userRole->searchCentreForIdUsers();

        if(empty($byCentreId)) {
            $response->getBody()->write(json_encode(['erreur' => "L'utilisateur n'est pas rattaché a un centre de formation"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    $formation = new Formation();
    $formation->id_centres_de_formation = $byCentreId;

    $dataFormations = $formation->getFormationsFromCentre();
    if(empty($dataFormations)) {
        $response->getBody()->write(json_encode(['erreur' => "Il n'y a aucune formation dans ce centre"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $dataSessions = $formation->getSessionsFromCentre();

    $data = [];
    foreach ($dataFormations as $formation) {
        if(!empty($dataSessions)){
            $formation['sessions'] = [];
            foreach ($dataSessions as $session) {
                if ($session['id_formations'] == $formation['id']) {
                    $formation['sessions'][] = $session;
                }
            }
        } else {
            $formation['sessions'] = "Il n'y a aucune session pour cette formation";
        }
        

        $data[] = $formation;
    }

    if (!empty($data)) {
        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $data]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Il n'y a aucun résultat"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth);
