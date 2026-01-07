<?php
require_once 'db.php';
/*=========================================================================================
TicketModel.php => Résumé des fonctions
===========================================================================================
> login
> addUser : 
> boolId() : retourne True si : id
> boolEmail : retourne True si : email
> boolRole : retourne True si : id (role)
> searchIdForEmail : retourne l'id (users) pour un email
> searchForId : retourne * pour : id
> deleteUser
> updatePassword 
> updateEmail
> searchIdRoleForIdUsers
> searchIdCentreForIdUsers()
===========================================================================================*/

class User extends Database {
    public $id;
    public $email;
    public $password;
    public $reset_token;
    public $id_apolearn;
    public $username_apolearn;
    //Clé étrangère
    public $id_role;

    public function login() {
        require_once 'db.php';

        $query = 'SELECT `email`,`id`,`password`,`id_role` FROM `users` WHERE `email` = ?';
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(1, $this->email, PDO::PARAM_STR); // Utilisation du paramètre $email passé à la méthode
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
        
        return $res; 
    }

//=============================================================
// --------------------- addUser ------------------------
// > ATTENTION : A désactiver 
// > Créé un premier utilisateur 
//=============================================================
  
    public function addUser() {
        require_once 'db.php';
    
        $addStudentQuery = "INSERT INTO `users` (`email`, `password`, `id_role`) VALUES (:email, :password, :id_role)";
        
        $stmt = $this->db->prepare($addStudentQuery);

        $hashed =  password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindValue(":email", $this->email, PDO::PARAM_STR);
        $stmt->bindValue(":password", $hashed, PDO::PARAM_STR);
        $stmt->bindValue(":id_role", $this->id_role, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function updatePassword() {

        require_once 'db.php';

    
        try {
            $query = "UPDATE users SET password = :newPassword WHERE id = :id";
            $queryexec = $this->db->prepare($query);

            $hashed =  password_hash($this->password, PASSWORD_DEFAULT);
           
            $queryexec->bindValue(':newPassword', $hashed, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function resetPassword() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE users SET password = :newPassword WHERE reset_token = :reset_token";
            $queryexec = $this->db->prepare($query);

            $hashed =  password_hash($this->password, PASSWORD_DEFAULT);
           
            $queryexec->bindValue(':newPassword', $hashed, PDO::PARAM_STR);
            $queryexec->bindValue(':reset_token', $this->reset_token, PDO::PARAM_STR);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getUserForReset() {
        require_once 'db.php';

 
    
        try {
            $query = "SELECT * FROM users WHERE reset_token = :reset_token";
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(':reset_token', $this->reset_token, PDO::PARAM_STR);

            $queryexec->execute();
            
            $result = $queryexec->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result;
            } else {
                return null;
            }
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    
    public function updateEmail() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE users SET email = :email WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':email', $this->email, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function deleteUser() {    
        $deleteQuery = "DELETE FROM users WHERE id = :id";;
        
        try{
            $stmt = $this->db->prepare($deleteQuery);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
            return $stmt->execute();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
    
//=============================================================
// --------------------- boolX ------------------------
// > TRUE si X existe 1 fois ou plus dans la BDD
// > FALSE si X existe 0 fois dans la BDD
//=============================================================

    public function boolEmail() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM users WHERE email = :email";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":email", $this->email, PDO::PARAM_STR);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    public function boolId() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM users WHERE id = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_STR);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    public function boolResetToken() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM users WHERE reset_token = :reset_token";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":reset_token", $this->reset_token, PDO::PARAM_STR);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    public function boolRole() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM role WHERE id = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id_role, PDO::PARAM_STR);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }


    //=============================================================
// --------------------- searchXforY ------------------------
// > return X en se basant sur Y
//=============================================================

    public function searchIdForEmail() {
        require_once 'db.php';

        $existingQuery = "SELECT id FROM users WHERE email = :email";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":email", $this->email, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['id'];
        } else {
            return null;
        }
    }

    public function searchIdRoleForEmail() {
        require_once 'db.php';

        $existingQuery = "SELECT id_role FROM users WHERE email = :email";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":email", $this->email, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['id_role'];
        } else {
            return null;
        }
    }

    public function searchForId() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM users WHERE id = :id";
        
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

    public function searchIdRoleForIdUsers(){
        require_once 'db.php';

        $query = "SELECT id_role
                FROM users
                WHERE id = :id";
        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['id_role'] : null;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function searchIdCentreForIdUsers() {
        require_once 'db.php';

        switch($this->id_role){
            case 3:
                $query = "SELECT id_centres_de_formation
                    FROM gestionnaires_centre
                    WHERE id_users = :id_users";
                break;
            case 4:
                $query = "SELECT id_centres_de_formation
                    FROM formateurs
                    WHERE id_users = :id_users";
                break;
            case 5:
                $query = "SELECT id_centres_de_formation
                    FROM etudiants
                    WHERE id_users = :id_users";
                break;
        }
        
        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id_users", $this->id, PDO::PARAM_INT);
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($result) {
                return $result['id_centres_de_formation'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
       
    }

    public function searchIdEntrepriseForIdUsers() {
        require_once 'db.php';
        switch($this->id_role){
            case 2:
                $query = "SELECT id_entreprises
                    FROM gestionnaires_entreprise
                    WHERE id_users = :id_users";
                break;
            case 6:
                $query = "SELECT id_entreprises
                    FROM conseillers_financeurs
                    WHERE id_users = :id_users";
                break;
        }

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id_users", $this->id, PDO::PARAM_INT);
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($result) {
                return $result;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
       
    }

    public function returnStructure() {
        require_once 'db.php';
        switch($this->id_role){
            case 3:
                $query = "SELECT id_centres_de_formation
                    FROM gestionnaires_centre
                    WHERE id_users = :id_users";
                break;
            case 4:
                $query = "SELECT id_centres_de_formation
                    FROM formateurs
                    WHERE id_users = :id_users";
                break;
            case 5:
                $query = "SELECT etudiants.id_centres_de_formation, etudiants.id_entreprises, 
                    conseillers_financeurs.id_entreprises AS financeur_id_entreprises 
                    FROM etudiants 
                    LEFT JOIN conseillers_financeurs 
                    ON conseillers_financeurs.id = etudiants.id_conseillers_financeurs 
                    WHERE etudiants.id_users = :id_users";
                break;
        }

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id_users", $this->id, PDO::PARAM_INT);
            $stmt->execute();
    
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if ($result) {
                return $result;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
       
    }


    public function searchSessionsParticipated() {
        require_once 'db.php';
        switch($this->id_role){
            case 4:
                $query = "SELECT formateurs_participant_session.id
                    FROM formateurs_participant_session
                    JOIN formateurs ON formateurs_participant_session.id_formateurs = formateurs.id
                    WHERE formateurs.id_users = :id_users";
                break;
            case 5:
                $query = "SELECT id_session
                    FROM etudiants
                    WHERE id_users = :id_users";
                break;
        }

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id_users", $this->id, PDO::PARAM_INT);
            $stmt->execute();
    
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if ($result) {
                return $result;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updatePasswordForResetToken() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE users SET password = :newPassword WHERE reset_token = :reset_token";
            $queryexec = $this->db->prepare($query);

            $hashed =  password_hash($this->password, PASSWORD_DEFAULT);
           
            $queryexec->bindValue(':newPassword', $hashed, PDO::PARAM_STR);
            $queryexec->bindValue(':reset_token', $this->reset_token, PDO::PARAM_STR);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateResetToken(){
        $query = "UPDATE users SET reset_token = :reset_token WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':reset_token', $this->reset_token, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function clearResetToken(){
        $query = "UPDATE users SET reset_token = NULL WHERE reset_token = :reset_token";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':reset_token', $this->reset_token, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function getResetToken(){
        $query = "SELECT reset_token FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    public function getNames(){
        $query = "SELECT *
        FROM administrateurs 
        WHERE administrateurs.id_users = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    public function adminGetUsersByRole() {
        require_once 'db.php';
    
        $query = "";
    
        switch($this->id_role){
            case 1:
                $roleTable = 'administrateurs';
                break;
            case 2:
                $roleTable = 'gestionnaires_entreprise';
                break;
            case 3:
                $roleTable = 'gestionnaires_centre';
                break;
            case 4:
                $roleTable = 'formateurs';
                break;
            case 5:
                $roleTable = 'etudiants';
                break;
            case 6:
                $roleTable = 'conseillers_financeurs';
                break;
            default:
                $roleTable = 'administrateurs, gestionnaires_entreprise, gestionnaires_centre, formateurs, etudiants, conseillers_financeurs';
                break;
        }
    
        $query = "SELECT $roleTable.firstname, $roleTable.lastname, role.role, users.email
                  FROM users
                  JOIN role ON role.id = users.id_role
                  JOIN $roleTable ON users.id = $roleTable.id_users";
    
        try {
            if ($this->id_role != 'all') {
                $queryexec = $this->db->prepare($query);
                $queryexec->bindValue(':id_role', $this->id_role, PDO::PARAM_STR);
            } else {
                $query = "SELECT COALESCE(administrateurs.firstname, gestionnaires_entreprise.firstname, gestionnaires_centre.firstname, formateurs.firstname, etudiants.firstname, conseillers_financeurs.firstname) AS firstname,
                 COALESCE(administrateurs.lastname, gestionnaires_entreprise.lastname, gestionnaires_centre.lastname, formateurs.lastname, etudiants.lastname, conseillers_financeurs.lastname) AS lastname,
                 role.role, users.email
                FROM users
                JOIN role ON role.id = users.id_role
                LEFT JOIN administrateurs ON users.id = administrateurs.id_users
                LEFT JOIN gestionnaires_entreprise ON users.id = gestionnaires_entreprise.id_users
                LEFT JOIN gestionnaires_centre ON users.id = gestionnaires_centre.id_users
                LEFT JOIN formateurs ON users.id = formateurs.id_users
                LEFT JOIN etudiants ON users.id = etudiants.id_users
                LEFT JOIN conseillers_financeurs ON users.id = conseillers_financeurs.id_users";
                $queryexec = $this->db->prepare($query);
            }
            $queryexec->execute();
            
            $result = $queryexec->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    public function gCentreGetUsersByRole($idCentre) {
        require_once 'db.php';
        
        $query = "";
           
        switch ($this->id_role) {
            case 3:
                $roleTable = 'gestionnaires_centre';
                break;
            case 4:
                $roleTable = 'formateurs';
                break;
            case 5:
                $roleTable = 'etudiants';
                break;
            default:
                $roleTable = 'gestionnaires_centre, formateurs, etudiants';
                break;
        }
        
        $query = "SELECT ";
        if ($roleTable === 'gestionnaires_centre' || $roleTable === 'etudiants' || $roleTable ==='formateurs') {
            $query .= "$roleTable.*,";
        } else {
            $query .= "COALESCE(gestionnaires_centre.firstname, formateurs.firstname, etudiants.firstname) AS firstname,
                      COALESCE(gestionnaires_centre.lastname, formateurs.lastname, etudiants.lastname) AS lastname, ";
        }
        
        $query .= "role.role, users.email
                   FROM users
                   JOIN role ON role.id = users.id_role";
        
        if ($roleTable === 'gestionnaires_centre' || $roleTable === 'formateurs' || $roleTable === 'etudiants') {
            $query .= " JOIN $roleTable ON users.id = $roleTable.id_users
                        WHERE $roleTable.id_centres_de_formation = :idCentre
                        AND users.id_role = :id_role";
        } else {
            $query .= " LEFT JOIN gestionnaires_centre ON users.id = gestionnaires_centre.id_users
                        LEFT JOIN formateurs ON users.id = formateurs.id_users
                        LEFT JOIN etudiants ON users.id = etudiants.id_users
                        WHERE gestionnaires_centre.id_centres_de_formation = :idCentre
                        OR formateurs.id_centres_de_formation = :idCentre
                        OR etudiants.id_centres_de_formation = :idCentre";
        }


        $queryexec = $this->db->prepare($query);

        $queryexec->bindValue(':idCentre', $idCentre, PDO::PARAM_INT);
        if ($this->id_role != 'all') {
            $queryexec->bindValue(':id_role', $this->id_role, PDO::PARAM_STR);
        } 

        $queryexec->execute();
        
        $result = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    }


    public function formateurGetEleves() {
        require_once 'db.php';

        $query = "SELECT formations.nom, etudiants.firstname, etudiants.lastname, role.role, users.email
                  FROM users
                  JOIN role ON role.id = users.id_role
                  JOIN etudiants ON users.id = etudiants.id_users
                  JOIN session ON session.id = etudiants.id_session
                  JOIN formations ON formations.id = session.id_formations
                  JOIN formateurs_participant_session ON session.id = formateurs_participant_session.id
                  JOIN formateurs ON formateurs.id = formateurs_participant_session.id_formateurs
                  WHERE formateurs.id = :id_formateur";
    
        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(':id_formateur', $this->id, PDO::PARAM_INT);
            $queryexec->execute();
            
            $result = $queryexec->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getIdEntrepriseForGestionnaireEntreprise(){
        require_once 'db.php';
        $query = "SELECT entreprises.id
            FROM users
            JOIN gestionnaires_entreprise ON users.id = gestionnaires_entreprise.id_users
            JOIN entreprises ON entreprises.id = gestionnaires_entreprise.id_entreprises
            WHERE users.id = :id_user";

        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(':id_user', $this->id, PDO::PARAM_INT);
            $queryexec->execute();
            
            $result = $queryexec->fetchColumn();

            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function gestionnaireEntrepriseGetEleves() {
        require_once 'db.php';

        $id_entreprise = $this->getIdEntrepriseForGestionnaireEntreprise();

        $query = "SELECT formations.nom, etudiants.firstname, etudiants.lastname, role.role, users.email
                    FROM users
                    JOIN role ON role.id = users.id_role
                    JOIN etudiants ON users.id = etudiants.id_users
                    JOIN session ON session.id = etudiants.id_session
                    JOIN formations ON formations.id = session.id_formations
                    JOIN entreprises ON entreprises.id = etudiants.id_entreprises
                    WHERE entreprises.id = :id_entreprise";
    
        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(':id_entreprise', $id_entreprise, PDO::PARAM_INT);
            $queryexec->execute();
            
            $result = $queryexec->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getIdEntrepriseForFinanceur(){
        require_once 'db.php';
        $query = "SELECT entreprises.id
            FROM users
            JOIN conseillers_financeurs ON users.id = conseillers_financeurs.id_users
            JOIN entreprises ON entreprises.id = conseillers_financeurs.id_entreprises
            WHERE users.id = :id_user";

        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(':id_user', $this->id, PDO::PARAM_INT);
            $queryexec->execute();
            
            $result = $queryexec->fetchColumn();

            return $result;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Récupération tous les étudiants financés par la même boite
    public function getAllFinancedForSameFinanceur(){
        require_once 'db.php';
        $id_entreprise = $this->getIdEntrepriseForFinanceur();

        $query = "SELECT formations.nom, etudiants.firstname, etudiants.lastname, role.role, users.email
        FROM users
        JOIN role ON role.id = users.id_role
        JOIN etudiants ON users.id = etudiants.id_users
        JOIN session ON session.id = etudiants.id_session
        JOIN formations ON formations.id = session.id_formations
        JOIN conseillers_financeurs ON conseillers_financeurs.id = etudiants.id_conseillers_financeurs
        WHERE conseillers_financeurs.id_entreprises = :id_entreprise";

        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(':id_entreprise', $id_entreprise, PDO::PARAM_INT);
            $queryexec->execute();

            $result = $queryexec->fetchAll(PDO::FETCH_ASSOC);

            return $result;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getIdCentreForIdUsers(){
        require_once 'db.php';

        switch($this->id_role){
            case 3: 
                $roleTable = "gestionnaires_centre";
                break;
            case 4:
                $roleTable = "formateurs";
                break;
            case 5: 
                $roleTable = "etudiants";
                break;
            default: 
                return null;
                break;
        }

        $query = "SELECT id_centres_de_formation
        FROM $roleTable
        WHERE id_users = :id_users";

        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(':id_users', $this->id, PDO::PARAM_INT);
            $queryexec->execute();

            $result = $queryexec->fetchAll(PDO::FETCH_ASSOC);

            return $result;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getInfoForId() {
        require_once 'db.php';

        $existingQuery = "SELECT users.email, users.id_role, users.id, users.id_gender, users.id_apolearn, users.username_apolearn,
                        role.role
                        FROM users 
                        JOIN role ON users.id_role = role.id
                        WHERE users.id = :id";
        
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

    public function getEtudiantDetailsForEmail($email) {
        require_once 'db.php';

        $query = "SELECT etudiants.*, 
                users.email, users.id_role
                FROM etudiants 
                JOIN users ON users.id = etudiants.id_users
                WHERE users.email = :email";
        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } 
    }

    public function getAuthorEvent(){
        require_once 'db.php';

        $query = "SELECT id_role
        FROM users
        WHERE id = :id";

        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
            $queryexec->execute();

            switch($queryexec->fetch(PDO::FETCH_ASSOC)['id_role']){
                case 1: 
                    $roleTable = "administrateurs";
                    break;
                case 3: 
                    $roleTable = "gestionnaires_centre";
                    break;
                case 4:
                    $roleTable = "formateurs";
                    break;
                default: 
                    return null;
                    break;
            }

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        

        $query = "SELECT $roleTable.firstname, $roleTable.lastname,
        role.role
        FROM $roleTable
        JOIN users ON users.id = $roleTable.id_users
        JOIN role ON role.id = users.id_role
        WHERE $roleTable.id_users = :id_users";

        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(':id_users', $this->id, PDO::PARAM_INT);
            $queryexec->execute();

            $result = $queryexec->fetch(PDO::FETCH_ASSOC);

            return $result;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getMailingInfo() {
        $query = "SELECT id_role FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
    
        $role = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$role) {
            return null; 
        }
    
        switch($role['id_role']) {
            case 1: 
                $table = "administrateurs";
                break;
            case 2: 
                $table = "gestionnaires_entreprise";
                break;
    
            case 3: 
                $table = "gestionnaires_centre";
                break;

            case 4: 
                $table = "formateurs";
                break;

            case 5: 
                $table = "etudiants";
                break;

            case 6: 
                $table = "conseillers_financeurs";
                break;
    
            default:
                return null;
        }
    
        $query = "SELECT users.email, $table.firstname, role.role
                  FROM users
                  JOIN $table ON $table.id_users = users.id
                  JOIN role ON role.id = users.id_role
                  WHERE users.id = :id";
    
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if ($result) {
            return $result;
        } else {
            return null; 
        }
    }

    public function getApolearnInfos(){
        $table = "";
        $target_role = $this->id_role;
        switch($target_role) {   
            case 3: 
                $table = "gestionnaires_centre";
                break;

            case 4: 
                $table = "formateurs";
                break;

            case 5: 
                $table = "etudiants";
                break;

            default:
                return null;
        }
    
        $query = "SELECT users.email, users.id_apolearn, users.id_gender, role.role,
                    $table.*
                  FROM users
                  JOIN $table ON $table.id_users = users.id
                  LEFT JOIN gender ON gender.id = users.id_gender
                  JOIN role ON role.id = users.id_role
                  WHERE users.id = :id";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
        
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($result)) {
                return $result;
            } else {
                return null; 
            }
        } catch (PDOException $e) {
            return null;
        }
    }

    
    public function updateApolearnInfos() {
        $query = "UPDATE users 
            SET id_apolearn = :id_apolearn,
            username_apolearn = :username_apolearn
            WHERE id = :id";
        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(':id_apolearn', $this->id_apolearn, PDO::PARAM_INT);
            $queryexec->bindValue(':username_apolearn', $this->username_apolearn, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
