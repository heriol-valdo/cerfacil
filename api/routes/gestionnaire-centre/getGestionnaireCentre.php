<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';

//========================================================================================
// But : Récupérer le profil d'un gestionnaire de centre
// Rôles : Admins, gestionnaire de centre
// champs: user_id
//========================================================================================

$app->get('/api/gestionnaire-centre/{user_id}/profil', function (Request $request, Response $response, $param) use ($key) {

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

        $gestionnaire = new GestionnaireCentre(); 
        $gestionnaire->id_users = $param['user_id'];

        if($gestionnaire ->boolId() === false){
            $response->getBody()->write(json_encode(['erreur' => "Cet utilisateur n'existe pas"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $centre = new CentreFormation();

        $gestionnaire->id_users = $param['user_id'];
        
            //récupération de l'id entreprise
        $gestionnaireDatas = $gestionnaire->getProfilGestionnaireCentre();
        $idCentre = $gestionnaireDatas[0]['id_centres_de_formation'];

        $centre->id=$idCentre;
        $centreDatas = $centre->getCentreFormationDatas();

        $centreDatasFiltered = array_filter($centreDatas[0], function($value, $key) {
            return ($key !== 'id' && $key !== 'id_entreprises');
        }, ARRAY_FILTER_USE_BOTH);  

        $gestionnaireDatasFiltered = array_filter($gestionnaireDatas[0], function($value, $key) {
            return ($key !== 'id' && $key !== 'id_role' && $key !== 'id_users');
        }, ARRAY_FILTER_USE_BOTH); 

        $profilDatas = [
            'gestionnaireDatas'=>$gestionnaireDatasFiltered,
            'centreDatas'=>$centreDatasFiltered,
        ];

        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $profilDatas]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

    }else{
        $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }

})->add($auth);

$app->get('/api/getGestionnaire', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];


    if ($role === 1 ) {
        $gestionnaire = new GestionnaireCentre();
        $gestionnaires =$gestionnaire->getGestionnaireForAdmin();


        if (!empty($gestionnaires)) {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' =>$gestionnaires]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "liste des gestionnaires vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
        
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

// But : Retrouver personnel d'un centre ou de tous (admin)
// Peut classer par 3 (gCentre) ou 4 (formateur) ou "all"
// // Rôles:  Admin, gCentre, Formateur
$app->get('/api/centre/{id_centre}/personnel/{id_role}', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $user = new User();
    $user->id = $token['id'];
    $user->id_role = intval($token['role']);

    try{
        $selected_centre = "all";
        switch ($user->id_role){
            case 1:
                if(filter_var($param['id_centre'], FILTER_VALIDATE_INT)){
                    $selected_centre = $param['id_centre'];
                }
                break;
            case 3:
            case 4:
                $selected_centre = $user->searchIdCentreForIdUsers();
                if(empty($selected_centre) || filter_var($selected_centre, FILTER_VALIDATE_INT) == false){
                    throw new Exception("Impossible de retrouver le centre de l'utilisateur");
                }
                break;
        }

        $selected_role = in_array($param['id_role'], [3,4]) ? $param['id_role'] : "all";

        $result = [];

        $roles = [
            3 => ['class' => 'GestionnaireCentre', 'method' => 'getGestionnairesByCentre', 'key' => 'gestionnaires_centre'],
            4 => ['class' => 'Formateur', 'method' => 'getFormateursByCentre', 'key' => 'formateurs']
        ];
        
        foreach ($roles as $role => $data) {
            if ($selected_role == "all" || $selected_role == $role) {
                $object = new $data['class']();
                $object->id_centres_de_formation = $selected_centre;
                $result[$data['key']] = $object->{$data['method']}();
            }
        }

        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Exception $e){
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth)->add($check_role([1,3,4]));