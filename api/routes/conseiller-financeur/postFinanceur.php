<?php // /routes/conseiller-financeur/postFinanceur.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/EntrepriseModel.php';
require_once __DIR__.'/../../models/FinanceurModel.php';

$app->post('/api/addUser/financeur', function (Request $request, Response $response) use ($key) {
    $user = new User(); 
    $data = $request->getParsedBody();

    // Intégrité formulaire
    if(!isset($data['email'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ adresse mail est obligatoire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['password'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ mot de passe est obligatoire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['lastname'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nom est obligatoire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['firstname'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Prénom est obligatoire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['type_financeur'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Type de financeur est obligatoire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['id_entreprises'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Entreprise est obligatoire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check => Champs vide
    if(empty($data['email'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ adresse mail est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['password'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ mot de passe est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['lastname'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nom est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['firstname'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Prénom est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['type_financeur'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Type de financeur est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['id_entreprises'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Entreprise est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check => Email existe
    $user->email = $data['email'];
    $emailExist = $user->boolEmail();
    if($emailExist){
        $response->getBody()->write(json_encode(['erreur' => "L'email est déjà utilisé"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check => Entreprise existe
    $entreprise = new Entreprise();
    $entreprise->id = $data['id_entreprises'];
    $entrepriseExist = $entreprise->boolId();
    if(!$entrepriseExist){
        $response->getBody()->write(json_encode(['erreur' => "L'entreprise sélectionnée n'existe pas'"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $financeur = new Financeur(); 
    $user->password = $data['password'];
    $user->id_role = 6;
    $user->addUser();

    // Données obligatoires
    // -- Renvoie l'id en fonction du mail
    $financeur->id_users = $user->searchIdForEmail();

    $financeur->firstname = $data['firstname'];
    $financeur->lastname = $data['lastname'];
    $financeur->type_financeur = $data['type_financeur'];
    $financeur->id_entreprises = $data['id_entreprises'];

    // Succès
    if($financeur->addFinanceur()){
        try{
            $user->id = $financeur->id_users;
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
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Erreur dans la création de l'utilisateur"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    
    
})->add($auth)
->add($checkAdminGestionnaireCentre);