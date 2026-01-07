<?php
require_once 'db.php';


class Facture extends Database{

    public static $table = "facture_cerfa";


   
    public function save($type,
        $numeroOF, $lieuF, $ibanF, $repreF, $emploiRF, $motif, $motif1, $motif2, $motif3, $motif4, $motif5, $montant,
        $montant1, $montant2, $montant3, $montant4, $montant5, $echeance1, $echeance2, $echeance3, $echeance4, $date1, $date2,
        $date3, $date4, $ht1, $ht2, $ht3, $ht4, $idcerfa
    ) {
        $sql = 'INSERT INTO ';
        $baseSql = self::$table.' SET numeroOF = :numeroOF, lieuF = :lieuF, ibanF = :ibanF, repreF = :repreF, emploiRF = :emploiRF, motif = :motif, motif1 = :motif1, motif2 = :motif2, motif3 = :motif3,
         motif4 = :motif4, motif5 = :motif5, montant = :montant, montant1 = :montant1, montant2 = :montant2, montant3 = :montant3, montant4 = :montant4, montant5 = :montant5,
          echeance1 = :echeance1, echeance2 = :echeance2, echeance3 = :echeance3, echeance4 = :echeance4, date1 = :date1, date2 = :date2, date3 = :date3, date4 = :date4, 
          ht1 = :ht1, ht2 = :ht2, ht3 = :ht3, ht4 = :ht4, idcerfa = :idcerfa';
    
    
        $baseParam = [
            ':numeroOF' => $numeroOF,
            ':lieuF' => $lieuF,
            ':ibanF' => $ibanF,
            ':repreF' => $repreF,
            ':emploiRF' => $emploiRF,
            ':motif' => $motif,
            ':motif1' => $motif1,
            ':motif2' => $motif2,
            ':motif3' => $motif3,
            ':motif4' => $motif4,
            ':motif5' => $motif5,
            ':montant' => $montant,
            ':montant1' => $montant1,
            ':montant2' => $montant2,
            ':montant3' => $montant3,
            ':montant4' => $montant4,
            ':montant5' => $montant5,
            ':echeance1' => $echeance1,
            ':echeance2' => $echeance2,
            ':echeance3' => $echeance3,
            ':echeance4' => $echeance4,
            ':date1' => $date1,
            ':date2' => $date2,
            ':date3' => $date3,
            ':date4' => $date4,
            ':ht1' => $ht1,
            ':ht2' => $ht2,
            ':ht3' => $ht3,
            ':ht4' => $ht4,
            ':idcerfa' => $idcerfa
        ];

      if($type===2){
          $sql = 'UPDATE ';
          $baseSql .= ' WHERE idcerfa= :idcerfa';
          $baseParam [':idcerfa'] = $idcerfa;
      }
      try {
        return $this->query($sql.$baseSql, $baseParam, true);
    } catch (PDOException $e) {
        return ['erreur' => $e->getMessage()];
    }
  }


  
  public  function find($id){
    $sql = static::selectString().' WHERE idcerfa = :idcerfa';
    return $this->query($sql,[':idcerfa'=>$id],true);
}


  public  function delete($id){
    $sql = 'DELETE FROM ' . self::$table . ' WHERE idcerfa = :id';
    $param = [':id' => $id];
    try {
        return $this->query($sql, $param,true);
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
