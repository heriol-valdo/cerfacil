<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

define('ROOT', 'https://cerfa.heriolvaldo.com/erp/');

define('ROOT_APP', 'https://cerfa.heriolvaldo.com/app/');

define('ROOT_CERFA', 'https://cerfa.heriolvaldo.com/cerfa/');

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../models/ClientCerfaModel.php';


//========================================================================================
// But : se connecter
// Rôles : tous
// champs: email, password
//========================================================================================
$app->post('/api/login', function (Request $request, Response $response) use ($key) {
    $data = $request->getParsedBody();
    
    $requiredFields = ['email', 'password'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            $response->getBody()->write(json_encode(['erreur' => 'Il manque des données dans la requête']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        if (empty($data[$field])) {
            $response->getBody()->write(json_encode(['erreur' => 'Certains champs obligatoires sont vides']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    $email= $data['email'];
    $password=$data['password'];

    $user = new User(); 
    $user->email = $email;
    $res = $user->login();

    if(empty($res = $user->login() )){
        $response->getBody()->write(json_encode(['erreur' => "Cet email n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    if(password_verify($password, $res[0]['password'])){
        $idByRole ='';
        $idCentre = '';
        if($res[0]['id_role'] == 1){
            $admin= new Admin();
            $admin->id_users=$res[0]['id'];
            $adminId = $admin->getAdminIdByUserId();
            $idByRole = $adminId[0];

        } elseif($res[0]['id_role'] == 2){
            $gestionnaireE = new GestionnaireEntreprise();
            $gestionnaireE->id_users=$res[0]['id'];
            $gestionnaireEId = $gestionnaireE->getGestionnaireIdByUserId();
            $idByRole = $gestionnaireEId[0];
        }elseif($res[0]['id_role'] == 3){
            $gestionnaireC = new GestionnaireCentre();
            $gestionnaireC->id_users=$res[0]['id'];
            $gestionnaireCId = $gestionnaireC->getGestionnaireIdByUserId();
            $idCentre = $gestionnaireC->searchCentreForIdUsers();
            $idByRole = $gestionnaireCId[0];
        }elseif($res[0]['id_role'] == 4){
            $formateur = new Formateur();
            $formateur->id_users=$res[0]['id'];
            $idCentre = $formateur->searchCentreForIdUsers();
            $formateurId = $formateur->getFormateurIdByUserId();
            $idByRole = $formateurId[0];
        }
        elseif($res[0]['id_role'] == 5){
            $etudiant = new Etudiant();
            $etudiant->id_users=$res[0]['id'];
            $etudiantId = $etudiant->getEtudiantIdByUserId();
            $idByRole = $etudiantId[0];
        }elseif($res[0]['id_role'] == 6){
            $financeur = new Financeur();
            $financeur->id_users=$res[0]['id'];
            $financeurId = $financeur->getFinanceurIdByUserId();
            $idByRole = $financeurId;
            
        }elseif($res[0]['id_role'] == 7){
            $clientCerfa = new ClientCerfa();
            $clientCerfa->id_users=$res[0]['id'];
            $clientCerfaId = $clientCerfa->getClientCerfaIdByUserId();
            $idByRole = $clientCerfaId;
            
        }

        $payload = [
            'iat' => time(),
            'exp' => time() + 3600,
            'id' => $res[0]['id'], 
            'idByRole' => $idByRole,
            'role' => $res[0]['id_role'],
            'centre' => ($res[0]['id_role'] == 3 || $res[0]['id_role'] == 4) ? $idCentre : null
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');
        $response->getBody()->write(json_encode(['valid' => "Vous êtes connectés avec " . $email , 'data' => $jwt]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

    }else{
        $response->getBody()->write(json_encode(['erreur' => 'Mauvais mot de passe']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
});


//========================================================================================
// But : Réinitialisation mdp
// Rôles : tous
// Champs obligatoire : email
//========================================================================================
$app->put('/api/user/password/reset', function (Request $request, Response $response, $param) use ($key) {  
    $data = $request->getParsedBody();
    $user = new User(); 
    // Check => Intégrité du formulaire
    $requiredFields = ['email'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            $response->getBody()->write(json_encode(['erreur' => "Le champ $field est manquant dans le formulaire"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        if (empty($data[$field])) {
            $response->getBody()->write(json_encode(['erreur' => "Le champ $field est vide dans le formulaire"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // Check => Remplissage champs
    $user->email = $data['email'];

    $existEmail = $user->boolEmail();
    if(!$existEmail){
        $response->getBody()->write(json_encode(['erreur' => "L'email entré n'existe pas dans la base de données"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
});


//========================================================================================
// But : Gère la partie Demande de réinitialisation de mdp
//       fonctionne en tandem avec PUT /password/reset/check
// Rôles : tous
// Champs obligatoires : email
// Champs facultatifs :
//========================================================================================
$app->post('/api/password/reset/request', function (Request $request, Response $response) use ($key) {
    $data = $request->getParsedBody();

    // Check => Intégrité du formulaire
    // -- Intégrité => Champs obligatoires
   
    if(!isset($data['email'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Email doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    // Check => Si champs obligatoires vide
    if(empty($data['email'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Email est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    // Check => Existence mail
    $user = new User(); 
    $user->email = $data['email'];
    $existEmail = $user->boolEmail();
    if(!$existEmail){
        $response->getBody()->write(json_encode(['erreur' => "L'adresse mail n'existe pas dans la base de données"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    $user->id = $user->searchIdForEmail();

    $existingResetToken = $user->getResetToken();
    if(!empty($existingResetToken['reset_token'])){

        $tokenParts = explode('::::', $existingResetToken['reset_token']);

        $tokenDateTime = $tokenParts[1];
        $tokenDateTimeObj = DateTime::createFromFormat('Y-m-d_H:i:s', $tokenDateTime);

        // Add 15 minutes to the token datetime
        $tokenDateTimeObj->add(new DateInterval('PT15M'));

        // Get the current datetime
        $currentDateTime = new DateTime();

        // Si le token est toujours en cours > Renvoi erreur
        if ($tokenDateTimeObj > $currentDateTime) {
            $response->getBody()->write(json_encode(['erreur' => "Une demande de réinitialisation a déjà été envoyée il y a moins de 15 minutes"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    }

    $token = bin2hex(random_bytes(32));
    $currentDateTime = date("Y-m-d_H:i:s"); 

    $tokenWithDateTime = $token . '::::' . $currentDateTime;

    $user->reset_token = $tokenWithDateTime;
    $user->updateResetToken();


    $role = $user->searchIdRoleForEmail();
    if($role==7){
        $url = ROOT_CERFA."resetPassword?reset_token=$tokenWithDateTime";
    }else if($role==5 || $role == 6 ){
         $url = ROOT_APP."resetPassword?reset_token=$tokenWithDateTime";
    }
    else{
        $url = ROOT."resetPassword?reset_token=$tokenWithDateTime";
    }

   

    require_once __DIR__.('/../../models/EmailModel.php');

    $resultNames = $user->getNames();

    $receiverFirstname = $resultNames['firstname'];
    $receiverLastname = $resultNames['lastname'];

    $nameDestinateur = $receiverFirstname." ".$receiverLastname;

    

    if(Email::sendEmailUser($user->email, $nameDestinateur, $url)){
        $response->getBody()->write(json_encode(['valid' => 'Un email de récupération a été envoyé sur votre adresse mail, le lien est valable 15 minutes. Pensez à vérifier vos courriers indésirables également.']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }



});