<?php

namespace Projet\Database;
use Projet\Model\Guzzle;

class Abonnement extends Guzzle{

    
    public static function save($date_debut,$date_fin, $quantite,$id_produit,$totalDossier,$totalFacture,$idstripe,$totalAbonement=null,$quantitesrecharge =null,$type=null,$id = null){
       
        $data = [
            'date_debut'=> $date_debut,
            'date_fin' => $date_fin,
            'quantite' => $quantite,
            'id_produit' => $id_produit,
            'id' => $id,
            'totalDossier' => $totalDossier,
            'totalFacture' => $totalFacture,
            'totalAbonement' => $totalAbonement,
            'quantitesrecharge' => $quantitesrecharge,
            'type' => $type,
            'stripe_id' => $idstripe

        ];
        $result =  self::sendRequest($data,'addupdateAbonnementCerfa','post');

        $result = json_decode($result);

        $response = [
            'valid' => false,
            'data' => null,
            'error' => null
        ];

        if (property_exists($result, 'erreur')) {
                $response['error'] =  $result->erreur;
        }
        if (property_exists($result, 'valid')) {
            $response['valid'] = true;
            $response['data'] = $result->valid;
        }

        return $response;
    }

    public static function find($id) {
        $data = ['id' => $id];
       
        $result = self::sendRequest($data, 'abonnementCerfaFind', 'post');
        $result = json_decode($result);
        $response = [
         'valid' => false,
         'data' => null,
         'error' => null
        ];
       
        if (property_exists($result, 'valid')) {
            $response['valid'] = true;
            $response['data'] = $result->data;
            
        } elseif (property_exists($result, 'erreur')) {   
            $response['error'] = $result->erreur;
        } 
        

        return $response;
        
    }
    
    public static function updateAbonnementById($id) {
        $data = ['id' => $id];
       
        $result = self::sendRequest($data, 'updateAbonnementById', 'post');
        $result = json_decode($result);
        $response = [
         'valid' => false,
         'data' => null,
         'error' => null
        ];
       
        if (property_exists($result, 'valid')) {
            $response['valid'] = true;
            $response['data'] = $result->data;
            
        } elseif (property_exists($result, 'erreur')) {   
            $response['error'] = $result->erreur;
        } 
        

        return $response;
        
    }
   

    public static function searchType(){
       
        $result =  self::sendRequest([],'abonnementCerfaSearchType','post');
        $result = json_decode($result);
        $response = [
         'valid' => false,
         'data' => null,
         'error' => null
        ];
       
        if (property_exists($result, 'valid')) {
            $response['valid'] = true;
            $response['data'] = $result->data;
            
        } elseif (property_exists($result, 'erreur')) {   
            $response['error'] = $result->erreur;
        } 
        

        return $response;

    }



 


   

}