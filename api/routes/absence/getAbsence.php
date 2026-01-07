<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/AbsenceModel.php';
require_once __DIR__.'/../../models/AdminModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';
require_once __DIR__.'/../../models/EtudiantModel.php';
require_once __DIR__.'/../../models/FinanceurModel.php';
require_once __DIR__.'/../../models/FormateurModel.php';
require_once __DIR__.'/../../models/FormateursParticipantSessionModel.php';
require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/GestionnaireEntrepriseModel.php';
require_once __DIR__.'/../../models/SessionModel.php';


//========================================================================================
// But : Récupérer les absences d'un étudiant
// Rôles : Admins, GestionnaireCentre, Formateurs, GestionnaireEntreprise?
// Champs : idEtudiant
//========================================================================================

$app->get('/api/absences/getAllOneStudent/{idEtudiant}', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    $absence = new Absence();

    if($role === 5) {
        $absence->id_etudiants=$token['idByRole']->id;
    }

    if($role === 1 || $role === 3 || $role === 4) {
        
        if(!isset($param['idEtudiant'])) {
            $response->getBody()->write(json_encode(['erreur' => "L'id de l'étudiant est requis"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $etudiant = new Etudiant();
        $etudiant->id = $param['idEtudiant'];
        $etudiantExist=$etudiant->boolIdRole();

        if(!$etudiantExist){
            $response->getBody()->write(json_encode(['erreur' => "Cet étudiant n'existe pas"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        $etudiantSession = $etudiant->getEtudiantDatasById()['id_session'];
        $etudiantCentre = $etudiant->getEtudiantDatasById()['id_centres_de_formation'];

        if($role === 3){
            $gestionnaire = new GestionnaireCentre();
            $gestionnaire->id_users = $token['id'];
            $gestionnaireDatas=$gestionnaire->getProfilGestionnaireCentre();
            $gestionnaireCentre=$gestionnaireDatas[0]['id_centres_de_formation'];
            if($etudiantCentre !== $gestionnaireCentre) {
                $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits pour cet étudiant"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
            }
        }

        if($role === 4){
            $formateurParticipeSession = new FormateursParticipantSession();
            $formateurParticipeSession->id_formateurs = $token['idByRole'];
            $formateurParticipeSession->id = $etudiantSession;
            $check = $formateurParticipeSession->checkIfFormateurParticipantSessionExist();
            if(!$check) {
                $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits pour cet étudiant"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
            }
        }

        $absence->id_etudiants=$param['idEtudiant'];
    }

    $absences = $absence->getAbsencesOneStudent();
    if(!$absences) {
        $response->getBody()->write(json_encode(['erreur' => "Aucune absence trouvée"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    else{
        $response->getBody()->write(json_encode(['valid' => "Récupération des absences réussie", 'data' => $absences]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }


})->add($auth)->add($check_role([1,3,4,5]));

//========================================================================================
// But : Récupérer les absences des étudiants d'un formateur
// Rôles : Formateurs
// Champs :aucun
//========================================================================================

$app->get('/api/absences/getAllFormateur', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    if($role !== 4 ) {
        $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits nécéssaires"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }

    $absence = new Absence();
    $formateurParticipeSession = new FormateursParticipantSession();
    $formateurParticipeSession->id_formateurs = $token['idByRole'];
    try {
        $sessions = $formateurParticipeSession->getSessionsByFormateur();
    } catch (PDOException $e) {
        $errorMessage = $e->getMessage();
        $response->getBody()->write(json_encode(['erreur' => $errorMessage]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }

    if(!$sessions) {
        $response->getBody()->write(json_encode(['erreur' => "Aucune session trouvée pour ce formateur"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $absences = [];

    foreach ($sessions as $session) {
        $etudiant = new Etudiant();
        $etudiant->id_session = $session['id'];
        $absences[$session['nom']] = $etudiant->getAbsencesStudents();
    }
    

    $response->getBody()->write(json_encode(['valid' => "Récupération des absences réussie", 'data' => $absences]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

    
})->add($auth);

//========================================================================================
// But : Récupérer les absences des étudiants d'un centre de formation
// Rôles : gestionnaires de centre
// Champs : dateDebut et dateFin
//========================================================================================

$app->get('/api/absences/getAllGestionnaireCentre', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    if($role !== 3 ) {
        $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits nécéssaires"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
    $absence = new Absence();
    $gestionnaire = new GestionnaireCentre();
    $gestionnaire->id_users = $token['id'];

    $gestionnaire->id_centres_de_formation = $gestionnaire->searchCentreForIdUsers();
    $absences = $gestionnaire->getAbsencesByIdGestionnaire();

    if(!$absences) {
        $response->getBody()->write(json_encode(['valid-null' => "Aucune absence trouvée pour ce centre"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    $response->getBody()->write(json_encode(['valid' => "Récupération des absences réussie", 'data' => $absences]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

    
})->add($auth);


//========================================================================================
// But : Récupérer les absences des étudiants d'une entreprise
// Rôles : gestionnaires entreprise
// Champs : dateDebut et dateFin
//========================================================================================

$app->get('/api/absences/getAllGestionnaireEntreprise', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    if($role !== 2 ) {
        $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits nécéssaires"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
    $absence = new Absence();
    $gestionnaire = new GestionnaireEntreprise();
    $gestionnaire->id_users = $token['id'];

    $gestionnaire->id_entreprises = $gestionnaire->getIdEntrepriseByUserId();

    $absences = $gestionnaire->getAbsencesByIdEntreprise();

    if(!$absences) {
        $response->getBody()->write(json_encode(['valid-null' => "Aucune absence trouvée pour cette entreprise"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    $response->getBody()->write(json_encode(['valid' => "Récupération des absences réussie", 'data' => $absences]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

    
})->add($auth)->add($checkGestionnaireEntreprise);


//========================================================================================
// But : Récupérer les absences des étudiants 
// Rôles : Administrateurs, Gestionnaire de centre, gestionnaire d'entreprise, étudiants, financeurs
// Champs : dateDebut et dateFin
//========================================================================================

$app->get('/api/absences/liste', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    $absence = new Absence();


    // Administrateurs
    if($role == 1){
        $admin = new Admin();
        $absences = $admin->getAbsenceList();
    }

    // Gestionnaire Entreprise
    if($role == 2) {
        $gestionnaire = new GestionnaireEntreprise();
        $gestionnaire->id_users = $token['id'];

        $gestionnaire->id_entreprises = $gestionnaire->getIdEntrepriseByUserId();

        $absences = $gestionnaire->getAbsenceList();
    }

    // Gestionnaire Centre
    if($role == 3) {
        $gestionnaire = new GestionnaireCentre();
        $gestionnaire->id_users = $token['id'];

        $gestionnaire->id_centres_de_formation = $gestionnaire->searchCentreForIdUsers();
        $absences = $gestionnaire->getAbsenceList();
    }

    // Formateurs
    if($role == 4){
        $formateur = new Formateur();
        $formateur->id = $token['idByRole'];

        $absences = $formateur->getAbsenceList();
    }

    // Etudiants
    if($role == 5){
        $etudiant = new Etudiant();
        $etudiant->id_users = $token['id'];

        $absences = $etudiant->getAbsenceList();
    }

     // Financeurs (récupère absences de tous les stagiaires suivis par sa boite)
     if($role == 6){
        $financeur = new Financeur();
        $financeur->id_users = $token['id'];

        $financeur->id_entreprises = $financeur->getIdEntrepriseByUserId();
        $absences = $financeur->getAbsenceList();
    }

    if(!$absences) {
        $response->getBody()->write(json_encode(['erreur' => "Aucune absence trouvée pour ce centre"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $response->getBody()->write(json_encode(['valid' => "Récupération des absences réussie", 'data' => $absences]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

    
})->add($auth);


//========================================================================================
// But : Récupérer les informations d'une absence
// Rôles : Tous
// Champs : id_absences (param)
//========================================================================================

$app->get('/api/absences/details/{id_absences}', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);


    // Vérification si id_absences est un nombre
    if(!is_numeric($param['id_absences'])){
        $response->getBody()->write(json_encode(['erreur' => "Format de l'ID invalide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $absence = new Absence();
    $absence->id = intval($param['id_absences']);
    
    $absenceInfo = $absence->getOneAbsenceInfo();

    if(empty($absenceInfo)){
        $response->getBody()->write(json_encode(['erreur' => "Aucune absence trouvée"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    
    // Gestionnaire Entreprise
    // > Vérification : Si propriétaire absence est aussi dans l'entreprise
    if($role == 2) {
        $gestionnaire = new GestionnaireEntreprise();
        $gestionnaire->id_users = $token['id'];

        $gestionnaire_idEntreprises = $gestionnaire->searchIdEntrepriseByUserId();

        if ($gestionnaire_idEntreprises != $absenceInfo['etudiants_idEntreprises']){
            $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas accès à cette ressource"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // Gestionnaire Centre
    // Vérification : Si Centre du gestionnaire est différent de celui du propriétaire de l'absence
    if($role == 3){
        $gestionnaire = new GestionnaireCentre();
        $gestionnaire->id_users = $token['id'];

        $gestionnaire_idCentre = $gestionnaire->searchCentreForIdUsers();

        if ($absenceInfo['session_idCentre'] != $gestionnaire_idCentre){
            $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas accès à cette ressource"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // Formateurs
    // Vérification : Si formateur participe à la même session que le propriétaire de l'absence
    if($role == 4){
        $formateur = new FormateursParticipantSession();
        $formateur->id_formateurs = $token['idByRole']->id;
        $formateur->id = $absenceInfo['session_id'];

        $boolParticipant = $formateur->boolFormateurParticipant();

        if (!$boolParticipant){
            $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas accès à cette ressource"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // Etudiants 
    // > Vérification : id_etudiants de l'absence récupérée
    if($role == 5){
        if($absenceInfo['id_etudiants'] != $token['idByRole']->id){
            $response->getBody()->write(json_encode(['erreur' => "Vous n'êtes pas le propriétaire de cette absence"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);   
        }
    }

     // Financeurs 
     // > Vérification : Si propriétaire absence est suivi par l'entreprise financeur
     if($role == 6){
        $financeur = new Financeur();
        $financeur->id_users = $token['id'];

        $financeur_idEntreprises = $financeur->getIdEntrepriseByUserId();

        if ($financeur_idEntreprises != $absenceInfo['financeur_idEntreprises']){
            $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas accès à cette ressource"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    $response->getBody()->write(json_encode(['valid' => "Récupération des informations de l'absence réussie", 'data' => $absenceInfo]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
})->add($auth);


//========================================================================================
// But : Récupérer les absences des étudiants d'un centre donné
// Rôles : Tous sauf étudiant
// Champs : byCentreId (seulement utile pour admin)
//========================================================================================

$app->get('/api/absences/centre/{byCentreId}', function (Request $request, Response $response, $param) use ($key) {
    $byCentreId = "all";
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    $absence = new Absence();

    // Administrateurs
    if($role == 1){
       $etudiant = new Etudiant();
        if(!empty($param['byCentreId']) && filter_var($param['byCentreId'], FILTER_VALIDATE_INT)){
            $centreFormation = new CentreFormation();
            $centreFormation->id = $param['byCentreId'];
            $centreFormationExist = $centreFormation->boolId();
            if($centreFormationExist == false){
                $response->getBody()->write(json_encode(['erreur' => "Le centre de formation sélectionné n'existe pas"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
            
            $byCentreId = $param['byCentreId'];
        }
        $etudiant->id_centres_de_formation = $byCentreId;

        $absences = $etudiant->getAbsenceListByCentre();
    }

    // Gestionnaire Entreprise
    if($role == 2) {
        $gestionnaire = new GestionnaireEntreprise();
        $gestionnaire->id_users = $token['id'];

        $gestionnaire->id_entreprises = $gestionnaire->getIdEntrepriseByUserId();

        $absences = $gestionnaire->getAbsenceList();
    }

    // Gestionnaire Centre
    if($role == 3) {
        $gestionnaire = new GestionnaireCentre();
        $gestionnaire->id_users = $token['id'];

        $gestionnaire->id_centres_de_formation = $gestionnaire->searchCentreForIdUsers();
        $absences = $gestionnaire->getAbsenceList();
    }

    // Formateurs
    if($role == 4){
        $formateur = new Formateur();
        $formateur->id = $token['idByRole'];

        $absences = $formateur->getAbsenceList();
    }

    // Etudiants
    if($role == 5){
        $etudiant = new Etudiant();
        $etudiant->id_users = $token['id'];

        $absences = $etudiant->getAbsenceList();
    }

     // Financeurs (récupère absences de tous les stagiaires suivis par sa boite)
     if($role == 6){
        $financeur = new Financeur();
        $financeur->id_users = $token['id'];

        $financeur->id_entreprises = $financeur->getIdEntrepriseByUserId();
        $absences = $financeur->getAbsenceList();
    }

    if(empty($absences)) {
        $response->getBody()->write(json_encode(['erreur' => "Aucune absence trouvée pour ce centre"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $response->getBody()->write(json_encode(['valid' => "Récupération des absences réussie", 'data' => $absences]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

    
})->add($auth);

$app->get('/api/sessions/absences/{centreId}', function (Request $request, Response $response, $param) use ($key) {
    $centre = $param['centreId'];

    $session = new Session();
    $session->id_centres_de_formation = $centre;
    $sessions = $session->getSessionsEnCoursByCentre();
    if(!$sessions) {
        $response->getBody()->write(json_encode(['erreur' => "Aucune session trouvée pour ce centre"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $absence = new Absence();

    $absences = [];
    foreach ($sessions as $session) {
        $sessionAbsences = $absence->getAbsencesForSession($session['id']);
        $absences[$session['nomSession']] = array_map(function($absence) {
            return (array)$absence;
        }, $sessionAbsences);
    }

    $result = [
        'sessions' => $sessions,
        'absences' => $absences
    ];


    $response->getBody()->write(json_encode(['valid' => "Récupération des sessions en cours réussie", 'data' => $result]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
})->add($auth);