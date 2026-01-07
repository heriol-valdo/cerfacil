<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/FormateursParticipantSessionModel.php';
require_once __DIR__.'/../../models/FormateurModel.php';
require_once __DIR__.'/../../models/SessionModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';
require_once __DIR__.'/../../models/UserModel.php';


//========================================================================================
// But : Récupérer les formateurs d'une session
// Rôles : Admins, gestionnaire de centre
// param :id_session
//========================================================================================

$app->get('/api/session/{idSession}/getFormateurs', function (Request $request, Response $response, $param) use ($key) {

    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    // Check => Format param
    if(empty($param['idSession'])) {
        $response->getBody()->write(json_encode(['error' => "Le champ idSession est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['idSession'])) {
        $response->getBody()->write(json_encode(['error' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $idSession=$param['idSession'];

    $session = new Session();
    $session->id= $idSession;
    $sessionExist = $session->boolId();

    if(!$sessionExist) {
        $response->getBody()->write(json_encode(['error' => "Cette session n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    if($role !== 1 && $role !== 3) {
        $response->getBody()->write(json_encode(['error' => "Vous n'avez pas les droits nécéssaires"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }

    $formateursParticipantSession = new FormateursParticipantSession();
    $formateursParticipantSession->id=$idSession;
    $formateurs = $formateursParticipantSession->getFormateursBySession();

    function getFormateurName($value) {
        $formateur = new Formateur;
        $formateur->id=$value['id_formateurs'];
        return $formateur->getFormateurName();
        }
    
    $formateursDatas = array_map('getFormateurName',$formateurs);
    
    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $formateursDatas]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    

})->add($auth);

//========================================================================================
// But : Récupérer les sessions d'un formateur
// Rôles : Admins, formateurs
// param possible : idFormateur
//========================================================================================

$app->get('/api/getSessionsByFormateur', function (Request $request, Response $response) use ($key) {

    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    if($role !== 1 && $role !== 4) {
        $response->getBody()->write(json_encode(['error' => "Vous n'avez pas les droits nécéssaires"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }

    $body = $request->getParsedBody();

    $formateur = new Formateur;
    $formateursParticipantSession = new FormateursParticipantSession();

    if($role===4){
        $formateursParticipantSession->id_formateurs= $token['idByRole'];
    }

    if($role===1){
        if (!isset($body['idFormateur'])) {
            $response->getBody()->write(json_encode(['error' => "L'id du formateur n'est pas renseigné dans la requête"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        
        $formateur->id=$body['idFormateur'];
        $formateurExist = $formateur->checkFormateurExist();

        if(!$formateurExist) {
            $response->getBody()->write(json_encode(['error' => "Ce formateur n'existe pas dans la base de données"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $formateursParticipantSession->id_formateurs=$body['idFormateur'];
    }

    $sessionsId = $formateursParticipantSession->getSessionsByFormateur();

    function getSessions($value) {
        $session = new Session();
        $session->id=$value['id'];
        return $session->getSessionDatas();
        }
    
    $sessions = array_map('getSessions',$sessionsId);
    
    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $sessions]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    

})->add($auth);

$app->get('/api/getSessionsByGestionnaire', function (Request $request, Response $response) use ($key) {
    $data = $request->getParsedBody();
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];

    

    if($role === 1 || $role === 3) {
        $gestionnaire  = new GestionnaireCentre();
        $idcentreformation= $gestionnaire->searchCentreForId($userConnected);

        if (empty($idcentreformation)) {
            $response->getBody()->write(json_encode(['erreur' => "ce gestionnaire n'est pas rattacher a un centre de formation"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $formateursParticipantSession = new FormateursParticipantSession();
        $allsessions =  $formateursParticipantSession ->getSessionFormateur($idcentreformation);

        if (!empty($allsessions)) {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $allsessions]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "Nous n'avons pas trouver de formateurs participant a une session "]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);

        }
      

    }else{
        $response->getBody()->write(json_encode(['error' => "Vous n'avez pas les droits nécéssaires"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);

    }

})->add($auth);


$app->get('/api/centre/{centreId}/sessions/formateurs', function (Request $request, Response $response, $param) {
    $token = $request->getAttribute('user');
    $user = new User();
    $user->id = $token['id'];
    $user->id_role = $token['role'];

    try {
        $participants = new FormateursParticipantSession();
        $session = new Session();
        $formateur = new Formateur();
        $centre = new CentreFormation();

        switch ($user->id_role){
            case 1:
                if(!isset($param['centreId']) || !filter_var($param['centreId'], FILTER_VALIDATE_INT)){
                    throw new Exception("Paramètre au format invalide");
                }
                $centre->id = $param['centreId'];
                $centre_exist = $centre->boolId();

                if(!$centre_exist){
                    throw new Exception("Le centre sélectionné n'existe pas");
                }

                $formateur->id_centres_de_formation = $centre->id;
                break;

            case 3:
            case 4:
                $formateur->id_centres_de_formation =  $user->searchIdCentreForIdUsers();
                break;
        }

        $list_formateurs = $formateur->getFormateursByCentre();

        $list_participations = $formateur->sessionsFormateursByCentre();

        $result = [];

        foreach ($list_formateurs as $formateur) {
            $formateurId = $formateur['id'];
            
            $sessionsForFormateur = array_filter($list_participations, function($participation) use ($formateurId) {
                return $participation['id_formateurs'] === $formateurId;
            });
        
            $sessionIds = array_column($sessionsForFormateur, 'id');
        
            $result[] = [
                'formateur' => $formateur,
                'sessions' => $sessionIds,
            ];
        }

        if(empty($result)){
            throw new Exception("Aucun résultat trouvé");
        } else {
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie', 'data' => $result]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($check_role([1,3,4]));