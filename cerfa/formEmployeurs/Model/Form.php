<?php

namespace Model;
use DbAuth;



class Form {
   
   
   public static function sendData(
    $typeE, $specifiqueE, $totalE, $siretE, $codeaE, $codeiE, $rueE, $voieE, $complementE, $postalE, $communeE,  $numeroE
) {

   
    $id = $_COOKIE['info'];

    // Vérifie si l'id est vide ou non
    if (empty($id)) {
        return [
            'valid' => true,
            'error' => 'L\'id est vide',
            'data'=>null
        ];
    }


    try {
       

        $data = [
        'id' => $id,
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
        'numeroE' => $numeroE
        ];

        $dbauth =  new DbAuth();
        $result =  $dbauth::sendRequest($data,'updateEntreprises','post');

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
    } catch (PDOException $e) {
        // Gérer l'exception ici, par exemple, en enregistrant le message d'erreur.
        $errorMessage = $e->getMessage(); // Le message d'erreur est à l'index 2 du tableau

        // Vous pouvez faire un retour spécifique en cas d'erreur si nécessaire.
        return [
            'valid' => true,
            'error' => $errorMessage,
        ];
    }
}


    


    public static function isLogin() {
      
        if (isset($_SESSION['emailUser'])) {
            return true;
        }
    
        return false;
    }
}
?>