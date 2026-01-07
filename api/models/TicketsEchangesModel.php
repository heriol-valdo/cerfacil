<?php // /models/TicketEchangesModel.php
require_once 'db.php';
/*=========================================================================================
TicketsEchangesModel.php => Résumé des fonctions
===========================================================================================
addTicketEchange() : Ajoute un message par rapport à un ticket
getListTicketEchangeByIdTickets($userRoleId) : Récupère la liste des échanges en fonction du ticket pour admin
deleteOneTicketEchange() : Supprime un message par rapport à un ticket
===========================================================================================*/

class TicketEchange extends Database{
    public $id;
    public $dateCreation;
    public $contenu;
    // Clés étrangères
    public $id_users;
    public $id_tickets;

    public function addTicketEchange() {
        $insertQuery= "INSERT INTO `tickets_echanges` 
        (`contenu`, `id_users`, `id_tickets`) 
        VALUES (:contenu, :id_users, :id_tickets)";
        
        try {
            $stmt = $this->db->prepare($insertQuery);

            $stmt->bindParam(":contenu", $this->contenu, PDO::PARAM_STR);
            $stmt->bindParam(":id_users", $this->id_users, PDO::PARAM_INT);
            $stmt->bindParam(":id_tickets", $this->id_tickets, PDO::PARAM_INT);

            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getListTicketEchangeByIdTickets() {
        $query = "SELECT tickets_echanges.*, 
        COALESCE(administrateurs.firstname, gestionnaires_entreprise.firstname, gestionnaires_centre.firstname, 
        formateurs.firstname, conseillers_financeurs.firstname) AS firstname,
        COALESCE(administrateurs.lastname, gestionnaires_entreprise.lastname, gestionnaires_centre.lastname,
        formateurs.lastname, conseillers_financeurs.lastname) AS lastname
        FROM tickets_echanges 
        JOIN tickets ON tickets.id = tickets_echanges.id_tickets
        LEFT JOIN administrateurs ON administrateurs.id_users = tickets_echanges.id_users
        LEFT JOIN gestionnaires_entreprise ON gestionnaires_entreprise.id_users = tickets_echanges.id_users
        LEFT JOIN gestionnaires_centre ON gestionnaires_centre.id_users = tickets_echanges.id_users
        LEFT JOIN formateurs ON formateurs.id_users = tickets_echanges.id_users
        LEFT JOIN conseillers_financeurs ON conseillers_financeurs.id_users = tickets_echanges.id_users
        WHERE tickets_echanges.id_tickets = :id_tickets";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id_tickets", $this->id_tickets, PDO::PARAM_INT);
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
}