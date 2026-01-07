<?php
use Model\Form;

class FormsController {
    public function index() {
        // Logique du contrôleur pour la page de connexion
        include 'Views/form.php';
    }

    
    public  function smic()
    {
        $url = 'https://entreprendre.service-public.fr/vosdroits/F2300';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        // if (curl_errno($ch)) {
        //     echo 'Erreur cURL : ' . curl_error($ch);
        //     exit;
        // }
        curl_close($ch);
        $dom = new DOMDocument;
        @$dom->loadHTML($response);
        $xpath = new DOMXPath($dom);
        $smicMensuelBrut = $xpath->query('//tr[th="Smic mensuel"]/td[1]/p/span[@class="sp-prix"]');
        if ($smicMensuelBrut->length > 0) {
            $montantBrutMensuel = $smicMensuelBrut->item(0)->textContent;
            return preg_replace('/[^\d,]/', '', $montantBrutMensuel);
        } else {
            return  1747.20;
        }
    }

    public function sendData() {
    // Récupérez toutes les données du formulaire
    
    $travailC = $_POST['travailC'];
    $modeC = $_POST['modeC'];
    $derogationC = $_POST['derogationC'];
    $numeroC = $_POST['numeroC'];
    $conclusionC = $_POST['conclusionC'];
    $debutC = $_POST['debutC'];
    $finC = $_POST['finC'];
    $avenantC = $_POST['avenantC'];
    $executionC = $_POST['executionC'];
    $dureC = $_POST['dureC'];
    $dureCM = $_POST['dureCM'];
    $typeC = $_POST['typeC'];
    $rdC = $_POST['rdC'];
    $raC = $_POST['raC'];
    $rpC = $_POST['rpC'];
    $rsC = $_POST['rsC'];
    $rdC1 = $_POST['rdC1'];
    $raC1 = $_POST['raC1'];
    $rpC1 = $_POST['rpC1'];
    $rsC1 = $_POST['rsC1'];

    $rdC2 = $_POST['rdC2'];
    $raC2 = $_POST['raC2'];
    $rpC2 = $_POST['rpC2'];
    $rsC2 = $_POST['rsC2'];

    $salaireC = $_POST['salaireC'];
    $caisseC = $_POST['caisseC'];
    $logementC = $_POST['logementC'];
    $avantageC = $_POST['avantageC'];
    $autreC = $_POST['autreC'];

    $lieuO = $_POST['lieuO'];
    $priveO = $_POST['priveO'];
    $attesteO = $_POST['attesteO'];





    $nomM = $_POST['nomM'];
    $prenomM = $_POST['prenomM'];
    $naissanceM = $_POST['naissanceM'];
    $securiteM = $_POST['securiteM'];
    $emailM = $_POST['emailM'];
    $emploiM = $_POST['emploiM'];
    $diplomeM = $_POST['diplomeM'];
    $niveauM = $_POST['niveauM'];

    $nomM1 = $_POST['nomM1'];
    $prenomM1 = $_POST['prenomM1'];
    $naissanceM1 = $_POST['naissanceM1'];
    $securiteM1 = $_POST['securiteM1'];
    $emailM1 = $_POST['emailM1'];
    $emploiM1 = $_POST['emploiM1'];
    $diplomeM1 = $_POST['diplomeM1'];
    $niveauM1 = $_POST['niveauM1'];

   

  

    // Assurez-vous que les données requises sont définies, par exemple, nom et prénom
    if (!empty($nomM) && !empty($modeC)) {
        // if(!empty($naissanceA) && empty($salaireC)){
        //     $smic = $this->smic();
        //     $smic = str_replace(',', '.', $smic);

        //     $dateAujourdhui = date("Y-m-d");

        //     $dateNaissanceObj = date_create($naissanceA);
        //     $dateAujourdhuiObj = date_create($dateAujourdhui);
        
        //     // Calcul de l'âge
        //     $diff = date_diff($dateNaissanceObj, $dateAujourdhuiObj);
        //     $age = $diff->y;
        
        //     if ($age < 18) {
        //         if(  $dateAujourdhui >= $rdC && $dateAujourdhui <= $raC){
        //             $salaireC = 0.27 * $smic;
        //             $rpC =27;
        //             $rpC1 ='';
        //             $rpC2 ='';
        //         }elseif($dateAujourdhui >= $rdC1 && $dateAujourdhui <= $raC1 ){
        //             $salaireC = 0.39 * $smic;
        //             $rpC1 =39;
        //             $rpC ='';
        //             $rpC2 ='';
        //         }elseif( $dateAujourdhui >= $rdC2 && $dateAujourdhui <= $raC2){
        //             $salaireC = 0.55 * $smic;
        //             $rpC2 =55;
        //             $rpC1 ='';
        //             $rpC ='';
        //         }else{
        //             $salaireC = 0.27 * $smic;
        //             $rpC =27;
        //             $rpC1 ='';
        //             $rpC2 ='';
        //         }
             
        //     } elseif ($age >= 18 && $age <= 20) {
        //         if(  $dateAujourdhui >= $rdC && $dateAujourdhui <= $raC){
        //             $salaireC = 0.43 * $smic;
        //             $rpC =43;
        //             $rpC1 ='';
        //             $rpC2 ='';
        //         }elseif($dateAujourdhui >= $rdC1 && $dateAujourdhui <= $raC1 ){
        //             $salaireC = 0.51 * $smic;
        //             $rpC1 =51;
        //             $rpC ='';
        //             $rpC2 ='';
        //         }elseif( $dateAujourdhui >= $rdC2 && $dateAujourdhui <= $raC2){
        //             $salaireC = 0.67 * $smic;
        //             $rpC2 =67;
        //             $rpC1 ='';
        //             $rpC ='';
        //         }else{
        //             $salaireC = 0.43 * $smic;
        //             $rpC =43;
        //             $rpC1 ='';
        //             $rpC2 ='';
        //         }   
             
                
        //     } elseif ($age >= 21 && $age <= 25) {

        //         if( $dateAujourdhui >= $rdC && $dateAujourdhui <= $raC){
        //             $salaireC = 0.53 * $smic;
        //             $rpC =53;
        //             $rpC1 ='';
        //             $rpC2 ='';
        //         }elseif($dateAujourdhui >= $rdC1 && $dateAujourdhui <= $raC1 ){
        //             $salaireC = 0.61 * $smic;
        //             $rpC1 =61;
        //             $rpC ='';
        //             $rpC2 ='';
        //         }elseif( $dateAujourdhui >= $rdC2 && $dateAujourdhui <= $raC2 ){
        //             $salaireC = 0.78 * $smic;
        //             $rpC2 =78;
        //             $rpC1 ='';
        //             $rpC ='';
        //         }else{
        //             $rpC =53;
        //             $salaireC = 0.53 * $smic;
        //             $rpC1 ='';
        //             $rpC2 ='';
        //         }
               
        //     } else {
        //         $salaireC = $smic;
        //         $rpC =100;
        //         $rpC1 =100;
        //         $rpC2 =100;
        //     }
        // }

        // Maintenant, appelez la méthode `sendData` en passant toutes les données
       
       
       
        $result = Form::sendData(
            $nomM,$prenomM,$naissanceM,$securiteM,$emailM,$emploiM,$diplomeM,$niveauM, 
            $nomM1,$prenomM1,$naissanceM1,$securiteM1,
            $emailM1,
            $emploiM1,$diplomeM1,$niveauM1, 
            $travailC,$derogationC,$numeroC,$conclusionC,
            $debutC,$finC,$avenantC,$executionC,$dureC,
            $typeC,
            $rdC,$raC,$rpC,$rsC,
            $rdC1,$raC1,$rpC1,$rsC1,
            $rdC2,$raC2,$rpC2,$rsC2,
            $salaireC,$caisseC,$logementC,$avantageC,
            $autreC,
            $lieuO,$priveO,$attesteO,$modeC,$dureCM
        );

        $response = [];

        if ($result['valid']) {
            $response['status'] = 'success';
            $response['message'] = "Le formulaire a été soumis avec succès";
        } else {
            $response['status'] = 'error';
            $response['message'] = $result['error'];
        }
        

        echo json_encode($response);
    } else {
        $response = [];
        $response['status'] = 'error';
        $response['message'] = "Veuillez remplir les champs obligatoires.";
        echo json_encode($response);
    }
}

}
?>