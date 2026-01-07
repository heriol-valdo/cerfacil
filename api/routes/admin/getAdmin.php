<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

//========================================================================================
// But : Récupérer le profil d'un administrateur
// Rôles : Admins
// Champs :aucun
//========================================================================================

$app->get('/api/admin/profil', function (Request $request, Response $response) use ($key) {
    require_once __DIR__.'/../../models/AdminModel.php';

    $admin = new Admin(); 

    $token=$request->getAttribute('user');
    $id = $token['id'];
    $admin->id_users=$id;

    if($admin->getProfilAdmin()){
    
        $adminDatas = $admin->getProfilAdmin();
        $adminDatasFiltered = array_filter($adminDatas[0], function($value, $key) {
            return ($key !== 'id' && $key !== 'id_users' && $key !== 'id_role');
        }, ARRAY_FILTER_USE_BOTH);        
        
    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $adminDatasFiltered]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

    }else{
        $response->getBody()->write(json_encode(['erreur' => 'La récupération des données a échoué']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

})->add($auth)->add($checkAdmin);

$app->get('/api/getAdmin', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];


    if ($role === 1 ) {
        $admin = new Admin();
        $admins =$admin->getAdminForAdmin();


        if (!empty( $admins)) {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $admins]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "liste des administrateurs vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
        
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);