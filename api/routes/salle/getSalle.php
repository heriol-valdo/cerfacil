<?php // /routes/salle/getSalle.php;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/SessionModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';
require_once __DIR__.'/../../models/SalleModel.php';
require_once __DIR__.'/../../models/EquipementModel.php';

//========================================================================================
// But : récupérer la liste des salles d'un centre de formation
// Rôles : Admins
// param: centre_id
//========================================================================================


$app->get('/api/centre/{centre_id}/salles', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $user = new User();
    $user->id = $token['id'];
    $user->id_role = intval($token['role']);

    try {
        $centreFormation = new CentreFormation(); 

        switch ($user->id_role) {
            case 1:
                if(filter_var($param['centre_id'], FILTER_VALIDATE_INT) == false){
                    throw new Exception ("Paramètre au mauvais format");
                }
                $centreFormation->id = $param['centre_id'];
                break;

            case 3:
            case 4:
                $user_centre = $user->searchIdCentreForIdUsers();
                if(empty($user_centre) || filter_var($user_centre, FILTER_VALIDATE_INT) == false){
                    throw new Exception ("Impossible de récupérer le centre de l'utilisateur");
                }
                $centreFormation->id = $user_centre;
                break;
        }
        $centreExist = $centreFormation->boolId();

        if($centreExist == false){
            throw new Exception("Ce centre de formation n'existe pas");
        }

        $salle = new Salle();
        $salle->id_centres_de_formation = $centreFormation->id;

        $result = $salle->searchAllForCentre();
        if(empty($result)){
            throw new Exception("Aucune salle enregistrée");
        } else {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }

    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($check_role([1,3,4]));

//========================================================================================
// But : récupérer la liste des salles d'un centre de formation pour un gestionnaire de centre
// Rôles : gestionnaire de centre
// param: aucun
//========================================================================================

$app->get('/api/salle/liste', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $salle = new Salle(); 
    $user = new User();
    $user->id = $token['id'];
    $user->id_role = $token['role'];

    $centreFormation = new CentreFormation();
    $centreFormation->id = $user->searchIdCentreForIdUsers();

    $centreExist = $centreFormation->boolId();

    if($centreExist == false){
        $response->getBody()->write(json_encode(['erreur' => "Ce centre de formation n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $salle->id_centres_de_formation = $centreFormation->id;

    $result = $salle->searchAllForCentre();

    if(!empty($result)){
        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Il n'y a aucune salle à afficher"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($check_role([1,3,4]));


$app->get('/api/getSalle', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];


    if ($role === 1 ) {
        $salle = new Salle();
        $salles =$salle->getSalleForAdmin();


        if (!empty($salles)) {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' =>$salles]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "liste des salles vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
        
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

$app->get('/api/salle/liste/equipement', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $salle = new Salle(); 
    $gestionnaireCentre = new GestionnaireCentre();
    $gestionnaireCentre->id_users = $token['id'];

    $centreFormation = new CentreFormation();
    $centreFormation->id = $gestionnaireCentre->searchCentreForIdUsers();

    if($centreFormation->boolId() === false){
        $response->getBody()->write(json_encode(['erreur' => "Ce centre de formation n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $salle->id_centres_de_formation = $centreFormation->id;

    $result = $salle->getEquipementForSalle();

    if(!empty($result)){
        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Il n'y a aucun equipement à afficher"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($checkGestionnaireCentre);

$app->get('/api/salle/salles&equipements/{centre_id}', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    try {
        $selected = "";
        $salle = new Salle();
        switch ($role){
            case 1:
                if(filter_var($param['centre_id'], FILTER_VALIDATE_INT) == false){
                    throw new Exception("Format du paramètre invalide");
                }
                $salle->id_centres_de_formation = $param['centre_id'];
                break;

            case 3:
                if(empty($token['centre'])){
                    throw new Exception("Utilisateur non rattaché à un centre de formation");
                }
                $salle->id_centres_de_formation = $token['centre'];
                break;
        }
        
        $salles = $salle->searchAllForCentre();
        if(empty($salles)) {
            throw new Exception("Aucune salle enregistrée");
        }

        $equipement = new Equipement();
        $salleEquipement = [];

        foreach ($salles as $salle) {
            $salleEquipement[$salle['nom']] = [
                "id" => $salle['id'],
                "nom" => $salle['nom'],
                "capacite_accueil" => $salle['capacite_accueil'],
                "equipements" => [] 
            ];
        
            $equipement->id_salles = $salle['id'];
            $equipements = $equipement->searchAllForSalle();
        
            if (empty($equipements)) {
                $salleEquipement[$salle['nom']]['equipements'] = null;
            } else {
                $salleEquipement[$salle['nom']]['equipements'] = $equipements;
            }
        }

        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $salleEquipement]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($check_role([1,3]));

// Récupération des salles pour une ou plusieurs sessions
$app->post('/api/salles/sessions', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userId = $token['id'];

    // Vérification des données reçues
    if (!isset($data['sessions']) || !is_array($data['sessions']) || empty($data['sessions'])) {
        $response->getBody()->write(json_encode(['erreur' => "Les identifiants de session sont requis"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $sessionIds = array_map('intval', $data['sessions']);

    // Vérification des autorisations pour chaque session
    foreach ($sessionIds as $sessionId) {
        $session = new Session();
        $session->id = $sessionId;

        if (!$session->boolId()) {
            $response->getBody()->write(json_encode(['erreur' => "La session $sessionId n'existe pas"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        // Vérification des droits d'accès selon le rôle
        if ($role == 3 || $role == 4) {
            $centreDeLaSession = $session->getCentreForId();
            if ($centreDeLaSession['id_centres_de_formation'] != $token['centre']) {
                $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas accès à la session $sessionId"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
            }
        }
    }

    // Récupération des salles
    $salle = new Salle();
    $sallesList = $salle->getSallesForSessions($sessionIds);

    if (empty($sallesList)) {
        $response->getBody()->write(json_encode(['message' => "Aucune salle trouvée pour ces sessions"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    $response->getBody()->write(json_encode(['data' => $sallesList]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
})->add($auth);