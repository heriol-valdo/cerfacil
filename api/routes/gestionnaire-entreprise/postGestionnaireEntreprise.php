<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/GestionnaireEntrepriseModel.php';
require_once __DIR__.'/../../models/EntrepriseModel.php';

//========================================================================================
// But : Permet d'ajouter un gestionnaire d'entreprise
// Rôles : admin, gestionnaire de centre
// Champs obligatoires : email, password | firstname, lastname, id_entreprises
// Champs facultatifs : telephone, lieu_travail
//========================================================================================
$app->post('/api/addUser/gestionnaireEntreprise', function (Request $request, Response $response) use ($key) {
    $data = $request->getParsedBody();

    // Check => Intégrité du formulaire
    // -- Intégrité => Champs obligatoires
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
    if(!isset($data['id_entreprises'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ entreprise doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // -- Intégrité => Champs facultatif
    if(!isset($data['telephone'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Telephone doit figurer sur le formulaire"]));
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
    if(!empty($data['id_entreprises'])){
        $entreprise = new Entreprise();
        $entreprise->id = $data['id_entreprises'];
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Le champ Entreprise est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // -- Check existence entreprise
    $entrepriseExist = $entreprise->boolId();
    if(!$entrepriseExist){
        $response->getBody()->write(json_encode(['erreur' => "L'entreprise sélectionnée n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Chargement des données
    // -- Chargement => Infos User
    $user->password = $data['password'];
    $user->id_role = 2;
    $user->addUser();

    // -- Chargement Données obligatoires
    $gestionnaireEntreprise = new GestionnaireEntreprise(); 

    $gestionnaireEntreprise->id_users = $user->searchIdForEmail(); // Renvoie l'id_users en fonction du mail entré

    $gestionnaireEntreprise->firstname = $data['firstname'];
    $gestionnaireEntreprise->lastname = $data['lastname'];
    $gestionnaireEntreprise->id_entreprises = $data['id_entreprises'];

    // -- Chargement => Données facultatives
    $gestionnaireEntreprise->telephone = !empty($data['telephone']) ? $data['telephone'] : "";
    $gestionnaireEntreprise->lieu_travail = !empty($data['lieu_travail']) ? $data['lieu_travail'] : "";

    // Succès
    $gestionnaireEntreprise->addGestionnaireEntreprise();
    /*$response->getBody()->write(json_encode(['valid' => "Vous avez ajouté un gestionnaire d'entreprise"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);*/

    try{
        $user->id = $gestionnaireEntreprise->id_users;
        $mailingInfos = $user->getMailingInfo();
        $mailFirstname = $mailingInfos[0]['firstname'];
        $mailEmail = $mailingInfos[0]['email'];
        $nomRole = $mailingInfos[0]['role'];
        if(Email::sendEmailCreationCompteERP($mailFirstname,$nomRole,$mailEmail,$user->password)){
            $response->getBody()->write(json_encode(['valid' => "Un email de validation de création du compte a été envoyé"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } else {
            throw new Exception("Utilisateur non trouvé");
        }
    } catch (Exception $e) {
        // En cas d'erreur, attrapez l'exception et renvoyez une réponse d'erreur
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    
})->add($auth)
->add($checkAdminGestionnaireCentre);