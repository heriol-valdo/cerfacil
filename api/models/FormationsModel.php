<?php
require_once 'db.php';


class Formations extends Database{

    public static $table = "formation";
    public $id;

    public $idusers;
    public $nomF;
    public $diplomeF;
    public $intituleF; 
    public $numeroF;
    public $siretF;
    public $codeF;
    public $rnF;
    public $entrepriseF;
    public $responsableF;
    public $prix;
    public $rueF;
    public $voieF;
    public $complementF;
    public $postalF;
    public $communeF;

    public $emailF;
    public $debutO;
    public $prevuO;
    public $dureO;
    public $nomO;
    public $numeroO;
    public $siretO;
    public $rueO;
    public $voieO;
    public $complementO;
    public $postalO;
    public $communeO;

    
    public  function save($id_centre =null,$idusers=null,
        $nomF=null,$diplomeF= null,$intituleF= null,$numeroF= null,$siretF= null,$codeF= null,$rnF= null,$entrepriseF= null,$responsableF= null,$prix=null,$rueF= null,$voieF= null,$complementF= null,$postalF= null,$communeF= null,
        $emailF = null,$debutO= null,$prevuO= null,$dureO= null,$nomO= null,$numeroO= null,$siretO= null,$rueO= null,$voieO= null,$complementO= null,$postalO= null,$communeO= null,
        $id = null){
         
          $sql = 'INSERT INTO ';
          $baseSql = self::$table.' SET id_centre =:id_centre,id_users =:id_users,
           nomF = :nomF,diplomeF = :diplomeF,intituleF = :intituleF,numeroF = :numeroF,siretF = :siretF,codeF = :codeF,rnF = :rnF,entrepriseF = :entrepriseF,
           responsableF = :responsableF, prix = :prix, rueF = :rueF,voieF = :voieF,complementF = :complementF,postalF = :postalF,communeF = :communeF,emailF = :emailF,debutO = :debutO,prevuO = :prevuO,dureO = :dureO,
           nomO = :nomO,numeroO = :numeroO,siretO = :siretO,rueO = :rueO,voieO = :voieO,complementO = :complementO,postalO = :postalO,communeO = :communeO
           ';
  
          $baseParam = [ 
          ':id_centre' => $id_centre,
          ':id_users' => $idusers,
          ':nomF' => $nomF,
          ':diplomeF' => $diplomeF,
          ':intituleF' => $intituleF, 
          ':numeroF' => $numeroF,
          ':siretF' => $siretF,
          ':codeF' => $codeF,
          ':rnF' => $rnF,
          ':entrepriseF' => $entrepriseF,
          ':responsableF' => $responsableF,
          ':prix' => $prix,
          ':rueF' => $rueF,
          ':voieF' => $voieF,
          ':complementF' => $complementF,
          ':postalF' => $postalF,
          ':communeF' => $communeF,
          ':emailF' => $emailF,
          ':debutO' => $debutO,
          ':prevuO' => $prevuO,
          ':dureO' => $dureO,
          ':nomO' => $nomO,
          ':numeroO' => $numeroO,
          ':siretO' => $siretO,
          ':rueO' => $rueO,
          ':voieO' => $voieO,
          ':complementO' => $complementO,
          ':postalO' => $postalO,
          ':communeO' => $communeO ];
  
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
          return $this->query($sql,[':id'=>$id],true);
      }
  
      public  function byNom($nom){
          $sql = self::selectString() . ' WHERE nomF = :nomF';
          $param = [':nomF' => $nom];
          return $this->query($sql, $param,true);
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

        if (!is_null($search)) {
          $tSearch = ' AND (nomF LIKE :search)';
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

    public  function searchTypeIdCentre($id_centre,$nbreParPage=null,$pageCourante=null,$search = null){
        $limit = ' ORDER BY nomF ASC, intituleF ASC';
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
          $tSearch = ' AND (nomF LIKE :search )';
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
            $tSearch = ' AND (nomF LIKE :search)';
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
  
      public  function searchType($idusers,$nbreParPage=null,$pageCourante=null,$search = null){
          $limit = ' ORDER BY nomF ASC, intituleF ASC';
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
            $tSearch = ' AND (nomF LIKE :search )';
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
