<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/UserModel.php';

require_once __DIR__.'/../../models/AdminModel.php';
require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/GestionnaireEntrepriseModel.php';
require_once __DIR__.'/../../models/FormateurModel.php';
require_once __DIR__.'/../../models/EtudiantModel.php';
require_once __DIR__.'/../../models/FinanceurModel.php';
require_once __DIR__.'/../../models/ClientCerfaModel.php';

//========================================================================================
// But : Récupérer son propre profil
// Rôles : Admins
// Champs :aucun
//========================================================================================

$app->get('/api/user/profile', function (Request $request, Response $response) use ($key) {
    $token=$request->getAttribute('user');

    $user = new User(); 

    $id = $token['id'];
    $role = $token['role'];
    $idByRole = $token['idByRole'];

    switch ($role) {
        case 1:
            require_once __DIR__.'/../../models/AdminModel.php';
            $role = new Admin();
            break;
        case 2:
            require_once __DIR__.'/../../models/GestionnaireEntrepriseModel.php';
            $role = new GestionnaireEntreprise();
            break;
        case 3:
            require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
            $role = new GestionnaireCentre();
            break;
        case 4:
            require_once __DIR__.'/../../models/FormateurModel.php';
            $role = new Formateur();
            break;
        case 5:
            require_once __DIR__.'/../../models/EtudiantModel.php';
            $role = new Etudiant();
            break;
        case 6:
            require_once __DIR__.'/../../models/FinanceurModel.php';
            $role = new Financeur();
            break;
        case 7:
            require_once __DIR__.'/../../models/ClientCerfaModel.php';
            $role = new ClientCerfa();
            break;
        default:
            $response->getBody()->write(json_encode(['erreur' => 'Erreur inattendue : Rôle invalide']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
    // Si Admin 
    $role->id_users=$id;
    if($role->getProfil()){
    
        $roleDatas = $role->getProfil();
        $roleDatasFiltered = array_filter($roleDatas[0], function($value, $key) {
            return ($key !== 'id' && $key !== 'password');
        }, ARRAY_FILTER_USE_BOTH);        
        
    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $roleDatasFiltered]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

    }else{
        $response->getBody()->write(json_encode(['erreur' => 'La récupération des données a échoué']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
})->add($auth);



$app->get('/api/user/decode', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');

    $userInfo = [
        'userId' => $token['id'],
        'userRole' => $token['role'],
        'userIdByRole' => $token['idByRole'],
        'idCentre' => $token['centre'],
        'exp' => $token['exp']
    ];

    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $userInfo]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
})->add($auth);


//========================================================================================
// But : Récupérer tous les utilisateurs selon critères
// Rôles : Tous (peut-être pas étudiant)
// Champs :aucun
//========================================================================================

$app->get('/api/user/liste/{byUserRoleId}', function (Request $request, Response $response, $param) use ($key) {
    // Filtres : Valeurs par défaut
    $byUserRoleId = 'all'; // 1,2,3,4,5,6 (en fonction du rôle de l'user)
  
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    $user = new User();

    $user->id = $token['id'];

    // Administrateurs
    if($role == 1){
        // Si le param est correct, l'insère dans le filtre 
        if (!empty($param['byUserRoleId']) && in_array($param['byUserRoleId'], [1, 2, 3, 4, 5, 6])) {
            $byUserRoleId = intval($param['byUserRoleId']);
        }

        $user->id_role = $byUserRoleId;

        $result = $user->adminGetUsersByRole();
    }

    // Gestionnaire entreprise (par défaut : récupère que ses élèves)
    if($role == 2){
        $result = $user->gestionnaireEntrepriseGetEleves();
    }

    // GestionnaireCentre : peut récupérer GCentre, formateurs, élèves
    if($role == 3){
        
        // Si le param est correct, l'insère dans le filtre
        if (!empty($param['byUserRoleId']) && in_array($param['byUserRoleId'], [3, 4, 5])) {
            $byUserRoleId = intval($param['byUserRoleId']);
        } 


        $user->id_role = $byUserRoleId;

        $gestionnaireCentre = new GestionnaireCentre();
        $gestionnaireCentre->id_users = $token['id'];
        $gestionnaireCentre->id_centres_de_formation = $gestionnaireCentre->searchCentreForIdUsers();
        
        $result = $user->gCentreGetUsersByRole($gestionnaireCentre->id_centres_de_formation);
    }


    // Formateurs (par défaut : récupère que ses élèves)
    if($role == 4){
        $formateur = new Formateur();
        $formateur->id_users = $token['id'];

        $result = $user->formateurGetEleves();
    }

     // Financeurs (par défaut : récupère que ses élèves)
     if($role == 6){
        $result = $user->getAllFinancedForSameFinanceur();
    }
    
    // Réussite : Envoi des données
    if(!empty($result)){
         $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' =>$result]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Il n'y a aucun résultat"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    } 
})->add($auth);


//========================================================================================
// But : Récupérer tous les utilisateurs par centre selon critères
// Rôles : Admin, GCentre, Formateurs
// Champs :aucun
//========================================================================================

$app->get('/api/users/centre/{byCentreId}/role/{byUserRoleId}', function (Request $request, Response $response, $param) use ($key) {
    // Filtres : Valeurs par défaut
    $byCentreId = 'all'; // Admin : Si byCentreId non entré
    $byUserRoleId = 'all'; // 1,2,3,4,5,6 (en fonction du rôle de l'user)
  
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    $user = new User();

    $user->id = $token['id'];
    $user->id_role = $role;

    switch($role){
        case 1:
            // Si le param est correct, l'insère dans le filtre 
            if (!empty($param['byCentreId']) && filter_var($param['byCentreId'], FILTER_VALIDATE_INT)) {
                $byCentreId = intval($param['byCentreId']);
            }
            break;

        case 3:
        case 4:
            $byCentreId = $user->searchIdCentreForIdUsers();
            if(empty($byCentreId)){
                $response->getBody()->write(json_encode(['erreur' => "L'utilisateur connecté n'est relié à aucun centre"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
            break;

        default: 
            $response->getBody()->write(json_encode(['erreur' => "Le rôle de l'utilisateur connecté n'est pas reconnu"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    
    // Si le param est correct, l'insère dans le filtre 
    if (!empty($param['byUserRoleId']) && in_array($param['byUserRoleId'], [3, 4, 5])) {
        $byUserRoleId = intval($param['byUserRoleId']);
    }

    switch($byUserRoleId) {
        case 3:
            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_centres_de_formation = $byCentreId;
            $result = $gestionnaireCentre->getGestionnairesByCentre();
            if(empty($result)){
                $result = "Il n'y a aucun gestionnaire associé à ce centre";
            }
            break;
        case 4 :
            $formateur = new Formateur();
            $formateur->id_centres_de_formation = $byCentreId;
            $result = $formateur->getFormateursByCentre();
            if(empty($result)){
                $result = "Il n'y a aucun formateur associé à ce centre";
            }
            break;
        case 5 :
            $etudiant = new Etudiant();
            $etudiant->id_centres_de_formation = $byCentreId;
            $result = $etudiant->getEtudiantsByCentre();
            if(empty($result)){
                $result = "Il n'y a aucun étudiant associé à ce centre";
            }
            break;
        default : 
            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_centres_de_formation = $byCentreId;
            $dataGestionnaires = $gestionnaireCentre->getGestionnairesByCentre();
            if(empty($dataGestionnaires)){
                $dataGestionnaires = "Il n'y a aucun gestionnaire associé à ce centre";
            }
        
            $formateur = new Formateur();
            $formateur->id_centres_de_formation = $byCentreId;
            $dataFormateurs = $formateur->getFormateursByCentre();
            if(empty($dataFormateurs)){
                $dataFormateurs = "Il n'y a aucun formateur associé à ce centre";
            }
        
            $etudiant = new Etudiant();
            $etudiant->id_centres_de_formation = $byCentreId;
            $dataEtudiants = $etudiant->getEtudiantsByCentre();
            if(empty($dataEtudiants)){
                $dataEtudiants = "Il n'y a aucun étudiant associé à ce centre";
            }
            
            $result = [
                'dataGestionnaires' => $dataGestionnaires,
                'dataFormateurs' => $dataFormateurs,
                'dataEtudiants' => $dataEtudiants
            ];
            break;
    }      
    
    // Réussite : Envoi des données
    if(!empty($result)){
         $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Il n'y a aucun résultat"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    } 
})->add($auth);


//========================================================================================
// But : Récupérer le profil d'un utilisateur
// Rôles : Admin
// Champs : aucun
//========================================================================================

$app->get('/api/admin/user/{byUserId}/details', function (Request $request, Response $response, $param) use ($key) {
    $token=$request->getAttribute('user');

    $id = $token['id'];
    $role = $token['role'];
    $idByRole = $token['idByRole'];

    // Pré-recherche infos de l'user recherché
    $user = new User(); 
    $user->id = $param['byUserId'];
    $userInfos = $user->getInfoForId();

    if(empty($userInfos)){
        $response->getBody()->write(json_encode(['erreur' => "L'utilisateur recherché n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $user->role = $userInfos['id_role'];

    // Rôle de la personne recherchée
    switch ($user->role) {
        case 1: // Admin
            $userTable = new Admin();
            break;

        case 2: // Gestionnaire entreprise
            $userTable = new GestionnaireEntreprise();
            break;

        case 3: // Gestionnaire centre
            $userTable = new GestionnaireCentre();
            break;
        
        case 4: // Formateur
            $userTable = new Formateur();
            break;

        case 5: // Etudiant
            $userTable = new Etudiant();
            break;

        case 6: // Financeur
            $userTable = new Financeur();
            break;

        default:
            $response->getBody()->write(json_encode(['erreur' => 'Erreur inattendue : Rôle invalide']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $userTable->id_users = $user->id;
    $userRoleInfos = $userTable->getRoleInfosForId();

    if(empty($userRoleInfos)){
        $response->getBody()->write(json_encode(['erreur' => "Profil de l'utilisateur recherché incomplet"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $data['userInfos'] = $userInfos;
    $data['roleInfos'] = $userRoleInfos;

    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $data]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
})->add($auth)->add($checkAdmin);


//========================================================================================
// But : Récupérer le profil d'un utilisateur
// Rôles : Gestionnaire d'entreprise
// Champs : aucun
//========================================================================================

$app->get('/api/gestionnaire_entreprise/user/{byUserId}/details', function (Request $request, Response $response, $param) use ($key) {
    $token=$request->getAttribute('user');

    $id = $token['id'];
    $role = $token['role'];
    $idByRole = $token['idByRole'];

    // Récupération identité de l'user connecté
    $loggedUser = new GestionnaireEntreprise();
    $loggedUser->id_users = $id;
    // Récupération batiment de l'user (entreprise/centre)
    $loggedStructureId = $loggedUser->getStructure();

    // Pré-recherche infos de l'user recherché
    $user = new User(); 
    $user->id = $param['byUserId'];
    $userInfos = $user->getInfoForId();

    if(empty($userInfos)){
        $response->getBody()->write(json_encode(['erreur' => "L'utilisateur recherché n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $user->role = $userInfos['id_role'];

    // Rôle de la personne recherchée
    switch ($user->role) {
        case 1: // Admin
            $userTable = new Admin();
            break;

        case 2: // Gestionnaire entreprise
            $userTable = new GestionnaireEntreprise();
            break;

        case 3: // Gestionnaire centre
            $userTable = new GestionnaireCentre();
            $accessCheck = new Etudiant();
            break;
        
        case 4: // Formateur
            $userTable = new Formateur();
            $accessCheck = new Etudiant();
            break;

        case 5: // Etudiant
            $userTable = new Etudiant();
            $accessCheck = new Etudiant();
            break;

        case 6: // Financeur
            $userTable = new Financeur();
            $accessCheck = new Etudiant();
            break;

        default:
            $response->getBody()->write(json_encode(['erreur' => 'Erreur inattendue : Rôle invalide']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $userTable->id_users = $user->id;
    $userRoleInfos = $userTable->getRoleInfosForId();

    // Vérification des accès en fonction de l'user recherché
    // Vidage des données si non-accès
    if($user->role == 2){ // Si user recherché est gestionnaire d'entreprise
        $userRoleInfos = ($userRoleInfos['id_entreprises'] == $loggedStructureId) ?  $userRoleInfos : NULL;
    }

    if(in_array($user->role, [3,4,5])){ // Si l'user recherché fait partie d'un centre
        $accessCheck = new Etudiant();
        $accessCheck->id_centres_de_formation = $userRoleInfos['id_centres_de_formation'];
        $accessCheck->id_entreprises = $loggedStructureId;
        
        $hasAccess = $accessCheck->centre_hasAccessToEntreprise();
        $userRoleInfos = $hasAccess ?  $userRoleInfos : NULL;
    }

    if($user->role == 6){
        $accessCheck = new Etudiant();
        $accessCheck->id_conseillers_financeurs = $userRoleInfos['id'];
        $accessCheck->id_entreprises = $loggedStructureId;
        
        $hasAccess = $accessCheck->entreprise_hasAccessToFinanceur();
        $userRoleInfos = $hasAccess ?  $userRoleInfos : NULL;
    }
    
    // Si la 2e partie des détails est vide :
    if(empty($userRoleInfos)){
        $response->getBody()->write(json_encode(['erreur' => "Erreur dans la récupération du profil"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $data['userInfos'] = $userInfos;
    $data['roleInfos'] = $userRoleInfos;

    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $data]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
})->add($auth)->add($checkGestionnaireCentre);


//========================================================================================
// But : Récupérer le profil d'un utilisateur
// Rôles : Gestionnaire de centre, formateurs
// Champs : aucun
//========================================================================================

$app->get('/api/centre/user/{byUserId}/details', function (Request $request, Response $response, $param) use ($key) {
    $token=$request->getAttribute('user');

    $id = $token['id'];
    $role = $token['role'];
    $idByRole = $token['idByRole'];

    $logged_user = new User();
    $logged_user->id = $token['id'];
    $logged_user->id_role = $token['role'];

    // Récupération identité de l'user connecté
    if($role == 3){
        $loggedUser = new GestionnaireCentre();
    } elseif ($role == 4){
        $loggedUser = new Formateur();
    }
    
    $loggedUser->id_users = $id;
    // Récupération batiment de l'user (entreprise/centre)
    $loggedStructureId = $logged_user->searchIdCentreForIdUsers();

    // Pré-recherche infos de l'user recherché
    $user = new User(); 
    $user->id = $param['byUserId'];
    $userInfos = $user->getInfoForId();

    if(empty($userInfos)){
        $response->getBody()->write(json_encode(['erreur' => "L'utilisateur recherché n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $user->role = $userInfos['id_role'];

    // Rôle de la personne recherchée
    switch ($user->role) {
        case 1: // Admin
            $userTable = new Admin();
            break;

        case 2: // Gestionnaire entreprise
            $userTable = new GestionnaireEntreprise();
            break;

        case 3: // Gestionnaire centre
            $userTable = new GestionnaireCentre();
            break;
        
        case 4: // Formateur
            $userTable = new Formateur();
            break;

        case 5: // Etudiant
            $userTable = new Etudiant();
            break;

        case 6: // Financeur
            $userTable = new Financeur();
            break;

        default:
            $response->getBody()->write(json_encode(['erreur' => 'Erreur inattendue : Rôle invalide']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $userTable->id_users = $user->id;
    $userRoleInfos = $userTable->getRoleInfosForId();

    if($user->role == 2){
        $accessCheck = new Etudiant();
        $accessCheck->id_entreprises = $userRoleInfos['id_entreprises'];
        $accessCheck->id_centres_de_formation = $loggedStructureId;
        
        $hasAccess = $accessCheck->centre_hasAccessToEntreprise();
        $userRoleInfos = $hasAccess ?  $userRoleInfos : NULL;
    }

    if(in_array($user->role, [3,4,5])){ // Si l'user recherché fait partie d'un centre
        // Vide les données si l'user recherché ne fait partie du centre du logged user
        $userRoleInfos = ($userRoleInfos['id_centres_de_formation'] == $loggedStructureId) ?  $userRoleInfos : NULL;
    }

    if($user->role == 6){
        $accessCheck = new Etudiant();
        $accessCheck->id_entreprises = $userRoleInfos['id_entreprises'];
        $accessCheck->id_centres_de_formation = $loggedStructureId;
        
        $hasAccess = $accessCheck->centre_hasAccessToFinanceur();
        $userRoleInfos = $hasAccess ?  $userRoleInfos : NULL;
    }
    
    // Si la 2e partie des détails est vide :
    if(empty($userRoleInfos)){
        $response->getBody()->write(json_encode(['erreur' => "Erreur dans la récupération du profil"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $data['userInfos'] = $userInfos;
    $data['roleInfos'] = $userRoleInfos;

    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $data]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
})->add($auth)->add($checkEquipePedagogique);


//========================================================================================
// But : Récupérer le profil d'un utilisateur
// Rôles : Gestionnaire de centre
// Champs : aucun
//========================================================================================

$app->get('/api/etudiant/user/{byUserId}/details', function (Request $request, Response $response, $param) use ($key) {
    $token=$request->getAttribute('user');

    $id = $token['id'];
    $role = $token['role'];
    $idByRole = $token['idByRole'];

    // Récupération identité de l'user connecté
    $loggedUser = new Etudiant();
    
    $loggedUser->id_users = $id;
    // Récupération batiment de l'user (entreprise/centre)
    $loggedStructureId = $loggedUser->getStructure();
    $loggedEntrepriseId = $loggedUser->getEntreprise();
    $loggedFinanceurId = $loggedUser->getFinanceur();

    // Pré-recherche infos de l'user recherché
    $user = new User(); 
    $user->id = $param['byUserId'];
    $userInfos = $user->getInfoForId();

    if(empty($userInfos)){
        $response->getBody()->write(json_encode(['erreur' => "L'utilisateur recherché n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $user->role = $userInfos['id_role'];

    // Rôle de la personne recherchée
    switch ($user->role) {
        case 1: // Admin
            $userTable = new Admin();
            break;

        case 2: // Gestionnaire entreprise
            $userTable = new GestionnaireEntreprise();
            break;

        case 3: // Gestionnaire centre
            $userTable = new GestionnaireCentre();
            break;
        
        case 4: // Formateur
            $userTable = new Formateur();
            break;

        case 5: // Etudiant
            $userTable = new Etudiant();
            break;

        case 6: // Financeur
            $userTable = new Financeur();
            break;

        default:
            $response->getBody()->write(json_encode(['erreur' => 'Erreur inattendue : Rôle invalide']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $userTable->id_users = $user->id;
    $userRoleInfos = $userTable->getRoleInfosForId();

    if($user->role == 2){
        $userRoleInfos = ($userRoleInfos['id_entreprises'] == $loggedEntrepriseId) ?  $userRoleInfos : NULL;
    }

    if(in_array($user->role, [3,4,5])){ // Si l'user recherché fait partie d'un centre
        // Vide les données si l'user recherché ne fait partie du centre du logged user
        $userRoleInfos = ($userRoleInfos['id_centres_de_formation'] == $loggedStructureId) ?  $userRoleInfos : NULL;
    }

    if($user->role == 6){
        $userRoleInfos = ($userRoleInfos['id'] == $loggedFinanceurId) ?  $userRoleInfos : NULL;
    }
    
    // Si la 2e partie des détails est vide :
    if(empty($userRoleInfos)){
        $response->getBody()->write(json_encode(['erreur' => "Erreur dans la récupération du profil"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $data['userInfos'] = $userInfos;
    $data['roleInfos'] = $userRoleInfos;

    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $data]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
})->add($auth)->add($checkEtudiant);


//========================================================================================
// But : Récupérer le profil d'un utilisateur
// Rôles : Financeurs
// Champs : aucun
//========================================================================================

$app->get('/api/financeur/user/{byUserId}/details', function (Request $request, Response $response, $param) use ($key) {
    $token=$request->getAttribute('user');

    $id = $token['id'];
    $role = $token['role'];
    $idByRole = $token['idByRole'];

    // Récupération identité de l'user connecté
    $loggedUser = new Financeur();
    
    $loggedUser->id_users = $id;
    // Récupération batiment de l'user (entreprise/centre)
    $loggedStructureId = $loggedUser->getStructure();

    // Pré-recherche infos de l'user recherché
    $user = new User(); 
    $user->id = $param['byUserId'];
    $userInfos = $user->getInfoForId();

    if(empty($userInfos)){
        $response->getBody()->write(json_encode(['erreur' => "L'utilisateur recherché n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $user->role = $userInfos['id_role'];

    // Rôle de la personne recherchée
    switch ($user->role) {
        case 1: // Admin
            $userTable = new Admin();
            break;

        case 2: // Gestionnaire entreprise
            $userTable = new GestionnaireEntreprise();
            break;

        case 3: // Gestionnaire centre
            $userTable = new GestionnaireCentre();
            break;
        
        case 4: // Formateur
            $userTable = new Formateur();
            break;

        case 5: // Etudiant
            $userTable = new Etudiant();
            break;

        case 6: // Financeur
            $userTable = new Financeur();
            break;

        default:
            $response->getBody()->write(json_encode(['erreur' => 'Erreur inattendue : Rôle invalide']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $userTable->id_users = $user->id;
    $userRoleInfos = $userTable->getRoleInfosForId();

    if($user->role == 2){ 
        if($loggedStructureId != $userRoleInfos['id_entreprises']){ // Si gestionnaire d'entreprise ne fait pas partie de l'entreprise de l'user
            $accessCheck = new Etudiant();
            // On vérifie : 
            // - Qu'un étudiant fait partie de l'entreprise de l'user cherché
            // - Et qu'un étudiant a l'user connecté comme conseiller
            $accessCheck->id_entreprises = $userRoleInfos['id_entreprises'];
            $accessCheck->id_conseillers_financeurs = $id;
            
            $hasAccess = $accessCheck->entreprise_hasAccessToFinanceur();
            $userRoleInfos = $hasAccess ?  $userRoleInfos : NULL;
        } 
    }

    if(in_array($user->role, [3,4,5])){ // Si l'user recherché fait partie d'un centre
        // Vide les données si l'user recherché ne fait partie du centre du logged user
        $accessCheck = new Etudiant();
        // On vérifie : 
        // - Qu'un étudiant fait partie du centre de l'user cherché
        // - Et qu'un étudiant a aussi l'user connecté comme conseiller
        $accessCheck->id_centres_de_formation = $userRoleInfos['id_centres_de_formation'];
        $accessCheck->id_conseillers_financeurs = $id;
        
        $hasAccess = $accessCheck->centre_hasAccessToFinanceur();
        $userRoleInfos = $hasAccess ?  $userRoleInfos : NULL;    }

    if($user->role == 6){ // Financeur
        // Si le financeur ne fait pas partie de l'entreprise de la personne connectée
        $userRoleInfos = ($userRoleInfos['id_entreprises'] == $loggedStructureId) ?  $userRoleInfos : NULL;
    }
    
    // Si la 2e partie des détails est vide :
    if(empty($userRoleInfos)){
        $response->getBody()->write(json_encode(['erreur' => "Erreur dans la récupération du profil"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $data['userInfos'] = $userInfos;
    $data['roleInfos'] = $userRoleInfos;

    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $data]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
})->add($auth)->add($checkFinanceur);

// Récupération des informations pour création compte apolearn
$app->get('/api/user/{id_users}/apolearn/preinfos', function (Request $request, Response $response, $param) use ($key) {
    $token=$request->getAttribute('user');

    $user = new User(); 
    $user->id = $token['id'];
    $user->role = $token['role'];

    try {
        if(filter_var($param['id_users'], FILTER_VALIDATE_INT) == false){
            throw new Exception('Paramètre au mauvais format');
        }

        $target = new User();
        $target->id = $param['id_users'];
        $target->id_role = $target->searchIdRoleForIdUsers();

        if(empty($target->id_role)){
            throw new Exception("Impossible de récupérer le rôle de l'utilisateur recherché");
        }


        switch ($user->role) {
            case 3:
                $user_id_centre = $user->searchIdCentreForIdUsers();
                $target_id_centre = $target->searchIdCentreForIdUsers();
                if($user_id_centre != $target_id_centre){
                    throw new Exception("Accès interdit");
                }
                break;
        }

        $result = $target->getApolearnInfos();

        if(empty($result)){
            throw new Exception ("Aucune information retrouvée");
        } else {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth);
