<?php
require_once 'db.php';
/*=========================================================================================
FormateurModel.php => Résumé des fonctions
===========================================================================================
> addFormateur() : ajouter un formateur
> boolId() : retourne True si : id_users
> boolIdRole : retourne True si : id
> checkFormateurExist() : retourne True si : id (formateur)
> searchAllForSalle() : Renvoie tous les équipements d'une salle (id_salles)
> searchCentreForIdUsers : Retourne le centre pour : id_users
> searchForId() : Renvoie * pour id_users
> searchFinancedForEntreprise : Renvoie les infos des étudiants financés : nom, prenom, formation, centre
> searchCoursForIdUsers : renvoie les infos de tous les cours pour un formateur
> searchCoursActifForIdUsers : Renvoie infos sur cours actifs en fonction : id_users
> searchSessionForId : renvoie id_session pour : id
> getProfilFormateur() : Renvoie * pour id_users
> getFormateurIdByUserId() : Renvoie id (formateur) en fonction : id_users
> getFormateurName() : Renvoie nom/prénom selon id (formateur)
> update : firstname, lastname, adressePostale, codePostal, ville, telephone, centreFormation, siret
===========================================================================================*/

class Formateur extends Database{
    public $id;
    public $firstname;
    public $lastname;
    public $adressePostale;
    public $codePostal;
    public $ville;
    public $telephone;
    public $siret;
    //Clés étrangères
    public $id_centres_de_formation;
    public $id_users;

    public function addFormateur() {
        $insertQuery= "INSERT INTO `formateurs` (`firstname`, `lastname`, `adressePostale`, `codePostal`, `ville`, `telephone`, `siret`, `id_centres_de_formation`, `id_users`) 
        VALUES (:firstname, :lastname, :adressePostale, :codePostal, :ville, :telephone, :siret, :id_centres_de_formation, :id_users)";
        
        $stmt = $this->db->prepare($insertQuery);
    
        $stmt->bindParam(":firstname", $this->firstname, PDO::PARAM_STR);
        $stmt->bindParam(":lastname", $this->lastname, PDO::PARAM_STR);
        $stmt->bindParam(":adressePostale", $this->adressePostale, PDO::PARAM_STR);
        $stmt->bindParam(":codePostal", $this->codePostal, PDO::PARAM_STR);
        $stmt->bindParam(":ville", $this->ville, PDO::PARAM_STR);
        $stmt->bindParam(":telephone", $this->telephone, PDO::PARAM_STR);
        $stmt->bindParam(":siret", $this->siret, PDO::PARAM_STR);
        $stmt->bindParam(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
        $stmt->bindParam(":id_users", $this->id_users, PDO::PARAM_INT);
    
        return $stmt->execute();
    }
    

    function searchCentreForIdUsers() {
        require_once 'db.php';

        $query = "SELECT centres_de_formation.id
        FROM centres_de_formation
        JOIN formateurs ON formateurs.id_centres_de_formation = centres_de_formation.id
        WHERE formateurs.id_users = :id_users;
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);

        return $res['id'];
    }

    function searchCentreForId() {
        require_once 'db.php';

        $query = "SELECT id_centres_de_formation
        FROM formateurs
        WHERE id = :id;
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
        $queryexec->execute();
        $result = $queryexec->fetch(PDO::FETCH_ASSOC);

        return $result['id_centres_de_formation'];
    }

    function searchCoursForIdUsers() {
        require_once 'db.php';

        $query = "SELECT cours.debut, cours.fin, cours.modalites, cours.nom AS cours_nom, salles.nom AS salle_nom
        FROM cours
        JOIN session ON cours.id_session = session.id
        JOIN etudiants ON etudiants.id_session = session.id
        JOIN salles ON cours.id_salles = salles.id
        JOIN formateurs ON cours.id_formateurs = formateurs.id
        WHERE formateurs.id_users = :id_users;
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $result = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    function searchCoursActifForIdUsers() {
        require_once 'db.php';

        $query = "SELECT cours.debut, cours.fin, cours.modalites, cours.nom AS cours_nom, salles.nom AS salle_nom
        FROM cours
        JOIN session ON cours.id_session = session.id
        JOIN salles ON cours.id_salles = salles.id
        JOIN formateurs ON cours.id_formateurs = formateurs.id
        WHERE formateurs.id_users = :id_users AND debut > CURDATE();
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $result = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getFormateurDatasByUserId() {
        $query = "SELECT * FROM formateurs WHERE id_users=:id_users";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function searchSessionForId() {
        require_once 'db.php';

        $existingQuery = "SELECT id_session FROM formateurs WHERE id = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['id_session'];
        } else {
            return null;
        }
    }

    function getProfilFormateur() {
        require_once 'db.php';

        $query = "SELECT *
        FROM formateurs AS t1
        JOIN users AS t2 ON t1.id_users = t2.id   
        WHERE t1.id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT); 
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $res; 
    }

    function getFormateurIdByUserId() {
        require_once 'db.php';

        $query = "SELECT id FROM formateurs WHERE id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_NUM);

        return $res;
    }

    function getFormateurName() {
        $query = "SELECT formateurs.firstname, formateurs.lastname FROM formateurs WHERE id = :id";
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
        $queryexec->execute();
        return $res = $queryexec->fetch(PDO::FETCH_ASSOC);
    }

    // Renvoie True si le user existe
    public function boolId() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM formateurs WHERE id_users = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id_users, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();
        echo $count;

        return ($count >= 1);
    }

    public function boolIdRole() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM formateurs WHERE id = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    public function checkFormateurExist() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM formateurs WHERE id = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    // Renvoie toutes les infos d'un formateur
    public function searchForId() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM formateurs WHERE id_users = :id_users";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    public function updateFirstname() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE formateurs SET firstname = :firstname WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateLastname() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE formateurs SET lastname = :lastname WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateAdressePostale() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE formateurs SET adressePostale = :adressePostale WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':adressePostale', $this->adressePostale, PDO::PARAM_STR);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateCodePostal() {
        require_once 'db.php';

    
        try {
            $query = "UPDATE formateurs SET codePostale = :codePostal WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':codePostal', $this->codePostal, PDO::PARAM_STR);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
            
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateVille() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE formateurs SET ville = :ville WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':ville', $this->ville, PDO::PARAM_STR);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateTelephone() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE formateurs SET telephone = :telephone WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':telephone', $this->telephone, PDO::PARAM_STR);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateCentreFormation() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE formateurs SET id_centres_de_formation = :id_centres_de_formation WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':id_centres_de_formation', $this->id_centres_de_formation, PDO::PARAM_INT);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateSiret() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE formateurs SET siret = :siret WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':siret', $this->siret, PDO::PARAM_STR);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function getProfil() {
        require_once 'db.php';

        $query = "SELECT formateurs.*, users.email, centres_de_formation.nomCentre, role.role
        FROM formateurs
        JOIN users ON formateurs.id_users = users.id  
        JOIN centres_de_formation ON centres_de_formation.id = formateurs.id_centres_de_formation
        JOIN role ON role.id = users.id_role 
        WHERE formateurs.id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $res; 
    }

    public function getFormateurForAdmin(){
        require_once 'db.php';
        $query = "SELECT users.email, formateurs.*, centres_de_formation.nomCentre
            FROM formateurs
            JOIN users ON formateurs.id_users = users.id
            JOIN centres_de_formation ON centres_de_formation.id = formateurs.id_centres_de_formation";

        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->execute();
            
            $result =  $queryexec->fetchAll(PDO::FETCH_ASSOC);

            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getFormateursByCentre(){
        require_once "db.php";
        $targetCentre = $this->id_centres_de_formation;

        $query = "SELECT formateurs.*, 
        users.email,
        centres_de_formation.nomCentre AS nom_centre,
        role.role
        FROM formateurs
        JOIN users ON formateurs.id_users = users.id
        JOIN centres_de_formation ON centres_de_formation.id = formateurs.id_centres_de_formation
        JOIN role ON users.id_role = role.id ";

        if($targetCentre != "all"){
            $query .= "WHERE formateurs.id_centres_de_formation = :id_centres_de_formation";
        }

        try {
            $queryexec = $this->db->prepare($query);
            if($targetCentre != "all"){
                $queryexec->bindParam(':id_centres_de_formation', $this->id_centres_de_formation, PDO::PARAM_INT);
            }
            $queryexec->execute();
            
            $result =  $queryexec->fetchAll(PDO::FETCH_ASSOC);

            return $result ?: [];
        } catch (PDOException $e) {
            echo "
            ". $e->getMessage();
        }
    }

    
    public function getAbsenceList(){
        $query= "SELECT absences.*, 
        etudiants.firstname, etudiants.lastname, etudiants.id AS etudiants_id, 
        centres_de_formation.nomCentre, 
        session.dateDebut AS session_dateDebut, session.dateFin AS session_dateFin, 
        formations.id AS formations_id, formations.nom AS formations_nom
        FROM absences
        JOIN etudiants ON etudiants.id = absences.id_etudiants
        JOIN centres_de_formation ON centres_de_formation.id = etudiants.id_centres_de_formation
        JOIN session ON session.id = etudiants.id_session
        JOIN formations ON formations.id = session.id_formations
        JOIN formateurs_participant_session ON formateurs_participant_session.id = session.id
        WHERE formateurs_participant_session.id_formateurs = :id_formateurs
        ORDER BY absences.dateDebut DESC
        ";

        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(':id_formateurs', $this->id, PDO::PARAM_INT);
            $queryexec->execute();
            $result = $queryexec->fetchAll(PDO::FETCH_ASSOC);

            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function getAllFormations_formateur() {
        $query = "SELECT formations.*, formations.nom AS formations_nom, centres_de_formation.nomCentre
        FROM formations
        JOIN centres_de_formation ON centres_de_formation.id = formations.id_centres_de_formation
        JOIN session ON session.id_formations = formations.id
        JOIN etudiants ON etudiants.id_session = session.id
        JOIN formateurs_participant_session ON formateurs_participant_session.id = session.id
        WHERE formateurs_participant_session.id_formateurs = :id_formateurs;
        ";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_formateurs", $this->id, PDO::PARAM_INT);
            $stmt->execute(); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }   
    }

    public function getStructure(){
        require_once 'db.php';       
        $query = "SELECT id_centres_de_formations
            FROM formateurs
            WHERE id_users = :id_users";

        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindParam(':id_users', $this->id_users, PDO::PARAM_INT);
            
            $queryexec->execute();
            
            $result =  $queryexec->fetch (PDO::FETCH_ASSOC);

            return $result ? : null;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Renvoi true si au moins étudiant fait partie du centre cherché 
    // et est suivi par au moins une personne de l'entreprise du financeur
    public function searchForIdUsersAndIdCentre() {
        require_once 'db.php';
    
        $query = "SELECT * 
                FROM formateurs 
                WHERE id_centres_de_formation = :id_centres_de_formation
                AND id_users = :id_users";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
            $stmt->bindParam(":id_users", $this->id_users, PDO::PARAM_INT);

            $stmt->execute(); 
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result ? : null;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Donne toutes les infos pour... 
    public function getRoleInfosForId() {
        require_once 'db.php';

        $query = "SELECT formateurs.*, 
                        centres_de_formation.nomCentre 
                        FROM formateurs 
                        JOIN centres_de_formation ON centres_de_formation.id = formateurs.id_centres_de_formation 
                        WHERE formateurs.id_users = :id_users; ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }
    
    function sessionsFormateursByCentre() {
        $query = "SELECT formateurs_participant_session.*
        FROM formateurs_participant_session
        JOIN formateurs ON formateurs_participant_session.id_formateurs = formateurs.id
        WHERE formateurs.id_centres_de_formation = :id_centres_de_formation
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
            $stmt->execute(); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }   
    }
}
