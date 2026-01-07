<?php

namespace Model;
use DbAuth;

class Form {
   
   
   public static function formSignature( $path) {

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
            'path' => $path,
            'prov'=>5
        ];
        $dbauth =  new DbAuth();
        $result =  $dbauth::sendRequest($data,'setPathSignature','post');
        $result = json_decode($result);

        $response = [
            'valid' => true,
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


public static function getCerfa($id) {

    if (empty($id)) {
        return [
            'valid' => true,
            'error' => 'L\'id est vide sur getCerfa',
            'data'=>null
        ];
    }


    try {
        $data = [
            'id' => $id
        ];
        $dbauth =  new DbAuth();
        $result =  $dbauth::sendRequest($data,'cerfaFind','post');
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
            $response['data'] =  $result->data;
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

public static function getEmployeur($id) {
    if (empty($id)) {
        return [
            'valid' => true,
            'error' => 'L\'id est vide sur getEmployeur',
            'data'=>null
        ];
    }


    try {
        $data = [
            'id' => $id
        ];
        $dbauth =  new DbAuth();
        $result =  $dbauth::sendRequest($data,'entrepriseFind','post');
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
            $response['data'] =  $result->data;
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

public static function getFormation($id) {
    if (empty($id)) {
        return [
            'valid' => true,
            'error' => 'L\'id est vide sur getFormation',
            'data'=>null
        ];
    }


    try {
        $data = [
            'id' => $id
        ];
        $dbauth =  new DbAuth();
        $result =  $dbauth::sendRequest($data,'formationFind','post');
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
            $response['data'] =  $result->data;
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