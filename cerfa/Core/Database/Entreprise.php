<?php

namespace Projet\Database;


use Projet\Model\Guzzle;

class Entreprise extends Guzzle{

    public static function save($nomE,$typeE=null, $specifiqueE=null, $totalE=null, $siretE=null,   $codeaE=null,
    $codeiE=null, $rueE=null, $voieE=null, $complementE=null, $postalE=null, $communeE=null, $emailE=null, $numeroE=null,$idopco=null,  
    $id = null){
        $data = [
        'id' => $id,
        'nomE' => $nomE,
        'typeE' => $typeE,
        'specifiqueE' => $specifiqueE,
        'totalE' => $totalE,
        'siretE' => $siretE,
        'codeaE' => $codeaE,
        'codeiE' => $codeiE,
        'rueE' => $rueE,
        'voieE' => $voieE,
        'complementE' => $complementE,
        'postalE' => $postalE,
        'communeE' => $communeE,
        'emailE' => $emailE,
        'numeroE' => $numeroE,
        'idopco' => $idopco ];

        $result =  self::sendRequest($data,'addupdateEntreprises','post');

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
        $result = self::sendRequest($data, 'entrepriseFind', 'post');
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
    public static function sendEmail($id){
        $data = ['id' => $id];
        $result = self::sendRequest($data, 'sendEmailFormDataEmployeur', 'post');
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

    public static function findbyopco($idopco){
        $data = ['idopco' => $idopco];
        try {
            $result = self::sendRequest($data, 'entrepriseFindByIdOpco', 'post');
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
        $data = ['nomE' => $nom];
        try {
            $result = self::sendRequest($data, 'entrepriseByNom', 'post');
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
        $result =  self::sendRequest($data,'deleteEntreprise','delete');

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
        $data = ['search'=> $search]; 
        $result =  self::sendRequest($data,'entrepriseCountBySearchType','post');
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
              //  var_die($response['data']);
                return $response['data']->total;
               
            }

            // Retourner 0 en cas d'erreur
            return $response['error'];
    }

    public static function searchType($nbreParPage=null,$pageCourante=null,$search = null){
        $data = [
            'search'=> $search,
            'pageCourante' => $pageCourante,
            'nbreParPage' => $nbreParPage
        ]; 
        $result =  self::sendRequest($data,'entrepriseSearchType','post');
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