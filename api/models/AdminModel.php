<?php
require_once 'db.php';

/*=========================================================================================
AdminModel.php => Résumé des fonctions
===========================================================================================
> addAdmin() : ajouter un admin
> searchForId() : retourne * en fonction : id_users
> getIdByUserId() : retourne * en fonction : id_users
> getProfilAdmin() : retourne * en fonction : id (users)
> updateFirstname()
> updateLastname()
> updateTelephone()
> updateLieuTravail()
===========================================================================================*/

class Admin extends Database {
    public $id;
    public $firstname;
    public $lastname;
    public $telephone;
    public $id_users;

    public function addAdmin() {
    
        $addAdminQuery = "INSERT INTO `administrateurs` (`firstname`, `lastname`, `telephone`, `lieu_travail`, `id_users`) VALUES (:firstname, :lastname, :telephone, :lieu_travail, :id_users)";
        
        $stmt = $this->db->prepare($addAdminQuery);

        $stmt->bindParam(":firstname", $this->firstname, PDO::PARAM_STR);
        $stmt->bindParam(":lastname", $this->lastname, PDO::PARAM_STR);
        $stmt->bindParam(":telephone", $this->telephone, PDO::PARAM_STR);
        $stmt->bindParam(":lieu_travail", $this->lieu_travail, PDO::PARAM_STR);
        $stmt->bindParam(":id_users", $this->id_users, PDO::PARAM_INT);

        return $stmt->execute();
    }

    function getAdminIdByUserId() {
        require_once 'db.php';

        $query = "SELECT id FROM administrateurs WHERE id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_NUM);

        return $res;

    }

    function getProfilAdmin() {
        require_once 'db.php';

        $query = "SELECT *
        FROM administrateurs AS t1
        JOIN users AS t2 ON t1.id_users = t2.id   
        WHERE t1.id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $res; 
    }

    function getProfil() {
        require_once 'db.php';

        $query = "SELECT administrateurs.*, users.email, role.role
        FROM administrateurs
        JOIN users ON administrateurs.id_users = users.id   
        JOIN role ON role.id = users.id_role
        WHERE administrateurs.id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $res; 
    }

    public function updateFirstname() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE administrateurs SET firstname = :firstname WHERE id_users = :id_users";
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
            $query = "UPDATE administrateurs SET lastname = :lastname WHERE id_users = :id_users";
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
            $query = "UPDATE administrateurs SET telephone = :telephone WHERE id_users = :id_users";
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
            $query = "UPDATE administrateurs SET lieu_travail = :lieu_travail WHERE id_users = :id_users";
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
            $query = "UPDATE administrateurs SET id_entreprises = :id_entreprises WHERE id_users = :id_users";
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

        $existingQuery = "SELECT * FROM administrateurs WHERE id_users = :id_users";
        
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

    public function getAdminForAdmin(){
        require_once 'db.php';
        $query = "SELECT administrateurs.*, users.email
            FROM administrateurs
            JOIN users ON users.id = administrateurs.id_users";

        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->execute();
            
            $result =  $queryexec->fetchAll(PDO::FETCH_ASSOC);

            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
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
        ORDER BY absences.dateDebut DESC
        ";
           
        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->execute();
            
            $result = $queryexec->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Donne toutes les infos pour... 
    public function getRoleInfosForId() {
        require_once 'db.php';

        $query = "SELECT administrateurs.*,
                        FROM administrateurs WHERE id_users = :id_users";
        
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
