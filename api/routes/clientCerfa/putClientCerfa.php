<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ .'/../../models/UserModel.php';

require_once __DIR__.'/../../models/ClientCerfaModel.php';

//========================================================================================
// But : Permet de modifier les infos d'un étudiant
// Rôles : admins
// Champs possibles : newEmail, newPassword | newFirstname, newLastname, newAdressePostale, newCodePostal, newVille, newDateNaissance, newCentre, newEntreprise, newFinanceur, newSession
//========================================================================================
$app->put('/api/admin/clientCerfa/{user_id}/update', function (Request $request, Response $response, $param) use ($key) {  
    $user = new User();
    $clientCerfa = new ClientCerfa();
    // Check => Format param
    if(empty($param['user_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ user_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $user->id = $param['user_id'];
    $clientCerfa->id_users = $user->id;

    // Si l'utilisateur existe
    if(!($user->boolId())){
        $response->getBody()->write(json_encode(['erreur' => "L'utilisateur n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
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

    

    $og_user = $user->searchForId();
    $og_role = $clientCerfa->searchForId();

    $og_email = $og_user['email'];

    $og_firstname = $og_role['firstname'];
    $og_lastname = $og_role['lastname'];
    $og_adressePostale = $og_role['adressePostale'];
    $og_codePostal = $og_role['codePostal'];
    $og_ville = $og_role['ville'];
    $og_telephone = $og_role['telephone'];

    

    if(isset($data['newEmail']) && !empty($data['newEmail']) && ($data['newEmail'] != $og_email)) {
        $user->email = $data['newEmail'];
        $emailExist = $user->boolEmail();
        if(!$emailExist){
            $changedEmail = 1;
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Cette adresse mail est déjà utilisée"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    }

    if(isset($data['newTelephone']) && !empty($data['newTelephone']) && ($data['newTelephone'] !=  $og_telephone)) {
        $clientCerfa->telephone= $data['newTelephone'];
        $telephoneExist = $clientCerfa->boolTelephone();
        if(!$telephoneExist){
            $changedTelephone = 1;
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Ce numero de telephone est déjà utilisée"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    }
    if(isset($data['newFirstname']) && !empty($data['newFirstname'])  && ($data['newFirstname'] != $og_firstname)) {
        $changedFirstname = 1;
        $clientCerfa->firstname = $data['newFirstname'];
    }
    if(isset($data['newLastname']) && !empty($data['newLastname'])  && ($data['newLastname'] != $og_lastname)) {
        $changedLastname = 1;
        $clientCerfa->lastname = $data['newLastname'];
    }
    if(isset($data['newAdressePostale']) && !empty($data['newAdressePostale'])  && ($data['newAdressePostale'] != $og_adressePostale)) {
        $changedAdresse = 1;
        $clientCerfa->adressePostale = $data['newAdressePostale'];
    }
    if(isset($data['newCodePostal']) && !empty($data['newCodePostal'])  && ($data['newCodePostal'] != $og_codePostal)) {
        $changedCodePostal = 1;
        $clientCerfa->codePostal = $data['newCodePostal'];
    }
    if(isset($data['newVille']) && !empty($data['newVille'])  && ($data['newVille'] != $og_ville)) {
        $changedVille = 1;
        $clientCerfa->ville = $data['newVille'];
    }

    if(isset($data['newTelephone']) && !empty($data['newTelephone'])  && ($data['newTelephone'] != $og_telephone)) {
        $changedTelephone = 1;
        $clientCerfa->telephone = $data['newTelephone'];
    }

 
  
    // Si aucun changement >> erreur
    if($changedEmail == 0 &&
    $changedFirstname == 0 &&
    $changedLastname == 0 &&
    $changedAdresse == 0 &&
    $changedCodePostal == 0 &&
    $changedVille == 0 &&
    $changedTelephone == 0){
        $response->getBody()->write(json_encode(['erreur' => "Veuillez modifier au moins une donnée"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    $changedEmail && $user->updateEmail();
    $changedFirstname && $clientCerfa->updateFirstname();
    $changedLastname && $clientCerfa->updateLastname();
    $changedAdresse && $clientCerfa->updateAdressePostale();
    $changedCodePostal && $clientCerfa->updateCodePostal();
    $changedVille && $clientCerfa->updateVille();
    $changedTelephone && $clientCerfa->updateTelephone();
   


    $response->getBody()->write(json_encode(['valid' => "Le client cerfa a été mis à jour"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth);