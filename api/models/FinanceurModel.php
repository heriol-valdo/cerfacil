<?php
require_once 'db.php';
/*=========================================================================================
FinanceurModel.php => Résumé des fonctions
===========================================================================================
> addFinanceur() : ajouter un financeur
> boolId() : retourne True si : id_users
> searchAllForSalle() : Renvoie tous les équipements d'une salle (id_salles)
> searchForId() : Renvoie * pour id_users
> searchFinancedForEntreprise : Renvoie les infos des étudiants financés : nom, prenom, formation, centre
> getProfilFinanceur() : Renvoie * pour id_users
> getFinanceurIdByUserId() : Renvoie id (financeur) en fonction : id_users
> updateFirstname
> updateLastname
> updateAdressePostale
> updateCodePostal()
> updateVille()
> updateTelephone()
> updateCentreFormation()
> updateSiret()
===========================================================================================*/

class Financeur extends Database{
    public $id;
    public $firstname;
    public $lastname;
    public $type_financeur;
    //Clés étrangères
    public $id_entreprises;
    public $id_users;

    public function getProfilFinanceur() {
        require_once 'db.php';

        $query = "SELECT *
        FROM conseillers_financeurs AS t1
        JOIN users AS t2 ON t1.id_users = t2.id   
        WHERE t1.id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT); 
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $res; 

    }

    public function getFinanceurDatas(){
        $query = "SELECT * FROM conseillers_financeurs WHERE id=:id";
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    function searchEntrepriseForIdUsers() {
        require_once 'db.php';

        $query = "SELECT id_entreprises
        FROM conseillers_financeurs
        WHERE id_users = :id_users;
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindParam(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);

        return $res['id_entreprises'];
    }


    public function addFinanceur() {
    
        $insertQuery= "INSERT INTO `conseillers_financeurs` (`firstname`, `lastname`, `type_financeur`, `id_entreprises`, `id_users`) VALUES (:firstname, :lastname, :type_financeur, :id_entreprises, :id_users)";
        
        $stmt = $this->db->prepare($insertQuery);

        $stmt->bindParam(":firstname", $this->firstname, PDO::PARAM_STR);
        $stmt->bindParam(":lastname", $this->lastname, PDO::PARAM_STR);
        $stmt->bindParam(":type_financeur", $this->type_financeur, PDO::PARAM_STR);
        $stmt->bindParam(":id_entreprises", $this->id_entreprises, PDO::PARAM_INT);
        $stmt->bindParam(":id_users", $this->id_users, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getFinanceurIdByUserId() {

        $query = "SELECT id FROM conseillers_financeurs WHERE id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();

        $res = $queryexec->fetch(PDO::FETCH_ASSOC);

        return $res['id'];
    }

    public function boolId() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM conseillers_financeurs WHERE id_users = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id_users, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    public function checkExist() {  
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM conseillers_financeurs WHERE id = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    public function updateFirstname() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE conseillers_financeurs SET firstname = :firstname WHERE id_users = :id_users";
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
            $query = "UPDATE conseillers_financeurs SET lastname = :lastname WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Non-utilisé pour le moment
    public function updateTelephone() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE gestionnaires_centre SET telephone = :telephone WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':telephone', $this->telephone, PDO::PARAM_STR);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateTypeFinanceur() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE conseillers_financeurs SET type_financeur = :type_financeur WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':type_financeur', $this->type_financeur, PDO::PARAM_STR);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } 

    public function updateEntreprise() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE conseillers_financeurs SET id_entreprises = :id_entreprises WHERE id_users = :id_users";
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

        $existingQuery = "SELECT * FROM conseillers_financeurs WHERE id_users = :id_users";
        
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

    public function searchFinancedForEntreprise() {
        require_once 'db.php';

        $existingQuery = "SELECT etudiants.lastname, etudiants.firstname, formations.nom, session.dateDebut, session.dateFin centres_de_formation.nomCentre 
                        FROM etudiants 
                        JOIN conseillers_financeurs ON etudiants.id_conseillers_financeurs = conseillers_financeurs.id
                        JOIN entreprises ON conseillers_financeurs.id_entreprises = entreprises.id
                        JOIN centres_de_formation ON etudiants.id_centres_de_formation = centres_de_formation.id
                        JOIN session ON etudiants.id_session = session.id
                        JOIN formations ON session.id_formations = formations.id
                        WHERE entreprises.id = :entreprises_id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":entreprises_id", $this->id_entreprises, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    public function searchCentreForEntreprise() {
        require_once 'db.php';

        $query = "SELECT centres_de_formation.* 
                        FROM centres_de_formation 
                        JOIN etudiants ON etudiants.id_centres_de_formation = centres_de_formation.id
                        JOIN conseillers_financeurs ON etudiants.id_conseillers_financeurs = conseillers_financeurs.id
                        WHERE conseillers_financeurs.id_entreprises = :id_entreprises";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_entreprises", $this->id_entreprises, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    function getProfil() {
        require_once 'db.php';

        $query = "SELECT conseillers_financeurs.*,  users.email, role.role
        FROM conseillers_financeurs
        JOIN users ON conseillers_financeurs.id_users = users.id   
        JOIN role ON role.id = users.id_role
        WHERE conseillers_financeurs.id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $res; 
    }

    public function getFinanceurForAdmin(){
        require_once 'db.php';
        $query = "SELECT users.email, conseillers_financeurs.*, entreprises.nomEntreprise
            FROM conseillers_financeurs
            JOIN users ON users.id = conseillers_financeurs.id_users
            JOIN entreprises ON entreprises.id = conseillers_financeurs.id_entreprises";


        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->execute();
            
            $result =  $queryexec->fetchAll(PDO::FETCH_ASSOC);

            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getFinanceurForCentre($centre) {
        require_once 'db.php';
        $query = "SELECT conseillers_financeurs.*
                  FROM etudiants
                  JOIN conseillers_financeurs ON etudiants.id_conseillers_financeurs = conseillers_financeurs.id
                  WHERE etudiants.id_centres_de_formation = :centre";
        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->execute(['centre' => $centre]);
    
            $result =  $queryexec->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }    

    function getIdEntrepriseByUserId() {
        require_once 'db.php';

        $query = "SELECT id_entreprises
        FROM conseillers_financeurs 
        WHERE id_users = :id_users
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);

        return $res['id_entreprises'];
    }

    public function searchEtudiantsFromEntrepriseAndCentre($idCentre) { 
        require_once 'db.php';

        $query = "SELECT *
                        FROM etudiants 
                        JOIN conseillers_financeurs ON etudiants.id_conseillers_financeurs = conseillers_financeurs.id
                        WHERE etudiants.id_centres_de_formation = :idCentre
                        AND conseillers_financeurs.id_entreprises = :id_entreprises";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_entreprises", $this->id_entreprises, PDO::PARAM_INT);
        $stmt->bindValue(":idCentre", $idCentre, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    public function getAbsenceList() {
        require_once 'db.php';
        
        $query = "SELECT absences.*, 
        etudiants.firstname, etudiants.lastname, etudiants.id AS etudiants_id, 
        centres_de_formation.nomCentre, 
        session.dateDebut AS session_dateDebut, session.dateFin AS session_dateFin,
        formations.id AS formations_id, formations.nom AS formations_nom
        FROM absences
        JOIN etudiants ON etudiants.id = absences.id_etudiants
        JOIN centres_de_formation ON centres_de_formation.id = etudiants.id_centres_de_formation
        JOIN session ON session.id = etudiants.id_session
        JOIN formations ON formations.id = session.id_formations 
        JOIN conseillers_financeurs ON conseillers_financeurs.id = etudiants.id_conseillers_financeurs
        JOIN entreprises ON entreprises.id = conseillers_financeurs.id_entreprises
        WHERE entreprises.id = :id_entreprises
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

    function getAllFormations_financeur() {
        $query = "SELECT formations.*, formations.nom AS formations_nom, centres_de_formation.nomCentre
        FROM formations
        JOIN centres_de_formation ON centres_de_formation.id = formations.id_centres_de_formation
        JOIN session ON formations.id = session.id_formations
        JOIN etudiants ON etudiants.id_session = session.id
        JOIN conseillers_financeurs ON conseillers_financeurs.id = etudiants.id_conseillers_financeurs
        WHERE conseillers_financeurs.id_entreprises = :id_entreprises;
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

    function getAllSessions_financeur() {
        $query = "SELECT session.*, formations.nom AS nom_formation
        FROM session
        INNER JOIN formations ON session.id_formations = formations.id
        JOIN etudiants ON etudiants.id_session = session.id
        JOIN conseillers_financeurs ON conseillers_financeurs.id = etudiants.id_conseillers_financeurs
        WHERE conseillers_financeurs.id_entreprises = :id_entreprises
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

    
    function getAllEtudiants_financeur() {
        $query = "SELECT etudiants.*
        FROM etudiants
        JOIN session ON session.id = etudiants.id_session
        JOIN conseillers_financeurs ON conseillers_financeurs.id = etudiants.id_conseillers_financeurs
        WHERE conseillers_financeurs.id_entreprises = :id_entreprises
        ORDER BY etudiants.lastname    
        ";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_entreprises', $this->id_entreprises, PDO::PARAM_INT);
            $stmt->execute(); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function getStructure(){
        require_once 'db.php';       
        $query = "SELECT id_entreprises
            FROM conseillers_financeurs
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

        $query = "SELECT conseillers_financeurs.*, 
                        entreprises.nomEntreprise 
                        FROM conseillers_financeurs 
                        JOIN entreprises ON entreprises.id = conseillers_financeurs.id_entreprises
                        WHERE conseillers_financeurs.id_users = :id_users; ";
        
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

    public function getIdEntrepriseForIdUsers() {
        require_once 'db.php';
    
        $query = "SELECT id_entreprises 
                FROM conseillers_financeurs 
                WHERE id_users = :id_users";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_users", $this->id_users, PDO::PARAM_INT);

            $stmt->execute(); 
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? : null;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
