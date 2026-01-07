<?php


namespace Projet\Database;



use Projet\Model\Guzzle;

class Profil extends Guzzle{

  

    public static function save($nom,$prenom,$email,$adresse,$postal,$ville,$telephone,$password,$id){
       
        $data = [
            'email'=> $email,
            'password' => $password,
            'firstname' => $nom,
            'lastname' => $prenom,
            'adressePostale'=> $adresse,
            'codePostal'=>$postal,
            'ville'=>$ville,
            'telephone'=>$telephone,
            'idCreation'=>$id,
            'roleCreation'=>2
           
        ];
        $result =self::sendRequest($data,'addUser/clientCerfa','POST');

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


    public static function edit($nom,$prenom,$email,$adresse,$postal,$ville,$telephone,$id){
       
        $data = [
            'newEmail' => $email,
            'newFirstname' => $nom,
            'newLastname' => $prenom,
            'newAdressePostale' => $adresse,
            'newCodePostal' => $postal,
            'newVille' => $ville,
            'newTelephone' => $telephone,
           
        ];
        $url = "admin/clientCerfa/".$id."/update" ;
        $result =self::sendRequest($data,$url,'PUT');

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


    public static function delete($id){
        $data = ['id' => $id];
        $url = "user/". $id ."/delete";
        $result =  self::sendRequest($data,$url,'delete');

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

    public static function setPassword($oldPassword,$newPassword){
        $data = [
            'oldPassword' => $oldPassword,
            'newPassword' => $newPassword
        ];
        $response = [
            'valid' => false,
            'data' => null,
            'error' => null
        ];
    
        $result = self::sendRequest($data,'password/update','put');
        
        $result = json_decode($result);
    
        if (property_exists($result, 'erreur')) {
            $response['error'] = $result->erreur;
            
        }
        if (property_exists($result, 'valid')) {
            $response['valid'] = true;
            $response['data'] = $result->valid;
            
        }

        return $response;
    }


    public static function resetPasswordSend($email){
        $data = [
            'email' => $email
        ];
        $response = [
            'valid' => false,
            'data' => null,
            'error' => null
        ];
    
        $result = self::sendRequest($data,'password/reset/request','post');
        
        $result = json_decode($result);
    
        if (property_exists($result, 'erreur')) {
            $response['error'] = $result->erreur;
            
        }
        if (property_exists($result, 'valid')) {
            $response['valid'] = true;
            $response['data'] = $result->valid;
            
        }

        return $response;
    }
   

    public static function resetPassword($newPassword,$confirmPassword,$token){
        $data = [
            'reset_token' => $token,
            'newPassword' => $newPassword,
            'confirmPassword' => $confirmPassword
        ];
        $response = [
            'valid' => false,
            'data' => null,
            'error' => null
        ];
    
        $result = self::sendRequest($data,'password/reset/check','put');
        
        $result = json_decode($result);
    
        if (property_exists($result, 'erreur')) {
            $response['error'] = $result->erreur;
            
        }
        if (property_exists($result, 'valid')) {
            $response['valid'] = true;
            $response['data'] = $result->valid;
            
        }

        return $response;
    }
    public static function countBySearchType($search=null){
        $data = [
            'search'=> $search
        ]; 
        $result =  self::sendRequest( $data,'clientCerfaCountBySearchType','get');
        $result = json_decode($result);
        $response = [
         'valid' => false,
         'data' => null,
         'error' => null
        ];
        if (!empty($result) && is_object($result)) {
             if (property_exists($result, 'valid')) {
                 if (property_exists($result, 'data')) {
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
                return $response['data']->total;
               
            }

            // Retourner 0 en cas d'erreur
            return   $response;
    }

    public static function searchType($nbreParPage=null,$pageCourante=null,$search = null){
        $data = [
            'search'=> $search,
            'pageCourante' => $pageCourante,
            'nbreParPage' => $nbreParPage
        ]; 
        $result =  self::sendRequest($data,'clientCerfaSearchType','get');
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