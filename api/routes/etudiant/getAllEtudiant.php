<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
    
require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__.'/../../models/EtudiantModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';
require_once __DIR__.'/../../models/EntrepriseModel.php';
require_once __DIR__.'/../../models/FinanceurModel.php';
require_once __DIR__.'/../../models/SessionModel.php';
require_once __DIR__.'/../../models/FormateurModel.php';
require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__ .'/../../models/AdminModel.php';
require_once __DIR__ .'/../../models/GestionnaireEntrepriseModel.php';
require_once __DIR__ .'/../../models/FormateursParticipantSessionModel.php';

//========================================================================================
// But : Récupérer la liste des etudiants
// Rôles : Admins, gestionnaire de centre, formateur
// param :user_id
//========================================================================================



$app->get('/api/etudiants', function (Request $request, Response $response, $args) use ($key) {  
    $token=$request->getAttribute('user');
    $id = $token['id'];
    $role =$token['role'];
    $idbyrole =$token['idByRole'];

   if(empty($token)){
    $response->getBody()->write(json_encode(['erreur' => "token invalide"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

   if($role == 1){
        $etudiant = new Etudiant();

        $allEtudiant = $etudiant->searchAllEtudiantForAdmin();

        if(!empty($allEtudiant)){
            $response->getBody()->write(json_encode(['valid' =>$allEtudiant ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "Listes des etudiants vides"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

   }elseif($role == 2){
        $gestionnaireentreprise = new GestionnaireEntreprise();
        $gestionnaireentreprise->id_users = $id;
        $allinfogestionnaireentreprise  = $gestionnaireentreprise->searchForId();
        $idgestionnaireentreprise = $allinfogestionnaireentreprise['id_entreprises'];

        if(empty($idgestionnaireentreprise)){
            $response->getBody()->write(json_encode(['erreur' => "le gestionnaire d'entreprise n'a ete rattaches a aucune entreprise donc il peut pas avoir accees a la liste des etudiants "]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $etudiant = new Etudiant();

        $allEtudiant = $etudiant->searchAllEtudiantForEntreprise($idgestionnaireentreprise);

        if(!empty($allEtudiant)){
            $response->getBody()->write(json_encode(['valid' =>$allEtudiant ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "Listes des etudiants vides"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
       
   }elseif($role == 3){
        $gestionnaireCentre = new GestionnaireCentre();
        $gestionnaireCentre->id_users = $id;
        $allinfogestionnairecentre = $gestionnaireCentre->searchForId();

        $idcentreformationgestionnaire =$allinfogestionnairecentre['id_centres_de_formation'];

        if(empty($idcentreformationgestionnaire)){
            $response->getBody()->write(json_encode(['erreur' => "le gestionnaire de centre n'a ete rattaches a aucun centre de formation donc il peut pas avoir accees a la liste des etudiants "]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }


        $etudiant = new Etudiant();

        $allEtudiant = $etudiant->searchAllEtudiantForCentreId($idcentreformationgestionnaire);

        if(!empty($allEtudiant)){
            $response->getBody()->write(json_encode(['valid' =>$allEtudiant ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "Listes des etudiants vides"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

    }elseif($role == 4){
        $formateurParticipantSession = new FormateursParticipantSession();

        $allsessionFormateurs = $formateurParticipantSession->searchForIdFormateur($idbyrole);
        if(empty($allsessionFormateurs)){
            $response->getBody()->write(json_encode(['erreur' => "le formateur  n'est  rattache a aucun session de formation "]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $etudiant = new Etudiant();
        $allEtudiant = [];
        foreach($allsessionFormateurs as $allsessionFormateur){
           $idsession = $allsessionFormateur['id'];
           $allEtudiant = array_merge($allEtudiant, $etudiant->searchAllEtudiantForIdSession($idsession) ?? []);
        }

        if(!empty($allEtudiant)){
            $response->getBody()->write(json_encode(['valid' =>$allEtudiant ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "Listes des etudiants vides"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }


    }elseif($role == 6){
        $token=$request->getAttribute('user');
        $role=intval($token['role']);
        $userConnected = $token['id'];  
    
        $financeur = new Financeur(); 
        $financeur->id_users = $id;
        $allinfofinanceur = $financeur->searchForId(); 
        $idtable =  $allinfofinanceur['id'];

        


        $etudiant = new Etudiant();
        $allEtudiant = $etudiant->searchAllEtudiantForFinanceur($idtable);

        if(!empty($allEtudiant)){
            $response->getBody()->write(json_encode(['valid' =>$allEtudiant ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "Listes des etudiants vides"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

    }
    else{
        $response->getBody()->write(json_encode(['erreur' => "Vous n'aves pas les droits pour acceder a cette ressource"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }


   
})->add($auth);

// Récupère tous les étudiants
$app->get('/api/etudiants/all', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    $user = new User();
    $user->id = $token['id'];
    $user->id_role = intval($token['role']);

    $etudiant = new Etudiant();

    // Administrateurs
    if($role == 1){
        $etudiants = $etudiant->getAllEtudiants_admin();
    }

    // GEntreprise
    if($role == 2){
        $gEntreprise = new GestionnaireEntreprise();
        $gEntreprise->id_users = $token['id'];
        $idEntreprise = $gEntreprise->searchEntrepriseForIdUsers();

        if(empty($idEntreprise)){
            $response->getBody()->write(json_encode(['erreur' => "Ce gestionnaire n'est pas rattaché à une entreprise"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $gEntreprise->id_entreprises = $idEntreprise;

        $etudiants = $gEntreprise->getAllEtudiants_gEntreprise();
    }
    
    // GCentre
    if($role == 3){
        $gestionnaire  = new GestionnaireCentre();
        $idcentreformation = $user->searchIdCentreForIdUsers();

        if (empty($idcentreformation)) {
            $response->getBody()->write(json_encode(['erreur' => "Ce gestionnaire n'est pas rattaché a un centre de formation"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $gestionnaire->id_centres_de_formation = $idcentreformation;
        $etudiants = $gestionnaire->getAllEtudiants_gCentre();
    }

    // Si Formateur
    if($role === 4){
        $formateurParticipant = new FormateursParticipantSession();
        $formateurParticipant->id_formateurs = $token['idByRole'];
        
        $etudiants = $formateurParticipant->getAllEtudiants_formateur();
    }

    // Si conseiller financeur
    if($role === 6){
        $financeur  = new Financeur();
        $financeur->id_users = $token['id'];
        $idEntreprise = $financeur-> getIdEntrepriseByUserId();

        if (empty($idEntreprise)) {
            $response->getBody()->write(json_encode(['erreur' => "Ce financeur n'est pas rattaché a une entreprise"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        
        $financeur->id_entreprises = $idEntreprise;

        $etudiants = $financeur->getAllEtudiants_financeur();
    }

    if (!empty($etudiants)) {
        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $etudiants]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Aucun étudiant enregistré"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
    
})->add($auth)->add($checkNotEtudiant);