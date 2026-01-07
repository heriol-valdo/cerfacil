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

//========================================================================================
// But : Récupérer le profil d'un etudiant
// Rôles : Admins, gestionnaire de centre, formateur
// param :user_id
//========================================================================================
$app->get('/api/etudiant/profil/{user_id}', function (Request $request, Response $response, $param) use ($key) {

    $token=$request->getAttribute('user');
    $role=intval($token['role']);
    $userConnected = $token['id'];  

    try{
        if(filter_var($param['user_id'], FILTER_VALIDATE_INT) == false){
            throw new Exception("Paramètre au mauvais format");
        }

        $etudiant = new Etudiant(); 
        $etudiant->id_users = $param['user_id'];
        $etudiantDatas = $etudiant->getProfilEtudiant();
        if(empty($etudiantDatas)){
            throw new Exception("Impossible de récupérer le profil de l'étudiant");
        }
        $etudiantDatasFiltered = array_filter($etudiantDatas[0], function($value, $key) {
            return ($key !== 'id' && $key !== 'id_centres_de_formation' && $key !== 'id_users' && $key !== 'id_role');
        }, ARRAY_FILTER_USE_BOTH); 

        $etudiant_id_centre = $etudiantDatas[0]['id_centres_de_formation'];
        $has_access = false;
        switch ($role){
            case 1: // Admin
                $has_access = true;
                break;

            case 2: // Gestionnaire Entreprise
                $gEntreprise = new GestionnaireEntreprise();
                $gEntreprise->id = $token['id'];
                $gEntreprise_entreprise = $gEntreprise->getStructure();
                $has_access = $gEntreprise_entreprise == $etudiantDatas[0]['id_centres_de_formation'] ? true : false;
                break;

            case 3: // Gestionnaire Centre
            case 4: // Formateur
                $user = new User();
                $user->id = $token['id'];
                $user->id_role = $role;
                $user_centre = $user->getIdCentreForIdUsers();
                $has_access = $user_centre[0]["id_centres_de_formation"] == $etudiantDatas[0]['id_centres_de_formation'] ? true : false;
                break;

            case 5: // Etudiant
                $has_access = $token['id'] == $param['user_id'] ? true : false;
                break;

            case 6: // Conseiller financeur
                $financeur = new Financeur();
                $financeur->id_users = $token['id'];
                $financeur_entreprise = $financeur->getStructure();

                $etudiant->id_entreprises = $financeur_entreprise;
                $is_financed = $etudiant->isFinancedByEntreprise();
                if($is_financed){
                    $has_access = true;
                }
                break;     
        }

        if($has_access == false){
            throw new Exception('Accès interdit');
        }
        
        $centreFormation = new CentreFormation();
        $centreFormation->id = $etudiant_id_centre;
        $centreFormationDatas = $centreFormation->getCentreFormationDatas();
        $centreFormationDatasFiltered = array_filter($centreFormationDatas[0], function($value, $key) {
            return ($key !== 'id' && $key !== 'id_entreprises');
        }, ARRAY_FILTER_USE_BOTH);   
    

        $entreprise =new Entreprise();
        $entreprise->id=$etudiantDatas[0]['id_entreprises'];
        $entrepriseDatas= $entreprise->getEntrepriseDatas();
    
    
        $financeur=new Financeur();
        $financeur->id=$etudiantDatas[0]['id_conseillers_financeurs'];
        $financeurDatas= $financeur->getFinanceurDatas();


        $session = new Session();
        $session->id= $etudiantDatas[0]['id_session'];
        $sessionDatas=$session->getSessionDatas();
       

        $profilDatas = [
            'etudiantDatas'=>$etudiantDatasFiltered,
            'centreFormationDatas'=>$centreFormationDatasFiltered,
            'entrepriseDatas'=>$etudiantDatas[0]['id_entreprises'] !== null ? $entrepriseDatas : null,
            'financeurDatas'=>$etudiantDatas[0]['id_conseillers_financeurs'] !== null ? $financeurDatas : null,
            'sessionDatas'=>$etudiantDatas[0]['id_session'] !== null ? $sessionDatas : null
        ];
        
        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $profilDatas]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Exception $e){
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth);