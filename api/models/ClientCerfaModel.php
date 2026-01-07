<?php
require_once 'db.php';


class ClientCerfa extends Database{

    public static $table = "clients_cerfa";
    public $id;
    public $firstname;
    public $lastname;
    public $adressePostale;
    public $codePostal;
    public $ville;

    public $telephone;

    public $idCreation;

    public $roleCreation;
   
    public $id_users;

    function getProfilClientCerfa() {
        require_once 'db.php';

        $query = "SELECT *
        FROM clients_cerfa AS t1
        JOIN users AS t2 ON t1.id_users = t2.id   
        WHERE t1.id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT); 
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $res; 
    }

    

    function getClientCerfaDatasById() {
        $query = "SELECT * FROM clients_cerfa WHERE id = :id";
        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id", $this->id, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    function getClientCerfaIdByUserId() {

        $query = "SELECT id FROM clients_cerfa WHERE id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $res;
    }

    

   
    

    public function addClientCerfa() {
    
        $insertQuery= "INSERT INTO `clients_cerfa` (
            `firstname`, `lastname`, `adressePostale`, `codePostal`, `ville`, `telephone`, `idCreation`, `roleCreation`, `id_users`) 
            VALUES (
                :firstname, :lastname, :adressePostale, :codePostal, :ville, :telephone, :idCreation, :roleCreation, :id_users
                )";
        
        $stmt = $this->db->prepare($insertQuery);

        $stmt->bindParam(":firstname", $this->firstname, PDO::PARAM_STR);
        $stmt->bindParam(":lastname", $this->lastname, PDO::PARAM_STR);
        $stmt->bindParam(":adressePostale", $this->adressePostale, PDO::PARAM_STR);
        $stmt->bindParam(":codePostal", $this->codePostal, PDO::PARAM_STR);
        $stmt->bindParam(":ville", $this->ville, PDO::PARAM_STR);
        $stmt->bindParam(":telephone", $this->telephone, PDO::PARAM_STR);

        $stmt->bindParam(":idCreation", $this->idCreation, PDO::PARAM_INT);
        $stmt->bindParam(":roleCreation", $this->roleCreation, PDO::PARAM_INT);
        $stmt->bindParam(":id_users", $this->id_users, PDO::PARAM_INT);

        $stmt->execute();
    }

    
    // Renvoie toutes les infos d'un étudiant
    public function searchForId() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM clients_cerfa WHERE id_users = :id_users";
        
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

    public function deleteUser($id) {
        require_once 'db.php';
    
        $deleteQuery = "DELETE FROM clients_cerfa WHERE id = :id";;
        
        $stmt = $this->db->prepare($deleteQuery);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    

    public function searchForIdClientCerfa() {
        require_once 'db.php';

        $existingQuery = "SELECT * FROM clients_cerfa WHERE id = :id";
        
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

    
    public function searchAllClientCerfaForAdmin() {
        require_once 'db.php';

        $existingQuery = "SELECT users.email, clients_cerfa.*
        FROM clients_cerfa
        JOIN users ON clients_cerfa.id_users = users.id
        WHERE clients_cerfa.roleCreation = 1
        ";

        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    




    public function updateFirstname() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE clients_cerfa SET firstname = :firstname WHERE id_users = :id_users";
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
            $query = "UPDATE clients_cerfa SET lastname = :lastname WHERE id_users = :id_users";
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
            $query = "UPDATE clients_cerfa SET adressePostale = :adressePostale WHERE id_users = :id_users";
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
            $query = "UPDATE clients_cerfa SET codePostal = :codePostal WHERE id_users = :id_users";
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
            $query = "UPDATE clients_cerfa SET ville = :ville WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':ville', $this->ville, PDO::PARAM_STR);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    
    public function updateTelephone() {
        require_once 'db.php';
    
        try {
            $query = "UPDATE clients_cerfa SET telephone = :telephone WHERE id_users = :id_users";
            $queryexec = $this->db->prepare($query);
           
            $queryexec->bindValue(':telephone', $this->telephone, PDO::PARAM_STR);
            $queryexec->bindValue(':id_users', $this->id_users, PDO::PARAM_INT);
    
            return $queryexec->execute();
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    
    public function boolTelephone() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM clients_cerfa WHERE telephone = :telephone";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":telephone", $this->telephone, PDO::PARAM_STR);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    

    // Return true si le clients_cerfa existe
    public function boolId() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM clients_cerfa WHERE id_users = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id_users, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    // Return true si le clients_cerfa existe
    public function boolIdRole() {
        require_once 'db.php';
    
        $existingQuery = "SELECT * FROM clients_cerfa WHERE id = :id";
        
        $stmt = $this->db->prepare($existingQuery);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return ($count >= 1);
    }

    function getAllAchatByIdClient() {
        require_once 'db.php';
    
       
        $query = "SELECT clients_cerfa.*, users.email, produit_cerfa.*, produit_cerfa_facture.*
                  FROM clients_cerfa
                  JOIN users ON clients_cerfa.id_users = users.id
                  JOIN produit_cerfa_facture ON clients_cerfa.id_users = produit_cerfa_facture.id_users
                  JOIN produit_cerfa ON produit_cerfa_facture.id_produit = produit_cerfa.id
                  WHERE clients_cerfa.id_users = :id_users";
    
      
        $queryexec = $this->db->prepare($query);
       
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        
        $queryexec->execute();
       
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);
    
       
        return $res; 
    }
    


    function getProfil() {
        require_once 'db.php';

        $query = "SELECT clients_cerfa.*, users.email, role.role
        FROM clients_cerfa
        JOIN users ON clients_cerfa.id_users = users.id
        JOIN role ON role.id = users.id_role

        WHERE clients_cerfa.id_users = :id_users";

        $queryexec = $this->db->prepare($query);
        $queryexec->bindValue(":id_users", $this->id_users, PDO::PARAM_INT);
        $queryexec->execute();
        $res = $queryexec->fetchAll(PDO::FETCH_ASSOC);

        return $res; 
    }


    
    
    public  function countBySearchType($idusers,$search = null) {
        $count = 'SELECT COUNT(*) AS Total FROM ' . self::$table;
        $where = ' WHERE 1 = 1';
        $tab = [];

        if(isset($idusers)){
            $tIdUsers = ' AND (idCreation = :idusers )';
            $tab[':idusers'] = $idusers;
        }else{
            $tIdUsers = '';
        }
        if (!is_null($search)) {
            $tSearch = ' AND (firstname LIKE :search)';
            $tab[':search'] = '%' . $search . '%';
        } else {
            $tSearch = '';
        }

        try {
            $result = $this->query($count . $where . $tSearch.$tIdUsers, $tab, true);
            return ['total' => $result['Total']];
        } catch (PDOException $e) {
            return ['erreur' => $e->getMessage()];
        }
    }

    public  function searchType($idusers,$nbreParPage=null,$pageCourante=null,$search = null){
        $limit = ' ORDER BY firstname ASC';
        $limit .= (isset($nbreParPage)&&isset($pageCourante))?' LIMIT '.(($pageCourante-1)*$nbreParPage).','.$nbreParPage:'';
        $where = ' WHERE 1 = 1';
        $tab = [];

        if(isset($idusers)){
            $tIdUsers = ' AND (idCreation = :idusers )';
            $tab[':idusers'] = $idusers;
        }else{
            $tIdUsers = '';
        }

        if(isset($search)){
            $tSearch = ' AND (firstname LIKE :search )';
            $tab[':search'] = '%'.$search.'%';
        }else{
            $tSearch = '';
        }
        
        try {
            return $this->query(self::selectString().$where.$tSearch.$tIdUsers.$limit,$tab);
        } catch (PDOException $e) {
            return ['erreur' => $e->getMessage()];
        }
    }

    public static function selectString(){
        return 'SELECT clients_cerfa.*, users.email FROM clients_cerfa JOIN users ON clients_cerfa.id_users = users.id
        ';
    }
    public  function query($sql, $params = [], $single = false) {
        try {
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
        } catch (PDOException $e) {
            throw new Exception('Erreur de requête SQL : ' . $e->getMessage());
        }
    }
}

   


    
