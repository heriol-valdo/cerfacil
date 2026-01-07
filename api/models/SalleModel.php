<?php // /models/SalleModel.php
require_once 'db.php';
/*=========================================================================================
SalleModel.php => Résumé des fonctions
===========================================================================================
> addSalle() : ajouter une salle
> boolId() : retourne True si : id
> boolIdForNom() : retourne True si un nom existe déjà dans les salles
> boolIsFromCentre() : retourne True si : id (salle) et id_centres_de_formation
> searchAllForCentre() : Renvoie toutes les salles d'un centre de formation
> searchCentreForId : Renvoie id_centres pour : id
> deleteSalle() 
> updateNom()
===========================================================================================*/

class Salle extends Database{
    public $id;
    public $nom;
    public $capacite_accueil;
    //Clés étrangères
    public $id_centres_de_formation;

    public function boolId() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM salles WHERE id = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    public function boolIdForNom() {
        require_once 'db.php';
    
        $existingQuery = "SELECT id FROM salles WHERE nom = :nom AND id_centres_de_formation = :id_centres_de_formation";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":nom", $this->nom, PDO::PARAM_STR);
        $stmt->bindValue(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);

        $stmt->execute();
    
        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    public function boolIsFromCentre() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM salles WHERE id = :id AND id_centres_de_formation = :id_centres_de_formation";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindValue(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    public function addSalle() {
    
        $insertQuery= "INSERT INTO `salles` (`nom`, `id_centres_de_formation`,`capacite_accueil`) VALUES (:nom, :id_centres_de_formation, :capacite_accueil)";
        try{
            $stmt = $this->db->prepare($insertQuery);

            $stmt->bindParam(":nom", $this->nom, PDO::PARAM_STR);
            $stmt->bindParam(":capacite_accueil", $this->capacite_accueil, PDO::PARAM_INT);
            $stmt->bindParam(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);

            return $stmt->execute();
        }catch(PDOException $e){
            return false;
        }
        
    }

    public function deleteSalle() {
        require_once 'db.php';
    
        $deleteQuery = "DELETE FROM salles WHERE id = :id";
        
        $stmt = $this->db->prepare($deleteQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function searchAllForCentre() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM salles WHERE id_centres_de_formation = :id_centres_de_formation";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
        $stmt->execute();
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $result;
    }

    public function searchCentreForId() {
        require_once 'db.php';
    
        $existingQuery = "SELECT id_centres_de_formation FROM salles WHERE id = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['id_centres_de_formation'];
    }

    
    public function updateNom() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE salles SET nom = :nom WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':nom', $this->nom, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateCapacite() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE salles SET capacite_accueil = :capacite_accueil WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':capacite_accueil', $this->capacite_accueil, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getSalleForAdmin(){
        require_once 'db.php';
        $query = "SELECT salles.*, centres_de_formation.nomCentre
            FROM salles
            JOIN centres_de_formation ON centres_de_formation.id = salles.id_centres_de_formation";

        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->execute();
            
            $result =  $queryexec->fetchAll(PDO::FETCH_ASSOC);

            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getEquipementForSalle() {
        require_once 'db.php';
    
        $query = "SELECT equipements.id, equipements.nom AS equipement_nom, equipements.quantite,salles.id AS id_salles, salles.nom AS salle_nom
        FROM salles 
        JOIN equipements ON equipements.id_salles = salles.id
        WHERE salles.id_centres_de_formation = :id_centres_de_formation
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
        $stmt->execute();
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $result;
    }

    public function getSallesForSessions($sessionIds) {
        $placeholders = implode(',', array_fill(0, count($sessionIds), '?'));
        $query = "SELECT DISTINCT s.id, s.nom, s.capacite_accueil
                  FROM salles s
                  JOIN events e ON s.id = e.id_salles
                  JOIN event_sessions es ON e.id = es.id_events
                  WHERE es.id IN ($placeholders)
                  ORDER BY s.nom";
        
        $stmt = $this->db->prepare($query);
        foreach ($sessionIds as $i => $id) {
            $stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSalleNom($id) {
        $query = "SELECT nom FROM salles WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['nom'] : null;
    }
}
