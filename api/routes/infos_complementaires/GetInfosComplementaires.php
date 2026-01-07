<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/EtudiantModel.php';
require_once __DIR__.'/../../models/InfosComplementairesModel.php';

//========================================================================================
// But : Récupérer infos complémentaires de 1 étudiant
// Rôles : Admins, gestionnaires de centre, formateurs
//========================================================================================

$app->get('/api/etudiant/{id_users_etudiants}/infos_complementaires', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $user = new User();
    $user->id = $token['id'];
    $user->id_role = intval($token['role']);

    try {
        if(filter_var($param['id_users_etudiants'], FILTER_VALIDATE_INT) == false) {
            throw new Exception("Paramètre au mauvais format");
        }
        $etudiant = new Etudiant();
        $etudiant->id_users = $param['id_users_etudiants'];
        $etudiant_infos = $etudiant->infos();
        if(empty($etudiant_infos)){
            throw new Exception("Impossible de récupérer le profil de l'étudiant");
        }

        $infos_complementaires = new InfosComplementaires();
        $infos_complementaires->id_etudiants = $etudiant_infos[0]['id'];

        $target = new User();
        $target->id = $etudiant->id_users;
        $target->id_role = $target->searchIdRoleForIdUsers();
        if($target->id_role != 5){
            throw new Exception("L'utilisateur sélectionné n'est pas un étudiant");
        }

        switch ($user->id_role){
            case 3:
            case 4: 
                $user_id_centre = $user->searchIdCentreForIdUsers();
                $target_id_centre = $target->searchIdCentreForIdUsers();
                if($user_id_centre != $target_id_centre){
                    throw new Exception("Accès interdit");
                }
                break;
        }

        $result['etudiant'] = $etudiant_infos;
        $result['messages'] = !empty($infos_complementaires->getInfos()) ? $infos_complementaires->getInfos() : [];
        
        $response->getBody()->write(json_encode(['valid' => "Récupération des messages réussie", 'data' => $result]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);    
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($check_role([1,3,4]));