                                                                                                                                                                                                                                                    <?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';
require_once __DIR__.'/../../models/FormationModel.php';

//========================================================================================
// But : Update une formation
// Rôles : Admins, gestionnaire de centre
// champs : nom, prix, lienFranceCompetence
//========================================================================================

$app->put('/formation/{formation_id}/update', function (Request $request, Response $response, $param) use ($key) {
    // Check => Format param
    if(empty($param['formation_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Le champ formation_id est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['formation_id'])) {
        $response->getBody()->write(json_encode(['erreur' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $formation = new Formation();
    $formation->id = $param['formation_id'];

    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data = $request->getParsedBody();

    // Check => GestionnaireCentre => Appartenance Formation
    if($role == 3){
        $gestionnaireCentre = new GestionnaireCentre();
        $gestionnaireCentre->id_users = $token['id'];
        $gestionnaireCentre_id_centre = $gestionnaireCentre->searchCentreForIdUsers();
        $formation_id_centre = $formation->searchCentreForId();
       
    
        if ($gestionnaireCentre_id_centre != $formation_id_centre){
            $response->getBody()->write(json_encode(['error' => "Vous n'avez pas accès aux informations de cette formation"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // Check => Intégrité formulaire
    if(!isset($data['nom'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Nom doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['prix'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Prix doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['lienFranceCompetence'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ Lien France Compétence doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check => Remplissage champs
    $changedNom = 0;
    $changedPrix = 0;
    $changedLien = 0;

    $og_infos = $formation->searchForId();

    if(!empty($data['nom']) && ($data['nom'] != $og_infos['nom'])){
        $changedNom = 1;
        $formation->nom = $data['nom'];
        $formation->updateNom();
    }

    if(!empty($data['prix']) && ($data['prix'] != $og_infos['prix'])){
        $changedPrix = 1;
        $formation->prix = $data['prix'];
        $formation->updatePrix();
    }

    if(!empty($data['lienFranceCompetence']) && ($data['lienFranceCompetence'] != $og_infos['lienFranceCompetence'])){
        $changedLien = 1;
        $formation->lienFranceCompetence = $data['lienFranceCompetence'];
        $formation->updateLien();
    }

    // Check => Zero changement
    if($changedNom == 0 &&
    $changedPrix == 0 &&
    $changedLien == 0){
        $response->getBody()->write(json_encode(['erreur' => "Veuillez changer au moins une donnée"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Succes
    $response->getBody()->write(json_encode(['valid' => "La formation a bien été mise à jour"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

})->add($auth)->add($checkAdminGestionnaireCentre);