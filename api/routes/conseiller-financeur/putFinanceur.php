<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ .'/../../models/UserModel.php';
require_once __DIR__ .'/../../models/FinanceurModel.php';
require_once __DIR__ .'/../../models/EntrepriseModel.php';

//========================================================================================
// But : Modifier les informations d'un conseiller financeur
// Rôles : admins
// Champs possibles : newEmail, newPassword, newFirstname, newLastname, newTypeFinanceur, newEntreprise
//========================================================================================

$app->put('/api/admin/updateFinanceur/{user_id}', function (Request $request, Response $response, $param) use ($key) {  
    $user = new User();
    $financeur = new Financeur();
    $user->id = $param['user_id'];
    $financeur->id_users = $user->id;

    // Check => Format param
    if(empty($param['user_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ user_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['user_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Si l'utilisateur existe
    if(!($user->boolId())){
        $response->getBody()->write(json_encode(['erreur' => "L'utilisateur n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $data = $request->getParsedBody();
    $result = $user->searchForId();

    $user->role = $result['id_role'];
    // Si le rôle est bien le bon
    if($result['id_role'] != 6){
        $response->getBody()->write(json_encode(['erreur' => "L'utilisateur n'est pas un conseiller financeur"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Reconnaissance si changement effectué
    $changedEmail = 0;

    $changedFirstname = 0;
    $changedLastname = 0;
    //$changedTelephone = 0;
    $changedTypeFinanceur = 0;
    $changedEntreprise = 0;

    $og_user = $user->searchForId();
    $og_role = $financeur->searchForId();

    $og_email = $og_user['email'];

    $og_firstname = $og_role['firstname'];
    $og_lastname = $og_role['lastname'];
    //$og_telephone = $og_role['telephone'];
    $og_typeFinanceur = $og_role['type_financeur'];
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
        $financeur->firstname = $data['newFirstname'];
    }
    if(isset($data['newLastname']) && !empty($data['newLastname']) && ($data['newLastname'] != $og_lastname)) {
        $changedLastname = 1;
        $financeur->lastname = $data['newLastname'];
    }
    /*if(isset($data['newTelephone']) && !empty($data['newTelephone']) && ($data['newTelephone'] != $og_telephone)) {
        $changedTelephone = 1;
        $gestionnaireEntreprise->telephone = $data['newTelephone'];
    }*/
    if(isset($data['newTypeFinanceur']) && !empty($data['newTypeFinanceur']) && ($data['newTypeFinanceur'] != $og_typeFinanceur)) {
        $changedTypeFinanceur = 1;
        $financeur->type_financeur = $data['newTypeFinanceur'];
    }
    if(isset($data['newEntreprise']) && !empty($data['newEntreprise']) && ($data['newEntreprise'] != $og_entreprise)) {
        require_once(__DIR__."/../../models/EntrepriseModel.php");
        $entreprise = new Entreprise();
        $entreprise->id = $data['newEntreprise'];
        $entrepriseExist = $entreprise->boolId();
        if($entrepriseExist){
            $changedEntreprise = 1;
            $financeur->id_entreprises = $data['newEntreprise'];
        } else {
            $response->getBody()->write(json_encode(['erreur' => "L'entreprise sélectionnée n'existe pas'"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // Si aucun changement >> erreur / $changedTelephone == 0 &&
    if($changedEmail == 0 &&
    $changedFirstname == 0 &&
    $changedLastname == 0 &&
    $changedTypeFinanceur == 0 &&
    $changedEntreprise == 0){
        $response->getBody()->write(json_encode(['erreur' => "Veuillez modifier au moins une donnée"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $changedEmail && $user->updateEmail();

    $changedFirstname && $financeur->updateFirstname();
    $changedLastname && $financeur->updateLastname();
    //$changedTelephone && $gestionnaireEntreprise->updateTelephone();
    $changedTypeFinanceur && $financeur->updateTypeFinanceur();
    $changedEntreprise && $financeur->updateEntreprise();

    $response->getBody()->write(json_encode(['valid' => "Le conseiller financeur a été mis à jour"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth)->add($checkAdmin);