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

//========================================================================================
// But : Mise à jour d'une absence donnée
// Rôles : Admins, GestionnaireCentre, Formateurs
// Champs : id_absences, dateDebut, dateFin, raison, justificatif
// Requête POST à cause de Slim (technologie utilisée)< pour API
// - Parce qu'on peut envoyer un fichier en justificatif, il faut passer par multipart
// - sauf que Slim ne prend pas le multipart quand en PUT
//========================================================================================
$app->post('/api/absences/{absences_id}/update', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    $data = $request->getParsedBody();

    $uploadedFiles = $request->getUploadedFiles();

    // Check => Format du param : absences_id
    if(empty($param['absences_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ absences_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['absences_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check => Absence existe
    $absence = new Absence();
    $absence->id = $param['absences_id'];
    $absenceExist = $absence->boolId();
    if(!$absenceExist){
        $response->getBody()->write(json_encode(['erreur' => "L'absence sélectionnée n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Comparaison => id_centres absences vs id_centres (gestionnaireCentre, Formateur)
    $absence_id_centre = $absence->searchCentreForId();
    
    if (in_array($role, [3, 4])) {
        if($role == 3){
            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_users = $token['id'];
            $user_centre_id = $gestionnaireCentre->searchCentreForIdUsers();
        }
    
        if($role == 4){
            $formateur = new Formateur();
            $formateur->id_users = $token['id'];
            $user_centre_id = $formateur->searchCentreForIdUsers();
        }

        if($absence_id_centre != $user_centre_id){
            $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas accès à cette absence"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400); 
        }    
    } 
    
    // Récupération => Informations de l'absence
    $ogInfos = $absence->searchForId();

    $og_dateDebut = $ogInfos['dateDebut'];
    $og_dateFin = $ogInfos['dateFin'];
    $og_raison = $ogInfos['raison'];

    // Initialisation => Capteurs changement
    $changed_dateDebut = 0;
    $changed_dateFin = 0;
    $changed_raison = 0;
    $changed_justificatif = 0;

    // Check => Intégrité du formulaire
    if(!isset($data['dateDebut'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Date de début doit figurer dans ce formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['dateFin'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Date de fin doit figurer dans ce formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['raison'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Raison de l'absence doit figurer dans ce formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check => Si champ rempli et si les données entrées sont différentes de celles en BDD
    // -- Champ => newDateDebut
    if(!empty($data['dateDebut'])){
        if($data['dateDebut'] != $og_dateDebut){
            $changed_dateDebut = 1;
            $og_dateDebut = $data['dateDebut'];
        }
    }

    // -- Champ => newDateFin
    if($data['dateFin'] != $og_dateFin){
        if($data['dateFin'] == ""){
            if($og_dateFin != NULL){
                $changed_dateFin = 1;
                $og_dateFin = NULL;
            }
        } else {
            $changed_dateFin = 1;
            $og_dateFin= $data['dateFin'];
        }
    }

    if($changed_dateDebut || $changed_dateFin){
        if($og_dateDebut <= $og_dateFin && $og_dateFin != NULL){
            $absence->dateDebut = $og_dateDebut;
            $absence->dateFin = $og_dateFin;
        } else if($og_dateFin == NULL) {
            $absence->dateDebut = $og_dateDebut;
            $absence->dateFin = $og_dateFin;
        } else {
            $response->getBody()->write(json_encode(['erreur' => "La date de début doit être avant la date de fin"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // -- Champ => newRaison
    if(!empty($data['raison'])){
        if($data['raison'] != $og_raison){
            $changed_raison = 1;
            $absence->raison = $data['raison'];
        }
    }

     // -- Champ => newJustificatif
     $justificatifFile = $uploadedFiles['justificatif'] ?? null;
    if ($justificatifFile) {
        $justificatifContent = $justificatifFile->getStream()->getContents();
        
            if ($justificatifContent === false) {
                $response->getBody()->write(json_encode(['erreur' => 'Erreur lors de la lecture du fichier téléchargé.']));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }
            
        
            $originalFilename = $justificatifFile->getClientFilename();
            
        
            $extension = pathinfo($originalFilename, PATHINFO_EXTENSION);
            
        
            $filename = sprintf('%s.%s', uniqid(), $extension);
            
        
            $directory = __DIR__ . '/../../documents/absences';
            $targetPath = $directory . DIRECTORY_SEPARATOR . $filename;
            
        
            $justificatifFile->moveTo($targetPath);

        
            if (!file_exists($targetPath)) {
                $response->getBody()->write(json_encode(['erreur' => 'Erreur lors du déplacement du fichier.']));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }

            $justificatifPath = 'documents/absences/' . $filename;

            $absence->justificatif = $justificatifPath;
            $changed_justificatif = 1;
    } 
    
    // Check => Si pas de changement >>> Erreur
    if($changed_dateDebut == 0 &&
    $changed_dateFin == 0 &&
    $changed_raison == 0 &&
    $changed_justificatif == 0){
        $response->getBody()->write(json_encode(['erreur' => "Il faut modifier au moins une donnée"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Si nouvelle période => Check : Si overlap
    if($changed_dateDebut || $changed_dateFin){
        $overlap = $absence->overlapAbsenceForUpdate();
        if(!empty($overlap)){
            $response->getBody()->write(json_encode(['erreur' => "L'absence en chevauche une déjà existante"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // Si il y a eu un changement >>> update
    $changed_dateDebut && $absence->updateDateDebut();
    $changed_dateFin && $absence->updateDateFin();
    $changed_raison && $absence->updateRaison();
    $changed_justificatif && $absence->updateJustificatif();

    // Succès
    $response->getBody()->write(json_encode(['valid' => "L'absence a bien été mise à jour"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);    
})->add($auth)->add($checkAdminEquipePedagogique);