<?php
require_once 'db.php';

/*=========================================================================================
CoursModel.php => Résumé des fonctions
===========================================================================================

===========================================================================================*/

class Cours extends Database {
    public $id;
    public $id_formateurs;
    public $id_events;
    public $id_matieres;

    public function add() {
        $query = "INSERT INTO cours (id_formateurs, id_events, id_matieres) VALUES (:id_formateurs, :id_events, :id_matieres)";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_formateurs', $this->id_formateurs, PDO::PARAM_INT);
            $stmt->bindParam(':id_events', $this->id_events, PDO::PARAM_INT);
            $stmt->bindParam(':id_matieres', $this->id_matieres, PDO::PARAM_INT);
            $result = $stmt->execute();
            if (!$result) {
                error_log("Erreur SQL : " . implode(", ", $stmt->errorInfo()));
            }
            return $result;
        } catch (PDOException $e) {
            error_log("Exception PDO : " . $e->getMessage());
            return false;
        }
    }

    public function checkSessionOverlap($debut, $fin, $id_session, $exclude_id = null) {
        // Construction de la requête de base
        $query = "SELECT COUNT(*) FROM cours 
                  JOIN events ON cours.id_events = events.id
                  JOIN event_sessions ON events.id = event_sessions.id_events
                  WHERE event_sessions.id = :id_session
                  AND (
                      (events.debut < :fin AND events.fin > :debut)
                      OR (events.debut = :debut AND events.fin = :fin)
                  )";

        // Préparation des paramètres de base
        $params = [
            ':debut' => $debut,
            ':fin' => $fin,
            ':id_session' => $id_session
        ];

        // Ajout conditionnel de la clause d'exclusion
        if ($exclude_id !== null) {
            $query .= " AND cours.id != :exclude_id";
            $params[':exclude_id'] = $exclude_id;
        }

        // Préparation et exécution de la requête
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();

        // Retourne true si un chevauchement est trouvé, false sinon
        return $stmt->fetchColumn() > 0;
    }

    public function checkFormateurOverlap($debut, $fin, $id_formateur, $exclude_id = null) {
        $query = "SELECT COUNT(*) FROM cours 
                  JOIN events ON cours.id_events = events.id
                  WHERE cours.id_formateurs = :id_formateur
                  AND (
                      (events.debut < :fin AND events.fin > :debut)
                      OR (events.debut = :debut AND events.fin = :fin)
                  )";

        $params = [
            ':debut' => $debut,
            ':fin' => $fin,
            ':id_formateur' => $id_formateur
        ];

        if ($exclude_id !== null) {
            $query .= " AND cours.id != :exclude_id";
            $params[':exclude_id'] = $exclude_id;
        }

        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    public function exists() {
        $query = "SELECT COUNT(*) FROM cours WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function getInfo() {
        $query = "SELECT * FROM cours WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $query = "UPDATE cours SET id_formateurs = :id_formateurs, id_matieres = :id_matieres WHERE id_events = :id_events";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_formateurs', $this->id_formateurs, PDO::PARAM_INT);
            $stmt->bindParam(':id_matieres', $this->id_matieres, PDO::PARAM_INT);
            $stmt->bindParam(':id_events', $this->id_events, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour du cours : " . $e->getMessage());
            return false;
        }
    }

    public function getCoursForSession($idSession) {
        $query = "SELECT c.id, c.id_formateurs, c.id_matieres, 
                         e.nom, e.debut, e.fin, e.url, e.description, e.id_salles, e.id_modalites,
                         m.matiere_nom, 
                         f.firstname as formateur_prenom, f.lastname as formateur_nom
                  FROM cours c
                  JOIN events e ON c.id_events = e.id
                  JOIN event_sessions es ON e.id = es.id_events
                  JOIN matieres m ON c.id_matieres = m.id
                  JOIN formateurs f ON c.id_formateurs = f.id
                  WHERE es.id = :id_session
                  ORDER BY e.debut ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_session', $idSession, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCoursForFormateur() {
        if (!$this->id_formateurs) {
            throw new Exception("L'ID du formateur n'est pas défini");
        }

        $query = "SELECT c.id, c.id_matieres, 
                         e.nom, e.debut, e.fin, e.url, e.description, e.id_salles, e.id_modalites,
                         m.matiere_nom, 
                         s.nomSession as session_nom,
                         s.dateDebut as session_debut, s.dateFin as session_fin
                  FROM cours c
                  JOIN events e ON c.id_events = e.id
                  JOIN matieres m ON c.id_matieres = m.id
                  JOIN event_sessions es ON e.id = es.id_events
                  JOIN session s ON es.id = s.id
                  WHERE c.id_formateurs = :id_formateurs
                  ORDER BY e.debut ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_formateurs', $this->id_formateurs, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      } 
  
      public function getCoursForIdEvents(){
        require_once 'db.php';

        $query = "(
            SELECT cours.*, 
                matieres.matiere_nom, 
                formateurs.firstname AS formateur_firstname, 
                formateurs.lastname AS formateur_lastname, 
                formations.nom AS formation_nom, 
                formations.id AS formation_id,
                session.id AS session_id
            FROM cours
            JOIN matieres ON matieres.id = cours.id_matieres
            JOIN session ON session.id = matieres.id_sessions
            JOIN formations ON formations.id = session.id_formations
            JOIN formateurs ON formateurs.id = cours.id_formateurs
            WHERE cours.id_events = :id_events
        )
        UNION
        (
            SELECT cours.*, 
                matieres.matiere_nom, 
                formateurs.firstname AS formateur_firstname, 
                formateurs.lastname AS formateur_lastname, 
                formations.nom AS formation_nom, 
                formations.id AS formation_id,
                NULL AS session_id
            FROM cours
            JOIN matieres ON matieres.id = cours.id_matieres
            JOIN formations ON formations.id = matieres.id_formations
            JOIN formateurs ON formateurs.id = cours.id_formateurs
            WHERE cours.id_events = :id_events
        );";  
        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id_events", $this->id_events, PDO::PARAM_INT);
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
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

    public function updateCours() {
        $query = "UPDATE cours SET id_formateurs = :id_formateurs, id_matieres = :id_matieres WHERE id_events = :id_events";


        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_formateurs',$this->id_formateurs, PDO::PARAM_INT);
            $stmt->bindParam(':id_matieres', $this->id_matieres, PDO::PARAM_INT);
            $stmt->bindParam(':id_events', $this->id_events, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    
    public function infos() {
        $query = "SELECT * FROM cours WHERE id_events = :id_events";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_events', $this->id_events, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
   
}