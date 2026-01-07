<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/CentreFormationModel.php';
require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/FormateursParticipantSessionModel.php';
require_once __DIR__.'/../../models/FormationModel.php';
require_once __DIR__.'/../../models/SessionModel.php';

//========================================================================================
// But : Récupérer les sessions en cours d'un centre de formation
// Rôles : Admins, gestionnaire de centre, formateur
// champs: idCentre (pour autre que admin valeur par défaut : default)
//========================================================================================

$app->get('/api/getSessionsEnCoursFromCentre/{byCentreId}', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];

    $centre = new CentreFormation();
    switch ($role){
        case 1:
            $selectedCentre = $param['byCentreId'];
            break;
        case 3:
        case 4:
            $roleTable = new User();
            $roleTable->id = $token['id'];
            $roleTable->id_role = $role;
            $selectedCentre = $roleTable->searchIdCentreForIdUsers();
            break;
            
    }
    $centre->id = $selectedCentre;
    if (filter_var($centre->id, FILTER_VALIDATE_INT) == false || empty($centre->id)) {
        $response->getBody()->write(json_encode(['erreur' => "Erreur dans la récupération du centre"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    $sessions = $centre->getSessionsEnCoursEtAvenirFromCentre();
    $sessionUsers = [];
    $sessionInfos = new Session();

    foreach($sessions as $session){
        $sessionInfos->id = $session['id'];
        $sessionInfos->id_centres_de_formation = $param['byCentreId'];
        $etudiants = $sessionInfos->getSessionEnCoursEtudiants();

        $formateurs = $sessionInfos->getSessionEnCoursFormateur();

        $participants = array_merge_recursive($etudiants, $formateurs);

        $sessionUsers[$session['nomSession']] = empty($participants) ? null : $participants;
    }


    if(empty($sessions)){
        $response->getBody()->write(json_encode(['erreur' => "Il n'y a aucune donnée à afficher"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    } else {
        $result = [
            'sessions' => $sessions,
            'participants' => $sessionUsers
        ];

        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
})->add($auth)->add($check_role([1,3,4]));

//========================================================================================
// But : Récupérer les infos d'une session
// Rôles : Admins, gestionnaire de centre
// champs: idCentre
//========================================================================================

$app->get('/getSessions/{idCentre}', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $user = new User();
    $user->id = $token['id'];
    $user->id_role = intval($token['role']);

    try {
        $selected = "";
        switch ($user->id_role){
            case 1:
                $selected = filter_var($param['idCentre'], FILTER_VALIDATE_INT) == true ? $param['idCentre'] : "all";
                break;

            case 3:
                $selected = $user->searchIdCentreForIdUsers();
                if(empty($selected) || filter_var($selected, FILTER_VALIDATE_INT) == false ){
                    throw new Exception("Erreur dans la récupération des informations du centre de l'utilisateur");
                }
                break;
        }

        $session = new Session();
        if($selected == "all"){
            $sessions = $session->getAllSessions_admin();
        } else {
            $sessions = $session->getSessions($selected);
        }

        if(empty($sessions)){
            throw new Exception("Aucun résultat");
        }

        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $sessions]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($check_role([1,3]));



$app->get('/getSessionsEnCoursByFormation', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];

    function getSessionsEnCoursByFormation($idCentre, $idFormation, $request, $response) {
        $session = new Session();
        $session->id_centres_de_formation = $idCentre;
        $session->id_formations = $idFormation;
        $sessions = $session->getSessionsEnCoursByFormation();

        if ($sessions) {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'sessions_en_cours' => $sessions]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Aucune session à retourner"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    if ($role === 1 || $role === 3) {

        $parsedBody = $request->getParsedBody();

        $requiredFields = ['idCentre','idFormation'];
        foreach ($requiredFields as $field) {
            if (!isset($parsedBody[$field])) {
                $response->getBody()->write(json_encode(['erreur' => 'Il manque des données dans la requête']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        $idCentre = $parsedBody['idCentre'];
        $idFormation = $parsedBody['idFormation']; 

        return getSessionsEnCoursByFormation($idCentre, $idFormation, $request, $response);
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

$app->get('/getSessionsTermineesFromCentre', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];

    function getSessionsTermineesFromCentre($idCentre, $request, $response) {
        $session = new Session();
        $session->id_centres_de_formation = $idCentre;
        $sessions = $session->getSessionsTermineesFromCentre();

        if ($sessions) {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'sessions_terminees' => $sessions]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Aucune session à retourner"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    if ($role === 1 || $role === 3) {

        if(!isset($request->getParsedBody()['idCentre'])) {
            $response->getBody()->write(json_encode(['erreur' => "paramètre manquant : idCentre"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }

        $idCentre = $request->getParsedBody()['idCentre'];

        return getSessionsTermineesFromCentre($idCentre, $request, $response);
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

$app->get('/getSessionsTermineesByFormation', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];

    function getSessionsTermineesByFormation($idCentre, $idFormation, $request, $response) {
        $session = new Session();
        $session->id_centres_de_formation = $idCentre;
        $session->id_formations = $idFormation;
        $sessions = $session->getSessionsTermineesByFormation();

        if ($sessions) {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'sessions_terminees' => $sessions]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Aucune session à retourner"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    if ($role === 1 || $role === 3) {

        $parsedBody = $request->getParsedBody();

        $requiredFields = ['idCentre','idFormation'];
        foreach ($requiredFields as $field) {
            if (!isset($parsedBody[$field])) {
                $response->getBody()->write(json_encode(['erreur' => 'Il manque des données dans la requête']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        $idCentre = $parsedBody['idCentre'];
        $idFormation = $parsedBody['idFormation'];   

        return getSessionsTermineesByFormation($idCentre, $idFormation, $request, $response);
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

$app->get('/getSessionsAvenirFromCentre', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];

    function getSessionsAvenirFromCentre($idCentre, $request, $response) {
        $session = new Session();
        $session->id_centres_de_formation = $idCentre;
        $sessions = $session->getSessionsAvenirFromCentre();

        if ($sessions) {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'sessions_a_venir' => $sessions]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Aucune session à retourner"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    if ($role === 1 || $role === 3) {

        if(!isset($request->getParsedBody()['idCentre'])) {
            $response->getBody()->write(json_encode(['erreur' => "paramètre manquant : idCentre"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }

        $idCentre = $request->getParsedBody()['idCentre'];

        return getSessionsAvenirFromCentre($idCentre, $request, $response);
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

$app->get('/getSessionsAvenirByFormation', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];

    function getSessionsAvenirByFormation($idCentre, $idFormation, $request, $response) {
        $session = new Session();
        $session->id_centres_de_formation = $idCentre;
        $session->id_formations = $idFormation;
        $sessions = $session->getSessionsAvenirByFormation();

        if ($sessions) {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'sessions_a_venir' => $sessions]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Aucune session à retourner"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    if ($role === 1 || $role === 3) {

        $parsedBody = $request->getParsedBody();

        $requiredFields = ['idCentre','idFormation'];
        foreach ($requiredFields as $field) {
            if (!isset($parsedBody[$field])) {
                $response->getBody()->write(json_encode(['erreur' => 'Il manque des données dans la requête']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        $idCentre = $parsedBody['idCentre'];
        $idFormation = $parsedBody['idFormation']; 

        return getSessionsAvenirByFormation($idCentre, $idFormation, $request, $response);
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);


$app->get('/sessions/all', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $user = new User();
    $user->id = $token['id'];
    $user->id_role = intval($token['role']);

    
    $session = new Session();
    $formation = new Formation();

    // Administrateurs
    if($role == 1){
        $sessions = $session->getAllSessions_admin();
    }

    // GEntreprise
    if($role == 2){
        $gEntreprise = new GestionnaireEntreprise();
        $gEntreprise->id_users = $token['id'];
        $idEntreprise = $gEntreprise->searchEntrepriseForIdUsers();

        if(empty($idEntreprise)){
            $response->getBody()->write(json_encode(['erreur' => "Ce gestionnaire n'est pas rattaché à une entreprise"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $gEntreprise->id_entreprises = $idEntreprise;

        $sessions = $gEntreprise->getAllSessions_gEntreprise();
    }
    
    // GCentre
    if($role === 3){
        $gestionnaire  = new GestionnaireCentre();
        $idcentreformation = $user->searchIdCentreForIdUsers();

        if (empty($idcentreformation)) {
            $response->getBody()->write(json_encode(['erreur' => "Ce gestionnaire n'est pas rattaché a un centre de formation"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $gestionnaire->id_centres_de_formation = $idcentreformation;

        $sessions = $gestionnaire->getAllSessions_gCentre();
    }

    // Si Formateur
    if($role === 4){
        $formateurParticipant = new FormateursParticipantSession();
        $formateurParticipant->id_formateurs = $token['idByRole'];

        $sessions = $formateurParticipant->getAllSessions_formateur();
    }

    // Si conseiller financeur
    if($role === 6){
        $financeur  = new Financeur();
        $financeur->id_users = $token['id'];
        $idEntreprise = $financeur-> getIdEntrepriseByUserId();

        if (empty($idEntreprise)) {
            $response->getBody()->write(json_encode(['erreur' => "Ce financeur n'est pas rattaché a une entreprise"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        
        $financeur->id_entreprises = $idEntreprise;

        $sessions = $financeur->getAllSessions_financeur();
    }

    if (!empty($sessions)) {
        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $sessions]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Liste des sessions vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
    
})->add($auth)->add($checkNotEtudiant);


//========================================================================================
// But : Récupérer session détails + participant
// Rôles : Admins, gestionnaire de centre, formateurs
// champs: bySessionId
//========================================================================================

$app->get('/formations/session/{bySessionId}/details', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $user = $token['id'];

    // Check : Param valide
    if(empty($param['bySessionId']) || !filter_var($param['bySessionId'], FILTER_VALIDATE_INT)){
        $response->getBody()->write(json_encode(['erreur' => "ID de session invalide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $session = new Session();
    $session->id = $param['bySessionId'];

    // Si GestionnaireCentre
    if($role === 3 || $role === 4){
        if($role === 3){
            $userRole  = new GestionnaireCentre();
        } elseif ($role === 4) {
            $userRole  = new Formateur();
        }

        $session_centreId = $session->getCentreForId();
        
        $userRole->id_users = $token['id'];
        $user_centreId = '';
        $user_centreId = $userRole->searchCentreForIdUsers();

        if($session_centreId != $user_centreId){
            $response->getBody()->write(json_encode(['erreur' => "L'utilisateur connecté n'a pas accès à cette session"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    $dataSession = $session->getSessionDetails();
    if(empty($dataSession)){
        $response->getBody()->write(json_encode(['erreur' => "La session sélectionnée n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    $dataFormateurs = $session->getSessionFormateurs();
    $dataEtudiants = $session->getSessionEtudiants();

    $dataSession['formateurs'] = [];
    $dataSession['etudiants'] = [];

    if(!empty($dataFormateurs)){
        foreach ($dataFormateurs as $formateur) {
            $dataSession['formateurs'][] = $formateur;
        }
    } else {
        $dataSession['formateurs'][] = "Il n'y a aucun formateur actuellement pour cette session";
    }
        
    if(!empty($dataEtudiants)){
        foreach ($dataEtudiants as $etudiant) {
            $dataSession['etudiants'][] = $etudiant;
        }
    } else {
        $dataSession['etudiants'][] = "Il n'y a aucun étudiant actuellement pour cette session";
    }

    $result = $dataSession;

    if (!empty($result)) {
        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Il n'y a aucun résultat"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth);


$app->get('/getSessionsFromCentre/{byCentreId}', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];

    try {
        $selected = "";
        switch ($role) {
            case 1:
                if(filter_var($param['byCentreId'], FILTER_VALIDATE_INT) == false){
                    throw new Exception('Format du paramètre invalide');
                }
                $selected = $param['byCentreId'];
                break;
            
            case 3:
            case 4:
                $user = new User();
                $user->id = $token['id'];
                $user->id_role = $role;
                $user_centre = $user->searchIdCentreForIdUsers();
                if(empty($user_centre) || filter_var($user_centre, FILTER_VALIDATE_INT) == false){
                    throw new Exception("Impossible de récupérer le centre de l'utilisateur");
                }
                $selected = $user_centre;
                break;

            default:
                throw new Exception('Rôle inattendu');
                break;
        }

        $centre = new CentreFormation();
        $centre->id = $selected;
        $sessions = $centre->getSessionsFromCentre();
        $sessionUsers = [];
        $sessionInfos = new Session();

        if(empty($sessions)){
            throw new Exception("Il n'y a aucune session dans ce centre");
        }
        
        foreach($sessions as $session){
            $sessionInfos->id = $session['id'];
            $sessionInfos->id_centres_de_formation = $param['byCentreId'];
            $etudiants = $sessionInfos->getSessionEnCoursEtudiants();
    
            $formateurs = $sessionInfos->getSessionEnCoursFormateur();
    
            $participants = array_merge_recursive($etudiants, $formateurs);
    
            $sessionUsers[$session['nomSession']] = empty($participants) ? null : $participants;
        }
    
        
        $result = [
            'sessions' => $sessions,
            'participants' => $sessionUsers
        ];

        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch(Exception $e) {
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($check_role([1,3,4]));

$app->get('/session/{id}/participants', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    try{
        if(filter_var($param['id'], FILTER_VALIDATE_INT) == false){
            throw new Exception("Paramètre invalide");
        }

        $session = new Session();
        $session->id = $param['id'];
        $sessionInfos = $session->infos();

        switch ($role){
            /*case 2:
                $roleTable = new GestionnaireEntreprise;
                break;*/
            case 3:
            case 4:
                if($token['centre'] != $sessionInfos[0]['id_centres_de_formation']){
                    throw new Exception("Accès interdit : $sessionCentre");
                }
                break;
            case 5:
                $etudiant = new Etudiant();
                $etudiant->id_users = $token['id'];
                $etudiantInfos = $etudiant->infos();
                if($etudiantInfos['id_session'] != $session->id){
                    throw new Exception("Accès interdit");
                }
                break;
            /*case 6:
                break;*/
        }

        $result = $session->getParticipants();
        
        if(empty($result)){
           throw new Exception("Aucun participant trouvé");
        } else {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }

    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($check_role([1,3,4,5]));