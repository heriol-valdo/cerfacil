<?php
require_once 'db.php';
/*=========================================================================================
FormationModel.php => Résumé des fonctions
===========================================================================================
> addFormation() : ajouter un formation
> getFormationsFromCentre() : Renvoie * en fonction : id_centres_de_formation
> getOneFormationFromCentre() : Renvoie * en fonction : id (formations)
> searchCentreForId : renvoie id_centres_de_formation pour : id
> searchForId : renvoie * pour : id
> searchCoursActifForIdCentreAndSession : renvoie cours (debut, fin, modalites, nom), salle (nom), formateurs (nom, prenom) pour id_centres_de_formation et id_session actif
> update : nom, prix, lienFranceCompetence
===========================================================================================*/

class Formation extends Database {
    public $id;
    public $nom;
    public $prix;
    public $lienFranceCompetence; 
    // Clés étrangères
    public $id_centres_de_formation;

    function addFormation() {

        $insertQuery = "INSERT INTO `formations` (`nom`, `prix`, `lienFranceCompetence`, `id_centres_de_formation`) VALUES (:nom, :prix, :lienFranceCompetence, :id_centres_de_formation)";
        
        try {
            $stmt = $this->db->prepare($insertQuery);
            $stmt->bindParam(":nom", $this->nom, PDO::PARAM_STR);
            $stmt->bindParam(":prix", $this->prix, PDO::PARAM_STR);
            $stmt->bindParam(":lienFranceCompetence", $this->lienFranceCompetence, PDO::PARAM_STR); 
            $stmt->bindParam(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
            return $stmt->execute();

        } catch (PDOException $e) {
            return false;
        }
    }

    function getFormationsFromCentre() {
        $targetCentre = $this->id_centres_de_formation;

        $query = "SELECT * FROM formations";

        if($targetCentre != "all"){
           $query .= " WHERE id_centres_de_formation = :id_centres_de_formation";
        }

        try {
            $stmt = $this->db->prepare($query);
            if($targetCentre != "all"){
                $stmt->bindParam(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
            }
            $stmt->execute(); // You need to execute the prepared statement
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return !empty($result) ? $result : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    function getOneFormationFromCentre() {
        $query = "SELECT * FROM formations WHERE id = :id";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute(); // You need to execute the prepared statement
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }


    function searchCoursActifForIdCentreAndSession() {
        require_once 'db.php';

        $query = "SELECT cours.debut, cours.fin, cours.modalites, cours.nom AS cours_nom, salles.nom AS salle_nom, formateurs.firstname AS formateur_prenom, formateurs.lastname AS formateur_nom 
        FROM cours
        JOIN session ON cours.id_session = session.id
        JOIN etudiants ON etudiants.id_session = session.id
        JOIN salles ON cours.id_salles = salles.id
        JOIN formateurs ON cours.id_formateurs = formateurs.id
        JOIN formations ON session.id_formations = formations.id
        WHERE formations.id_centres_de_formation = :id_centres_de_formation AND debut > CURDATE() AND formations.id_session = id_session;
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
        $queryexec->bindValue(":id_session", $this->id_session, PDO::PARAM_INT);
        $queryexec->execute();
        $result = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    function searchCentreForId() {
        require_once 'db.php';

        $query = "SELECT id_centres_de_formation
        FROM formations
        WHERE id = :id;
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);

        return $res['id_centres_de_formation'];
    }

    function searchForId() {
        require_once 'db.php';

        $query = "SELECT *
        FROM formations
        WHERE id = :id;
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);

        return $res;
    }

    function updateNom(){
        require_once 'db.php';
    
        try {
            $query = "UPDATE formations SET nom = :nom WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':nom', $this->nom, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function updatePrix(){
        require_once 'db.php';
    
        try {
            $query = "UPDATE formations SET prix = :prix WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':prix', $this->prix, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function updateLien(){
        require_once 'db.php';
    
        try {
            $query = "UPDATE formations SET lienFranceCompetence = :lienFranceCompetence WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':lienFranceCompetence', $this->lienFranceCompetence, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getFormationForAdmin(){
        require_once 'db.php';
        $query = "SELECT formations.*, centres_de_formation.nomCentre
            FROM formations
            JOIN centres_de_formation ON centres_de_formation.id = formations.id_centres_de_formation";

        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->execute();
            
            $result =  $queryexec->fetchAll(PDO::FETCH_ASSOC);

            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getFormationForGestionnaire($idcentreformation){
        require_once 'db.php';
        $query = "SELECT formations.*, centres_de_formation.nomCentre
            FROM formations
            JOIN centres_de_formation ON centres_de_formation.id = formations.id_centres_de_formation
            WHERE formations.id_centres_de_formation = :idcentreformation;
            ";

        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(':idcentreformation', $idcentreformation, PDO::PARAM_INT);
            $queryexec->execute();
            
            $result =  $queryexec->fetchAll(PDO::FETCH_ASSOC);

            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function deleteFormation(){
        require_once 'db.php';
        $query = "DELETE FROM formations WHERE id = :id";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
        $queryexec->execute();
    }

    
    function getSessionsFromCentre() {
        $targetCentre = $this->id_centres_de_formation;

        $query = "SELECT session.*
        FROM session
        JOIN formations ON session.id_formations = formations.id";
    
        if($targetCentre != "all"){
            $query .= " WHERE formations.id_centres_de_formation = :id_centres_de_formation";
        }
        
        try {
            $stmt = $this->db->prepare($query);
            if($targetCentre != "all"){
                $stmt->bindParam(":id_centres_de_formation", $targetCentre, PDO::PARAM_INT);
            }
            $stmt->execute(); // Vous devez exécuter la requête préparée
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }
}
    

