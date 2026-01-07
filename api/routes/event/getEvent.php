<?php // /routes/equipement/getEvent.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/EventModel.php';
require_once __DIR__.'/../../models/EventSessionsModel.php';
require_once __DIR__.'/../../models/EventUsersModel.php';
require_once __DIR__.'/../../models/CoursModel.php';

require_once __DIR__.'/../../models/UserModel.php';
require_once __DIR__.'/../../models/FormateurModel.php';
require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/SessionModel.php';

//========================================================================================
// But : Récupérer les events selon un utilisateur
// Rôles : Tous
// param : byUserId (valeur par défaut : default : si par défaut recherche pour soi-même)
//========================================================================================
$app->get('/api/event/liste/user/{byUserId}', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    $byUserId = $token['id'];

    if (filter_var($param['byUserId'], FILTER_VALIDATE_INT) && $role != 5) {
        $byUserId = $param['byUserId'];

        // Si on recherche un autre utilisateur que soit, on recherche le centre de l'user
        $logged_user = new User();
        $logged_user->id = $token['id'];

        $logged_user->id_role = $role;
        switch($logged_user->id_role){
            case 3: // Gestionnaire Centre
            case 4: // Formateur
                $logged_centre = $logged_user->searchIdCentreForIdUsers()['id_centres_de_formation'];
                break;
            case 2: // Gestionnaire Entreprise
            case 6: // Financeur
                $logged_entreprise = $logged_user->searchIdEntrepriseForIdUsers()['id_entreprises'];
                break;
        }
    }

    $searched_user = new User();
    $searched_user->id = $byUserId;

    // Check : Existence user recherché
    if($searched_user->boolId() == false){
        $response->getBody()->write(json_encode(['erreur' => "L'utilisateur recherché n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $searched_user->id_role = $searched_user->searchIdRoleForIdUsers();

    // Récupération des structures de la personne recherchée
    $searched_user_structure = $searched_user->returnStructure()[0];

    $searched_user_entreprise = !empty($searched_user_structure['id_entreprises']) ? $searched_user_structure['id_entreprises'] : null;
    $searched_user_centre = !empty($searched_user_structure['id_centres_de_formation']) ? $searched_user_structure['id_centres_de_formation'] : null;
    $searched_user_financeur_entreprise = !empty($searched_user_structure['financeur_id_entreprises']) ? $searched_user_structure['financeur_id_entreprises'] : null;

    switch($logged_user->id_role){ // Check : Si user logged a accès à l'user recherché en comparant les structures associées
        case 2: // Gestionnaire entreprise : check si id_entreprises présent
            if(empty($logged_entreprise) || empty($searched_user_entreprise) || $logged_entreprise != $searched_user_entreprise){
                $response->getBody()->write(json_encode(['erreur' => "L'utilisateur recherché ne fait pas partie de votre structure"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
            break;
        case 3: // Gestionnaire centre : check id_centres
        case 4: // Formateur : check id_centres 
            if(empty($logged_centre) || empty($searched_user_centre) || $logged_centre != $searched_user_centre){
                $response->getBody()->write(json_encode(['erreur' => "L'utilisateur recherché ne fait pas partie de votre structure"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
            break;
        case 6: // Financeur : check id_entreprises du financeur associé
            if(empty($logged_entreprise) || empty($searched_user_financeur_entreprise) || $logged_entreprise != $searched_user_financeur_entreprise){
                echo "logged_entreprise : $logged_entreprise";
                echo "searched_user_financeur_entreprise : $searched_user_financeur_entreprise";

                $response->getBody()->write(json_encode(['erreur' => "L'utilisateur recherché ne fait pas partie de votre structure"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
            break;
    }

    $events_sessions_participated = [];

    switch($searched_user->id_role){
        case 3:
            break;
        case 4: 
        case 5:
            // On recherche les sessions auxquelles on participe
            $sessions_participated = $searched_user->searchSessionsParticipated()[0];
            $event_sessions = new EventSessions();
            if(is_array($sessions_participated)){
                $sessions_ids = is_array($sessions_participated['id_session']) 
                ? $sessions_participated['id_session'] 
                : [$sessions_participated['id_session']];
                // On récupère les events des sessions participées
                foreach($sessions_ids AS $unique_session){
                    $event_sessions->id = $unique_session;
                    $events_for_session = $event_sessions->getEventsForIdSession();

                    if (is_array($events_for_session)) {
                        $events_sessions_participated = array_merge($events_sessions_participated, $events_for_session);
                    }
                }  
            } 
            break;
        default:
            $response->getBody()->write(json_encode(['erreur' => "L'utilisateur n'a pas de planning"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            break;
    }

    $event = new Event();
    // Récupération des events où l'user cherché est l'auteur
    $event->id_users = $searched_user->id;
    $events_authored = $event->getEventsForIdUsers();

    $event->id_centres_de_formation = $searched_user_centre;
    $events_public = $event->getPublicEventsForIdCentre();

    $event_users = new EventUsers();
    // Récupération des events où l'user cherché est participant
    $event_users->id_users = $searched_user->id;
    $events_user_participated = $event_users->getEventsForIdUsers();

    // Check avant de merge
    $events_public = array_filter($events_public ?? []);
    $events_authored = array_filter($events_authored ?? []);
    $events_sessions_participated = array_filter($events_sessions_participated ?? []);
    $events_user_participated = array_filter($events_user_participated ?? []);

    $merged_events = array_merge($events_public, $events_authored, $events_sessions_participated, $events_user_participated);

    $filtered_events = [];
    foreach ($merged_events as $event) {
        if (!in_array($event['id'], $eventIds)) {
            $filtered_events[] = $event;
            $eventIds[] = $event['id'];
        }
    }

    foreach ($filtered_events as &$event) {
        $author = new User();
        $author->id = $event['id_users'];
        $event['author_details'] = $author->getAuthorEvent();

        if ($event['id_types_event'] == 2) {
            $cours = new Cours();
            $cours->id_events = $event['id'];
            $coursDetails = $cours->getCoursForIdEvents();
            $event['cours_details'] = $coursDetails;
        }
    }

    $result = $filtered_events;

    if(empty($result)){
        $response->getBody()->write(json_encode(['erreur' => "Aucun résultat trouvé"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);  
    } else {
        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);  
    }
    
})->add($auth);



//========================================================================================
// But : Récupérer les events selon une session
// Rôles : Admins, gestionnaire de centre, formateur
// param obligatoire : bySessionId (int)
//========================================================================================
$app->get('/api/event/liste/session/{bySessionId}', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    if(filter_var($param['bySessionId'], FILTER_VALIDATE_INT) == false){
        $response->getBody()->write(json_encode(['erreur' => "Le paramètre entré est invalide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    $logged_user = new User();
    $logged_user->id = $token['id'];
    $logged_user->id_role = $role;

    $session = new Session();
    $session->id = $param['bySessionId'];
    $session_id_centre = $session->searchCentreForId();

    if($role == 3 || $role == 4){
        
        $logged_user_centre = $logged_user->searchIdCentreForIdUsers();
        if($session_id_centre != $logged_user_centre){
            $response->getBody()->write(json_encode(['erreur' => "La session n'existe pas dans votre centre"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }    
    }

    // Récupération des structures de la personne recherchée

    $events_sessions_participated = [];

    $event_sessions = new EventSessions();

    $event_sessions->id = $session->id;
    $events_for_session = $event_sessions->getEventsForIdSession();

    foreach ($events_for_session as &$event) {
        $author = new User();
        $author->id = $event['id_users'];
        $event['author_details'] = $author->getAuthorEvent();
        if ($event['id_types_event'] == 2) {
            $cours = new Cours();
            $cours->id_events = $event['id'];
            $coursDetails = $cours->getCoursForIdEvents();
            $event['cours_details'] = $coursDetails;
        }
    }

    $result = $events_for_session;

    if(empty($result)){
        $response->getBody()->write(json_encode(['erreur' => "Aucun résultat trouvé"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);  
    } else {
        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);  
    }
    
})->add($auth)->add($check_role([1,3,4]));


//========================================================================================
// But : Récupérer les infos d'un event
// Rôles : Tous
// param : id 
//========================================================================================
$app->get('/api/event/{id}/infos', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    try {
        if (!filter_var($param['id'], FILTER_VALIDATE_INT)) {
            throw new Exception("Paramètre invalide");
        }

        $event = new Event();
        $eventUsers = new EventUsers();
        $eventSessions = new EventSessions();

        $event->id = $param['id'];
        $eventUsers->id = $event->id;
        $eventSessions->id_events = $event->id;

        $result = [];
        $result = $event->infos();
        if(empty($result)){
            throw new Exception("L'évènement n'existe pas");
        }

        if($result['id_types_event'] == 2){
            $cours = new Cours();
            $cours->id_events = $event->id;

            $result_cours = $cours->getCoursForIdEvents();
            $result['cours'] = !empty($result_cours) ? $result_cours : [];
        }

        if (in_array($role, [3, 4])) { // Gestionnaire centre ou Formateur
            if ($result['id_centres_de_formation'] != $token['centre']) {
                throw new Exception("Accès refusé");
            }
        }
        
        $users = $eventUsers->getUsers();
        $sessions = $eventSessions->getSessions();
        
        $result['users'] = !empty($users) ? $users : [];
        $result['sessions'] = !empty($sessions) ? $sessions : [];

        // Vérifier si l'événement est un cours (id_types_event = 2)
        /*if ($result['id_types_event'] == 2) {
            $cours = new Cours();
            $cours->id_events = $event->id;
            $coursInfo = $cours->getCoursForIdEvents();
            if ($coursInfo) {
                $result['cours'] = [
                    'id_formateurs' => $coursInfo['id_formateurs'],
                    'id_matieres' => $coursInfo['id_matieres'],
                    'matiere_nom' => $coursInfo['matiere_nom'],
                    'formateur_firstname' => $coursInfo['formateur_firstname'],
                    'formateur_lastname' => $coursInfo['formateur_lastname'],
                    'formation_nom' => $coursInfo['formation_nom'],
                    'formation_id' => $coursInfo['formation_id']
                ];
            }
        }*/

        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);  
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($check_role([1,3,4,5]));