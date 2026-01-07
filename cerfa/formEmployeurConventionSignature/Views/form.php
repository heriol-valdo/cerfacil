<?php
use Model\Form;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;
require_once('./vendor/tecnickcom/tcpdf/tcpdf.php');
require_once('./vendor/setasign/fpdi/src/Fpdi.php');



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

function getImageType($filename) {
    $info = getimagesize($filename);
    if ($info === false) {
        return false;
    }
    $mime = $info['mime'];
    switch ($mime) {
        case 'image/jpeg':
            return 'JPG';
        case 'image/png':
            return 'PNG';
        case 'image/gif':
            return 'GIF';
        default:
            return false;
    }
}
function createpdf($id,$cerfas,$ligneemployeur,$ligneformation ){

    try{

        $cerfas = $cerfas['data'];
        $ligneemployeur =$ligneemployeur['data'];
        $ligneformation = $ligneformation['data'];
        
        $pdf = new Fpdi();

        $pdfUrl = $cerfas->conventionOpco;
        $urlSegments = explode('/', $pdfUrl);
        $relativePath = implode('/', array_slice($urlSegments, 7));
        $basePath = $_SERVER['DOCUMENT_ROOT'] . '/cerfa/public/assets/conventionOpco/';
        $filePath =  $basePath.$relativePath;
                    
        // Ajoute une page du PDF existant
        $pageCount = $pdf->setSourceFile(StreamReader::createByFile($filePath));
    
    // Paramètres de page
   
    for ($i = 1; $i <= $pageCount; $i++) {
        $tplIdx = $pdf->importPage($i);
        $pdf->addPage();
        $pdf->useTemplate($tplIdx, 0, 0);

        // Définir la police
        $pdf->SetFont('helvetica', '', 12);

        // Ajouter du texte spécifique à chaque page
        switch ($i) {
            case 2:
                if($pageCount == 3){}else{
                    if(!empty($cerfas->signatureConventionEcole)){
                        $imageUrl =$cerfas->signatureConventionEcole;
                        $imagePath = tempnam(sys_get_temp_dir(), 'image2_');
                        file_put_contents($imagePath, file_get_contents($imageUrl));
                        $pdf->Image($imageUrl, 120.5, 220, 50, 22, '', '', '', false, 150, '', false, false, 0);
                    }


                if(!empty($cerfas->signatureConventionEmployeur)){
                    $imageUrl =$cerfas->signatureConventionEmployeur;
                    $imagePath = tempnam(sys_get_temp_dir(), 'image3_');
                    file_put_contents($imagePath, file_get_contents($imageUrl));
                    $pdf->Image($imageUrl, 20, 220, 50, 22, '', '', '', false, 150, '', false, false, 0);
                }

               
                }

                
                
                break;

                case 3:

                    if(!empty($cerfas->signatureConventionEcole)){
                        $imageUrl =$cerfas->signatureConventionEcole;
                        $imagePath = tempnam(sys_get_temp_dir(), 'image2_');
                        file_put_contents($imagePath, file_get_contents($imageUrl));
                        $pdf->Image($imageUrl, 120.5, 130, 50, 22, '', '', '', false, 150, '', false, false, 0);
                    }
    
                  

                    if(!empty($cerfas->signatureConventionEmployeur)){
                        $imageUrl =$cerfas->signatureConventionEmployeur;
                        $imagePath = tempnam(sys_get_temp_dir(), 'image3_');
                        file_put_contents($imagePath, file_get_contents($imageUrl));
                        $pdf->Image($imageUrl, 20, 130, 50, 22, '', '', '', false, 150, '', false, false, 0);
                    }

                break;
            
           
          
            default:
                break;
        }
    }

   

    
    
    
    // Générez le contenu PDF
    ob_start();
    $name= $id.'convention_document.pdf';
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
				<img src="./Public/img/lgxlogo.png" alt="icon entreprise Cerfacil" class="imagestruct">
			</figure>
		</div>
        <div>
			<h2 class="imagestructs">CerFacil</h2>
		</div>
        <div>
			<p style=" margin-top: 20px;"><h6 
		   style="font-style: oblique; font-weight: normal;"class="text-center ">Signer la convention pour qu'il soit valider par l'opco</h6></p>
           <button type="submit" onclick="return ModalOpen();" class="sendBtn btn btn-lg btn-rounded text-center" name="submit_form" <?php if (!empty($cerfas['data']->signatureConventionEmployeur)) echo 'disabled'; ?>>
             Signer
            </button>

        </div>

     
        <div id="pdfViewer" ></div>
        <input type="hidden"  value="https://cerfa.heriolvaldo.com/cerfa/formEmployeurConventionSignature/Public/assets/pdf/<?=$name?>" id="url">
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

                   
                   