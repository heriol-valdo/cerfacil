<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/PointageModel.php';
require_once __DIR__.'/../../models/PointagesinfoModel.php';


//========================================================================================
// But : Ajouter un pointage / dépointage
// Rôles : Etudiant
// champs : Aucun
//========================================================================================

$app->post('/api/pointage/add', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    $pointage = new Pointage();
    $pointageInfo = new PointagesInfo();

    // Check => Si étudiant a pointé ou pas
    $pointage->id_etudiants = $token['idByRole']->id;
    $has_pointed = $pointage->boolPointed();

    if($has_pointed){
        $depointed = $pointage->disablePreviousPointage();
        if($depointed == false){
            $response->getBody()->write(json_encode(['erreur' => "Erreur dans le dépointage"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }



    // Si a pointé : dépointer
    // Si non pointé : pointer
    $pointage->entree_sortie = $has_pointed ? 0 : 1; // 1 = entrée, 0 = sortie
    $pointage->is_pointed = $has_pointed ? 0 : 1;
    
    $lastInsertedId = $pointage->addPointage();



    if($lastInsertedId){
        // Ajouter les informations de pointage supplémentaires dans la table pointages_infos
        $pointageInfo->id_pointages = $lastInsertedId;
        $id_etudiants = $token['idByRole']->id;
        $result = $pointageInfo->addPointage($id_etudiants);

        if ($result) {
            if($pointage->is_pointed == 1){
                $response->getBody()->write(json_encode(['valid' => "Vous avez pointé avec succès"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
            } else {
                $response->getBody()->write(json_encode(['valid' => "Vous avez dépointé avec succès"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
            }
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Il y a eu une erreur dans le pointage, veuillez contacter un administrateur"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
       
        
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Il y a eu une erreur dans le pointage, veuillez contacter un administrateur"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($checkEtudiant);

