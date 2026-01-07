<?php


namespace Projet\Controller\Page;



use Projet\Database\Profil;
use Projet\Model\App;


class HomeController extends PageController{
    
    public function index(){
        $this->check();
        $this->render('page.home.index');
    }

    public function error(){
        $this->session->write('danger',"You are requesting a resource that does not exist");
        $this->render('page.home.error');
    }

    public function unauthorize(){
        $this->session->write('danger',"You do not have permission to access this resource");
        $this->render('page.home.unauthorize');
    }

    public function logout(){
        $_SESSION = array();
        if(App::getDBAuth()->signOut()){
            App::redirect(App::url(""));
        }
    }

    public function log(){
        $return = '';
        header('Content-Type: text/plain');
        if (isset($_POST['login']) && isset($_POST['password'])) {
            $login = $_POST['login'];
            $password = $_POST['password'];
            if (!empty($login) && !empty($password)) {
                if(strlen($password) >= 12){
                $conMessage = App::getDBAuth()->login($login, $password);
                if (is_bool($conMessage)) {
                    $lastUrl = empty($this->session->read('lastUrlAsked')) ? App::url('home') : $this->session->read('lastUrlAsked');
                    $this->session->delete('lastUrlAsked');
                    $return = $lastUrl;
                } else {
                    $return = $conMessage;
                }

               }else{
                $message = "Veuillez remplir au moins 12 caratères sur le mot de passe";
                $return = $message;
               }
            } else {
                $message = "Please fill in all required fields";
                $return = $message;
            }
        } else {
            $message = "Invalid request"; 
            $return = $message;
        }
        echo $return;
    }
    

    public function resetPasswordSend(){
        header('content-type: application/json');
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
            if (!empty($email)) {
                $save = Profil::resetPasswordSend($email);
                if($save['valid']){
                    $message = $save['data'];
                    $this->session->write('success', $message);
                    $return = array("statuts" => 0, "mes" => $message);
                }else{
                    $message = $save['error'];
                    $return = array("statuts" => 1, "mes" => $message);
                }
            } else {
                $message = "Veuillez remplir correctement tous les champs requis";
                $return = array("statuts" => 1, "mes" => $message);
            }
        } else {
            $message = "Veuillez remplir correctement tous les champs requis"; 
            $return = array("statuts" => 1, "mes" => $message);
        }
        echo json_encode($return);
    }

    public function resetPassword(){
        header('content-type: application/json');
    
        if (isset($_POST['newPassword']) && isset($_POST['confirmPassword']) && isset($_POST['token'])) {
            $newPassword = $_POST['newPassword'];
            $confirmPassword = $_POST['confirmPassword'];
            $token = $_POST['token'];
    
            if (!empty($newPassword) && !empty($confirmPassword) && !empty($token)) {
    
                if ($newPassword == $confirmPassword) {
                    // Vérification des règles de sécurité du mot de passe
                    if ($this->validatePassword($newPassword)) {
                        // Sauvegarde du nouveau mot de passe
                        $save = Profil::resetPassword($newPassword, $confirmPassword, $token);
                        if ($save['valid']) {
                            $message = $save['data'];
                            $this->session->write('success', $message);
                            $return = array("statuts" => 0, "mes" => $message);
                        } else {
                            $message = $save['error'];
                            $return = array("statuts" => 1, "mes" => $message);
                        }
                    } else {
                        $message = "Le mot de passe doit contenir au moins 12 caractères, une majuscule, un chiffre et un caractère spécial.";
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                } else {
                    $message = "Le mot de passe et la confirmation doivent être identiques.";
                    $return = array("statuts" => 1, "mes" => $message);
                }
            } else {
                $message = "Veuillez remplir correctement tous les champs requis.";
                $return = array("statuts" => 1, "mes" => $message);
            }
        } else {
            $message = "Veuillez remplir correctement tous les champs requis.";
            $return = array("statuts" => 1, "mes" => $message);
        }
    
        echo json_encode($return);
    }
    
    // Fonction pour valider les règles du mot de passe
    private function validatePassword($password) {
        // Mot de passe d'au moins 12 caractères, avec 1 majuscule, 1 chiffre, et 1 caractère spécial
        return strlen($password) >= 12 &&
               preg_match('/[A-Z]/', $password) &&    // Au moins une majuscule
               preg_match('/[0-9]/', $password) &&    // Au moins un chiffre
               preg_match('/[\W_]/', $password);      // Au moins un caractère spécial
    }

    
}