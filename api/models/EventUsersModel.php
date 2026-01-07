<?php
require_once 'db.php';

/*=========================================================================================
EventUsersModel.php => Résumé des fonctions
===========================================================================================
> add()
> getEventsIdForIdUsers()
===========================================================================================*/

class EventUsers extends Database {
    public $id; // id_session de la session qui participe
    // Clés étrangères
    public $id_users;

    public function add() {
    
        $query= "INSERT INTO `event_users` (`id`, `id_users`) 
                        VALUES (:id, :id_users)";
        try{
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            $stmt->bindParam(":id_users", $this->id_users, PDO::PARAM_INT);

            return ($stmt->execute());
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
        
    }

    public function getEventsForIdUsers(){
        require_once 'db.php';

        $query = "SELECT events.*,
                modalites.modalites_nom,
                types_event.type_event_nom,
                salles.nom AS salles_nom, salles.capacite_accueil
                FROM events
                JOIN event_users ON event_users.id = events.id
                JOIN types_event ON types_event.id = events.id_types_event
                JOIN modalites ON modalites.id = events.id_modalites
                LEFT JOIN salles ON salles.id = events.id_salles
                WHERE event_users.id_users = :id_users";       
        try{
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

    public function getUsers(){
        require_once 'db.php';

        $query = "SELECT id_users
                FROM event_users
                WHERE id = :id";       
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

    
    public function clear() {
        $query = "DELETE FROM event_users WHERE id = :id";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}