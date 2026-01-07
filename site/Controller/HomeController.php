<?php
use Model\Form;

class HomeController {
    public function index() {
        // Logique du contrôleur pour la page d'accueil
        include 'Views/index.php';
    }

    public function alternance() {
        // Logique du contrôleur pour la page d'accueil
        include 'Views/alternance.php';
    }

    public function facturation() {
        // Logique du contrôleur pour la page d'accueil
        include 'Views/facturation.php';
    }

    public function tarifs() {
        // Logique du contrôleur pour la page d'accueil
        include 'Views/tarifs.php';
    }

    public function faq() {
        // Logique du contrôleur pour la page d'accueil
        include 'Views/faq.php';
    }

    public function contact() {
        // Logique du contrôleur pour la page d'accueil
        include 'Views/contact.php';
    }

    public function sendContact(){
        $name = $_POST['name'];
        $email  = $_POST['email'];
        $phone  = $_POST['phone'];
        $company  = $_POST['company'];
        $message  = $_POST['message'];
        $selectedDate  = $_POST['selected-date'];
        $selectedTime  = $_POST['selected-time'];
           


// Vérifier si la requête est en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}
else{

    // Assurez-vous que les données requises sont définies, par exemple, nom et prénom
    if (!empty( $name) && !empty($email) && !empty($phone) && !empty($selectedDate) && !empty($selectedTime)) {
        // Maintenant, appelez la méthode `sendData` en passant toutes les données
        $result = Form::sendData(
            $name, $email, $phone, $company, $message, $selectedDate, $selectedTime
        );

        if ($result['valid']) {
            echo json_encode(['success' => true, 'message' => 'Le formulaire a été soumis avec succès']);
        } else {
            echo json_encode(['success' => false, 'message' => $result['error']]);
        }

    } else {
        echo json_encode(['success' => false, 'message' => 'Veuillez remplir les champs obligatoires.']);
    }
   
}


    }

    public function sendNewLetter(){
       
    // Vérifier si la requête est en POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
        exit;
    }
    else{

        $email  = $_POST['emailletter'];
        // Assurez-vous que les données requises sont définies, par exemple, nom et prénom
        if (!empty($email) ) {
            $result = Form::sendNewLetter( $email);

           
            if ($result['valid']) {
                echo json_encode(['success' => true, 'message' => 'Le formulaire a été soumis avec succès']);
            } else {
                echo json_encode(['success' => false, 'message' => $result['error']]);
            }

        } else {
            echo json_encode(['success' => false, 'message' => 'Veuillez remplir les champs obligatoires.']);
        }
    
    }



    }

}
?>