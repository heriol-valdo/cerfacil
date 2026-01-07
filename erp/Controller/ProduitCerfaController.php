<?php

require_once __DIR__ . '/../requestFile/authRequet.php';
require_once __DIR__ . '/LogoutController.php';

class ProduitCerfaController {
    public function index() {


        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];


        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

             include("Views/produitCerfa/index.php");
        }

      
       
        
    }

    public static function ListProduitCerfa() {
        $result = sendRequest([],$_SESSION['userToken'],"produitCerfa",'post');

        $result = json_decode($result);

        if(!empty($result)){
            if (property_exists($result, 'erreur')) {
                $allproduits = "";
            }
            if (property_exists($result, 'valid')) {
                $allproduits= $result;
            }
        } 

        return $allproduits;
    }

    public function save(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            $postData = file_get_contents('php://input');
            $decoded = json_decode($postData, true);
        
           
            if (!isset($decoded['nom']) ||!isset($decoded['type']) ||!isset($decoded['prix_dossier']) || !isset($decoded['prix_abonement']) ||!isset($decoded['caracteristique1']) 
                ||!isset($decoded['caracteristique2']) ||!isset($decoded['caracteristique3'])  ||!isset($decoded['caracteristique4'])) {
                echo json_encode([
                    'erreur' => 'Certains champs ne sont pas remplis'
                ]);
                exit;
            }
        
            $nom = trim($decoded['nom']);
            $type = trim($decoded['type']);
            $prix_dossier = trim($decoded['prix_dossier']);
            $prix_abonement = trim($decoded['prix_abonement']);
            $caracteristique1 = trim($decoded['caracteristique1']);
            $caracteristique2 = trim($decoded['caracteristique2']);
            $caracteristique3 = trim($decoded['caracteristique3']);
            $caracteristique4 = trim($decoded['caracteristique4']);
        
            $data = [
                'nom'=> $nom,
                'type' => $type,
                'prix_dossier' => $prix_dossier,
                'prix_abonement' => $prix_abonement,
                'caracteristique1'=> $caracteristique1,
                'caracteristique2'=>$caracteristique2,
                'caracteristique3'=>$caracteristique3,
                'caracteristique4'=>$caracteristique4
               
            ];
            
        
           
            $result = sendRequest($data,$_SESSION['userToken'],"addProduitCerfa",'post');
            $result = json_decode($result);
            
           
        
            if (property_exists($result, 'erreur')) {
             
                echo json_encode([
                    'erreur' => $result->erreur
                ]);
                exit;
            }
        
        
            if (property_exists($result, 'valid')) {
                echo json_encode([
                    'valid' => $result->valid,
                ]);
                exit;
            }
        }
    }

    public function update(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            $postData = file_get_contents('php://input');
            $decoded = json_decode($postData, true);
        
        
            if (!isset($decoded['nom']) ||!isset($decoded['type']) ||!isset($decoded['prix_dossier']) || !isset($decoded['prix_abonement']) ||!isset($decoded['caracteristique1']) 
            ||!isset($decoded['caracteristique2']) ||!isset($decoded['caracteristique3'])  ||!isset($decoded['caracteristique4'])  || !isset($decoded['id'])) {
            echo json_encode([
                'erreur' => 'Certains champs ne sont pas remplis'
            ]);
            exit;
        }
        
        
            $id = trim($decoded['id']);
            $nom = trim($decoded['nom']);
            $type = trim($decoded['type']);
            $prix_dossier = trim($decoded['prix_dossier']);
            $prix_abonement = trim($decoded['prix_abonement']);
            $caracteristique1 = trim($decoded['caracteristique1']);
            $caracteristique2 = trim($decoded['caracteristique2']);
            $caracteristique3 = trim($decoded['caracteristique3']);
            $caracteristique4 = trim($decoded['caracteristique4']);
           
        
        
           
            $data = [
                'id'=> $id,
                'newNom'=> $nom,
                'newType' => $type,
                'newPrixDossier' => $prix_dossier,
                'newPrixAbonement' => $prix_abonement,
                'newCaracteristique1'=> $caracteristique1,
                'newCaracteristique2'=>$caracteristique2,
                'newCaracteristique3'=>$caracteristique3,
                'newCaracteristique4'=>$caracteristique4
               
            ];
        
            $url = "produitCerfaUpdate" ;
            $result = sendRequest($data, $_SESSION['userToken'], $url, 'PUT');
            $result = json_decode($result);
        
        
            if (property_exists($result, 'erreur')) {
                echo json_encode([
                    'erreur' => $result->erreur,
                ]);
                exit;
            }
        
            if (property_exists($result, 'valid')) {
                echo json_encode([
                    'valid' => $result->valid,
                ]);
                exit;
            }
        
        }
    }

    public function delete(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $postData = file_get_contents('php://input');
            $decoded = json_decode($postData, true);
        
            $id = trim($decoded['id']);
            $url = "produitCerfa/". $id ."/delete";
            $result = sendRequest([], $_SESSION['userToken'], $url, 'DELETE');
            $result = json_decode($result);
        
            if (property_exists($result, 'erreur')) {
                echo json_encode([
                    'erreur' => $result->erreur,
                ]);
                exit;
            }
        
            if (property_exists($result, 'valid')) {
                echo json_encode([
                    'valid' => $result->valid,
                ]);
                exit;
            }
        }
    }

     

    

    public static function validToken() {

        $logOutIfTokenExpired = false;
        $logOutUserIfNotConnected = false ;
        // Vérifier l'expiration du token
        if (isset($_SESSION['user']['exp'])) {
            $expirationTime = $_SESSION['user']['exp'];
            $currentTime = time();
            
            if ($currentTime > $expirationTime || !isset($_SESSION) || empty($_SESSION)) {
                // Token expiré ou session invalide
                $_SESSION = array();
                $logOutIfTokenExpired = true;
               
            }
        }
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            $_SESSION = [];
            $logOutUserIfNotConnected = true;
        }
        
        // Si on arrive ici, le token est valide
        return [
            'logOutIfTokenExpired' => $logOutIfTokenExpired,
            'logOutUserIfNotConnected' => $logOutUserIfNotConnected
        ];
    }

   

}
?>