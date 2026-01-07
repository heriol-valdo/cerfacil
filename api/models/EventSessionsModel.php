<?php
require_once 'db.php';

/*=========================================================================================
EventSessionsModel.php => Résumé des fonctions
===========================================================================================
> add()
getEventsForIdSession : récupère events pour id_session
delete 
===========================================================================================*/

class EventSessions extends Database {
    public $id; // id_session de la session qui participe
    // Clés étrangères
    public $id_events;

    public function add() {
    
        $query= "INSERT INTO `event_sessions` (`id`, `id_events`) 
                        VALUES (:id, :id_events)";
        try{
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            $stmt->bindParam(":id_events", $this->id_events, PDO::PARAM_INT);

            return ($stmt->execute());
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
        
    }

    public function getEventsForIdSession(){
        require_once 'db.php';

        $query = "SELECT events.*,
                modalites.modalites_nom,
                types_event.type_event_nom,
                salles.nom AS salles_nom, salles.capacite_accueil
                FROM events
                JOIN event_sessions ON events.id = event_sessions.id_events
                JOIN types_event ON types_event.id = events.id_types_event
                JOIN modalites ON modalites.id = events.id_modalites
                LEFT JOIN salles ON salles.id = events.id_salles
                WHERE event_sessions.id = :id";       
        try{
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

    public function delete() {
        $query = "DELETE FROM event_sessions WHERE id = :id AND id_events = :id_events";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':id_events', $this->id_events, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getSessions(){
        require_once 'db.php';

        $query = "SELECT id
                FROM event_sessions
                WHERE id_events = :id_events";       
        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id_events", $this->id_events, PDO::PARAM_INT);
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

    public function clear() {
        $query = "DELETE FROM event_sessions WHERE id_events = :id_events";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_events', $this->id_events, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}