<?php


namespace Projet\Controller\Admin;



use Projet\Controller\Admin\AdminsController;

use Projet\Database\Cerfa;
use Projet\Database\Formation;

use Projet\Database\Entreprise;
use Projet\Database\Opco;
use Projet\Model\App;
use Projet\Database\Produit;
use Projet\Database\Profil;
use Projet\Database\Alternant;
use Projet\Database\Abonnement;
use Projet\Model\FileHelper;

use Exception;


class FormationsController extends AdminsController
{
    public function index(){
        //Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        $user = $this->user;
        $nbreParPage = 10;
       
        // nouveau
       // Traitement de la recherche
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $search = isset($_POST['search']) && !empty($_POST['search']) ? $_POST['search'] : null;
        $this->session->write('search', $search); // Enregistrer la recherche dans la session
        
        // Récupérer la page envoyée et l'enregistrer dans la session
        $pageCourante = isset($_POST['page']) && is_numeric($_POST['page']) ? (int)$_POST['page'] : 1;
        $this->session->write('pageCourante', $pageCourante);
        
        // Rediriger pour éviter la soumission multiple de formulaire
        header("Location: " . App::url('formations'));
        exit;
    }

    // Vérifier si une recherche est en session
    $search = $this->session->exists('search') ? $this->session->read('search') : null;



    // Vérifier si la page courante est en session
    $pageCourante = $this->session->exists('pageCourante') ? $this->session->read('pageCourante') : 1;

    // Calculer le nombre total d'éléments et de pages
    $nbre = Formation::countBySearchType($search);
    $nbrePages = ceil($nbre / $nbreParPage);

       
      
   
   

        // fin nouveau 
        $items = Formation::searchType($nbreParPage,$pageCourante,$search);
     


        $abonnements = Abonnement::searchType();
        $abonnements = $abonnements['data'];
        
        $hasType1Product = false;
    
        if (!empty($abonnements)) {
            foreach ($abonnements as $abonnement) {
                $tableauproduits = Produit::find($abonnement->id_produit);
                $tableauproduit = $tableauproduits['data'];
    
                if ($tableauproduit->type == 1) {
                    $dateCourante  = date("Y-m-d");
                    $hasType1Product = ($dateCourante > $abonnement->date_fin)?false : true;
                    break; 
                }
            }
        }
    
        if ($hasType1Product) {
            $this->render('admin.admin.formation',compact('search','user','nbre','nbrePages','items','pageCourante'));
            //$this->session->delete('search');

        } else {
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
    }


    public function save()
{
    header('content-type: application/json');
    $return = [];
    $tab = ["add", "edit"];

    if (
        isset($_POST['nomF']) && !empty($_POST['nomF']) &&
        isset($_POST['action']) && !empty($_POST['action']) &&
        isset($_POST['idElement']) && in_array($_POST["action"], $tab)
    ) {

        $nomF = $_POST['nomF'];
        $diplomeF = $_POST['diplomeF'];
        $intituleF = $_POST['intituleF'];
        $numeroF = $_POST['numeroF'];
        $siretF = $_POST['siretF'];
        $codeF = $_POST['codeF'];
        $rnF = $_POST['rnF'];
        $entrepriseF = $_POST['entrepriseF'];
        $responsableF = $_POST['responsableF'];
        $prix = $_POST['prix'];
        $rueF = $_POST['rueF'];
        $voieF = $_POST['voieF'];
        $complementF = $_POST['complementF'];
        $postalF = $_POST['postalF'];
        $communeF = $_POST['communeF'];

        $emailF = $_POST['emailF'];
        $debutO = $_POST['debutO'];
        $prevuO = $_POST['prevuO'];
        $dureO = $_POST['dureO'];
        $nomO = $_POST['nomO'];
        $numeroO = $_POST['numeroO'];
        $siretO = $_POST['siretO'];
        $rueO = $_POST['rueO'];
        $voieO = $_POST['voieO'];
        $complementO = $_POST['complementO'];
        $postalO = $_POST['postalO'];
        $communeO = $_POST['communeO'];
        
        $action = $_POST['action'];
        $id = $_POST['idElement'];

       


        if ($action == "edit") {
            if (!empty($id)) {
                $formation = Formation::find($id);
                //var_dump($formation['data']->id);

                if (isset($_FILES['image']['tmp_name']) && !empty($_FILES['image']['tmp_name']) ) {
                    if ($_FILES['image']['error'] == 0) {
                        $extensions_valides = array('jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG');
                        $extension_upload = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1));
                        if (in_array($extension_upload, $extensions_valides)) {
                            if ($_FILES['image']['size'] <= 2000000) {
                                $root = FileHelper::moveImage($_FILES['image']['tmp_name'], "logo", "png", "", true);
                                if (!empty($formation['data']->logo) ) {
                                    FileHelper::deleteImage($formation['data']->logo);
                                }
        
                            }else{
                                $message = 'Le fichier doit avoir une taille inférieure à 2M';
                                $return = array("statuts" => 0, "mes" => $message);
                            }
        
                        }else{
                            $message = "Le fichier doit être une image";
                            $return = array("statuts" => 0, "mes" => $message);
                        }
        
                    }else{
                        $message = "Une erreur est survenue lors du telechargement de l'image";
                        $return = array("statuts" => 0, "mes" => $message);
                    }
        
                }else{
                    $root = $formation['data']->logo;
                }

                if ($formation) {
                        try {
                            $save = Formation::save(
                            $nomF,$diplomeF,$intituleF,$numeroF,$siretF,$codeF,$rnF,$entrepriseF,$responsableF,$prix,$rueF,$voieF,$complementF,$postalF,$communeF,
                            $emailF,$debutO,$prevuO,$dureO,$nomO,$numeroO,$siretO,$rueO,$voieO,$complementO,$postalO,$communeO,
                            $root,$id);
                            if ($save['valid']) {
                                $message = "La formation a été mise à jour avec succès";
                                $this->session->write('success', $message);
                                $return = array("statuts" => 0, "mes" => $message);
                            } else {
                                $message = $save['error'];
                                $return = array("statuts" => 1, "mes" => $message);
                            }
                        } catch (Exception $e) {
                            $message = $this->error;
                            $return = array("statuts" => 1, "mes" => $message);
                        }
                    
                } else {
                    $message = $this->error;
                    $return = array("statuts" => 1, "mes" => $message);
                }
            } else {
                $message = $this->error;
                $return = array("statuts" => 1, "mes" => $message);
            }
        } else {

                    if (isset($_FILES['image']['tmp_name']) && !empty($_FILES['image']['tmp_name']) ) {
                        if ($_FILES['image']['error'] == 0) {
                            $extensions_valides = array('jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG');
                            $extension_upload = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1));
                            if (in_array($extension_upload, $extensions_valides)) {
                                if ($_FILES['image']['size'] <= 2000000) {
                                    $root = FileHelper::moveImage($_FILES['image']['tmp_name'], "logo", "png", "", true);
                                }else{
                                    $message = 'Le fichier doit avoir une taille inférieure à 2M';
                                    $return = array("statuts" => 0, "mes" => $message);
                                }
            
                            }else{
                                $message = "Le fichier doit être une image";
                                $return = array("statuts" => 0, "mes" => $message);
                            }
            
                        }else{
                            $message = "Une erreur est survenue lors du telechargement de l'image";
                            $return = array("statuts" => 0, "mes" => $message);
                        }
            
                    }else{
                        $root = "";
                    }
                    
                try {
                    $save = Formation::save(
                    $nomF,$diplomeF,$intituleF,$numeroF,$siretF,$codeF,$rnF,$entrepriseF,$responsableF,$prix,$rueF,$voieF,$complementF,$postalF,$communeF,
                    $emailF,$debutO,$prevuO,$dureO,$nomO,$numeroO,$siretO,$rueO,$voieO,$complementO,$postalO,$communeO,
                    $root,$id = null);
                    if ($save['valid']) {
                        $message = $save['data'];
                        $this->session->write('success', $message);
                        $return = array("statuts" => 0, "mes" => $message);
                    } else {
                        $message = $save['error'];
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                } catch (Exception $e) {
                    $message =  $e->getMessage();
                    $return = array("statuts" => 1, "mes" => $message);
                }
            
        }
    } else {
        $message = "Veuillez renseigner tous les champs requis";
        $return = array("statuts" => 1, "mes" => $message);
    }

    echo json_encode($return);
}

   

    public function delete(){
        //Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        header('content-type: application/json');
        if (isset($_POST['id'])&&!empty($_POST['id'])){
            $id = $_POST['id'];
            $formation = Formation::find($id);
            if ($formation['valid']){

                $formationcerfa = Cerfa :: findbyformation($id);

                if($formationcerfa['valid']){
                    $message = "La formation ne peut pas etre suprimer car elle est rattacher a un cerfa";
                    $return = array("statuts"=>1, "mes"=>$message);
                }else{
                    $save =Formation::delete($id);
                    if($save['valid']){
                        FileHelper::deleteImage($formation['data']->logo);
                        $message = $save['data'];
                        $this->session->write('success', $message);
                        $return = array("statuts" => 0, "mes" => $message);
                    }else{
                        $message = $save['error'];
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                }

             

            }else{
                $message = $formation['error'];
                $return = array("statuts"=>1, "mes"=>$message);
            }
        }else{
            $message = "Renseigner l'id SVP !!!";
            $return = array("statuts"=>1, "mes"=>$message);
        }
        echo json_encode($return);
    }

    

}