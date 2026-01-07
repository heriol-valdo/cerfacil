<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/EntrepriseModel.php';
require_once __DIR__.'/../../models/AdminModel.php';


//=============================================================
// --------------------- /addAdmin ------------------------
// > ATTENTION : A désactiver après avoir créé les admins
// > Création d'utilisateurs sans check de login
//=============================================================
$app->post('/api/addAdmin', function (Request $request, Response $response) use ($key) {
    $user = new User(); 
    $data = $request->getParsedBody();
    
    if(isset($data['email']) && isset($data['password'])){
        if(!empty($data['email']) && !empty($data['password'])){
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->id_role = 1;

            $user->addUser();
            $response->getBody()->write(json_encode(['valid' => 'Vous avez ajouté un utilisateur']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Veuillez entrer toutes les informations 2"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Veuillez entrer toutes les informations 1"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});


//========================================================================================
// But : Permet d'ajouter un admin
// Rôles : Admins
// Champs obligatoires : email, password | firstname, lastname
// Champs facultatifs : telephone, lieu_travail
//========================================================================================
$app->post('/api/addUser/admin', function (Request $request, Response $response) use ($key) {
    
    $data = $request->getParsedBody();
    
    // Check => Intégrité du formulaire
    if(!isset($data['email'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Email doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['password'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Mot de passe doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['firstname'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Prénom doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['lastname'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nom doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['telephone'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Téléphone doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['lieu_travail'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Lieu de travail doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }


    $user = new User(); 

    // Check => Email utilisé ou pas
    if(empty($data['email'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Email est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    $user->email = $data['email'];
    $emailExist = $user->boolEmail();
    if($emailExist){
        $response->getBody()->write(json_encode(['erreur' => "Cette adresse mail est déjà utilisée"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check => Si champs obligatoires vide
    if(empty($data['password'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ mot de passe est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['firstname'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Prénom est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['lastname'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nom est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Chargement des données
    $admin = new Admin(); 
    // -- Obligatoires : password, firstname, lastname, (email déjà chargé plus haut)
    $user->id_role = 1;
    $user->password = $data['password'];
    $user->addUser();

    $admin->id_users = $user->searchIdForEmail(); // Charge ID user en fonction du mail entré
    $admin->firstname = $data['firstname'];
    $admin->lastname = $data['lastname'];

    // -- Champs facultatifs : telephone, lieu_travail
    $admin->telephone = !empty($data['telephone']) ? $data['telephone'] : NULL;
    $admin->lieu_travail = !empty($data['lieu_travail']) ? $data['lieu_travail'] : NULL;
    
    // Succès
    if($admin->addAdmin()){
        try{
            $user->id = $admin->id_users;
            $mailingInfos = $user->getMailingInfo();
            $mailFirstname = $mailingInfos[0]['firstname'];
            $mailEmail = $mailingInfos[0]['email'];
            $nomRole = $mailingInfos[0]['role'];
            if(Email::sendEmailCreationCompteERP($mailFirstname,$nomRole,$mailEmail,$user->password)){
                $response->getBody()->write(json_encode(['valid' => "Un email de validation de création du compte a été envoyé"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } else {
                throw new Exception("Utilisateur non trouvé");
            }
        } catch (Exception $e) {
            // En cas d'erreur, attrapez l'exception et renvoyez une réponse d'erreur
            $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Erreur dans la création de l'utilisateur"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    
    
})->add($auth)
->add($checkAdmin);