<?php
require_once 'db.php';

/*=========================================================================================
AbsenceModel.php => Résumé des fonctions
===========================================================================================
> addAbsence() : ajouter une absence
> boolId() : retourne True si : id
> deleteAbsence() : delete en fonction : id
> searchCentreForId : renvoie id_centres_de_formation pour : id
> overlapAbsence() : retourne absences qui chevauchent une autre
> searchForId() : retourne * en fonction : id
> searchStudentId() : retourne * en fonction : id_etudiant
> updateDateDebut()
> updateDateFin()
> updateRaison()
> updateJustificatif()
===========================================================================================*/

class Absence extends Database {
    public $id;
    public $dateDebut;
    public $dateFin;
    public $raison;
    public $justificatif;
    // Clé étrangère
    public $id_etudiants;

    
    public function addAbsence() {       
        $insertQuery= "INSERT INTO `absences` (`dateDebut`, `dateFin`, `raison`, `justificatif`, `id_etudiants`) VALUES (:dateDebut, :dateFin, :raison, :justificatif, :id_etudiants)";
        
        $stmt = $this->db->prepare($insertQuery);

        $stmt->bindParam(":dateDebut", $this->dateDebut, PDO::PARAM_STR);
        $stmt->bindParam(":dateFin", $this->dateFin, PDO::PARAM_STR);
        $stmt->bindParam(":raison", $this->raison, PDO::PARAM_STR);
        $stmt->bindParam(":justificatif", $this->justificatif, PDO::PARAM_STR);
        $stmt->bindParam(":id_etudiants", $this->id_etudiants, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function getAbsenceById() {
        $query = "SELECT * FROM `absences` WHERE `id` = :id";
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getAbsencesOneStudent() {
        $query = "SELECT absences.*, etudiants.firstname, etudiants.lastname
        FROM absences 
        JOIN etudiants ON etudiants.id = absences.id_etudiants
        WHERE absences.id_etudiants = :id_etudiants";
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_etudiants", $this->id_etudiants, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    function searchCentreForId() {
        require_once 'db.php';

        $query = "SELECT etudiants.id_centres_de_formation
        FROM etudiants
        JOIN absences ON absences.id_etudiants = etudiants.id
        WHERE absences.id = :id;
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);

        return $res['id_centres_de_formation'];
    }

    // Check si une absence en chevauche une autre
    public function overlapAbsence(){
        $overlapQuery = "SELECT * FROM `absences`
        WHERE `id_etudiants` = :id_etudiants
        AND (
            (dateFin IS NULL AND dateDebut < :dateFin) OR
            (dateFin IS NOT NULL AND NOT (dateFin < :dateDebut OR dateDebut > :dateFin))
        )";
        $stmtOverlap = $this->db->prepare($overlapQuery);
        $stmtOverlap->bindParam(":id_etudiants", $this->id_etudiants, PDO::PARAM_INT);
        $stmtOverlap->bindParam(":dateDebut", $this->dateDebut, PDO::PARAM_STR);
        $stmtOverlap->bindParam(":dateFin", $this->dateFin, PDO::PARAM_STR);
        $stmtOverlap->execute();

        return $stmtOverlap->fetchAll(PDO::FETCH_ASSOC);
    }

     // Check si une absence en chevauche une autre en ignorant l'absence actuellement sélectionnée
     public function overlapAbsenceForUpdate(){
        $overlapQuery = "SELECT * FROM `absences`
        WHERE `id_etudiants` = :id_etudiants
        AND `id` != :id
        AND (
            (dateFin IS NULL AND dateDebut < :dateFin) OR
            (dateFin IS NOT NULL AND NOT (dateFin < :dateDebut OR dateDebut > :dateFin))
        )";
        $stmtOverlap = $this->db->prepare($overlapQuery);
        $stmtOverlap->bindParam(":id_etudiants", $this->id_etudiants, PDO::PARAM_INT);
        $stmtOverlap->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmtOverlap->bindParam(":dateDebut", $this->dateDebut, PDO::PARAM_STR);
        $stmtOverlap->bindParam(":dateFin", $this->dateFin, PDO::PARAM_STR);
        $stmtOverlap->execute();

        return $stmtOverlap->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchStudentId() {
        $selectQuery = "SELECT * FROM `absences` WHERE `id_etudiants` = :id_etudiants";
        
        $stmt = $this->db->prepare($selectQuery);
        $stmt->bindParam(":id_etudiants", $this->id_etudiants, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
     // Return true si l'absence existe
     public function boolId() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM absences WHERE id = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    // Renvoie toutes les infos d'une absence
    public function searchForId() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM absences WHERE id = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    public function updateDateDebut() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE absences SET dateDebut = :dateDebut WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':dateDebut', $this->dateDebut, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateDateFin() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE absences SET dateFin = :dateFin WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':dateFin', $this->dateFin, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateRaison() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE absences SET raison = :raison WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':raison', $this->raison, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateJustificatif() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE absences SET justificatif = :justificatif WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':justificatif', $this->justificatif, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function deleteAbsence() {
        require_once 'db.php';
    
        $deleteQuery = "DELETE FROM absences WHERE id = :id";;
        
        $stmt = $this->db->prepare($deleteQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getOneAbsenceInfo() {
        try {
            $query = "SELECT absences.*, 
                      etudiants.firstname, etudiants.lastname, etudiants.id_entreprises AS etudiants_idEntreprises, etudiants.id_conseillers_financeurs AS etudiants_idFinanceurs, etudiants.id AS etudiants_id,
                      session.id AS session_id, session.id_centres_de_formation AS session_idCentre, 
                      formations.nom AS formation_nom, 
                      conseillers_financeurs.id_entreprises AS financeur_idEntreprises 
                      FROM absences 
                      JOIN etudiants ON etudiants.id = absences.id_etudiants 
                      LEFT JOIN conseillers_financeurs ON conseillers_financeurs.id = etudiants.id_conseillers_financeurs 
                      LEFT JOIN session ON session.id = etudiants.id_session 
                      LEFT JOIN formations ON formations.id = session.id_formations 
                      WHERE absences.id = :id";
                     
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
            $queryexec->execute();
            $res = $queryexec->fetch(PDO::FETCH_ASSOC);
            return $res;
        } catch (PDOException $e) {
            // Log the error or handle it as needed
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getAbsencesForSession($sessionId){
        try {
            $query = "SELECT a.*, e.firstname AS etudiant_prenom, e.lastname AS etudiant_nom
            FROM absences a
            JOIN etudiants e ON a.id_etudiants = e.id
            WHERE e.id_session = :sessionId
            ORDER BY a.dateDebut DESC";

            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(":sessionId", $sessionId, PDO::PARAM_INT);
            $queryexec->execute();
            $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch (PDOException $e) {
            // Log the error or handle it as needed
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

}