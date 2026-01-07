<?php // /routes/etudiant/postEtudiant.php;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/ClientCerfaModel.php';
require_once __DIR__.'/../../models/EmailModel.php';


//========================================================================================
// Champs obligatoires : email, password | firstname, lastname, adressePostale, codePostal, ville, telephone
//========================================================================================


$app->post('/api/addUser/clientCerfa', function (Request $request, Response $response) use ($key) {
    $token=$request->getAttribute('user');
    $role=intval($token['role']);
    $data = $request->getParsedBody();

    // Check : Intégrité du formulaire
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
    if(!isset($data['adressePostale'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Adresse doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['codePostal'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Code postal doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['ville'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Ville doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['telephone'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ telephone doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['roleCreation'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ role creation doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['idCreation'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ id creation doit figurer sur le formulaire"]));
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
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
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
    if(empty($data['adressePostale'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Adresse est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['codePostal'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Code postal est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['ville'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Ville est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['telephone'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ telephone est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['roleCreation'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ role Creation est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['idCreation'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ id Creation est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    

   
    if($role == 1 || $role == 7){

        
            // Chargement des données
            // -- Chargement => Infos User
            $user->password = $data['password'];
            $user->id_role = 7;
           
          
            $clientCerfa = new ClientCerfa(); 

         
            $clientCerfa->telephone=$data['telephone'];
            $telephoneExist = $clientCerfa->boolTelephone();
            if($telephoneExist){
                $response->getBody()->write(json_encode(['erreur' => "Ce numero de telephone est déjà utilisée"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
             
            } else{

                $user->addUser();

                $clientCerfa->id_users = $user->searchIdForEmail(); // Renvoie l'id_users en fonction du mail entré
    
    
    
                $clientCerfa->firstname = $data['firstname'];
                $clientCerfa->lastname = $data['lastname'];
                $clientCerfa->adressePostale = $data['adressePostale'];
                $clientCerfa->codePostal = $data['codePostal'];
                $clientCerfa->ville = $data['ville'];
                $clientCerfa->telephone = $data['telephone'];
                $clientCerfa->idCreation = $data['idCreation'];
                $clientCerfa->roleCreation = $data['roleCreation'];
            
    
                // Succès
             
    
                if(Email::sendEmailCreationClientCerfa($data['firstname'],$data['email'],$data['password'])){
                    $clientCerfa->addClientCerfa();
                    $response->getBody()->write(json_encode(['valid' => 'Vous avez ajouté un client cerfa']));
                    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                }else{
                    $response->getBody()->write(json_encode(['erreur' => "Une erreur est survenue mors de l'envoie du mail"]));
                    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                }

            }

           
           
    }else{
        $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits  necessaires pour effectuer cette action"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }

    
    

})->add($auth);


