<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/PointageModel.php';
require_once __DIR__.'/../../models/PointagesinfoModel.php';
require_once __DIR__.'/../../models/SessionModel.php';

//========================================================================================
// But : Pour la personne connectée, retourne si elle a pointé ou pas
// Rôles : Etudiant
// champs : Aucun
//========================================================================================

$app->get('/api/pointage/status', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    $pointage = new Pointage();

    // Check => Si étudiant a pointé ou pas
    $pointage->id_etudiants = $token['idByRole']->id;

    $data = [
        "has_pointed" => $pointage->boolPointed(),
        "last_pointed_info" => $pointage->lastPointedInfo(),
        "currentTime" => $pointage->currentTime()
    ];

    if (empty($data)) {
        $response->getBody()->write(json_encode(['erreur' => "Il n'y a aucun résultat"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    } else {
        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $data]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
})->add($auth)->add($checkEtudiant);

//========================================================================================
// But : Récupère l'historique de pointage de un étudiant
// Rôles : Tous
// champs : byEtudiantId ou byEtudiantEmail ou byEtudiantNom
//========================================================================================
$app->get('/api/pointage/{identifier}/historique', function (Request $request, Response $response, $args) {
    $pointageInfo = new PointagesInfo();

    // Récupérer l'identifiant (ID, email ou nom) à partir de l'URL
    $identifier = $args['identifier'];

    $historique = null;

    // Vérifier si l'identifiant est numérique (ID)
    if (is_numeric($identifier)) {
        $historique = $pointageInfo->getPointagesInfoByEtudiantId(intval($identifier));
    } elseif (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        // Vérifier si l'identifiant est un email
        $historique = $pointageInfo->getPointagesInfoByEtudiantEmail($identifier);
    } else {
        // Sinon, considérer l'identifiant comme un nom
        $historique = $pointageInfo->getPointagesInfoByEtudiantNom($identifier);
    }

    // Vérifier si des données ont été trouvées
    if (empty($historique)) {
        $response->getBody()->write(json_encode(['erreur' => "Il n'y a aucun résultat"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    } else {
        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $historique]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
})->add($auth);


//========================================================================================
// But : Récupère l'historique de pointage de un étudiant
// Rôles : Gestionnaire de centre et Formateur
// champs : byCentreId
//========================================================================================

$app->get('/api/pointage/centre/{byCentreId}/historique', function (Request $request, Response $response, $args) {
    try {
        // Récupération et débogage du token
        $token = $request->getAttribute('user');
        $role = intval($token['role']);

        // Débogage du token et du rôle
        if (!$token || !$role) {
            throw new Exception("Token ou rôle manquant.");
        }

        // Récupérer l'ID du centre de formation à partir des paramètres de l'URL
        if (!isset($args['byCentreId']) || !is_numeric($args['byCentreId'])) {
            throw new Exception("ID du centre manquant ou non valide.");
        }
        $centreId = intval($args['byCentreId']);
        
        // Débogage de l'ID du centre
        if ($centreId <= 0) {
            throw new Exception("ID du centre non valide.");
        }

        $pointageInfo = new PointagesInfo();
        $pointageInfo->id_centres_de_formation = $centreId;
        $historique = null;

        // Vérifier si l'utilisateur est Formateur (role 4) ou Gestionnaire de centre (role 3)
        if ($role === 4 || $role === 3) {
            // Récupérer les informations de pointage des étudiants appartenant au centre de formation spécifié
            $historique = $pointageInfo->getPointagesInfoByCentreId();
        } else {
            throw new Exception("Accès refusé");
        }

        // Vérifier si des données ont été trouvées
        if (empty($historique)) {
            throw new Exception("Il n'y a aucun résultat pour ce centre");
        } else {
            $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $historique]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
    } catch (Exception $e) {
        // En cas d'erreur, attrapez l'exception et renvoyez une réponse d'erreur
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth);



//========================================================================================
// But : Récupère le statut des pointages d'une session
// Rôles : Tous, sauf étudiant
// champs : bySessionId
//========================================================================================
$app->get('/api/pointage/session/{bySessionId}/status', function (Request $request, Response $response, $param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    $session = new Session();
    $session->id = $param['bySessionId'];

    $result = $session->getPointageStatus();
    if (empty($result)) {
        $response->getBody()->write(json_encode(['erreur' => "Il n'y a aucun résultat"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    
})->add($auth)->add($checkNotEtudiant);


/*========================================================================================================================
But : Récupérer les pointages d'un étudiant : Total, total par mois, total par jour par mois
Rôles : Tous
Param : id (id users)
=========================================================================================================================*/
$app->get('/api/pointages/etudiant/{id}', function (Request $request, Response $response,$param) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);

    try{
        if(filter_var($param['id'], FILTER_VALIDATE_INT) === false || $param['id'] < 1){
            throw new Exception('Format du paramètre invalide');
        }

        $etudiant = new Etudiant();
        $etudiant->id_users = $param['id'];
        $etudiantInfos = $etudiant->infos();
        if(empty($etudiantInfos)){
            throw new Exception("Erreur dans la récupération du profil de l'étudiant");
        }

        switch($role){
            case 2: // Gestionnaire Entreprise 
                $gEntreprise = new GestionnaireEntreprise();
                $gEntreprise->id_users = $token['id'];
                $gEntreprise_id_entreprise = $gEntreprise->getStructure();
                if($gEntreprise_id_entreprise != $etudiantInfos[0]['id_entreprises']){
                    throw new Exception('Accès interdit');
                }
                break; 

            case 3: // Gestionnaire Centre
            case 4: // Formateur
                if($token['centre'] != $etudiantInfos[0]['id_centres_de_formation']){
                    throw new Exception('Accès interdit');
                }
                break;

            case 5: // Etudiant
                if($token['id'] != $etudiantInfos[0]['id_users']){
                    throw new Exception('Accès interdit');
                }
                break;
                
            case 6: // Financeur
                $financeur = new Financeur();
                $financeur->id_users = $token['id'];

                $etudiant->id_entreprises = $financeur->searchEntrepriseForIdUsers();
                if($etudiant->isFinancedByEntreprise() == false){
                    throw new Exception('Accès interdit');
                }
                break;
        }

        $pointage = new Pointage();
        $pointage->id_etudiants = $etudiantInfos[0]['id'];
        $totalHours = $pointage->totalHours();
        if(empty($totalHours)){
            throw new Exception('Erreur dans la récupération des données');
        }
        $totalHoursMonth = $pointage->totalHoursPerMonth();
        $totalHoursDay = $pointage->totalHoursPerDay();

        $result = [
            'id_users' => $etudiant->id_users,
            'id_etudiants' => $pointage->id_etudiants,
            'total_hours' => $totalHours,
            'monthly' => []
        ];

        if(is_array($totalHoursMonth) == true){
            foreach ($totalHoursMonth as $month => $hours) {
                $result['monthly'][$month] = [
                    'month_hours' => $hours,
                    'days' => []
                ];
            }
        }
        
        if(is_array($totalHoursDay) == true){
            foreach ($totalHoursDay as $day => $dayHours) {
                $month = substr($day, 0, 7); 
                $result['monthly'][$month]['days'][$day] = $dayHours;
            }
        }

        $response->getBody()->write(json_encode(['valid' => "Récupération des données réussie", 'data' => $result]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
})->add($auth);