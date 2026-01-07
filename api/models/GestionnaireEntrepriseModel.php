<?php
require_once 'db.php';
/*=========================================================================================
GestionnaireEntreprise.php => Résumé des fonctions
===========================================================================================
> addGestionnaireEntreprise() : ajouter un étudiant
> boolId() : retourne True si : id_users
> searchForId() : Renvoie * pour id_users
> getProfilGestionnaireEntreprise() : Renvoie * pour id_users
> getEtudiantDatasById() : Renvoie * pour id (etudiant)
> getGestionnaireIdByUserId() : Renvoie id (gestionnaire_entreprise) en fonction : id_users
> update : firstname, lastname, telephone, lieutravail, entreprise
===========================================================================================*/


class GestionnaireEntreprise extends Database{
    public $id;
    public $firstname;
    public $lastname;
    public $telephone;
    public $lieu_travail;
    //Clés étrangères
    public $id_entreprises;
    public $id_users;

    function getGestionnaireIdByUserId() {
        require_once 'db.php';

        $query = "SELECT id FROM gestionnaires_entreprise WHERE id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_NUM);

        return $res;
    }

    function searchEntrepriseForIdUsers() {
        require_once 'db.php';

        $query = "SELECT id_entreprises
        FROM gestionnaires_entreprise
        WHERE id_users = :id_users;
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindParam(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);

        return $res['id_entreprises'];
    }


    public function getIdEntrepriseByUserId() {
        $query = "SELECT id_entreprises FROM gestionnaires_entreprise WHERE id_users = :id_users";
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_NUM);
        return $res;
    }

    public function searchIdEntrepriseByUserId() {
        $query = "SELECT id_entreprises FROM gestionnaires_entreprise WHERE id_users = :id_users";
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);
        return $res['id_entreprises'];
    }

    
    public function getAbsencesByIdEntreprise() {
        $query = "SELECT absences.*, etudiants.firstname, etudiants.lastname
        FROM absences 
        JOIN etudiants ON etudiants.id = absences.id_etudiants
        JOIN entreprises ON entreprises.id = etudiants.id_entreprises
        JOIN session ON session.id = etudiants.id_session
        WHERE entreprises.id = :id_entreprises
        AND NOW() BETWEEN session.dateDebut AND session.dateFin";
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_entreprises", $this->id_entreprises, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getAbsenceList(){
        $query= "SELECT absences.*, etudiants.firstname, etudiants.lastname, etudiants.id AS etudiants_id, 
        centres_de_formation.nomCentre, 
        session.dateDebut AS session_dateDebut, session.dateFin AS session_dateFin, 
        formations.id AS formations_id, formations.nom AS formations_nom
        FROM absences
        JOIN etudiants ON etudiants.id = absences.id_etudiants
        JOIN centres_de_formation ON centres_de_formation.id = etudiants.id_centres_de_formation
        JOIN session ON session.id = etudiants.id_session
        JOIN formations ON formations.id = session.id_formations 
        WHERE etudiants.id_entreprises = :id_entreprises
        ORDER BY absences.dateDebut DESC
        ";

        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(':id_entreprises', $this->id_entreprises, PDO::PARAM_INT);
            $queryexec->execute();
            $result = $queryexec->fetchAll(PDO::FETCH_ASSOC);

            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function getProfilGestionnaireEntreprise() {
        require_once 'db.php';

        $query = "SELECT *
        FROM gestionnaires_entreprise AS t1
        JOIN users AS t2 ON t1.id_users = t2.id   
        WHERE t1.id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT); 
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $res; 
    }

    public function addGestionnaireEntreprise() {
    
        $addGestionnaireEntrepriseQuery = "INSERT INTO `gestionnaires_entreprise` (`firstname`, `lastname`, `telephone`, `lieu_travail`, `id_entreprises`, `id_users`) VALUES (:firstname, :lastname, :telephone, :lieu_travail, :id_entreprises, :id_users)";
        
        $stmt = $this->db->prepare($addGestionnaireEntrepriseQuery);

        $stmt->bindParam(":firstname", $this->firstname, PDO::PARAM_STR);
        $stmt->bindParam(":lastname", $this->lastname, PDO::PARAM_STR);
        $stmt->bindParam(":telephone", $this->telephone, PDO::PARAM_STR);
        $stmt->bindParam(":lieu_travail", $this->lieu_travail, PDO::PARAM_STR);
        $stmt->bindParam(":id_entreprises", $this->id_entreprises, PDO::PARAM_INT);
        $stmt->bindParam(":id_users", $this->id_users, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function boolId() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM gestionnaires_entreprise WHERE id_users = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id_users, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    
    public function updateFirstname() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE gestionnaires_entreprise SET firstname = :firstname WHERE id_users = :id_users";
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
            $query = "UPDATE gestionnaires_entreprise SET lastname = :lastname WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateTelephone() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE gestionnaires_entreprise SET telephone = :telephone WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':telephone', $this->telephone, PDO::PARAM_STR);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateLieuTravail() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE gestionnaires_entreprise SET lieu_travail = :lieu_travail WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':lieu_travail', $this->lieu_travail, PDO::PARAM_STR);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateEntreprise() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE gestionnaires_entreprise SET id_entreprises = :id_entreprises WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':id_entreprises', $this->id_entreprises, PDO::PARAM_INT);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Donne toutes les infos pour... 
    public function searchForId() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM gestionnaires_entreprise WHERE id_users = :id_users";
        
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

    function searchCentreForIdUsers() {
        require_once 'db.php';

        $query = "SELECT id_entreprises
        FROM gestionnaires_centre
        WHERE id_users = :id_users;
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);

        return $res['id'];
    }

    function getProfil() {
        require_once 'db.php';

        $query = "SELECT gestionnaires_entreprise.*, users.email, entreprises.nomEntreprise, role.role
        FROM gestionnaires_entreprise
        JOIN users ON gestionnaires_entreprise.id_users = users.id   
        JOIN entreprises ON entreprises.id = gestionnaires_entreprise.id_entreprises
        JOIN role ON role.id = users.id_role
        WHERE gestionnaires_entreprise.id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $res; 
    }

    
    public function getGestionnaireForAdmin(){
        require_once 'db.php';
        $query = "SELECT users.email, gestionnaires_entreprise.*, entreprises.nomEntreprise
            FROM gestionnaires_entreprise
            JOIN entreprises ON entreprises.id = gestionnaires_entreprise.id_entreprises
            JOIN users ON users.id = gestionnaires_entreprise.id_users";

        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->execute();
            
            $result =  $queryexec->fetchAll(PDO::FETCH_ASSOC);

            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function getAllFormations_gEntreprise() {
        $query = "SELECT formations.*, formations.nom AS formations_nom, centres_de_formation.nomCentre
        FROM formations
        JOIN centres_de_formation ON centres_de_formation.id = formations.id_centres_de_formation
        JOIN session ON session.id_formations = formations.id
        JOIN etudiants ON etudiants.id_session = session.id
        WHERE etudiants.id_entreprises = :id_entreprises;
        ";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_entreprises", $this->id_entreprises, PDO::PARAM_INT);
            $stmt->execute(); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }   
    }

    function getAllSessions_gEntreprise() {
        $query = "SELECT session.*, formations.nom AS nom_formation
        FROM session
        INNER JOIN formations ON session.id_formations = formations.id
        JOIN etudiants ON etudiants.id_session = session.id
        WHERE etudiants.id_entreprises = :id_entreprises
        ";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_entreprises", $this->id_entreprises, PDO::PARAM_INT);
            $stmt->execute(); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }   
    }

    function getAllEtudiants_gEntreprise() {
        $query = "SELECT etudiants.*
        FROM etudiants
        JOIN session ON session.id = etudiants.id_session
        WHERE etudiants.id_entreprises = :id_entreprises
        ORDER BY etudiants.lastname
        ";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_entreprises", $this->id_entreprises, PDO::PARAM_INT);
            $stmt->execute(); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function getStructure(){
        require_once 'db.php';       
        $query = "SELECT id_entreprises
            FROM gestionnaires_entreprise
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

    // Donne toutes les infos pour... 
    public function getRoleInfosForId() {
        require_once 'db.php';

        $query = "SELECT gestionnaires_entreprise.*, 
                        entreprises.nomEntreprise 
                        FROM gestionnaires_entreprise 
                        JOIN entreprises ON entreprises.id = gestionnaires_entreprise.id_entreprises
                        WHERE gestionnaires_entreprise.id_users = :id_users; ";
        
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
}
