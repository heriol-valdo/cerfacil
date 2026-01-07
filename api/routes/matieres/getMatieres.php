<?php // /routes/equipement/getCours.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/MatiereModel.php';
require_once __DIR__.'/../../models/SessionModel.php';
require_once __DIR__.'/../../models/EtudiantModel.php';
require_once __DIR__.'/../../models/FormateursParticipantSessionModel.php';
require_once __DIR__.'/../../models/UserModel.php';


$app->get('/api/{sessionId}/matieres', function (Request $request, Response $response, $args) {
    $sessionId = $args['sessionId'];
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userId = $token['id'];

    try {
        // Vérification que sessionId est un entier positif
        if (!filter_var($sessionId, FILTER_VALIDATE_INT) || $sessionId <= 0) {
            throw new Exception("ID de session invalide");
        }

        $session = new Session();
        $session->id = $sessionId;

        // Vérification de l'existence de la session
        if (!$session->boolId()) {
            throw new Exception("La session spécifiée n'existe pas");
        }

        // Vérification des droits d'accès
        if ($role == 5) { // Étudiant
            $etudiant = new Etudiant();
            $etudiant->id_users = $userId;
            $etudiantData = $etudiant->searchForId();
            if (!$etudiantData || $etudiantData['id_session'] != $sessionId) {
                throw new Exception("Vous n'avez pas accès à cette session");
            }
        } elseif ($role == 3) { 
            $centreDeLaSession = $session->getCentreForId();
            if ($centreDeLaSession['id_centres_de_formation'] != $token['centre']) {
                throw new Exception("Vous n'avez pas accès à cette session");
            }
        } elseif ($role == 4) {
            $formateurParticipantSession = new FormateursParticipantSession();
            $formateurParticipantSession->id = $sessionId;
            $formateurParticipantSession->id_formateurs = intval($token['idByRole']);
        
            if (!$formateurParticipantSession->checkIfFormateurParticipantSessionExist()) {
                throw new Exception("Vous n'avez pas accès à cette session");
            }
        }

        $matieres = new Matiere();
        $matieresList = $matieres->getMatieresForSession($sessionId);

        if (empty($matieresList)) {
            $response->getBody()->write(json_encode(['message' => "Aucune matière trouvée pour cette session"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }

        $response->getBody()->write(json_encode(['data' => $matieresList]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth);

$app->get('/api/matieres/sessions', function (Request $request, Response $response) {
    $queryParams = $request->getQueryParams();
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userId = $token['id'];

    try {
        // Vérifie si le paramètre 'sessions' existe et n'est pas vide
        if (!isset($queryParams['sessions']) || empty($queryParams['sessions'])) {
            throw new Exception("Le paramètre 'sessions' est requis et ne peut pas être vide");
        }

        $sessionIds = explode(',', $queryParams['sessions']);
        $validSessionIds = [];

        foreach ($sessionIds as $sessionId) {
            if (!filter_var($sessionId, FILTER_VALIDATE_INT) || $sessionId <= 0) {
                throw new Exception("L'ID de session '$sessionId' n'est pas valide");
            }
            $validSessionIds[] = intval($sessionId);
        }

        // Vérification des droits d'accès pour chaque session
        foreach ($validSessionIds as $sessionId) {
            $session = new Session();
            $session->id = $sessionId;

            if (!$session->boolId()) {
                throw new Exception("La session $sessionId n'existe pas");
            }

            if ($role == 5) { // Étudiant
                $etudiant = new Etudiant();
                $etudiant->id_users = $userId;
                $etudiantData = $etudiant->searchForId();
                if (!$etudiantData || $etudiantData['id_session'] != $sessionId) {
                    throw new Exception("Vous n'avez pas accès à la session $sessionId");
                }
            } elseif ($role == 3) {
                $centreDeLaSession = $session->getCentreForId();
            
                if ($centreDeLaSession['id_centres_de_formation'] != $token['centre']) {
                    throw new Exception("Vous n'avez pas accès à la session $sessionId");
                }
            } elseif ($role == 4) {
                $formateurParticipantSession = new FormateursParticipantSession();
                $formateurParticipantSession->id = $sessionId;
                $formateurParticipantSession->id_formateurs = intval($token['idByRole']);
            
                if (!$formateurParticipantSession->checkIfFormateurParticipantSessionExist()) {
                    throw new Exception("Vous n'avez pas accès à la session $sessionId");
                }
            }
        }

        $matieres = new Matiere();
        $matieresList = $matieres->getMatieresForMultipleSessions($validSessionIds);

        if (empty($matieresList)) {
            $response->getBody()->write(json_encode(['message' => "Aucune matière trouvée pour ces sessions"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }

        $response->getBody()->write(json_encode(['data' => $matieresList]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth);


$app->get('/api/centre/{centreId}/matieres', function (Request $request, Response $response, $param) {
    $token = $request->getAttribute('user');
    $user = new User();
    $user->id = $token['id'];
    $user->id_role = $token['role'];

    try {
        $centre = new CentreFormation();
        switch($user->id_role){
            case 1: // Admin
                if(!isset($param['centreId']) || !filter_var($param['centreId'], FILTER_VALIDATE_INT)){
                    throw new Exception("Paramètre au mauvais format");
                }
                $centre->id = $param['centreId'];
                $centre_exist = $centre->boolId();

                if($centre_exist == false){
                    throw new Exception("Le centre sélectionné n'existe pas");
                }
                break;

            case 3: // Gestionnaire Centre
            case 4: // Formateur
                $centre->id = $user->searchIdCentreForIdUsers();
                break;
        }

        $sessions = $centre->getSessionsFromCentre();
        $matieres = $centre->getMatieresFromCentre();

        $result = [];

        foreach ($sessions as $session) {
            $matieresForSession = array_filter($matieres, function($matiere) use ($session) {
                return $matiere['id_sessions'] == $session['id'];
            });

            $result[] = [
                'session' => $session,
                'matieres' => $matieresForSession // Corrected variable name
            ];
        }


// Now $formationsWithMatieres contains formations grouped with their respective matieres

        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($check_role([1,3,4]));