<?php

namespace Projet\Database;


use Projet\Model\Guzzle;

class Formation extends Guzzle{


    public static function save(
      $nomF,$diplomeF= null,$intituleF= null,$numeroF= null,$siretF= null,$codeF= null,$rnF= null,$entrepriseF= null,$responsableF= null,$prix=null,$rueF= null,$voieF= null,$complementF= null,$postalF= null,$communeF= null,
      $emailF=null,$debutO= null,$prevuO= null,$dureO= null,$nomO= null,$numeroO= null,$siretO= null,$rueO= null,$voieO= null,$complementO= null,$postalO= null,$communeO= null,$logo = null,
      $id = null){


        $data = [ 
        'id' => $id,
        'nomF' => $nomF,
        'diplomeF' => $diplomeF,
        'intituleF' => $intituleF, 
        'numeroF' => $numeroF,
        'siretF' => $siretF,
        'codeF' => $codeF,
        'rnF' => $rnF,
        'entrepriseF' => $entrepriseF,
        'responsableF' => $responsableF,
        'prix' => $prix,
        'rueF' => $rueF,
        'voieF' => $voieF,
        'complementF' => $complementF,
        'postalF' => $postalF,
        'communeF' => $communeF,
        'emailF' => $emailF,
        'debutO' => $debutO,
        'prevuO' => $prevuO,
        'dureO' => $dureO,
        'nomO' => $nomO,
        'numeroO' => $numeroO,
        'siretO' => $siretO,
        'rueO' => $rueO,
        'voieO' => $voieO,
        'complementO' => $complementO,
        'postalO' => $postalO,
        'communeO' => $communeO,
        'logo' =>$logo ];

        $result =  self::sendRequest($data,'addupdateFormation','post');

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

   

    public static function find($id){
        $data = ['id' => $id];
        $result = self::sendRequest($data, 'formationFind', 'post');
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
    }

    public static function byNom($nom){
        $data = ['nomF' => $nom];
        try {
            $result = self::sendRequest($data, 'formationByNom', 'post');
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

    public static function delete($id){
        $data = ['id' => $id];
        $result =  self::sendRequest($data,'deleteFormation','delete');

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

    public static function countBySearchType($search = null){
        $data = [
            'search'=> $search
        ]; 
        try {
        $result =  self::sendRequest($data,'formationCountBySearchType','post');
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
 
            if ($response['valid'] && isset($response['data'])) {
               // var_die($response['data']);
                return $response['data']->total;
               
            }

            //var_die($response);
            return 0;

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Afficher les détails de l'erreur
            $responseBody = $e->getResponse()->getBody(true);
            echo 'Request Error: ' . $e->getMessage() . ' Response Body: ' . $responseBody;
            return [
                'valid' => false,
                'error' => $responseBody
            ];
        }
    }

    public static function searchType($nbreParPage=null,$pageCourante=null,$search = null){
        $data = [
            'search'=> $search,
            'pageCourante' => $pageCourante,
            'nbreParPage' => $nbreParPage
        ]; 
        $result =  self::sendRequest($data,'formationSearchType','post');
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