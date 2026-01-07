<?php // /routes/equipement/getEquipement.php;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__.'/../../models/CentreFormationModel.php';
require_once __DIR__.'/../../models/SalleModel.php';
require_once __DIR__.'/../../models/EquipementModel.php';
require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/FormateurModel.php';


//========================================================================================
// But : Affiche la liste des équiepements d'une salle 
// Rôles : gestionnaire de centre, admin, formateur
// param: salle_id
//========================================================================================
$app->get('/api/salle/{salle_id}/equipement/liste', function (Request $request, Response $response, $param) use ($key) {

    $salle = new Salle(); 
    $salle->id = $param['salle_id'];
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    // Check => Existence Salle
    if($salle->boolId() === false){
        $response->getBody()->write(json_encode(['erreur' => "Cette salle n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    
    // Check => GestionnaireCentre => Appartenance Salle 
    if($role == 3){
        $salle_id_centre = $salle->searchCentreForId();
        $gestionnaireCentre = new GestionnaireCentre();
        $gestionnaireCentre->id_users = $token['id'];
        $gestionnaireCentre_id_centre = $gestionnaireCentre->searchCentreForIdUsers();

        if($gestionnaireCentre_id_centre != $salle_id_centre){
            $response->getBody()->write(json_encode(['erreur' => "La salle sélectionnée n'existe pas dans votre centre"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);    
        }
    }

    // Check => Formateur => Appartenance Salle 
    if($role == 4){
        $salle_id_centre = $salle->searchCentreForId();
        $formateur = new Formateur();
        $formateur->id_users = $token['id'];
        $formateur_id_centre = $formateur->searchCentreForIdUsers();

        if($formateur_id_centre != $salle_id_centre){
            $response->getBody()->write(json_encode(['erreur' => "La salle sélectionnée n'existe pas dans votre centre"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);    
        }
    }
    
    $equipement = new Equipement();
    $equipement->id_salles= $param['salle_id'];

    $result = $equipement->searchAllForSalle();

    $resultFiltered = array_filter($result, function($value, $key) {
        return ($key === 'nom' || $key === 'quantite');
    }, ARRAY_FILTER_USE_BOTH);

    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $resultFiltered]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

})->add($auth)->add($checkAdminEquipePedagogique);

$app->get('/api/getEquipement', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];

    if ($role == 1 ) {
        $equipement = new Equipement();
        $equipements =$equipement->getEquipementForAdmin();


        if (!empty($equipements)) {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' =>$equipements]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "liste des equipements vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
        
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth)->add($checkAdmin);

$app->get('/api/getEquipement/{centre_id}', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];

    if ($role == 3 ) {
        $salle = new Salle();
        $salle->id_centres_de_formation = $param['centre_id'];
        $salles = $salle->searchAllForCentre();

        $equipements = [];
        
        foreach ($salles as $salle) {
            $equipement = new Equipement();
            $equipement->id_salles = $salle['id'];
            $result = $equipement->searchAllForSalle();
            if (is_array($result)) {
                foreach ($result as $item) {
                    $equipements[] = $item;
                }
            }
        }

        if (!empty($equipements)) {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' =>$equipements]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "liste des equipements vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
        
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth)->add($checkGestionnaireCentre);
