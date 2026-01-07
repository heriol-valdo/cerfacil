<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__ .'/../../models/UserModel.php';
require_once __DIR__ .'/../../models/GestionnaireCentreModel.php';
require_once __DIR__."/../../models/CentreFormationModel.php";

//========================================================================================
// But : Permet de modifier un gestionnaire de centre
// Rôles : admin
// Champs possibles : newEmail, newPassword | newFirstname, newLastname, newCentre, newTelephone
//========================================================================================

$app->put('/api/admin/gestionnaireCentre/{user_id}/update', function (Request $request, Response $response, $param) use ($key) {  
    $user = new User();
    $gestionnaireCentre = new GestionnaireCentre();
    
     // Check => Format param
     if(empty($param['user_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ user_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $user->id = $param['user_id'];
    $gestionnaireCentre->id_users = $user->id;

    // Si l'utilisateur existe
    if(!($user->boolId())){
        $response->getBody()->write(json_encode(['erreur' => "L'utilisateur n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $data = $request->getParsedBody();
    $result = $user->searchForId();

    $user->role = $result['id_role'];
    // Si le rôle est bien le bon
    if($result['id_role'] != 3){
        $response->getBody()->write(json_encode(['erreur' => "L'utilisateur n'est pas un gestionnaire de centre"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Reconnaissance si changement effectué
    $changedEmail = 0;

    $changedFirstname = 0;
    $changedLastname = 0;
    $changedTelephone = 0;
    $changedCentreFormation = 0;

    $og_user = $user->searchForId();
    $og_role = $gestionnaireCentre->searchForId();
    //var_dump($og_role);

    $og_email = $og_user['email'];

    $og_firstname = $og_role['firstname'];
    $og_lastname = $og_role['lastname'];
    $og_telephone = $og_role['telephone'];
    $og_centre = $og_role['id_centres_de_formation'];

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
        $gestionnaireCentre->firstname = $data['newFirstname'];
    }
    if(isset($data['newLastname']) && !empty($data['newLastname'])  && ($data['newLastname'] != $og_lastname)) {
        $changedLastname = 1;
        $gestionnaireCentre->lastname = $data['newLastname'];
    }
    if(isset($data['newTelephone']) && !empty($data['newTelephone'])  && ($data['newTelephone'] != $og_telephone)) {
        $changedTelephone = 1;
        $gestionnaireCentre->telephone = $data['newTelephone'];
    }
    if(isset($data['newCentre']) && !empty($data['newCentre']) && ($data['newCentre'] != $og_centre)) {
        $centreFormation = new CentreFormation();
        $centreFormation->id = $data['newCentre'];
        $centreFormationExist = $centreFormation->boolId();
        if($centreFormationExist){
            $changedCentreFormation = 1;
            $gestionnaireCentre->id_centres_de_formation = $data['newCentre'];
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Le centre de formation sélectionné n'existe pas'"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // Si aucun changement >> erreur
    if($changedEmail == 0 &&
    $changedFirstname == 0 &&
    $changedLastname == 0 &&
    $changedTelephone == 0 &&
    $changedCentreFormation == 0){
        $response->getBody()->write(json_encode(['erreur' => "Veuillez modifier au moins une donnée"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $changedEmail && $user->updateEmail();

    $changedFirstname && $gestionnaireCentre->updateFirstname();
    $changedLastname && $gestionnaireCentre->updateLastname();
    $changedTelephone && $gestionnaireCentre->updateTelephone();
    $changedCentreFormation && $gestionnaireCentre->updateCentreFormation();

    $response->getBody()->write(json_encode(['valid' => "Le gestionnaire de centre a été mis à jour"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth)->add($checkAdmin);