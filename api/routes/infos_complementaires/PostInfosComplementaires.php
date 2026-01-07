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
// But : Envoyer un message dans infos complémentaires de 1 étudiant
// Rôles : Admins, gestionnaire de centre, formateurs
// champs: 'objet','contenu','id_users_etudiants','id_types_infos'
//========================================================================================

$app->post('/api/etudiant/infos_complementaires/send', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $user = new User();
    $user->id = $token['id'];
    $user->id_role = intval($token['role']);
    $data = $request->getParsedBody();

    try {
        $infos_complementaires = new InfosComplementaires();

        $etudiant = new Etudiant();
        $etudiant->id = $data['id_etudiants'];
        $etudiant->id_users = $etudiant->returnIdUsers();
        $etudiant_exist = $etudiant->boolIdUsers();
        if($etudiant_exist == false) {
            throw new Exception("L'étudiant sélectionné n'existe pas");
        }

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

        // Remplissage champs
        $infos_complementaires->objet = $data['objet'];
        $infos_complementaires->contenu = $data['contenu'];
        $infos_complementaires->id_etudiants = $etudiant->id;
        $infos_complementaires->id_users = $user->id;
        $infos_complementaires->id_types_infos = $data['id_types_infos'];

        $result = $infos_complementaires->add();
        if($result === true){
            $response->getBody()->write(json_encode(['valid' => "Le message a bien été envoyé"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201); 
        } else {
            throw new Exception("Erreur dans l'ajout du message");
        }  
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    } 
})->add($auth)->add($check_role([1,3,4]))->add($check_required(['objet','contenu','id_etudiants','id_types_infos']));