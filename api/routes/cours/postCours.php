<?php
// /routes/equipement/postCours.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../models/EventModel.php';
require_once __DIR__ . '/../../models/EventSessionsModel.php';
require_once __DIR__ . '/../../models/CoursModel.php';
require_once __DIR__ . '/../../models/SessionModel.php';
require_once __DIR__ . '/../../models/EtudiantModel.php';
require_once __DIR__ . '/../../models/SalleModel.php';
require_once __DIR__ . '/../../models/EmailModel.php';
require_once __DIR__ . '/../../models/FormateurModel.php';
require_once __DIR__ . '/../../models/MatiereModel.php';
require_once __DIR__ . '/../../models/FormateursParticipantSessionModel.php';

//========================================================================================
// But : Permet d'ajouter un cours standard ou récurrent avec des participants : sessions entières
// Rôles : administrateur, gestionnaire centre, formateur
// Champs obligatoires : 
//   - Pour tous : nom, id_modalites, id_matieres, is_recurrent, event_sessions
//   - Pour non-récurrent : debut, fin
//   - Pour récurrent : dateDebut, heureDebut, heureFin, jours, frequence
//   - Pour récurrent : soit dateFin, soit nbOccurences (l'un des deux est obligatoire)
//   - Pour rôles autres que formateur : id_formateurs
// Champs facultatifs : 
//   - id_salles (obligatoire si présentiel)
//   - url (obligatoire si distanciel)
//   - description
//   - id_users (pour admin seulement)
//   - id_centres_de_formation (pour admin seulement)
// Validations :
//   - Cohérence entre fréquence et jours pour les événements récurrents
//   - Dates de début et fin valides pour les événements récurrents
//   - Nombre d'occurrences positif si spécifié
//   - Pas de chevauchement avec d'autres cours du même formateur
//   - Respect des contraintes de la session (dates, formateur associé)
// Gestion des récurrences :
//   - Utilisation de l'ID du premier événement comme id_recurrence pour toute la série
//   - Calcul précis des occurrences selon la fréquence, les jours, et la fin spécifiée
//   - Limite de sécurité pour éviter les boucles infinies
//========================================================================================

$app->post('/api/cours/add', function (Request $request, Response $response) {
    $token = $request->getAttribute('user');
    $data = $request->getParsedBody();
    error_log("Données reçues par l'API: " . json_encode($data));

    $role = intval($token['role']);
    $userId = $token['id'];
    $userIdByRole = $token['idByRole'];
    $userCentre = $role == 1 ? ($data['idCentreFormation'] ?? null) : $token['centre'];

    try {
        function validateRequiredFields($data, $role) {
            $requiredFields = ['event_sessions', 'nom', 'id_modalites', 'id_matieres', 'is_recurrent'];
            $requiredFields = $role != 4 ? array_merge($requiredFields, ['id_formateurs']) : $requiredFields;
            
            if ($data['is_recurrent']) {
                $requiredFields = array_merge($requiredFields, ['dateDebut', 'heureDebut', 'heureFin', 'jours', 'frequence']);
            } else {
                $requiredFields = array_merge($requiredFields, ['debut', 'fin']);
            }

            $missingFields = array_filter($requiredFields, function($field) use ($data) {
                return !isset($data[$field]) || $data[$field] === '';
            });

            if (!empty($missingFields)) {
                throw new Exception("Les champs suivants sont obligatoires : " . implode(', ', $missingFields));
            }

            if ($data['is_recurrent'] && !isset($data['dateFin']) && !isset($data['nbOccurences'])) {
                throw new Exception("Soit dateFin, soit nbOccurences doit être spécifié pour un événement récurrent");
            }

            if ($data['is_recurrent']) {
                validateRecurrentFields($data);
                validateFrequencyAndDays($data);
            }
        }

        function validateRecurrentFields($data) {
            if (!isset($data['is_recurrent']) || 
                (!is_bool($data['is_recurrent']) && 
                 $data['is_recurrent'] !== 'true' && 
                 $data['is_recurrent'] !== 'false' &&
                 $data['is_recurrent'] !== '1' &&
                 $data['is_recurrent'] !== '0')) {
                throw new Exception("Le champ 'is_recurrent' doit être un booléen ou une chaîne représentant un booléen");
            }
            
            // Convertir is_recurrent en booléen si c'est une chaîne
            $data['is_recurrent'] = filter_var($data['is_recurrent'], FILTER_VALIDATE_BOOLEAN);
        
            if (!is_array($data['frequence']) || count($data['frequence']) !== 2) {
                throw new Exception("Le champ frequence doit être un tableau avec deux éléments");
            }
        
            if (!is_array($data['jours']) || !validateJours($data['jours'])) {
                throw new Exception("Le champ jours doit être un tableau d'entiers entre 1 et 7");
            }
        }

        function validateJours($jours) {
            $validJours = range(1, 7);
            return empty(array_diff($jours, $validJours));
        }

        function validateFrequencyAndDays($data) {
            $frequencyValue = intval($data['frequence'][0]);
            $frequencyUnit = strtolower($data['frequence'][1]);
            $days = $data['jours'];
        
            if ($frequencyValue <= 0) {
                throw new Exception("La valeur de fréquence doit être supérieure à 0");
            }
        
            switch ($frequencyUnit) {
                case 'semaine':
                case 'semaines':
                    if (empty($days)) {
                        throw new Exception("Pour une fréquence hebdomadaire, vous devez spécifier au moins un jour de la semaine");
                    }
                    break;
                case 'mois':
                    if (empty($days) || count($days) > 7) {
                        throw new Exception("Pour une fréquence mensuelle, vous devez spécifier entre 1 et 7 jours de la semaine");
                    }
                    break;
                default:
                    throw new Exception("Unité de fréquence non reconnue");
            }
        }

        function validateModalite($data) {
            if (!in_array($data['id_modalites'], [1, 2, 3])) {
                throw new Exception("La modalité n'est pas valide");
            }

            $modaliteValidations = [
                1 => fn() => isset($data['id_salles']) && filter_var($data['id_salles'], FILTER_VALIDATE_INT),
                2 => fn() => !empty($data['url'])
            ];

            if (isset($modaliteValidations[$data['id_modalites']]) && !$modaliteValidations[$data['id_modalites']]()) {
                $errorMessages = [
                    1 => "Une salle valide est requise pour un cours en présentiel",
                    2 => "Une URL est requise pour un cours à distance"
                ];
                throw new Exception($errorMessages[$data['id_modalites']]);
            }
        }

        function validateMatiere($data) {
            $matiere = new Matiere();
            $matiere->id = $data['id_matieres'];
            if (!$matiere->boolId()) {
                throw new Exception("La matière spécifiée n'existe pas");
            }
        }

        function validateSessions($data, $role, $userIdByRole, $userCentre) {
            $data['event_sessions'] = is_array($data['event_sessions']) ? $data['event_sessions'] : [$data['event_sessions']];
            
            $errors = array_filter(array_map(function($sessionId) use ($data, $role, $userIdByRole, $userCentre) {
                return validateSingleSession($sessionId, $data, $role, $userIdByRole, $userCentre);
            }, $data['event_sessions']));

            if (!empty($errors)) {
                throw new Exception(implode("\n", $errors));
            }
        }

        function validateSingleSession($sessionId, $data, $role, $userIdByRole, $userCentre) {
            $session = new Session();
            $cours = new Cours();
            $formateurParticipantSession = new FormateursParticipantSession();

            $session->id = $sessionId;
            if (!$session->boolId()) {
                return "La session $sessionId n'existe pas";
            }

            $sessionCentre = $session->searchCentreForId();
            if (!$sessionCentre) {
                return "Impossible de trouver le centre pour la session $sessionId";
            }
        
            if ($sessionCentre != $userCentre && $role != 1) {
                return "La session $sessionId n'appartient pas à votre centre de formation";
            }

            $sessionDates = $session->getSessionDates($sessionId);
            $courseStart = new DateTime($data['is_recurrent'] ? $data['dateDebut'] : $data['debut']);
            $courseEnd = new DateTime($data['is_recurrent'] ? ($data['dateFin'] ?? $data['dateDebut']) : $data['fin']);
            $sessionStart = new DateTime($sessionDates['dateDebut']);
            $sessionEnd = new DateTime($sessionDates['dateFin']);

            if ($courseStart < $sessionStart || $courseEnd > $sessionEnd) {
                return "Le cours doit être compris entre le début et la fin de la session $sessionId";
            }

            if (!$data['is_recurrent'] && $cours->checkSessionOverlap($data['debut'], $data['fin'], $sessionId)) {
                return "Ce cours chevauche un cours existant pour la session $sessionId";
            }

            $formateurParticipantSession->id = $sessionId;
            $formateurParticipantSession->id_formateurs = $role == 4 ? $userIdByRole : $data['id_formateurs'];

            if (!$formateurParticipantSession->checkIfFormateurParticipantSessionExist()) {
                return "Le formateur spécifié ne participe pas à la session $sessionId";
            }

            return null;
        }

        function validateRecurrentDates($data) {
            if ($data['is_recurrent']) {
                $startDate = new DateTime($data['dateDebut']);
                
                if (isset($data['dateFin'])) {
                    $endDate = new DateTime($data['dateFin']);
                    if ($startDate >= $endDate) {
                        throw new Exception("La date de fin doit être postérieure à la date de début");
                    }
                } elseif (!isset($data['nbOccurences']) || !is_numeric($data['nbOccurences']) || $data['nbOccurences'] <= 0) {
                    throw new Exception("Pour un événement récurrent, vous devez spécifier soit une date de fin, soit un nombre d'occurrences valide");
                }
            }
        }

        function validateFormateur($data, $role, $userIdByRole) {
            if ($role != 4) {
                $formateur = new Formateur();
                $formateur->id = $data['id_formateurs'];
                if (!$formateur->boolIdRole()) {
                    throw new Exception("Le formateur spécifié n'existe pas");
                }
            }

            $idFormateur = $role == 4 ? $userIdByRole : $data['id_formateurs'];
            $cours = new Cours();
            if (!$data['is_recurrent'] && $cours->checkFormateurOverlap($data['debut'], $data['fin'], $idFormateur)) {
                throw new Exception("Ce cours chevauche un cours existant pour le même formateur");
            }
        }

        function addSingleCours($data, $role, $userId, $userIdByRole, $userCentre) {
            $eventId = createEvent($data, $role, $userId, $userCentre);
            linkEventToSessions($eventId, $data['event_sessions']);
            createCours($eventId, $data, $role, $userIdByRole);
            return "Le cours a été ajouté avec succès";
        }

        function addRecurrentCours($data, $role, $userId, $userIdByRole, $userCentre) {
            $occurrences = calculateOccurrences($data);
            $firstEventId = null;
        
            try {
                $db = new Database();
                $conn = $db->getConnection();
                $conn->beginTransaction();
        
                foreach ($occurrences as $index => $occurrence) {
                    if (checkRecurrentOverlap($occurrence['debut'], $occurrence['fin'], $data, $role, $userIdByRole)) {
                        throw new Exception("Un chevauchement a été détecté pour l'occurrence du " . $occurrence['debut']);
                    }
                    $eventId = createEvent($data, $role, $userId, $userCentre, $occurrence['debut'], $occurrence['fin'], $firstEventId);
                    
                    if ($index === 0) {
                        $firstEventId = $eventId;
                        updateEventRecurrence($eventId, $eventId);
                    }
                    
                    linkEventToSessions($eventId, $data['event_sessions']);
                    createCours($eventId, $data, $role, $userIdByRole);
                }
        
                $conn->commit();
                return count($occurrences) . " cours récurrents ont été ajoutés avec succès";
            } catch (Exception $e) {
                $conn->rollBack();
                throw $e;
            }
        }
        
        function createEvent($data, $role, $userId, $userCentre, $debut = null, $fin = null, $recurrenceId = null) {
            $event = new Event();
            $event->nom = $data['nom'];
            $event->debut = $debut ?? $data['debut'];
            $event->fin = $fin ?? $data['fin'];
            $event->id_modalites = $data['id_modalites'];
            $event->id_types_event = 2; // Type cours
            $event->id_users = $role == 1 && isset($data['id_users']) ? $data['id_users'] : $userId;
            $event->id_salles = $data['id_modalites'] == 1 ? $data['id_salles'] : null;
            $event->url = $data['id_modalites'] == 2 ? $data['url'] : null;
            $event->description = $data['description'] ?? null;
            $event->id_centres_de_formation = $userCentre;
            $event->id_recurrence = $recurrenceId;
            
        
            $eventId = $event->addDefault();
            if (!$eventId) {
                error_log("Failed to create event");
                throw new Exception("Erreur lors de la création de l'événement");
            }
            return $eventId;
        }
        
        function updateEventRecurrence($eventId, $recurrenceId) {
            $event = new Event();
            $event->id = $eventId;
            $event->id_recurrence = $recurrenceId;
            if (!$event->updateRecurrence()) {
                throw new Exception("Erreur lors de la mise à jour de l'id_recurrence pour l'événement $eventId");
            }
        }

        function linkEventToSessions($eventId, $sessionIds) {
            $eventSession = new EventSessions();
            $errors = array_filter(array_map(function($sessionId) use ($eventId, $eventSession) {
                $eventSession->id = $sessionId;
                $eventSession->id_events = $eventId;
                return $eventSession->add() ? null : "Erreur lors de la liaison de l'événement à la session $sessionId";
            }, $sessionIds));

            if (!empty($errors)) {
                throw new Exception(implode("\n", $errors));
            }
        }

        function createCours($eventId, $data, $role, $userIdByRole) {
            $cours = new Cours();
            $cours->id_formateurs = $role == 4 ? $userIdByRole : $data['id_formateurs'];
            $cours->id_events = $eventId;
            $cours->id_matieres = $data['id_matieres'];
            if (!$cours->add()) {
                throw new Exception("Erreur lors de la création du cours");
            }
            return true;
        }

        function generateRecurrenceId() {
            return uniqid('rec_', true);
        }

        function calculateOccurrences($data) {
            $startDate = new DateTime($data['dateDebut']);
            $endDate = isset($data['dateFin']) ? new DateTime($data['dateFin']) : null;
            $maxOccurrences = isset($data['nbOccurences']) ? intval($data['nbOccurences']) : PHP_INT_MAX;
        
            $frequencyValue = intval($data['frequence'][0]);
            $frequencyUnit = strtolower($data['frequence'][1]);
        
            $occurrences = [];
            $currentDate = clone $startDate;
        
            $safetyCounter = 0;
            $maxIterations = 1000; // Limite de sécurité
        
            while (count($occurrences) < $maxOccurrences && $safetyCounter < $maxIterations) {
                $weekStart = (clone $currentDate)->modify('monday this week');
                $weekEnd = (clone $weekStart)->modify('+6 days');
        
                if ($endDate && $weekStart > $endDate) {
                    break;
                }
        
                $weekOccurrences = calculateWeekOccurrences($weekStart, $weekEnd, $data, $endDate, $maxOccurrences - count($occurrences));
                $occurrences = array_merge($occurrences, $weekOccurrences);
        
                if ($frequencyUnit === 'semaine' || $frequencyUnit === 'semaines') {
                    $currentDate->modify("+{$frequencyValue} week");
                } elseif ($frequencyUnit === 'mois') {
                    $currentDate->modify("+{$frequencyValue} month");
                } else {
                    throw new Exception("Unité de fréquence non reconnue");
                }
        
                $safetyCounter++;
            }
        
            return array_slice($occurrences, 0, $maxOccurrences);
        }
        
        function calculateWeekOccurrences($weekStart, $weekEnd, $data, $endDate, $remainingOccurrences) {
            $weekOccurrences = [];
            $currentDay = clone $weekStart;
        
            while ($currentDay <= $weekEnd && count($weekOccurrences) < $remainingOccurrences) {
                $dayOfWeek = $currentDay->format('N'); // 1 (lundi) à 7 (dimanche)
                if (in_array($dayOfWeek, $data['jours'])) {
                    $occurrenceDate = clone $currentDay;
                    $occurrenceDate->setTime(
                        intval($data['heureDebut']),
                        intval(substr($data['heureDebut'], 3, 2))
                    );
        
                    $occurrenceEnd = clone $occurrenceDate;
                    $occurrenceEnd->setTime(
                        intval($data['heureFin']),
                        intval(substr($data['heureFin'], 3, 2))
                    );
        
                    if ($occurrenceDate >= new DateTime($data['dateDebut']) && ($endDate === null || $occurrenceDate <= $endDate)) {
                        $weekOccurrences[] = [
                            'debut' => $occurrenceDate->format('Y-m-d H:i:s'),
                            'fin' => $occurrenceEnd->format('Y-m-d H:i:s')
                        ];
                    }
                }
                $currentDay->modify('+1 day');
            }
        
            return $weekOccurrences;
        }

        function calculateDuration($data) {
            $start = new DateTime($data['heureDebut']);
            $end = new DateTime($data['heureFin']);
            if ($end < $start) {
                $end->modify('+1 day');
            }
            return $start->diff($end);
        }

        function checkRecurrentOverlap($debut, $fin, $data, $role, $userIdByRole) {
            $cours = new Cours();
            $idFormateur = $role == 4 ? $userIdByRole : $data['id_formateurs'];
            return $cours->checkFormateurOverlap($debut, $fin, $idFormateur);
        }

        function sendCoursRecapEmails($data, $eventSessions) {
            error_log("Valeur de coursRecapMail: " . var_export($data['coursRecapMail'], true));
    
            if (!isset($data['coursRecapMail']) || $data['coursRecapMail'] === false || $data['coursRecapMail'] === 'false') {
                error_log("coursRecapMail non défini, false ou 'false', sortie de la fonction");
                return;
            }
        
            try {
                error_log("Récupération des étudiants");
                $etudiants = [];
                foreach ($eventSessions as $sessionId) {
                    $etudiant = new Etudiant();
                    $etudiants = array_merge($etudiants, $etudiant->searchAllEtudiantForIdSession($sessionId));
                }
                error_log("Nombre d'étudiants trouvés : " . count($etudiants));
        
                error_log("Récupération des infos du formateur");
                $formateur = new Formateur();
                $formateur->id = $data['id_formateurs'];
                $formateurInfo = $formateur->getFormateurName();
                error_log("Infos formateur : " . json_encode($formateurInfo));
        
                error_log("Récupération des infos de la matière");
                $matiere = new Matiere();
                $matiere->id = $data['id_matieres'];
                $matiereInfo = $matiere->getMatiereInfo();
                error_log("Infos matière : " . json_encode($matiereInfo));
        
                foreach ($etudiants as $etudiant) {
                    if (!isset($etudiant['email']) || !isset($etudiant['firstname'])) {
                        error_log("Données d'étudiant incomplètes : " . json_encode($etudiant));
                        continue;
                    }
        
                    $salleName = null;
                    if (isset($data['id_salles'])) {
                        $salle = new Salle();
                        $salleName = $salle->getSalleNom($data['id_salles']);
                    }
        
                    Email::sendCourseRecap(
                        $etudiant['email'],
                        $etudiant['firstname'],
                        $data['nom'],
                        $data['dateDebut'],
                        $data['dateFin'] ?? ($data['nbOccurences'] ? null : $data['dateDebut']),
                        $data['heureDebut'],
                        $data['heureFin'],
                        $data['jours'] ?? [],
                        $data['frequence'] ?? [],
                        $data['id_modalites'],
                        $salleName,
                        $data['url'] ?? null,
                        $data['description'] ?? null,
                        $formateurInfo['firstname'] . ' ' . $formateurInfo['lastname'],
                        $matiereInfo['matiere_nom'],
                        $data['is_recurrent']
                    );
                }
            } catch (Exception $e) {
                error_log("Erreur lors de l'envoi des emails de récapitulatif : " . $e->getMessage());
                
            }
        }

        validateRequiredFields($data, $role);
        validateModalite($data);
        validateMatiere($data);
        validateSessions($data, $role, $userIdByRole, $userCentre);
        validateFormateur($data, $role, $userIdByRole);
        validateRecurrentDates($data);

        $result = $data['is_recurrent'] 
        ? addRecurrentCours($data, $role, $userId, $userIdByRole, $userCentre)
        : addSingleCours($data, $role, $userId, $userIdByRole, $userCentre);

    try {
        sendCoursRecapEmails($data, $data['event_sessions']);
    } catch (Exception $e) {
        error_log("Erreur lors de l'envoi des emails de récapitulatif : " . $e->getMessage());
       
    }

    $response->getBody()->write(json_encode(['success' => "Le cours a été ajouté avec succès"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
} catch (Exception $e) {
    error_log("Erreur lors de l'ajout du cours : " . $e->getMessage());
    $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
}
})->add($auth)->add($checkAdminEquipePedagogique);