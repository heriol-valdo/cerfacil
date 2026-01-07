<?php // Admin : home-content.php

include __DIR__.'../../elements/header.php';
// require_once __DIR__ . '/../../requestFile/authRequet.php';
require_once __DIR__ . '/../../Controller/FormationController.php';
require_once __DIR__ . '/../../Controller/EntrepriseController.php';
require_once __DIR__ . '/../../Controller/CerfaController.php';
require_once __DIR__ . '/../../vendor/tecnickcom/tcpdf/tcpdf.php';


$cerfas = CerfaController::getCerfasbyId($_SESSION['idDossierSignature']);
$ligneformation = FormationController::getFormations($cerfas->idformation);
$ligneemployeur = EntrepriseController::getEntreprises($cerfas->idemployeur);

function nameOpco($idopco){
   if($idopco== null || $idopco == 'null'  || $idopco == 0 || $idopco == '0' || $idopco == ''){
      return isEmptySave('');
   }else{
        $opco = CerfaController::getOpco($idopco);
         if (property_exists( $opco, 'erreur')) {
               return '<span class="text-danger">Erreur : '.$opco->erreur.'</span>';
        }else if(property_exists( $opco, 'valid')) {

            return $opco->data->nom;
        }
   }
}

function nameOpcos($idopco){
   if($idopco== null || $idopco == 'null'  || $idopco == 0 || $idopco == '0' || $idopco == ''){
      return isEmpty('');
   }else{
        $opco = CerfaController::getOpco($idopco);
         if (property_exists( $opco, 'erreur')) {
               return '<span class="text-danger">Erreur : '.$opco->erreur.'</span>';
        }else if(property_exists( $opco, 'valid')) {

            return $opco->data->nom;
        }
   }
}

  function isEmpty($string) {
        if(!empty($string)){
            return $string;
        }
        return '<span class="text-danger">Pas renseigné</span>';
    }

    function isEmptySave($string) {
        if(!empty($string)){
            return $string;
        }
        return 'Pas renseigné';
    }


    if(isset($_SESSION['idDossierSignature'])) {
        

        if($cerfas && $ligneemployeur && $ligneformation) {
            $name = createpdf($_SESSION['idDossierSignature'], $cerfas, $ligneemployeur, $ligneformation);
        } else {
            $error = "Certaines données nécessaires ne sont pas disponibles.";
        }
    } else {
        $error = "L'identifiant n'est pas défini.";
    }
           

        
function createpdf($id,$cerfas,$ligneemployeur,$ligneformation ){

    try{
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    
    // Paramètres de page
    $pdf->SetMargins(0, 0, 0);
    $pdf->SetAutoPageBreak(true, 0);
    
    
    
    // Ajoutez une première page
    $pdf->AddPage();
    
    // Chargez l'image de la première page du Cerfa
    $pdf->Image('https://cerfa.heriolvaldo.com/cerfa/public/assets/pdf/cerfa1.jpg', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight(), 'JPEG');
    
    if($cerfas->priveO == "oui"){
        $pdf->SetXY(77, 29.3);
        $pdf->Cell(0, 10,  "x", 0, 1, 'L');
    }elseif($cerfas->priveO == "non"){
        $pdf->SetXY(123, 29.3);
        $pdf->Cell(0, 10,  "x", 0, 1, 'L');
    }

    $pdf->SetXY(144.8, 23.8);
    $pdf->Cell(0, 10,  $cerfas->modeC, 0, 1, 'L');

    $pdf->SetXY(10, 39);
    $pdf->Cell(0, 10,  $ligneemployeur->nomE, 0, 1, 'L');
    

    $pdf->SetXY(105, 39);
    $pdf->Cell(0, 10,  $ligneemployeur->siretE, 0, 1, 'L');


    $pdf->SetXY(140, 43);
    $pdf->Cell(0, 10,  $ligneemployeur->typeE, 0, 1, 'L');



    $pdf->SetXY(145, 48);
    $pdf->Cell(0, 10,  $ligneemployeur->specifiqueE, 0, 1, 'L');


    $pdf->SetXY(168, 55);
    $pdf->Cell(0, 10,  $ligneemployeur->codeaE, 0, 1, 'L');



    $pdf->SetXY(105, 65.5);
    $pdf->Cell(0, 10,  $ligneemployeur->totalE, 0, 1, 'L');


    $pdf->SetXY(105, 79.2);
    $pdf->Cell(0, 10,  $ligneemployeur->codeiE, 0, 1, 'L');




    $pdf->SetXY(15, 48);
    $pdf->Cell(0, 10,  $ligneemployeur->rueE, 0, 1, 'L');



    $pdf->SetXY(55, 48);
    $pdf->Cell(0, 10,  $ligneemployeur->voieE, 0, 1, 'L');



    $pdf->SetXY(33, 55);
    $pdf->Cell(0, 10,  $ligneemployeur->complementE, 0, 1, 'L');



    $pdf->SetXY(33, 61);
    $pdf->Cell(0, 10,  $ligneemployeur->postalE, 0, 1, 'L');


    $pdf->SetXY(33, 67);
    $pdf->Cell(0, 10,  $ligneemployeur->communeE, 0, 1, 'L');


    $pdf->SetXY(33, 73.5);
    $pdf->Cell(0, 10,  $ligneemployeur->numeroE, 0, 1, 'L');


    $pdf->SetXY(33, 79);
    $pdf->Cell(0, 10,  $ligneemployeur->emailE, 0, 1, 'L');

    $pdf->SetXY(75, 101.2);
    $pdf->Cell(0, 10,  $cerfas->nomA, 0, 1, 'L');

    $pdf->SetXY(37, 107);
    $pdf->Cell(0, 10,  $cerfas->nomuA, 0, 1, 'L');

    $pdf->SetXY(103, 112.5);
    $pdf->Cell(0, 10,  $cerfas->prenomA, 0, 1, 'L');

    $pdf->SetXY(46, 119);
    $pdf->Cell(0, 10,  $cerfas->securiteA, 0, 1, 'L');

    $pdf->SetXY(12.6, 129.5);
    $pdf->Cell(0, 10,  $cerfas->rueA, 0, 1, 'L');

    $pdf->SetXY(37, 129.8);
    $pdf->Cell(0, 10,  $cerfas->voieA, 0, 1, 'L');

    $pdf->SetXY(35, 136);
    $pdf->Cell(0, 10,  $cerfas->complementA, 0, 1, 'L');

    $pdf->SetXY(33, 142);
    $pdf->Cell(0, 10,  $cerfas->postalA, 0, 1, 'L');

    $pdf->SetXY(32, 148.8);
    $pdf->Cell(0, 10,  $cerfas->communeA, 0, 1, 'L');

    $pdf->SetXY(31, 155);
    $pdf->Cell(0, 10,  $cerfas->numeroA, 0, 1, 'L');

    $pdf->SetXY(27, 160.5);
    $pdf->Cell(0, 10,  $cerfas->emailA, 0, 1, 'L');


    $pdf->SetXY(8.5, 184);
    $pdf->Cell(0, 10,  $cerfas->nomR.'  '. $cerfas->prenomR, 0, 1, 'L');


    $pdf->SetXY(13, 193.5);
    $pdf->Cell(0, 10,  $cerfas->rueR, 0, 1, 'L');

    $pdf->SetXY(37, 193.5);
    $pdf->Cell(0, 10,  $cerfas->voieR, 0, 1, 'L');

    $pdf->SetXY(34, 199.7);
    $pdf->Cell(0, 10,  $cerfas->complementR, 0, 1, 'L');

    $pdf->SetXY(33, 205.5);
    $pdf->Cell(0, 10,  $cerfas->postalR, 0, 1, 'L');

    $pdf->SetXY(30, 211.7);
    $pdf->Cell(0, 10,  $cerfas->communeR, 0, 1, 'L');

    $pdf->SetXY(27, 217.5);
    $pdf->Cell(0, 10,  $cerfas->emailR, 0, 1, 'L');

    if ($cerfas->naissanceA == '') {

    }else{
        $date_formatee = date("d/m/Y", strtotime($cerfas->naissanceA));
        $pdf->SetXY(142, 120.5);
        $pdf->Cell(0, 10,  $date_formatee, 0, 1, 'L');
    }

    if($cerfas->sexeA == "M"){
        $pdf->SetXY(117, 127);
        $pdf->Cell(0, 10,  "X", 0, 1, 'L');
    }elseif($cerfas->sexeA == "F"){
        $pdf->SetXY(127, 127);
        $pdf->Cell(0, 10,  "X", 0, 1, 'L');
    }
    
    $pdf->SetXY(155.5, 133);
    $pdf->Cell(0, 10, $cerfas->departementA , 0, 1, 'L');

    $pdf->SetXY(105, 144.5);
    $pdf->Cell(0, 10, $cerfas->communeNA , 0, 1, 'L');

    $pdf->SetXY(127, 151);
    $pdf->Cell(0, 10, $cerfas->nationaliteA , 0, 1, 'L');

    $pdf->SetXY(165, 151);
    $pdf->Cell(0, 10, $cerfas->regimeA , 0, 1, 'L');
   
    if($cerfas->declareSA == "oui"){
        $pdf->SetXY(120, 160.5);
        $pdf->Cell(0, 10,  "X", 0, 1, 'L');
    }elseif($cerfas->declareSA == "non"){
        $pdf->SetXY(140, 160.5);
        $pdf->Cell(0, 10,  "X", 0, 1, 'L');
    }

    
    if($cerfas->declareHA == "oui"){
        $pdf->SetXY(127, 170.8);
        $pdf->Cell(0, 10,  "X", 0, 1, 'L');
    }elseif($cerfas->declareHA == "non"){
        $pdf->SetXY(146, 170.8);
        $pdf->Cell(0, 10,  "X", 0, 1, 'L');
    }

    if($cerfas->declareRA == "oui"){
        $pdf->SetXY(129.5, 220);
        $pdf->Cell(0, 10,  "X", 0, 1, 'L');
    }elseif($cerfas->declareRA == "non"){
        $pdf->SetXY(148.5, 220);
        $pdf->Cell(0, 10,  "X", 0, 1, 'L');
    }

    $pdf->SetXY(153, 177);
    $pdf->Cell(0, 10, $cerfas->situationA , 0, 1, 'L');

    $pdf->SetXY(164, 183);
    $pdf->Cell(0, 10, $cerfas->titrePA , 0, 1, 'L');

    $pdf->SetXY(161, 189.5);
    $pdf->Cell(0, 10, $cerfas->derniereCA , 0, 1, 'L');

    $pdf->SetXY(106, 200.5);
    $pdf->Cell(0, 10, $cerfas->intituleA , 0, 1, 'L');

    $pdf->SetXY(172, 206.3);
    $pdf->Cell(0, 10, $cerfas->titreOA , 0, 1, 'L');



    $pdf->SetXY(8.5, 243.5);
    $pdf->Cell(0, 10, $cerfas->nomM , 0, 1, 'L');

    $pdf->SetXY(25.5, 250);
    $pdf->Cell(0, 10, $cerfas->prenomM , 0, 1, 'L');

   
    if($cerfas->naissanceM == ""){}else{
        $date_formateeM = date("d/m/Y", strtotime($cerfas->naissanceM));
        $pdf->SetXY(43, 256);
        $pdf->Cell(0, 10,  $date_formateeM, 0, 1, 'L');

    }
    $pdf->SetXY(18.5, 261.5);
    $pdf->Cell(0, 10,  $cerfas->securiteM, 0, 1, 'L');

    $pdf->SetXY(8.5, 270.7);
    $pdf->Cell(0, 10,  $cerfas->emailM, 0, 1, 'L');

    $pdf->SetXY(8.5, 279.8);
    $pdf->Cell(0, 10,  $cerfas->emploiM, 0, 1, 'L');


    $pdf->SetXY(105.5, 243.5);
    $pdf->Cell(0, 10, $cerfas->nomM1 , 0, 1, 'L');

    $pdf->SetXY(123, 250);
    $pdf->Cell(0, 10, $cerfas->prenomM1 , 0, 1, 'L');

    if($cerfas->naissanceM1 == ""){}else{
        $date_formateeM1 = date("d/m/Y", strtotime($cerfas->naissanceM1));
        $pdf->SetXY(141, 256);
        $pdf->Cell(0, 10,  $date_formateeM1, 0, 1, 'L');

    }

   

    $pdf->SetXY(116, 261.5);
    $pdf->Cell(0, 10,  $cerfas->securiteM1, 0, 1, 'L');

    $pdf->SetXY(105.5, 270.7);
    $pdf->Cell(0, 10,  $cerfas->emailM1, 0, 1, 'L');

    $pdf->SetXY(105.5, 279.8);
    $pdf->Cell(0, 10,  $cerfas->emploiM1, 0, 1, 'L');

    

    
    
    
    // Ajoutez une deuxième page
    $pdf->AddPage();
    
    // Chargez l'image de la deuxième page du Cerfa
    $pdf->Image('https://cerfa.heriolvaldo.com/cerfa/public/assets/pdf/cerfa2.jpg', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight(), 'JPEG');
    
    $pdf->SetXY(8.5, 6.5);
    $pdf->Cell(0, 10, $cerfas->diplomeM , 0, 1, 'L');

    $pdf->SetXY(92, 11);
    $pdf->Cell(0, 10, $cerfas->niveauM , 0, 1, 'L');

    $pdf->SetXY(105.5, 6.5);
    $pdf->Cell(0, 10, $cerfas->diplomeM1 , 0, 1, 'L');

    $pdf->SetXY(189, 11);
    $pdf->Cell(0, 10, $cerfas->niveauM1 , 0, 1, 'L');

    $pdf->SetXY(63, 29);
    $pdf->Cell(0, 10, $cerfas->typeC , 0, 1, 'L');

    $pdf->SetXY(143, 29);
    $pdf->Cell(0, 10, $cerfas->derogationC , 0, 1, 'L');

    $pdf->SetXY(131, 36.8);
    $pdf->Cell(0, 10, $cerfas->numeroC , 0, 1, 'L');


    if($cerfas->conclusionC == ''){}else{

        $date_formateeC = date("d/m/Y", strtotime($cerfas->conclusionC));
        $pdf->SetXY(8.5, 50);
        $pdf->Cell(0, 10,  $date_formateeC, 0, 1, 'L');

    }

    if($cerfas->executionC == ''){}else{

        $date_formateeE = date("d/m/Y", strtotime($cerfas->executionC));
        $pdf->SetXY(68, 50);
        $pdf->Cell(0, 10,  $date_formateeE, 0, 1, 'L');
    }


    if($cerfas->debutC == ''){}else{

        $date_formateeD = date("d/m/Y", strtotime($cerfas->debutC));
        $pdf->SetXY(135, 50);
        $pdf->Cell(0, 10,  $date_formateeD, 0, 1, 'L');
    }

    if($cerfas->avenantC == ''){}else{

        $date_formateeA = date("d/m/Y", strtotime($cerfas->avenantC));
        $pdf->SetXY(51, 55.3);
        $pdf->Cell(0, 10,  $date_formateeA, 0, 1, 'L');
    }

    if($cerfas->finC == ''){}else{

        $date_formateeF = date("d/m/Y", strtotime($cerfas->finC));
        $pdf->SetXY(39, 66.3);
        $pdf->Cell(0, 10,  $date_formateeF, 0, 1, 'L');
    }

   
    $pdf->SetXY(105.8, 61.8);
    $pdf->Cell(0, 10, $cerfas->dureC , 0, 1, 'L');

    $pdf->SetXY(128.8, 61.8);
    $pdf->Cell(0, 10, $cerfas->dureCM , 0, 1, 'L');

   

    

    if($cerfas->travailC == "oui"){
        $pdf->SetXY(139, 71.5);
        $pdf->Cell(0, 10,  "X", 0, 1, 'L');
    }elseif($cerfas->travailC == "non"){
        $pdf->SetXY(158, 71.5);
        $pdf->Cell(0, 10,  "X", 0, 1, 'L');
    }

    if($cerfas->rdC == ''){}else{

        $date_formateeR = date("d/m/Y", strtotime($cerfas->rdC));
        $pdf->SetFontSize(11.5);
        $pdf->SetXY(27, 81.4);
        $pdf->Cell(0, 10,  $date_formateeR, 0, 1, 'L');
    }

    if($cerfas->raC == ''){}else{

        $date_formateeA = date("d/m/Y", strtotime($cerfas->raC));
        $pdf->SetFontSize(11.5);
        $pdf->SetXY(55, 81.4);
        $pdf->Cell(0, 10,  $date_formateeA, 0, 1, 'L');
    }




   

   

    $pdf->SetFontSize(11.5);
    $pdf->SetXY(83, 81.4);
    $pdf->Cell(0, 10, $cerfas->rpC , 0, 1, 'L');

    $pdf->SetFontSize(11.5);
    $pdf->SetXY(98, 81.4);
    $pdf->Cell(0, 10, $cerfas->rsC , 0, 1, 'L');


    if($cerfas->rdC1 == ''){}else{

        $date_formateeR1 = date("d/m/Y", strtotime($cerfas->rdC1));
        $pdf->SetFontSize(11.5);
        $pdf->SetXY(27, 86.4);
        $pdf->Cell(0, 10,  $date_formateeR1, 0, 1, 'L');
    }

    if($cerfas->raC1 == ''){}else{

        $date_formateeA1 = date("d/m/Y", strtotime($cerfas->raC1));
        $pdf->SetFontSize(11.5);
        $pdf->SetXY(55, 86.4);
        $pdf->Cell(0, 10,  $date_formateeA1, 0, 1, 'L');
    }
  

    

    $pdf->SetFontSize(11.5);
    $pdf->SetXY(83, 86.4);
    $pdf->Cell(0, 10, $cerfas->rpC1 , 0, 1, 'L');

    $pdf->SetFontSize(11.5);
    $pdf->SetXY(98, 86.4);
    $pdf->Cell(0, 10, $cerfas->rsC1 , 0, 1, 'L');


    if($cerfas->rdC2 == ''){}else{

        $date_formateeR2 = date("d/m/Y", strtotime($cerfas->rdC2));
        $pdf->SetFontSize(11.5);
        $pdf->SetXY(27,91);
        $pdf->Cell(0, 10,  $date_formateeR2, 0, 1, 'L');
    }

    if($cerfas->raC2 == ''){}else{

        $date_formateeA2 = date("d/m/Y", strtotime($cerfas->raC2));
        $pdf->SetFontSize(11.5);
        $pdf->SetXY(55, 91);
        $pdf->Cell(0, 10,  $date_formateeA2, 0, 1, 'L');
    }

    $pdf->SetFontSize(11.5);
    $pdf->SetXY(83, 91);
    $pdf->Cell(0, 10, $cerfas->rpC2 , 0, 1, 'L');

    $pdf->SetFontSize(11.5);
    $pdf->SetXY(98, 91);
    $pdf->Cell(0, 10, $cerfas->rsC2 , 0, 1, 'L');
  


    $pdf->SetXY(75, 100.5);
    $pdf->Cell(0, 10, $cerfas->salaireC. " €" , 0, 1, 'L');

    $pdf->SetXY(105.5, 104.5);
    $pdf->Cell(0, 10, $cerfas->caisseC , 0, 1, 'L');

    $pdf->SetXY(87, 109.5);
    $pdf->Cell(0, 10, $cerfas->avantageC , 0, 1, 'L');

    $pdf->SetXY(141, 109.5);
    $pdf->Cell(0, 10, $cerfas->logementC , 0, 1, 'L');

    if($cerfas->autreC == "oui"){
        $pdf->SetXY(195, 109.5);
        $pdf->Cell(0, 10,  "X", 0, 1, 'L');
    }elseif($cerfas->autreC == "non"){
        $pdf->SetXY(141, 109.5);
        $pdf->Cell(0, 10,  "", 0, 1, 'L');
    }


    // formation 
    if( $cerfas->idformation == 0){

    }else{

        if($ligneformation->entrepriseF == "oui"){
            $pdf->SetXY(41, 119.5);
            $pdf->Cell(0, 10,  "X", 0, 1, 'L');
        }elseif($ligneformation->entrepriseF == "non"){
            $pdf->SetXY(60, 119.5);
            $pdf->Cell(0, 10,  "X", 0, 1, 'L');
        }

       

        $pdf->SetXY(8.5, 128.5);
        $pdf->Cell(0, 10, $ligneformation->nomF , 0, 1, 'L');

        $pdf->SetXY(38, 133.5);
        $pdf->Cell(0, 10, $ligneformation->numeroF , 0, 1, 'L');

        $pdf->SetXY(38, 138.5);
        $pdf->Cell(0, 10, $ligneformation->siretF , 0, 1, 'L');

        $pdf->SetXY(12.8, 148);
        $pdf->Cell(0, 10, $ligneformation->rueF , 0, 1, 'L');

        $pdf->SetXY(38, 148);
        $pdf->Cell(0, 10, $ligneformation->voieF , 0, 1, 'L');

        $pdf->SetXY(34, 154);
        $pdf->Cell(0, 10, $ligneformation->complementF , 0, 1, 'L');

        $pdf->SetXY(33, 160);
        $pdf->Cell(0, 10, $ligneformation->postalF , 0, 1, 'L');

        $pdf->SetXY(30, 166.5);
        $pdf->Cell(0, 10, $ligneformation->communeF , 0, 1, 'L');

        if($ligneformation->responsableF == "oui"){
            $pdf->SetXY(8.5, 176.5);
            $pdf->Cell(0, 10,  "X", 0, 1, 'L');
        }elseif($ligneformation->entrepriseF == "non"){
            $pdf->SetXY(8.5, 176.5);
            $pdf->Cell(0, 10,  "", 0, 1, 'L');
        }

        $pdf->SetXY(168, 119.3);
        $pdf->Cell(0, 10, $ligneformation->diplomeF , 0, 1, 'L');

        $pdf->SetXY(105.5, 128.5);
        $pdf->Cell(0, 10, $ligneformation->intituleF , 0, 1, 'L');

        $pdf->SetXY(138.5, 133.8);
        $pdf->Cell(0, 10, $ligneformation->codeF , 0, 1, 'L');

        $pdf->SetXY(132.5, 138.4);
        $pdf->Cell(0, 10, $ligneformation->rnF , 0, 1, 'L');

        if($ligneformation->debutO == ''){}else{

            $date_formateeO = date("d/m/Y", strtotime($ligneformation->debutO));
            $pdf->SetFontSize(11.5);
            $pdf->SetXY(105.5, 152.4);
            $pdf->Cell(0, 10,  $date_formateeO, 0, 1, 'L');
        }

        if($ligneformation->prevuO == ''){}else{

            $date_formateeP = date("d/m/Y", strtotime($ligneformation->prevuO));
            $pdf->SetFontSize(11.5);
            $pdf->SetXY(105.5, 162.4);
            $pdf->Cell(0, 10,  $date_formateeP, 0, 1, 'L');
        }

        

       

        $pdf->SetXY(147, 167.4);
        $pdf->Cell(0, 10, $ligneformation->dureO , 0, 1, 'L');

        $pdf->SetXY(105.5, 187.8);
        $pdf->Cell(0, 10, $ligneformation->nomO , 0, 1, 'L');

        $pdf->SetXY(121.5, 192.6);
        $pdf->Cell(0, 10, $ligneformation->numeroO , 0, 1, 'L');

        $pdf->SetXY(126.5, 197.6);
        $pdf->Cell(0, 10, $ligneformation->siretO , 0, 1, 'L');

        $pdf->SetXY(110.9, 207);
        $pdf->Cell(0, 10, $ligneformation->rueO , 0, 1, 'L');

        $pdf->SetXY(135.7, 207.2);
        $pdf->Cell(0, 10, $ligneformation->voieO , 0, 1, 'L');

        $pdf->SetXY(131.7, 212.2);
        $pdf->Cell(0, 10, $ligneformation->complementO , 0, 1, 'L');

        $pdf->SetXY(130, 218.2);
        $pdf->Cell(0, 10,$ligneformation->postalO , 0, 1, 'L');

        $pdf->SetXY(128, 224.5);
        $pdf->Cell(0, 10, $ligneformation->communeO , 0, 1, 'L');
    }

    



    $pdf->SetXY(22, 234.5);
    $pdf->Cell(0, 10, $cerfas->lieuO , 0, 1, 'L');

    

    if($cerfas->attesteO == "oui"){
        $pdf->SetXY(8.5, 229.5);
        $pdf->Cell(0, 10,  "X", 0, 1, 'L');
    }elseif($cerfas->attesteO == "non"){
        $pdf->SetXY(8.5, 229.5);
        $pdf->Cell(0, 10,  "", 0, 1, 'L');
    }

    if(!empty($cerfas->signatureEmployeur)){
        $imageUrl =$cerfas->signatureEmployeur;
        $imagePath = tempnam(sys_get_temp_dir(), 'image_');
        file_put_contents($imagePath, file_get_contents($imageUrl));
        $pdf->Image($imageUrl, 45, 245.5, 20, 8, '', '', '', false, 150, '', false, false, 0);
    }

    if(!empty($cerfas->signatureApprenti)){
        $imageUrl =$cerfas->signatureApprenti;
        $imagePath = tempnam(sys_get_temp_dir(), 'image1_');
        file_put_contents($imagePath, file_get_contents($imageUrl));
        $pdf->Image($imageUrl, 95, 245.5, 20, 8, '', '', '', false, 150, '', false, false, 0);
    }

    // if(!empty($cerfas->signatureEcole)){
    //     $imageUrl =$cerfas->signatureEcole;
    //     $imagePath = tempnam(sys_get_temp_dir(), 'image2_');
    //     file_put_contents($imagePath, file_get_contents($imageUrl));
    //     $pdf->Image($imagePath, 15, 208, 50, 22, '', '', '', false, 200, '', false, false, 0);
    //   }
    

    
    
    
    // Générez le contenu PDF
    ob_start();
    $name= $id.'cerfa_document.pdf';
    $pdfFilePath = __DIR__ . '/../../assets/pdf/'.$name;
    $pdf->Output($pdfFilePath, 'F');
    return $name;
   }catch(Exception $e){
    return $e->getMessage();
   }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signature | CerFacil</title>
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.min.css">
    <style>

          .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: black;
        }
        
        .buttons-vertical {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .button-container {
            margin-top:20px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        #signaturepad {
            border: 2px solid #ccc;
            border-radius: 4px;
            cursor: crosshair;
            width: 100%;
            height: 200px;
        }
        
        .sendBtn, .sendBtn1 {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        
        .sendBtn:hover, .sendBtn1:hover {
            background-color: #0056b3;
        }
        
        .sendBtn:disabled, .sendBtn1:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }
        
        .form-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .section-title {
            color: #333;
            margin-bottom: 20px;
        }
        
        .card-title {
            color: #495057;
            margin-bottom: 15px;
        }
        
        #pdfViewer {
            border: 1px solid #dee2e6;
            border-radius: 4px;
           
            padding-top:20px;
            padding-left:20%;
            margin: 20px 0;
            max-height: 600px;
            overflow-y: auto;
        }
        
        #loader {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }
        .validation-errors .alert {
    border-left: 4px solid #dc3545;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

.validation-modal .swal2-html-container {
    text-align: left !important;
}


        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-row.full {
            grid-template-columns: 1fr;
        }

        .form-row.triple {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            color: #495057;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-input, .form-select {
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .form-input[readonly] {
            background: #f8f9fa;
            color: #6c757d;
            cursor: not-allowed;
        }

        .form-display {
            padding: 12px 15px;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            color: #495057;
            min-height: 42px;
            display: flex;
            align-items: center;
        }

        .required {
            color: #e74c3c;
        }

        .control-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #2980b9, #1c5a7a);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-success {
            background: linear-gradient(45deg, #27ae60, #219a52);
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(45deg, #219a52, #1a7a41);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(45deg, #95a5a6, #7f8c8d);
            color: white;
        }

        .btn-secondary:hover {
            background: linear-gradient(45deg, #7f8c8d, #6c7b7c);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(149, 165, 166, 0.3);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .status-indicator {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }

        .status-view {
            background: #e3f2fd;
            color: #1976d2;
        }

        .status-edit {
            background: #fff3e0;
            color: #f57c00;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .control-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }

        .validation-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 8px;
            margin: 15px 0;
            border: 1px solid #c3e6cb;
        }

        
        .section-title {
            color: #2c3e50;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #3498db;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 60px;
            height: 3px;
            background: #e74c3c;
        }

        .form-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
            border-left: 4px solid #3498db;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .card-title {
            color: #2c3e50;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .text-danger {
  color: #F44336;
   font-size: 12px;
   font-weight: 600;
}


/* Style de base pour tous les labels */
.label {
  display: inline-block;
  padding: 4px 8px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin: 2px;
  line-height: 1;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  transition: all 0.2s ease-in-out;
}

/* Styles spécifiques pour chaque statut */
.label-success {
  background-color: #4CAF50;
  color: white;
  border-radius: 10px;
  border: 1px solid #388E3C;
}

.label-info {
  background-color: #2196F3;
  color: white;
  border-radius: 5px;
}

.label-primary {
  background-color: #3F51B5;
  color: white;
  border-radius: 5px;
}

.label-danger {
  background-color: #F44336;
  color: white;
  border-radius: 5px;
}

.label-default {
  background-color: #9E9E9E;
  color: white;
  border-radius: 5px;
}

.label-warning {
  background-color: #FF9800;
  color: white;
  border-radius: 5px;
}


/* Effet au survol */
.label:hover {
  opacity: 0.9;
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Version responsive */
@media (max-width: 768px) {
  .label {
    font-size: 10px;
    padding: 3px 6px;
  }
}
          body {
                font-family: 'Poppins', sans-serif;
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                min-height: 100vh;
                color: #1e293b;
            }
          footer {
            padding: 0;
            text-align: center;
            color: var(--3main-color);
            width: 100%;
            background-color:rgb(228, 234, 245);
        }

        .footer-bottom p {
            font-size: 15px;
            margin-bottom: 0;
        }
        .header {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 12px 100px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo {
            width: 32px;
            height: 32px;
            background: linear-gradient(45deg, #3b82f6, #8b5cf6);
            border-radius: 8px;
        }

        .breadcrumb {
            font-size: 14px;
            color: #6b7280;
        }

        .breadcrumb a {
            color: #3b82f6;
            text-decoration: none;
        }

        .main-header {
            background: white;
            padding: 20px 100px;
            border-bottom: 1px solid #e5e7eb;
        }

        .profile-section {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .profile-name {
            font-size: 24px;
            font-weight: 600;
            color: #111827;
        }

        .rupture-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            border: 1px solid #f59e0b;
        }

        .date {
            color: #6b7280;
            font-size: 14px;
        }

        .nav-tabs {
            background: white;
            padding: 0 100px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            gap: 32px;
        }

        .nav-tab {
            padding: 16px 0;
            color: #6b7280;
            text-decoration: none;
            border-bottom: 2px solid transparent;
            font-weight: 500;
            cursor: pointer;
        }

        .nav-tab.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }

        .nav-tab:hover {
            color: #3b82f6;
        }

        .content {
            width: 100%;
            margin-right: 20px;
            padding-left: 100px;
            padding-top: 32px;
            padding-right: 20px

        }

        .form-section {
            background: white;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 24px;
            color: #111827;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        .form-row.full {
            grid-template-columns: 1fr;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-input {
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-input:disabled {
            background-color: #f9fafb;
            color: #9ca3af;
        }

        .form-select {
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            cursor: pointer;
        }

        .form-textarea {
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            resize: vertical;
            min-height: 80px;
        }

        .info-icon {
            width: 16px;
            height: 16px;
            background: #6b7280;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            margin-left: 8px;
            cursor: help;
        }

        .radio-group {
            display: flex;
            gap: 16px;
            margin-top: 8px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .radio-input {
            width: 16px;
            height: 16px;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .contract-section {
            margin-bottom: 32px;
        }

        .contract-info {
            background: #f3f4f6;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .contract-reason {
            font-size: 14px;
            color: #6b7280;
            background: #f9fafb;
            padding: 12px;
            border-radius: 6px;
            border-left: 3px solid #3b82f6;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .nav-tabs {
                gap: 16px;
                overflow-x: auto;
            }
            
            .content {
                padding: 16px;
            }
            
            .form-section {
                padding: 20px;
            }
        }
    </style>
   
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo"></div>
            <div class="breadcrumb">
            <a href="home"><?= $ligneformation->intituleF ?></a> › 
            <a href="home"><?=  date('d/m/Y', strtotime($cerfas->date_creation ))?></a> › 
            <strong><?= (empty($cerfas->nomA)? $cerfas->emailA : $cerfas->nomA ) ?></strong> › 
            Dossier
        </div>
        </div>

        <div class="main-header">
             <div class="profile-section">
                <h1 class="profile-name"><?= (empty($cerfas->nomA)? $cerfas->emailA : $cerfas->nomA ) ?></h1>
                <?= CerfaController::getEtatCerfa($cerfas->numeroInterne,$ligneemployeur->idopco);?>
                <!-- <span class="date">25/02/2025</span> -->
            </div>
        </div>

        <nav class="nav-tabs">
            <a href="#" class="nav-tab active" data-tab="company">Signatures</a>
        </nav>

        <div class="content">
            <div >
                <div class="form-section">
                    <h2 class="section-title">Signature</h2>
                    <p style="margin-top: 20px;">
                        <h6 style="font-style: oblique; font-weight: normal;" class="text-center">
                            Signer le cerfa pour qu'il soit validé par l'opco
                        </h6>
                    </p>
                    
                    <div class="form-card">
                        <h3 class="card-title">Informations sur le cerfa</h3>

                        <div style="padding-left:45%;">
                            <button type="button" onclick="ModalOpen();" class="sendBtn btn btn-lg btn-rounded text-center"  name="submit_form">
                                Signer
                            </button>
                        </div>

                        <div id="pdfViewer"></div>
                        <input type="hidden" value='https://cerfa.heriolvaldo.com/app/assets/pdf/<?=$name?>' id="url">
                        <input type="file" id="file" name="file" style="display:none" accept=".jpg, .jpeg, .png"/>
                        <div id="loader"></div>

                        <!-- Modal principal -->
                        <div id="myModal" class="modal">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2>Choisir une option</h2>
                                    <span class="close" onclick="closeModal()">&times;</span>
                                </div>
                                <div class="modal-body">
                                    <div class="buttons-vertical">
                                        <button id="button1" onclick="ModalOpenSignature()" class="sendBtn btn btn-lg btn-rounded text-center">
                                            Manuellement
                                        </button>
                                        <button id="button2" onclick="openFileDialog()" class="sendBtn btn btn-lg btn-rounded text-center">
                                            Importer un fichier
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal signature -->
                        <div id="myModalSignature" class="modal">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2>Signer Manuellement</h2>
                                    <span class="close" onclick="closeModalSignature()">&times;</span>
                                </div>
                                <div class="modal-body">
                                    <form onsubmit="return sendData();" method="POST" id="myForm">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <canvas id="signaturepad" width="500" height="200"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="button-container">
                                                <button type="submit" id="circle" class="sendBtn1 btn btn-lg btn-rounded text-center">
                                                    Envoyer
                                                </button>
                                                <button type="button" id="clear" class="sendBtn1 btn btn-lg btn-rounded text-center" onclick="clearCanvas()">
                                                    Effacer
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            <!-- Footer content -->
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        // Variables globales
    let signaturePad = null;
    let fileInputInitialized = false;

// Fonction pour initialiser le signature pad
 function initializeSignaturePad() {
        const canvas = document.getElementById('signaturepad');
        const ctx = canvas.getContext('2d');
        let writingMode = false;

        canvas.addEventListener('pointerdown', handlePointerDown, { passive: true });
        canvas.addEventListener('pointerup', handlePointerUp, { passive: true });
        canvas.addEventListener('pointermove', handlePointerMove, { passive: true });

        function handlePointerDown(event) {
            writingMode = true;
            ctx.beginPath();
            const [positionX, positionY] = getCursorPosition(event);
            ctx.moveTo(positionX, positionY);
        }

        function handlePointerUp() {
            writingMode = false;
        }

        function handlePointerMove(event) {
            if (!writingMode) return;
            const [positionX, positionY] = getCursorPosition(event);
            ctx.lineTo(positionX, positionY);
            ctx.stroke();
        }

        function getCursorPosition(event) {
            const positionX = event.clientX - event.target.getBoundingClientRect().x;
            const positionY = event.clientY - event.target.getBoundingClientRect().y;
            return [positionX, positionY];
        }

        // Configuration du canvas
        ctx.lineWidth = 3;
        ctx.lineJoin = ctx.lineCap = 'round';
        ctx.strokeStyle = '#000';

        return {
            clear: function() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            },
            getDataURL: function() {
                return canvas.toDataURL();
            },
            isEmpty: function() {
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                return !imageData.data.some(channel => channel !== 0);
            }
        };
    }

// Fonctions de gestion des modales
    function ModalOpen() {
        document.getElementById("myModal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("myModal").style.display = "none";
    }

    function ModalOpenSignature() {
        closeModal();
        document.getElementById("myModalSignature").style.display = "block";
        
        // Initialiser le signature pad si nécessaire
        if (!signaturePad) {
            signaturePad = initializeSignaturePad();
        }
    }

    function closeModalSignature() {
        document.getElementById("myModalSignature").style.display = "none";
        ModalOpen();
    }

    function closeModalSignatureSigne() {
        document.getElementById("myModalSignature").style.display = "none";
    }

    function closeAllModals() {
        closeModal();
        closeModalSignatureSigne();
    }

// Gestion du canvas
    function clearCanvas() {
        if (signaturePad) {
            signaturePad.clear();
        }
    }

// Gestion des fichiers
    function openFileDialog() {
        const fileInput = document.getElementById('file');
        
        if (!fileInputInitialized) {
            fileInput.addEventListener('change', handleFileChange);
            fileInputInitialized = true;
        }
        
        fileInput.click();
    }

    function handleFileChange(event) {
        const file = event.target.files[0];
        if (!file) return;

        const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        
        if (!validImageTypes.includes(file.type)) {
            showValidationErrors("Veuillez sélectionner un fichier image valide (JPEG, PNG).");
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const fileContent = e.target.result.split(',')[1];
            sendFileContent(fileContent, file.name, file.type);
        };
        reader.readAsDataURL(file);
    }

    function resetFileInput() {
        document.getElementById('file').value = '';
    }

    function showValidationErrors(errors) {
        Swal.fire({
            title: 'Erreurs de validation',
            html: `
                <div class="validation-errors">
                    <p class="mb-3 text-muted">
                        ${errors}
                    </p>
                    <div class="text-left">
                    
                    </div>
                </div>
            `,
            icon: 'error',
            confirmButtonText: 'Corriger les erreurs',
            confirmButtonColor: '#dc3545',
            width: '600px',
            customClass: {
                container: 'validation-modal'
            }
    });
    }

    function showSuccessMessage(message) {
        Swal.fire({
            title: 'Succès',
            html: `
                <div>
                    ${message}
                </div>
            `,
            icon: 'success',
            confirmButtonText: 'Continuer',
            confirmButtonColor: '#28a745',
            width: '600px',
            customClass: {
                container: 'success-modal'
            }
        });
    }

// Envoi des données
function sendFileContent(fileContent, fileName, fileType) {

     // Initialisation des éléments
        const target = $('#loader');
        $('.sendBtn').prop('disabled', true);
    // Créer un FormData pour l'envoi
    const formData = new FormData();
    formData.append('fileContent', fileContent);
    formData.append('fileName', fileName);
    formData.append('fileType', fileType);

    const spinner = new Spinner().spin(target[0]);
        const submitButton = document.getElementById('circle');
        if (submitButton) {
            submitButton.disabled = true;
        }

    // Requête AJAX
    $.ajax({
        url: 'formSignaturefileApprenti',
        type: 'POST',
        data: formData, // Utiliser formData créé ci-dessus
        processData: false,
        contentType: false,
        success: function(response) {
            console.log('Réponse serveur:', response);
            
            if (submitButton) {
                submitButton.disabled = false;
            }
            
            if (response.error) {
                showValidationErrors(response.message);
                if (response.debug) {
                    console.log('Debug info:', response.debug);
                }
                closeModal();
                closeModalSignatureSigne();
                setTimeout(function() {
                    // location.reload();
                }, 2500);
            } else {
                showSuccessMessage(response.message);
                showSuccessMessage("Merci pour votre collaboration. Nous reviendrons vers vous au plus vite");
                closeModal();
                closeModalSignatureSigne();
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        },
        error: function(xhr, status, error) {
            console.error('Erreur AJAX:', {xhr, status, error});
            
            if (submitButton) {
                submitButton.disabled = false;
            }
            showValidationErrors('Erreur de communication: ' + error);
            closeModal();
            closeModalSignatureSigne();
            setTimeout(function() {
                // location.reload();
            }, 2500);
        },
        complete: function() {
            if (typeof spinner !== 'undefined') {
                spinner.stop();
            }
            $('.sendBtn').prop('disabled', false);
        }
    });
}

    function sendData() {
        // Vérifications initiales
        if (!signaturePad || signaturePad.isEmpty()) {
            showValidationErrors("Veuillez dessiner votre signature avant de l'envoyer.");
            return false;
        }

        // Initialisation des éléments
        const target = $('#loader');
        $('.sendBtn').prop('disabled', true);
        
        // Récupération de la signature
        const imageURL = signaturePad.getDataURL();
        
        // Préparation des données
        const formData = new FormData();
        formData.append('signature', imageURL);
        
        // Initialisation du spinner et désactivation du bouton
        const spinner = new Spinner().spin(target[0]);
        const submitButton = document.getElementById('circle');
        if (submitButton) {
            submitButton.disabled = true;
        }
        
        // Requête AJAX
        $.ajax({
            url: 'formSignatureManuelleApprenti',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
            
                if (submitButton) {
                    submitButton.disabled = false;
                }
                
                if (response.error) {
                showValidationErrors(response.message);
                    closeModal();
                    closeModalSignatureSigne();
                    setTimeout(function() {
                      //  location.reload();
                    }, 2500);
                } else {
                    showSuccessMessage(response.message);
                    showSuccessMessage("Merci pour votre collaboration. Nous reviendrons vers vous au plus vite");
                    closeModal();
                    closeModalSignatureSigne();
                    setTimeout(function() {
                       location.reload();
                    }, 1000);
                }
            },
            error: function(xhr, status, error) {
                if (submitButton) {
                    submitButton.disabled = false;
                }
                showValidationErrors(error);
                closeModal();
                closeModalSignatureSigne();
                setTimeout(function() {
                   // location.reload();
                }, 2500);
            },
            complete: function() {
                spinner.stop();
                $('.sendBtn').prop('disabled', false); // Réactiver les boutons
            }
        });

        return false;
    }

// Chargement du document
        function loadDocument() {
            const url = document.getElementById("url").value;
            const documentContainer = document.getElementById("pdfViewer");
            const fileType = url.split('.').pop().toLowerCase();
            
            documentContainer.innerHTML = '';


            if (typeof pdfjsLib === 'undefined') {
                        throw new Error('PDF.js n\'est pas chargé. Vérifiez la connexion internet.');
                    }

            if (fileType === 'pdf') {
                // Simuler le chargement d'un PDF
                documentContainer.innerHTML = '<p>Chargement du PDF en cours...</p>';
                
                // Dans un vrai cas, vous utiliseriez PDF.js ici
                setTimeout(() => {
                    const loadingTask = pdfjsLib.getDocument(url);
                    loadingTask.promise.then(function(pdf) {
                    // Fetch all pages
                    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                        pdf.getPage(pageNum).then(function(page) {
                        const scale = 1.5;
                        const viewport = page.getViewport({scale: scale});
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                
                        // Append the canvas to the container
                    
                        documentContainer.appendChild(canvas);

                
                        // Render the page into the canvas context
                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        page.render(renderContext);
                        });
                        documentContainer.innerHTML = '';
                    }
                    }, function(reason) {
                    console.error(reason);
                    });
                }, 1000);

                
                
            } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType)) {
                const imageViewer = document.createElement('img');
                imageViewer.src = url;
                imageViewer.style.maxWidth = "100%";
                imageViewer.style.height = "auto";
                imageViewer.onerror = function() {
                    documentContainer.innerHTML = 'Erreur lors du chargement de l\'image.';
                };
                documentContainer.appendChild(imageViewer);
            } else {
                documentContainer.innerHTML = 'Type de fichier non supporté.';
            }
        }

        // Fermeture des modales en cliquant à l'extérieur
        window.addEventListener('click', function(event) {
            const modal1 = document.getElementById('myModal');
            const modal2 = document.getElementById('myModalSignature');
            
            if (event.target === modal1) {
                closeModal();
            } else if (event.target === modal2) {
                closeModalSignature();
            }
        });

        // Initialisation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            loadDocument();
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.min.js"></script>
</body>
</html>