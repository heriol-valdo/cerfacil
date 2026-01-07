<?php // /routes/equipement/putEquipement.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ .'/../../models/EquipementModel.php';
require_once __DIR__."/../../models/SalleModel.php";
require_once __DIR__.'/../../models/GestionnaireCentreModel.php';


//========================================================================================
// But : Permet de modifier un équipement 
// Rôles : admin, gestionnaireCentre
// Champs obligatoires : newNom, newQuantite, newSalle
//========================================================================================
$app->put('/api/salle/{salle_id}/equipement/{equipement_id}/update', function (Request $request, Response $response, $param) use ($key) {  
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    // Check => Format param 1
    if(empty($param['salle_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ salle_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['salle_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check => Format param 2
    if(empty($param['equipement_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ equipement_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['equipement_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $equipement = new Equipement();
    $salle = new Salle();
    
    $equipement->id = $param['equipement_id'];
    $salle->id = $param['salle_id'];

    if(!($equipement->boolId())){
        $response->getBody()->write(json_encode(['erreur' => "L'équipement n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    if(!($salle->boolId())){
        $response->getBody()->write(json_encode(['erreur' => "La salle n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $data = $request->getParsedBody();

    $changedNom = 0;
    $changedQuantite = 0;
    //$changedSalle = 0;

    if((!isset($data['newNom']) && !isset($data['newQuantite'])) || (empty($data['newNom']) && empty($data['newQuantite']))){
        $response->getBody()->write(json_encode(['erreur' => "Veuillez faire au moins un changement"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check => Remplissage des champs et changement par rapport og_infos
    $og_infos = $equipement->searchAllForId();

    $og_nom = $og_infos[0]['nom'];
    $og_quantite = $og_infos[0]['quantite'];
    $og_salle = $og_infos[0]['id_salles'];

    // Check => GestionnaireCentre
    if($role == 3){
        $salle_id_centre = $salle->searchCentreForId();
        $gestionnaireCentre = new GestionnaireCentre();
        $gestionnaireCentre->id_users = $token['id'];
        $gestionnaireCentre_id_centre = $gestionnaireCentre->searchCentreForIdUsers();

        // Check => Appartenance Salle
        if($gestionnaireCentre_id_centre != $salle_id_centre){
            $response->getBody()->write(json_encode(['erreur' => "$gestionnaireCentre_id_centre La salle sélectionnée n'existe pas dans votre centre"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);    
        }
    }

    if(!empty($data['newNom']) && ($og_nom != $data['newNom'])){
        $changedNom = 1;
        $equipement->nom = $data['newNom'];
    } else {
        $changedNom = 0;
    }

    if(!empty($data['newQuantite']) && ($og_quantite != $data['newQuantite'])) {
        $changedQuantite = 1;
        $equipement->quantite = $data['newQuantite'];
    } else {
        $changedQuantite = 0;
    }


/*    if(!empty($data['newSalle']) &&  ($og_salle != $data['id_salles'])) {
        $salle->id = $data['newSalle'];
        if($salle->boolId()){
            $changedSalle = 1;
            $equipement->salle = $data['newSalle'];
        } else {
            $response->getBody()->write(json_encode(['erreur' => "La salle n'existe pas"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    } else {
        $changedSalle = 0;
    }*/

    // Check => 0 changement = Erreur
    if($changedNom == 0 && $changedQuantite == 0){
        $response->getBody()->write(json_encode(['erreur' => "Veuillez changer au moins une donnée"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Succès
    $changedNom && $equipement->updateNom();
    $changedQuantite && $equipement->updateQuantite();
    //$changedSalle && $equipement->updateSalle();
    
    $response->getBody()->write(json_encode(['valid' => "L'équipement a été mis à jour"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

})->add($auth)->add($checkAdminGestionnaireCentre);