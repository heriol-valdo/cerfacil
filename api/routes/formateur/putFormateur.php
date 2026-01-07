<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ .'/../../models/UserModel.php';

require_once __DIR__ .'/../../models/FormateurModel.php';
require_once __DIR__."/../../models/CentreFormationModel.php";

//========================================================================================
// But : Permet de modifier les infos d'un formateur
// Rôles : admin, gestionnaireCentre, formateur
// Champs possibles : newEmail, newPassword | newFirstname, newLastname,newAdressePostale, newCentre, newCodePostal, newVille, newSiret, newTelephone
//========================================================================================

$app->put('/api/formateur/{user_id}/update', function (Request $request, Response $response, $param) use ($key) {  
    $token=$request->getAttribute('user');
    $role=intval($token['role']);
    $data = $request->getParsedBody();

    // Check => Format param
    if(empty($param['user_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ user_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['user_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $user = new User();
    $formateur = new Formateur();
    $user->id = $param['user_id'];
    $formateur->id_users = $user->id;

    // Si l'utilisateur existe
    if(!($user->boolId())){
        $response->getBody()->write(json_encode(['erreur' => "L'utilisateur n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check => Intégrité du formulaire
    if(!isset($data['newEmail'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Email doit figurer dans le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['newFirstname'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Prénom doit figurer dans le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['newLastname'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nom doit figurer dans le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['newAdressePostale'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Adresse postale doit figurer dans le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['newCodePostal'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Code Postal doit figurer dans le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['newSiret'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Siret doit figurer dans le formulaire"]));
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
    $changedTelephone = 0;
    $changedSiret = 0;
    $changedCentreFormation = 0;

    $og_user = $user->searchForId();
    $og_role = $formateur->searchForId();

    $og_email = $og_user['email'];
    $og_firstname = $og_role['firstname'];
    $og_lastname = $og_role['lastname'];
    $og_adressePostale = $og_role['adressePostale'];
    $og_codePostal = $og_role['codePostal'];
    $og_ville = $og_role['ville'];
    $og_telephone = $og_role['telephone'];
    $og_centre = $og_role['id_centres_de_formation'];
    $og_siret = $og_role['siret'];

    if(!empty($data['newEmail']) && ($data['newEmail'] != $og_email)) {
        $user->email = $data['newEmail'];
        $emailExist = $user->boolEmail();
        if(!$emailExist){
            $changedEmail = 1;
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Cette adresse mail est déjà utilisée"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
    if(!empty($data['newFirstname'])  && ($data['newFirstname'] != $og_firstname)) {
        $changedFirstname = 1;
        $formateur->firstname = $data['newFirstname'];
    }
    if(!empty($data['newLastname'])  && ($data['newLastname'] != $og_lastname)) {
        $changedLastname = 1;
        $formateur->lastname = $data['newLastname'];
    }
    if(!empty($data['newAdressePostale'])  && ($data['newAdressePostale'] != $og_adressePostale)) {
        $changedAdresse = 1;
        $formateur->adressePostale = $data['newAdressePostale'];
    }
    if(!empty($data['newCodePostal'])  && ($data['newCodePostal'] != $og_codePostal)) {
        $changedCodePostal = 1;
        $formateur->codePostal = $data['newCodePostal'];
    }
    if(!empty($data['newVille'])  && ($data['newVille'] != $og_ville)) {
        $changedVille = 1;
        $formateur->ville = $data['newVille'];
    }

    if(isset($data['newTelephone']) && !empty($data['newTelephone'])  && ($data['newTelephone'] != $og_telephone)) {
        $changedTelephone = 1;
        $formateur->telephone = $data['newTelephone'];
    }
    
    if(!empty($data['newSiret'])  && ($data['newSiret'] != $og_siret)) {
        $changedSiret = 1;
        $formateur->siret = $data['newSiret'];
    }

    // Si Admin / Si gestionnaireCentre ou Formateur => Aucun changement
    if($role == 1){
        if(!isset($data['newCentre'])){
            $response->getBody()->write(json_encode(['erreur' => "Le champ Centre de formation doit figurer dans le formulaire"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        if(!empty($data['newCentre']) && ($data['newCentre'] != $og_centre)) {
            $centreFormation = new CentreFormation();
            $centreFormation->id = $data['newCentre'];
            $centreFormationExist = $centreFormation->boolId();
            
            if($centreFormationExist){
                $changedCentreFormation = 1;
                $formateur->id_centres_de_formation = $data['newCentre'];
            } else {
                $response->getBody()->write(json_encode(['erreur' => "Le centre de formation sélectionné n'existe pas"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }
    }


    // Si aucun changement >> erreur
    if($changedEmail == 0 &&
    $changedFirstname == 0 &&
    $changedLastname == 0 &&
    $changedAdresse == 0 &&
    $changedCodePostal == 0 &&
    $changedVille == 0 &&
    $changedTelephone == 0 &&
    $changedCentreFormation == 0 && 
    $changedSiret == 0){
        $response->getBody()->write(json_encode(['erreur' => "Veuillez modifier au moins une donnée"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $changedEmail && $user->updateEmail();

    $changedFirstname && $formateur->updateFirstname();
    $changedLastname && $formateur->updateLastname();
    $changedAdresse && $formateur->updateAdressePostale();
    $changedCodePostal && $formateur->updateCodePostal();
    $changedVille && $formateur->updateVille();
    $changedTelephone && $formateur->updateTelephone();
    $changedCentreFormation && $formateur->updateCentreFormation();
    $changedSiret && $formateur->updateSiret();

    $response->getBody()->write(json_encode(['valid' => "Le formateur a été mis à jour"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth)->add($checkAdminEquipePedagogique);