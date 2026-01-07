<?php // /models/EquipementModel.php
require_once 'db.php';
/*=========================================================================================
EquipementModel.php => Résumé des fonctions
===========================================================================================
> addEquipement() : ajouter un équipement
> boolId() : retourne True si : id
> boolIdForNomAndIdSalles() : retourne True si un nom existe dans une salle (id_salles)
> searchAllForSalle() : Renvoie tous les équipements d'une salle (id_salles)
> searchAllForId() : Renvoie * pour un id
> deleteEquipement() 
> updateNom()
> updateQuantite()
> updateSalle()
===========================================================================================*/

class Equipement extends Database{
    public $id;
    public $nom;
    public $quantite;
    //Clés étrangères
    public $id_salles;

    public function boolId() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM equipements WHERE id = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_STR);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    public function boolIdForNomAndIdSalles() {
        require_once 'db.php';
    
        $existingQuery = "SELECT id FROM equipements WHERE LOWER(nom) = LOWER(:nom) AND id_salles = :id_salles";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":nom", $this->nom, PDO::PARAM_STR);
        $stmt->bindValue(":id_salles", $this->id_salles, PDO::PARAM_INT);

        $stmt->execute();
    
        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    public function addEquipement() {
    
        $insertQuery= "INSERT INTO `equipements` (`nom`, `quantite`, `id_salles`) VALUES (:nom, :quantite, :id_salles)";
        
        $stmt = $this->db->prepare($insertQuery);

        $stmt->bindParam(":nom", $this->nom, PDO::PARAM_STR);
        $stmt->bindParam(":quantite", $this->quantite, PDO::PARAM_INT);
        $stmt->bindParam(":id_salles", $this->id_salles, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function deleteEquipement() {
        require_once 'db.php';
    
        $deleteQuery = "DELETE FROM equipements WHERE id = :id";;
        
        $stmt = $this->db->prepare($deleteQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function searchAllForSalle() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM equipements WHERE id_salles = :id_salles";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id_salles", $this->id_salles, PDO::PARAM_INT);
        $stmt->execute();
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $result;
    }

    public function searchAllForId() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM equipements WHERE id = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $result;
    }

    public function updateNom() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE equipements SET nom = :nom WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':nom', $this->nom, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateQuantite() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE equipements SET quantite = :quantite WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':quantite', $this->quantite, PDO::PARAM_INT);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateSalle() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE equipements SET id_salles = :id_salles WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':id_salles', $this->id_salles, PDO::PARAM_INT);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getEquipementForAdmin(){
        require_once 'db.php';
        $query = "SELECT equipements.id, equipements.nom AS equipement_nom, equipements.quantite, equipements.id_salles, salles.nom AS salle_nom
        FROM equipements
        JOIN salles ON salles.id = equipements.id_salles";


        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->execute();
            
            $result =  $queryexec->fetchAll(PDO::FETCH_ASSOC);

            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
