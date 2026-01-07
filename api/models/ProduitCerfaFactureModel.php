<?php
require_once 'db.php';


class ProduitCerfaFacture extends Database{

    public static $table = "produit_cerfa_facture";
    public $id;
   
    public $id_produit;

    public $id_users;

    public $quantite;

    public $totalDossier;

    public $totalFacture;

    public $date_debut;
    public $date_fin;

    public $totalAbonement;

    public $id_centre;

    public $stripe_id;



    public  function save($id_produit,$id_centre,$id_users,$quantite,$totalDossier,$totalFacture,$date_debut=null, $date_fin=null,$totalAbonement=null,$type=null,$stripe_id=null){
       
        $sql = 'INSERT INTO ';
        $baseSql = self::$table.' SET id_centre =:id_centre,id_users =:id_users,date_debut = :date_debut,date_fin = :date_fin,quantite = :quantite, id_produit = :id_produit, 
        totalDossier = :totalDossier,totalAbonement = :totalAbonement, totalFacture = :totalFacture, stripe_id = :stripe_id ';


        if ($totalAbonement !== null) {
            if(intval($type) === 1){
                
                $NtotalAbonement = null;
                $Ndate_debut = $date_debut;
                $Ndate_fin = $date_fin;
    
              }else{
                $NtotalAbonement = $totalAbonement;
                $Ndate_debut = $date_debut;
                $Ndate_fin = $date_fin;
              }
    
           }else{
                $NtotalAbonement = null;
                $Ndate_debut = $date_debut;
                $Ndate_fin = $date_fin;

           }

           if(intval($quantite) !== 0){
              $Nquantite = $quantite;
              $NtotalDossier = $totalDossier;

           }else{
                $Nquantite = 0;
                $NtotalDossier = 0;
           }
          
            

        $baseParam = [ 
        ':id_centre' => $id_centre,
        ':id_produit' => $id_produit,
        ':id_users' => $id_users,
        ':quantite' => $Nquantite,
        ':totalDossier' => $NtotalDossier,
        ':date_debut' => $Ndate_debut,
        ':date_fin' => $Ndate_fin,
        ':totalAbonement' => $NtotalAbonement,
        ':totalFacture' => $totalFacture,
        ':stripe_id' => $stripe_id
      
       ];

       

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

    public  function searchType($idusers){
        $where = ' WHERE 1 = 1';
        $tab = [];

        if(isset($idusers)){
            $tIdUsers = ' AND (id_users = :idusers )';
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

