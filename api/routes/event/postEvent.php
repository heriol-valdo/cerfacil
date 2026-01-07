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
require_once __DIR__ . '/../../models/CentreFormationModel.php';
require_once __DIR__ . '/../../models/GestionnaireCentreModel.php';
require_once __DIR__ . '/../../models/SessionModel.php';

//========================================================================================
// But : Permet d'ajouter un event standard avec des participants : sessions entières et/ou participants individuels
// Rôles : administrateur, gestionnaire centre, formateur
// Champs obligatoires : nom, debut, fin, id_types_event, id_centres_de_formation, id_modalites
// Champs facultatifs :  id_salles, url, description (pour admin : id_users), event_sessions (array des id_session), event_users (array des id_users), is_notified
//========================================================================================
$app->post('/api/event/default/add', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    $data = $request->getParsedBody();
    if (!in_array($data['id_types_event'],[1,3])) { // Type event 1 = Général
        $response->getBody()->write(json_encode(['erreur' => "Type event incompatible"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $event = new Event();
    $requiredFields = ['nom', 'debut', 'fin', 'id_types_event', 'id_modalites'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            $response->getBody()->write(json_encode(['erreur' => "Le champs $field doit figurer"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        if (empty($data[$field])) {
            $response->getBody()->write(json_encode(['erreur' => "Le champs $field est vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        $event->$field = $data[$field];
    }

    if ($event->id_modalites == 2) { // Si distanciel : salles = null
        $data['id_salles'] = null;
    }

    $secondaryFields = ['id_salles', 'url', 'description'];
    foreach ($secondaryFields as $field) {
        $event->$field = !empty($data[$field]) ? $data[$field] : null;
    }

    // Check => Dates cohérentes
    if ($data['debut'] > $data['fin']) {
        $response->getBody()->write(json_encode(['erreur' => "La date de début doit être avant la date de fin"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Admin : Si reception users : applique user comme auteur, sinon admin = auteur
    if ($role == 1) {
        $event->id_users = !empty($data['id_users']) ? $data['id_users'] : $token['id'];
    } else { // Gestionnaire Centre, Formateur : auteur = eux-même
        $event->id_users = $token['id'];
    }

    // Gestion id_centres_de_formation
    if ($role == 1) {
        if (empty($data['id_centres_de_formation']) || filter_var($data['id_centres_de_formation'], FILTER_VALIDATE_INT) === false) {
            $response->getBody()->write(json_encode(['erreur' => "L'id du centre entré est invalide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } else {
            $centre = new CentreFormation();
            $centre->id = $data['id_centres_de_formation'];
            $centre_exist = $centre->boolId();
            if(!$centre_exist){
                $response->getBody()->write(json_encode(['erreur' => "Le centre de formation sélectionné n'existe pas"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
            $event->id_centres_de_formation = $data['id_centres_de_formation'];
        }
    } elseif ($role == 3 || $role == 4) { // GestionnaireCentre, Formateur : Get id_centres_de_formation et le remplace dans Event
        // ::class pour instanciation dynamique
        $roleClass = $role == 3 ? GestionnaireCentre::class : Formateur::class;
        $roleTable = new $roleClass();
        $roleTable->id_users = $token['id'];
        $event->id_centres_de_formation = $roleTable->searchCentreForIdUsers();
    }

    $added_event = $event->addDefault();
    if (empty($added_event)) {
        $response->getBody()->write(json_encode(['erreur' => "Erreur lors de l'ajout de l'évènement"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Ajout des sessions participantes (si rempli)
    if (!empty($data['event_sessions']) && $data['id_types_event'] == 1) {
        $event_sessions = new EventSessions();
        $event_sessions->id_events = $added_event;

        $data['event_sessions'] = is_array($data['event_sessions']) ? $data['event_sessions'] : [$data['event_sessions']];

        foreach ($data['event_sessions'] as $session) {
            $session_participant = new Session();
            $session_participant->id = $session;
            if ($session_participant->searchCentreForId() != $event->id_centres_de_formation) {
                $event->delete();
                $response->getBody()->write(json_encode(['erreur' => "Une des sessions listée ne fait pas partie du centre"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
            $event_sessions->id = $session_participant->id;

            if ($event_sessions->add() == false) { // Si ajout échoué : suppression event créé + Message erreur
                $event->id = $added_event;
                $event->delete();

                $response->getBody()->write(json_encode(['erreur' => "Erreur lors de l'ajout des sessions participantes. Veuillez réessayer."]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }
    }

    // Ajout des participants individuels (si rempli)
    if (!empty($data['event_users'])) {
        $event_users = new EventUsers();
        $event_users->id = $added_event;

        $data['event_users'] = is_array($data['event_users']) ? $data['event_users'] : [$data['event_users']];

        foreach ($data['event_users'] as $user) {
            $user_participant = new User();
            $user_participant->id = $user;
            $user_participant->id_role = $user_participant->searchIdRoleForIdUsers();
            $user_participant_centre = $user_participant->searchIdCentreForIdUsers();
            if ($event->id_centres_de_formation != $user_participant_centre) {
                $response->getBody()->write(json_encode(['erreur' => "Un des participants listé ne fait pas partie du centre"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }

            $event_users->id_users = $user;
            if ($event_users->add() == false) { // Si ajout échoué : suppression event créé + Message erreur
                $event->id = $added_event;
                $event->delete();

                $response->getBody()->write(json_encode(['erreur' => "Erreur lors de l'ajout des participants. Veuillez réessayer."]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }
    }

    // Mailing pour participants
    if($data['is_notified'] == true){
        $event->id_modalites = $data['id_modalites'];
        $modaliteNom = $event->getModaliteNom();

        try{
            $event->id_salles = $data['id_salles'];
            $salleNom = $event->getSalleNom() ?? "Aucune salle renseignée";

            $event->id_modalites = $data['id_modalites'];
            $modaliteNom = $event->getModaliteNom();

            $event->id = $added_event;
            $mailingList = $event->getMailingList();

            $is_update = false;

            $is_cours = false;
            $matiere_nom = null;
            $formateur_nom = null;
            $formateur_prenom = null;

            foreach(array_filter($mailingList) as $participant){
                $participant_firstname = $participant['firstname'];
                $participant_email = $participant['email'];

                if(!Email::sendEventRecap($is_update, $participant_email, $participant_firstname, $event->nom, $event->debut, $event->fin, $modaliteNom, $salleNom, $event->url, $event->description,$is_cours, $formateur_nom, $formateur_prenom, $matiere_nom)){
                    throw new Exception("Erreur dans l'envoi du mail recapitulatif");
                }
            }

            $response->getBody()->write(json_encode(['valid' => "Création de l'évènement. Envoi du mail récapitulatif"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            // En cas d'erreur, attrapez l'exception et renvoyez une réponse d'erreur
            $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    } else {
        $response->getBody()->write(json_encode(['valid' => "Création de l'événement"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    
})->add($auth)->add($checkAdminEquipePedagogique);


//========================================================================================
// But : Permet d'ajouter un event standard récurrent avec des participants : sessions entières et/ou participants individuels
// Rôles : administrateur, gestionnaire centre, formateur
// Champs obligatoires : nom,  id_types_event, id_centres_de_formation (pour admin, pour autres chargement auto), id_modalites
//                       dateDebut, dateFin ou nbOccurences, heureDebut, heureFin, [jours] (format : 0, 1,2,4,etc. | 0=lundi, 1=mardi, etc.), [frequence] (nombre, week/month)
// Champs facultatifs :  id_salles, url, description (pour admin : id_users), [event_sessions] (array des id_session), [event_users] (array des id_users)
//========================================================================================
$app->post('/api/event/recurrent/add', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    $data = $request->getParsedBody();
    if (!in_array($data['id_types_event'], [1,3])) { // Type event 3 = Privé
        $response->getBody()->write(json_encode(['erreur' => "Type event incompatible"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    // Decode the JSON strings
    $jours = json_decode($data['jours'], true);
    $frequence = json_decode($data['frequence'], true);
    /*$data['event_users'] = !empty($data['event_users']) ? json_decode($data['event_users'], true) : null;
    $data['event_sessions'] = !empty($data['event_sessions']) ? json_decode($data['event_sessions'], true) : null;*/

    /*$response->getBody()->write(json_encode(['erreur' => $data['event_users']]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);*/

    // Check if decoding was successful
    if (json_last_error() !== JSON_ERROR_NONE) {
        $response->getBody()->write(json_encode(['erreur' => "Erreur de décodage JSON"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $event = new Event();
    // Vérification : Champs obligatoires
    // Champs qui seront identiques sur les occurences
    $error_requiredField_message = "Champs vide : ";
    $error_requiredField = false;

    $requiredBaseFields = ['nom', 'id_modalites', 'id_types_event'];
    foreach ($requiredBaseFields as $field) {
        if (empty($data[$field])) {
            $error_requiredField = true;
            $error_requiredField_message .= "$field, ";
        } else {
            $event->$field = $data[$field];
        }
    }

    // Champs qui vont définir le nombre de répétition
    $requiredRecurrentFields = ['dateDebut', 'heureDebut', 'heureFin', 'jours', 'frequence'];
    foreach ($requiredRecurrentFields as $field) {
        if (empty($data[$field])) {
            $error_requiredField = true;
            $error_requiredField_message .= "$field, ";
        }
    }

    // Si erreur sur champs requis : 
    if ($error_requiredField) {
        $error_requiredField_message = rtrim($error_requiredField_message, ', ');

        $response->getBody()->write(json_encode(['erreur' => $error_requiredField_message]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Champs salle = Null si modalites = 2 (distanciel)
    $data['id_salles'] = $event->id_modalites != 2 ? $data['id_salles'] : null;

    $secondaryFields = ['id_salles', 'url', 'description'];
    foreach ($secondaryFields as $field) {
        $event->$field = !empty($data[$field]) ? $data[$field] : null;
    }

    // Il faut au moins soit la date de fin, soit le nombre d'occurences
    if (empty($data['dateFin']) && empty($data['nbOccurences'])) {
        $response->getBody()->write(json_encode(['erreur' => "Veuillez sélectionner une date de fin ou un nombre de répétition"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    
    // Un seul des 2 doit être rempli
    if (!empty($data['dateFin']) && !empty($data['nbOccurences'])) {
        $response->getBody()->write(json_encode(['erreur' => "Veuillez sélectionner une date de fin ou un nombre de répétition"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Check => Heures cohérentes
    $error_period = false;
    $error_period = $error_period || ($data['heureDebut'] > $data['heureFin']) ? true : false;
    $error_period = $error_period || (!empty($data['dateFin']) && $data['dateDebut'] > $data['dateFin']) ? true : false;

    if ($error_period) {
        $response->getBody()->write(json_encode(['erreur' => "La période sélectionnée est incorrecte"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Chargement : Auteur de l'event 
    // Admin : lui-même ou un user choisi / GestionnaireCentre-Formateur : lui-même
    if ($role == 1) {
        $event->id_users = !empty($data['id_users']) ? $data['id_users'] : $token['id'];
    } else { // Gestionnaire Centre, Formateur : auteur = eux-même
        $event->id_users = $token['id'];
    }

    // Gestion id_centres_de_formation
    if ($role == 1) {
        $user = new User();
        $user->id = $event->id_users;
        $user->role = $user->searchIdRoleForIdUsers();   
        $event->id_centres_de_formation = in_array($user->role,[3,4]) ? $user->searchIdCentreForIdUsers() : $data["id_centres_de_formation"];

        $centre = new Centre();
        $centre->id = $event->id;
        $centre_exist = $centre->boolId();
        if(!$centre_exist){
            $response->getBody()->write(json_encode(['erreur' => "Le centre de formation sélectionné n'existe pas"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    } elseif ($role == 3 || $role == 4) { // GestionnaireCentre, Formateur : Get id_centres_de_formation et le remplace dans Event
        // ::class pour instanciation dynamique
        $roleClass = $role == 3 ? GestionnaireCentre::class : Formateur::class;
        $roleTable = new $roleClass();
        $roleTable->id_users = $token['id'];
        $event->id_centres_de_formation = $roleTable->searchCentreForIdUsers();
    }

    if (empty($event->id_centres_de_formation) || filter_var($event->id_centres_de_formation, FILTER_VALIDATE_INT) === false) {
        $response->getBody()->write(json_encode(['erreur' => "L'ID du centre est invalide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Extract recurrent fields
    $dateDebut = new DateTime($data['dateDebut']);
    $dateFin = !empty($data['dateFin']) ? new DateTime($data['dateFin']) : null;
    $nbOccurences = !empty($data['nbOccurences']) ? intval($data['nbOccurences']) : null;
    $heureDebut = $data['heureDebut'];
    $heureFin = $data['heureFin'];

    $quantite = intval($data['frequence'][0]);
    $type = strtoupper(substr($data['frequence'][1], 0, 1));

    if (in_array($type, ['W', 'M', 'Y'])) {
        $intervalSpec = 'P' . $quantite . $type; 
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Mauvaise fréquence"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    
    $interval = new DateInterval($intervalSpec);
    $period = $dateFin ? new DatePeriod($dateDebut, $interval, $dateFin) : new DatePeriod($dateDebut, $interval, $nbOccurences);
    
    $id_recurrence = null;
    $eventCount = 0;

    $events = [];
    $eventId = null;

    foreach ($period as $currentDate) { 
        foreach ($data['jours'] as $jour) {
            $eventDate = clone $currentDate;
            $dayOfWeek = $eventDate->format('w');
        
            if ($dayOfWeek <= $jour) {
                // If the current day is before or the same as the target day, just add the difference
                $daysToAdd = $jour - $dayOfWeek;
            } else {
                // If the current day is after the target day, move to the next week's target day
                $daysToAdd = 7 - ($dayOfWeek - $jour);
            }
    
            // Add the calculated days to the eventDate
            $eventDate->modify("+$daysToAdd days");
            if ((!empty($dateFin) && $eventDate >= $dateDebut && $eventDate <= $dateFin) || ($nbOccurences && $eventCount < $nbOccurences)) {
                // Create the event
                $eventStart = new DateTime($eventDate->format('Y-m-d') . ' ' . $heureDebut);
                $eventEnd = new DateTime($eventDate->format('Y-m-d') . ' ' . $heureFin);

                // Prepare event data
                $event->debut = $eventStart->format('Y-m-d H:i:s');
                $event->fin = $eventEnd->format('Y-m-d H:i:s');

                $eventId = $event->addDefault();
                if (empty($eventId)) {
                    $response->getBody()->write(json_encode(['erreur' => "Erreur lors de l'ajout de l'évènement"]));
                    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                }

                // Définit l'id_recurrence du groupe entier
                $id_recurrence = $id_recurrence ?? $eventId;

                $event->id = $eventId;
                $event->id_recurrence = $id_recurrence;
                $error_recurring = $event->updateRecurrence();
                if(!$error_recurring){
                    $response->getBody()->write(json_encode(['erreur' => "Erreur lors de l'ajout au groupe récurrent"]));
                    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                }

                 // Ajout des sessions participantes (si rempli)
                if ($data['id_types_event'] == 1 && !empty($data['event_sessions'])) {

                    $event_sessions = new EventSessions();
                    $event_sessions->id_events = $eventId;

                    $data['event_sessions'] = is_array($data['event_sessions']) ? $data['event_sessions'] : [$data['event_sessions']];

                    foreach ($data['event_sessions'] as $session) {
                        $session_participant = new Session();
                        $session_participant->id = $session;
                        if ($session_participant->searchCentreForId() != $event->id_centres_de_formation) {
                            $event->recurrenceDeleteAll();
                            $response->getBody()->write(json_encode(['erreur' => "Une des sessions listée ne fait pas partie du centre"]));
                            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                        }
                        $event_sessions->id = $session_participant->id;

                        if ($event_sessions->add() == false) { // Si ajout échoué : suppression event créé + Message erreur
                            $event->recurrenceDeleteAll();
                            $response->getBody()->write(json_encode(['erreur' => "Erreur lors de l'ajout des sessions participantes. Veuillez réessayer."]));
                            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                        }
                    }
                }

                // Ajout des participants individuels (si rempli)
                if (!empty($data['event_users'])) {
                    
        
                    $event_users = new EventUsers();
                    $event_users->id = intval($eventId);
                    $data['event_users'] = is_array($data['event_users']) ? $data['event_users'] : [$data['event_users']];
                    foreach ($data['event_users'] as $user) {
                        $user_participant = new User();
                        $user_participant->id = $user;
                        $user_participant->id_role = $user_participant->searchIdRoleForIdUsers();
                        $user_participant_centre = $user_participant->searchIdCentreForIdUsers();
                        if ($event->id_centres_de_formation != $user_participant_centre) {
                            $event->recurrenceDeleteAll();
                            $response->getBody()->write(json_encode(['erreur' => "Un des participants listé ne fait pas partie du centre"]));
                            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                        }

                        $event_users->id_users = $user;
                        $result = $event_users->add();
                        if (!$result) { // Si ajout échoué : suppression event créé + Message erreur
                            $event->recurrenceDeleteAll();
                            $response->getBody()->write(json_encode(['erreur' => "Erreur lors de l'ajout des participants. Veuillez réessayer."]));
                            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                        }
                    }
                }
            }
                
            $eventCount++;
            // Arrêt de la boucle si nbOccurence existe et si le compte est >= nbOccurence
            if ($nbOccurences && $eventCount >= $nbOccurences) {
                break 2;
            }
        }
    }

    // Mailing pour participants
    // nom,  id_types_event, id_centres_de_formation (pour admin, pour autres chargement auto), id_modalites
    // dateDebut, dateFin ou nbOccurences, heureDebut, heureFin, [jours] (format : 0, 1,2,4,etc. | 0=lundi, 1=mardi, etc.), [frequence] (nombre, week/month)
    // Champs facultatifs :  id_salles, url, description (pour admin : id_users), [event_sessions] (array des id_session), [event_users] (array des id_users)
    if($data['is_notified'] == true){
        $event_info = new Event();
        $event_info->id = $eventId;
        $event_info->id_modalites = $data['id_modalites'];
        $modaliteNom = $event_info->getModaliteNom();

        $event_info->id_salles = $data['id_salles'];
        $salleNom = $event_info->getSalleNom() ?? "Aucune salle renseignée";

        $event->id = $eventId;
        $mailingList = $event->getMailingList();

        $is_update = false;
        
        try{
            foreach(array_filter($mailingList) as $participant){
                $participant_firstname = $participant['firstname'];
                $participant_email = $participant['email'];
                $is_cours = false;
                $matiere_nom = null;
                $formateur_nom = null;
                $formateur_prenom = null;
                if(!Email::sendEventRecurrentRecap($is_update, $participant_email, $participant_firstname, $data['nom'], $data['dateDebut'], $data['dateFin'], $data['nbOccurences'], $data['heureDebut'], $data['heureFin'], $data['jours'], $data['frequence'], $modaliteNom, $salleNom, $data['url'], $data['description'], $is_cours, $formateur_nom, $formateur_prenom, $matiere_nom)){
                    throw new Exception("Erreur dans l'envoi du mail recapitulatif");
                } 
            }
            $response->getBody()->write(json_encode(['valid' => "Création de l'évènement. Envoi du mail récapitulatif"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (Exception $e) {
            // En cas d'erreur, attrapez l'exception et renvoyez une réponse d'erreur
            $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    } else {
        $response->getBody()->write(json_encode(['valid' => "Création de l'événement"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
})->add($auth)->add($check_role([1,3,4]));
