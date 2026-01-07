<?php // /routes/equipement/getCours.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/../../models/CoursModel.php';
require_once __DIR__.'/../../models/SessionModel.php';

//========================================================================================
// But : Permet de récupérer les cours d'une session avec vérifications d'existence de session 
// et d'autorisations d'accès selon le rôle de l'utilisateur.
// Rôles : administrateur, gestionnaire centre, formateur
// Champs obligatoires : idSession
// Champs facultatifs : aucun
//========================================================================================

$app->get('/api/session/{idSession}/cours', function (Request $request, Response $response, $args) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userId = $token['id'];

    $idSession = $args['idSession'];

    $session = new Session();
    $session->id = $idSession;

    if (!$session->boolId()) {
        $response->getBody()->write(json_encode(['erreur' => "La session spécifiée n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Vérification des droits d'accès
    if ($role == 5) { // Étudiant
        $etudiant = new Etudiant();
        $etudiant->id_users = $userId;
        $etudiantData = $etudiant->searchForId();
        if (!$etudiantData || $etudiantData['id_session'] != $idSession) {
            $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas accès à cette session"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
        }
    } elseif ($role == 3 || $role == 4) { // Gestionnaire de centre ou Formateur
        $centreDeLaSession = $session->getCentreForId();
        if ($centreDeLaSession['id_centres_de_formation'] != $token['centre']) {
            $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas accès à cette session"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
        }
    }

    $cours = new Cours();
    $coursDeLaSession = $cours->getCoursForSession($idSession);

    if (empty($coursDeLaSession)) {
        $response->getBody()->write(json_encode(['message' => "Aucun cours trouvé pour cette session"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    $response->getBody()->write(json_encode(['data' => $coursDeLaSession]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
})->add($auth)->add($check_role([1, 3, 4, 5]));


//========================================================================================
// But : Permet de récupérer les cours d'un formateur avec vérifications d'existence de formateur 
// et d'autorisations d'accès selon le rôle de l'utilisateur.
// Rôles : administrateur, gestionnaire centre, formateur
// Champs obligatoires : idFormateur
// Champs facultatifs : aucun
//========================================================================================


$app->get('/api/formateur/{idFormateur}/cours', function (Request $request, Response $response, $args) {
    $idFormateur = $args['idFormateur'];
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userId = $token['id'];
    $userIdByRole = $token['idByRole'];

    // Vérification de l'existence du formateur
    $formateur = new Formateur();
    $formateur->id = $idFormateur;
    if (!$formateur->boolIdRole()) {
        return $response->withJson(['erreur' => "Le formateur spécifié n'existe pas"], 404);
    }

    // Vérification des autorisations
    if ($role === 4) { // Formateur
        if ($userIdByRole != $idFormateur) {
            return $response->withJson(['erreur' => "Vous n'avez pas les droits pour accéder aux cours de ce formateur"], 403);
        }
    } elseif ($role === 3) { // Gestionnaire de centre
        $formateurCentre = $formateur->searchCentreForId();
        $userCentre = $token['centre'];
        if ($formateurCentre != $userCentre) {
            return $response->withJson(['erreur' => "Ce formateur n'appartient pas à votre centre"], 403);
        }
    } elseif ($role !== 1) { // Ni admin, ni gestionnaire, ni formateur
        return $response->withJson(['erreur' => "Vous n'avez pas les droits pour accéder à cette ressource"], 403);
    }

    // Récupération des cours
    $cours = new Cours();
    $cours->id_formateurs = $idFormateur;
    try {
        $coursList = $cours->getCoursForFormateur();

        if (empty($coursList)) {
            return $response->withJson(['message' => "Aucun cours trouvé pour ce formateur"], 200);
        }

        return $response->withJson(['cours' => $coursList], 200);
    } catch (Exception $e) {
        return $response->withJson(['erreur' => $e->getMessage()], 500);
    }
})->add($auth)->add($checkAdminEquipePedagogique);