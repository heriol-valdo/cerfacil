<?php
use Model\Form;

class FormsController {
    public function index() {
        // Logique du contrôleur pour la page de connexion
        include 'Views/form.php';
    }

    public function sendData() {
    // Récupérez toutes les données du formulaire
    
    $typeE = $_POST['typeE'];
    $specifiqueE = $_POST['specifiqueE'];
    $totalE = $_POST['totalE'];
    $siretE = $_POST['siretE'];
    $codeaE = $_POST['codeaE'];
    $codeiE = $_POST['codeiE'];
    $rueE = $_POST['rueE'];
    $voieE = $_POST['voieE'];
    $complementE = $_POST['complementE'];
    $postalE = $_POST['postalE'];
    $communeE = $_POST['communeE'];
    $numeroE = $_POST['numeroE'];

   

  

    // Assurez-vous que les données requises sont définies, par exemple, nom et prénom
    if (!empty($typeE) && !empty($specifiqueE)) {
        // Maintenant, appelez la méthode `sendData` en passant toutes les données
        $result = Form::sendData(
             $typeE, $specifiqueE, $totalE, $siretE, $codeaE, 
             $codeiE, $rueE, $voieE, $complementE, $postalE, 
             $communeE,  $numeroE
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