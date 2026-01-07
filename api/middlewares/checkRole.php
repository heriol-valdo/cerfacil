<?php // /middlewares/checkRole.php
use Slim\Factory\AppFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Psr7\Response;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

/*========================================
// Vérifie si l'user est bien du bon rôle
//========================================
checkRole : [array des roles autorisés]
Admin : $checkAdmin
Admin, Financeur : $checkAdminFinanceur
Admin, GestionnaireCentre : $checkAdminGestionnaireCentre
Admin, GCentre, Financeur : $checkGCentreFinanceur
Etudiant : $checkEtudiant
Financeur : $checkFinanceur
GestionnaireCentre : $checkGestionnaireCentre
GestionnaireEntreprise : $checkGestionnaireEntreprise
PAS étudiant, PAS financeur : $checkNotEtudiantFinanceur
Formateur, GestionnaireCentre : $checkEquipePedagogique
Admin, Formateur, GestionnaireCentre : $checkAdminEquipePedagogique
*/

$check_role = function ($allowedRoles) use ($key) {
    return function ($request, $handler) use ($allowedRoles, $key) {
        $token = $request->getHeader('token');
        $response = new Response();

        if (empty($token)) {
            $response->getBody()->write(json_encode(['erreur' => 'Token manquant']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        try {
            // Decode the token using the key
            $decoded = JWT::decode($token[0], new Key($key, 'HS256'));
            $role = $decoded->role;

            // Check if the role is in the allowed roles
            if (in_array($role, $allowedRoles)) {
                // If the role is allowed, proceed to the next middleware/handler
                return $handler->handle($request);
            } else {
                // If the role is not allowed, return a 403 response
                $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits suffisants"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['erreur' => 'Token invalide', 'détails' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    };
};


$checkAdmin = function ($request, $handler) use ($key) {
    $token = $request->getHeader('token');
    try{
        $decoded = JWT::decode($token[0], new Key($key, 'HS256'));
        $role = $decoded->role;

        if($role == '1'){
            return $handler->handle($request);
        } else {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['erreur' => "vous n'avez pas les droits suffisants"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    } catch (Exception $e){
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['erreur' => 'Token invalide']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
};

$checkEtudiant = function ($request, $handler) use ($key) {
    $token = $request->getHeader('token');
    try{
        $decoded = JWT::decode($token[0], new Key($key, 'HS256'));
        $role = $decoded->role;

        if($role == '5'){
            return $handler->handle($request);
        } else {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['erreur' => "vous n'avez pas les droits suffisants"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    } catch (Exception $e){
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['erreur' => 'Token invalide']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
};

$checkAdminFinanceur = function ($request, $handler) use ($key) {
    $token = $request->getHeader('token');
    try{
        $decoded = JWT::decode($token[0], new Key($key, 'HS256'));
        $role = $decoded->role;

        if($role == '1' || $role == '6'){
            return $handler->handle($request);
        } else {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['erreur' => "vous n'avez pas les droits suffisants"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400); 
        }
    } catch (Exception $e){
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['erreur' => 'Token invalide']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
};


$checkAdminGestionnaireCentre = function ($request, $handler) use ($key) {
    $token = $request->getHeader('token');
    try{
        $decoded = JWT::decode($token[0], new Key($key, 'HS256'));
        $role = $decoded->role;

        if($role == '1' || $role == '3'){
            return $handler->handle($request);
        } else {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['erreur' => "vous n'avez pas les droits suffisants"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400); 
        }
    } catch (Exception $e){
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['erreur' => 'Token invalide']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
};

$checkGestionnaireCentre = function ($request, $handler) use ($key) {
    $token = $request->getHeader('token');
    try{
        $decoded = JWT::decode($token[0], new Key($key, 'HS256'));
        $role = $decoded->role;

        if($role == '3'){
            return $handler->handle($request);
        } else {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['erreur' => "vous n'avez pas les droits suffisants"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400); 
        }
    } catch (Exception $e){
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['erreur' => 'Token invalide']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
};

$checkFinanceur = function ($request, $handler) use ($key) {
    $token = $request->getHeader('token');
    try{
        $decoded = JWT::decode($token[0], new Key($key, 'HS256'));
        $role = $decoded->role;

        if($role == '6'){
            return $handler->handle($request);
        } else {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['erreur' => "vous n'avez pas les droits suffisants"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400); 
        }
    } catch (Exception $e){
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['erreur' => 'Token invalide']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
};

$checkGestionnaireEntreprise = function ($request, $handler) use ($key) {
    $token = $request->getHeader('token');
    try{
        $decoded = JWT::decode($token[0], new Key($key, 'HS256'));
        $role = $decoded->role;

        if($role == '2'){
            return $handler->handle($request);
        } else {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['erreur' => "vous n'avez pas les droits suffisants"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400); 
        }
    } catch (Exception $e){
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['erreur' => 'Token invalide']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
};

$checkNotEtudiantFinanceur = function ($request, $handler) use ($key) {
    $token = $request->getHeader('token');
    try{
        $decoded = JWT::decode($token[0], new Key($key, 'HS256'));
        $role = $decoded->role;

        if($role == '5' || $role == '6'){
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['erreur' => "vous n'avez pas les droits suffisants"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400); 
        } else {
            return $handler->handle($request);
        }
    } catch (Exception $e){
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['erreur' => 'Token invalide']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
};

$checkNotEtudiant = function ($request, $handler) use ($key) {
    $token = $request->getHeader('token');
    try{
        $decoded = JWT::decode($token[0], new Key($key, 'HS256'));
        $role = $decoded->role;

        if($role == '5'){
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['erreur' => "vous n'avez pas les droits suffisants"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400); 
        } else {
            return $handler->handle($request);
        }
    } catch (Exception $e){
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['erreur' => 'Token invalide']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
};

$checkNotEntrepriseFinanceur = function ($request, $handler) use ($key) {
    $token = $request->getHeader('token');
    try{
        $decoded = JWT::decode($token[0], new Key($key, 'HS256'));
        $role = $decoded->role;

        if($role == '2' || $role == '6'){
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['erreur' => "vous n'avez pas les droits suffisants"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400); 
        } else {
            return $handler->handle($request);
        }
    } catch (Exception $e){
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['erreur' => 'Token invalide']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
};

$checkEquipePedagogique = function ($request, $handler) use ($key) {
    $token = $request->getHeader('token');
    try{
        $decoded = JWT::decode($token[0], new Key($key, 'HS256'));
        $role = $decoded->role;

        if($role == '3' || $role == '4'){
            return $handler->handle($request);   
        } else {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['erreur' => "vous n'avez pas les droits suffisants"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400); 
        }
    } catch (Exception $e){
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['erreur' => 'Token invalide']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
};

$checkAdminEquipePedagogique = function ($request, $handler) use ($key) {
    $token = $request->getHeader('token');
    try{
        $decoded = JWT::decode($token[0], new Key($key, 'HS256'));
        $role = $decoded->role;

        if($role == '1' || $role == '3' || $role == '4'){
            return $handler->handle($request);
        } else {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['erreur' => "vous n'avez pas les droits suffisants"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400); 
        }
    } catch (Exception $e){
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['erreur' => 'Token invalide 2']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
};

$checkEtudiantFormateur = function ($request, $handler) use ($key) {
    $token = $request->getHeader('token');
    try{
        $decoded = JWT::decode($token[0], new Key($key, 'HS256'));
        $role = $decoded->role;

        // Formateur, Etudiant
        if($role == '4' || $role == '5'){
            return $handler->handle($request);
        } else {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['erreur' => "vous n'avez pas les droits suffisants"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400); 
        }
    } catch (Exception $e){
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['erreur' => 'Token invalide 2']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
};


$checkGCentreFinanceur = function ($request, $handler) use ($key) {
    $token = $request->getHeader('token');
    try{
        $decoded = JWT::decode($token[0], new Key($key, 'HS256'));
        $role = $decoded->role;

        // Formateur, Etudiant
        if($role == '1' || $role == '3' || $role == '6'){
            return $handler->handle($request);
        } else {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['erreur' => "vous n'avez pas les droits suffisants"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400); 
        }
    } catch (Exception $e){
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['erreur' => 'Token invalide 2']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
};