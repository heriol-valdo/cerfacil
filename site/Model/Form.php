<?php

namespace Model;
use DbAuth;



class Form {
   
   
   public static function sendData(  $name, $email, $phone, $company, $message, $selectedDate, $selectedTime) {

   
   

    try {
       

        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'company' => $company,
            'message' => $message,
            'selectedDate' => $selectedDate,
            'selectedTime' => $selectedTime
           

        ];

        $dbauth =  new DbAuth();
        $result =  $dbauth::sendRequest($data,'contactCerFacil','post');

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

public static function sendNewLetter($email) {

    try {
       
        $data = [
            'email' => $email
        ];

        $dbauth =  new DbAuth();
        $result =  $dbauth::sendRequest($data,'newletterCerFacil','post');

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