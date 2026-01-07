<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/GestionnaireEntrepriseModel.php';
require_once __DIR__.'/../../models/EntrepriseModel.php';
require_once __DIR__.'/../../models/UserModel.php';

//========================================================================================
// But : Permet de recuperer le profil d'un gestionnaire d'entreprise
// Rôles : admin
// param: user_id
//========================================================================================

$app->get('/api/gestionnaire-entreprise/{user_id}/profil', function (Request $request, Response $response, $param) use ($key) {

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

        $gestionnaire = new GestionnaireEntreprise(); 
        $gestionnaire->id_users = $param['user_id'];

        if($gestionnaire->boolId() === false){
            $response->getBody()->write(json_encode(['erreur' => "Cet utilisateur n'existe pas"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $entreprise = new Entreprise();

        $gestionnaire->id_users = $param['user_id'];
        
            //récupération de l'id entreprise
        $gestionnaireDatas = $gestionnaire->getProfilGestionnaireEntreprise();
        $idEntreprise = $gestionnaireDatas[0]['id_entreprises'];

        $entreprise->id=$idEntreprise;
        $entrepriseDatas = $entreprise->getEntrepriseDatas();

        $profilDatas = [
            'gestionnaireDatas'=>$gestionnaireDatas,
            'entrepriseDatas'=>$entrepriseDatas,
        ];

        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $profilDatas]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

    }else{
        $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits suffisants"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

})->add($auth);

$app->get('/api/getGestionnaireEntreprise', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];


    if ($role === 1 ) {
        $gestionnaire = new GestionnaireEntreprise();
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