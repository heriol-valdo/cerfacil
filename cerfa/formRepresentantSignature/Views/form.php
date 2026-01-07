<?php
use Model\Form;
require_once('./vendor/tecnickcom/tcpdf/tcpdf.php');



$encodedData = isset($_GET['data']) ? $_GET['data'] :0;

$decodedData = json_decode(base64_decode(urldecode($encodedData)), true);

$id =$decodedData;


if(isset($id)) {
    $cerfas = Form::getCerfa($id);

    if(isset($cerfas['data']->idemployeur)) {
        $ligneemployeur = Form::getEmployeur($cerfas['data']->idemployeur);
    }

    if(isset($cerfas['data']->idformation)) {
        $ligneformation = Form::getFormation($cerfas['data']->idformation);
    }

    if($cerfas['valid'] && $ligneemployeur['valid'] && $ligneformation['valid']) {
        $name = createpdf($id, $cerfas, $ligneemployeur, $ligneformation);
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
    
    $cerfas = $cerfas['data'];
    $ligneemployeur =$ligneemployeur['data'];
    $ligneformation = $ligneformation['data'];
    
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

    if(!empty($cerfas->signatureEcole)){
        $imageUrl =$cerfas->signatureEcole;
        $imagePath = tempnam(sys_get_temp_dir(), 'image2_');
        file_put_contents($imagePath, file_get_contents($imageUrl));
        $pdf->Image($imagePath, 15, 208, 50, 22, '', '', '', false, 200, '', false, false, 0);
    }
   

    if(!empty($cerfas->signatureRepresentantApprenti)){
        $imageUrl =$cerfas->signatureRepresentantApprenti;
        $imagePath = tempnam(sys_get_temp_dir(), 'image3_');
        file_put_contents($imagePath, file_get_contents($imageUrl));
        $pdf->Image($imageUrl, 135, 246, 20, 8, '', '', '', false, 150, '', false, false, 0);
    }

    
    
    
    // Générez le contenu PDF
    ob_start();
    $name= $id.'cerfa_document.pdf';
    $pdfFilePath = __DIR__ .'./../Public/assets/pdf/'.$name;
    $pdf->Output($pdfFilePath, 'F');
    return $name;
   }catch(Exception $e){
    return $e->getMessage();
   }
}



?>
<!Doctype html>
<html lang="fr">
<head>
<link rel="icon" type="image/png" href="./Public/img/favicon.png" >
<script src="./Public/assets/jquery/jquery.min.js" type="text/javascript"></script>
<script src="./Public/assets/jquery/toastr/toastr.js" type="text/javascript"></script>
<meta charset="utf-8">
<link href="./Public/css/font-awesome/materiel/materielindigo.min.css?ver=1.3.0" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="./Public/css/font-awesome-4.7.0/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="./Public/assets/bootstrap/css/bootstrap.css">
<link href="./Public/assets/bootstrap/css/bootstrap.min.css?ver=1.2.0" rel="stylesheet">
<link href="./Public/assets/jquery/toastr/toastr.min.css" rel="stylesheet">
<script src="./Public/js/form.js" type="text/javascript"></script>
<script src="./Public/js/waitMe.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>



<link rel="stylesheet" type="text/css" href="./Public/css/form.css">


<title>CerFacil-FORM</title>
</head>

<body onload="load()">
<main class="bg-white"  > 
	<div>
		<div>
			<figure>
				<img src="./Public/img/lgxlogo.png" alt="icon entreprise LGX" class="imagestruct">
			</figure>
		</div>
        <div>
			<h2 class="imagestructs">CerFacil</h2>
		</div>
        <div>
			<p style=" margin-top: 20px;"><h6 
		   style="font-style: oblique; font-weight: normal;"class="text-center ">Signer le cerfa pour qu'il soit valider par l'opco</h6></p>
           <button type="submit" onclick="return ModalOpen();" class="sendBtn btn btn-lg btn-rounded text-center" name="submit_form" <?php if (!empty($cerfas['data']->signatureRepresentantApprenti)) echo 'disabled'; ?>>
             Signer
            </button>

        </div>

     
        <div id="pdfViewer" ></div>
        <input type="hidden"  value="https://cerfa.heriolvaldo.com/cerfa/formRepresentantSignature/Public/assets/pdf/<?=$name?>" id="url">
        <input type="file" id="file" name="file" style="display:none" accept=".jpg, .jpeg, .png"/>
        <div id="loader"></div>

        

        <div id="myModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                <span class="close" onclick="closeModal()" >&times; fermer</span>
               
                </div>
                <div class="modal-body">
                <!-- Contenu de la modale -->
                <h2>Choisir une option</h2>
                <div class="buttons-vertical">
                    <button id="button1" onclick="ModalOpenSignature()" class="sendBtn btn  btn-lg btn-rounded  text-center">Manuellement </button>
                    <button id="button2" onclick="file()" class="sendBtn btn  btn-lg btn-rounded  text-center">Importer un fichier</button>
                </div>
                </div>
            </div>
        </div>
        <div id="myModalSignature" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                <span class="close" onclick="closeModalSignature()" >&times; Retour</span>
               
                </div>
                <div class="modal-body">
                <!-- Contenu de la modale -->
                <h2>Signer Manuellement </h2>
                    <div>
                        <form  onsubmit="return sendData();" method="POST"  id="myForm">
                        <div class="row">            
                            <div class="col-md-6">
                                <div class="form-group">   
                                    <canvas id="signaturepad"></canvas>
                                </div>
                            </div>    
                        </div>
                        <div class="row"> 
                            <div class="button-container">
                                <button type="submit" id="circle" class="sendBtn1 btn btn-lg btn-rounded text-center" name="submit_form">Envoyer</button>
                                <button type="button" id="clear" class="sendBtn1 btn btn-lg btn-rounded text-center" onclick="clearCanvas()">Effacer</button>
                            </div>
                        </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
	</div>
</main>






<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js"></script>

<script src="./Public/assets/bootstrap/js/bootstrap.bundle.min.js?ver=1.2.0"></script>
<script src="./Public/assets/bootstrap/js/bootstrap.bundle.js?ver=1.2.0"></script>
<script src="./Public/assets/bootstrap/js/bootstrap.js?ver=1.2.0"></script>
<script src="./Public/assets/bootstrap/js/bootstrap.min.js?ver=1.2.0"></script>
<script src="./Public/assets/bootstrap/js/bootstrap.esm.js?ver=1.2.0"></script>
<script src="./Public/assets/bootstrap/js/bootstrap.esm.min.js?ver=1.2.0"></script>
</body>
</html>

                   
                   