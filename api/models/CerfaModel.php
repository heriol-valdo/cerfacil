<?php
require_once 'db.php';


class Cerfa extends Database{

    public static $table = "cerfa";
    public $id;

    public $idusers;
    public $idemployeur;
    public $idformation;
    public $nomA;
    public $nomuA;
    public $prenomA;
    public $sexeA;
    public $naissanceA;
    public $departementA;
    public $communeNA;
    public $nationaliteA;
    public $regimeA;
    public $situationA;
    public $titrePA;
    public $derniereCA;
    public $securiteA;
    public $intituleA;
    public $titreOA;
    public $declareSA;
    public $declareHA;
    public $declareRA;
    public $rueA;
    public $voieA;
    public $complementA;
    public $postalA;
    public $communeA;
    public $numeroA;
    public $emailA; 
    public $nomR; 
    public $emailR; 
    public $rueR;
    public $voieR;
    public $complementR;
    public $postalR;
    public $communeR;
    public $nomM; 
    public $prenomM;
    public $naissanceM;  
    public $securiteM;
    public $emailM;
    public $emploiM;
    public $diplomeM;
    public $niveauM; 
    public $nomM1; 
    public $prenomM1;
    public $naissanceM1;  
    public $securiteM1;
    public $emailM1;
    public $emploiM1;
    public $diplomeM1;
    public $niveauM1;
    public $travailC;
    public $derogationC;
    public $numeroC;
    public $conclusionC;
    public $debutC;
    public $finC;
    public $avenantC;
    public $executionC;
    public $dureC;
    public $typeC;
    public $rdC;
    public $raC;
    public $rpC;
    public $rsC;
    public $rdC1;
    public $raC1;
    public $rpC1;
    public $rsC1;
    public $rdC2;
    public $raC2;
    public $rpC2;
    public $rsC2;
    public $salaireC;
    public $caisseC;
    public $logementC;
    public $avantageC;
    public $autreC; 
    public $lieuO;
    public $priveO;
    public $attesteO;
    public $modeC;
    public $prenomR;
    public $dureCM;
   

    public  function save($id_centre=null,$idusers=null,$idemployeur=null,$idformation = null,
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
     
      $sql = 'INSERT INTO ';
      $baseSql = self::$table.' SET id_centre =:id_centre,id_users =:id_users, idemployeur = :idemployeur,idformation = :idformation,
      nomA = :nomA,nomuA = :nomuA,prenomA = :prenomA,sexeA = :sexeA, naissanceA = :naissanceA,
       departementA = :departementA,communeNA = :communeNA,nationaliteA = :nationaliteA,
       regimeA = :regimeA,situationA = :situationA,titrePA = :titrePA,derniereCA = :derniereCA,
       securiteA = :securiteA,intituleA = :intituleA,titreOA = :titreOA,
       declareSA = :declareSA,declareHA = :declareHA,declareRA = :declareRA,rueA = :rueA,
       voieA = :voieA,complementA = :complementA,postalA = :postalA,communeA = :communeA,
       numeroA = :numeroA,emailA = :emailA,
       nomR = :nomR,emailR = :emailR,rueR = :rueR,voieR = :voieR,complementR = :complementR,postalR = :postalR,communeR = :communeR,
       nomM = :nomM,prenomM = :prenomM,naissanceM = :naissanceM, securiteM = :securiteM,emailM = :emailM,emploiM = :emploiM, diplomeM = :diplomeM,niveauM = :niveauM,
       nomM1 = :nomM1,prenomM1 = :prenomM1,naissanceM1 = :naissanceM1, securiteM1 = :securiteM1,emailM1 = :emailM1,emploiM1 = :emploiM1, diplomeM1 = :diplomeM1,niveauM1 = :niveauM1, 
       travailC = :travailC,derogationC = :derogationC,numeroC = :numeroC,conclusionC = :conclusionC,debutC = :debutC,finC = :finC,avenantC = :avenantC,executionC = :executionC,dureC = :dureC,
       typeC = :typeC,rdC = :rdC,raC = :raC,rpC = :rpC,rsC = :rsC,rdC1 = :rdC1,raC1 = :raC1,rpC1 = :rpC1,rsC1 = :rsC1,
       rdC2 = :rdC2,raC2 = :raC2,rpC2 = :rpC2,rsC2 = :rsC2,
       salaireC = :salaireC,caisseC = :caisseC,logementC = :logementC,
       avantageC = :avantageC,autreC = :autreC,lieuO = :lieuO,priveO = :priveO,attesteO = :attesteO,modeC = :modeC,prenomR = :prenomR,dureCM = :dureCM
       ';


      $baseParam = [
      ':id_centre' => $id_centre,
      ':id_users' => $idusers,
      ':idemployeur' => $idemployeur,
      ':idformation' => $idformation,
      ':nomA' => $nomA,
      ':nomuA' => $nomuA,
      ':prenomA' => $prenomA,
      ':sexeA' => $sexeA,
      ':naissanceA' => $naissanceA,
      ':departementA' => $departementA,
      ':communeNA' => $communeNA,
      ':nationaliteA' => $nationaliteA,
      ':regimeA' => $regimeA,
      ':situationA' => $situationA,
      ':titrePA' => $titrePA,
      ':derniereCA' => $derniereCA,
      ':securiteA' => $securiteA,
      ':intituleA' => $intituleA,
      ':titreOA' => $titreOA,
      ':declareSA' => $declareSA,
      ':declareHA' => $declareHA,
      ':declareRA' => $declareRA,
      ':rueA' => $rueA,
      ':voieA' => $voieA,
      ':complementA' => $complementA,
      ':postalA' => $postalA,
      ':communeA' => $communeA,
      ':numeroA' => $numeroA,
      ':emailA' => $emailA, 
      ':nomR' => $nomR, 
      ':emailR' => $emailR, 
      ':rueR' => $rueR,
      ':voieR' => $voieR,
      ':complementR' => $complementR,
      ':postalR' => $postalR,
      ':communeR' => $communeR,
      ':nomM' => $nomM, 
      ':prenomM' => $prenomM,
      ':naissanceM' => $naissanceM,  
      ':securiteM' => $securiteM,
      ':emailM' => $emailM,
      ':emploiM' => $emploiM,
      ':diplomeM' => $diplomeM,
      ':niveauM' => $niveauM, 
      ':nomM1' => $nomM1, 
      ':prenomM1' => $prenomM1,
      ':naissanceM1' => $naissanceM1,  
      ':securiteM1' => $securiteM1,
      ':emailM1' => $emailM1,
      ':emploiM1' => $emploiM1,
      ':diplomeM1' => $diplomeM1,
      ':niveauM1' => $niveauM1,
      ':travailC' => $travailC,
      ':derogationC' => $derogationC,
      ':numeroC' => $numeroC,
      ':conclusionC' => $conclusionC,
      ':debutC' => $debutC,
      ':finC' => $finC,
      ':avenantC' => $avenantC,
      ':executionC' => $executionC,
      ':dureC' => $dureC,
      ':typeC' => $typeC,
      ':rdC' => $rdC,
      ':raC' => $raC,
      ':rpC' => $rpC,
      ':rsC' => $rsC,
      ':rdC1' => $rdC1,
      ':raC1' => $raC1,
      ':rpC1' => $rpC1,
      ':rsC1' => $rsC1,
      ':rdC2' => $rdC2,
      ':raC2' => $raC2,
      ':rpC2' => $rpC2,
      ':rsC2' => $rsC2,
      ':salaireC' => $salaireC,
      ':caisseC' => $caisseC,
      ':logementC' => $logementC,
      ':avantageC' => $avantageC,
      ':autreC' => $autreC, 
      ':lieuO' => $lieuO,
      ':priveO' => $priveO,
      ':attesteO' => $attesteO,
      ':modeC' => $modeC,
      ':prenomR' => $prenomR,
      ':dureCM' => $dureCM ];

      if(isset($id)){
          $sql = 'UPDATE ';
          $baseSql .= ' WHERE id = :id';
          $baseParam [':id'] = $id;
      }
      try {
        return $this->query($sql.$baseSql, $baseParam, true);
    } catch (PDOException $e) {
        return ['erreur' => $e->getMessage()];
    }
  }


  public  function updateCerfaContrat(
  $nomM = null,$prenomM = null,$naissanceM = null,$securiteM = null,$emailM = null,$emploiM = null,$diplomeM = null,$niveauM = null, 
  $nomM1 = null,$prenomM1 = null,$naissanceM1 = null,$securiteM1 = null,$emailM1= null,$emploiM1= null,$diplomeM1= null,$niveauM1 = null, 
  $travailC= null,$derogationC= null,$numeroC= null,$conclusionC= null,$debutC= null,$finC= null,$avenantC= null,$executionC= null,$dureC= null,$typeC= null,
  $rdC= null,$raC= null,$rpC= null,$rsC= null,$rdC1= null,$raC1= null,$rpC1= null,$rsC1= null,
  $rdC2= null,$raC2= null,$rpC2= null,$rsC2= null,
  $salaireC= null,$caisseC= null,$logementC= null,$avantageC= null,$autreC= null,
  $lieuO= null,$priveO= null,$attesteO= null,$modeC= null,$dureCM= null,
  $id = null){
   
    $sql = 'INSERT INTO ';
    $baseSql = self::$table.' SET 
     nomM = :nomM,prenomM = :prenomM,naissanceM = :naissanceM, securiteM = :securiteM,emailM = :emailM,emploiM = :emploiM, diplomeM = :diplomeM,niveauM = :niveauM,
     nomM1 = :nomM1,prenomM1 = :prenomM1,naissanceM1 = :naissanceM1, securiteM1 = :securiteM1,emailM1 = :emailM1,emploiM1 = :emploiM1, diplomeM1 = :diplomeM1,niveauM1 = :niveauM1, 
     travailC = :travailC,derogationC = :derogationC,numeroC = :numeroC,conclusionC = :conclusionC,debutC = :debutC,finC = :finC,avenantC = :avenantC,executionC = :executionC,dureC = :dureC,
     typeC = :typeC,rdC = :rdC,raC = :raC,rpC = :rpC,rsC = :rsC,rdC1 = :rdC1,raC1 = :raC1,rpC1 = :rpC1,rsC1 = :rsC1,
     rdC2 = :rdC2,raC2 = :raC2,rpC2 = :rpC2,rsC2 = :rsC2,
     salaireC = :salaireC,caisseC = :caisseC,logementC = :logementC,
     avantageC = :avantageC,autreC = :autreC,lieuO = :lieuO,priveO = :priveO,attesteO = :attesteO,modeC = :modeC,dureCM = :dureCM
     ';


    $baseParam = [
   
    ':nomM' => $nomM, 
    ':prenomM' => $prenomM,
    ':naissanceM' => $naissanceM,  
    ':securiteM' => $securiteM,
    ':emailM' => $emailM,
    ':emploiM' => $emploiM,
    ':diplomeM' => $diplomeM,
    ':niveauM' => $niveauM, 
    ':nomM1' => $nomM1, 
    ':prenomM1' => $prenomM1,
    ':naissanceM1' => $naissanceM1,  
    ':securiteM1' => $securiteM1,
    ':emailM1' => $emailM1,
    ':emploiM1' => $emploiM1,
    ':diplomeM1' => $diplomeM1,
    ':niveauM1' => $niveauM1,
    ':travailC' => $travailC,
    ':derogationC' => $derogationC,
    ':numeroC' => $numeroC,
    ':conclusionC' => $conclusionC,
    ':debutC' => $debutC,
    ':finC' => $finC,
    ':avenantC' => $avenantC,
    ':executionC' => $executionC,
    ':dureC' => $dureC,
    ':typeC' => $typeC,
    ':rdC' => $rdC,
    ':raC' => $raC,
    ':rpC' => $rpC,
    ':rsC' => $rsC,
    ':rdC1' => $rdC1,
    ':raC1' => $raC1,
    ':rpC1' => $rpC1,
    ':rsC1' => $rsC1,
    ':rdC2' => $rdC2,
    ':raC2' => $raC2,
    ':rpC2' => $rpC2,
    ':rsC2' => $rsC2,
    ':salaireC' => $salaireC,
    ':caisseC' => $caisseC,
    ':logementC' => $logementC,
    ':avantageC' => $avantageC,
    ':autreC' => $autreC, 
    ':lieuO' => $lieuO,
    ':priveO' => $priveO,
    ':attesteO' => $attesteO,
    ':modeC' => $modeC,
    ':dureCM' => $dureCM ];

    if(isset($id)){
        $sql = 'UPDATE ';
        $baseSql .= ' WHERE id = :id';
        $baseParam [':id'] = $id;
    }
    try {
      return $this->query($sql.$baseSql, $baseParam, true);
  } catch (PDOException $e) {
      return ['erreur' => $e->getMessage()];
  }
}
  public  function updateCerfaEtudiant(
  $nomA = null, $nomuA = null, $prenomA = null, $sexeA = null,
  $naissanceA = null, $departementA = null, $communeNA = null, 
  $nationaliteA = null, $regimeA = null, $situationA = null, $titrePA = null,
  $derniereCA = null, $securiteA = null, $intituleA = null, $titreOA = null, 
  $declareSA = null, $declareHA = null, $declareRA = null, $rueA = null, 
  $voieA = null, $complementA = null, $postalA = null, $communeA = null, 
  $numeroA = null, $emailA = null,
  
  $nomR = null,$prenomR=null,$emailR = null, $rueR = null, $voieR = null, $complementR = null, $postalR = null, $communeR = null, 
  $id = null){
   
    $sql = 'INSERT INTO ';
    $baseSql = self::$table.' SET 
    nomA = :nomA,nomuA = :nomuA,prenomA = :prenomA,sexeA = :sexeA, naissanceA = :naissanceA,
     departementA = :departementA,communeNA = :communeNA,nationaliteA = :nationaliteA,
     regimeA = :regimeA,situationA = :situationA,titrePA = :titrePA,derniereCA = :derniereCA,
     securiteA = :securiteA,intituleA = :intituleA,titreOA = :titreOA,
     declareSA = :declareSA,declareHA = :declareHA,declareRA = :declareRA,rueA = :rueA,
     voieA = :voieA,complementA = :complementA,postalA = :postalA,communeA = :communeA,
     numeroA = :numeroA,emailA = :emailA,
     nomR = :nomR,prenomR = :prenomR,emailR = :emailR,rueR = :rueR,voieR = :voieR,complementR = :complementR,postalR = :postalR,communeR = :communeR
     ';


    $baseParam = [
    ':nomA' => $nomA,
    ':nomuA' => $nomuA,
    ':prenomA' => $prenomA,
    ':sexeA' => $sexeA,
    ':naissanceA' => $naissanceA,
    ':departementA' => $departementA,
    ':communeNA' => $communeNA,
    ':nationaliteA' => $nationaliteA,
    ':regimeA' => $regimeA,
    ':situationA' => $situationA,
    ':titrePA' => $titrePA,
    ':derniereCA' => $derniereCA,
    ':securiteA' => $securiteA,
    ':intituleA' => $intituleA,
    ':titreOA' => $titreOA,
    ':declareSA' => $declareSA,
    ':declareHA' => $declareHA,
    ':declareRA' => $declareRA,
    ':rueA' => $rueA,
    ':voieA' => $voieA,
    ':complementA' => $complementA,
    ':postalA' => $postalA,
    ':communeA' => $communeA,
    ':numeroA' => $numeroA,
    ':emailA' => $emailA, 
    ':nomR' => $nomR, 
    ':prenomR' => $prenomR, 
    ':emailR' => $emailR, 
    ':rueR' => $rueR,
    ':voieR' => $voieR,
    ':complementR' => $complementR,
    ':postalR' => $postalR,
    ':communeR' => $communeR
   ];

    if(isset($id)){
        $sql = 'UPDATE ';
        $baseSql .= ' WHERE id = :id';
        $baseParam [':id'] = $id;
    }
    try {
      return $this->query($sql.$baseSql, $baseParam, true);
  } catch (PDOException $e) {
      return ['erreur' => $e->getMessage()];
  }
}

  public  function update(
  $nomA = null, $nomuA = null, $prenomA = null, $sexeA = null,
  $naissanceA = null, $departementA = null, $communeNA = null, 
  $nationaliteA = null, $regimeA = null, $situationA = null, $titrePA = null,
  $derniereCA = null, $securiteA = null, $intituleA = null, $titreOA = null, 
  $declareSA = null, $declareHA = null, $declareRA = null, $rueA = null, 
  $voieA = null, $complementA = null, $postalA = null, $communeA = null, 
  $numeroA = null, 
  
  $nomR = null,$prenomR= null,$emailR = null, $rueR = null, $voieR = null, $complementR = null, $postalR = null, $communeR = null, 
  $nomM = null,$prenomM = null,$naissanceM = null,$securiteM = null,$emailM = null,$emploiM = null,$diplomeM = null,$niveauM = null, 
  $nomM1 = null,$prenomM1 = null,$naissanceM1 = null,$securiteM1 = null,$emailM1= null,$emploiM1= null,$diplomeM1= null,$niveauM1 = null, 
  $id = null){
   
    $sql = 'INSERT INTO ';
    $baseSql = self::$table.' SET 
    nomA = :nomA,nomuA = :nomuA,prenomA = :prenomA,sexeA = :sexeA, naissanceA = :naissanceA,
     departementA = :departementA,communeNA = :communeNA,nationaliteA = :nationaliteA,
     regimeA = :regimeA,situationA = :situationA,titrePA = :titrePA,derniereCA = :derniereCA,
     securiteA = :securiteA,intituleA = :intituleA,titreOA = :titreOA,
     declareSA = :declareSA,declareHA = :declareHA,declareRA = :declareRA,rueA = :rueA,
     voieA = :voieA,complementA = :complementA,postalA = :postalA,communeA = :communeA,
     numeroA = :numeroA,
     nomR = :nomR,prenomR = :prenomR,emailR = :emailR,rueR = :rueR,voieR = :voieR,complementR = :complementR,postalR = :postalR,communeR = :communeR,
     nomM = :nomM,prenomM = :prenomM,naissanceM = :naissanceM, securiteM = :securiteM,emailM = :emailM,emploiM = :emploiM, diplomeM = :diplomeM,niveauM = :niveauM,
     nomM1 = :nomM1,prenomM1 = :prenomM1,naissanceM1 = :naissanceM1, securiteM1 = :securiteM1,emailM1 = :emailM1,emploiM1 = :emploiM1, diplomeM1 = :diplomeM1,niveauM1 = :niveauM1
     ';


    $baseParam = [
    ':nomA' => $nomA,
    ':nomuA' => $nomuA,
    ':prenomA' => $prenomA,
    ':sexeA' => $sexeA,
    ':naissanceA' => $naissanceA,
    ':departementA' => $departementA,
    ':communeNA' => $communeNA,
    ':nationaliteA' => $nationaliteA,
    ':regimeA' => $regimeA,
    ':situationA' => $situationA,
    ':titrePA' => $titrePA,
    ':derniereCA' => $derniereCA,
    ':securiteA' => $securiteA,
    ':intituleA' => $intituleA,
    ':titreOA' => $titreOA,
    ':declareSA' => $declareSA,
    ':declareHA' => $declareHA,
    ':declareRA' => $declareRA,
    ':rueA' => $rueA,
    ':voieA' => $voieA,
    ':complementA' => $complementA,
    ':postalA' => $postalA,
    ':communeA' => $communeA,
    ':numeroA' => $numeroA,

    ':nomR' => $nomR, 
    ':prenomR' => $prenomR,
    ':emailR' => $emailR, 
    ':rueR' => $rueR,
    ':voieR' => $voieR,
    ':complementR' => $complementR,
    ':postalR' => $postalR,
    ':communeR' => $communeR,

    ':nomM' => $nomM, 
    ':prenomM' => $prenomM,
    ':naissanceM' => $naissanceM,  
    ':securiteM' => $securiteM,
    ':emailM' => $emailM,
    ':emploiM' => $emploiM,
    ':diplomeM' => $diplomeM,
    ':niveauM' => $niveauM, 
    ':nomM1' => $nomM1, 
    ':prenomM1' => $prenomM1,
    ':naissanceM1' => $naissanceM1,  
    ':securiteM1' => $securiteM1,
    ':emailM1' => $emailM1,
    ':emploiM1' => $emploiM1,
    ':diplomeM1' => $diplomeM1,
    ':niveauM1' => $niveauM1
   ];

    if(isset($id)){
        $sql = 'UPDATE ';
        $baseSql .= ' WHERE id = :id';
        $baseParam [':id'] = $id;
    }
    try {
      return $this->query($sql.$baseSql, $baseParam, true);
  } catch (PDOException $e) {
      return ['erreur' => $e->getMessage()];
  }
}

public  function updateApprenti(
    $nomA = null, $nomuA = null, $prenomA = null, $sexeA = null,
    $naissanceA = null, $departementA = null, $communeNA = null, 
    $nationaliteA = null, $regimeA = null, $situationA = null, $titrePA = null,
    $derniereCA = null, $securiteA = null, $intituleA = null, $titreOA = null, 
    $declareSA = null, $declareHA = null, $declareRA = null, $rueA = null, 
    $voieA = null, $complementA = null, $postalA = null, $communeA = null, 
    $numeroA = null, 
    
    $nomR = null,$prenomR= null,$emailR = null, $rueR = null, $voieR = null, $complementR = null, $postalR = null, $communeR = null, 
   
    $id = null){
     
      $sql = 'INSERT INTO ';
      $baseSql = self::$table.' SET 
      nomA = :nomA,nomuA = :nomuA,prenomA = :prenomA,sexeA = :sexeA, naissanceA = :naissanceA,
       departementA = :departementA,communeNA = :communeNA,nationaliteA = :nationaliteA,
       regimeA = :regimeA,situationA = :situationA,titrePA = :titrePA,derniereCA = :derniereCA,
       securiteA = :securiteA,intituleA = :intituleA,titreOA = :titreOA,
       declareSA = :declareSA,declareHA = :declareHA,declareRA = :declareRA,rueA = :rueA,
       voieA = :voieA,complementA = :complementA,postalA = :postalA,communeA = :communeA,
       numeroA = :numeroA,
       nomR = :nomR,prenomR = :prenomR,emailR = :emailR,rueR = :rueR,voieR = :voieR,complementR = :complementR,postalR = :postalR,communeR = :communeR
       ';
  
  
      $baseParam = [
      ':nomA' => $nomA,
      ':nomuA' => $nomuA,
      ':prenomA' => $prenomA,
      ':sexeA' => $sexeA,
      ':naissanceA' => $naissanceA,
      ':departementA' => $departementA,
      ':communeNA' => $communeNA,
      ':nationaliteA' => $nationaliteA,
      ':regimeA' => $regimeA,
      ':situationA' => $situationA,
      ':titrePA' => $titrePA,
      ':derniereCA' => $derniereCA,
      ':securiteA' => $securiteA,
      ':intituleA' => $intituleA,
      ':titreOA' => $titreOA,
      ':declareSA' => $declareSA,
      ':declareHA' => $declareHA,
      ':declareRA' => $declareRA,
      ':rueA' => $rueA,
      ':voieA' => $voieA,
      ':complementA' => $complementA,
      ':postalA' => $postalA,
      ':communeA' => $communeA,
      ':numeroA' => $numeroA,
  
      ':nomR' => $nomR, 
      ':prenomR' => $prenomR,
      ':emailR' => $emailR, 
      ':rueR' => $rueR,
      ':voieR' => $voieR,
      ':complementR' => $complementR,
      ':postalR' => $postalR,
      ':communeR' => $communeR
  
    
     ];
  
      if(isset($id)){
          $sql = 'UPDATE ';
          $baseSql .= ' WHERE id = :id';
          $baseParam [':id'] = $id;
      }
      try {
        return $this->query($sql.$baseSql, $baseParam, true);
    } catch (PDOException $e) {
        return ['erreur' => $e->getMessage()];
    }
  }
  

  public  function setConventionOpco($conventionOpco,$id){
      $sql = 'UPDATE '.self::$table.' SET conventionOpco = :conventionOpco WHERE id = :id';
      $param = [':conventionOpco'=>($conventionOpco),':id'=>($id)];
      return $this->query($sql,$param,true);
  }

  public  function setCerfaOpco($cerfaOpco,$id){
      $sql = 'UPDATE '.self::$table.' SET cerfaOpco = :cerfaOpco WHERE id = :id';
      $param = [':cerfaOpco'=>($cerfaOpco),':id'=>($id)];
      return $this->query($sql,$param,true);
  }

  public  function setFactureOpco($factureOpco,$id){
      $sql = 'UPDATE '.self::$table.' SET factureOpco = :factureOpco WHERE id = :id';
      $param = [':factureOpco'=>($factureOpco),':id'=>($id)];
      return $this->query($sql,$param,true);
  }

  public  function setNumeroInterne($numeroInterne,$id){
      $sql = 'UPDATE '.self::$table.' SET numeroInterne = :numeroInterne WHERE id = :id';
      $param = [':numeroInterne'=>($numeroInterne),':id'=>($id)];
      return $this->query($sql,$param,true);
  }

  public  function setNumeroExterne($numeroExterne,$id){
      $sql = 'UPDATE '.self::$table.' SET numeroExterne = :numeroExterne WHERE id = :id';
      $param = [':numeroExterne'=>($numeroExterne),':id'=>($id)];
      return $this->query($sql,$param,true);
  }

  public  function setNumeroDeca($numeroDeca,$id){
      $sql = 'UPDATE '.self::$table.' SET numeroDeca = :numeroDeca WHERE id = :id';
      $param = [':numeroDeca'=>($numeroDeca),':id'=>($id)];
      return $this->query($sql,$param,true);
  }

   // ce qui est en bas fonctionne

   public function setPath($prov, $path, $id) {
    switch ($prov) {
        case 1:
            $column = 'signatureApprenti';
            break;
        case 2:
            $column = 'signatureEmployeur';
            break;
        case 3:
            $column = 'signatureEcole';
            break;
        case 4:
            $column = 'signatureConventionEmployeur';
            break;
        case 5:
            $column = 'signatureConventionEcole';
            break;
        case 6:
            $column = 'signatureRepresentantApprenti';
            break;
        default:
            throw new InvalidArgumentException("Invalid value for prov. Expected 1, 2, or 3.");
    }

    $sql = 'UPDATE ' . self::$table . ' SET ' . $column . ' = :path WHERE id = :id';
    $param = [':path' => $path, ':id' => $id];
    return $this->query($sql, $param, true);
}


  public  function findbyentreprise($idemployeur){
      $sql = static::selectString().' WHERE idemployeur = :idemployeur';
      return $this->query($sql,[':idemployeur'=>$idemployeur],true);
  }

  public function findbyentrepriseForApp($entrepriseId) {
    // 1. Vérifiez la connexion à la base
    if (!$this->db) {
        error_log("Erreur: Pas de connexion DB");
        return [];
    }

    // 2. Loggez la requête
    error_log("Recherche CERFA pour entreprise: $entrepriseId");

    try {
        // 3. Requête avec paramètre sécurisé
        $query = $this->db->prepare("SELECT * FROM cerfa WHERE idemployeur = ?");
        $query->execute([$entrepriseId]);
        
        // 4. Retournez toujours un tableau
        return $query->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        error_log("Erreur SQL: " . $e->getMessage());
        return [];
    }
}

  public  function findbyformation($idformation){
      $sql = static::selectString().' WHERE idformation = :idformation';
      return $this->query($sql,[':idformation'=>$idformation],true);
  }


 
  public  function byNom($nom){
      $sql = self::selectString() . ' WHERE nomA = :nomA';
      $param = [':nomA' => $nom];
      return $this->query($sql, $param,true);
  }

  
  public  function find($id){
    $sql = static::selectString().' WHERE id = :id';
    return $this->query($sql,[':id'=>$id],true);
}

public  function findByIdClient($id){
    $sql = static::selectString().' WHERE id_users = :id';
    return $this->query($sql,[':id'=>$id],false);
}


  public  function byEmail($email){
      $sql = self::selectString() . ' WHERE emailA = :emailA';
      $param = [':emailA' => $email];
      return $this->query($sql, $param);
  }

  public  function delete($id){
    $sql = 'DELETE FROM ' . self::$table . ' WHERE id = :id';
    $param = [':id' => $id];
    try {
        return $this->query($sql, $param,true);
    } catch (PDOException $e) {
        return ['erreur' => $e->getMessage()];
    }
}

  public  function countBySearchType($idusers,$search = null){
      $count = 'SELECT COUNT(*) AS Total FROM '.self::$table;
      $where = ' WHERE 1 = 1';
      $tab = [];
      if(isset($idusers)){
        $tIdUsers = ' AND (id_users = :idusers )';
        $tab[':idusers'] = $idusers;
    }else{
        $tIdUsers = '';
    }
      if(isset($search)){
          $tSearch = ' AND (nomA LIKE :search)';
          $tab[':search'] = '%'.$search.'%';
      }else{
          $tSearch = '';
      }
      
      try {
        $result = $this->query($count . $where . $tSearch.$tIdUsers, $tab, true);
        return ['total' => $result['Total']];
        } catch (PDOException $e) {
            return ['erreur' => $e->getMessage()];
        }
  }

  public  function countBySearchTypeIdCentre($id_centre,$search = null){
    $count = 'SELECT COUNT(*) AS Total FROM '.self::$table;
    $where = ' WHERE 1 = 1';
    $tab = [];
    if(isset($id_centre)){
        $tIdUsers = ' AND (id_centre = :id_centre )';
        $tab[':id_centre'] = $id_centre;
    }else{
        $tIdUsers = '';
    }
    if(isset($search)){
        $tSearch = ' AND (nomA LIKE :search)';
        $tab[':search'] = '%'.$search.'%';
    }else{
        $tSearch = '';
    }
    
    try {
      $result = $this->query($count . $where . $tSearch.$tIdUsers, $tab, true);
      return ['total' => $result['Total']];
      } catch (PDOException $e) {
          return ['erreur' => $e->getMessage()];
      }
}

public  function searchTypeIdCentre($id_centre,$nbreParPage=null,$pageCourante=null,$search = null){
    $limit = ' ORDER BY nomA ';
    $limit .= (isset($nbreParPage)&&isset($pageCourante))?' LIMIT '.(($pageCourante-1)*$nbreParPage).','.$nbreParPage:'';
    $where = ' WHERE 1 = 1';
    $tab = [];
    if(isset($id_centre)){
        $tIdUsers = ' AND (id_centre = :id_centre )';
        $tab[':id_centre'] = $id_centre;
    }else{
        $tIdUsers = '';
    }
    if(isset($search)){
        $tSearch = ' AND (nomA LIKE :search )';
        $tab[':search'] = '%'.$search.'%';
    }else{
        $tSearch = '';
    }
    
    
    try {
        return $this->query(self::selectString().$where.$tSearch.$tIdUsers.$limit,$tab);
    } catch (PDOException $e) {
        return ['erreur' => $e->getMessage()];
    }
}

    public  function searchType($idusers,$nbreParPage=null,$pageCourante=null,$search = null){
        $limit = ' ORDER BY nomA ';
        $limit .= (isset($nbreParPage)&&isset($pageCourante))?' LIMIT '.(($pageCourante-1)*$nbreParPage).','.$nbreParPage:'';
        $where = ' WHERE 1 = 1';
        $tab = [];
        if(isset($idusers)){
            $tIdUsers = ' AND (id_users = :idusers )';
            $tab[':idusers'] = $idusers;
        }else{
            $tIdUsers = '';
        }
        if(isset($search)){
            $tSearch = ' AND (nomA LIKE :search )';
            $tab[':search'] = '%'.$search.'%';
        }else{
            $tSearch = '';
        }
        
        
        try {
            return $this->query(self::selectString().$where.$tSearch.$tIdUsers.$limit,$tab);
        } catch (PDOException $e) {
            return ['erreur' => $e->getMessage()];
        }
  }




    

    public static function selectString(){
        return 'SELECT * FROM '.self::$table;
    }
    public  function query($sql, $params = [], $single = false) {
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        if ($single) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
