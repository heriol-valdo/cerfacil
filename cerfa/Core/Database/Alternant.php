<?php

namespace Projet\Database;


use Projet\Model\Table;

class Alternant extends Table{

    protected static $table = 'alternant';


    
    public static function save($nom,$prenom,$numero,$sexe,$email,$id=null){
        $sql = 'INSERT INTO ';
        $baseSql = self::getTable().' SET prenom = :prenom,numero = :numero,
            nom = :nom,sexe = :sexe,email = :email';
        $baseParam = [':prenom' => $prenom,':sexe' => $sexe,':nom' => $nom,
            ':email' => $email,':numero' => $numero];
        if(isset($id)){
            $sql = 'UPDATE ';
            $baseSql .= ' WHERE id = :id';
            $baseParam [':id'] = $id;
        }
        return self::query($sql.$baseSql, $baseParam, true, true);
    }

    public static function find($id){
        $sql = static::selectString().' WHERE id = :id';
        return self::query($sql,[':id'=>$id],true);
    }


  

   
    

    public static function byNom($name){
        $sql = self::selectString() . ' WHERE nom = :nom';
        $param = [':nom' => $name];
        return self::query($sql, $param,true);
    }

   

    public static function byEmail($email){
        $sql = self::selectString() . ' WHERE email = :email';
        $param = [':email' => $email];
        return self::query($sql, $param,true);
    }

    public static function byNumero($numero){
        $sql = self::selectString() . ' WHERE numero = :numero';
        $param = [':numero' => $numero];
        return self::query($sql, $param,true);
    }

    public static function countBySearchType($search = null,$debut=null,$fin=null,$etat=null){
        $count = 'SELECT COUNT(*) AS Total FROM '.self::getTable();
        $where = ' WHERE 1 = 1';
        $tab = [];
        if(isset($search)){
            $tSearch = ' AND ( nom LIKE :search )';
            $tab[':search'] = '%'.$search.'%';
        }else{
            $tSearch = '';
        }
        if(isset($debut)){
            $tDebut = ' AND DATE(created_at) >= :debut';
            $tab[':debut'] = $debut;
        }else{
            $tDebut = '';
        }
        if(isset($fin)){
            $tFin = ' AND DATE(created_at) <= :fin';
            $tab[':fin'] = $fin;
        }else{
            $tFin = '';
        }
        if(isset($etat)){
            $tEtat = ' AND etat = :etat';
            $tab[':etat'] = $etat;
        }else{
            $tEtat = '';
        }

        return self::query($count.$where.$tSearch.$tDebut.$tFin.$tEtat,$tab,true);
    }

    public static function searchType($nbreParPage=null,$pageCourante=null,$search = null,$debut=null,$fin=null,$etat=null){
        $limit = ' ORDER BY nom ASC,created_at DESC';
        $limit .= (isset($nbreParPage)&&isset($pageCourante))?' LIMIT '.(($pageCourante-1)*$nbreParPage).','.$nbreParPage:'';
        $where = ' WHERE 1 = 1';
        $tab = [];
        if(isset($search)){
            $tSearch = ' AND (nom LIKE :search )';
            $tab[':search'] = '%'.$search.'%';
        }else{
            $tSearch = '';
        }
        if(isset($debut)){
            $tDebut = ' AND DATE(created_at) >= :debut';
            $tab[':debut'] = $debut;
        }else{
            $tDebut = '';
        }
        if(isset($fin)){
            $tFin = ' AND DATE(created_at) <= :fin';
            $tab[':fin'] = $fin;
        }else{
            $tFin = '';
        }
        if(isset($etat)){
            $tEtat = ' AND etat = :etat';
            $tab[':etat'] = $etat;
        }else{
            $tEtat = '';
        }
        return self::query(self::selectString().$where.$tSearch.$tDebut.$tFin.$tEtat.$limit,$tab);
    }

    

}