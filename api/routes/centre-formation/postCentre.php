<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';

//========================================================================================
// But : Ajouter un nouveau centre de formation
// Rôles : Admins
// champs obligatoires : nomCentre
// champs facultatifs: adresseCentre, codePostalCentre, villeCentre, idEntrepriseCentre, telephoneCentre
//========================================================================================

$app->post('/api/admin/centreFormation/add', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    // Check si les champs sont remplis
    $data = $request->getParsedBody();

    $centreFormation = new CentreFormation();
    // Remplissage informations CentreFormation
    // - Champs obligatoires
    // -- nomCentre
    if(isset($data['nomCentre'])){
        if(!empty($data['nomCentre'])){
            $centreFormation->nomCentre = $data['nomCentre'];
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Le champ Nom du centre est vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);    
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nom du centre est obligatoire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // -- adresseCentre
    if(isset($data['adresseCentre'])){
        if(!empty($data['adresseCentre'])){
            $centreFormation->adresseCentre = $data['adresseCentre'];
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Le champ Adresse du centre est vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);    
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Le champ Adresse du centre est obligatoire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // -- codePostalCentre
    if(isset($data['codePostalCentre'])){
        if(!empty($data['codePostalCentre'])){
            $centreFormation->codePostalCentre = $data['codePostalCentre'];
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Le champ Code postal est vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);    
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Le champ Code postal est obligatoire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // -- villeCentre
    if(isset($data['villeCentre'])){
        if(!empty($data['villeCentre'])){
            $centreFormation->villeCentre = $data['villeCentre'];
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Le champ Ville est vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);    
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Le champ Ville est obligatoire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // -- idEntrepriseCentre
    if(isset($data['idEntrepriseCentre'])){
        if(!empty($data['idEntrepriseCentre'])){
            $centreFormation->id_entreprises = $data['idEntrepriseCentre'];
        } else {
            $response->getBody()->write(json_encode(['erreur' => "Veuillez sélectionner une entreprise rattachée au centre"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);    
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Le champ Entreprise rattachée est obligatoire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // - Champs facultatifs
    // -- telephoneCentre
    if(isset($data['telephoneCentre']) && !empty($data['telephoneCentre'])){
        $centreFormation->telephoneCentre = $data['telephoneCentre'];
    } else {
        $centreFormation->telephoneCentre = "";
    }

    // Succès
    $centreFormation->addCentre();
    $response->getBody()->write(json_encode(['valid' => "Le centre a bien été créé"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);    
})->add($auth)->add($checkAdmin);