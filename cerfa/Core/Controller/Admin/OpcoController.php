<?php


namespace Projet\Controller\Admin;



use Projet\Controller\Admin\AdminsController;

use Projet\Database\Opco;
use Projet\Database\Entreprise;
use Projet\Database\Abonnement;

use Projet\Database\Produit;
use Projet\Database\Profil;
use Projet\Database\Alternant;
use Projet\Database\Formation;

use Projet\Database\Cerfa;


use Projet\Model\App;

use Projet\Model\Privilege;
use Exception;


class OpcoController extends AdminsController
{
    public function index() {
        $user = $this->user;
        $nbreParPage = 10;
        
        // nouveau
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $search = isset($_POST['searchopco']) && !empty($_POST['searchopco']) ? $_POST['searchopco'] : null;
            $this->session->write('searchopco', $search); // Enregistrer la recherche dans la session
            
            // Récupérer la page envoyée et l'enregistrer dans la session
            $pageCourante = isset($_POST['page']) && is_numeric($_POST['page']) ? (int)$_POST['page'] : 1;
            $this->session->write('pageCouranteopco', $pageCourante);
            
            // Rediriger pour éviter la soumission multiple de formulaire
            header("Location: " . App::url('opco'));
            exit;
        }
        
        // Vérifier si une recherche est en session
        $search = $this->session->exists('searchopco') ? $this->session->read('searchopco') : null;
        
        
        
        // Vérifier si la page courante est en session
        $pageCourante = $this->session->exists('pageCouranteopco') ? $this->session->read('pageCouranteopco') : 1;



        $nbre = Opco::countBySearchType($search);
        $nbrePages = ceil($nbre / $nbreParPage);
        
       
        
        $items = Opco::searchType($nbreParPage, $pageCourante, $search);
    
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
            $this->render('admin.admin.opco', compact('search', 'user', 'nbre', 'nbrePages', 'items','pageCourante'));
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
        isset($_POST['nom']) && !empty($_POST['nom']) &&
        isset($_POST['cle']) && !empty($_POST['cle']) &&
        isset($_POST['action']) && !empty($_POST['action']) &&
        isset($_POST['id']) && in_array($_POST["action"], $tab)
    ) {

        $nom = $_POST['nom'];
        $cle = $_POST['cle'];

        $lienE = $_POST['lienE'];
        $lienCe = $_POST['lienCe'];
        $lienCo = $_POST['lienCo'];
        $lienF = $_POST['lienF'];
        $lienT = $_POST['lienT'];
        $clid = $_POST['clid'];
        $clse = $_POST['clse'];
        
        
        $action = $_POST['action'];
        $id = $_POST['id'];

        if ($action == "edit") {
            if (!empty($id)) {
                $opco = Opco::find($id);
                if ($opco['valid']) {
                        try {
                            $save = Opco::save($nom,$cle,$lienE,$lienCe,$lienCo,$lienF,$lienT,$clid, $clse,$id);
                            if($save['valid']){
                                $message = "l'opco été mis à jour avec succès";
                                $this->session->write('success', $message);
                                $return = array("statuts" => 0, "mes" => $message);
                            }else{
                                $message = $save['error'];
                                $return = array("statuts" => 1, "mes" => $message);
                            }
                           
                        } catch (Exception $e) {
                            $message = $e->getMessage();
                            $return = array("statuts" => 1, "mes" => $message);
                        }
                    
                } else {
                    $message = $opco['error'];
                    $return = array("statuts" => 1, "mes" => $message);
                }
            } else {
                $message = $this->error;
                $return = array("statuts" => 1, "mes" => $message);
            }
        } else {
                try {
                    $save = Opco::save($nom,$cle,$lienE,$lienCe,$lienCo,$lienF,$lienT,$clid, $clse,$id = null);
                    if($save['valid']){
                        $message = $save['data'];
                        $this->session->write('success', $message);
                        $return = array("statuts" => 0, "mes" => $message);
                    }else{
                        $message = $save['error'];
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                } catch (Exception $e) {
                    $message = $this->error;
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
        header('content-type: application/json');
        if (isset($_POST['id'])&&!empty($_POST['id'])){
            $id = $_POST['id'];
            $opco = Opco::find($id);
            if ($opco['valid']){
                $opcoentreprise = Entreprise :: findbyopco($id);

                 if($opcoentreprise['valid']){
                    $message = "L'opco ne peut pas etre suprimer car il est rattacher a une entreprise";
                    $return = array("statuts"=>1, "mes"=>$message);
                 }else{
                    $save = Opco::delete($id);
                    if($save['valid']){
                        $message = $save['data'];
                        $this->session->write('success', $message);
                        $return = array("statuts" => 0, "mes" => $message);
                    }else{
                        $message = $save['error'];
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                 }
                

            }else{
                $message = $opco['error'];;
                $return = array("statuts"=>1, "mes"=>$message);
            }
        }else{
            $message = "Renseigner l'id SVP !!!";
            $return = array("statuts"=>1, "mes"=>$message);
        }
        echo json_encode($return);
    }



}