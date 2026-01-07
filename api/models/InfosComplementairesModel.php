<?php // /models/TicketEchangesModel.php
require_once 'db.php';
/*=========================================================================================

===========================================================================================*/

class InfosComplementaires extends Database{
    public $id;
    public $dateCreation;
    public $objet;
    public $contenu;
    // Clés étrangères
    public $id_users;
    public $id_etudiants;
    public $id_types_infos;

    public function add() {
        $query = "INSERT INTO `infos_complementaires` 
        (`dateCreation`,`objet`, `contenu`, `id_users`, `id_etudiants`, `id_types_infos`) 
        VALUES (:dateCreation, :objet, :contenu, :id_users, :id_etudiants, :id_types_infos)";
        
        try {
            $stmt = $this->db->prepare($query);

            $current_datetime = new DateTime();
            $paris_timezone = new DateTimeZone('Europe/Paris');
            $current_datetime->setTimezone($paris_timezone);

            $formatted_date = $current_datetime->format('Y-m-d H:i:s');

            $stmt->bindValue(":dateCreation", $formatted_date, PDO::PARAM_STR);
            $stmt->bindValue(":objet", $this->objet, PDO::PARAM_STR);
            $stmt->bindValue(":contenu", $this->contenu, PDO::PARAM_STR);
            $stmt->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
            $stmt->bindValue(":id_etudiants", $this->id_etudiants, PDO::PARAM_INT);
            $stmt->bindValue(":id_types_infos", $this->id_types_infos, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getInfos(){
        $query = "SELECT infos_complementaires.*, 
            role.role,
            users.email, 
            types_infos.nom,
            COALESCE(administrateurs.firstname, gestionnaires_centre.firstname, 
            formateurs.firstname) AS firstname,
            COALESCE(administrateurs.lastname, gestionnaires_centre.lastname,
            formateurs.lastname) AS lastname
        FROM infos_complementaires 
        JOIN etudiants ON etudiants.id = infos_complementaires.id_etudiants
        LEFT JOIN administrateurs ON administrateurs.id_users = infos_complementaires.id_users
        LEFT JOIN gestionnaires_centre ON gestionnaires_centre.id_users = infos_complementaires.id_users
        LEFT JOIN formateurs ON formateurs.id_users = infos_complementaires.id_users
        LEFT JOIN types_infos ON types_infos.id = infos_complementaires.id_types_infos
        LEFT JOIN users ON users.id = infos_complementaires.id_users
        LEFT JOIN role ON role.id = users.id_role
        WHERE infos_complementaires.id_etudiants = :id_etudiants
        ORDER BY infos_complementaires.dateCreation DESC";


        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id_etudiants", $this->id_etudiants, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($result)) {
                return $result;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    /*
    public function deleteOneTicketEchange() {
        require_once 'db.php';
    
        $query = "DELETE FROM tickets_echanges
                    WHERE id = :id";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function boolId() {
        require_once 'db.php';
    
        $query = "SELECT * 
                FROM tickets_echanges 
                WHERE id = :id";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute();
    
            $count = $stmt->fetchColumn();
    
            return ($count >= 1);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function searchAllForId() {
        require_once 'db.php';
    
        $query = "SELECT * 
                FROM tickets_echanges 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result;
    }
    */
}