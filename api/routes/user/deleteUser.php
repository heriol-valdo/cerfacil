<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/ClientCerfaModel.php';
require_once __DIR__.'/../../models/AbonnementCerfaModel.php';

//========================================================================================
// But : Supprimer un utilisateur
// Rôles : Admins
// param: user_id
//========================================================================================
$app->delete('/api/user/{user_id}/delete', function (Request $request, Response $response, $param) use ($key) {
    $token=$request->getAttribute('user');
    $id = $token['id'];
    $role =$token['role'];
    $idbyrole =$token['idByRole'];
    $data = $request->getParsedBody();

    try {
        if(empty($param['user_id']) || !preg_match('/^\d+$/', $param['user_id'])) {
            throw new Exception('Paramètre au mauvais format');
        }
        $user = new User(); 
        $user->id = $param['user_id'];
        
        $idExist = $user->boolId();
        if($idExist == false){
            throw new Exception("L'utilisateur n'existe pas");
        }

        $tableau = $user->searchForId();

        switch($role){ // Autorisations suppression
            case 1: // Admin
                $has_access_1 = true; // Admins
                $has_access_2 = true; // GEntreprise
                $has_access_ecole = true; // GCentre, Formateur, Etudiant
                $has_access_6 = true; // Financeur
                $has_access_7 = true; // Client cerfa
                break;
            
            case 3: // GCentre
                $has_access_1 = false; // Admins
                $has_access_2 = true; // GEntreprise
                $has_access_ecole = true; // GCentre, Formateur, Etudiant
                $has_access_6 = true; // Financeur
                $has_access_7 = false; // Client cerfa
                break;

            case 7:
                $has_access_1 = true; // Admins
                $has_access_2 = true; // GEntreprise
                $has_access_ecole = true; // GCentre, Formateur, Etudiant
                $has_access_6 = true; // Financeur
                $has_access_7 = true; // Client cerfa
                break;

            default:
                $has_access_1 = false; // Admins
                $has_access_2 = false; // GEntreprise
                $has_access_ecole = false; // GCentre, Formateur, Etudiant 
                $has_access_6 = false; // Financeur
                $has_access_7 = false; // Client cerfa
                break;
        }

        $logged_user = new User();
        $logged_user->id = $token['id'];
        $logged_user->id_role = $role;

        switch ($tableau['id_role']){
            case 1:
                if($has_access_1 == false){
                    throw new Exception("Vous n'avez pas les droits suffisants");
                };
                break;
            
            case 3:
            case 4:
            case 5:
                if($has_access_ecole == false){
                    throw new Exception("Vous n'avez pas les droits suffisants");
                }
                
                if(in_array($role,[3])){
                    $user->id_role = $tableau['id_role'];
                    $user_id_centre = $user->searchIdCentreForIdUsers();
                    $logged_id_centre = $logged_user->searchIdCentreForIdUsers();
                    if($logged_id_centre != $user_id_centre){
                        throw new Exception("Accès interdit");
                    }
                }
                break;

            case 7:
                if($has_access_7 == false){
                    throw new Exception("Vous n'avez pas les droits suffisants");
                }
                $clientcerfa = new ClientCerfa();
                $clientcerfa->id_users = $param['user_id'];
   
                $clientCerfatableau = $clientcerfa->searchForId();

                if($clientCerfatableau['roleCreation'] == 1){
                    $abonnementCerfa = new AbonnementCerfa();
                    $tableauabonnementCerfa =  $abonnementCerfa->searchType($param['user_id']);
                
                    if(!empty($tableauabonnementCerfa)){
                        throw new Exception("Cet utilisateur ne peut pas être supprimé car il est rattaché à un produit");
                    }
                }
                break;
        }
                /*if($clientCerfatableau['roleCreation'] != 1){
                    $clientcerfa->deleteUser($clientCerfatableau['id']);
                    $user->deleteUser();

                    $response->getBody()->write(json_encode(['valid' => 'Vous avez supprimé l\'utilisateur']));
                    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

                }else{
                    $abonnementCerfa = new AbonnementCerfa();
                    $tableauabonnementCerfa =  $abonnementCerfa->searchType($param['user_id']);

                
                    if(empty($tableauabonnementCerfa)){
                        $clientcerfa->deleteUser($clientCerfatableau['id']);
                        $user->deleteUser();
                        $response->getBody()->write(json_encode(['valid' => "Vous avez supprimé l\'utilisateur"]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                    }else{
                        $response->getBody()->write(json_encode(['erreur' => "cet utilisateur ne peut pas etre supprimer car il est rattacher a  un produit"]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                    }
                }*/

        if($user->deleteUser()){
            $response->getBody()->write(json_encode(['valid' => 'Vous avez supprimé l\'utilisateur']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            throw new Exception("Erreur lors de la suppression");
        }
    } catch (Exception $e){
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(40);
    }

})->add($auth)->add($check_role([1,3,7]));