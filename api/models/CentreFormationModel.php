<?php // /models/CentreFormation.php
require_once 'db.php';
/*=========================================================================================
CentreFormationModel.php => Résumé des fonctions
===========================================================================================
> addCentre() : ajouter un centre
> boolId() : retourne True si : id
> searchForId() : retourne * en fonction : id
> searchAll() : Renvoie noms de tous les centres, leur entreprise rattaché et le nb de formations
> searchCentreForOne() : Renvoie le nom du centre, le nom de la formation et son nb de sessions actives
> searchOneCentre() : Renvoie les infos d'un centre, son entreprise et le nb de formations
> searchFormationForOne : Renvoie le nomFormation, prixFormation, lienFranceFormation et le nbSession
> getCentreFormationDatas() : retourne * en fonction : id
> deleteCentre() 
> updateNom()
> updateAdresse()
> updateCodePostal()
> updateVille()
> updateTelephone()
> updateEntreprise()
===========================================================================================*/

class CentreFormation extends Database{
    public $id;
    public $nomCentre;
    public $adresseCentre;
    public $codePostalCentre;
    public $villeCentre;
    public $telephoneCentre;
    // Clé étrangère
    public $id_entreprises;


    //=============================================================
    // --------------------- boolX ------------------------
    // > TRUE si X existe 1 fois ou plus dans la BDD
    // > FALSE si X existe 0 fois dans la BDD
    //=============================================================

    public function boolId() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM centres_de_formation WHERE id = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    function getCentreFormationDatas() {
        require_once 'db.php';

        $query = "SELECT * FROM centres_de_formation WHERE id = :id";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT); // Utilisation du paramètre $email passé à la méthode
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $res; 
    }

    public function addCentre() {
    
        $insertQuery= "INSERT INTO `centres_de_formation` (`nomCentre`, `adresseCentre`, `codePostalCentre`, `villeCentre`, `telephoneCentre`, `id_entreprises`) 
        VALUES (:nomCentre, :adresseCentre, :codePostalCentre, :villeCentre, :telephoneCentre, :id_entreprises)";
        
        $stmt = $this->db->prepare($insertQuery);

        $stmt->bindParam(":nomCentre", $this->nomCentre, PDO::PARAM_STR);
        $stmt->bindParam(":adresseCentre", $this->adresseCentre, PDO::PARAM_STR);
        $stmt->bindParam(":codePostalCentre", $this->codePostalCentre, PDO::PARAM_STR);
        $stmt->bindParam(":villeCentre", $this->villeCentre, PDO::PARAM_STR);
        $stmt->bindParam(":telephoneCentre", $this->telephoneCentre, PDO::PARAM_STR);
        $stmt->bindParam(":id_entreprises", $this->id_entreprises, PDO::PARAM_INT);

        $stmt->execute();
    }

    
    public function deleteCentre() {
        require_once 'db.php';
    
        $deleteQuery = "DELETE FROM centres_de_formation WHERE id = :id";;
        
        $stmt = $this->db->prepare($deleteQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Renvoie les infos d'un centre
    public function searchForId() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM centres_de_formation WHERE id = :id";
        
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

    public function updateNom() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE centres_de_formation SET nomCentre = :nomCentre WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':nomCentre', $this->nomCentre, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateAdresse() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE centres_de_formation SET adresseCentre = :adresseCentre WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':adresseCentre', $this->adresseCentre, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateCodePostal() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE centres_de_formation SET codePostalCentre = :codePostalCentre WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':codePostalCentre', $this->codePostalCentre, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateVille() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE centres_de_formation SET villeCentre = :villeCentre WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':villeCentre', $this->villeCentre, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateTelephone() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE centres_de_formation SET telephoneCentre = :telephoneCentre WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':telephoneCentre', $this->telephoneCentre, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateEntreprise() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE centres_de_formation SET id_entreprises = :id_entreprises WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':id_entreprises', $this->id_entreprises, PDO::PARAM_INT);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Renvoie les centres de formation avec le nom de l'entreprise rattachée et le nombre de formations proposé
    public function searchAll() {
        require_once 'db.php';

        $searchQuery = "SELECT centres_de_formation.*, entreprises.nomEntreprise, entreprises.id AS entrepriseId, COUNT(formations.id) AS nbFormations
                        FROM centres_de_formation 
                        LEFT JOIN entreprises ON centres_de_formation.id_entreprises = entreprises.id
                        LEFT JOIN formations ON formations.id_centres_de_formation = centres_de_formation.id
                        GROUP BY centres_de_formation.id";     

        $stmt = $this->db->prepare($searchQuery);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    // Renvoie les formations et le nombre de sessions associées actives
    public function searchCentreForOne() {
        require_once 'db.php';

        $searchQuery = "SELECT centres_de_formation.nom, formations.nom, COUNT(session.id) AS nbSessionActive
                        FROM centres_de_formation 
                        JOIN formations ON formations.id_centres_de_formation = centres_de_formation.id
                        LEFT JOIN session ON formations.id = session.id_formations
                                            AND CURRENT_DATE() BETWEEN session.dateDebut AND session.dateFin
                        WHERE centres_de_formation.id = :id
                        GROUP BY formations.nom";
 

        $stmt = $this->db->prepare($searchQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    // Renvoie les formations pour un centre
    public function searchOneCentre() {
        require_once 'db.php';

        $searchQuery = "SELECT
                            centres_de_formation.id AS centreId,   
                            centres_de_formation.nomCentre, 
                            centres_de_formation.adresseCentre, 
                            centres_de_formation.codePostalCentre, 
                            centres_de_formation.villeCentre, 
                            centres_de_formation.telephoneCentre, 
                            entreprises.nomEntreprise, 
                            centres_de_formation.id_entreprises,
                            COUNT(formations.id) AS nbFormations
                        FROM centres_de_formation 
                        LEFT JOIN entreprises ON centres_de_formation.id_entreprises = entreprises.id
                        LEFT JOIN formations ON formations.id_centres_de_formation = centres_de_formation.id
                        WHERE centres_de_formation.id = :id";        

        $stmt = $this->db->prepare($searchQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    function searchFormationForOne() {
        $query = "SELECT formations.nom, formations.prix, formations.lienFranceCompetence, COUNT(session.id) AS nbSessions
        FROM formations 
        LEFT JOIN session ON session.id_formations = formations.id
        WHERE formations.id_centres_de_formation = :id_centres_de_formation
        GROUP BY formations.id, formations.nom, formations.prix, formations.lienFranceCompetence;
        ";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_centres_de_formation", $this->id, PDO::PARAM_INT);
            $stmt->execute(); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    function getSessionsEnCoursEtAvenirFromCentre() {
        $query = "SELECT session.*, formations.nom AS nom_formation
        FROM session
        JOIN formations ON session.id_formations = formations.id
        JOIN centres_de_formation ON centres_de_formation.id = formations.id_centres_de_formation
        WHERE session.id_centres_de_formation = :id_centres_de_formation
                  AND (NOW() BETWEEN session.dateDebut AND session.dateFin
                  OR NOW() < session.dateDebut)";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_centres_de_formation", $this->id, PDO::PARAM_INT);
            $stmt->execute(); // Vous devez exécuter la requête préparée
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    function getSessionsFromCentre() {
        $query = "SELECT session.*, formations.nom AS nom_formation
        FROM session
        JOIN formations ON session.id_formations = formations.id
        JOIN centres_de_formation ON centres_de_formation.id = formations.id_centres_de_formation
        WHERE session.id_centres_de_formation = :id_centres_de_formation";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_centres_de_formation", $this->id, PDO::PARAM_INT);
            $stmt->execute(); // Vous devez exécuter la requête préparée
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    function getFormationsFromCentre() {
        $query = "SELECT *
        FROM formations
        WHERE id_centres_de_formation = :id";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute(); 
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            return [];
        }
    }

    function getMatieresFromCentre() {
        $query = "SELECT matieres.*
        FROM matieres
        JOIN session ON matieres.id_sessions = session.id
        WHERE session.id_centres_de_formation = :id";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute(); 
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            return [];
        }
    }
}