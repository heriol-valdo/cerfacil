<?php

namespace Projet\Database;
use Projet\Model\Guzzle;

class Produit extends Guzzle{

    
    public static function find($id) {
        $data = ['id' => $id];
       
        $result = self::sendRequest($data, 'produitCerfaFind', 'post');
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
       
        $result =  self::sendRequest([],'produitCerfa','post');
        $result = json_decode($result);
        $response = [
         'valid' => false,
         'data' => null,
         'error' => null
        ];
       
        if (property_exists($result, 'valid')) {
            $response['valid'] = true;
            $response['data'] = $result->valid;
            
        } elseif (property_exists($result, 'erreur')) {   
            $response['error'] = $result->erreur;
        } 
        

        return $response;

    }

   

}