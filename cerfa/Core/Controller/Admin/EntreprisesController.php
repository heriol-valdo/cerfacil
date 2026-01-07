<?php


namespace Projet\Controller\Admin;


use DateTime;
use Exception;

use Projet\Database\Cerfa;

use Projet\Database\Entreprise;
use Projet\Database\Opco;
use Projet\Database\Produit;
use Projet\Database\Profil;
use Projet\Database\Alternant;
use Projet\Model\App;
use Projet\Database\Formation;
use Projet\Database\Abonnement;


class EntreprisesController extends AdminController{

    public function index(){
        $user = $this->user;
        // Privilege::hasPrivilege(Privilege::$AllView,$user->privilege);
        $nbreParPage = 10;
        $search = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $search = isset($_POST['searchentreprise']) && !empty($_POST['searchentreprise']) ? $_POST['searchentreprise'] : null;
            $this->session->write('searchentreprise', $search); // Enregistrer la recherche dans la session
            
            // Récupérer la page envoyée et l'enregistrer dans la session
            $pageCourante = isset($_POST['page']) && is_numeric($_POST['page']) ? (int)$_POST['page'] : 1;
            $this->session->write('pageCouranteentreprise', $pageCourante);
            
            // Rediriger pour éviter la soumission multiple de formulaire
            header("Location: " . App::url('employeurs'));
            exit;
        }
        
        // Vérifier si une recherche est en session
        $search = $this->session->exists('searchentreprise') ? $this->session->read('searchentreprise') : null;
        
        
        
        // Vérifier si la page courante est en session
        $pageCourante = $this->session->exists('pageCouranteentreprise') ? $this->session->read('pageCouranteentreprise') : 1;

        $nbre = Entreprise::countBySearchType($search);
        $nbrePages = ceil($nbre / $nbreParPage);
      
        
        $items = Entreprise::searchType($nbreParPage,$pageCourante,$search);
        $opcos = Opco::searchType();
       

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
            $this->render('admin.admin.entreprise',compact('search','items','opcos','user','nbre','nbrePages','pageCourante'));
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
            isset($_POST['nomE']) && !empty($_POST['nomE']) &&
            isset($_POST['emailE']) && !empty($_POST['emailE']) &&
            isset($_POST['action']) && !empty($_POST['action']) &&
            isset($_POST['id']) && in_array($_POST["action"], $tab)
        ) {
    
            $nomE = $_POST['nomE'];
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
            $emailE = $_POST['emailE'];
            $numeroE = $_POST['numeroE'];
            $idopco = $_POST['idopco'];

            $action = $_POST['action'];
            $id = $_POST['id'];
    
            if ($action == "edit") {
                if (!empty($id)) {
                    $entreprise = Entreprise::find($id);
    
                    if ($entreprise['valid']) {
                       
                        try {
                            $save = Entreprise::save($nomE, $typeE, $specifiqueE, $totalE, $siretE, $codeaE, $codeiE, $rueE, $voieE, $complementE, $postalE, $communeE, $emailE, $numeroE, $idopco, $id);
                            if ($save['valid']) {
                                $message = "L'employeur a été mise à jour avec succès";
                                $this->session->write('success', $message);
                                $return = array("statuts" => 0, "mes" => $message);
                            } else {
                                $message = $save['error'];
                                $return = array("statuts" => 1, "mes" => $message);
                            }
                        } catch (Exception $e) {
                            $message = $e->getMessage();
                            $return = array("statuts" => 1, "mes" => $message);
                        }
                    
                    } else {
                        $message = $entreprise['error'];
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                } else {
                    $message = $this->error;
                    $return = array("statuts" => 1, "mes" => $message);
                }
            } else {
                $result = Entreprise::byNom($nomE);
                if (!$result['valid']) {
                    try {
                        $save = Entreprise::save($nomE, $typeE, $specifiqueE, $totalE, $siretE, $codeaE, $codeiE, $rueE, $voieE, $complementE, $postalE, $communeE, $emailE, $numeroE, $idopco, $id = null);
                        if ($save['valid']) {
                            $message = $save['data'];
                            $this->session->write('success', $message);
                            $return = array("statuts" => 0, "mes" => $message);
                        } else {
                            $message = $save['error'];
                            $return = array("statuts" => 1, "mes" => $message);
                        }
                    } catch (Exception $e) {
                        $message = $e->getMessage();
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                } else {
                    $message = "Le nom de l'employeur existe déjà, veuillez utiliser un autre nom";
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
            $entreprise = Entreprise::find($id);
            if ($entreprise['valid']){

                $entreprisecerfa = Cerfa :: findbyentreprise($id);
                if($entreprisecerfa['valid']){
                    $message = "L'employeur ne peut pas etre suprimer car elle est rattacher a un cerfa";
                    $return = array("statuts"=>1, "mes"=>$message);
                }else{
                    $save = Entreprise::delete($id);
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
                $message = $entreprise['error'];
                $return = array("statuts"=>1, "mes"=>$message);
            }
        }else{
            $message = "Renseigner l'id SVP !!!";
            $return = array("statuts"=>1, "mes"=>$message);
        }
        echo json_encode($return);
    }

    public function sendEmail(){
        //Privilege::hasPrivilege(Privilege::$AllView,$this->user->privilege);
        header('content-type: application/json');
        if (isset($_POST['id'])&&!empty($_POST['id'])){
            $id = $_POST['id'];
            $entreprise = Entreprise::find($id);
            if ($entreprise['valid']){

                $entreprises= Entreprise::sendEmail($id);
                if($entreprises['valid']){
                    $message = $entreprises['data'];
                    $this->session->write('success', $message);
                    $return = array("statuts"=>0, "mes"=>$message);
                }else{
                    $message = $entreprise['error'];
                    $return = array("statuts"=>1, "mes"=>$message);
                }
               

            }else{
                $message = $entreprise['error'];
                $return = array("statuts"=>1, "mes"=>$message);
            }
        }else{
            $message = "Renseigner l'id SVP !!!";
            $return = array("statuts"=>1, "mes"=>$message);
        }
        echo json_encode($return);
    }
    

}