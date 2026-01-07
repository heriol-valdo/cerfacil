<?php
/**
 * Created by PhpStorm.
 * Eleve: Ndjeunou
 * Date: 23/01/2017
 * Time: 09:19
 */

namespace Projet\Controller\Admin;


use DateTime;
use Exception;
use Projet\Database\affiliate_project;
use Projet\Database\affiliate_user;
use Projet\Database\Alternant;

use Projet\Database\category;
use Projet\Database\Cerfa;
use Projet\Database\checkout_orders;
use Projet\Database\Contact;
use Projet\Database\council;
use Projet\Database\customer_project;
use Projet\Database\Entreprise;
use Projet\Database\Formation;
use Projet\Database\Opco;
use Projet\Database\orders;
use Projet\Database\products;
use Projet\Database\Profile;
use Projet\Database\project_payment;
use Projet\Database\Question;
use Projet\Database\schedule_meeting;
use Projet\Database\subcategory;
use Projet\Database\Profil;
use Projet\Database\Test;
use Projet\Database\users;
use Projet\Database\Visite;
use Projet\Database\withdraw_request;
use Projet\Model\App;
use Projet\Model\DateParser;
use Projet\Model\Privilege;
use Projet\Model\StringHelper;
use Projet\Database\Produit;

class HomeController extends AdminController{
    
    public function index(){
        $user = $this->user;
        //Privilege::hasPrivilege(Privilege::$AllView,$user->privilege);
        $search = (isset($_GET['search'])&&!empty($_GET['search'])) ? $_GET['search'] : null;
        $sexe = (isset($_GET['sexe'])&&!empty($_GET['sexe'])) ? $_GET['sexe'] : null;
        $login_debut = (isset($_GET['login_debut'])&&!empty($_GET['login_debut'])) ? date(MYSQL_DATE_FORMAT, strtotime($_GET['login_debut'])) : null;
        $login_end = (isset($_GET['login_end'])&&!empty($_GET['login_end'])) ? date(MYSQL_DATE_FORMAT, strtotime($_GET['login_end'])) : null;
        $debut = (isset($_GET['debut'])&&!empty($_GET['debut'])) ? date(MYSQL_DATE_FORMAT, strtotime($_GET['debut'])) : null;
        $end = (isset($_GET['end'])&&!empty($_GET['end'])) ? date(MYSQL_DATE_FORMAT, strtotime($_GET['end'])) : null;
        $nbreadmins = Profil::countBySearchType($search);
        $nbreformations = Formation::countBySearchType($search);
        $nbreemployeurs = Entreprise::countBySearchType($search);
        $nbreopco = Opco::countBySearchType($search);
        $nbrecerfas = Cerfa::searchType($search);
        $nbreproduit = Produit::searchType();
        $produits = $nbreproduit['data'];
        $nbreproduits =  count($produits);
        $_SESSION['page_active'] = 'home';
        $current = date(DATE_FORMAT);
        $this->render('admin.user.index',compact('user','current','nbreadmins','nbrecerfas','nbreemployeurs','nbreformations','nbreopco','nbreproduits'));
       
    }

  public function searchhome(){
    $return = [];
    header('content-type: application/json');
    if (isset($_POST['date_debut'])  && !empty($_POST['date_debut']) && isset($_POST['date_fin'])
        && !empty($_POST['date_fin'])){
       
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
      
            if ($date_debut <= $date_fin){
             
                try{
                    $cerfas = Cerfa::searchType(null);
                    $nbrecerfas = 0;
                    $nbreconventions = 0;
                    $nbrefactures = 0;
                    foreach($cerfas as $cerfa){
                        if ($date_debut <= $cerfa->date_creation && $cerfa->date_creation <= $date_fin){
                       
                        if(!empty($cerfa->conventionOpco)){
                            $nbreconventions += 1;
                        }

                        if(!empty($cerfa->numeroInterne)){
                            $nbrecerfas +=1;
                        }

                        if(!empty($cerfa->factureOpco)){
                            $nbrefactures += 1;
                        }
                       }
                    }
                  
                    $message = "Recuperation reussie";
                    $return = array("statuts"=>0, "mes"=>$message,"nbrecerfas"=>$nbrecerfas,"nbreconventions"=>$nbreconventions,"nbrefactures"=>$nbrefactures);
                   
                }catch(Exception $e){
                   
                    $message = $e->getMessage(). "Une erreur est survenue, réessayer";
                    $return = array("statuts"=>1, "mes"=>$message);
                }
            }else{
                $message = "La date de début ne peut pas être supérieure à la date de fin";
                $return = array("statuts"=>1, "mes"=>$message);
            }
        
    }else{
        $message = "Veuillez remplir correctement tous les champs requis";
        $return = array("statuts"=>1, "mes"=>$message);
    }
    echo json_encode($return);
  }


    public function password(){
        $user = $this->user;
        $this->render('admin.user.password',compact('user'));
    }

    public function profil(){
        $user = $this->user;
        $this->render('admin.user.profil',compact('user'));
    }

    public function changePassword(){
        $return = [];
        header('content-type: application/json');
        if (isset($_POST['oldpassword'])  && !empty($_POST['oldpassword']) && isset($_POST['newpassword'])
            && !empty($_POST['newpassword']) && isset($_POST['confirmpassword']) && !empty($_POST['confirmpassword'])){
            $user = $this->user;
            $oldpass = $_POST['oldpassword'];
            $newpass = $_POST['newpassword'];
            $confirmpass = $_POST['confirmpassword'];
                if ($newpass == $confirmpass){
                    
                    if($this->validatePassword($newpass)){

                        try{
                       
                            $save = Profil::setPassword($oldpass,$newpass);
                            if($save['valid']){
                                $message = $save['data'];
                                $return = array("statuts"=>0, "mes"=>$message);
                            }else{
                                $message = $save['error'];
                                $return = array("statuts"=>1, "mes"=>$message);
                            }
                           
                        }catch(Exception $e){
                           
                            $message = $e->getMessage(). "Une erreur est survenue, réessayer";
                            $return = array("statuts"=>1, "mes"=>$message);
                        }
                    }else{
                        $message = "Le mot de passe doit contenir au moins 12 caractères, une majuscule, un chiffre et un caractère spécial.";
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                    
                }else{
                    $message = "Le nouveau mot de passe doit être identique à la confirmation";
                    $return = array("statuts"=>1, "mes"=>$message);
                }
            
        }else{
            $message = "Une erreur est survenue, réessayer";
            $return = array("statuts"=>1, "mes"=>$message);
        }
        echo json_encode($return);
    }

    private function validatePassword($password) {
        // Mot de passe d'au moins 12 caractères, avec 1 majuscule, 1 chiffre, et 1 caractère spécial
        return strlen($password) >= 12 &&
               preg_match('/[A-Z]/', $password) &&    // Au moins une majuscule
               preg_match('/[0-9]/', $password) &&    // Au moins un chiffre
               preg_match('/[\W_]/', $password);      // Au moins un caractère spécial
    }


    public function session(){
       
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Assurez-vous que 'page' est défini dans les données POST
            if (isset($_POST['page'])) {
                // Mettez à jour la session avec la nouvelle valeur
                $_SESSION['page_active'] = $_POST['page'];
        
                // Envoyez une réponse pour indiquer le succès
                echo 'Session mise à jour avec succès.';
            } else {
                // Envoyez une réponse d'erreur si 'page' n'est pas défini
                http_response_code(400);
                echo 'Erreur: Paramètre manquant.';
            }
        } else {
            $user = $this->user;
            //Privilege::hasPrivilege(Privilege::$AllView,$user->privilege);
            $search = (isset($_GET['search'])&&!empty($_GET['search'])) ? $_GET['search'] : null;
            $sexe = (isset($_GET['sexe'])&&!empty($_GET['sexe'])) ? $_GET['sexe'] : null;
            $login_debut = (isset($_GET['login_debut'])&&!empty($_GET['login_debut'])) ? date(MYSQL_DATE_FORMAT, strtotime($_GET['login_debut'])) : null;
            $login_end = (isset($_GET['login_end'])&&!empty($_GET['login_end'])) ? date(MYSQL_DATE_FORMAT, strtotime($_GET['login_end'])) : null;
            $debut = (isset($_GET['debut'])&&!empty($_GET['debut'])) ? date(MYSQL_DATE_FORMAT, strtotime($_GET['debut'])) : null;
            $end = (isset($_GET['end'])&&!empty($_GET['end'])) ? date(MYSQL_DATE_FORMAT, strtotime($_GET['end'])) : null;
            $nbreadmins = Profil::countBySearchType($search,$sexe,$debut,$end,$login_debut,$login_end);
            $nbrealternants = Alternant::countBySearchType();
             $nbreformations = Formation::countBySearchType($search);
            $nbreemployeurs = Entreprise::countBySearchType($search);
            $nbreopco = Opco::countBySearchType($search);
            $nbrecerfas = Cerfa::countBySearchType($search);
            $_SESSION['page_active'] = 'home';
            $current = date(DATE_FORMAT);
            $this->render('admin.user.index',compact('user','current','nbreadmins','nbrealternants','nbrecerfas','nbreemployeurs','nbreformations','nbreopco'));
            // // Envoyez une réponse d'erreur si la requête n'est pas de type POST
            // http_response_code(405);
            // echo 'Erreur: Méthode non autorisée.';
           
        }
    }




  

}