<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ .'/../../models/UserModel.php';
require_once __DIR__ .'/../../models/GestionnaireEntrepriseModel.php';
require_once __DIR__ .'/../../models/EntrepriseModel.php';

//========================================================================================
// But : Mettre à jour un gestionnaire d'entreprise
// Rôles : admin, gestionnaire de centre
// Champs possibles : newEmail, newPassword | newFirstname, newLastname, newEntreprise, newTelephone, newLieuTravail
//========================================================================================

$app->put('/api/gestionnaireEntreprise/{user_id}/update', function (Request $request, Response $response, $param) use ($key) {  
    $user = new User();
    $gestionnaireEntreprise = new GestionnaireEntreprise();

    // Check => Format param
    if(empty($param['user_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ user_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['user_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $user->id = $param['user_id'];
    $gestionnaireEntreprise->id_users = $user->id;

    // Si l'utilisateur existe
    if(!($user->boolId())){
        $response->getBody()->write(json_encode(['erreur' => "L'utilisateur n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $data = $request->getParsedBody();
    $result = $user->searchForId();

    $user->role = $result['id_role'];
    // Si le rôle est bien le bon
    if($result['id_role'] != 2){
        $response->getBody()->write(json_encode(['erreur' => "L'utilisateur n'est pas un gestionnaire d'entreprise"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Reconnaissance si changement effectué
    $changedEmail = 0;

    $changedFirstname = 0;
    $changedLastname = 0;
    $changedTelephone = 0;
    $changedLieuTravail = 0;
    $changedEntreprise = 0;

    $og_user = $user->searchForId();
    $og_role = $gestionnaireEntreprise->searchForId();

    $og_email = $og_user['email'];

    $og_firstname = $og_role['firstname'];
    $og_lastname = $og_role['lastname'];
    $og_telephone = $og_role['telephone'];
    $og_lieuTravail = $og_role['lieu_travail'];
    $og_entreprise = $og_role['id_entreprises'];

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
    if(isset($data['newFirstname']) && !empty($data['newFirstname']) && ($data['newFirstname'] != $og_firstname)) {
        $changedFirstname = 1;
        $gestionnaireEntreprise->firstname = $data['newFirstname'];
    }
    if(isset($data['newLastname']) && !empty($data['newLastname']) && ($data['newLastname'] != $og_lastname)) {
        $changedLastname = 1;
        $gestionnaireEntreprise->lastname = $data['newLastname'];
    }
    if(isset($data['newTelephone']) && !empty($data['newTelephone']) && ($data['newTelephone'] != $og_telephone)) {
        $changedTelephone = 1;
        $gestionnaireEntreprise->telephone = $data['newTelephone'];
    }
    if(isset($data['newLieuTravail']) && !empty($data['newLieuTravail']) && ($data['newLieuTravail'] != $og_lieuTravail)) {
        $changedLieuTravail = 1;
        $gestionnaireEntreprise->lieu_travail = $data['newLieuTravail'];
    }
    if(isset($data['newEntreprise']) && !empty($data['newEntreprise']) && ($data['newEntreprise'] != $og_entreprise)) {
        require_once(__DIR__."/../../models/EntrepriseModel.php");
        $entreprise = new Entreprise();
        $entreprise->id = $data['newEntreprise'];
        $entrepriseExist = $entreprise->boolId();
        if($entrepriseExist){
            $changedEntreprise = 1;
            $gestionnaireEntreprise->id_entreprises = $data['newEntreprise'];
        } else {
            $response->getBody()->write(json_encode(['erreur' => "L'entreprise sélectionnée n'existe pas'"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // Si aucun changement >> erreur
    if($changedEmail == 0 &&
    $changedFirstname == 0 &&
    $changedLastname == 0 &&
    $changedTelephone == 0 &&
    $changedLieuTravail == 0 &&
    $changedEntreprise == 0){
        $response->getBody()->write(json_encode(['erreur' => "Veuillez modifier au moins une donnée"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $changedEmail && $user->updateEmail();

    $changedFirstname && $gestionnaireEntreprise->updateFirstname();
    $changedLastname && $gestionnaireEntreprise->updateLastname();
    $changedTelephone && $gestionnaireEntreprise->updateTelephone();
    $changedLieuTravail && $gestionnaireEntreprise->updateLieuTravail();
    $changedEntreprise && $gestionnaireEntreprise->updateEntreprise();

    $response->getBody()->write(json_encode(['valid' => "Le gestionnaire de centre a été mis à jour"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth)->add($checkAdmin);