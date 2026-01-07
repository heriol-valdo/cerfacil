<?php // /routes/etudiant/postEtudiant.php;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/EtudiantModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';

//========================================================================================
// But : Permet d'ajouter un étudiant
// Rôles : admin, gestionnaireCentre
// Champs obligatoires : email, password | firstname, lastname, adressePostale, codePostal, ville, date_naissance, id_centres_de_formation
// Champs facultatifs : id_entreprises, id_conseillers_financeurs, id_session
//========================================================================================
$app->post('/api/addUser/etudiant', function (Request $request, Response $response) use ($key) {
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
    if(!isset($data['date_naissance'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Date de naissance doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    
    

    // -- Intégrité => Champs facultatifs
    if(!isset($data['id_entreprises'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Entreprise doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['id_conseillers_financeurs'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Conseiller financeur doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['id_session'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Session doit figurer sur le formulaire"]));
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
    if(empty($data['date_naissance'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Date de naissance est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    // Check => Administrateurs : Si champ centres de formation existe et si il est vide
    if($role == 1){
        if(!isset($data['id_centres_de_formation'])){
            $response->getBody()->write(json_encode(['erreur' => "Le champ Centre de formation doit figurer sur le formulaire"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        if(!empty($data['id_centres_de_formation'])){
            $centreFormation = new CentreFormation();
            $centreFormation->id = $data['id_centres_de_formation'];
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Le champ Centre de formation est vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    $centreFormation = new CentreFormation();
    $gestionnaireCentre_id_centre='';

    // Check => GestionnaireCentre : Récupération id_centres pour le donner à l'étudiant
    if($role == 3){
        $gestionnaireCentre = new GestionnaireCentre();
        $gestionnaireCentre->id_users = $token['id'];
        $gestionnaireCentre_id_centre = $gestionnaireCentre->searchCentreForIdUsers();
        $centreFormation->id = $gestionnaireCentre_id_centre;
    }

    
    // -- Check existence centre de formation
    $centreFormation->id = $data['id_centres_de_formation'];
    $centreFormationExist = $centreFormation->boolId();
    if(!$centreFormationExist){
        $response->getBody()->write(json_encode(['erreur' => "Le centre de formation sélectionné n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    
    // Chargement des données
    // -- Chargement => Infos User
    $user->password = $data['password'];
    $user->id_role = 5;
    $user->addUser();

    // -- Chargement => Données Etudiant obligatoires
    $etudiant = new Etudiant(); 

    $etudiant->id_users = $user->searchIdForEmail(); // Renvoie l'id_users en fonction du mail entré

    $etudiant->firstname = $data['firstname'];
    $etudiant->lastname = $data['lastname'];
    $etudiant->adressePostale = $data['adressePostale'];
    $etudiant->codePostal = $data['codePostal'];
    $etudiant->ville = $data['ville'];
    $etudiant->date_naissance = $data['date_naissance'];
    if($role == 1){
        $etudiant->id_centres_de_formation = $data['id_centres_de_formation'];
    }
    if($role == 3){
        $etudiant->id_centres_de_formation = $gestionnaireCentre_id_centre;
    }

    // -- Chargement => Données Etudiant facultatives
    $etudiant->id_entreprises = !empty($data['id_entreprises']) ? $data['id_entreprises'] : NULL;
    $etudiant->id_conseillers_financeurs = !empty($data['id_conseillers_financeurs']) ? $data['id_conseillers_financeurs'] : NULL;
    $etudiant->id_session = !empty($data['id_session']) ? $data['id_session'] : NULL;

    // Succès
    if($etudiant->addEtudiant()){
        try{
            $user->id = $etudiant->id_users;
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
        $response->getBody()->write(json_encode(['erreur' => "Erreur dans la création de l'étudiant"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

})->add($auth)
->add($checkAdminGestionnaireCentre);
