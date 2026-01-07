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
require_once __DIR__ . '/../../models/CoursModel.php';


require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../models/FormateurModel.php';
require_once __DIR__ . '/../../models/GestionnaireCentreModel.php';
require_once __DIR__ . '/../../models/SessionModel.php';

//========================================================================================
// But : Permet d'ajouter un event standard récurrent avec des participants : sessions entières et/ou participants individuels
// Rôles : administrateur, gestionnaire centre, formateur


// Si range = "default"
// Champs facultatifs : nom, debut, fin, id_centres_de_formation (admin), id_modalites
//                        id_salles, url, description, (pour admin facultatif : id_users (pour attribuer à un utilisateur choisi)), event_sessions (array des id_session), event_users (array des id_users)

// Range = "after" ou "all"
// Champs facultatifs : nom,  id_types_event, id_centres_de_formation (pour admin), id_modalites
//                       dateDebut, dateFin ou nbOccurences, heureDebut, heureFin, [jours] (format : 1,2,4,etc. | 0=lundi, 1=mardi, etc.), [frequence] (nombre, semaine/mois)
//                       id_salles, url, description (pour admin : id_users), [event_sessions] (array des id_session), [event_users] (array des id_users)
//========================================================================================
$app->put('/api/event/{id}/edit/{range}', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data = $request->getParsedBody();

    // Check => Paramètres
    $error = "";
    $error .= empty($param['id']) || filter_var($param['id'], FILTER_VALIDATE_INT) === false ? "ID incorrect. " : "";
    $error .= empty($param['range']) || !in_array($param['range'], ["default", "after", "all"]) ? "Range incorrect. " : "";

    function returnError($response, $error)
    {
        $errorData = ['erreur' => trim($error)];
        $response->getBody()->write(json_encode($errorData));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    if (!empty($error)) {
        return returnError($response, $error);
    }

    if (!empty($data['event_sessions'])) {
        $data['event_sessions'] = is_array($data['event_sessions']) ? $data['event_sessions'] : array($data['event_sessions']);
        sort($data['event_sessions']);
    }
    if (!empty($data['event_users'])) {
        $data['event_users'] = is_array($data['event_users']) ? $data['event_users'] : array($data['event_users']);
        sort($data['event_users']);
    }

    // Récupération => Infos de l'event sélectionné
    $event = new Event();
    $event->id = $param['id'];
    $event->id_recurrence = $event->getIdRecurrence()['id_recurrence'];
    $eventInfos = $event->infos();

    $event_participant_sessions = new EventSessions();
    $event_participant_sessions->id_events = $event->id;
    $og_event_participant_sessions = $event_participant_sessions->getSessions();
    $og_sessions = [];
    foreach ($og_event_participant_sessions as $session) {
        $og_sessions[] = $session['id'];
    }
    sort($og_sessions);

    $event_participant_users = new EventUsers();
    $event_participant_users->id = $event->id;
    $og_event_participant_users = $event_participant_users->getUsers();
    $og_users = [];
    foreach ($og_event_participant_users as $user) {
        $og_users[] = $user['id_users'];
    }
    sort($og_users);

    // Check => Id_centres_de_formation & auteur de l'event (formateur)
    $user = new User();
    $user->id = $role == 1 && !empty($data['id_users']) ? $data['id_users'] : $token['id'];
    $user->id_role = $user->searchIdRoleForIdUsers();
    $event->id_centres_de_formation = in_array($user->id_role, [3, 4]) ? $user->searchIdCentreForIdUsers() : $data["id_centres_de_formation"];
    $event->id_users = $user->id;

    $error .= empty($event->id_centres_de_formation) ? "Erreur dans la récupération du centre de formation. " : null;
    $error .= $event->id_centres_de_formation != $eventInfos['id_centres_de_formation'] ? "Cet évènement n'est pas modifiable par ce centre. " : null;
    $error .= $role == 4 && $user->id != $eventInfos['id_users'] ? "Vous n'avez pas le droit de modifier cet évènement. " : null;

    if (!empty($error)) {
        return returnError($response, $error);
    }

    // Check => Si des changements sont demandés
    $hasChanged = false;
    $fields = ['nom', 'url', 'description', 'id_salles', 'id_modalites', 'id_types_event'];
    $data['id_salles'] = $data['id_modalites'] != 2 ? $data['id_salles'] : null;

    foreach ($fields as $field) {
        if (isset($data[$field]) && $data[$field] != $eventInfos[$field]) {
            $hasChanged = true;
            $event->$field = $data[$field];
        } else {
            $event->$field = $eventInfos[$field];
        }
    }

    if ($event->id_types_event == 2) {
        $cours = new Cours();
        $cours_fields = ["id_formateurs", "id_matieres"];
        foreach ($cours_fields as $field) {
            if (empty($field)) {
                $response->getBody()->write(json_encode(['erreur' => "Veuillez remplir tous les champs du cours"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        $cours->id_formateurs = $data['id_formateurs'];
        $cours->id_matieres = $data['id_matieres'];

        $cours->id_events = $param['id'];
        $coursInfos = $cours->infos();
        if($cours->id_formateurs != $coursInfos['id_formateurs'] ||
        $cours->id_matieres != $coursInfos['id_matieres']){
            $hasChanged = true;
        }
    }

    // Check => Dates
    $dateFields = ['debut', 'fin'];
    foreach ($dateFields as $field) {
        $og_date = new DateTime($eventInfos[$field]);
        $input_date = new DateTime($data[$field]);
        if (!empty($input_date) && $input_date != $og_date) {
            $hasChanged = true;

            $event->$field = $input_date->format('Y-m-d H:i:s');
        } else {
            $event->$field = $og_date->format('Y-m-d H:i:s');
        }
    }

    $hasChanged = $hasChanged || $data['event_sessions'] != $og_sessions;
    $hasChanged = $hasChanged || $data['event_users'] != $og_users;

    $result = false;

    switch ($param['range']) {
        case "default": // Default : Un event
            if (!$hasChanged) {
                $response->getBody()->write(json_encode(['erreur' => "Il n'y a aucun changement."]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
            $eventId = $event->id;

            $result = $event->updateEvent();
            if ($result) {
                if ($data['id_types_event'] == 1 || $data['id_types_event'] == 2) {
                    $event_sessions = new EventSessions();
                    $event_sessions->id_events = $event->id;

                    $data['event_sessions'] = is_array($data['event_sessions']) ? $data['event_sessions'] : [$data['event_sessions']];
                    $data['event_sessions'] = $data['event_sessions'] == [null] ? [] : $data['event_sessions'];

                    $event_sessions->clear();
                    foreach ($data['event_sessions'] as $session) {
                        $session_participant = new Session();
                        $session_participant->id = $session;
                        if ($session_participant->searchCentreForId() != $event->id_centres_de_formation && !empty($data['event_sessions'])) {
                            $response->getBody()->write(json_encode(['erreur' => "Une des sessions listée ne fait pas partie du centre"]));
                            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                        }

                        $event_sessions->id = $session_participant->id;

                        if ($event_sessions->add() == false) { // Si ajout échoué : suppression event créé + Message erreur
                            $response->getBody()->write(json_encode(['erreur' => "Erreur lors de l'ajout des sessions participantes. Veuillez réessayer."]));
                            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                        }
                    }


                    if ($data['id_types_event'] == 2) {
                        $cours->id_events = $event->id;
                        $result_update_cours = $cours->updateCours();
                        if ($result_update_cours == false) {
                            $response->getBody()->write(json_encode(['erreur' => "Erreur lors de la mise à jour des informations du cours"]));
                            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                        }
                    }
                }

                $event_users = new EventUsers();
                $event_users->id = $event->id;
                $event_users->clear();

                $data['event_users'] = is_array($data['event_users']) ? $data['event_users'] : [$data['event_users']];
                $data['event_users'] = $data['event_users'] == [null] ? [] : $data['event_users'];

                foreach ($data['event_users'] as $user) {
                    $user_participant = new User();
                    $user_participant->id = $user;
                    $user_participant->id_role = $user_participant->searchIdRoleForIdUsers();
                    $user_participant_centre = $user_participant->searchIdCentreForIdUsers();
                    if ($event->id_centres_de_formation != $user_participant_centre && !empty($data['event_users'])) {
                        $response->getBody()->write(json_encode(['erreur' => $data['event_users']]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                    }

                    $event_users->id_users = $user;
                    if ($event_users->add() == false) { // Si ajout échoué : suppression event créé + Message erreur
                        $response->getBody()->write(json_encode(['erreur' => "Erreur lors de l'ajout des participants. Veuillez réessayer."]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                    }
                }
            }
            $clearResult = $event->clearIdRecurrence();
            if (!$clearResult) {
                $response->getBody()->write(json_encode(['erreur' => "Erreur dans le clear id_recurrence"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
            break;
        case "after":
        case "all":
            if (!$event->delete()) {
                $response->getBody()->write(json_encode(['erreur' => "Erreur dans la suppression des anciens évènements"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
            // Suppression des anciens évènements en fonction du range
            if (isset($event->id_recurrence) && !empty($event->id_recurrence)) {
                if ($param['range'] == "after") {
                    $event->debut = $eventInfos['debut'];
                    if (!$event->recurrenceDeleteAfter()) {
                        $response->getBody()->write(json_encode(['erreur' => "Erreur dans la suppression des anciens évènements"]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                    }
                } elseif ($param['range'] == "all") {
                    if (!$event->recurrenceDeleteAll()) {
                        $response->getBody()->write(json_encode(['erreur' => "Erreur dans la suppression des anciens évènements"]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                    }
                }
            }

            // Vérification des champs obligatoires pour modifier les events
            $requiredFields = ['dateDebut', 'heureDebut', 'heureFin', 'jours', 'frequence'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    $error .= "$field vide. ";
                }
            }
            // Decode the JSON strings
            // $jours = $data['jours'];
            //$error .= json_last_error() !== JSON_ERROR_NONE ? "Erreur de décodage JSON. " : null;

            if (
                (empty($data['dateFin']) && empty($data['nbOccurences'])) ||
                (!empty($data['dateFin']) && !empty($data['nbOccurences']))
            ) {
                $error .= "Veuillez sélectionner une date de fin ou un nombre de répétition. ";
            }

            $heureDebut = $data['heureDebut'];
            $heureFin = $data['heureDebut'];

            $error .= $heureDebut > $heureFin ? "L'heure de début doit être avant l'heure de fin. " : null;
            $error .= !empty($data['dateFin']) && $data['dateDebut'] > $data['dateFin'] ? "La date de début doit être avant la date de fin. " : null;

            if (!empty($error)) {
                return returnError($response, $error);
            }

            // Extract recurrent fields
            $currentDate = new DateTime();
            $dateDebut = new DateTime($data['dateDebut']);
            /*if ($dateDebut < $currentDate) {
                $dateDebut = $currentDate;
            }
            $og_dateDebut = new DateTime($eventInfos['debut']);
            $clear_dateDebut = $og_dateDebut < $currentDate ? $currentDate : $og_dateDebut;*/
            $dateFin = !empty($data['dateFin']) ? new DateTime($data['dateFin']) : null;
            $nbOccurences = !empty($data['nbOccurences']) ? intval($data['nbOccurences']) : null;


            $intervalSpec = 'P' . $data['frequence'][0] . strtoupper(substr($data['frequence'][1], 0, 1)); // e.g., P1W for one week, P1M for one month
            $interval = new DateInterval($intervalSpec);
            $period = $dateFin ? new DatePeriod($dateDebut, $interval, $dateFin) : new DatePeriod($dateDebut, $interval, $nbOccurences);

            $id_recurrence = null;
            $eventCount = 0;

            $events = [];
            // $event->debut = $clear_dateDebut->format('Y-m-d H:i:s');

            if (!empty($error)) {
                return returnError($response, $error);
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

            $result = false;
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
                        if (!$error_recurring) {
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

                        if ($data['id_types_event'] == 2) {
                            $cours->id_events = $eventId;
                            $result_update_cours = $cours->add();
                            if ($result_update_cours == false) {
                                $response->getBody()->write(json_encode(['erreur' => "Erreur lors de la mise à jour des informations du cours"]));
                                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                            }
                        }

                        // Ajout des sessions participantes (si rempli)
                        if (($data['id_types_event'] == 1 || $data['id_types_event'] == 2) && !empty($data['event_sessions'])) {
                            $event_sessions = new EventSessions();
                            $event_sessions->id_events = $eventId;

                            $data['event_sessions'] = is_array($data['event_sessions']) ? $data['event_sessions'] : [$data['event_sessions']];

                            foreach ($data['event_sessions'] as $session) {
                                $session_participant = new Session();
                                $session_participant->id = $session;
                                if ($session_participant->searchCentreForId() != $event->id_centres_de_formation) {
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
                            $event_users->id = $eventId;

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
                                    $event->recurrenceDeleteAll();
                                    $response->getBody()->write(json_encode(['erreur' => "Erreur lors de l'ajout des participants. Veuillez réessayer."]));
                                    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                                }
                            }
                        }
                    } else {
                        $response->getBody()->write(json_encode(['erreur' => "Erreur dans la sélection de la fin de récurrence"]));
                        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                    }

                    $eventCount++;

                    // Arrêt de la boucle si nbOccurence existe et si le compte est >= nbOccurence
                    if ($nbOccurences && $eventCount >= $nbOccurences) {
                        $result = true;
                        break 2;
                    }
                }
            }
            $result = true;
            break;
    }

    // Mailing
    if ($result) {
        if ($data['is_notified'] == true) {
            $event_info = new Event();
            if (empty($event->id_recurrence)) {
                $event_info->id_modalites = $data['id_modalites'];
                $modaliteNom = $event_info->getModaliteNom();

                try {
                    $event_info->id_salles = $data['id_salles'];
                    $salleNom = $event_info->getSalleNom() ?? "Aucune salle renseignée";

                    $event_info->id_modalites = $data['id_modalites'];
                    $modaliteNom = $event_info->getModaliteNom();

                    $event_info->id = $param['id'];
                    $mailingList = $event_info->getMailingList();

                    $is_cours = false;
                    if($data['id_types_event'] == 2){
                        $is_cours = true;
                        $matiere = new Matiere();
                        $matiere->id = $data['id_matieres'];
                        $matiere_nom = $matiere->getMatiereNom();

                        $formateur = new Formateur();
                        $formateur->id = $data['id_formateurs'];
                        $formateurInfos = $formateur->searchForId();
                        $formateur_nom = $formateurInfos['nom'];
                        $formateur_prenom = $formateurInfos['prenom'];
                    }

                    $is_update = true;
                    foreach (array_filter($mailingList) as $participant) {
                        $participant_firstname = $participant['firstname'];
                        $participant_email = $participant['email'];

                        if (!Email::sendEventRecap($is_update, $participant_email, $participant_firstname, $data['nom'], $data['debut'], $data['fin'], $modaliteNom, $salleNom, $data['url'], $data['description'], $is_cours, $formateur_nom, $formateur_prenom, $matiere_nom)) {
                            throw new Exception("Erreur dans l'envoi du mail recapitulatif");
                        }
                    }

                    $response->getBody()->write(json_encode(['valid' => "Mise à jour de l'évènement. Envoi du mail récapitulatif"]));
                    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                } catch (Exception $e) {
                    // En cas d'erreur, attrapez l'exception et renvoyez une réponse d'erreur
                    $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
                    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                }
            } else {
                switch ($param['range']) {
                    case "default":
                        $event_info->id_modalites = $data['id_modalites'];
                        $modaliteNom = $event_info->getModaliteNom();

                        try {
                            $event_info->id_salles = $data['id_salles'];
                            $salleNom = $event_info->getSalleNom() ?? "Aucune salle renseignée";

                            $event_info->id_modalites = $data['id_modalites'];
                            $modaliteNom = $event_info->getModaliteNom();

                            $event_info->id = $param['id'];
                            $mailingList = $event_info->getMailingList();

                            $is_update = true;

                            $is_cours = false;
                            if($data['id_types_event'] == 2){
                                $is_cours = true;
                                $matiere = new Matiere();
                                $matiere->id = $data['id_matieres'];
                                $matiere_nom = $matiere->getMatiereNom();
        
                                $formateur = new Formateur();
                                $formateur->id = $data['id_formateurs'];
                                $formateurInfos = $formateur->searchForId();
                                $formateur_nom = $formateurInfos['nom'];
                                $formateur_prenom = $formateurInfos['prenom'];
                            }        

                            foreach (array_filter($mailingList) as $participant) {
                                $participant_firstname = $participant['firstname'];
                                $participant_email = $participant['email'];
                                if (!Email::sendEventRecap($is_update, $participant_email, $participant_firstname, $data['nom'], $data['debut'], $data['fin'], $modaliteNom, $salleNom, $data['url'], $data['description'], $is_cours, $formateur_nom, $formateur_prenom, $matiere_nom)) {
                                    throw new Exception("Erreur dans l'envoi du mail recapitulatif");
                                }
                            }

                            $response->getBody()->write(json_encode(['valid' => "Mise à jour de l'évènement. Envoi du mail récapitulatif"]));
                            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                        } catch (Exception $e) {
                            // En cas d'erreur, attrapez l'exception et renvoyez une réponse d'erreur
                            $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
                            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                        }
                        break;

                    case "after":
                    case "all":
                        $event_info = new Event();
                        $event_info->id = $eventId;
                        $event_info->id_modalites = $data['id_modalites'];
                        $modaliteNom = $event_info->getModaliteNom();

                        $event_info->id_salles = $data['id_salles'];
                        $salleNom = $event_info->getSalleNom() ?? "Aucune salle renseignée";

                        $mailingList = $event_info->getMailingList();

                        $is_update = true;

                        $is_cours = false;
                        if($data['id_types_event'] == 2){
                            $is_cours = true;
                            $matiere = new Matiere();
                            $matiere->id = $data['id_matieres'];
                            $matiere_nom = $matiere->getMatiereNom();
    
                            $formateur = new Formateur();
                            $formateur->id = $data['id_formateurs'];
                            $formateurInfos = $formateur->searchForId();
                            $formateur_nom = $formateurInfos['nom'];
                            $formateur_prenom = $formateurInfos['prenom'];
                        }    

                        try {
                            foreach (array_filter($mailingList) as $participant) {
                                $participant_firstname = $participant['firstname'];
                                $participant_email = $participant['email'];
                                if (!Email::sendEventRecurrentRecap($participant_email, $participant_firstname, $data['nom'], $data['dateDebut'], $data['dateFin'], $data['nbOccurences'], $data['heureDebut'], $data['heureFin'], $data['jours'], $data['frequence'], $modaliteNom, $salleNom, $data['url'], $data['description'], $is_cours, $formateur_nom, $formateur_prenom, $matiere_nom)) {
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
                        break;
                }
            }
        } else {
            $response->getBody()->write(json_encode(['valid' => "Mise à jour réussie."]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Erreur dans la mise à jour."]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth)->add($check_role([1, 3, 4]));