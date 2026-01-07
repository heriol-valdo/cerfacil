<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;



require_once __DIR__.'/../../models/ProduitCerfaModel.php';


$app->put('/api/produitCerfaUpdate', function (Request $request, Response $response, $param) use ($key) {  
    $produitCerfa = new Produit();
    $data = $request->getParsedBody();
    // Check => Format param
    if(!isset($data['id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $produitCerfa->id = $data['id'];
   

    // Si le produit  existe
    if(!($produitCerfa->boolId())){
        $response->getBody()->write(json_encode(['erreur' => "Le produit  n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

   


    // Reconnaissance si changement effectué
    $changedNom = 0;
    $changedType = 0;
    $changedPrixDossier = 0;
    $changedPrixAbonement = 0;
    $changedCaracteristique1 = 0;
    $changedCaracteristique2 = 0;
    $changedCaracteristique3 = 0;
    $changedCaracteristique4 = 0;


    $og_role = $produitCerfa->getProduitCerfaDatasById();

   
    $og_nom = $og_role['nom'];
    $og_type = $og_role['type'];
    $og_prix_dossier = $og_role['prix_dossier'];
    $og_prix_abonement = $og_role['prix_abonement'];
    $og_caracteristique1 = $og_role['caracteristique1'];
    $og_caracteristique2 = $og_role['caracteristique2'];
    $og_caracteristique3 = $og_role['caracteristique3'];
    $og_caracteristique4 = $og_role['caracteristique4'];
   

    
    if(isset($data['newNom']) && !empty($data['newNom'])  && ($data['newNom'] != $og_nom)) {
        $changedNom = 1;
        $produitCerfa->nom = $data['newNom'];
    }
    if(isset($data['newType']) && !empty($data['newType'])  && ($data['newType'] != $og_type)) {
        $changedType = 1;
        $produitCerfa->type = $data['newType'];
    }
    if(isset($data['newPrixDossier']) && !empty($data['newPrixDossier'])  && ($data['newPrixDossier'] != $og_prix_dossier)) {
        $changedPrixDossier = 1;
        $produitCerfa->prix_dossier = $data['newPrixDossier'];
    }
    if(isset($data['newPrixAbonement'])   && ($data['newPrixAbonement'] != $og_prix_abonement)) {
        $changedPrixAbonement = 1;
        $produitCerfa->prix_abonement = $data['newPrixAbonement'];
    }
    if(isset($data['newCaracteristique1']) && !empty($data['newCaracteristique1'])  && ($data['newCaracteristique1'] != $og_caracteristique1)) {
        $changedCaracteristique1 = 1;
        $produitCerfa->caracteristique1 = $data['newCaracteristique1'];
    }
    if(isset($data['newCaracteristique2']) && !empty($data['newCaracteristique2'])  && ($data['newCaracteristique2'] != $og_caracteristique2)) {
        $changedCaracteristique2 = 1;
        $produitCerfa->caracteristique2 = $data['newCaracteristique2'];
    }
    if(isset($data['newCaracteristique3'])   && ($data['newCaracteristique3'] != $og_caracteristique3)) {
        $changedCaracteristique3 = 1;
        $produitCerfa->caracteristique3 = $data['newCaracteristique3'];
    }
    if(isset($data['newCaracteristique4'])   && ($data['newCaracteristique4'] != $og_caracteristique4)) {
        $changedCaracteristique4 = 1;
        $produitCerfa->caracteristique4 = $data['newCaracteristique4'];
    }

    
  
    // Si aucun changement >> erreur
    if($changedNom == 0 &&
    $changedType == 0 &&
    $changedPrixDossier == 0 &&
    $changedPrixAbonement == 0 &&
    $changedCaracteristique1 == 0 &&
    $changedCaracteristique2 == 0 &&
    $changedCaracteristique3 == 0 &&
    $changedCaracteristique4 == 0
    ){
        $response->getBody()->write(json_encode(['erreur' => "Veuillez modifier au moins une donnée"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    if(($produitCerfa->boolType())){
        $response->getBody()->write(json_encode(['erreur' => "un produit avec ce type existe deja"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $changedNom && $produitCerfa->updateNom();
    $changedType && $produitCerfa->updateType();
    $changedPrixDossier && $produitCerfa->updatePrixDossier();
    $changedPrixAbonement && $produitCerfa->updatePrixAbonement();
    $changedCaracteristique1 && $produitCerfa->updateCaracteristique1();
    $changedCaracteristique2 && $produitCerfa->updateCaracteristique2();
    $changedCaracteristique3 && $produitCerfa->updateCaracteristique3();
    $changedCaracteristique4 && $produitCerfa->updateCaracteristique4();
   


    $response->getBody()->write(json_encode(['valid' => "Le produit cerfa a été mis à jour"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth);