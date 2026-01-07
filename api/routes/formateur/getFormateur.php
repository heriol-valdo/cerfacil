<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__.'/../../models/FormateurModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';
require_once __DIR__.'/../../models/FormateursParticipantSessionModel.php';


//========================================================================================
// But : Récupérer le profil d'un formateur
// Rôles : Admins, formateurs
// param :user_id
//========================================================================================

$app->get('/api/formateur/{user_id}/profil', function (Request $request, Response $response, $param) use ($key) {

    $token=$request->getAttribute('user');
    $role=intval($token['role']);
    $userConnected = $token['id'];  

    // Check => Format param
    if(empty($param['user_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ user_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['user_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    if($role === 1 || $userConnected === $param['user_id']){


        $formateur = new Formateur(); 
        $formateur->id_users = $param['user_id'];

        if($formateur ->boolId() === false){
            $response->getBody()->write(json_encode(['erreur' => "Cet utilisateur n'existe pas"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $centreFormation = new CentreFormation();

        $formateur->id_users = $param['user_id'];
        
            //récupération de l'id entreprise
        $formateurDatas = $formateur->getProfilFormateur();
        $idCentreFormation = $formateurDatas[0]['id_centres_de_formation'];

        $centreFormation->id=$idCentreFormation;
        $centreFormationDatas = $centreFormation->getCentreFormationDatas();

        $centreFormationDatasFiltered = array_filter($centreFormationDatas[0], function($value, $key) {
            return ($key !== 'id' && $key !== 'id_entreprises');
        }, ARRAY_FILTER_USE_BOTH);   
        
        $formateurDatasFiltered = array_filter($formateurDatas[0], function($value, $key) {
            return ($key !== 'id' && $key !== 'id_centres_de_formation' && $key !== 'id_users' && $key !== 'id_role');
        }, ARRAY_FILTER_USE_BOTH); 

        $profilDatas = [
            'formateurDatas'=>$formateurDatasFiltered,
            'centreFormationDatas'=>$centreFormationDatasFiltered,
        ];

        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $profilDatas]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

})->add($auth);

$app->get('/api/getFormateur', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $idByRole = $token['idByRole'];


    if ($role === 1 ) {
        $formateur = new Formateur();
        $formateurs =$formateur->getFormateurForAdmin();


        if (!empty($formateurs)) {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' =>$formateurs]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "liste des formateurs vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

    } elseif ($role === 3) {
        $formateur = new Formateur();

        $formateur->id = $idByRole;
        $centre = $formateur->searchCentreForId();

        $formateur->id_centres_de_formation = $centre;
        $formateurs =$formateur->getFormateursByCentre();

        if (!empty($formateurs)) {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' =>$formateurs]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
        
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);


// Récupération des formateurs pour une ou plusieurs sessions

$app->post('/api/formateurs/sessions', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $sessionIds = $data['sessions'] ?? [];

    if (empty($sessionIds)) {
        $response->getBody()->write(json_encode(['erreur' => "Aucune session spécifiée"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $formateursParticipantSession = new FormateursParticipantSession();
    $formateurs = $formateursParticipantSession->getUniqueFormateursForSessions($sessionIds);

    if (empty($formateurs)) {
        $response->getBody()->write(json_encode(['message' => "Aucun formateur trouvé pour ces sessions"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    $response->getBody()->write(json_encode(['data' => $formateurs]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
})->add($auth);