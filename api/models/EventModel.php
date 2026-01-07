<?php
require_once 'db.php';

/*=========================================================================================
EventModel.php => Résumé des fonctions
===========================================================================================
> addDefault : Ajout d'un event "standard"
delete() : suppression
getEventsForIdUsers() : récupère les events par rapport à un id_users
getPublicEventsForIdCentre() : récupère les events publics d'un centre
recurrenceDeleteAll() : suppression de toutes les récurrences d'un event
updateIdRecurrence() : update l'id recurrence d'un event
infos() : * from id
recurrenceDeleteAfter : supprime les events recurrents après la date du jour
===========================================================================================*/

class Event extends Database
{
    public $id;
    public $nom;
    public $debut;
    public $fin;
    public $url;
    public $description;
    public $id_recurrence;
    // Clés étrangères
    public $id_salles;
    public $id_users;
    public $id_modalites;
    public $id_centres_de_formation;
    public $id_types_event;

    public function addDefault()
    {

        $query = "INSERT INTO `events` (`nom`, `debut`, `fin`, `url`, `description`, `id_salles`, `id_users`, `id_modalites`, `id_centres_de_formation`, `id_types_event`, `id_recurrence`) 
                  VALUES (:nom, :debut, :fin, :url, :description, :id_salles, :id_users, :id_modalites, :id_centres_de_formation, :id_types_event, :id_recurrence)";
        try {
            $stmt = $this->db->prepare($query);

            $stmt->bindValue(":nom", $this->nom, PDO::PARAM_STR);
            $stmt->bindValue(":debut", $this->debut, PDO::PARAM_STR);
            $stmt->bindValue(":fin", $this->fin, PDO::PARAM_STR);
            $stmt->bindValue(":url", $this->url, $this->url === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
            $stmt->bindValue(":description", $this->description, $this->description === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
            $stmt->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
            $stmt->bindValue(":id_salles", $this->id_salles, $this->id_salles === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $stmt->bindValue(":id_modalites", $this->id_modalites, PDO::PARAM_INT);
            $stmt->bindValue(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
            $stmt->bindValue(":id_types_event", $this->id_types_event, PDO::PARAM_INT);
            $stmt->bindValue(":id_recurrence", $this->id_recurrence, $this->id_recurrence === null ? PDO::PARAM_NULL : PDO::PARAM_INT);

            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            } else {
                return null;
            }
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return null;
        }
    }

    public function delete()
    {
        require_once 'db.php';

        $query = "DELETE FROM events WHERE id = :id";
        ;
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
            return ($stmt->execute());
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function getEventsForIdUsers()
    {
        require_once 'db.php';

        $query = "SELECT events.*,
                modalites.modalites_nom,
                types_event.type_event_nom,
                salles.nom AS salles_nom, salles.capacite_accueil
                FROM events
                JOIN types_event ON types_event.id = events.id_types_event
                JOIN modalites ON modalites.id = events.id_modalites
                LEFT JOIN salles ON salles.id = events.id_salles
                WHERE events.id_users = :id_users";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                return $result;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }

    }

    public function getPublicEventsForIdCentre()
    {
        require_once 'db.php';

        $query = "SELECT events.*,
                modalites.modalites_nom,
                types_event.type_event_nom,
                salles.nom AS salles_nom, salles.capacite_accueil
                FROM events
                LEFT JOIN event_sessions ON event_sessions.id_events = events.id
                JOIN types_event ON types_event.id = events.id_types_event
                JOIN modalites ON modalites.id = events.id_modalites
                LEFT JOIN salles ON salles.id = events.id_salles
                WHERE id_centres_de_formation = :id_centres_de_formation
                AND id_modalites = 1
                AND event_sessions.id_events IS NULL";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                return $result;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }

    }

    public function getInfo()
    {
        $query = "SELECT e.*, es.id AS id_session 
                  FROM events e
                  LEFT JOIN event_sessions es ON e.id = es.id_events
                  WHERE e.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data)
    {

        $query = "UPDATE events SET 
                  nom = :nom, 
                  debut = :debut, 
                  fin = :fin, 
                  url = :url, 
                  description = :description, 
                  id_salles = :id_salles, 
                  id_modalites = :id_modalites 
                  WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom', $data['nom'], PDO::PARAM_STR);
        $stmt->bindParam(':debut', $data['debut'], PDO::PARAM_STR);
        $stmt->bindParam(':fin', $data['fin'], PDO::PARAM_STR);
        $stmt->bindParam(':url', $data['url'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':id_salles', $data['id_salles'], PDO::PARAM_INT);
        $stmt->bindParam(':id_modalites', $data['id_modalites'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updateRecurrence()
    {
        $query = "UPDATE events SET id_recurrence = :id_recurrence WHERE id = :id";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_recurrence', $this->id_recurrence, PDO::PARAM_INT);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating event recurrence: " . $e->getMessage());
            return false;
        }
    }


    public function recurrenceDeleteAll()
    {
        $query = "DELETE FROM
                events
                WHERE id_recurrence = :id_recurrence";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id_recurrence", $this->id_recurrence, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function infos()
    {
        $query = "SELECT *
            FROM events
            WHERE id = :id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result : null;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function recurrenceDeleteAfter()
    {
        $query = "DELETE FROM
                events
                WHERE id_recurrence = :id_recurrence
                AND debut >= :debut";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id_recurrence", $this->id_recurrence, PDO::PARAM_INT);
            $stmt->bindValue(":debut", $this->debut, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function getIdRecurrence()
    {
        $query = "SELECT id_recurrence
            FROM events
            WHERE id = :id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result : null;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function clearIdRecurrence()
    {
        $query = "UPDATE events
            SET id_recurrence = null
            WHERE id = :id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id", (int) $this->id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function updateEvent()
    {
        $query = "UPDATE events 
        SET nom = :nom, 
        debut = :debut, 
        fin = :fin, 
        url = :url, 
        description = :description, 
        id_salles = :id_salles, 
        id_modalites = :id_modalites 
        WHERE id = :id";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nom', $this->nom, PDO::PARAM_STR);
            $stmt->bindValue(':debut', $this->debut, PDO::PARAM_STR);
            $stmt->bindValue(':fin', $this->fin, PDO::PARAM_STR);
            $stmt->bindValue(':url', $this->url !== null ? $this->url : null, $this->url === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
            $stmt->bindValue(':description', $this->description !== null ? $this->description : null, $this->description === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
            $stmt->bindValue(':id_salles', $this->id_salles !== null ? (int) $this->id_salles : null, $this->id_salles === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $stmt->bindValue(':id_modalites', (int) $this->id_modalites, PDO::PARAM_INT);
            $stmt->bindValue(':id', (int) $this->id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }

    }

    public function getModaliteNom()
    {
        $query = "SELECT modalites_nom FROM modalites WHERE id = :id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id", $this->id_modalites, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['modalites_nom'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return null;
        }
    }


    public function getSalleNom()
    {
        $query = "SELECT nom FROM salles WHERE id = :id";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id", $this->id_salles, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['nom'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return null;
        }
    }


    public function getMailingList()
    {
        $query = "SELECT users_etudiants.email AS email,
            etudiants.firstname AS firstname
            FROM event_sessions
            LEFT JOIN etudiants ON etudiants.id_session = event_sessions.id
            LEFT JOIN users AS users_etudiants ON etudiants.id_users = users_etudiants.id
            WHERE event_sessions.id_events = :id

            UNION

            SELECT users_formateurs.email AS email,
            formateurs.firstname AS firstname
            FROM event_sessions
            LEFT JOIN formateurs_participant_session ON formateurs_participant_session.id = event_sessions.id
            LEFT JOIN formateurs ON formateurs_participant_session.id_formateurs = formateurs.id
            LEFT JOIN users AS users_formateurs ON formateurs.id_users = users_formateurs.id
            WHERE event_sessions.id_events = :id
            
            UNION 
            
            SELECT users.email,
            formateurs.firstname AS firstname
            FROM event_users
            JOIN users ON users.id = event_users.id_users
            JOIN formateurs ON formateurs.id_users = users.id
            WHERE event_users.id = :id

            UNION 
                        
            SELECT users.email,
            etudiants.firstname AS firstname
            FROM event_users
            JOIN users ON users.id = event_users.id_users
            JOIN etudiants ON etudiants.id_users = users.id
            WHERE event_users.id = :id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                return $result;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
}