<?php
require_once 'db.php';
/*=========================================================================================
EntrepriseModel.php => Résumé des fonctions
===========================================================================================
> addEntreprise() : ajouter une entreprise
> boolId() : retourne True si : id
> boolSiret() : retourne True si siret
> searchAll() : Renvoie noms de toutes les entreprises et le nb de centres + formations + sessions rattachés
> searchCentreForOne() : Renvoie le nom du centre, le nom de la formation, le nombre de sessions actives
> countCentre() : retourne le nb de centres rattachés à une entreprise (id)
> getEntrepriseDatas() : retourne * en fonction : id
> searchOneEntreprise() : retourne * en fonction : id
> deleteEntreprise() 
> updateSiret()
> updateNomEntreprise()
> updateNomDirecteur()
> updateAdressePostale()
> updateCodePostal()
> updateVille()
> updateApe()
> updateIntracommunautaire()
> updateIsActif()
> updateSoumisTva()
> updateDomaineActivite()
> updateFormeJuridique()
> updateSiteWeb()
> updateFax()
> updateLogo()
> updateEmail()
> updateTelephone()
> updateEntreprise()
===========================================================================================*/

class Entreprise extends Database{
    public $id;
    public $siret;
    public $nomEntreprise;
    public $nomDirecteur; 
    public $adressePostale; 
    public $codePostal;
    public $ville;
    public $telephone;
    public $ape; 
    public $intracommunautaire; 
    public $isActif; 
    public $soumis_tva;
    public $domaineActivite;
    public $formeJuridique;
    public $siteWeb; 
    public $fax; 
    public $logo;
    public $dateCreation;
    public $email;

    function getEntrepriseDatas() {
        require_once 'db.php';

        $query = "SELECT * FROM entreprises WHERE id = :id";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT); // Utilisation du paramètre $email passé à la méthode
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $res; 
    }

    //=============================================================
    // --------------------- boolX ------------------------
    // > TRUE si X existe 1 fois ou plus dans la BDD
    // > FALSE si X existe 0 fois dans la BDD
    //=============================================================

    public function boolId() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM entreprises WHERE id = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_STR);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    public function boolSiret() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM entreprises WHERE siret = :siret";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":siret", $this->siret, PDO::PARAM_STR);
        $stmt->execute();

        $count = $stmt->fetchAll();
        if(!empty($count)){
            return True;
        }else{
            return False;
        }
    }

    public function searchIdBySiret() {
        require_once 'db.php';

        $existingQuery = "SELECT id FROM entreprises WHERE siret = :siret";

        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":siret", $this->siret, PDO::PARAM_STR);
        $stmt->execute();
        $res = $stmt->fetchColumn();

        return $res;
    }

    public function addEntreprise() {
        try{
            $insertQuery= "INSERT INTO `entreprises` (
                `siret`, `nomEntreprise`, `nomDirecteur`, `adressePostale`, `codePostal`, `ville`, `telephone`, `ape`, `intracommunautaire`, `isActif`, `soumis_tva`, `domaineActivite`, `formeJuridique`, `siteWeb`, `fax`, `logo`, `email`) 
                VALUES (
                    :siret, :nomEntreprise, :nomDirecteur, :adressePostale, :codePostal, :ville, :telephone, :ape, :intracommunautaire, :isActif, :soumis_tva, :domaineActivite, :formeJuridique, :siteWeb, :fax, :logo, :email
                    )";
            
            $stmt = $this->db->prepare($insertQuery);
    
            $stmt->bindParam(":siret", $this->siret, PDO::PARAM_STR);
            $stmt->bindParam(":nomEntreprise", $this->nomEntreprise, PDO::PARAM_STR);
            $stmt->bindParam(":nomDirecteur", $this->nomDirecteur, PDO::PARAM_STR);
            $stmt->bindParam(":adressePostale", $this->adressePostale, PDO::PARAM_STR);
            $stmt->bindParam(":codePostal", $this->codePostal, PDO::PARAM_STR);
            $stmt->bindParam(":ville", $this->ville, PDO::PARAM_STR);
            $stmt->bindParam(":telephone", $this->telephone, PDO::PARAM_STR);
            $stmt->bindParam(":ape", $this->ape, PDO::PARAM_STR);
            $stmt->bindParam(":intracommunautaire", $this->intracommunautaire, PDO::PARAM_STR);
            $stmt->bindParam(":isActif", $this->isActif, PDO::PARAM_INT);
            $stmt->bindParam(":soumis_tva", $this->soumis_tva, PDO::PARAM_INT);
            $stmt->bindParam(":domaineActivite", $this->domaineActivite, PDO::PARAM_STR);
            $stmt->bindParam(":formeJuridique", $this->formeJuridique, PDO::PARAM_STR);
            $stmt->bindParam(":siteWeb", $this->siteWeb, PDO::PARAM_STR);
            $stmt->bindParam(":fax", $this->fax, PDO::PARAM_STR);
            $stmt->bindParam(":logo", $this->logo, PDO::PARAM_STR);
            $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
            
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            // Throw a new exception or the caught one to be handled outside
            throw new Exception("Error adding entreprise: " . $e->getMessage());
        }
        
    }

    // Renvoie toutes les infos d'un étudiant
    public function searchForId() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM entreprises WHERE id = :id";
        
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

        
    public function deleteEntreprise() {
        require_once 'db.php';
    
        $deleteQuery = "DELETE FROM entreprises WHERE id = :id";;
        
        $stmt = $this->db->prepare($deleteQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateSiret() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE entreprises SET siret = :siret WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':siret', $this->siret, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateNomEntreprise() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE entreprises SET nomEntreprise = :nomEntreprise WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':nomEntreprise', $this->nomEntreprise, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateNomDirecteur() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE entreprises SET nomDirecteur = :nomDirecteur WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':nomDirecteur', $this->nomDirecteur, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateAdressePostale() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE entreprises SET adressePostale = :adressePostale WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':adressePostale', $this->adressePostale, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateCodePostal() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE entreprises SET codePostal = :codePostal WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':codePostal', $this->codePostal, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateVille() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE entreprises SET ville = :ville WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':ville', $this->ville, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateTelephone() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE entreprises SET telephone = :telephone WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':telephone', $this->telephone, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateApe() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE entreprises SET ape = :ape WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':ape', $this->ape, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateIntracommunautaire() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE entreprises SET intracommunautaire = :intracommunautaire WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':intracommunautaire', $this->intracommunautaire, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateIsActif() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE entreprises SET isActif = :isActif WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':isActif', $this->isActif, PDO::PARAM_BOOL);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateSoumisTva() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE entreprises SET soumis_tva = :soumis_tva WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':soumis_tva', $this->soumis_tva, PDO::PARAM_BOOL);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateDomaineActivite() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE entreprises SET domaineActivite = :domaineActivite WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':domaineActivite', $this->domaineActivite, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateFormeJuridique() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE entreprises SET formeJuridique = :formeJuridique WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':formeJuridique', $this->formeJuridique, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateSiteWeb() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE entreprises SET siteWeb = :siteWeb WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':siteWeb', $this->siteWeb, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateFax() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE entreprises SET fax = :fax WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':fax', $this->fax, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateLogo() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE entreprises SET logo = :logo WHERE id = :id";
            $queryexec = $this->db->prepare($query);
            
            $queryexec->bindValue(':logo', $this->logo, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateEmail() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE entreprises SET email = :email WHERE id = :id";
            $queryexec = $this->db->prepare($query);
            
            $queryexec->bindValue(':email', $this->email, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Renvoie les entreprises en base de données et si ils sont rattachés à des centres de formation
    public function searchAll() {
        require_once 'db.php';

        $searchQuery = "SELECT entreprises.nomEntreprise, 
                            COUNT(centres_de_formation.nomCentre) as nbCentre, 
                            COUNT(formations.id) AS nbFormations, 
                            COUNT(session.id) AS nbSessionActive
                        FROM entreprises 
                        LEFT JOIN centres_de_formation ON centres_de_formation.id_entreprises = entreprises.id
                        LEFT JOIN formations ON formations.id_centres_de_formation = centres_de_formation.id
                        LEFT JOIN session ON formations.id = session.id_formations
                                            AND CURRENT_DATE() BETWEEN session.dateDebut AND session.dateFin
                        GROUP BY entreprises.id";     

        $stmt = $this->db->prepare($searchQuery);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    // Renvoie les informations d'une entreprise
    public function searchOneEntreprise() {
        require_once 'db.php';

        $searchQuery = "SELECT entreprises.*, 
                    entreprises_type.is_accueil, entreprises_type.is_financeur, entreprises_type.id_centres_de_formation,
                    centres_de_formation.nomCentre
                    FROM entreprises
                    LEFT JOIN entreprises_type ON entreprises_type.id_entreprises = entreprises.id
                    LEFT JOIN centres_de_formation ON centres_de_formation.id = entreprises_type.id_centres_de_formation
                    WHERE entreprises.id = :id";       

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

    // Renvoie le nombre de centre rattaché à une entreprise
    public function countCentre() {
        require_once 'db.php';

        $searchQuery = "SELECT COUNT(centres_de_formation.id)
                        FROM entreprises 
                        LEFT JOIN centres_de_formation ON centres_de_formation.id_entreprises = entreprises.id
                        WHERE centres_de_formation.id_entreprises = :id";        

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

    // Renvoie les formations et le nombre de sessions associées actives
    public function searchCentreForOne() {
        require_once 'db.php';

        $searchQuery = "SELECT centres_de_formation.nomCentre, formations.nom, COUNT(session.id) AS nbSessionActive
                FROM centres_de_formation 
                LEFT JOIN formations ON formations.id_centres_de_formation = centres_de_formation.id
                LEFT JOIN session ON formations.id = session.id_formations
                WHERE centres_de_formation.id_entreprises = :id
                    AND (session.dateDebut IS NULL OR CURRENT_DATE() BETWEEN session.dateDebut AND session.dateFin)
                GROUP BY centres_de_formation.nomCentre, formations.nom";
 

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

    public function getEntrepriseForAdmin(){
        require_once 'db.php';
        $query = "SELECT * FROM entreprises";

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
