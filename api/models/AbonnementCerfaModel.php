<?php
require_once 'db.php';


class AbonnementCerfa extends Database{

    public static $table = "abonement_cerfa";
    public $id;
    public $date_debut;
    public $date_fin;
    public $quantite;
    //Clés étrangères
    public $id_produit;

    
    public $id_users;

    public $id_centre;

    public  function save($date_debut,$date_fin,$quantite,$id_produit, $id_centre=null ,$id_users=null,$id=null){
       
        $sql = 'INSERT INTO ';
        $baseSql = self::$table.' SET id_centre =:id_centre, id_users =:id_users,date_debut = :date_debut,date_fin = :date_fin,quantite = :quantite, id_produit = :id_produit';


        $baseParam = [ 
        ':id_centre' => $id_centre,
        ':id_users' => $id_users,
        ':date_debut' => $date_debut,
        ':date_fin' => $date_fin,
        ':quantite' => $quantite,
        ':id_produit' => $id_produit
        
       ];

        if(isset($id)){
            $sql = 'UPDATE ';
            $baseSql .= ' WHERE id = :id';
            $baseParam [':id'] = $id;
        }

        try {
            return $this->query($sql.$baseSql, $baseParam, true);
        } catch (PDOException $e) {
            return ['erreur' => $e->getMessage()];
        }

        
    }
    public function find($id_users, $id_produit) {
        $sql = static::selectString() . ' WHERE id_users = :id_users AND id_produit = :id_produit';
        $params = [':id_users' => $id_users, ':id_produit' => $id_produit];
    
        try {
            return $this->query($sql, $params, true);
        } catch (PDOException $e) {
            return ['erreur' => $e->getMessage()];
        }
    }

    public function findIdCentre($id_centre, $id_produit) {
        $sql = static::selectString() . ' WHERE id_centre = :id_centre AND id_produit = :id_produit';
        $params = [':id_centre' => $id_centre, ':id_produit' => $id_produit];
    
        try {
            return $this->query($sql, $params, true);
        } catch (PDOException $e) {
            return ['erreur' => $e->getMessage()];
        }
    }

    public function updateAbonnementById($id) {
        $sql = "UPDATE abonement_cerfa SET quantite = quantite - 1 WHERE id = :id AND quantite > 0";
        $params = [':id' => $id];
    
        try {
            return $this->query($sql, $params,true);
        } catch (PDOException $e) {
            return ['erreur' => $e->getMessage()];
        }
    }
  

    public  function delete($id){
        $sql = 'DELETE FROM ' . self::$table . ' WHERE id = :id';
        $param = [':id' => $id];
        try {
            return $this->query($sql, $param,true);
        } catch (PDOException $e) {
            return ['erreur' => $e->getMessage()];
        }
    }

    public  function searchType($idusers){
        $where = ' WHERE 1 = 1';
        $tab = [];

        if(isset($idusers)){
            $tIdUsers = ' AND (id_users = :idusers)';
            $tab[':idusers'] = $idusers;
        }else{
            $tIdUsers = '';
        }

        
        try {
            return $this->query(self::selectString().$where.$tIdUsers,$tab);
        } catch (PDOException $e) {
            return ['erreur' => $e->getMessage()];
        }
    }

    public  function searchTypeIdCentre($id_centre){
        $where = ' WHERE 1 = 1';
        $tab = [];

        if(isset($id_centre)){
            $tIdUsers = ' AND (id_centre = :id_centre)';
            $tab[':id_centre'] = $id_centre;
        }else{
            $tIdUsers = '';
        }

        
        try {
            return $this->query(self::selectString().$where.$tIdUsers,$tab);
        } catch (PDOException $e) {
            return ['erreur' => $e->getMessage()];
        }
    }

    public  function searchAllForIdProduit($id_produit){
        $where = ' WHERE 1 = 1';
        $tab = [];

        if(isset($id_produit)){
            $tIdUsers = ' AND (id_produit = :id_produit )';
            $tab[':id_produit'] = $id_produit;
        }else{
            $tIdUsers = '';
        }

        
        try {
            return $this->query(self::selectString().$where.$tIdUsers,$tab);
        } catch (PDOException $e) {
            return ['erreur' => $e->getMessage()];
        }
    }

    public static function selectString(){
        return 'SELECT * FROM '.self::$table;
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

