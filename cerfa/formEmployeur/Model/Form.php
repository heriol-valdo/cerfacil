<?php

namespace Model;
use DbAuth;



class Form {
   
   
   public static function sendData(
    $nomA, $nomuA, $prenomA, $sexeA, $naissanceA, $departementA, $communeNA, $nationaliteA, $regimeA, 
    $situationA, $titrePA, $derniereCA, $securiteA, $intituleA, $titreOA, $declareSA, $declareHA, $declareRA,
    $rueA, $voieA, $complementA, $postalA, $communeA, $numeroA, $nomR,$prenomR, $emailR, $rueR, $voieR, 
    $complementR, $postalR, $communeR,
    $nomM,  $prenomM,$naissanceM ,$securiteM, $emailM, $emploiM, $diplomeM, $niveauM,
    $nomM1,  $prenomM1,$naissanceM1 ,$securiteM1, $emailM1, $emploiM1, $diplomeM1, $niveauM1
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
            'prenomR' => $prenomR,
            'emailR' => $emailR,
            'rueR' => $rueR,
            'voieR' => $voieR,
            'complementR' => $complementR,
            'postalR' => $postalR,
            'communeR' => $communeR,

            'nomM' => $nomM,
            'prenomM' => $prenomM,
            'naissanceM' => $naissanceM,
            'securiteM' => $securiteM,
            'emailM' => $emailM,
            'emploiM' => $emploiM,
            'diplomeM' => $diplomeM,
            'niveauM' => $niveauM,
            'nomM1' => $nomM1,
            'prenomM1' => $prenomM1,
            'naissanceM1' => $naissanceM1,
            'securiteM1' => $securiteM1,
            'emailM1' => $emailM1,
            'emploiM1' => $emploiM1,
            'diplomeM1' => $diplomeM1,
            'niveauM1' => $niveauM1

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