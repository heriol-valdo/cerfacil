<?php
require_once 'db.php';


class Entreprises extends Database{

    public static $table = "entreprise";
    public $id;

    public $idusers;
    public $nomE;
    public$typeE;
    public $specifiqueE;
    public $totalE;
    public $siretE;
    public $codeaE;
    public $codeiE;
    public $rueE;
    public $voieE;
    public $complementE;
    public $postalE;
    public $communeE;
    public $emailE;
    public $numeroE;
    public $idopco;
    public $id_centre;

    public  function save($id_centre=null,$idusers=null,$nomE=null,$typeE=null, $specifiqueE=null, $totalE=null, $siretE=null,   $codeaE=null,
    $codeiE=null, $rueE=null, $voieE=null, $complementE=null, $postalE=null, $communeE=null, $emailE=null, $numeroE=null,$idopco=null,  
    $id = null){
       
        $sql = 'INSERT INTO ';
        $baseSql = self::$table.' SET id_centre =:id_centre,id_users =:id_users,nomE = :nomE, typeE = :typeE, specifiqueE = :specifiqueE, totalE = :totalE,
         siretE = :siretE, codeaE = :codeaE, codeiE = :codeiE,
         rueE = :rueE, voieE = :voieE, complementE = :complementE, postalE = :postalE, 
         communeE = :communeE, emailE = :emailE, numeroE = :numeroE, idopco = :idopco';


        $baseParam = [
        ':id_centre' => $id_centre,
        ':id_users' => $idusers,
        ':nomE' => $nomE,
        ':typeE' => $typeE,
        ':specifiqueE' => $specifiqueE,
        ':totalE' => $totalE,
        ':siretE' => $siretE,
        ':codeaE' => $codeaE,
        ':codeiE' => $codeiE,
        ':rueE' => $rueE,
        ':voieE' => $voieE,
        ':complementE' => $complementE,
        ':postalE' => $postalE,
        ':communeE' => $communeE,
        ':emailE' => $emailE,
        ':numeroE' => $numeroE,
        ':idopco' => $idopco ];

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

    public  function update($typeE=null, $specifiqueE=null, $totalE=null, $siretE=null,   $codeaE=null,
    $codeiE=null, $rueE=null, $voieE=null, $complementE=null, $postalE=null, $communeE=null,  $numeroE=null,  
    $id = null){
       
        $sql = 'INSERT INTO ';
        $baseSql = self::$table.' SET typeE = :typeE, specifiqueE = :specifiqueE, totalE = :totalE,
         siretE = :siretE, codeaE = :codeaE, codeiE = :codeiE,
         rueE = :rueE, voieE = :voieE, complementE = :complementE, postalE = :postalE, 
         communeE = :communeE,  numeroE = :numeroE';


        $baseParam = [
        ':typeE' => $typeE,
        ':specifiqueE' => $specifiqueE,
        ':totalE' => $totalE,
        ':siretE' => $siretE,
        ':codeaE' => $codeaE,
        ':codeiE' => $codeiE,
        ':rueE' => $rueE,
        ':voieE' => $voieE,
        ':complementE' => $complementE,
        ':postalE' => $postalE,
        ':communeE' => $communeE,
        ':numeroE' => $numeroE,
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
        $sql = self::selectString().' WHERE id = :id';
        return $this->query($sql,[':id'=>$id],true);
    }

    public  function findbyopco($idopco){
        $sql = self::selectString().' WHERE idopco = :idopco';
        return $this->query($sql,[':idopco'=>$idopco],true);
    }

    public  function byNom($nom){
        $sql = self::selectString() . ' WHERE nomE = :nomE';
        $param = [':nomE' => $nom];
        // try {
        //     return $this->query($sql, $param,true);
        // } catch (PDOException $e) {
        //     return ['erreur' => $e->getMessage()];
        // }

        return $this->query($sql, $param,true);
    }


    
  public  function byEmail($email){
      $sql = self::selectString() . ' WHERE emailE = :emailE';
      $param = [':emailE' => $email];
      return $this->query($sql, $param);
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
        if(isset($search)){
            $tSearch = ' AND (nomE LIKE :search)';
            $tab[':search'] = '%'.$search.'%';
        }else{
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
        $limit = ' ORDER BY nomE ';
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
            $tSearch = ' AND (nomE LIKE :search )';
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


    public  function countBySearchTypeIdCentre($id_centre,$search = null){
        $count = 'SELECT COUNT(*) AS Total FROM '.self::$table;
        $where = ' WHERE 1 = 1';
        $tab = [];
        if(isset($id_centre)){
            $tIdUsers = ' AND (id_centre = :id_centre )';
            $tab[':id_centre'] = $id_centre;
        }else{
            $tIdUsers = '';
        }
        if(isset($search)){
            $tSearch = ' AND (nomE LIKE :search)';
            $tab[':search'] = '%'.$search.'%';
        }else{
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
        $limit = ' ORDER BY nomE ';
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
            $tSearch = ' AND (nomE LIKE :search )';
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

