<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';
require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/EmailModel.php';


//========================================================================================
// But : Permet d'ajouter un gestionnaire de centre
// Rôles : admin
// Champs obligatoires : email, password | firstname, lastname, id_centres_de_formation
// Champs facultatifs : telephone
//========================================================================================
$app->post('/api/addUser/gestionnaireCentre', function (Request $request, Response $response) use ($key) {
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
    if(!isset($data['id_centres_de_formation'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Centre de formation doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // -- Intégrité => Champs facultatif
    if(!isset($data['telephone'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Telephone doit figurer sur le formulaire"]));
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
    if(!empty($data['id_centres_de_formation'])){
        $centreFormation = new CentreFormation();
        $centreFormation->id = $data['id_centres_de_formation'];
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Le champ Centre de formation est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // -- Check existence centre de formation
    $centreFormationExist = $centreFormation->boolId();
    if(!$centreFormationExist){
        $response->getBody()->write(json_encode(['erreur' => "Le centre de formation sélectionné n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Chargement des données
    // -- Chargement => Infos User
    $user->password = $data['password'];
    $user->id_role = 3;
    $user->addUser();

    // -- Chargement => Données Etudiant obligatoires
    $gestionnaireCentre = new GestionnaireCentre(); 

    $gestionnaireCentre->id_users = $user->searchIdForEmail(); // Renvoie l'id_users en fonction du mail entré

    $gestionnaireCentre->firstname = $data['firstname'];
    $gestionnaireCentre->lastname = $data['lastname'];
    $gestionnaireCentre->id_centres_de_formation = $data['id_centres_de_formation'];

    // -- Chargement => Données Etudiant facultatives
    $gestionnaireCentre->telephone = !empty($data['telephone']) ? $data['telephone'] : NULL;


    // Succès
    $gestionnaireCentre->addGestionnaireCentre();

    try{
        $user->id = $gestionnaireCentre->id_users;
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
->add($checkAdmin);