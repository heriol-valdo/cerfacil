<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/EntreprisesModel.php';
require_once __DIR__.'/../../models/ClientCerfaModel.php';

require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';
require_once __DIR__.'/../../models/CerfaModel.php';



$app->post('/api/entrepriseByNom', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data = $request->getParsedBody();
    $requiredKeys = ['nomE'];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['error' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
    $nom  = isset($data['nomE']) ? $data['nomE'] : null;

    if ($role === 7 || $role === 3) {
        $entreprise = new Entreprises();
        $entreprises = $entreprise->byNom($nom); 
        if (!empty($entreprises)) {
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie','data' => $entreprises]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "tableau vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);


$app->post('/api/entrepriseFind', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $id=$data['id'];
        $entreprise = new Entreprises();
        $entreprises = $entreprise->find($id);
        if (!empty($entreprises)) {
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie','data' => $entreprises]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "tableau vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
   
});


$app->post('/api/entrepriseByEmail', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data = $request->getParsedBody();
    $email = isset($data['emailE']) ? trim($data['emailE']) : '';

    // Vérification des droits
    if (!in_array($role, [3, 5, 6, 7])) {
        $response->getBody()->write(json_encode([
            'erreur' => 'Accès non autorisé',
            'detail' => 'Votre rôle ne vous permet pas cette action'
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
    }

    // Vérification de l'email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response->getBody()->write(json_encode([
            'erreur' => 'Email invalide ou manquant',
            'debug' => ['input' => $data]
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $entrepriseModel = new Entreprises();
    $entreprises = $entrepriseModel->byEmail($email);
    $cerfaModel = new Cerfa();

    $tableauCerfas = [];
    $debug_info = [
        'input_email' => $email,
        'raw_entreprises' => $entreprises,
        'entreprises_validées' => [],
        'cerfas_trouvés' => [],
        'warnings' => []
    ];

    // Aucune entreprise trouvée
    if (empty($entreprises)) {
        $response->getBody()->write(json_encode([
            'erreur' => "Aucune entreprise trouvée avec l'email: $email",
            'debug' => $debug_info
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }

    // CORRECTION PRINCIPALE: Vérifier si c'est un tableau ou un objet unique
    if (!is_array($entreprises)) {
        $entreprises = [$entreprises]; // Convertir en tableau pour traitement uniforme
    }

    // Parcours des entreprises trouvées
    foreach ($entreprises as $index => $entrepriseItem) {
    // Correction pour des tableaux contenant un seul objet
    if (is_array($entrepriseItem) && count($entrepriseItem) === 1 && is_object($entrepriseItem[0])) {
        $entrepriseItem = $entrepriseItem[0];
    }

    // Si encore un array associatif, cast en objet
    if (is_array($entrepriseItem)) {
        $entrepriseItem = (object)$entrepriseItem;
    }

    // Vérification finale que c'est bien un objet
    if (!is_object($entrepriseItem)) {
        $debug_info['warnings'][] = [
            'index' => $index,
            'warning' => 'Élément non objet (après correction)',
            'type' => gettype($entrepriseItem),
            'value' => $entrepriseItem
        ];
        continue;
    }

    if (!property_exists($entrepriseItem, 'id')) {
        $debug_info['warnings'][] = [
            'index' => $index,
            'warning' => 'Objet entreprise sans propriété "id"',
            'objet' => $entrepriseItem
        ];
        continue;
    }

    $entrepriseId = $entrepriseItem->id;

    $debug_info['entreprises_validées'][] = [
        'id' => $entrepriseId,
        'nom' => $entrepriseItem->nomE ?? '',
        'email' => $entrepriseItem->emailE ?? ''
    ];

    $cerfas = $cerfaModel->findbyentrepriseForApp($entrepriseId);

    $debug_info['cerfas_trouvés'][] = [
        'entreprise_id' => $entrepriseId,
        'nombre' => is_array($cerfas) ? count($cerfas) : 0,
        'cerfas' => $cerfas
    ];

    if (!empty($cerfas) && is_array($cerfas)) {
        $tableauCerfas = array_merge($tableauCerfas, $cerfas);
    }
}


    // Résultat final
    if (!empty($tableauCerfas)) {
        $response->getBody()->write(json_encode([
            'valid' => true,
            'data' => $tableauCerfas,
            'debug' => $debug_info
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        // Debug supplémentaire pour comprendre pourquoi aucun CERFA n'est trouvé
        $debug_info['suggestion'] = [
            'check_query' => "SELECT * FROM cerfa WHERE entreprise_id = [ID_ENTREPRISE]",
            'check_table' => "Vérifiez que la table cerfa contient des enregistrements pour cette entreprise"
        ];
        
        $response->getBody()->write(json_encode([
            'success' => false,
            'erreur' => "Aucun CERFA trouvé pour l'entreprise",
            'debug' => $debug_info
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
})->add($auth);



$app->post('/api/entrepriseFindByIdOpco', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $id=$data['idopco'];

    if ($role === 7 || $role === 3) {
        $entreprise = new Entreprises();
        $entreprises = $entreprise->findbyopco($id);
        if (!empty($entreprises)) {
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie','data' => $entreprises]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => "tableau vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

$app->post('/api/entrepriseCountBySearchType', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $id = intval($token['id']);
    $search=$data['search'];

    if ($role === 7 || $role === 3) {
        $entreprise = new Entreprises();
     
      
        if($role === 7){
            $clientCerfa = new ClientCerfa();
            $clientCerfa->id_users = $id;
    
            $profilClient = $clientCerfa->getProfil();
    
            $effectiveUserId = $profilClient[0]['roleCreation'] == 2 ? $profilClient[0]['idCreation'] : $id;
            $result = $entreprise->countBySearchType($effectiveUserId,$search);

        }else{
            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_users = $id;
            $datagestionnairescentre = $gestionnaireCentre->searchForId();
            $effectiveUserId = $datagestionnairescentre['id_centres_de_formation'];
            $result = $entreprise->countBySearchTypeIdCentre($effectiveUserId,$search);
          
        }


       
        if (isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } else{
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie', 'data' => $result]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);

$app->post('/api/entrepriseSearchType', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $data =$request->getParsedBody();
    $search=$data['search'];
    $pageCourante=$data['pageCourante'];
    $nbreParPage=$data['nbreParPage'];
    $id = intval($token['id']);

    if ($role === 7 || $role === 3) {
        $entreprise = new Entreprises();
     

        if($role === 7){
            $clientCerfa = new ClientCerfa();
            $clientCerfa->id_users = $id;
    
            $profilClient = $clientCerfa->getProfil();
    
            $effectiveUserId = $profilClient[0]['roleCreation'] == 2 ? $profilClient[0]['idCreation'] : $id;

            $result = $entreprise->searchType( $effectiveUserId,$nbreParPage,$pageCourante,$search);

        }else{
            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_users = $id;
            $datagestionnairescentre = $gestionnaireCentre->searchForId();
            $effectiveUserId = $datagestionnairescentre['id_centres_de_formation'];

            $result = $entreprise->searchTypeIdCentre($effectiveUserId,$nbreParPage,$pageCourante,$search);
          
        }

      
        if (isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } else{
            $response->getBody()->write(json_encode(['valid' => 'Récupération des données réussie', 'data' => $result]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
})->add($auth);


