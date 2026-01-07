<?php

namespace Model;
use DbAuth;



class Form {
   
   
   public static function sendData(
    $nomM,$prenomM,$naissanceM,$securiteM,$emailM,$emploiM,$diplomeM,$niveauM, 
    $nomM1,$prenomM1,$naissanceM1,$securiteM1,$emailM1,$emploiM1,$diplomeM1,$niveauM1, 
    $travailC,$derogationC,$numeroC,$conclusionC,$debutC,$finC,$avenantC,$executionC,$dureC,$typeC,
    $rdC,$raC,$rpC,$rsC,
    $rdC1,$raC1,$rpC1,$rsC1,
    $rdC2,$raC2,$rpC2,$rsC2,
    $salaireC,$caisseC,$logementC,$avantageC,$autreC,
    $lieuO,$priveO,$attesteO,$modeC,$dureCM
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
        'niveauM1' => $niveauM1,
        'travailC' => $travailC,
        'derogationC' => $derogationC,
        'numeroC' => $numeroC,
        'conclusionC' => $conclusionC,
        'debutC' => $debutC,
        'finC' => $finC,
        'avenantC' => $avenantC,
        'executionC' => $executionC,
        'dureC' => $dureC,
        'typeC' => $typeC,
        'rdC' => $rdC,
        'raC' => $raC,
        'rpC' => $rpC,
        'rsC' => $rsC,
        'rdC1' => $rdC1,
        'raC1' => $raC1,
        'rpC1' => $rpC1,
        'rsC1' => $rsC1,
        'rdC2' => $rdC2,
        'raC2' => $raC2,
        'rpC2' => $rpC2,
        'rsC2' => $rsC2,
        'salaireC' => $salaireC,
        'caisseC' => $caisseC,
        'logementC' => $logementC,
        'avantageC' => $avantageC,
        'autreC' => $autreC, 
        'lieuO' => $lieuO,
        'priveO' => $priveO,
        'attesteO' => $attesteO,
        'modeC' => $modeC,
        'dureCM' => $dureCM
      
        ];

        $dbauth =  new DbAuth();
        $result =  $dbauth::sendRequest($data,'updateCerfaContrat','post');

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