<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/SessionModel.php';
require_once __DIR__.'/../../models/FormationModel.php';

//========================================================================================
// But : Récupérer les sessions en cours d'un centre de formation
// Rôles : Admins, gestionnaire de centre
// champs: idCentre
//========================================================================================

$app->post('/api/addSession', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $parsedBody = $request->getParsedBody();

    try {
        $requiredFields = ['dateDebut', 'dateFin', 'nom', 'nbPlace', 'id_formations', 'id_centres_de_formation', 'id_formateurs']; 
        foreach ($requiredFields as $field) {
            if (!isset($parsedBody[$field]) || empty($parsedBody[$field])) {
               throw new Exception('Certains champs obligatoires sont vides');
            }
        }

        $session = new Session();
        $session->dateDebut = $parsedBody['dateDebut'];
        $session->dateFin = $parsedBody['dateFin'];
        $session->nomSession = $parsedBody['nom'];
        $session->nbPlace = $parsedBody['nbPlace'];
        $session->id_formations = $parsedBody['id_formations'];
        $session->id_centres_de_formation = $parsedBody['id_centres_de_formation'];

        $session_id = $session->addSession();
        if(empty($session_id)){
            throw new Exception('Erreur lors de la création de la session');
        }

        $participant = new FormateursParticipantSession();
        $participant->id = $session_id;
        $participant->id_formateurs = $parsedBody['id_formateurs'];
        $result = $participant->postFormateurParticipantSession();
        if($result == false){
            $session->id = $session_id;
            $session->deleteSession();
            throw new Exception("Erreur lors de l'ajout du référent pédagogique");
        } else {
            $response->getBody()->write(json_encode(['valid' => 'Vous avez ajouté une session']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    } 
})->add($auth)->add($check_role([1,3]));

