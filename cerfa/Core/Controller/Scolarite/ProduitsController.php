<?php


namespace Projet\Controller\Scolarite;



use DateTime;
use DOMDocument;
use DOMXPath;
use Projet\Controller\Admin\AdminsController;

use Projet\Database\Produit;
use Projet\Database\Profil;
use Projet\Database\Alternant;
use Projet\Database\Formation;
use Projet\Database\Entreprise;
use Projet\Database\Opco;
use Projet\Database\Cerfa;
use Projet\Database\Abonnement;
use Projet\Model\StripeHandler;




// class ProduitsController extends AdminsController
// {
//     public function index(){
       
//         $user = $this->user;
//         $search = (isset($_GET['search'])&&!empty($_GET['search'])) ? $_GET['search'] : null;
       
//         $produits = Produit::searchType();
//         $produits = $produits['data'];

//         $abonnements = Abonnement::searchType();
//         $abonnements = $abonnements['data'];
        
       
        
//         if($user->data->role == 'ClientCERFA' || $user->data->role == 'Gestionnaire de centre'){
//             if($user->data->role == 'ClientCERFA'){
//                 if($user->data->roleCreation ==1 ){
//                     $this->render('admin.scolarite.produit',compact('produits','user','abonnements'));
//                 }else{
//                     $nbreadmins = Profil::countBySearchType($search);
//                     $nbreformations = Formation::countBySearchType($search);
//                     $nbreemployeurs = Entreprise::countBySearchType($search);
//                     $nbreopco = Opco::countBySearchType($search);
//                     $nbrecerfas = Cerfa::countBySearchType($search);
//                     $nbreproduit = Produit::searchType();
//                     $produits = $nbreproduit['data'];
//                     $nbreproduits =  count($produits);
//                     $_SESSION['page_active'] = 'home';
//                     $current = date(DATE_FORMAT);
//                     $this->render('admin.user.index',compact('user','current','nbreadmins','nbrecerfas','nbreemployeurs','nbreformations','nbreopco','nbreproduits'));

//                 }

//             }else{
//                 $this->render('admin.scolarite.produit',compact('produits','user','abonnements'));
//             }
           
//         }else{
//             $nbreadmins = Profil::countBySearchType($search);
//             $nbreformations = Formation::countBySearchType($search);
//             $nbreemployeurs = Entreprise::countBySearchType($search);
//             $nbreopco = Opco::countBySearchType($search);
//             $nbrecerfas = Cerfa::countBySearchType($search);
//             $nbreproduit = Produit::searchType();
//             $produits = $nbreproduit['data'];
//             $nbreproduits =  count($produits);
//             $_SESSION['page_active'] = 'home';
//             $current = date(DATE_FORMAT);
//             $this->render('admin.user.index',compact('user','current','nbreadmins','nbrecerfas','nbreemployeurs','nbreformations','nbreopco','nbreproduits'));
//         }
//     }


//     public function save()
// {
//     header('content-type: application/json');
//     $return = [];
//     $tab = ["add", "edit"];

//     if (
//         isset($_POST['quantite']) && !empty($_POST['quantite']) &&
//         isset($_POST['prix_dossier']) && !empty($_POST['prix_dossier']) &&
//         isset($_POST['action']) && !empty($_POST['action']) &&
//         isset($_POST['id']) && in_array($_POST["action"], $tab)
//     ) {

//         $prix_abonement = $_POST['prix_abonement'];
//         $prix_dossier = $_POST['prix_dossier'];

//         $quantite = $_POST['quantite'];
//         $selectAbonemet = $_POST['selectAbonemet'];
//         $totalAbonement  = $_POST['totalAbonement'];
//         $totalDossier = $_POST['totalDossier'];
//         $totalFacture = $_POST['totalFacture'];

       
        

//         $date_debut = isset($_POST['date_debut'])? $_POST['date_debut'] : null;
//         $date_fin = isset($_POST['date_fin'])? $_POST['date_fin'] : null;
        
        
//         $action = $_POST['action'];
//         $id = $_POST['id'];
//         $idProduit = $_POST['idProduit'];

//         if ($action == "edit") {
//             if (!empty($id)) {
//                     try {
//                         $quantiterecharge = $_POST['quantiterecharge'];
//                         $dateCourante = date("Y-m-d");
//                         $type = 1;
//                         if($dateCourante < $date_fin){
//                             $dateDebuts = $date_debut;
//                             $dateFin = $date_fin;
//                             $type = 1;
//                         }else{
//                             $type = 2;
//                             if ($selectAbonemet == 1) {
//                                 $dateFin = ( $dateCourante > $date_fin)?date('Y-m-d', strtotime($dateCourante . ' +1 month')): $date_fin;
//                                 $dateDebuts =  ($dateCourante > $date_fin)?  $dateCourante : $date_debut;
//                             } else {
//                                 $dateFin = ( $dateCourante > $date_fin)?date('Y-m-d', strtotime($dateCourante . ' +12 months')): $date_fin;
//                                 $dateDebuts =  ($dateCourante > $date_fin)?  $dateCourante : $date_debut;
//                             }
//                         }

//                         StripeHandler::initialize('sk_test_51Q0jJtP2q6i0JROS5CTCqcAXkGRNOLD8nB197YvtqT14MyM54Jnoel9TGLhqEsmPNCzcQE3FJk0OxZOzD1Wo7Ht300IPyeWP0F');

//                         // Effectuer le paiement.
//                         $result = StripeHandler::Paiement($totalFacture);

//                         if($result){

//                             $save = Abonnement::save($dateDebuts,$dateFin,$quantite,$idProduit,$totalDossier,$totalFacture,$totalAbonement,$quantiterecharge,$type,$id);
                       
//                             if($save['valid']){
//                                 $message = "l'Abonement été mis à jour avec succès";
//                                 $this->session->write('success', $message);
//                                 $return = array("statuts" => 0, "mes" => $message);
//                             }else{
//                                 $message = $save['error'];
//                                 $return = array("statuts" => 1, "mes" => $message);
//                             }

//                         }else{

//                             $message = "Le paiement à echoué";
//                             $return = array("statuts" => 1, "mes" => $message);

//                         }

                       
                        
                        
//                     } catch (Exception $e) {
//                         $message = $e->getMessage();
//                         $return = array("statuts" => 1, "mes" => $message);
//                     }
                    
                
//             } else {
//                 $message = $this->error;
//                 $return = array("statuts" => 1, "mes" => $message);
//             }
//         } else {
//                 try {
//                     $dateDebut = date("Y-m-d"); // Récupère la date courante en format string

//                     if ($selectAbonemet == 1) {
//                         $dateFin = date('Y-m-d', strtotime($dateDebut . ' +1 month'));
//                     } else {
//                         $dateFin = date('Y-m-d', strtotime($dateDebut . ' +12 months'));
//                     }
                    
//                     $save = Abonnement::save($dateDebut,$dateFin,$quantite,$idProduit,$totalDossier,$totalFacture,$totalAbonement);
                   
//                     if($save['valid']){
//                         $message = $save['data'];
//                         $this->session->write('success', $message);
//                         $return = array("statuts" => 0, "mes" => $message);
//                     }else{
//                         $message = $save['error'];
//                         $return = array("statuts" => 1, "mes" => $message."ok");
//                     }
//                 } catch (Exception $e) {
//                     $message = $this->error;
//                     $return = array("statuts" => 1, "mes" => $message);
//                 }
            
//         }
//     } else {
//         $message = "Veuillez renseigner tous les champs requis";
//         $return = array("statuts" => 1, "mes" => $message);
//     }

//     echo json_encode($return);
// }

// public function saves()
// {
//     header('content-type: application/json');
//     $return = [];
//     $tab = ["add", "edit"];

//     if (
//         isset($_POST['quantites']) && !empty($_POST['quantites']) &&
//         isset($_POST['prix_dossiers']) && !empty($_POST['prix_dossiers']) &&
//         isset($_POST['action']) && !empty($_POST['action']) &&
//         isset($_POST['id']) && in_array($_POST["action"], $tab)
//     ) {

       
//         $prix_dossiers = $_POST['prix_dossiers'];

//         $quantites = $_POST['quantites'];
       
       
//         $totalDossiers = $_POST['totalDossiers'];
//         $totalFactures = $_POST['totalFactures'];
        
        
        
//         $action = $_POST['action'];
//         $id = $_POST['id'];
//         $idProduits = $_POST['idProduits'];

//         if ($action == "edit") {
//             if (!empty($id)) {
//                     try {
                       
//                         $quantitesrecharge = $_POST['quantitesrecharge'];
//                         $dateDebut = date("Y-m-d");
                     
//                         $save = Abonnement::save($dateDebut,$dateDebut,$quantites,$idProduits,$totalDossiers,$totalFactures,null,$quantitesrecharge,null,$id);
                       
//                         if($save['valid']){
//                             $message = "l'Abonement été mis à jour avec succès";
//                             $this->session->write('success', $message);
//                             $return = array("statuts" => 0, "mes" => $message);
//                         }else{
//                             $message = $save['error'];
//                             $return = array("statuts" => 1, "mes" => $message);
//                         }
                        
//                     } catch (Exception $e) {
//                         $message = $e->getMessage();
//                         $return = array("statuts" => 1, "mes" => $message);
//                     }
                    
                
//             } else {
//                 $message = $this->error;
//                 $return = array("statuts" => 1, "mes" => $message);
//             }
//         } else {
//                 try {
                  
//                     $dateDebut = date("Y-m-d"); // Récupère la date courante en format string
//                     $save = Abonnement::save($dateDebut,$dateDebut,$quantites,$idProduits,$totalDossiers,$totalFactures);
                   
//                     if($save['valid']){
//                         $message = $save['data'];
//                         $this->session->write('success', $message);
//                         $return = array("statuts" => 0, "mes" => $message);
//                     }else{
//                         $message = $save['error'];
//                         $return = array("statuts" => 1, "mes" => $message);
//                     }
//                 } catch (Exception $e) {
//                     $message = $this->error;
//                     $return = array("statuts" => 1, "mes" => $message);
//                 }
            
//         }
//     } else {
//         $message = "Veuillez renseigner tous les champs requis";
//         $return = array("statuts" => 1, "mes" => $message);
//     }

//     echo json_encode($return);
// }


// } 



class ProduitsController extends AdminsController
{
    public function index(){
       
        $user = $this->user;
        $search = (isset($_GET['search'])&&!empty($_GET['search'])) ? $_GET['search'] : null;
       
        $produits = Produit::searchType();
        $produits = $produits['data'];

        $abonnements = Abonnement::searchType();
        $abonnements = $abonnements['data'];
        
       
        
        if($user->data->role == 'ClientCERFA' || $user->data->role == 'Gestionnaire de centre'){
            if($user->data->role == 'ClientCERFA'){
                if($user->data->roleCreation ==1 ){
                    $this->render('admin.scolarite.produit',compact('produits','user','abonnements'));
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
                $this->render('admin.scolarite.produit',compact('produits','user','abonnements'));
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
    }


 




// Controller modifié pour Ajout des dossiers 
public function save()
{
    header('content-type: application/json');
    $return = [];
    $tab = ["add", "edit"];

    if (
        isset($_POST['quantite']) && !empty($_POST['quantite']) &&
        isset($_POST['prix_dossier']) && !empty($_POST['prix_dossier']) &&
        isset($_POST['action']) && !empty($_POST['action']) &&
        isset($_POST['id']) && in_array($_POST["action"], $tab) &&
        isset($_POST['stripe_token']) && !empty($_POST['stripe_token'])
    ) {

        $prix_abonement = floatval($_POST['prix_abonement']);
        $prix_dossier = floatval($_POST['prix_dossier']);
        $quantite = intval($_POST['quantite']);
        $selectAbonemet = intval($_POST['selectAbonemet']);
        
      

        $date_debut = isset($_POST['date_debut']) ? $_POST['date_debut'] : null;
        $date_fin = isset($_POST['date_fin']) ? $_POST['date_fin'] : null;
        $action = $_POST['action'];
        $id = $_POST['id'];
        $idProduit = $_POST['idProduit'];
        $stripe_token = $_POST['stripe_token'];

        

        if ($action == "edit") {
            if (!empty($id)) {

                
                try {
                    $quantiterecharge = $_POST['quantiterecharge'];
                    $dateCourante = date("Y-m-d");
                    $type = 1;
                    
                    if ($dateCourante < $date_fin) {
                        $dateDebuts = $date_debut;
                        $dateFin = $date_fin;
                        $type = 1;
                    } else {
                        $type = 2;
                        if ($selectAbonemet == 1) {
                            $dateFin = ($dateCourante > $date_fin) ? date('Y-m-d', strtotime($dateCourante . ' +1 month')) : $date_fin;
                            $dateDebuts = ($dateCourante > $date_fin) ? $dateCourante : $date_debut;
                        } else {
                            $dateFin = ($dateCourante > $date_fin) ? date('Y-m-d', strtotime($dateCourante . ' +12 months')) : $date_fin;
                            $dateDebuts = ($dateCourante > $date_fin) ? $dateCourante : $date_debut;
                        }
                    }


                    // Recalcul côté serveur pour sécurité
                    $number  = ($type==1)? 0 : (($selectAbonemet==1)?1 : 12) ;
                    $totalDossier = $prix_dossier *  $quantiterecharge;
                    $totalAbonement = $prix_abonement * $number;
                    $totalFacture = $totalDossier + $totalAbonement;
                    $tva = $totalFacture *0.20;
                    $totalFacture =$totalDossier + $totalAbonement +$tva;
                
                    
                    // Vérification du montant envoyé par le client
                    $totalFactureClient = floatval($_POST['totalFacture']);
                    if (abs($totalFacture - $totalFactureClient) > 0.01) {
                        $return = array("statuts" => 1, "mes" => "Erreur: Le montant calculé ne correspond pas");
                        echo json_encode($return);
                        return;
                    }

                    // Traitement du paiement Stripe
                    $paymentResult = $this->processStripePayment($stripe_token, $totalFacture);
                    
                    if (!$paymentResult['success']) {
                        $return = array("statuts" => 1, "mes" => "Erreur de paiement: " . $paymentResult['error']);
                        echo json_encode($return);
                        return;
                    }


                    $save = Abonnement::save($dateDebuts, $dateFin, $quantite, $idProduit, 
                    $totalDossier, 
                    $totalFacture
                    , $paymentResult['charge_id'],$totalAbonement, 
                    $quantiterecharge, $type, $id);
                    
                    if ($save['valid']) {
                        $message = "L'abonnement a été mis à jour avec succès. Paiement confirmé.";
                        $this->session->write('success', $message);
                        $return = array("statuts" => 0, "mes" => $message);
                    } else {
                        // Rembourser en cas d'erreur
                        $this->refundStripePayment($paymentResult['charge_id']);
                        $message = $save['error'];
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                    
                } catch (Exception $e) {
                    // Rembourser en cas d'erreur
                    $this->refundStripePayment($paymentResult['charge_id']);
                    $message = $e->getMessage();
                    $return = array("statuts" => 1, "mes" => $message);
                }
            } else {
                $message = $this->error;
                $return = array("statuts" => 1, "mes" => $message);
            }
        } else {

            $number = ($selectAbonemet == 1)? 1 : 12; 
            $totalDossier = $prix_dossier * $quantite;
            $totalAbonement = $prix_abonement * $number;
            $totalFacture = $totalDossier + $totalAbonement;
            $tva = $totalFacture *0.20;
            $totalFacture =$totalDossier + $totalAbonement +$tva;
        
            
            // Vérification du montant envoyé par le client
            $totalFactureClient = floatval($_POST['totalFacture']);
            if (abs($totalFacture - $totalFactureClient) > 0.01) {
                $return = array("statuts" => 1, "mes" => "Erreur: Le montant calculé ne correspond pas");
                echo json_encode($return);
                return;
            }


            // Traitement du paiement Stripe
                $paymentResult = $this->processStripePayment($stripe_token, $totalFacture);
                
                if (!$paymentResult['success']) {
                    $return = array("statuts" => 1, "mes" => "Erreur de paiement: " . $paymentResult['error']);
                    echo json_encode($return);
                    return;
                }

            try {


                $dateDebut = date("Y-m-d");

                if ($selectAbonemet == 1) {
                    $dateFin = date('Y-m-d', strtotime($dateDebut . ' +1 month'));
                } else {
                    $dateFin = date('Y-m-d', strtotime($dateDebut . ' +12 months'));
                }
                
                $save = Abonnement::save($dateDebut, $dateFin, $quantite, $idProduit, 
                $totalDossier, $totalFacture, $paymentResult['charge_id'],
                $totalAbonement
                , null, null, null);
               
                if ($save['valid']) {
                    $message = $save['data'] . " Paiement confirmé.";
                    $this->session->write('success', $message);
                    $return = array("statuts" => 0, "mes" => $message);
                } else {
                    // Rembourser en cas d'erreur
                    $this->refundStripePayment($paymentResult['charge_id']);
                    $message = $save['error'];
                    $return = array("statuts" => 1, "mes" => $message);
                }
            } catch (Exception $e) {
                // Rembourser en cas d'erreur
                if (isset($paymentResult['charge_id'])) {
                    $this->refundStripePayment($paymentResult['charge_id']);
                }
                $message = $e->getMessage();
                $return = array("statuts" => 1, "mes" => $message);
            }
        }
    } else {
        $message = "Veuillez renseigner tous les champs requis et effectuer le paiement";
        $return = array("statuts" => 1, "mes" => $message);
    }

    echo json_encode($return);
}

private function processStripePayment($token, $amount)
{
    try {
        // Configuration Stripe
        \Stripe\Stripe::setApiKey('sk_test_51Q0jJtP2q6i0JROS5CTCqcAXkGRNOLD8nB197YvtqT14MyM54Jnoel9TGLhqEsmPNCzcQE3FJk0OxZOzD1Wo7Ht300IPyeWP0F');
        
        // Convertir en centimes
        $amountInCents = round($amount * 100);
        
        // Créer la charge
        $charge = \Stripe\Charge::create([
            'amount' => $amountInCents,
            'currency' => 'eur',
            'source' => $token,
            'description' => 'Paiement abonnement - ' . date('Y-m-d H:i:s'),
            'metadata' => [
                'user_id' => $this->session->read('user_id'),
                'timestamp' => time()
            ]
        ]);
        
        return [
            'success' => true, 
            'charge_id' => $charge->id,
            'amount_paid' => $charge->amount / 100
        ];
        
    } catch (\Stripe\Exception\CardException $e) {
        return ['success' => false, 'error' => 'Carte refusée: ' . $e->getMessage()];
    } catch (\Stripe\Exception\RateLimitException $e) {
        return ['success' => false, 'error' => 'Trop de requêtes simultanées'];
    } catch (\Stripe\Exception\InvalidRequestException $e) {
        return ['success' => false, 'error' => 'Paramètres invalides: ' . $e->getMessage()];
    } catch (\Stripe\Exception\AuthenticationException $e) {
        return ['success' => false, 'error' => 'Erreur d\'authentification Stripe'];
    } catch (\Stripe\Exception\ApiConnectionException $e) {
        return ['success' => false, 'error' => 'Erreur de connexion avec Stripe'];
    } catch (\Stripe\Exception\ApiErrorException $e) {
        return ['success' => false, 'error' => 'Erreur API Stripe: ' . $e->getMessage()];
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Erreur inattendue: ' . $e->getMessage()];
    }
}

private function refundStripePayment($chargeId)
{
    try {
        \Stripe\Stripe::setApiKey('sk_test_51Q0jJtP2q6i0JROS5CTCqcAXkGRNOLD8nB197YvtqT14MyM54Jnoel9TGLhqEsmPNCzcQE3FJk0OxZOzD1Wo7Ht300IPyeWP0F');
        
        \Stripe\Refund::create([
            'charge' => $chargeId,
            'reason' => 'requested_by_customer'
        ]);
        
        return true;
    } catch (Exception $e) {
        // Log l'erreur pour investigation manuelle
        error_log("Erreur remboursement Stripe: " . $e->getMessage() . " - Charge ID: " . $chargeId);
        return false;
    }
}


// pour les factures 

public function saves()
{
    header('content-type: application/json');
    $return = [];
    $tab = ["add", "edit"];

    if (
        isset($_POST['quantites']) && !empty($_POST['quantites']) &&
        isset($_POST['prix_dossiers']) && !empty($_POST['prix_dossiers']) &&
        isset($_POST['action']) && !empty($_POST['action']) &&
        isset($_POST['id']) && in_array($_POST["action"], $tab) &&
        isset($_POST['stripe_token']) && !empty($_POST['stripe_token'])
    ) {

        $prix_dossiers = floatval($_POST['prix_dossiers']);
        $quantites = intval($_POST['quantites']);
        
        // Recalcul côté serveur pour sécurité
       
        
        $action = $_POST['action'];
        $id = $_POST['id'];
        $idProduits = $_POST['idProduits'];
        $stripe_token = $_POST['stripe_token'];

       

        // === TRAITEMENT STRIPE ===
        try {
        
        
                
                if ($action == "edit") {
                    if (!empty($id)) {
                        try {
                            $quantitesrecharge = $_POST['quantitesrecharge'];
                            $dateDebut = date("Y-m-d");


                             $totalDossiers = $prix_dossiers * $quantitesrecharge;
                            $tva = $totalDossiers *0.20; // Ajustez selon votre logique métier
                            $totalFactures =$totalDossiers +$tva;

                            // Vérification du montant envoyé par le client
                            $totalFactureClient = floatval($_POST['totalFactures']);
                            if (abs($totalFactures - $totalFactureClient) > 0.01) {
                                $return = array("statuts" => 1, "mes" => "Erreur: Le montant calculé ne correspond pas");
                                echo json_encode($return);
                                return;
                            }

                            $paymentResult = $this->processStripePaymentFacture($stripe_token, $totalFactures, $quantites,$idProduits);
            
                            if (!$paymentResult['success']) {
                                $return = array("statuts" => 1, "mes" => "Erreur de paiement: " . $paymentResult['error']);
                                echo json_encode($return);
                                return;
                            }
                         
                            $save = Abonnement::save($dateDebut, $dateDebut, $quantites, $idProduits,
                             $totalDossiers, $totalFactures
                            , $paymentResult['charge_id'],null, $quantitesrecharge, 
                            null, $id);
                           
                            if($save['valid']){
                                $message = "L'abonnement a été mis à jour et le paiement effectué avec succès";
                                $this->session->write('success', $message);
                                $return = array("statuts" => 0, "mes" => $message);
                            } else {
                                // Rembourser si l'enregistrement échoue
                                 $this->refundStripePayment($paymentResult['charge_id']);
                                $message = $save['error'];
                                $return = array("statuts" => 1, "mes" => $message);
                            }
                            
                        } catch (Exception $e) {
                            // Rembourser en cas d'erreur
                            $this->refundStripePayment($paymentResult['charge_id']);
                            $message = $e->getMessage();
                            $return = array("statuts" => 1, "mes" => $message);
                        }
                    } else {
                        $message = "ID manquant";
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                } else {
                  
                    try {
                        $dateDebut = date("Y-m-d");


                        $totalDossiers = $prix_dossiers * $quantites;
                        $tva = $totalDossiers *0.20; // Ajustez selon votre logique métier
                        $totalFactures =$totalDossiers +$tva;

                        // Vérification du montant envoyé par le client
                        $totalFactureClient = floatval($_POST['totalFactures']);
                        if (abs($totalFactures - $totalFactureClient) > 0.01) {
                            $return = array("statuts" => 1, "mes" => "Erreur: Le montant calculé ne correspond pas");
                            echo json_encode($return);
                            return;
                        }

                        $paymentResult = $this->processStripePaymentFacture($stripe_token, $totalFactures, $quantites,$idProduits);
        
                        if (!$paymentResult['success']) {
                            $return = array("statuts" => 1, "mes" => "Erreur de paiement: " . $paymentResult['error']);
                            echo json_encode($return);
                            return;
                        }

                        $save = Abonnement::save($dateDebut, $dateDebut,
                         $quantites, 
                        $idProduits, $totalDossiers, $totalFactures, 
                        $paymentResult['charge_id'],null,null,
                        null,null);
                       
                        if($save['valid']){
                            $message = $save['data'] . " - Paiement effectué avec succès";
                            $this->session->write('success', $message);
                            $return = array("statuts" => 0, "mes" => $message);
                        } else {
                            // Rembourser si l'enregistrement échoue
                             $this->refundStripePayment($paymentResult['charge_id']);
                            $message = $save['error'];
                            $return = array("statuts" => 1, "mes" => $message);
                        }
                    } catch (Exception $e) {
                        // Rembourser en cas d'erreur
                        $this->refundStripePayment($paymentResult['charge_id']);
                        $message = "Erreur lors de l'enregistrement";
                        $return = array("statuts" => 1, "mes" => $message);
                    }
                }
           

        } catch (\Stripe\Exception\CardException $e) {
            $return = array("statuts" => 1, "mes" => "Erreur de carte: " . $e->getError()->message);
        } catch (\Stripe\Exception\RateLimitException $e) {
            $return = array("statuts" => 1, "mes" => "Trop de requêtes");
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            $return = array("statuts" => 1, "mes" => "Requête invalide");
        } catch (\Stripe\Exception\AuthenticationException $e) {
            $return = array("statuts" => 1, "mes" => "Erreur d'authentification");
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            $return = array("statuts" => 1, "mes" => "Erreur de connexion");
        } catch (\Stripe\Exception\ApiErrorException $e) {
            $return = array("statuts" => 1, "mes" => "Erreur API Stripe");
        } catch (Exception $e) {
            $return = array("statuts" => 1, "mes" => "Erreur générale: " . $e->getMessage());
        }

    } else {
        $message = "Veuillez renseigner tous les champs requis";
        $return = array("statuts" => 1, "mes" => $message);
    }

    echo json_encode($return);
}


private function processStripePaymentFacture($token, $amount,$quantites,$idProduits)
{
    try {
        // Configuration Stripe
        \Stripe\Stripe::setApiKey('sk_test_51Q0jJtP2q6i0JROS5CTCqcAXkGRNOLD8nB197YvtqT14MyM54Jnoel9TGLhqEsmPNCzcQE3FJk0OxZOzD1Wo7Ht300IPyeWP0F');
        
        // Convertir en centimes
        $amountInCents = round($amount * 100);
        
        // Créer la charge
        $charge = \Stripe\Charge::create([
            'amount' => $amountInCents,
            'currency' => 'eur',
            'source' => $token,
            'description' => 'Achat de dossiers - Quantité: ' . $quantites,
            'metadata' => [
                'user_id' => $this->session->read('user_id'), // Adaptez selon votre session
                'product_id' => $idProduits,
                'quantity' => $quantites
            ]
        ]);
        
        return [
            'success' => true, 
            'charge_id' => $charge->id,
            'amount_paid' => $charge->amount / 100
        ];
        
    } catch (\Stripe\Exception\CardException $e) {
        return ['success' => false, 'error' => 'Carte refusée: ' . $e->getMessage()];
    } catch (\Stripe\Exception\RateLimitException $e) {
        return ['success' => false, 'error' => 'Trop de requêtes simultanées'];
    } catch (\Stripe\Exception\InvalidRequestException $e) {
        return ['success' => false, 'error' => 'Paramètres invalides: ' . $e->getMessage()];
    } catch (\Stripe\Exception\AuthenticationException $e) {
        return ['success' => false, 'error' => 'Erreur d\'authentification Stripe'];
    } catch (\Stripe\Exception\ApiConnectionException $e) {
        return ['success' => false, 'error' => 'Erreur de connexion avec Stripe'];
    } catch (\Stripe\Exception\ApiErrorException $e) {
        return ['success' => false, 'error' => 'Erreur API Stripe: ' . $e->getMessage()];
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Erreur inattendue: ' . $e->getMessage()];
    }
}

}