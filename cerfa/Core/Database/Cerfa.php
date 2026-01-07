<?php

namespace Projet\Database;


use Projet\Model\Guzzle;

class Cerfa extends Guzzle{

    public static function save($idemployeur,$idformation = null,
      $nomA = null, $nomuA = null, $prenomA = null, $sexeA = null,
      $naissanceA = null, $departementA = null, $communeNA = null, 
      $nationaliteA = null, $regimeA = null, $situationA = null, $titrePA = null,
      $derniereCA = null, $securiteA = null, $intituleA = null, $titreOA = null, 
      $declareSA = null, $declareHA = null, $declareRA = null, $rueA = null, 
      $voieA = null, $complementA = null, $postalA = null, $communeA = null, 
      $numeroA = null, $emailA = null,
      
      $nomR = null,$emailR = null, $rueR = null, $voieR = null, $complementR = null, $postalR = null, $communeR = null, 
      $nomM = null,$prenomM = null,$naissanceM = null,$securiteM = null,$emailM = null,$emploiM = null,$diplomeM = null,$niveauM = null, 
      $nomM1 = null,$prenomM1 = null,$naissanceM1 = null,$securiteM1 = null,$emailM1= null,$emploiM1= null,$diplomeM1= null,$niveauM1 = null, 
      $travailC= null,$derogationC= null,$numeroC= null,$conclusionC= null,$debutC= null,$finC= null,$avenantC= null,$executionC= null,$dureC= null,$typeC= null,
      $rdC= null,$raC= null,$rpC= null,$rsC= null,$rdC1= null,$raC1= null,$rpC1= null,$rsC1= null,
      $rdC2= null,$raC2= null,$rpC2= null,$rsC2= null,
      $salaireC= null,$caisseC= null,$logementC= null,$avantageC= null,$autreC= null,
      $lieuO= null,$priveO= null,$attesteO= null,$modeC= null,$prenomR= null,$dureCM= null,
      $id = null){

        $data = [
        'id' => $id,
        'idemployeur' => $idemployeur,
        'idformation' => $idformation,
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
        'emailA' => $emailA, 
        'nomR' => $nomR, 
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
        'prenomR' => $prenomR,
        'dureCM' => $dureCM ];

        
        $result =  self::sendRequest($data,'addupdateCefa','post');

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


    public static function updateCerfaContrat(
    $nomM = null,$prenomM = null,$naissanceM = null,$securiteM = null,$emailM = null,$emploiM = null,$diplomeM = null,$niveauM = null, 
    $nomM1 = null,$prenomM1 = null,$naissanceM1 = null,$securiteM1 = null,$emailM1= null,$emploiM1= null,$diplomeM1= null,$niveauM1 = null, 
    $travailC= null,$derogationC= null,$numeroC= null,$conclusionC= null,$debutC= null,$finC= null,$avenantC= null,$executionC= null,$dureC= null,$typeC= null,
    $rdC= null,$raC= null,$rpC= null,$rsC= null,$rdC1= null,$raC1= null,$rpC1= null,$rsC1= null,
    $rdC2= null,$raC2= null,$rpC2= null,$rsC2= null,
    $salaireC= null,$caisseC= null,$logementC= null,$avantageC= null,$autreC= null,
    $lieuO= null,$priveO= null,$attesteO= null,$modeC= null,$dureCM= null,
    $id = null){

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
      'dureCM' => $dureCM ];

      
      $result =  self::sendRequest($data,'updateCerfaContrat','post');

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
    public static function updateCerfaEtudiant(  $nomA = null, $nomuA = null, $prenomA = null, $sexeA = null,
    $naissanceA = null, $departementA = null, $communeNA = null, 
    $nationaliteA = null, $regimeA = null, $situationA = null, $titrePA = null,
    $derniereCA = null, $securiteA = null, $intituleA = null, $titreOA = null, 
    $declareSA = null, $declareHA = null, $declareRA = null, $rueA = null, 
    $voieA = null, $complementA = null, $postalA = null, $communeA = null, 
    $numeroA = null, $emailA = null,
    
    $nomR = null,$prenomR=null,$emailR = null, $rueR = null, $voieR = null, $complementR = null, $postalR = null, $communeR = null,$id=null ){

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
            'emailA' => $emailA, 
            'nomR' => $nomR, 
            'prenomR' => $prenomR, 
            'emailR' => $emailR, 
            'rueR' => $rueR,
            'voieR' => $voieR,
            'complementR' => $complementR,
            'postalR' => $postalR,
            'communeR' => $communeR
        ];

        $result =  self::sendRequest($data,'updateCerfaEtudiant','post');

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
    

    public static function findFactureByIdCerfa($id){
        $data = ['id' => $id];
        $result = self::sendRequest($data, 'findFactureByIdCerfa', 'post');
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

    public static function setFactureEcheance( 
         $numeroOF, $lieuF, $ibanF, $repreF, $emploiRF, $motif, $motif1, $motif2, $motif3, $motif4, $motif5, $montant,
        $montant1, $montant2, $montant3, $montant4, $montant5, $echeance1, $echeance2, $echeance3, $echeance4, $date1, $date2,
        $date3, $date4, $ht1, $ht2, $ht3, $ht4, $idcerfa){
            $data = [
                'numeroOF' => $numeroOF,
                'lieuF' => $lieuF,
                'ibanF' => $ibanF,
                'repreF' => $repreF,
                'emploiRF' => $emploiRF,
                'motif' => $motif,
                'motif1' => $motif1,
                'motif2' => $motif2,
                'motif3' => $motif3,
                'motif4' => $motif4,
                'motif5' => $motif5,
                'montant' => $montant,
                'montant1' => $montant1,
                'montant2' => $montant2,
                'montant3' => $montant3,
                'montant4' => $montant4,
                'montant5' => $montant5,
                'echeance1' => $echeance1,
                'echeance2' => $echeance2,
                'echeance3' => $echeance3,
                'echeance4' => $echeance4,
                'date1' => $date1,
                'date2' => $date2,
                'date3' => $date3,
                'date4' => $date4,
                'ht1' => $ht1,
                'ht2' => $ht2,
                'ht3' => $ht3,
                'ht4' => $ht4,
                'idcerfa' => $idcerfa
            ];
        $result = self::sendRequest($data, 'factureEcheance', 'post');
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

    public static function setPathSignature($path,$id){
        $data = [
            'id' => $id,
            'path' => $path,
            'prov'=>3
        ];
        $result = self::sendRequest($data, 'setPathSignature', 'post');
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

    public static function setPathSignatureManuelleConvention($path,$id){
        $data = [
            'id' => $id,
            'path' => $path,
            'prov'=>5
        ];
        $result = self::sendRequest($data, 'setPathSignature', 'post');
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
    public static function setCerfaOpco($cerfaOpco,$id){
        $data = [
            'id' => $id,
            'cerfaOpco'=> $cerfaOpco
        ];
        $result = self::sendRequest($data, 'cerfaSetCerfaOpco', 'post');
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

    public static function setConventionOpco($conventionOpco,$id){
        $data = [
            'id' => $id,
            'conventionOpco'=> $conventionOpco
        ];
        $result = self::sendRequest($data, 'cerfaSetConventionOpco', 'post');
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

    public static function setFactureOpco($factureOpco,$id){
        $data = [
            'id' => $id,
            'factureOpco'=> $factureOpco
        ];
        $result = self::sendRequest($data, 'cerfaSetFactureOpco', 'post');
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
        //var_dump($result);
        return $response;
    }




    public static function setNumeroInterne($numeroInterne,$id){
        $data = [
            'id' => $id,
            'numeroInterne'=> $numeroInterne
        ];
        $result = self::sendRequest($data, 'cerfaSetNumeroInterne', 'post');
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


    public static function setNumeroExterne($numeroExterne,$id){
        $data = [
            'id' => $id,
            'numeroExterne'=> $numeroExterne
        ];
        $result = self::sendRequest($data, 'cerfaSetNumeroExterne', 'post');
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

    public static function setEntreprise($id,$idEntreprise){
        $data = [
            'id' => $id,
            'idEntreprise'=> $idEntreprise
        ];
        $result = self::sendRequest($data, 'cerfaSetEntreprise', 'post');
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

    public static function setFormation($id,$idFormation){
        $data = [
            'id' => $id,
            'idFormation'=> $idFormation
        ];
        $result = self::sendRequest($data, 'cerfaSetFormation', 'post');
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

    public static function setNumeroDeca($numeroDeca,$id){
        $data = [
            'id' => $id,
            'numeroDeca'=> $numeroDeca
        ];
        $result = self::sendRequest($data, 'cerfaSetNumeroDeca', 'post');
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

  

    public static function sendEmailSignatureEmployeur($email,$id){
        $data = [
        'id' => $id,
        'email' => $email
       ];
        $result =  self::sendRequest($data,'sendEmailFormSignatureEmployeur','post');
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

    public static function sendEmailSignatureConventionEmployeur($email,$id){
        $data = [
        'id' => $id,
        'email' => $email
       ];
        $result =  self::sendRequest($data,'sendEmailFormSignatureConventionEmployeur','post');
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
    public static function sendEmailSignatureApprenti($email,$id){
        $data = [
        'id' => $id,
        'email' => $email
       ];
        $result =  self::sendRequest($data,'sendEmailFormSignatureApprenti','post');
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

    public static function sendEmailSignatureApprentiRepresentant($email,$id){
        $data = [
        'id' => $id,
        'email' => $email
       ];
        $result =  self::sendRequest($data,'sendEmailFormSignatureApprentiRepresentant','post');
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
    public static function sendEmailSignatureEcole($email,$id){
        $data = [
        'id' => $id,
        'email' => $email
       ];
        $result =  self::sendRequest($data,'sendEmailFormSignatureEcole','post');
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

    public static function sendEmailSignatureConventionEcole($email,$id){
        $data = [
        'id' => $id,
        'email' => $email
       ];
        $result =  self::sendRequest($data,'sendEmailFormSignatureConventionEcole','post');
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
    public static function sendEmailEmployeur($email,$id){
        $data = [
        'id' => $id,
        'email' => $email
       ];
        $result =  self::sendRequest($data,'sendEmailFormEmployeur','post');
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

    //new 
    public static function sendEmailContratEmployeur($email,$id){
        $data = [
        'id' => $id,
        'email' => $email
       ];
        $result =  self::sendRequest($data,'sendEmailFormContratEmployeur','post');
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
    public static function sendEmailApprenti($email,$id){
        $data = [
        'id' => $id,
        'email' => $email
       ];
        $result =  self::sendRequest($data,'sendEmailFormApprenti','post');
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




    public static function findbyentreprise($idemployeur){
        $data = ['idemployeur' => $idemployeur];
        try {
        $result = self::sendRequest($data, 'cerfaByIdEmployeur', 'post');
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

    public static function findbyformation($idformation){
        $data = ['idformation' => $idformation];
        try {
        $result = self::sendRequest($data, 'cerfaByIdFormation', 'post');
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
            echo 'Request Error: ' . $e->getMessage() . ' Response Body: ' . $responseBody;
            return [
                'valid' => false,
                'error' => $responseBody
            ];
        }
    }


    public static function find($id){
        $data = ['id' => $id];
        $result = self::sendRequest($data, 'cerfaFind', 'post');
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

    public static function byEmail($email){
        $data = ['emailA' => $email];
        try {
            $result = self::sendRequest($data, 'cerfaByEmail', 'post');
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

    public static function byNom($nom){
        $data = ['nomA' => $nom];
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
        $result =  self::sendRequest($data,'deleteCerfa','delete');

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
        $result =  self::sendRequest($data,'cerfaCountBySearchType','post');
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
        $result =  self::sendRequest($data,'cerfaSearchType','post');
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



 
    public static function setnumeroInterneFacture($numeroInterneFacture,$selectSendEcheance,$id){
        $data = [
            'id' => $id,
            'selectSendEcheance' => $selectSendEcheance,
            'numeroInterneFacture'=> $numeroInterneFacture
        ];
        $result = self::sendRequest($data, 'cerfaSetNumeroInterneFacture', 'post');
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

    public static function setnumeroInterneDocument($numeroInterneDocument,$selectSendEcheance,$id){
        $data = [
            'id' => $id,
            'selectSendEcheance' => $selectSendEcheance,
            'numeroInterneDocument'=> $numeroInterneDocument
        ];
        $result = self::sendRequest($data, 'cerfaSetNumeroInterneDocument', 'post');
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

   

}