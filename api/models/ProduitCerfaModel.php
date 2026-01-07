<?php
require_once 'db.php';


class Produit extends Database{
    public $id;
    public $nom;
    public $type;
    public $prix_dossier;
    public $prix_abonement;
    public $caracteristique1;

    public $caracteristique2;

    public $caracteristique3;

    public $caracteristique4;
   
    function getProduitCerfaDatasById() {
        $query = "SELECT * FROM produit_cerfa WHERE id = :id";
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);
        return $res;

        
    }

    function getProduitCerfaDatasByIdAbonnement($id) {
        $query = "SELECT * FROM produit_cerfa WHERE id = :id";
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $id, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);
       
        try {
            return $res;
        } catch (PDOException $e) {
            return ['erreur' => $e->getMessage()];
        }
        
    }
    public function boolId() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM produit_cerfa WHERE id = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_STR);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }


    
    public function boolType() {
        require_once 'db.php';
            try {
                $existingQuery = "SELECT COUNT(*) FROM produit_cerfa WHERE type = :type";
                
                $stmt = $this->db->prepare($existingQuery);
                $stmt->bindValue(":type", $this->type, PDO::PARAM_STR);
                $stmt->execute();
        
                $count = $stmt->fetchColumn();
        
                return ($count > 0);
            } catch (PDOException $e) {
                // Log the error or handle it appropriately
                error_log("Database error in typeExists: " . $e->getMessage());
                return false; // or throw an exception
            }
        }
    
    public function addProduitCerfa() {
        try {
            $insertQuery = "INSERT INTO `produit_cerfa` (
                `nom`, `type`, `prix_dossier`, `prix_abonement`, `caracteristique1`, `caracteristique2`, `caracteristique3`, `caracteristique4`) 
                VALUES (
                    :nom, :type, :prix_dossier, :prix_abonement, :caracteristique1, :caracteristique2, :caracteristique3, :caracteristique4
                )";
            
            $stmt = $this->db->prepare($insertQuery);
    
            $stmt->bindParam(":nom", $this->nom, PDO::PARAM_STR);
            $stmt->bindParam(":type", $this->type, PDO::PARAM_STR);
            $stmt->bindParam(":prix_dossier", $this->prix_dossier, PDO::PARAM_STR);
            $stmt->bindParam(":prix_abonement", $this->prix_abonement, PDO::PARAM_STR);
            $stmt->bindParam(":caracteristique1", $this->caracteristique1, PDO::PARAM_STR);
            $stmt->bindParam(":caracteristique2", $this->caracteristique2, PDO::PARAM_STR);
            $stmt->bindParam(":caracteristique3", $this->caracteristique3, PDO::PARAM_STR);
            $stmt->bindParam(":caracteristique4", $this->caracteristique4, PDO::PARAM_STR);
    
            $result = $stmt->execute();
    
            if ($result) {
                return ["success" => true, "message" => "Product added successfully", "id" => $this->db->lastInsertId()];
            } else {
                return ["success" => false, "message" => "Failed to add product"];
            }
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Database error: " . $e->getMessage()];
        }
    }

    public function produitCerfa() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM produit_cerfa ";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    public function updateNom() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE produit_cerfa SET nom = :nom WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':nom', $this->nom, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateType() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE produit_cerfa SET type = :type1 WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':type1', $this->type, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updatePrixDossier() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE produit_cerfa SET prix_dossier = :prix_dossier WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':prix_dossier', $this->prix_dossier, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updatePrixAbonement() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE produit_cerfa SET prix_abonement = :prix_abonement WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':prix_abonement', $this->prix_abonement, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateCaracteristique1() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE produit_cerfa SET caracteristique1 = :caracteristique1 WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':caracteristique1', $this->caracteristique1, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateCaracteristique2() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE produit_cerfa SET caracteristique2 = :caracteristique2 WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':caracteristique2', $this->caracteristique2, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateCaracteristique3() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE produit_cerfa SET caracteristique3 = :caracteristique3 WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':caracteristique3', $this->caracteristique3, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateCaracteristique4() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE produit_cerfa SET caracteristique4 = :caracteristique4 WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':caracteristique4', $this->caracteristique4, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function deleteProduit() {
        require_once 'db.php';
    
        $deleteQuery = "DELETE FROM produit_cerfa WHERE id = :id";;
        
        $stmt = $this->db->prepare($deleteQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();
    }

    
   
    }
