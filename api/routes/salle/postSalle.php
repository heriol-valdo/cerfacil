<?php // /routes/salle/postSalle.php;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';
require_once __DIR__.'/../../models/SalleModel.php';

//========================================================================================
// But : Permet d'ajouter une salle à un centre
// Rôles : admin, gestionnaireCentre
// Champs obligatoires : id_centres_de_formation, nom
// Champs facultatifs : capacite_accueil
//========================================================================================
$app->post('/api/salle/add', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data = $request->getParsedBody();

    // Check => Intégrité du formulaire
    // -- Intégrité => Champs obligatoires
   
    if(!isset($data['nom'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nom de la salle doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check => Si champs obligatoires vide
    
    if(empty($data['nom'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nom de la salle est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check => Existence centre de formation
    // Admin
    $centreFormation = new CentreFormation(); 
    if($role == 1){
        if(!isset($data['id_centres_de_formation'])){
            $response->getBody()->write(json_encode(['erreur' => "Le champ Centre de formation doit figurer sur le formulaire"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        if(empty($data['id_centres_de_formation'])){
            $response->getBody()->write(json_encode(['erreur' => "Le champ Centre de formation est vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        $centreFormation->id = $data['id_centres_de_formation'];
        $centreFormationExist = $centreFormation->boolId();
        if(!$centreFormationExist){
            $response->getBody()->write(json_encode(['erreur' => "Le centre de formation sélectionné n'existe pas"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
    // GestionnaireCentre
    if($role == 3){
        $gestionnaireCentre = new GestionnaireCentre();
        $gestionnaireCentre->id_users = $token['id'];
        $gestionnaireCentre_id_centre = $gestionnaireCentre->searchCentreForIdUsers();
        $centreFormation->id = $gestionnaireCentre_id_centre;
        $centreFormationExist = $centreFormation->boolId();
        if(!$centreFormationExist){
            $response->getBody()->write(json_encode(['erreur' => "Le centre de formation sélectionné n'existe pas"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
    

    // Check => Existence salle avec ce nom
    $salle = new Salle(); 
    $salle->nom = $data['nom'];
    $salle->id_centres_de_formation = $centreFormation->id;
    $nomExist = $salle->boolIdforNom();
    if($nomExist){
        $response->getBody()->write(json_encode(['erreur' => "Une salle porte déjà ce nom"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // -- Chargement => Données Salle facultatives
    $salle->capacite_accueil = !empty($data['capacite_accueil']) ? $data['capacite_accueil'] : NULL;

    $result = $salle->addSalle();
    // Succès
    if($result){
        $response->getBody()->write(json_encode(['valid' => 'Vous avez ajouté une salle']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Erreur dans la création de la salle']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    

})->add($auth)->add($checkAdminGestionnaireCentre);