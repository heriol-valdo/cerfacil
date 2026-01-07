<?php // /models/TicketModel.php
require_once 'db.php';
/*=========================================================================================
TicketModel.php => Résumé des fonctions
===========================================================================================
> addTicket() : ajouter un ticket
> boolId() : retourne True si : id
> searchForId : retourne * pour : id
> searchAllForUser() : retourne infos du ticket en fonction de l'utilisateur (id_users)
> searchAllForEtat() : retourne tous les tickets selon l'état du ticket
> searchAll() : retourne tous les tickets
> searchAuthorRoleForId() : retourne le role de l'auteur du ticket (id)
> searchOne() : retourne toutes les infos d'un ticket en fonction de l'utilisateur
> deleteTicket
> updateEtat
===========================================================================================*/

class Ticket extends Database{

    public static $table = "tickets";
    public $id;
    public $objet;
    public $telephone;
    public $description;
    public $dateCreation;
    public $id_users;
    public $id_etat_ticket;


    public function addTicket() {
        $insertQuery= "INSERT INTO `tickets` (`objet`, `telephone`, `description`, `dateCreation`, `id_users`, `id_etat_ticket`) 
        VALUES (:objet, :telephone, :description, :dateCreation, :id_users, :id_etat_ticket)";
        
        $stmt = $this->db->prepare($insertQuery);

        $stmt->bindParam(":objet", $this->objet, PDO::PARAM_STR);
        $stmt->bindParam(":telephone", $this->telephone, PDO::PARAM_STR);
        $stmt->bindParam(":description", $this->description, PDO::PARAM_STR);
        $stmt->bindParam(":dateCreation", $this->dateCreation, PDO::PARAM_STR);
        $stmt->bindParam(":id_users", $this->id_users, PDO::PARAM_INT);
        $stmt->bindParam(":id_etat_ticket", $this->id_etat_ticket, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function boolId() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM tickets WHERE id = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_STR);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    public function searchForId() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM tickets WHERE id = :id";
        
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

    public function searchAllForUser() {
        require_once 'db.php';

        $existingQuery = "SELECT tickets.id, tickets.dateCreation, etat_ticket.etat, tickets.objet
                        FROM tickets 
                        JOIN users ON tickets.id_users = users.id
                        JOIN role ON users.id_role = role.id
                        JOIN etat_ticket ON tickets.id_etat_ticket = etat_ticket.id
                        WHERE tickets.id_users = :id_users";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    public function searchAllForEtat() {
        require_once 'db.php';

        $existingQuery = "SELECT tickets.id, tickets.dateCreation, etat_ticket.etat, tickets.objet, users.email, role.role,
                        COUNT(tickets.id) as nbEtat
                        FROM tickets 
                        JOIN users ON tickets.id_users = users.id
                        JOIN role ON users.id_role = role.id
                        JOIN etat_ticket ON tickets.id_etat_ticket = etat_ticket.id
                        WHERE tickets.id_etat_ticket = :id_etat_ticket";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id_etat_ticket", $this->id_etat_ticket, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }


    public function searchAll() {
        require_once 'db.php';

        $existingQuery = "SELECT tickets.id, tickets.dateCreation, etat_ticket.etat, tickets.objet, users.email, role.role
                        FROM tickets 
                        JOIN users ON tickets.id_users = users.id
                        JOIN role ON users.id_role = role.id
                        JOIN etat_ticket ON tickets.id_etat_ticket = etat_ticket.id";
                            
        $stmt = $this->db->prepare($existingQuery);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    // Renvoie role de l'auteur d'un ticket
    public function searchAuthorRoleForId(){
        require_once 'db.php';
        $authorRoleQuery = "SELECT users.id_role
        FROM tickets 
        JOIN users ON tickets.id_users = users.id
        WHERE tickets.id = :id";

        $stmt = $this->db->prepare($authorRoleQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
        return $result;
        } else {
        return null;
        }   
    }

    // Renvoie des informations plus détaillées sur un ticket
    public function searchOne() {
        require_once 'db.php';

        $authorIdRole = $this->searchAuthorRoleForId();

        // Complète la requête SQL en fonction du rôle
        switch ($authorIdRole['id_role']) {
            case 1:
                $firstSelector = "administrateurs.firstname AS firstname, administrateurs.lastname AS lastname";
                $secondSelector = "JOIN administrateurs ON administrateurs.id_users = users.id";
                break;
            case 2:
                $firstSelector =  "gestionnaires_entreprise.firstname AS firstname, gestionnaires_entreprise.lastname AS lastname";
                $secondSelector = "JOIN gestionnaires_entreprise ON gestionnaires_entreprise.id_users = users.id";
                break;
            case 3:
                $firstSelector = "gestionnaires_centre.firstname AS firstname, gestionnaires_centre.lastname AS lastname";
                $secondSelector = "JOIN gestionnaires_centre ON gestionnaires_centre.id_users = users.id";
                break;
            case 4:
                $firstSelector = "formateurs.firstname AS firstname, formateurs.lastname AS lastname";
                $secondSelector = "JOIN formateurs ON formateurs.id_users = users.id";
                break;
            case 6:
                $firstSelector = "conseillers_financeurs.firstname AS firstname, conseillers_financeurs.lastname AS lastname";
                $secondSelector = "JOIN conseillers_financeurs ON conseillers_financeurs.id_users = users.id";
                break;   
            case 7:
                $firstSelector = "clients_cerfa.firstname AS firstname, clients_cerfa.lastname AS lastname";
                $secondSelector = "JOIN clients_cerfa ON clients_cerfa.id_users = users.id";
                break;      
            default:
                $response->getBody()->write(json_encode(['erreur' => 'Le rôle n\'est pas bon']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $existingQuery = 'SELECT tickets.*, 
                        '.$firstSelector.', users.email, role.role, etat_ticket.etat
                        FROM tickets 
                        JOIN users ON tickets.id_users = users.id
                        JOIN role ON users.id_role = role.id
                        JOIN etat_ticket ON tickets.id_etat_ticket = etat_ticket.id
                        '.$secondSelector.'
                        WHERE tickets.id = :id';
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    public function deleteTicket() {
        require_once 'db.php';
    
        $deleteQuery = "DELETE FROM tickets WHERE id = :id";;
        
        $stmt = $this->db->prepare($deleteQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateEtat() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE tickets SET id_etat_ticket = :id_etat_ticket WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':id_etat_ticket', $this->id_etat_ticket, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateReponse() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE tickets SET reponse = :reponse WHERE id = :id";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':reponse', $this->reponse, PDO::PARAM_STR);
            $queryexec->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    public  function countBySearchType($idusers,$search = null){
        $count = 'SELECT COUNT(*) AS Total FROM '.self::$table;
        $where = ' WHERE 1 = 1';
        $tab = [];

        if(isset($idusers)){
          $tIdUsers = ' AND (id_users = :idusers )';
          $tab[':idusers'] = $idusers;
      }else{
          $tIdUsers = '';
      }

        if (!is_null($search)) {
          $tSearch = ' AND (objet LIKE :search)';
          $tab[':search'] = '%' . $search . '%';
      } else {
          $tSearch = '';
      }

      try {
          $result = $this->query($count.$where.$tSearch.$tIdUsers, $tab, true);
          return ['total' => $result['Total']];
      } catch (PDOException $e) {
          return ['erreur' => $e->getMessage()];
      }
    }

    

    public function searchAllForUsers($nbreParPage = null, $pageCourante = null, $search = null)
{
    // Base query
    $baseQuery = "SELECT tickets.id, tickets.dateCreation, etat_ticket.etat, tickets.objet
                  FROM tickets 
                  JOIN users ON tickets.id_users = users.id
                  JOIN role ON users.id_role = role.id
                  JOIN etat_ticket ON tickets.id_etat_ticket = etat_ticket.id
                  WHERE tickets.id_users = :id_users";

    // Prepare parameters array
    $params = [':id_users' => $this->id_users];

    // Add search filter if search term is provided
    if ($search !== null && trim($search) !== '') {
        $baseQuery .= " AND tickets.objet LIKE :search";
        $params[':search'] = '%' . trim($search) . '%';
    }

    // Add sorting
    $baseQuery .= " ORDER BY tickets.objet ASC";

    // Add pagination if both nbreParPage and pageCourante are provided
    $isPaginated = false;
    if ($nbreParPage !== null && $pageCourante !== null) {
        $offset = max(0, ($pageCourante - 1) * $nbreParPage);
        $baseQuery .= " LIMIT :offset, :limit";
        $params[':offset'] = (int)$offset;
        $params[':limit'] = (int)$nbreParPage;
        $isPaginated = true;
    }

    try {
        // Prepare the statement
        $stmt = $this->db->prepare($baseQuery);

        // Bind parameters with correct types
        foreach ($params as $key => $value) {
            $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $paramType);
        }

        // Execute the query
        $stmt->execute();

        // Fetch results
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return results or null if no results
        return $result ?: null;

    } catch (PDOException $e) {
        // Log the error
        error_log('Database error in searchAllForUser: ' . $e->getMessage());
        
        // Return error details
        return [
            'erreur' => 'Erreur de recherche des tickets',
            'details' => $e->getMessage()
        ];
    }
}

        public static function selectString(){
            return 'SELECT * FROM '.self::$table;
        }
        public  function query($sql, $params = [], $single = false) {
            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();

            if ($single) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }

}