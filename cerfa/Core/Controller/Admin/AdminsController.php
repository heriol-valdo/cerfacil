<?php


namespace Projet\Controller\Admin;


use DateTime;
use Exception;

use Projet\Database\Profil;

use Projet\Model\App;
use Projet\Model\DataHelper;
use Projet\Model\Email;
use Projet\Model\EmailAll;
use Projet\Model\FileHelper;
use Projet\Model\Privilege;
use Projet\Database\Entreprise;
use Projet\Database\Formation;
use Projet\Database\Opco;
use Projet\Database\Alternant;
use Projet\Database\Cerfa;


class AdminsController extends AdminController{

    public function index(){
        $user = $this->user;
        //Privilege::hasPrivilege(Privilege::$AllView,$user->privilege);
        $nbreParPage = 20;
       
        // nouveau
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $search = isset($_POST['searchadmins']) && !empty($_POST['searchadmins']) ? $_POST['searchadmins'] : null;
            $this->session->write('searchadmins', $search); // Enregistrer la recherche dans la session
            
            // Récupérer la page envoyée et l'enregistrer dans la session
            $pageCourante = isset($_POST['page']) && is_numeric($_POST['page']) ? (int)$_POST['page'] : 1;
            $this->session->write('pageCouranteadmins', $pageCourante);
            
            // Rediriger pour éviter la soumission multiple de formulaire
            header("Location: " . App::url('admins'));
            exit;
        }
        
        // Vérifier si une recherche est en session
        $search = $this->session->exists('searchadmins') ? $this->session->read('searchadmins') : null;
        
        
        
        // Vérifier si la page courante est en session
        $pageCourante = $this->session->exists('pageCouranteadmins') ? $this->session->read('pageCouranteadmins') : 1;
       
        $nbre = Profil::countBySearchType($search);
        $nbrePages = ceil($nbre / $nbreParPage);
        

        if($user->data->role == 'ClientCERFA' || $user->data->role == 'Gestionnaire de centre'){
            if($user->data->role == 'ClientCERFA'){
                if($user->data->roleCreation ==1 ){
                    $profils = Profil::searchType($nbreParPage,$pageCourante,$search);
                    $this->render('admin.admin.index',compact('search','profils','user','nbre','nbrePages','pageCourante'));
                }else{
                    $nbreadmins = Profil::countBySearchType($search);
                    $nbreformations = Formation::countBySearchType($search);
                    $nbreemployeurs = Entreprise::countBySearchType($search);
                    $nbreopco = Opco::countBySearchType($search);
                    $nbrecerfas = Cerfa::countBySearchType($search);
                    $nbreproduit = Produit::searchType();
                    $produits = $nbreproduit['data'];
                    $nbreproduits =  count($produits);
                    $_SESSION['page_active'] = 'home';
                    $current = date(DATE_FORMAT);
                    $this->render('admin.user.index',compact('user','current','nbreadmins','nbrecerfas','nbreemployeurs','nbreformations','nbreopco','nbreproduits'));

                }

            }else{
                $nbreadmins = Profil::countBySearchType($search);
                $nbreformations = Formation::countBySearchType($search);
                $nbreemployeurs = Entreprise::countBySearchType($search);
                $nbreopco = Opco::countBySearchType($search);
                $nbrecerfas = Cerfa::countBySearchType($search);
                $nbreproduit = Produit::searchType();
                $produits = $nbreproduit['data'];
                $nbreproduits =  count($produits);
                $_SESSION['page_active'] = 'home';
                $current = date(DATE_FORMAT);
                $this->render('admin.user.index',compact('user','current','nbreadmins','nbrecerfas','nbreemployeurs','nbreformations','nbreopco','nbreproduits'));
            }
           
        }else{
            $nbreadmins = Profil::countBySearchType($search);
            $nbrealternants = Alternant::countBySearchType();
            $nbreformations = Formation::countBySearchType($search);
            $nbreemployeurs = Entreprise::countBySearchType($search);
            $nbreopco = Opco::countBySearchType($search);
            $nbrecerfas = Cerfa::countBySearchType($search);
            $_SESSION['page_active'] = 'home';
            $current = date(DATE_FORMAT);
            $this->render('admin.user.index',compact('user','current','nbreadmins','nbrealternants','nbrecerfas','nbreemployeurs','nbreformations','nbreopco'));
           
        }
       
    }

    public static function url($url){
        return 'https://lgx-solution.fr/cerfa/'.$url;
    }

    public function save(){
       
        header('content-type: application/json');
        $return = [];
        $tab = ["add", "edit"];
        $user = $this->user;
        if (isset($_POST['nom']) && !empty($_POST['nom']) &&isset($_POST['prenom']) && !empty($_POST['prenom'])
            &&isset($_POST['email'])&& isset($_POST['action']) && !empty($_POST['action']) && isset($_POST['id']) 
            && in_array($_POST["action"], $tab)) {
            $nom = trim($_POST['nom']);
            $prenom = trim($_POST['prenom']);
            $email = trim($_POST['email']);

            $adresse = trim($_POST['adresse']);
            $postal = trim($_POST['postal']);
            $ville = trim($_POST['ville']);
            $telephone = trim($_POST['telephone']);


            $action = $_POST['action'];
            $id = (int)$_POST['id'];
          
                if($action == "edit") {
                    if (!empty($id)){
                      
                        try{
                            $save = Profil::edit($nom,$prenom,$email,$adresse,$postal,$ville,$telephone,$id);
                            if($save['valid']){
                                $message = "L'administrateur a été mis à jour avec succès";
                                $this->session->write('success',$message);
                                $return = array("statuts" => 0, "mes" => $message);
                            }else{
                                $message =$save['error'];
                                $return = array("statuts" => 1, "mes" => $message);
                            }
                           
                        }catch (Exception $e){
                            $message =$e->getMessage();
                            $return = array("statuts" => 1, "mes" => $message);
                        }
                                
                            
                       
                    } else {
                        $message = $this->error;
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                } else {
                    try{
                        $motDePasseGenere = $this->genererMotDePasses();
                        $save = Profil::save($nom,$prenom,$email,$adresse,$postal,$ville,$telephone,$motDePasseGenere,$user->data->id_users);                      
                        if($save['valid']){
                            $message = "L'administrateur a été ajouté avec succès";
                            $this->session->write('success',$message);
                            $return = array("statuts" => 0, "mes" => $message);
                        }else{
                            $message =$save['error'];
                            $return = array("statuts" => 1, "mes" => $message);
                        }
                    }catch (Exception $e){
                        $message = $e->getMessage();
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                
                }
                
            
        } else {
            $message = "Veiullez renseigner tous les champs requis";
            $return = array("statuts" => 1, "mes" => $message);
        }
        echo json_encode($return);
    }

  
   

    public function genererMotDePasse($longueur = 12) {
        // Caractères autorisés
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@';
    
        // Mélanger les caractères
        $melange = str_shuffle($caracteres);
    
        // Extraire la portion souhaitée
        $motDePasse = substr($melange, 0, $longueur);
    
        return $motDePasse;
    }

    function genererMotDePasses() {
        // Définition des jeux de caractères
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $specialChars = '@#$!';
        $length = 20;

        // Combinaison de tous les jeux de caractères
        $allChars = $lowercase . $uppercase . $numbers . $specialChars;

        // S'assurer que le mot de passe contiendra au moins un de chaque type requis
        $password = [];
        $password[] = $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password[] = $numbers[random_int(0, strlen($numbers) - 1)];
        $password[] = $specialChars[random_int(0, strlen($specialChars) - 1)];

        // Remplir le reste du mot de passe
        for ($i = 3; $i < $length; $i++) {
            $password[] = $allChars[random_int(0, strlen($allChars) - 1)];
        }

        // Mélanger le tableau de caractères pour plus de sécurité
        shuffle($password);

        // Retourner le mot de passe en tant que chaîne de caractères
        return implode('', $password);

    }

    public function reset(){
        Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        header('content-type: application/json');
        $return = [];
        if(isset($_POST['id']) && !empty($_POST['id'])){
            $id = $_POST['id'];
            $profil = Profil::find($id);
            if ($profil){
                $pdo = App::getDb()->getPDO();
                try{
                    $pdo->beginTransaction();

                    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@';
    
                        // Mélanger les caractères
                    $melange = str_shuffle($caracteres);
                    
                        // Extraire la portion souhaitée
                    $code= substr($melange, 0, rand(12, 15));
                   
                    
                    Profil::setPassword(sha1($code),$id);
                    if(!empty($profil->email)){
                        Email::sendEmailUser($profil->email, $profil->nom,$code);
                    }
                    $message = "Le mot de passe a été changé avec succès";
                    $this->session->write('success',$message);
                    $pdo->commit();
                    $return = array("statuts" => 0, "mes" => $message);
                }catch (Exception $e){
                    $pdo->rollBack();
                    $message = $this->error;
                    $return = array("statuts" => 1, "mes" => $message);
                }
            }else{
                $message = $this->error;
                $return = array("statuts" => 1, "mes" => $message);
            }
        }else{
            $message = $this->error;
            $return = array("statuts" => 1, "mes" => $message);
        }
        echo json_encode($return);
    }

    
    

    public function activate(){
        Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        header('content-type: application/json');
        $return = [];
        if(isset($_POST['id']) && !empty($_POST['id'])&&isset($_POST['etat']) && in_array($_POST['etat'],[0,1,2])){
            $id = $_POST['id'];
            $etat = $_POST['etat'];
            $profil = Profil::find($id);
            if($profil){
                $pdo = App::getDb()->getPDO();
                try{
                    $pdo->beginTransaction();
                    Profil::setEtat($etat,$id);
                    if($etat==1){
                        if(!empty($profil->email)){
                            Email::sendEmailUserActive($profil->email, $profil->nom) ;
                        }
                    }else{
                        if(!empty($profil->email)){
                            Email::sendEmailUserDesactive($profil->email, $profil->nom) ;
                        }

                       
                    }
                    $message = "L'opération s'est passée avec succès";
                    $this->session->write('success',$message);
                    $pdo->commit();
                    $return = array("statuts" => 0, "mes" => $message);
                }catch (Exception $e){
                    $pdo->rollBack();
                    $message = $this->error;
                    $return = array("statuts" => 1, "mes" => $message);
                }
            }else{
                $message = $this->error;
                $return = array("statuts" => 1, "mes" => $message);
            }
        }else{
            $message = $this->error;
            $return = array("statuts" => 1, "mes" => $message);
        }
        echo json_encode($return);
    }

    public function setPhoto() {
        // Assurez-vous que l'en-tête de la réponse est défini comme texte
        header('Content-Type: text/plain');
    
        try {
            Privilege::hasPrivilege(Privilege::$AllView, $this->user->privilege);
    
            if (isset($_FILES['image']['name'])) {
                $id = DataHelper::post('idPhoto');
                
                if (isset($_FILES['image']['tmp_name']) && !empty($_FILES['image']['tmp_name']) && !is_null($id)) {
                    if ($_FILES['image']['error'] == 0) {
                        $extensions_valides = array('jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG');
                        $extension_upload = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1));
                        
                        if (in_array($extension_upload, $extensions_valides)) {
                            if ($_FILES['image']['size'] <= 2000000) {
                                $profil = Profil::find($id);
                                if ($profil) {
                                    $pdo = App::getDb()->getPDO();
                                    $pdo->beginTransaction();
                                    
                                    $root = FileHelper::moveImage($_FILES['image']['tmp_name'], "identity", "png", "", true);
    
                                    if (!empty($profil->photo) && strpos($profil->photo, 'images') === false) {
                                        FileHelper::deleteImage($profil->photo);
                                    }
    
                                    if ($root) {
                                        Profil::setPhoto($root, $id);
                                        $success = "La photo a été mise à jour avec Succès";
                                        $this->session->write('success', $success);
                                        $pdo->commit();
                                        echo $success; // Renvoie un message de succès au format texte
                                    } else {
                                        $erreur = $this->error;
                                        echo $erreur; // Renvoie un message d'erreur au format texte
                                    }
                                } else {
                                    $erreur = 'Une erreur est survenue, rechargez et réessayez';
                                    echo $erreur; // Renvoie un message d'erreur au format texte
                                }
                            } else {
                                $erreur = 'Le fichier doit avoir une taille inférieure à 2M';
                                echo $erreur; // Renvoie un message d'erreur au format texte
                            }
                        } else {
                            $message = "Le fichier doit être une image";
                            echo $message; // Renvoie un message d'erreur au format texte
                        }
                    } else {
                        $message = "Une erreur est survenue lors de l'envoi du fichier";
                        echo $message; // Renvoie un message d'erreur au format texte
                    }
                } else {
                    $message = "Vous devez télécharger un fichier";
                    echo $message; // Renvoie un message d'erreur au format texte
                }
            } else {
                $message = "Une erreur est survenue";
                echo $message; // Renvoie un message d'erreur au format texte
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            $erreur = "Une erreur est survenue lors du traitement de la requête";
            echo $erreur; // Renvoie un message d'erreur au format texte
        }
    }

    public function delete(){
        header('content-type: application/json');
        if (isset($_POST['id'])&&!empty($_POST['id'])){
            $id = $_POST['id'];
                try{
                    $save = Profil::delete($id);
                    if($save['valid']){
                        $message = "L'administrateur a été supprimée avec succès";
                        $this->session->write('success',$message);
                        $return = array("statuts"=>0, "mes"=>$message);
                    }else{
                        $message = $save['error'];
                        $return = array("statuts"=>1, "mes"=>$message);
                    }
    
                }catch (Exception $e){
                    $message =$e->getMessage();
                    $return = array("statuts" => 1, "mes" => $message);
                }
               
            
        }else{
            $message = "Renseigner l'id SVP !!!";
            $return = array("statuts"=>1, "mes"=>$message);
        }
        echo json_encode($return);
    }
    

}