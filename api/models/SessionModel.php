<?php
require_once 'db.php';
/*=========================================================================================
SessionModel.php => Résumé des fonctions
===========================================================================================
> addSession() : ajouter une session
> boolId() : retourne True si : id
> boolSessionActive() : Retourne true si : id + si date < dateFin
> searchForId : renvoie * pour : id
> searchCentreForId : Renvoie l'id du centre pour l'id (session)
> searchDateDebutForId : Renvoie la date de début pour l'id (session)
> searchDateFinForId : Renvoie la date de fin pour l'id (session)
> getSessionDatas() : retourne toutes les infos d'une session, le nom de la formation 
et le lien Francecompetence pour une session
> getSessionsEnCoursFromCentre() : retourne toutes les infos des sessions, le nom de la formation
entre dateDebut et une dateFin pour un centre
> getSessionsEnCoursByFormation() : retourne toutes les infos des sessions, le nom de la formation
entre dateDebut et une dateFin pour une formation
> getSessionsTermineesFromCentre() : retourne toutes les infos des sessions, le nom de la formation
entre après une dateFin pour un centre de formation
> getSessionsTermineesByFormation() : retourne toutes les infos des sessions, le nom de la formation
entre après une dateFin pour une formation
> getSessionsAvenirFromCentre() : donne les sessions et le nom de la formation si la date est < dateDebut
pour un centre
> getSessionsAvenirByFormation() : donne les sessions et le nom de la formation si la date est < dateDebut
pour une formation
> deleteSession() :
===========================================================================================*/

class Session extends Database{
    public $id;
    public $dateDebut;
    public $dateFin;
    public $nomSession;
    public $nbPlace;
    //Clés étrangères
    public $id_formations;
    public $id_centres_de_formation;

    // Renvoie true si il existe
    public function boolId() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM session WHERE id = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_STR);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    public function infos() {
        require_once 'db.php';

        $query = "SELECT * FROM session WHERE id = :id";
        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_STR);
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

    public function boolSessionActive() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM session WHERE id = :id AND dateFin > CURDATE()";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_STR);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    function searchCentreForId() {
        require_once 'db.php';

        $query = "SELECT centres_de_formation.id
        FROM centres_de_formation
        JOIN formations ON formations.id_centres_de_formation = centres_de_formation.id
        JOIN session ON session.id_formations = formations.id
        WHERE session.id = :id;
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);

        return $res['id'];
    }

    function searchForId() {
        require_once 'db.php';

        $query = "SELECT *
        FROM session
        WHERE id = :id
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);

        return $res;
    }

    function searchDateDebutForId() {
        require_once 'db.php';

        $query = "SELECT dateDebut
        FROM session
        WHERE id = :id
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);

        return $res['dateDebut'];
    }

    function searchDateFinForId() {
        require_once 'db.php';

        $query = "SELECT dateFin
        FROM session
        WHERE id = :id;
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);

        return $res['dateFin'];
    }

    function getSessionDatas() {
        require_once 'db.php';

        $query = "SELECT session.*, formations.nom, formations.lienFranceCompetence
        FROM session
        JOIN formations ON session.id_formations = formations.id
        WHERE session.id = :id;
        ";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $res;
    }

    function getSessionsEnCoursFromCentre() {
        $query = "SELECT session.*, formations.nom AS nom_formation
        FROM session
        INNER JOIN formations ON session.id_formations = formations.id
        WHERE session.id_centres_de_formation = :id_centres_de_formation
                  AND NOW() BETWEEN dateDebut AND dateFin";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
            $stmt->execute(); // Vous devez exécuter la requête préparée
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    function getSessionsEnCoursByCentre() {
     
        $query = "SELECT s.id, s.nomSession
        FROM session s
        WHERE s.id_centres_de_formation = :id_centres_de_formation
          AND CURDATE() BETWEEN s.dateDebut AND s.dateFin";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    
    }


    function getSessionsByFormation() {
        $query = "SELECT session.*, formations.nom AS nom_formation
        FROM session
        INNER JOIN formations ON session.id_formations = formations.id
        WHERE sessions.id_formations = :id_formations";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_formations", $this->$id_formations, PDO::PARAM_INT);
            $stmt->execute(); // Vous devez exécuter la requête préparée
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    function getSessions($id) {
        $query = "SELECT session.*, formations.nom AS nom_formation
        FROM session
        INNER JOIN formations ON session.id_formations = formations.id
        WHERE session.id_centres_de_formation = :id_centres_de_formation";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_centres_de_formation", $id, PDO::PARAM_INT);
            $stmt->execute(); // Vous devez exécuter la requête préparée
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }
    function getAllSessions_admin() {
        $query = "SELECT session.*, formations.nom AS nom_formation
        FROM session
        INNER JOIN formations ON session.id_formations = formations.id
        ";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute(); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }


    function getSessionsEnCoursByFormation() {
        $query = "SELECT session.*, formations.nom AS nom_formation
        FROM session
        INNER JOIN formations ON session.id_formations = formations.id
        WHERE session.id_centres_de_formation = :id_centres_de_formation
        AND session.id_formations = :id_formations
        AND NOW() BETWEEN dateDebut AND dateFin";
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
            $stmt->bindParam(":id_formations", $this->id_formations, PDO::PARAM_INT);
            $stmt->execute(); // Vous devez exécuter la requête préparée
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }
    

    function getSessionsTermineesFromCentre() {
        $query = "SELECT session.*, formations.nom AS nom_formation
        FROM session
        INNER JOIN formations ON session.id_formations = formations.id
        WHERE session.id_centres_de_formation = :id_centres_de_formation
        AND NOW() > dateFin;
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
            $stmt->execute(); // You need to execute the prepared statement
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    function getSessionsTermineesByFormation() {
        echo $this->id_centres_de_formation;
        $query = "SELECT session.*, formations.nom AS nom_formation
        FROM session
        INNER JOIN formations ON session.id_formations = formations.id
        WHERE session.id_centres_de_formation = :id_centres_de_formation
        AND session.id_formations = :id_formations
        AND NOW() > dateFin;
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
            $stmt->bindParam(":id_formations", $this->id_formations, PDO::PARAM_INT);
            $stmt->execute(); // You need to execute the prepared statement
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    function getSessionsAvenirFromCentre() {
        $query = "SELECT session.*, formations.nom AS nom_formation
        FROM session
        INNER JOIN formations ON session.id_formations = formations.id
        WHERE session.id_centres_de_formation = :id_centres_de_formation
        AND NOW() < dateDebut;
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
            $stmt->execute(); // You need to execute the prepared statement
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    function getSessionsAvenirByFormation() {
        $query = "SELECT session.*, formations.nom AS nom_formation
        FROM session
        INNER JOIN formations ON session.id_formations = formations.id
        WHERE session.id_centres_de_formation = :id_centres_de_formation
        AND session.id_formations = :id_formations
        AND NOW() < dateDebut;
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_centres_de_formation", $this->id_centres_de_formation, PDO::PARAM_INT);
            $stmt->bindParam(":id_formations", $this->id_formations, PDO::PARAM_INT);
            $stmt->execute(); // You need to execute the prepared statement
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function getSessionDates($id_session) {
        $query = "SELECT dateDebut, dateFin FROM session WHERE id = :id_session";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_session', $id_session, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function addSession() {

        $insertQuery = "INSERT INTO `session` (`nomSession`, `dateDebut`, `dateFin`, `nbPlace`, `id_formations`, `id_centres_de_formation`) VALUES (:nomSession, :dateDebut, :dateFin, :nbPlace, :id_formations, :idCentre)";
        
        try {
            $stmt = $this->db->prepare($insertQuery);
            
            $stmt->bindParam(":nomSession", $this->nomSession, PDO::PARAM_STR);
            $stmt->bindParam(":dateDebut", $this->dateDebut, PDO::PARAM_STR);
            $stmt->bindParam(":dateFin", $this->dateFin, PDO::PARAM_STR); 
            $stmt->bindParam(":nbPlace", $this->nbPlace, PDO::PARAM_INT);
            $stmt->bindParam(":id_formations", $this->id_formations, PDO::PARAM_INT);
            $stmt->bindParam(":idCentre", $this->id_centres_de_formation, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            } else {
                return false;
            }

        } catch (PDOException $e) {
            return false;
        }
    }

    function deleteSession() {
        $deleteQuery = "DELETE FROM session WHERE id = :id";

        try {
            $stmt = $this->db->prepare($deleteQuery);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    function updateDateDebut(){
        require_once 'db.php';
    
        try {
            $query = "UPDATE session SET dateDebut = :dateDebut WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':dateDebut', $this->dateDebut, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function updateDateFin(){
        require_once 'db.php';
    
        try {
            $query = "UPDATE session SET dateFin = :dateFin WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':dateFin', $this->dateFin, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function updateNbPlace(){
        require_once 'db.php';
    
        try {
            $query = "UPDATE session SET nbPlace = :nbPlace WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':nbPlace', $this->nbPlace, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getCentreForId(){
        require_once 'db.php';

        $query = "SELECT formations.id_centres_de_formation
                FROM session
                JOIN formations ON formations.id = session.id_formations
                WHERE session.id = :id";

        try {
            $queryexec = $this->db->prepare($query);
        
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
            $queryexec->execute();

            return $queryexec->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    public function getSessionDetails(){
        require_once 'db.php';

        $query = "SELECT session.*,
                formations.nom AS formations_nom, formations.lienFranceCompetence AS formations_lienFranceCompetence
                FROM session
                JOIN formations ON formations.id = session.id_formations
                WHERE session.id = :id";

        try {
            $queryexec = $this->db->prepare($query);
        
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
            $queryexec->execute();

            return $queryexec->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getSessionEtudiants(){
        require_once 'db.php';

        $query = "SELECT *
                FROM etudiants
                WHERE id_session = :id_session";

        try {
            $queryexec = $this->db->prepare($query);
        
            $queryexec->bindValue(':id_session', $this->id, PDO::PARAM_INT);
            $queryexec->execute();

            return $queryexec->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getSessionFormateurs(){
        require_once 'db.php';

        $query = "SELECT formateurs.*
                FROM formateurs_participant_session
                JOIN formateurs ON formateurs.id = formateurs_participant_session.id_formateurs
                WHERE formateurs_participant_session.id = :id_session";

        try {
            $queryexec = $this->db->prepare($query);
        
            $queryexec->bindValue(':id_session', $this->id, PDO::PARAM_INT);
            $queryexec->execute();
            
            return $queryexec->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getSessionEnCoursEtudiants(){
        require_once 'db.php';

        $query = "SELECT etudiants.id_users, etudiants.lastname, etudiants.firstname,
                users.email, role.role
                FROM session
                JOIN etudiants ON etudiants.id_session = session.id
                JOIN users ON users.id = etudiants.id_users
                JOIN role ON role.id = users.id_role
                WHERE etudiants.id_session = :id_session
                AND etudiants.id_centres_de_formation = :id_centres_de_formation
                ";

        try {
            $queryexec = $this->db->prepare($query);
        
            $queryexec->bindValue(':id_session', $this->id, PDO::PARAM_INT);
            $queryexec->bindValue(':id_centres_de_formation', $this->id_centres_de_formation, PDO::PARAM_INT);
            $queryexec->execute();
            
            return $queryexec->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getSessionEnCoursFormateur(){
        require_once 'db.php';

        $query = "SELECT formateurs.id_users, formateurs.lastname, formateurs.firstname,
                users.email, role.role
                FROM session
                JOIN formateurs_participant_session ON formateurs_participant_session.id = session.id
                JOIN formateurs ON formateurs_participant_session.id_formateurs = formateurs.id
                JOIN users ON users.id = formateurs.id_users
                JOIN role ON role.id = users.id_role
                WHERE formateurs_participant_session.id = :id_session
                AND formateurs.id_centres_de_formation = :id_centres_de_formation
                ";

        try {
            $queryexec = $this->db->prepare($query);
        
            $queryexec->bindValue(':id_session', $this->id, PDO::PARAM_INT);
            $queryexec->bindValue(':id_centres_de_formation', $this->id_centres_de_formation, PDO::PARAM_INT);

            $queryexec->execute();
            
            return $queryexec->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getPointageStatus(){
        require_once 'db.php';

        $query = "SELECT pointages.id_etudiants, pointages.entree_sortie, MAX(pointages.date) AS last_pointed_date,
                etudiants.*
                FROM pointages
                JOIN etudiants ON etudiants.id = pointages.id_etudiants
                WHERE etudiants.id_session = :id_session
                ORDER BY etudiants.lastname";

        try {
            $queryexec = $this->db->prepare($query);
        
            $queryexec->bindValue(':id_session', $this->id, PDO::PARAM_INT);
            $queryexec->execute();

            return $queryexec->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    public function hasStarted(){
        require_once 'db.php';

        $query = "SELECT dateDebut FROM session WHERE id = :id";

        try {
            $queryexec = $this->db->prepare($query);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
            $queryexec->execute();

            $result = $queryexec->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $dateDebut = new DateTime($result['dateDebut']);
                $currentDate = new DateTime();
                
                return $dateDebut < $currentDate;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    
    public function getParticipants(){
        $query = "SELECT etudiants.lastname AS lastname,
            etudiants.firstname AS firstname,
            users.id AS id_users,
            users.id_role AS id_role,
            role.role AS role
            FROM session
            JOIN etudiants ON etudiants.id_session = session.id
            JOIN users ON etudiants.id_users = users.id
            JOIN role ON role.id = users.id_role
            WHERE session.id = :id

            UNION

            SELECT formateurs.lastname AS lastname,
            formateurs.firstname AS firstname,
            users.id AS id_users,
            users.id_role AS id_role,
            role.role AS role
            FROM session
            JOIN formateurs_participant_session ON formateurs_participant_session.id = session.id
            JOIN formateurs ON formateurs_participant_session.id_formateurs = formateurs.id
            JOIN users ON formateurs.id_users = users.id
            JOIN role ON users.id_role = role.id
            WHERE session.id = :id;";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
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
}
