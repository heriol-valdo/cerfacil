<?php
require_once 'db.php';
/*=========================================================================================
EtudiantModel.php => Résumé des fonctions
===========================================================================================
> addEtudiant() : ajouter un étudiant
> boolId() : retourne True si : id_users
> boolIdRole() : retourne True si : id (etudiant)
> searchAllForSalle() : Renvoie tous les équipements d'une salle (id_salles)
> searchForId() : Renvoie * pour id_users
> searchSessionForId : Renvoie id_session pour : id
> searchForIdEtudiant : Renvoie * pour id (etudiant)
> searchCentreForId : renvoie id_centres_de_formation pour : id
> searchCoursForIdUsers : renvoie les infos de tous les cours pour un étudiant
> searchCoursActifForIdUsers : renvoie les infos de tous les cours actifs pour un étudiant
> getProfilEtudiant() : Renvoie * pour id_users
> getEtudiantDatasById() : Renvoie * pour id (etudiant)
> getEtudiantIdByUserId() : Renvoie id (etudiant) en fonction : id_users
> deleteEtudiant() 
> updateFirstname
> updateLastname
> updateAdressePostale
> updateCodePostal()
> updateVille()
> updateDateNaissance()
> updateEntreprise()
> updateCentreFormation()
> updateFinanceur()
> updateSession()
===========================================================================================*/

class Etudiant extends Database{
    public $id;
    public $firstname;
    public $lastname;
    public $adressePostale;
    public $codePostal;
    public $ville;
    public $date_naissance;
    //Clés étrangères
    public $id_entreprises;
    public $id_centres_de_formation;
    public $id_conseillers_financeurs;
    public $id_session;
    public $id_users;

    function getProfilEtudiant() {
        $query = "SELECT *
        FROM etudiants AS t1
        JOIN users AS t2 ON t1.id_users = t2.id   
        WHERE t1.id_users = :id_users";
        try {    
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
            $queryexec->execute();
            $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
    
            return $res ?: null;  
        } catch (Exception $e) {
            error_log('Error in getProfilEtudiant: ' . $e->getMessage());
            return null;
        }
    }


      public static function genererMotDePasses() {
        // Définition des jeux de caractères
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $specialChars = '@#$!';
        $length = 20;

        // Combinaison de tous les jeux de caractères
        $allChars = $lowercase . $uppercase . $numbers . $specialChars;

        // S'assurer que le mot de passe contiendra au moins un de chaque type requis
        $password = [];
        $password[] = $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password[] = $numbers[random_int(0, strlen($numbers) - 1)];
        $password[] = $specialChars[random_int(0, strlen($specialChars) - 1)];

        // Remplir le reste du mot de passe
        for ($i = 3; $i < $length; $i++) {
            $password[] = $allChars[random_int(0, strlen($allChars) - 1)];
        }

        // Mélanger le tableau de caractères pour plus de sécurité
        shuffle($password);

        // Retourner le mot de passe en tant que chaîne de caractères
        return implode('', $password);

    }

        
     // Return true si l'absence existe
     public function boolIdUsers() {
        require_once 'db.php';
    
        $query = "SELECT * FROM etudiants WHERE id_users = :id_users";
        
        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
            $stmt->execute();

            $count = $stmt->fetchColumn();

            return ($count >= 1);
        } catch (Exception $e) {
            error_log('Error in getProfilEtudiant: ' . $e->getMessage());
            return null;
        }
        
    }

    public function returnIdUsers() {
        require_once 'db.php';
    
        $query = "SELECT id_users FROM etudiants WHERE id = :id";
        
        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch();

            return $result['id_users'];
        } catch (Exception $e) {
            error_log('Error in getProfilEtudiant: ' . $e->getMessage());
            return null;
        }
        
    }

    public function infos() {
        require_once 'db.php';

        $query = "SELECT etudiants.*, users.email, users.id_role, users.id_gender, users.id_apolearn FROM etudiants 
            JOIN users ON users.id = etudiants.id_users
            WHERE etudiants.id_users = :id_users";
        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id_users", $this->id_users, PDO::PARAM_STR);
            $stmt->execute();
    
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if ($result) {
                return $result;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    function getEtudiantDatasById() {
        $query = "SELECT * FROM etudiants WHERE id = :id";
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    function getEtudiantIdByUserId() {

        $query = "SELECT id FROM etudiants WHERE id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $res;
    }

    function getEtudiantsByIdSession() {
        $query = "SELECT id, lastname, firstname FROM etudiants WHERE id_session = :id_session";
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_session", $this->id_session, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getAbsencesStudents() {
        $query = "SELECT t1.lastname, t1.firstname, t2.*
                  FROM etudiants AS t1
                  JOIN absences AS t2 ON t1.id = t2.id_etudiants   
                  WHERE t1.id_session = :id_session";
        
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_session", $this->id_session, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    

    public function addEtudiant() {
    
        $insertQuery= "INSERT INTO `etudiants` (
            `firstname`, `lastname`, `adressePostale`, `codePostal`, `ville`, `date_naissance`,  `id_users`) 
            VALUES (
                :firstname, :lastname, :adressePostale, :codePostal, :ville, :date_naissance,  :id_users
                )";
        
        $stmt = $this->db->prepare($insertQuery);

        $stmt->bindParam(":firstname", $this->firstname, PDO::PARAM_STR);
        $stmt->bindParam(":lastname", $this->lastname, PDO::PARAM_STR);
        $stmt->bindParam(":adressePostale", $this->adressePostale, PDO::PARAM_STR);
        $stmt->bindParam(":codePostal", $this->codePostal, PDO::PARAM_STR);
        $stmt->bindParam(":ville", $this->ville, PDO::PARAM_STR);
        $stmt->bindParam(":date_naissance", $this->date_naissance, PDO::PARAM_STR);

        $stmt->bindParam(":id_users", $this->id_users, PDO::PARAM_INT);

        return $stmt->execute();
    }

    
    // Renvoie toutes les infos d'un étudiant
    public function searchForId() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM etudiants WHERE id_users = :id_users";
        
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

    function searchCentreForId() {
        require_once 'db.php';

        $query = "SELECT id_centres_de_formation
        FROM etudiants
        WHERE id = :id;
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
        $queryexec->execute();
        $result = $queryexec->fetch(PDO::FETCH_ASSOC);

        return $result['id_centres_de_formation'];
    }

    public function searchForIdEtudiant() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM etudiants WHERE id = :id";
        
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

    

    public function searchSessionForId() {
        require_once 'db.php';

        $existingQuery = "SELECT id_session FROM etudiants WHERE id = :id";
        
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

    function searchCoursForIdUsers() {
        require_once 'db.php';

        $query = "SELECT cours.debut, cours.fin, cours.modalites, cours.nom AS cours_nom, salles.nom AS salle_nom, formateurs.firstname AS formateur_prenom, formateurs.lastname AS formateur_nom
        FROM cours
        JOIN session ON cours.id_session = session.id
        JOIN etudiants ON etudiants.id_session = session.id
        JOIN salles ON cours.id_salles = salles.id
        JOIN formateurs ON cours.id_formateurs = formateurs.id
        WHERE etudiants.id_users = :id_users;
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $result = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    function searchCoursActifForIdUsers() {
        require_once 'db.php';

        $query = "SELECT cours.debut, cours.fin, cours.modalites, cours.nom AS cours_nom, salles.nom AS salle_nom, formateurs.firstname AS formateur_prenom, formateurs.lastname AS formateur_nom
        FROM cours
        JOIN session ON cours.id_session = session.id
        JOIN etudiants ON etudiants.id_session = session.id
        JOIN salles ON cours.id_salles = salles.id
        JOIN formateurs ON cours.id_formateurs = formateurs.id
        WHERE etudiants.id_users = :id_users AND debut > CURDATE();
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $result = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function updateFirstname() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE etudiants SET firstname = :firstname WHERE id_users = :id_users";
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
            $query = "UPDATE etudiants SET lastname = :lastname WHERE id_users = :id_users";
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
            $query = "UPDATE etudiants SET adressePostale = :adressePostale WHERE id_users = :id_users";
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
            $query = "UPDATE etudiants SET codePostal = :codePostal WHERE id_users = :id_users";
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
            $query = "UPDATE etudiants SET ville = :ville WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':ville', $this->ville, PDO::PARAM_STR);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateDateNaissance() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE etudiants SET date_naissance = :date_naissance WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':date_naissance', $this->date_naissance, PDO::PARAM_STR);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateEntreprise() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE etudiants SET id_entreprises = :id_entreprises WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':id_entreprises', $this->id_entreprises, PDO::PARAM_INT);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateCentreFormation() {
        require_once 'db.php';

    
        try {
            $query = "UPDATE etudiants SET id_centres_de_formation = :id_centres_de_formation WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':id_centres_de_formation', $this->id_centres_de_formation, PDO::PARAM_INT);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateFinanceur() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE etudiants SET id_conseillers_financeurs = :id_conseillers_financeurs WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':id_conseillers_financeurs', $this->id_conseillers_financeurs, PDO::PARAM_INT);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateSession() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE etudiants SET id_session = :id_session WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':id_session', $this->id_session, PDO::PARAM_INT);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Return true si l'étudiant existe
    public function boolId() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM etudiants WHERE id_users = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id_users, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    // Return true si l'étudiant existe
    public function boolIdRole() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM etudiants WHERE id = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    public function boolIdSession() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM etudiants WHERE id_session = :id_session";

        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id_session", $this->id_session, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    public function getCentresFinanced() {
        require_once 'db.php';

        $query = "SELECT * FROM etudiants WHERE id_conseillers_financeurs = :id_conseillers_financeurs AND id_centres_de_formation = :id_centres_de_formation";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_conseillers_financeurs", $this->id_conseillers_financeurs, PDO::PARAM_INT);
        $stmt->bindValue(":id_centres_de_formation", $this->id_centres_de_formation , PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    }

    function getProfil() {
        require_once 'db.php';

        $query = "SELECT etudiants.*,  users.email, role.role
        FROM etudiants
        JOIN users ON etudiants.id_users = users.id  
        JOIN role ON role.id = users.id_role
        WHERE etudiants.id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $res; 
    }


    public function searchAllEtudiantForAdmin() {
        require_once 'db.php';

        $existingQuery = "SELECT users.email, users.id_apolearn, users.username_apolearn, etudiants.*
        FROM etudiants
        JOIN users ON etudiants.id_users = users.id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


        if ($result) {
            return $result;
        } else {
            return null;
        }
    }
    
    public function searchAllEtudiantForEntreprise($identreprises) {
        require_once 'db.php';

        $existingQuery = "SELECT users.email, users.id_apolearn, users.username_apolearn, etudiants.* FROM etudiants 
        JOIN users ON etudiants.id_users = users.id
        WHERE id_entreprises = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $identreprises, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


        if ($result) {
            return $result;
        } else {
            return null;
        }
    }


    public function searchAllEtudiantForCentreId($idcentre) {
        require_once 'db.php';

        $existingQuery = "SELECT users.email, users.id_apolearn, users.username_apolearn, etudiants.* 
        FROM etudiants 
        JOIN users ON etudiants.id_users = users.id
        WHERE id_centres_de_formation = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id",$idcentre, PDO::PARAM_INT);
        $stmt->execute();


        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    public function searchAllEtudiantForIdSession($idsession) {
        require_once 'db.php';

        $existingQuery = "SELECT users.email, etudiants.*
        FROM etudiants 
        JOIN users ON etudiants.id_users = users.id
        WHERE id_session = :id";

        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id",$idsession, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    public function searchAllEtudiantForFinanceur($id) {
        require_once 'db.php';

        $existingQuery = "SELECT users.email, users.id_apolearn, users.username_apolearn, etudiants.*
        FROM etudiants 
        JOIN users ON etudiants.id_users = users.id
        WHERE id_conseillers_financeurs = :id";

        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id",$id, PDO::PARAM_INT);
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
        session.dateDebut AS session_dateDebut, session.dateFin AS session_dateFin
        FROM absences
        JOIN etudiants ON etudiants.id = absences.id_etudiants
        JOIN centres_de_formation ON centres_de_formation.id = etudiants.id_centres_de_formation
        JOIN session ON session.id = etudiants.id_session
        JOIN formations ON formations.id = session.id_formations 
        WHERE etudiants.id_users = :id_users
        ORDER BY absences.dateDebut DESC
        ";
           
        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
            $queryexec->execute();
            
            $result = $queryexec->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function getAllEtudiants_admin() {
        $query = "SELECT etudiants.*
        FROM etudiants
        JOIN session ON session.id = etudiants.id_session
        ORDER BY etudiants.lastname
        ";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute(); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function searchFinancedFromCentre() {
        require_once 'db.php';

        $existingQuery = "SELECT etudiants.*,
                        formations.nom AS formations_nom, formations.id AS formations_id,
                        session.nomSession AS session_nom, session.dateDebut AS session_dateDebut, session.dateFin AS session_dateFin, session.id_formations AS id_formations
                        FROM etudiants 
                        JOIN conseillers_financeurs ON etudiants.id_conseillers_financeurs = conseillers_financeurs.id
                        LEFT JOIN session ON etudiants.id_session = session.id
                        LEFT JOIN formations ON session.id_formations = formations.id
                        WHERE conseillers_financeurs.id_entreprises = :id_entreprises
                        AND etudiants.id_centres_de_formation = :id_centres_de_formation";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id_entreprises", $this->id_entreprises, PDO::PARAM_INT);
        $stmt->bindValue(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }


    public function getEtudiantsByCentre(){
        require_once 'db.php';

        $targetCentre = $this->id_centres_de_formation;
       
        $query = "SELECT etudiants.*, 
            users.email,
            role.role
            FROM etudiants
            JOIN users ON etudiants.id_users = users.id
            JOIN role ON users.id_role = role.id ";

        if($targetCentre != "all"){
            $query .= "WHERE etudiants.id_centres_de_formation = :id_centres_de_formation";
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


    public function getAbsenceListByCentre() {
        require_once 'db.php';

        $targetCentre = $this->id_centres_de_formation;
        
        $query = "SELECT absences.*, 
        etudiants.firstname, etudiants.lastname, etudiants.id AS etudiants_id,
        centres_de_formation.nomCentre, 
        session.dateDebut AS session_dateDebut, session.dateFin AS session_dateFin, 
        formations.id AS formations_id, formations.nom AS formations_nom
        FROM absences
        JOIN etudiants ON etudiants.id = absences.id_etudiants
        LEFT JOIN centres_de_formation ON centres_de_formation.id = etudiants.id_centres_de_formation
        LEFT JOIN session ON session.id = etudiants.id_session
        LEFT JOIN formations ON formations.id = session.id_formations
        ";

        if($targetCentre == "all"){
            $query .= " ORDER BY absences.dateDebut DESC";
        } else {
            $query .= " WHERE etudiants.id_centres_de_formation = :id_centres_de_formation
            ORDER BY absences.dateDebut DESC";
        }
        try {
            $queryexec = $this->db->prepare($query);
            if($targetCentre != "all"){
                $queryexec->bindParam(':id_centres_de_formation', $this->id_centres_de_formation, PDO::PARAM_INT);
            }
            $queryexec->execute();
            
            $result = $queryexec->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getStructure(){
        require_once 'db.php';       
        $query = "SELECT id_centres_de_formations
            FROM etudiants
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

    public function getEntreprise(){
        require_once 'db.php';       
        $query = "SELECT id_entreprises
            FROM etudiants
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

    public function getFinanceur(){
        require_once 'db.php';       
        $query = "SELECT id_conseillers_financeurs
            FROM etudiants
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

     // Donne toutes les infos pour 
     public function getRoleInfosForId() {
        require_once 'db.php';

        $query = "SELECT etudiants.*, 
                formations.nom AS formations_nom, formations.id AS formations_id,
                session.nomSession, session.dateDebut, session.dateFin,
                centres_de_formation.nomCentre, 
                entreprises.nomEntreprise,
                conseillers_financeurs.lastname AS financeur_lastname, conseillers_financeurs.firstname AS financeur_firstname
                FROM etudiants 
                LEFT JOIN entreprises ON entreprises.id = etudiants.id_entreprises
                LEFT JOIN session ON session.id = etudiants.id_session
                LEFT JOIN formations ON formations.id = session.id_formations
                LEFT JOIN conseillers_financeurs ON conseillers_financeurs.id = etudiants.id_conseillers_financeurs
                LEFT JOIN centres_de_formation ON centres_de_formation.id = etudiants.id_centres_de_formation 
                WHERE etudiants.id_users = :id_users;  ";
        
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

    // Renvoie True si on trouve un étudiant qui fait partie de l'entreprise cherchée
    // et qui fait partie du centre cherché
    public function centre_hasAccessToEntreprise() {
        $query = "SELECT id 
                FROM etudiants 
                WHERE id_entreprises = :id_entreprises
                AND id_centres_de_formation = :id_centres_de_formation";
                
        $queryexec = $this->db->prepare($query);
        $queryexec->bindParam(":id_entreprises", $this->id_entreprises, PDO::PARAM_INT);
        $queryexec->bindParam(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);

        $queryexec->execute();
        
        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    // Renvoie True si on trouve un étudiant qui fait partie de l'entreprise cherchée
    // et qui fait partie du centre cherché
    public function centre_hasAccessToFinanceur() {
        $query = "SELECT etudiants.id 
                FROM etudiants 
                JOIN conseillers_financeurs ON conseillers_financeurs.id = etudiants.id_conseillers_financeurs
                WHERE conseillers_financeurs.id_entreprises = :id_entreprises
                AND etudiants.id_centres_de_formation = :id_centres_de_formation";
                
        $queryexec = $this->db->prepare($query);
        $queryexec->bindParam(":id_entreprises", $this->id_entreprises, PDO::PARAM_INT);
        $queryexec->bindParam(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);

        $queryexec->execute();
        
        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }


    // Renvoie True si on trouve un étudiant qui fait partie de l'entreprise cherchée
    // et qui fait partie du centre cherché
    public function entreprise_hasAccessToFinanceur() {
        $query = "SELECT id 
                FROM etudiants 
                WHERE id_conseillers_financeurs = :id_conseillers_financeurs
                AND id_entreprises = :id_entreprises";
                
        $queryexec = $this->db->prepare($query);
        $queryexec->bindParam(":id_conseillers_financeurs", $this->id_conseillers_financeurs, PDO::PARAM_INT);
        $queryexec->bindParam(":id_entreprises", $this->id_entreprises, PDO::PARAM_INT);

        $queryexec->execute();
        
        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    public function isFinancedByEntreprise(){
        require_once 'db.php';
    
        $query = "SELECT etudiants.*
            FROM etudiants
            JOIN conseillers_financeurs ON conseillers_financeurs.id = etudiants.id_conseillers_financeurs
            JOIN entreprises ON conseillers_financeurs.id_entreprises = entreprises.id
            WHERE etudiants.id_users = :id_users
            AND entreprises.id = :id_entreprises"; 
    
        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindParam(':id_users', $this->id_users, PDO::PARAM_INT);
            $queryexec->bindParam(':id_entreprises', $this->id_entreprises, PDO::PARAM_INT);  
            $queryexec->execute();
            
            $result = $queryexec->fetch(PDO::FETCH_ASSOC); 
            
            return $result !== false;
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;  
        }
    }
    
}
