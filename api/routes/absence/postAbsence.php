<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/AbsenceModel.php';
require_once __DIR__.'/../../models/EtudiantModel.php';

//========================================================================================
// But : Permet d'ajouter une absence
// Rôles : Admins, GestionnaireCentre, Formateurs, GestionnaireEntreprise
// Champs : id_etudiants, dateDebut, dateFin, raison, justificatif
//========================================================================================
$app->post('/api/absences/add', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    $data = $request->getParsedBody();
    $uploadedFiles = $request->getUploadedFiles();

    // Check => Intégrité du formulaire
    // -- Champ => id_etudiant
    if(!isset($data['id_etudiants']) || !isset($data['id_etudiants'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Etudiant doit apparaître dans le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    // -- Champ => dateDebut, dateFin
    if(!isset($data['dateDebut']) || !isset($data['dateFin'])){
        $response->getBody()->write(json_encode(['erreur' => "Les champs date de début et date de fin doivent apparaître dans le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // -- Champ => raison
    if(!isset($data['raison'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Raison de l'absence doit apparaître dans le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $etudiant = new Etudiant();

    // Check => Etudiant existe
    // Champ => id_etudiant
    $etudiant->id = $data['id_etudiants'];
    $etudiantExist = $etudiant->boolIdRole();

    if(!$etudiantExist){
        $response->getBody()->write(json_encode(['erreur' => "L'étudiant sélectionné n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    $etudiantInfo = $etudiant->searchForIdEtudiant();

    // Check => User existe
    $etudiant->id_users = $etudiantInfo['id_users'];
    $userExist = $etudiant->boolId();
    if(!$userExist){
        $response->getBody()->write(json_encode(['erreur' => "L'utilisateur sélectionné n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $absence = new Absence();
    $absence->id_etudiants = $etudiant->id;

    

    // Remplissage des données en se basant sur le formulaire
    // -- Champs => dateDebut, dateFin
    if(!empty($data['dateDebut'])){
        if(empty($data['dateFin'])){
            $absence->dateDebut = $data['dateDebut'];
            $absence->dateFin = NULL;
        } else {
            $absenceDebut = new DateTime($data['dateDebut']);
            $absenceFin = new DateTime($data['dateFin']);
            if($absenceDebut <= $absenceFin){
                $absence->dateDebut = $data['dateDebut'];
                $absence->dateFin = $data['dateFin'];
            } else {
                $response->getBody()->write(json_encode(['erreur' => "La date de début ne peut pas être supérieure à la date de fin"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Veuillez remplir la date de début d'absence"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // --- Champ => raison
    if(!empty($data['raison'])){
        $absence->raison = $data['raison'];
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Veuillez remplir la raison de l'absence"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // --- Champ => Justificatif
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
                return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
            }

            $justificatifPath = 'documents/absences/' . $filename;

            $absence->justificatif = $justificatifPath;
    } else {
        $absence->justificatif = NULL;
    }
 

    // Check => Si dates de l'absence chevauche une autre
    $overlap = $absence->overlapAbsence();
    if(!empty($overlap)){
        $response->getBody()->write(json_encode(['erreur' => "L'absence en chevauche une déjà existante"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Succès
    $absence->addAbsence();
    $response->getBody()->write(json_encode(['valid' => "L'absence a bien été créée"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);    
})->add($auth)->add($checkNotEtudiantFinanceur);