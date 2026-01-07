<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ .'/../../models/UserModel.php';
require_once __DIR__ .'/../../models/AdminModel.php';
require_once __DIR__ .'/../../models/EntrepriseModel.php';

//========================================================================================
// But : modifier le profil d'un administrateur
// Rôles : Admins
// Champs possibles : newEmail, newPassword, newFirstname, newLastname, newTelephone, newLieuTravail
//========================================================================================

$app->put('/api/admin/{userId}/updateAdmin', function (Request $request, Response $response, $param) use ($key) {  
    $token = $request->getAttribute('user');
    $id = $token['id'];

    $user = new User();
    $admin = new Admin();

    $user->id = $param['userId'];
    $admin->id_users = $user->id;

    // Vérifie si l'utilisateur existe
    if(!($user->boolId())){
        $response->getBody()->write(json_encode(['erreur' => "L'utilisateur n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $data = $request->getParsedBody();
    
    // Débogage pour vérifier les données reçues
    error_log('Données reçues: ' . print_r($data, true));
    
    $result = $user->searchForId();

    $user->role = $result['id_role'];
    // Vérifie le rôle
    if($result['id_role'] != 1){
        $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Variables pour détecter les changements
    $changedEmail = 0;
    $changedFirstname = 0;
    $changedLastname = 0;
    $changedTelephone = 0;
    $changedLieuTravail = 0;

    // Récupération des données de l'utilisateur
    $og_user = $user->searchForId();
    $og_role = $admin->searchForId();

    // Débogage pour vérifier les données actuelles
    error_log('Données utilisateur actuelles: ' . print_r($og_user, true));
    error_log('Données admin actuelles: ' . print_r($og_role, true));

    $og_email = $og_user['email'];
    $og_firstname = $og_role['firstname'];
    $og_lastname = $og_role['lastname'];
    $og_telephone = $og_role['telephone'];
    $og_lieuTravail = $og_role['lieu_travail'];

    // Débogage spécifique pour le lieu de travail
    error_log('Lieu de travail actuel: ' . $og_lieuTravail);
    if(isset($data['newLieuTravail'])) {
        error_log('Nouveau lieu de travail: ' . $data['newLieuTravail']);
        error_log('Comparaison: ' . ($data['newLieuTravail'] != $og_lieuTravail ? 'Différent' : 'Identique'));
    }

    // Vérification de l'email
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
    
    // Vérification du prénom
    if(isset($data['newFirstname']) && !empty($data['newFirstname']) && ($data['newFirstname'] != $og_firstname)) {
        $changedFirstname = 1;
        $admin->firstname = $data['newFirstname'];
    }
    
    // Vérification du nom
    if(isset($data['newLastname']) && !empty($data['newLastname']) && ($data['newLastname'] != $og_lastname)) {
        $changedLastname = 1;
        $admin->lastname = $data['newLastname'];
    }
    
    // Vérification du téléphone
    if(isset($data['newTelephone']) && !empty($data['newTelephone']) && ($data['newTelephone'] != $og_telephone)) {
        $changedTelephone = 1;
        $admin->telephone = $data['newTelephone'];
    }
    
    // Vérification du lieu de travail - CORRIGÉ
    if(isset($data['newLieuTravail']) && $data['newLieuTravail'] !== "" && trim($data['newLieuTravail']) !== trim($og_lieuTravail)) {
        $changedLieuTravail = 1;
        $admin->lieu_travail = $data['newLieuTravail'];
        error_log('Changement de lieu de travail détecté!');
    }

   

    // Si aucun changement >> erreur
    if($changedEmail == 0 &&
       $changedFirstname == 0 &&
       $changedLastname == 0 &&
       $changedTelephone == 0 &&
       $changedLieuTravail == 0) {
        $response->getBody()->write(json_encode(['erreur' => "Veuillez modifier au moins une donnée"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Application des changements
    if($changedEmail) $user->updateEmail();
    if($changedFirstname) $admin->updateFirstname();
    if($changedLastname) $admin->updateLastname();
    if($changedTelephone) $admin->updateTelephone();
    if($changedLieuTravail) $admin->updateLieuTravail();

    $response->getBody()->write(json_encode(['valid' => "Les informations ont bien été modifées"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth)->add($checkAdmin);