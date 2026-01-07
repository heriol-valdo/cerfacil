<?php
require_once 'db.php';

class EntrepriseType extends Database {
    // Clés étrangères
    public $id_entreprises;
    public $id_centres_de_formation = null;
    public $is_accueil = null;
    public $is_financeur = null;

    public function addEntrepriseType() {
        // Validation des paramètres avant insertion
        if (empty($this->id_entreprises)) {
            throw new InvalidArgumentException("ID entreprise est requis");
        }
    
        $query = "INSERT INTO entreprises_type (
            id_entreprises, 
            id_centres_de_formation, 
            is_accueil, 
            is_financeur
        ) VALUES (
            :id_entreprises, 
            :id_centres_de_formation, 
            :is_accueil, 
            :is_financeur
        )";
    
        try {
            $stmt = $this->db->prepare($query);
            
            $stmt->bindValue(":id_entreprises", $this->id_entreprises, PDO::PARAM_INT);      
            $stmt->bindValue(":id_centres_de_formation", 
                $this->id_centres_de_formation ?? null, 
                $this->id_centres_de_formation ? PDO::PARAM_INT : PDO::PARAM_NULL
            );
            $stmt->bindValue(":is_accueil", $this->is_accueil ? 1 : 0, PDO::PARAM_INT);
            $stmt->bindValue(":is_financeur", $this->is_financeur ? 1 : 0, PDO::PARAM_INT);
    
            $result = $stmt->execute();
            
            if (!$result) {
                // Récupérer les informations détaillées sur l'erreur
                $errorInfo = $stmt->errorInfo();
                throw new PDOException("Erreur d'insertion: " . $errorInfo[2], $errorInfo[1]);
            }
            
            return true;
        } catch (PDOException $e) {    
            // Log de l'erreur détaillée
            error_log("Erreur dans addEntrepriseType: " . $e->getMessage());
            
            // Lever une exception pour une gestion plus fine
            throw $e;
        }
    }
    
    

    function getEntrepriseByType($type) {
        require_once 'db.php';

        $query="";

        if($type == "financeur") {
            $query = "SELECT * FROM entreprises_type 
            JOIN entreprises ON entreprises_type.id_entreprises = entreprises.id
            WHERE is_financeur = 1";
        }
        if($type == "centre") {
            $query = "SELECT * FROM entreprises_type 
            JOIN entreprises ON entreprises_type.id_entreprises = entreprises.id
            WHERE id_centres_de_formation IS NOT NULL";
        }
        if($type == "accueil") {
            $query = "SELECT * FROM entreprises_type 
            JOIN entreprises ON entreprises_type.id_entreprises = entreprises.id
            WHERE is_accueil = 1";
        }

        $queryexec = $this->db->prepare($query);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function deleteEntrepriseType() {
        require_once 'db.php';
    
        $deleteQuery = "DELETE FROM entreprises_type WHERE id_entreprises = :id_entreprises";;
        
        $stmt = $this->db->prepare($deleteQuery);
        $stmt->bindValue(":id_entreprises", $this->id_entreprises, PDO::PARAM_INT);
        $stmt->execute();
    }
}
