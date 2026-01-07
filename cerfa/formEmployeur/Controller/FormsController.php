<?php
use Model\Form;

class FormsController {
    public function index() {
        // Logique du contrôleur pour la page de connexion
        include 'Views/form.php';
    }

    public function sendData() {
    // Récupérez toutes les données du formulaire
    $nomA = $_POST['nomA'];
    $nomuA = $_POST['nomuA'];
    $prenomA = $_POST['prenomA'];
    $sexeA = $_POST['sexeA'];
    $naissanceA = $_POST['naissanceA'];
    $departementA = $_POST['departementA'];
    $communeNA = $_POST['communeNA'];
    $nationaliteA = $_POST['nationaliteA'];
    $regimeA = $_POST['regimeA'];
    $situationA = $_POST['situationA'];
    $titrePA = $_POST['titrePA'];
    $derniereCA = $_POST['derniereCA'];
    $securiteA = $_POST['securiteA'];
    $intituleA = $_POST['intituleA'];
    $titreOA = $_POST['titreOA'];
    $declareSA = $_POST['declareSA'];
    $declareHA = $_POST['declareHA'];
    $declareRA = $_POST['declareRA'];
    $rueA = $_POST['rueA'];
    $voieA = $_POST['voieA'];
    $complementA = $_POST['complementA'];
    $postalA = $_POST['postalA'];
    $communeA = $_POST['communeA'];
    $numeroA = $_POST['numeroA'];

    $nomR = $_POST['nomR'];
    $prenomR = $_POST['prenomR'];
    $emailR = $_POST['emailR'];
    $rueR = $_POST['rueR'];
    $voieR = $_POST['voieR'];
    $complementR = $_POST['complementR'];
    $postalR = $_POST['postalR'];
    $communeR = $_POST['communeR'];

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
    if (!empty($nomA) && !empty($prenomA)) {
        // Maintenant, appelez la méthode `sendData` en passant toutes les données
        $result = Form::sendData(
            $nomA, $nomuA, $prenomA, $sexeA, $naissanceA, $departementA, $communeNA, $nationaliteA, $regimeA, 
            $situationA, $titrePA, $derniereCA, $securiteA, $intituleA, $titreOA, $declareSA, $declareHA, $declareRA,
            $rueA, $voieA, $complementA, $postalA, $communeA, $numeroA, $nomR,$prenomR, $emailR, $rueR, $voieR, 
            $complementR, $postalR, $communeR,
            $nomM,  $prenomM,$naissanceM ,$securiteM, $emailM, $emploiM, $diplomeM, $niveauM,
            $nomM1,  $prenomM1,$naissanceM1 ,$securiteM1, $emailM1, $emploiM1, $diplomeM1, $niveauM1
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