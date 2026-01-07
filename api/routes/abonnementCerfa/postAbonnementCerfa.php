<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/ClientCerfaModel.php';
require_once __DIR__.'/../../models/AbonnementCerfaModel.php';
require_once __DIR__.'/../../models/ProduitCerfaModel.php';
require_once __DIR__.'/../../models/ProduitCerfaFactureModel.php';
require_once __DIR__.'/../../models/EmailModel.php';
require_once __DIR__ .'/../../models/UserModel.php';


$app->post('/api/addupdateAbonnementCerfa', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data = $request->getParsedBody();

    // Liste des clés requises
    $requiredKeys = [
        'date_fin', 'date_debut', 'quantite', 'id_produit'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['erreur' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    }

    // Récupération des valeurs
    $id = $data['id'];
    $date_debut = $data['date_debut'];
    $date_fin = $data['date_fin'];
    $quantite = $data['quantite'];
    $id_produit = $data['id_produit'];
    $totalFacture = $data['totalFacture'];
    $totalDossier = $data['totalDossier'];
    $totalAbonement = $data['totalAbonement'];
    $quantitesrecharge = $data['quantitesrecharge'];
    $type = $data['type'];
    $stripe_id = $data['stripe_id'];

    // Vérification du rôle
    if ($role === 7 || $role === 3) {

        if($role === 7){
            $clientCerfa = new ClientCerfa();
            $clientCerfa->id_users =  $userConnected ;

            $profilClient = $clientCerfa->getProfil();

            $effectiveUserId = $profilClient[0]['roleCreation'] == 2 ? $profilClient[0]['idCreation'] :$userConnected;
            $firstname = $profilClient[0]['firstname'];
            $email = $profilClient[0]['email'];
            $produit = new Produit();
            $tabProduit = $produit->getProduitCerfaDatasByIdAbonnement($id_produit);
            $nameProduit = $tabProduit['nom'];
            $prixUnitaireDossier = $tabProduit['prix_dossier'];
            $prixUnitaireAbonement = $tabProduit['prix_abonement'];

            $produitcerfafacture = new ProduitCerfaFacture();
            

        if(isset($id)){
            $emailAbonement = Email::sendEmailReAbonement($email,$firstname,$date_debut,$date_fin,$quantitesrecharge,$nameProduit,$prixUnitaireDossier,$prixUnitaireAbonement,
            $totalFacture,$totalDossier,$type, $stripe_id,$totalAbonement);

            if(intval($tabProduit['type']) === 3 || intval($tabProduit['type']) ===4){

                $produitcerfafacture->save($id_produit,null,$effectiveUserId,$quantitesrecharge,$totalDossier,$totalFacture,$date_debut, $date_fin
                ,null,null,$stripe_id);
            }else{
                $produitcerfafacture->save($id_produit,null,$effectiveUserId,$quantitesrecharge,$totalDossier,$totalFacture,$date_debut, 
                $date_fin,$totalAbonement,$type,$stripe_id);
            }
        }else{
            $emailAbonement = Email::sendEmailAbonement($email,$firstname,$date_debut,$date_fin,$quantite,
            $nameProduit,$prixUnitaireDossier,
            $prixUnitaireAbonement,$totalFacture,$totalDossier,
            $stripe_id,$totalAbonement);
            //insertion dans le tableau des achat produit_cerfa_facture
            if( intval($tabProduit['type']) === 3 ||  intval($tabProduit['type']) ===4){

                $produitcerfafacture->save($id_produit,null,$effectiveUserId,$quantite,
                $totalDossier,$totalFacture,$date_debut, 
                $date_fin,null,null,$stripe_id);
            }else{
                $produitcerfafacture->save($id_produit,null,$effectiveUserId,$quantite,$totalDossier,$totalFacture
                ,$date_debut, $date_fin,$totalAbonement,
                $type,$stripe_id);
            }
        }



       
        $abonnementCerfa = new AbonnementCerfa();
        $result = $abonnementCerfa->save($date_debut, $date_fin, $quantite, $id_produit, null,$effectiveUserId,$id);
        if (!isset($result['erreur'])  && $emailAbonement) {
            $response->getBody()->write(json_encode(['valid' => 'Vous avez ajouté un Abonement'.$email]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }

        }elseif($role === 3){

            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_users = $userConnected;
            $datagestionnairescentre = $gestionnaireCentre->searchForId();
            $effectiveUserId = $datagestionnairescentre['id_centres_de_formation'];
            $firstname = $datagestionnairescentre['firstname'];

        
            $user = new User();
            $user->id =  $userConnected;
            $og_user = $user->searchForId();
            $email = $og_user['email'];
          

            $produit = new Produit();
            $tabProduit = $produit->getProduitCerfaDatasByIdAbonnement($id_produit);
            $nameProduit = $tabProduit['nom'];
            $prixUnitaireDossier = $tabProduit['prix_dossier'];
            $prixUnitaireAbonement = $tabProduit['prix_abonement'];

            $produitcerfafacture = new ProduitCerfaFacture();

            

            if(isset($id)){
                $emailAbonement = Email::sendEmailReAbonement($email,$firstname,$date_debut,$date_fin,$quantitesrecharge,$nameProduit,$prixUnitaireDossier,$prixUnitaireAbonement,
                $totalFacture,$totalDossier,$type,$stripe_id,$totalAbonement);

                if(intval($tabProduit['type']) === 3 || intval($tabProduit['type']) ===4){

                    $produitcerfafacture->save($id_produit,$effectiveUserId,null,$quantitesrecharge,$totalDossier,
                    $totalFacture,$date_debut, 
                    $date_fin,null,null,$stripe_id);
                }else{
                    $produitcerfafacture->save($id_produit,$effectiveUserId,null,$quantitesrecharge,$totalDossier,$totalFacture,$date_debut, 
                    $date_fin,$totalAbonement,$type,$stripe_id);
                }
            }else{
                $emailAbonement = Email::sendEmailAbonement($email,$firstname,$date_debut,$date_fin,$quantite,$nameProduit,
                $prixUnitaireDossier,$prixUnitaireAbonement,$totalFacture,
                $totalDossier,$stripe_id,$totalAbonement);
                //insertion dans le tableau des achat produit_cerfa_facture
                if( intval($tabProduit['type']) === 3 ||  intval($tabProduit['type']) ===4){

                    $produitcerfafacture->save($id_produit,$effectiveUserId,null,$quantite,$totalDossier,$totalFacture,$date_debut, 
                    $date_fin,null,null,$stripe_id);
                }else{
                    $produitcerfafacture->save($id_produit,$effectiveUserId,null,$quantite,$totalDossier,$totalFacture,$date_debut, $date_fin,
                    $totalAbonement,$type,$stripe_id);
                }
            }



        
            $abonnementCerfa = new AbonnementCerfa();
            $result = $abonnementCerfa->save($date_debut, $date_fin, $quantite, $id_produit,$effectiveUserId,null,$id);
            if (!isset($result['erreur'])  && $emailAbonement) {
                $response->getBody()->write(json_encode(['valid' => 'Vous avez ajouté un Abonement'.$email]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
            } else {
                $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
            }

        }
        
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);


// Modification de la quantite d'un abonnement Apres en envoie
$app->post('/api/updateAbonnementById', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $id = $token['id'];
    $data =$request->getParsedBody();
    $id = isset($data['id']) ? $data['id'] : null;

    if ($role === 7 || $role === 3) {
        $clientCerfa = new ClientCerfa();
        $clientCerfa->id_users = $id;

        $profilClient = $clientCerfa->getProfil();

        $effectiveUserId = $profilClient[0]['roleCreation'] == 2 ? $profilClient[0]['idCreation'] : $id;

       

        $abonementCerfa = new AbonnementCerfa();
        $result = $abonementCerfa->updateAbonnementById($id);
        if (isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } else{
            $response->getBody()->write(json_encode(['valid' => 'Modification de la quantite reussite', 'data' => $result]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

 




