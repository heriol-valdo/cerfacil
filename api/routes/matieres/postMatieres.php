<?php // /routes/equipement/getCours.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/MatiereModel.php';
require_once __DIR__.'/../../models/SessionModel.php';
require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/EtudiantModel.php';
require_once __DIR__.'/../../models/FormateursParticipantSessionModel.php';
require_once __DIR__.'/../../models/FormationModel.php';

//========================================================================================
// But : Permet d'ajouter une salle à un centre
// Rôles : admin, gestionnaireCentre
// Champs obligatoires : id_centres_de_formation, nom
// Champs facultatifs : capacite_accueil
//========================================================================================
$app->post('/api/matieres/add', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $user = new User();
    $user->id = $token['id'];
    $user->id_role = intval($token['role']);

    $data = $request->getParsedBody();

    try {
        // Vérification des champs requis
        if (!isset($data['matiere_nom']) || !isset($data['id_sessions'])) {
            throw new Exception("Les champs matiere_nom et id_sessions sont requis");
        }

        // Vérification de l'existence de la session
        $session = new Session();
        $session->id = $data['id_sessions'];
        if (!$session->boolId()) {
            throw new Exception("La session spécifiée n'existe pas");
        }

        switch ($user->id_role){
            case 3:
            case 4:
                $user_id_centre = $user->searchIdCentreForIdUsers();
                $session_id_centre = $session->getCentreForId()["id_centres_de_formation"];
                if($session_id_centre != $user_id_centre){
                    throw new Exception("Vous n'avez pas les droits pour ajouter une matière à cette session");
                }
                break;
        }

        $matiere = new Matiere();
        $matiere->matiere_nom = $data['matiere_nom'];
        $matiere->id_sessions = $data['id_sessions'];
        $matiere->id_formations = $data['id_formations'] ?? null;
        
        if($matiere->add()){
            $response->getBody()->write(json_encode(['valid' => 'Vous avez ajouté une matière']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            throw new Exception("Erreur dans l'ajout de la matière");
        }
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($check_role([1,3,4]));