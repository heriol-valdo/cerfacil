<?php

namespace Projet\Database;
use Projet\Model\Guzzle;

class Opco extends Guzzle{

    public static function save($nom,$cle= null,$lienE= null,$lienCe= null, $lienCo= null, $lienF= null,$lienT= null,$clid= null,$clse= null,$id = null){
       
        $data = [
            'nom'=> $nom,
            'cle' => $cle,
            'lienE' => $lienE,
            'lienCe' => $lienCe,
            'lienCo'=>$lienCo,
            'lienF' => $lienF,
            'lienT' => $lienT,
            'clid' => $clid,
            'clse' => $clse,
            'id' => $id
        ];
        $result =  self::sendRequest($data,'addupdateOpco','post');

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
        try {
        $result = self::sendRequest($data, 'opcoFind', 'post');
        $result = json_decode($result);
        $response = [
            'valid' => false,
            'data' => null,
            'error' => null
        ];
        if (!empty($result) && is_object($result)) {
            if (property_exists($result, 'valid')) {
                if (property_exists($result, 'data') && is_object($result->data)) {
                    $response['valid'] = true;
                    $response['data'] = $result->data;
                } else {
                    $response['error'] = "La propriété 'data' est manquante ou invalide.";
                }
            } elseif (property_exists($result, 'erreur')) {
                $response['error'] = $result->erreur;
            } else {
                $response['error'] = "La réponse ne contient ni 'valid' ni 'erreur'.";
            }
        } else {
            $response['error'] = "La réponse est vide ou n'est pas un objet JSON valide.";
        }
    
        return $response;

    } catch (\GuzzleHttp\Exception\ClientException $e) {
        // Afficher les détails de l'erreur
        $responseBody = $e->getResponse()->getBody(true);
        //echo 'Request Error: ' . $e->getMessage() . ' Response Body: ' . $responseBody;
        return [
            'valid' => false,
            'error' => $responseBody
        ];
      }
    }
    

    public static function byNom($nom){
        $result =  self::sendRequest(['nom'=>$nom],'opcoByNom','post');
        $result = json_decode($result);
        $response = [
         'valid' => false,
         'data' => null,
         'error' => null
        ];
        if (!empty($result) && is_object($result)) {
             if (property_exists($result, 'valid')) {
                 if (property_exists($result, 'data') && is_array($result->data)) {
                     $response['valid'] = true;
                     $response['data'] = $result->data;
                 } else {
                     $response['error'] = "La propriété 'data' est manquante ou invalide.";
                 }
             } elseif (property_exists($result, 'erreur')) {   
                 $response['error'] = $result->erreur;
             } else {
                 $response['error'] = "La réponse ne contient ni 'valid' ni 'erreur'.";
             }
         } else {
                 $response['error'] = "La réponse est vide ou n'est pas un objet JSON valide.";
         }
 
         return $response;
    }


    public static function delete($id){
        $data = ['id' => $id];
        $result =  self::sendRequest($data,'deleteOpco','delete');

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
    public static function countBySearchType($search){
        $data = [
            'search'=> $search
        ]; 
        $result =  self::sendRequest( $data,'opcoSearchType','post');
        $result = json_decode($result);
        $response = [
         'valid' => false,
         'data' => null,
         'error' => null
        ];
        if (!empty($result) && is_object($result)) {
             if (property_exists($result, 'valid')) {
                 if (property_exists($result, 'data') && is_array($result->data)) {
                     $response['valid'] = true;
                     $response['data'] = $result->data;
                 } else {
                     $response['error'] = "La propriété 'data' est manquante ou invalide.";
                 }
             } elseif (property_exists($result, 'erreur')) {   
                 $response['error'] = $result->erreur;
             } else {
                 $response['error'] = "La réponse ne contient ni 'valid' ni 'erreur'.";
             }
         } else {
                 $response['error'] = "La réponse est vide ou n'est pas un objet JSON valide.";
         }
 
          // Extraire le nombre de résultats si la réponse est valide
            if ($response['valid'] && isset($response['data'])) {
                return count($response['data']);
               
            }

            // Retourner 0 en cas d'erreur
            return 0;
    }

    public static function searchType($nbreParPage=null,$pageCourante=null,$search = null){
        $data = [
            'search'=> $search,
            'pageCourante' => $pageCourante,
            'nbreParPage' => $nbreParPage
        ]; 
        $result =  self::sendRequest($data,'opcoSearchType','post');
        $result = json_decode($result);
        $response = [
         'valid' => false,
         'data' => null,
         'error' => null
        ];
        if (!empty($result) && is_object($result)) {
             if (property_exists($result, 'valid')) {
                 if (property_exists($result, 'data') && is_array($result->data)) {
                     $response['valid'] = true;
                     $response['data'] = $result->data;
                 } else {
                     $response['error'] = "La propriété 'data' est manquante ou invalide.";
                 }
             } elseif (property_exists($result, 'erreur')) {   
                 $response['error'] = $result->erreur;
             } else {
                 $response['error'] = "La réponse ne contient ni 'valid' ni 'erreur'.";
             }
         } else {
                 $response['error'] = "La réponse est vide ou n'est pas un objet JSON valide.";
         }

         if ($response['valid']) {
            return $response['data'];
         }
 
         return [];

    }



 


   

}