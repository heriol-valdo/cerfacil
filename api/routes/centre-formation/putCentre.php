<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';
require_once __DIR__.'/../../models/EntrepriseModel.php';

//========================================================================================
// But : Ajouter un nouveau centre de formation
// Rôles : Admins
// champs possibles : newNom, newAdresse, newCodePostal, newVille, idEntrepriseCentre, telephoneCentre
//========================================================================================

$app->put('/api/admin/centreFormation/{centre_id}/update', function (Request $request, Response $response, $param) use ($key) {  
    $centreFormation = new CentreFormation();
    $centreFormation->id = $param['centre_id'];

    // Check => Format param
    if(empty($param['centre_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le param centre_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['centre_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    
    // Check si le centre existe
    $centreExist = $centreFormation->boolId();
    if(!$centreExist){
        $response->getBody()->write(json_encode(['erreur' => "Le centre sélectionné n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $data = $request->getParsedBody();
    $og_infos = $centreFormation->searchForId();

    $og_nom = $og_infos['nomCentre'];
    $og_adresse = $og_infos['adresseCentre'];
    $og_codePostalCentre = $og_infos['codePostalCentre'];
    $og_ville = $og_infos['villeCentre'];
    $og_telephone = $og_infos['telephoneCentre'];
    $og_entreprise = $og_infos['id_entreprises'];

    $changedNomCentre = 0;
    $changedAdresseCentre = 0;
    $changedCodePostalCentre = 0;
    $changedVilleCentre = 0;
    $changedTelephoneCentre = 0;
    $changedEntreprise = 0;

    if(isset($data['newNom']) && !empty($data['newNom']) && ($og_nom != $data['newNom'])) {
        $changedNomCentre = 1;
        $centreFormation->nomCentre = $data['newNom'];
    } else {
        $centreFormation->nomCentre = $og_nom;
    }
    
    if(isset($data['newAdresse']) && !empty($data['newAdresse']) && ($og_adresse != $data['newAdresse'])) {
        $changedAdresseCentre = 1;
        $centreFormation->adresseCentre = $data['newAdresse'];
    } else {
        $centreFormation->adresseCentre = $og_adresse;
    }

    if(isset($data['newCodePostal']) && !empty($data['newCodePostal']) && ($og_codePostalCentre != $data['newCodePostal'])) {
        $changedCodePostalCentre = 1;
        $centreFormation->codePostalCentre = $data['newCodePostal'];
    } else {
        $centreFormation->codePostalCentre = $og_codePostalCentre;
    }

    if(isset($data['newVille']) && !empty($data['newVille'])  && ($og_ville != $data['newVille'])) {
        $changedVilleCentre = 1;
        $centreFormation->villeCentre = $data['newVille'];
    } else {
        $centreFormation->villeCentre = $og_ville;
    }

    if(isset($data['newTelephone']) && !empty($data['newTelephone'])  && ($og_telephone != $data['newTelephone'])) {
        $changedTelephoneCentre = 1;
        $centreFormation->telephoneCentre = $data['newTelephone'];
    } else {
        $centreFormation->telephoneCentre = $og_telephone;
    }

    if(isset($data['newEntreprise']) && !empty($data['newEntreprise'])  && ($og_entreprise != $data['newEntreprise'])) {
        $entreprise = new Entreprise;
        $entreprise->id = $data['newEntreprise'];
        $entrepriseExist = $entreprise->boolId();

        // Check si l'entreprise existe
        if($entrepriseExist){
            $changedEntreprise = 1;
            $centreFormation->id_entreprises = $data['newEntreprise'];
        } else {
            $response->getBody()->write(json_encode(['erreur' => "L'entreprise sélectionnée n'existe pas"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        
    } else {
        $centreFormation->id_entreprises = $og_entreprise;
    }

    // Check si il y a eu au moins un changement
    if($changedNomCentre == 0 &&
    $changedAdresseCentre == 0 &&
    $changedCodePostalCentre == 0 &&
    $changedVilleCentre == 0 &&
    $changedTelephoneCentre == 0 &&
    $changedEntreprise == 0){
        $response->getBody()->write(json_encode(['erreur' => "Veuillez modifier au moins une information"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Si il y a eu un changement >>> update
    $changedNomCentre && $centreFormation->updateNom();
    $changedAdresseCentre && $centreFormation->updateAdresse();
    $changedCodePostalCentre && $centreFormation->updateCodePostal();
    $changedVilleCentre && $centreFormation->updateVille();
    $changedTelephoneCentre && $centreFormation->updateTelephone();
    $changedEntreprise && $centreFormation->updateEntreprise();


    $response->getBody()->write(json_encode(['valid' => "Le centre de formation a été mis à jour"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth)->add($checkAdmin);