<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ .'/../../models/UserModel.php';

require_once __DIR__ .'/../../models/EtudiantModel.php';
require_once __DIR__."/../../models/CentreFormationModel.php";
require_once __DIR__."/../../models/EntrepriseModel.php";
require_once __DIR__."/../../models/FinanceurModel.php";
require_once __DIR__."/../../models/SessionModel.php";

//========================================================================================
// But : Permet de modifier les infos d'un étudiant
// Rôles : admins
// Champs possibles : newEmail, newPassword | newFirstname, newLastname, newAdressePostale, newCodePostal, newVille, newDateNaissance, newCentre, newEntreprise, newFinanceur, newSession
//========================================================================================
$app->put('/api/admin/etudiant/{user_id}/update', function (Request $request, Response $response, $param) use ($key) {  
    $user = new User();
    $etudiant = new Etudiant();
    // Check => Format param
    if(empty($param['user_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ user_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $user->id = $param['user_id'];
    $etudiant->id_users = $user->id;

    // Si l'utilisateur existe
    if(!($user->boolId())){
        $response->getBody()->write(json_encode(['erreur' => "L'utilisateur n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $data = $request->getParsedBody();
    $result = $user->searchForId();

    $user->role = $result['id_role'];


    // Reconnaissance si changement effectué
    $changedEmail = 0;
    $changedFirstname = 0;
    $changedLastname = 0;
    $changedAdresse = 0;
    $changedCodePostal = 0;
    $changedVille = 0;
    $changedDateNaissance = 0;

    $changedEntreprise = 0;
    $changedCentreFormation = 0;
    $changedFinanceur = 0;
    $changedSession = 0;

    $og_user = $user->searchForId();
    $og_role = $etudiant->searchForId();

    $og_email = $og_user['email'];

    $og_firstname = $og_role['firstname'];
    $og_lastname = $og_role['lastname'];
    $og_adressePostale = $og_role['adressePostale'];
    $og_codePostal = $og_role['codePostal'];
    $og_ville = $og_role['ville'];
    $og_dateNaissance = $og_role['date_naissance'];

    $og_entreprise = $og_role['id_entreprises'];
    $og_centre = $og_role['id_centres_de_formation'];
    $og_financeur = $og_role['id_conseillers_financeurs'];
    $og_session = $og_role['id_session'];

    if(isset($data['newEmail']) && !empty($data['newEmail']) && ($data['newEmail'] != $og_email)) {
        $user->email = $data['newEmail'];
        $emailExist = $user->boolEmail();
        if(!$emailExist){
            $changedEmail = 1;
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Cette adresse mail est déjà utilisée"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
    if(isset($data['newFirstname']) && !empty($data['newFirstname'])  && ($data['newFirstname'] != $og_firstname)) {
        $changedFirstname = 1;
        $etudiant->firstname = $data['newFirstname'];
    }
    if(isset($data['newLastname']) && !empty($data['newLastname'])  && ($data['newLastname'] != $og_lastname)) {
        $changedLastname = 1;
        $etudiant->lastname = $data['newLastname'];
    }
    if(isset($data['newAdressePostale']) && !empty($data['newAdressePostale'])  && ($data['newAdressePostale'] != $og_adressePostale)) {
        $changedAdresse = 1;
        $etudiant->adressePostale = $data['newAdressePostale'];
    }
    if(isset($data['newCodePostal']) && !empty($data['newCodePostal'])  && ($data['newCodePostal'] != $og_codePostal)) {
        $changedCodePostal = 1;
        $etudiant->codePostal = $data['newCodePostal'];
    }
    if(isset($data['newVille']) && !empty($data['newVille'])  && ($data['newVille'] != $og_ville)) {
        $changedVille = 1;
        $etudiant->ville = $data['newVille'];
    }

    if(isset($data['newDateNaissance']) && !empty($data['newDateNaissance'])  && ($data['newDateNaissance'] != $og_dateNaissance)) {
        $changedDateNaissance = 1;
        $etudiant->date_naissance = $data['newDateNaissance'];
    }

    if(isset($data['newEntreprise']) && !empty($data['newEntreprise']) && ($data['newEntreprise'] != $og_entreprise) && $data['newEntreprise'] != "") {
        $entreprise = new Entreprise();
        $entreprise->id = $data['newEntreprise'];
        $entrepriseExist = $entreprise->boolId();
        if($entrepriseExist){
            $changedEntreprise = 1;
            $etudiant->id_entreprises = $data['newEntreprise'];
        } else {
            $response->getBody()->write(json_encode(['erreur' => "L'entreprise sélectionnée n'existe pas"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
    if(isset($data['newCentre']) && !empty($data['newCentre']) && ($data['newCentre'] != $og_centre) && $data['newCentre'] != "")  {
        $centreFormation = new CentreFormation();
        $centreFormation->id = $data['newCentre'];
        $centreFormationExist = $centreFormation->boolId();
        if($centreFormationExist){
            $changedCentreFormation = 1;
            $etudiant->id_centres_de_formation = $data['newCentre'];
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Le centre de formation sélectionné n'existe pas"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
    if(isset($data['newFinanceur']) && !empty($data['newFinanceur']) && ($data['newFinanceur'] != $og_financeur) && $data['newFinanceur'] != "")  {
        $financeur = new Financeur();
        
        $financeur->id = $data['newFinanceur'];
        $financeurExist = $financeur->checkExist();
        if($financeurExist){
            $changedFinanceur = 1;
            $etudiant->id_conseillers_financeurs = $data['newFinanceur'];
            
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Le conseiller financeur sélectionné n'existe pas"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
    if(isset($data['newSession']) && !empty($data['newSession']) && ($data['newSession'] != $og_session) && $data['newSession'] != "")  {
        $session = new Session();
        $session->id = $data['newSession'];
        $sessionExist = $session->boolId();
        if($sessionExist){
            $changedSession = 1;
            $etudiant->id_session = $data['newSession'];
        } else {
            $response->getBody()->write(json_encode(['erreur' => "La session sélectionnée n'existe pas dans la base de données"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
    // Si aucun changement >> erreur
    if($changedEmail == 0 &&
    $changedFirstname == 0 &&
    $changedLastname == 0 &&
    $changedAdresse == 0 &&
    $changedCodePostal == 0 &&
    $changedVille == 0 &&
    $changedDateNaissance == 0 &&
    $changedEntreprise == 0 && 
    $changedCentreFormation == 0 &&
    $changedFinanceur == 0 &&
    $changedSession == 0){
        $response->getBody()->write(json_encode(['erreur' => "Veuillez modifier au moins une donnée"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $changedEmail && $user->updateEmail();
    $changedFirstname && $etudiant->updateFirstname();
    $changedLastname && $etudiant->updateLastname();
    $changedAdresse && $etudiant->updateAdressePostale();
    $changedCodePostal && $etudiant->updateCodePostal();
    $changedVille && $etudiant->updateVille();
    $changedDateNaissance && $etudiant->updateDateNaissance();
    $changedEntreprise && $etudiant->updateEntreprise();
    $changedCentreFormation && $etudiant->updateCentreFormation();
    $changedFinanceur && $etudiant->updateFinanceur();
    $changedSession && $etudiant->updateSession();


    $response->getBody()->write(json_encode(['valid' => "L'étudiant a été mis à jour"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth)->add($checkAdmin);