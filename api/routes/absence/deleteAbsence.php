<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/AbsenceModel.php';
require_once __DIR__.'/../../models/EtudiantModel.php';
require_once __DIR__.'/../../models/FormateurModel.php';
require_once __DIR__.'/../../models/GestionnaireEntrepriseModel.php';
require_once __DIR__.'/../../models/FormateursParticipantSessionModel.php';
require_once __DIR__.'/../../models/GestionnaireCentreModel.php';


//========================================================================================
// But : Supprimer une absence {absence_id}
// Rôles : Admins, GestionnaireCentre, Formateurs
// Champs : {absence_id}
//========================================================================================
$app->delete('/api/absences/{absence_id}/delete', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    $absence = new Absence();
    $absence->id = $param['absence_id'];

    // Check => Absence existe
    $absenceExist = $absence->boolId();
    if(!$absenceExist){
        $response->getBody()->write(json_encode(['erreur' => "L'absence sélectionnée n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $absenceDatas= $absence->getAbsenceById();
    $absenceIdEtudiant = $absenceDatas['id_etudiants'];

    // Vérification appartenance absence => id_centres
    $absence_id_centre = $absence->searchCentreForId();
    // GestionnaireCentre et Formateur
    if (in_array($role, [3, 4])) {
        $gestionnaireCentre = new GestionnaireCentre();
        $formateur = new Formateur();

        $roleObjects = [
            3 => $gestionnaireCentre,
            4 => $formateur,
        ];
    
        $roleObjects[$role]->id_users = $token['idByRole'];
        $role_id_centre = $roleObjects[$role]->searchCentreForIdUsers();
    }

    $etudiant = new Etudiant();
    $etudiant->id = $absenceIdEtudiant;
    $etudiantDatas = $etudiant->getEtudiantDatasById();

    if($role === 3) {
        $etudiantCentreId = $etudiantDatas['id_centres_de_formation'];

        $gestionnaire = new GestionnaireCentre();
        $gestionnaire->id = $token['idByRole']; 
        $gestionnaireDatas = $gestionnaire->getGestionnaireDatasById();
        $gestionnaireCentreId = $gestionnaireDatas['id_centres_de_formation'];

        if($etudiantCentreId != $gestionnaireCentreId) {
            $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits nécéssaires"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);

        }
    }

    if($role === 4) {
        $etudiantSessionId = $etudiantDatas['id_session'];
        $formateurparticipantSession = new FormateursParticipantSession();
        $formateurparticipantSession->id = $etudiantSessionId;
        $formateurparticipantSession->id_formateurs= $token['idByRole'];
        $exist = $formateurparticipantSession->checkIfFormateurParticipantSessionExist();

        if(!$exist) {
            $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits nécéssaires"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

    }

    // Succès => Suppression absence
    $absence->deleteAbsence();
    $response->getBody()->write(json_encode(['valid' => "L'absence a bien été supprimée"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);    
})->add($auth)->add($checkAdminEquipePedagogique);

