<?php
require_once 'db.php';
/*=========================================================================================
GestionnaireCentreModel.php => Résumé des fonctions
===========================================================================================
> addGestionnaireCentre() : ajouter un gestionnaire de centre
> boolId() : retourne True si : id_users
> searchForId() : Renvoie * pour id_users
> searchForIdEtudiant : Renvoie * pour id (etudiant)
> searchCentreForIdUsers : Retourne le centre pour : id_users
> getProfilGestionnaireCentre() : Renvoie * pour id_users
> getGestionnaireIdByUserId() : Renvoie id (gestionnaire_centre) en fonction : id_users
> updateFirstname
> updateLastname
> updateTelephone
> updateCentreFormation()
===========================================================================================*/

class GestionnaireCentre extends Database{
    public $id;
    public $firstname;
    public $lastname;
    public $telephone;
    //Clés étrangères
    public $id_centres_de_formation;
    public $id_users;

    public function addGestionnaireCentre() {
    
        $insertQuery= "INSERT INTO `gestionnaires_centre` (`firstname`, `lastname`, `telephone`, `id_centres_de_formation`, `id_users`) VALUES (:firstname, :lastname, :telephone, :id_centres_de_formation, :id_users)";
        
        $stmt = $this->db->prepare($insertQuery);

        $stmt->bindParam(":firstname", $this->firstname, PDO::PARAM_STR);
        $stmt->bindParam(":lastname", $this->lastname, PDO::PARAM_STR);
        $stmt->bindParam(":telephone", $this->telephone, PDO::PARAM_STR);
        $stmt->bindParam(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
        $stmt->bindParam(":id_users", $this->id_users, PDO::PARAM_INT);

        $stmt->execute();
    }

    function searchCentreForIdUsers() {
        require_once 'db.php';

        $query = "SELECT centres_de_formation.id
        FROM centres_de_formation
        JOIN gestionnaires_centre ON gestionnaires_centre.id_centres_de_formation = centres_de_formation.id
        WHERE gestionnaires_centre.id_users = :id_users;
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_NUM);
        
        return $res[0];
    }


    public function getProfilGestionnaireCentre () {
        require_once 'db.php';

        $query = "SELECT *
        FROM gestionnaires_centre AS t1
        JOIN users AS t2 ON t1.id_users = t2.id   
        WHERE t1.id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT); 
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $res; 
    }

    function getGestionnaireIdByUserId() {
        require_once 'db.php';

        $query = "SELECT id FROM gestionnaires_centre WHERE id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_NUM);

        return $res;
    }
    
    function getGestionnaireDatasByUserId() {
        $query= "SELECT * FROM gestionnaires_centre WHERE id_users = :id_users";
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    function getAbsencesByIdGestionnaire() {
        require_once 'db.php';
        
        $query = "SELECT DISTINCT formations.nom, e.lastname, e.firstname, a.*
                  FROM gestionnaires_centre gc
                  JOIN etudiants e ON gc.id_centres_de_formation = e.id_centres_de_formation
                  JOIN absences a ON e.id = a.id_etudiants
                  JOIN session s ON e.id_session = s.id
                  JOIN formations ON s.id_formations = formations.id
                  WHERE gc.id_centres_de_formation = :id_centres_de_formation
                  AND NOW() BETWEEN s.dateDebut AND s.dateFin";
    
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
        return $res;
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
        WHERE etudiants.id_centres_de_formation = :id_centres_de_formation
        ORDER BY absences.dateDebut DESC
        ";

        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(':id_centres_de_formation', $this->id_centres_de_formation, PDO::PARAM_INT);
            $queryexec->execute();
            $result = $queryexec->fetchAll(PDO::FETCH_ASSOC);

            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function getGestionnaireDatasById() {
        require_once 'db.php';

        $query = "SELECT * FROM gestionnaires_centre WHERE id = :id";
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    

    public function boolId() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM gestionnaires_centre WHERE id_users = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id_users, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    public function updateFirstname() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE gestionnaires_centre SET firstname = :firstname WHERE id_users = :id_users";
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
            $query = "UPDATE gestionnaires_centre SET lastname = :lastname WHERE id_users = :id_users";
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
            $query = "UPDATE gestionnaires_centre SET telephone = :telephone WHERE id_users = :id_users";
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
            $query = "UPDATE gestionnaires_centre SET id_centres_de_formation = :id_centres_de_formation WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':id_centres_de_formation', $this->id_centres_de_formation, PDO::PARAM_INT);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function searchForId() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM gestionnaires_centre WHERE id_users = :id_users";
        
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

    function getProfil() {
        require_once 'db.php';

        $query = "SELECT gestionnaires_centre.*, users.email, centres_de_formation.nomCentre,centres_de_formation.*, role.role
        FROM gestionnaires_centre
        JOIN users ON gestionnaires_centre.id_users = users.id   
        JOIN centres_de_formation ON centres_de_formation.id = gestionnaires_centre.id_centres_de_formation   
        JOIN role ON role.id = users.id_role
        WHERE gestionnaires_centre.id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $res; 
    }

    public function getGestionnaireForAdmin(){
        require_once 'db.php';
        $query = "SELECT users.email, gestionnaires_centre.*, centres_de_formation.nomCentre
            FROM gestionnaires_centre
            JOIN centres_de_formation ON centres_de_formation.id = gestionnaires_centre.id_centres_de_formation
            JOIN users ON gestionnaires_centre.id_users = users.id";

        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->execute();
            
            $result =  $queryexec->fetchAll(PDO::FETCH_ASSOC);

            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function searchCentreForId($idusers) {
        require_once 'db.php';

        $query = "SELECT id_centres_de_formation
        FROM gestionnaires_centre
        WHERE id_users = :iduser;
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":iduser", $idusers, PDO::PARAM_INT);
        $queryexec->execute();
        $result = $queryexec->fetch(PDO::FETCH_ASSOC);

        return $result['id_centres_de_formation'];
    }

    function getAllSessions_gCentre() {
        $query = "SELECT session.*, formations.nom AS nom_formation
        FROM session
        INNER JOIN formations ON session.id_formations = formations.id
        WHERE session.id_centres_de_formation = :id_centres_de_formation
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

    function getAllEtudiants_gCentre() {
        $query = "SELECT etudiants.*
        FROM etudiants
        WHERE etudiants.id_centres_de_formation = :id_centres_de_formation
        ORDER BY etudiants.lastname
        ";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_centres_de_formation', $this->id_centres_de_formation, PDO::PARAM_INT);
            $stmt->execute(); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    
    public function getGestionnairesByCentre(){
        require_once 'db.php';

        $targetCentre = $this->id_centres_de_formation;
        $query = "SELECT gestionnaires_centre.*, 
            centres_de_formation.nomCentre AS nom_centre,
            users.email,
            role.role
            FROM gestionnaires_centre
            JOIN users ON gestionnaires_centre.id_users = users.id
            JOIN centres_de_formation ON centres_de_formation.id = gestionnaires_centre.id_centres_de_formation
            JOIN role ON users.id_role = role.id";

        if($targetCentre !== "all"){
            $query .= " WHERE gestionnaires_centre.id_centres_de_formation = :id_centres_de_formation";
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
            echo "Error: " . $e->getMessage();
        }
    }

    public function getStructure(){
        require_once 'db.php';       
        $query = "SELECT id_centres_de_formations
            FROM gestionnaires_centre
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
                FROM gestionnaires_centre 
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

        $query = "SELECT gestionnaires_centre.*, 
                        centres_de_formation.nomCentre 
                        FROM gestionnaires_centre 
                        JOIN centres_de_formation ON centres_de_formation.id = gestionnaires_centre.id_centres_de_formation 
                        WHERE gestionnaires_centre.id_users = :id_users; ";
        
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

    
    public function boolHasSession($id_session) {
        require_once 'db.php';
    
        $query = "SELECT session.id
                    FROM session 
                    JOIN formations ON formations.id = session.id_formations
                    JOIN gestionnaires_centre ON gestionnaires_centres.id_centres_de_formation = formations.id_centres_de_formation
                    WHERE formations.id_centres_de_formation = :id_centres_de_formation
                    AND gestionnaires_centre.id_users = :id_users
                    AND session.id = :id_session";
        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
            $stmt->bindParam(":id_users", $this->id_users, PDO::PARAM_INT);
            $stmt->bindParam(":id_session", $id_session, PDO::PARAM_INT);
            $stmt->execute();
    
            $count = $stmt->fetchColumn();
    
            return ($count >= 1);

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
       
    }

    public function getIdCentreForIdUsers() {
        require_once 'db.php';
    
        $query = "SELECT id_centres_de_formation 
                FROM gestionnaires_centre 
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
