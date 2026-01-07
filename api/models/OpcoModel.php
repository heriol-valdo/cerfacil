<?php
require_once 'db.php';


class Opco extends Database{

    public static $table = "opco";
    public $id;
    public $nom;
    public $cle;
    public $lienE;
    //Clés étrangères
    public $lienCe;

    public $lienCo;
    public $lienF;

    public $lienT;

    public $clid;

    public $clse;
    public $idusers;
    public $id_centre;

    public  function save($id_centre=null,$idusers=null,$nom=null,$cle= null,$lienE= null,$lienCe= null, $lienCo= null, $lienF= null,$lienT= null,$clid= null,$clse= null,$id = null){
       
        $sql = 'INSERT INTO ';
        $baseSql = self::$table.' SET id_centre =:id_centre,id_users =:id_users,nom = :nom,cle = :cle,lienE = :lienE, lienCe = :lienCe, lienCo = :lienCo , lienF = :lienF, lienT = :lienT, clid = :clid, clse = :clse ';


        $baseParam = [  
        ':id_centre' => $id_centre,
        ':id_users' => $idusers,
        ':nom' => $nom,
        ':cle' => $cle,
        ':lienE' => $lienE,
        ':lienCe' => $lienCe,
        ':lienCo' => $lienCo,
        ':lienF' => $lienF,
        ':lienT' => $lienT,
        ':clid' => $clid,
        ':clse' => $clse
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
    public  function find($id){
        $sql = static::selectString().' WHERE id = :id';
        if (!is_null($id)) {
            $param = [':id'=>$id];
        } else {
            $param = '';
        }
        // try {
        //     return $this->query($sql,$param,true);
        // } catch (PDOException $e) {
        //     return ['erreur' => $e->getMessage()];
        // }
        
        return $this->query($sql,$param,true);
    }

    public  function byNom($nom){
        $sql = self::selectString() .'WHERE nom = :nom';
        if (!is_null($nom)) {
            $param = [':nom'=>$nom];
        } else {
            $param = '';
        }
        try {
            return $this->query($sql, $param,true);
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


    public  function countBySearchType($idusers,$search = null) {
        $count = 'SELECT COUNT(*) AS Total FROM ' . self::$table;
        $where = ' WHERE 1 = 1';
        $tab = [];

        if(isset($idusers)){
            $tIdUsers = ' AND (id_users LIKE :idusers )';
            $tab[':idusers'] = $idusers;
        }else{
            $tIdUsers = '';
        }
        if (!is_null($search)) {
            $tSearch = ' AND (nom LIKE :search)';
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
        $limit = ' ORDER BY nom ASC';
        $limit .= (isset($nbreParPage)&&isset($pageCourante))?' LIMIT '.(($pageCourante-1)*$nbreParPage).','.$nbreParPage:'';
        $where = ' WHERE 1 = 1';
        $tab = [];

        if(isset($idusers)){
            $tIdUsers = ' AND (id_users = :idusers )';
            $tab[':idusers'] = $idusers;
        }else{
            $tIdUsers = '';
        }

        if(isset($search)){
            $tSearch = ' AND (nom LIKE :search )';
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

    public  function countBySearchTypeIdCentre($id_centre,$search = null) {
        $count = 'SELECT COUNT(*) AS Total FROM ' . self::$table;
        $where = ' WHERE 1 = 1';
        $tab = [];

        if(isset($id_centre)){
            $tIdUsers = ' AND (id_centre = :id_centre )';
            $tab[':id_centre'] = $id_centre;
        }else{
            $tIdUsers = '';
        }
        if (!is_null($search)) {
            $tSearch = ' AND (nom LIKE :search)';
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
    public  function searchTypeIdCentre($id_centre,$nbreParPage=null,$pageCourante=null,$search = null){
        $limit = ' ORDER BY nom ASC';
        $limit .= (isset($nbreParPage)&&isset($pageCourante))?' LIMIT '.(($pageCourante-1)*$nbreParPage).','.$nbreParPage:'';
        $where = ' WHERE 1 = 1';
        $tab = [];

        if(isset($id_centre)){
            $tIdUsers = ' AND (id_centre = :id_centre )';
            $tab[':id_centre'] = $id_centre;
        }else{
            $tIdUsers = '';
        }

        if(isset($search)){
            $tSearch = ' AND (nom LIKE :search )';
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

