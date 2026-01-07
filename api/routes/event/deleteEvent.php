<?php // /routes/equipement/postEvent.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../models/EventModel.php';
require_once __DIR__ . '/../../models/EventSessionsModel.php';
require_once __DIR__ . '/../../models/EventUsersModel.php';

require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../models/FormateurModel.php';
require_once __DIR__ . '/../../models/GestionnaireCentreModel.php';
require_once __DIR__ . '/../../models/SessionModel.php';

//========================================================================================
// But : Supprimer une session (gCentre + Formateur, seulement celles qui ne sont pas encore passées)
// Rôles : administrateur, gestionnaire centre, formateur
// Champs obligatoires : 
// - (int) id
// - (str) range : default/all/after
// (default : supprime 1 event / all : supprime toutes les recurrences existantes / after : supprime toutes les récurrences après la date de l'event sélectionné incluse)

//========================================================================================
$app->delete('/api/event/{id}/delete/{range}', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $error_message = "";

    // Check => Paramètres
    $error_message .= empty($param['id'] || filter_var($param['id'], FILTER_VALIDATE_INT) === false) ? "Le paramètre ID est incorrect. " : "";
    $error_message .= empty($param['range']) || !in_array($param['range'],["default", "all", "after"]) ? "Le paramètre range est incorrect" : "";
    if(!empty($error_message)){
        $response->getBody()->write(json_encode(['erreur' =>  trim($error_message)]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $user = new User();
    $user->id = $token['id'];

    $event = new Event();
    $event->id = $param['id'];

    $eventInfos = $event->infos();

    // Check (gCentre, formateur) : appartenance de l'event
    if(in_array($role, [3,4])){
        // Check : Appartenance centre
        $user->id_role = $user->searchIdRoleForIdUsers();
        $userCentre = $user->searchIdCentreForIdUsers();
        $error_centre = $userCentre != $eventInfos['id_centres_de_formation'];

        // Check : Si event est passé
        $currentDate = new DateTime();
        $debutDate = new DateTime($eventInfos['debut']);
        $finDate = new DateTime($eventInfos['fin']);
        $error_date = $currentDate > $debutDate || $currentDate > $finDate;
        
        // Check (formateur) : si auteur de l'event
        $error_author = $role == 4 && $event['id_users'] != $user->id;

        $error_message .= $error_date ? "L'évènement est déjà passé. " : "";
        $error_message .= $error_centre ? "Vous n'avez pas accès à cet évènement. " : "";
        $error_message .= $error_author ? "Vous n'êtes pas l'auteur de cet évènement.  " : "";

        if(!empty($error_message)){
            $response->getBody()->write(json_encode(['erreur' =>  trim($error_message)]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    // Check => Range de suppression
    switch($param['range']){
        case "default": // Suppression de 1 event
            $result = $event->delete();
            $action = "Suppression de l'évènement";
            break;
        
        case "all": // Suppression de tous les events recurrents
            $event->id_recurrence = $eventInfos['id_recurrence'];
            $result = !empty($event->id_recurrence) && $event->recurrenceDeleteAll();
            $action = "Suppression de toute la série d'évènements";
            break;
        
        case "after":
            $event->id_recurrence = $eventInfos['id_recurrence'];
            $event->debut = $eventInfos['debut'];
            $result = !empty($event->id_recurrence) && $event->recurrenceDeleteAfter();
            $action = "Suppression des évènements créés après la date du jour";
            break;
    }

    if($result){
        $response->getBody()->write(json_encode(['valid' => $action. " : succès"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        $response->getBody()->write(json_encode(['erreur' => $action." : échec"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($check_role([1,3,4]));