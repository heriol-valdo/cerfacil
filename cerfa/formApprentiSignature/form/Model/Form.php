<?php

namespace Model;
use DbAuth;

class Form {
   
   
   public static function sendData(
    $nomA, $nomuA, $prenomA, $sexeA, $naissanceA, $departementA, $communeNA, $nationaliteA, $regimeA, 
    $situationA, $titrePA, $derniereCA, $securiteA, $intituleA, $titreOA, $declareSA, $declareHA, $declareRA,
    $rueA, $voieA, $complementA, $postalA, $communeA, $numeroA, $nomR, $emailR, $rueR, $voieR, 
    $complementR, $postalR, $communeR,$prenomR
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
            'nomA' => $nomA,
            'nomuA' => $nomuA,
            'prenomA' => $prenomA,
            'sexeA' => $sexeA,
            'naissanceA' => $naissanceA,
            'departementA' => $departementA,
            'communeNA' => $communeNA,
            'nationaliteA' => $nationaliteA,
            'regimeA' => $regimeA,
            'situationA' => $situationA,
            'titrePA' => $titrePA,
            'derniereCA' => $derniereCA,
            'securiteA' => $securiteA,
            'intituleA' => $intituleA,
            'titreOA' => $titreOA,
            'declareSA' => $declareSA,
            'declareHA' => $declareHA,
            'declareRA' => $declareRA,
            'rueA' => $rueA,
            'voieA' => $voieA,
            'complementA' => $complementA,
            'postalA' => $postalA,
            'communeA' => $communeA,
            'numeroA' => $numeroA,

            'nomR' => $nomR,
            'emailR' => $emailR,
            'rueR' => $rueR,
            'voieR' => $voieR,
            'complementR' => $complementR,
            'postalR' => $postalR,
            'communeR' => $communeR,
            'prenomR' => $prenomR,

            'nomM' => null,
            'prenomM' => null,
            'naissanceM' => null,
            'securiteM' => null,
            'emailM' => null,
            'emploiM' => null,
            'diplomeM' => null,
            'niveauM' => null,
            'nomM1' => null,
            'prenomM1' => null,
            'naissanceM1' => null,
            'securiteM1' => null,
            'emailM1' => null,
            'emploiM1' => null,
            'diplomeM1' => null,
            'niveauM1' => null
        ];
        $dbauth =  new DbAuth();
        $result =  $dbauth::sendRequest($data,'updateCerfaByFormInformation','post');

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