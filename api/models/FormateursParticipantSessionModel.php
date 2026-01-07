<?php
require_once 'db.php';
/*=========================================================================================
FormateursParticipantSessionModel.php => Résumé des fonctions
===========================================================================================
> postFormateurParticipantSession() : ajouter un formateur (id_formateurs) participant à une session (id)
> checkIfFormateurParticipantSessionExist() : Renvoie True si id et id_formateurs
> getFormateursBySession() : renvoie l'id formateur en fonction id
> getSessionsByFormateur() : renvoie id des sessions en fonction de l'id_formateurs
===========================================================================================*/

class FormateursParticipantSession extends Database{
    public $id;
    public $id_formateurs;

    function postFormateurParticipantSession() {

        $query = "INSERT INTO formateurs_participant_session (id_formateurs, id) VALUES (:id_formateurs, :id)";
        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(":id_formateurs", $this->id_formateurs, PDO::PARAM_INT);
            $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
            return $queryexec->execute();
        } catch (PDOException $e) {
            return false;
        }   
    }
    function checkIfFormateurParticipantSessionExist() {
        
        $query = "SELECT * 
        FROM formateurs_participant_session 
        WHERE id=:id AND id_formateurs=:id_formateurs
        ";
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_formateurs", $this->id_formateurs, PDO::PARAM_INT);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
        $queryexec->execute();
        $count = $queryexec->fetchColumn();
        return ($count >= 1);
    }

    function getFormateursBySession() {
        $query = "SELECT formateurs.*, users.email 
        FROM formateurs_participant_session 
        JOIN formateurs ON formateurs.id = formateurs_participant_session.id_formateurs
        JOIN users ON users.id = formateurs.id_users
        WHERE formateurs_participant_session.id=:id
        ";
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
        $queryexec->execute();
        return $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
    }

    function getSessionsByFormateur() {
        $query = "SELECT formateurs_participant_session.id, formations.nom
        FROM formateurs_participant_session
        JOIN session ON formateurs_participant_session.id = session.id
        JOIN formations ON session.id_formations = formations.id
        WHERE formateurs_participant_session.id_formateurs = :id_formateurs
        AND NOW() BETWEEN session.dateDebut AND session.dateFin
        ";
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_formateurs", $this->id_formateurs, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
        return $res;    
    }

    function searchForIdFormateur($idformateur) {
        $query = "SELECT *  FROM formateurs_participant_session  WHERE id_formateurs =:id ";
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $idformateur, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    function getSessionFormateur( $idcentre) {
        $query = "SELECT 
        session.*,
        formations.nom AS nom_formation,
        formateurs.lastname AS nom_formateur
        FROM 
            session
        JOIN 
            formations ON session.id_formations = formations.id
        JOIN 
            formateurs_participant_session ON session.id = formateurs_participant_session.id
        JOIN 
            formateurs ON formateurs_participant_session.id_formateurs = formateurs.id
        WHERE 
            session.id_centres_de_formation = :id_centres_de_formation
        ";
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_centres_de_formation", $idcentre, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    function getAllSessions_formateur() {
        $query = "SELECT session.*, formations.nom AS nom_formation
        FROM session
        INNER JOIN formations ON session.id_formations = formations.id
        JOIN formateurs_participant_session ON formateurs_participant_session.id = session.id
        WHERE formateurs_participant_session.id_formateurs = :id_formateurs
        ";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_formateurs", $this->id_formateurs, PDO::PARAM_INT);
            $stmt->execute(); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }   
    }

    function getAllEtudiants_formateur() {
        $query = "SELECT etudiants.*
        FROM etudiants
        JOIN session ON etudiants.id_session = session.id
        JOIN formateurs_participant_session ON formateurs_participant_session.id = session.id
        WHERE formateurs_participant_session.id_formateurs = :id_formateurs
        ORDER BY etudiants.lastname
        ";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_formateurs', $this->id_formateurs, PDO::PARAM_INT);
            $stmt->execute(); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    function boolFormateurParticipant(){
        $query = "SELECT * FROM formateurs_participant_session 
        WHERE id = :id
        AND id_formateurs = :id_formateurs";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindValue(":id_formateurs", $this->id_formateurs, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }


public function getUniqueFormateursForSessions($sessionIds) {
    $placeholders = implode(',', array_fill(0, count($sessionIds), '?'));
    $query = "SELECT DISTINCT f.id, f.firstname, f.lastname
              FROM formateurs f
              JOIN formateurs_participant_session fps ON f.id = fps.id_formateurs
              WHERE fps.id IN ($placeholders)
              ORDER BY f.lastname, f.firstname";
    
    $stmt = $this->db->prepare($query);
    foreach ($sessionIds as $i => $id) {
        $stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}