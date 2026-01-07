<?php
require_once 'db.php';

class Matiere extends Database {
    public $id;
    public $matiere_nom;
    public $id_formations;
    public $id_sessions;

    function add() {
        $query = "INSERT INTO `matieres` (`matiere_nom`, `id_sessions`, `id_formations`) VALUES (:matiere_nom, :id_sessions, :id_formations)";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":matiere_nom", $this->matiere_nom, PDO::PARAM_STR);
            $stmt->bindValue(":id_sessions", $this->id_sessions, PDO::PARAM_INT);
            $stmt->bindValue(":id_formations", $this->id_formations, PDO::PARAM_INT);
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }


    function boolId() {
        $query = "SELECT * FROM matieres WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    function getMatieresForSession($sessionId) {
        $query = "SELECT id, matiere_nom, id_formations, id_sessions
                  FROM matieres
                  WHERE id_sessions = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $sessionId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMatieresForMultipleSessions($sessionIds) {
        $placeholders = implode(',', array_fill(0, count($sessionIds), '?'));
        $query = "SELECT DISTINCT id, matiere_nom, id_formations, id_sessions
                  FROM matieres
                  WHERE id_sessions IN ($placeholders)
                  ORDER BY matiere_nom";
        
        $stmt = $this->db->prepare($query);
        foreach ($sessionIds as $i => $id) {
            $stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMatiereInfo() {
        $query = "SELECT matiere_nom FROM matieres WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $this->id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}