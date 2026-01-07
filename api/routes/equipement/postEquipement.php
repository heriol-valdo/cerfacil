<?php // /routes/equipement/postEquipement.php;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/EquipementModel.php';
require_once __DIR__.'/../../models/SalleModel.php';


//========================================================================================
// But : Permet d'ajouter un équipement à une salle donnée
// Rôles : admin, gestionnaireCentre
// Champs obligatoires : {salle_id}, nom, quantite
// Champs facultatifs :  
//========================================================================================
$app->post('/api/salle/{salle_id}/equipement/add', function (Request $request, Response $response, $param) use ($key) {
    $data = $request->getParsedBody();

    // Check : Intégrité du formulaire
    // -- Intégrité => Champs obligatoires
    if(!isset($data['nom'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nom de l'équipement doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['quantite'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Quantité de l'équipement doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check => Si champs obligatoires vide
    if(empty($data['nom'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nom de la salle est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['quantite'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Quantité de la salle est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if($data['quantite'] < 0){
        $response->getBody()->write(json_encode(['erreur' => "La quantité d'un équipement ne peut être négative"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check => Existence salle
    $salle = new Salle(); 
    $salle->id = $param['salle_id'];
    $salleExist = $salle->boolId();
    if(!$salleExist){
        $response->getBody()->write(json_encode(['erreur' => "La salle sélectionnée n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check => Existence équipement avec ce nom dans la salle
    $equipement = new Equipement(); 
    $equipement->nom = $data['nom'];
    $equipement->id_salles = $salle->id;

    $equipementExistSalle = $equipement->boolIdForNomAndIdSalles();
    if($equipementExistSalle){
        $response->getBody()->write(json_encode(['erreur' => "Un équipement de ce nom existe déjà dans cette salle"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Chargement données
    // -- Chargement => Données Equipement obligatoires
    $equipement->id_salles = $salle->id;
    $equipement->nom = $data['nom'];
    $equipement->quantite = $data['quantite'];

    // Succès
    $equipement->addEquipement();    
    $response->getBody()->write(json_encode(['valid' => 'Vous avez ajouté un équipement']));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

})->add($auth)->add($checkAdminGestionnaireCentre);