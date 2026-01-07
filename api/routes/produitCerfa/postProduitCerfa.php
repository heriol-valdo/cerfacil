<?php // /routes/etudiant/postEtudiant.php;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../../vendor/autoload.php';


require_once __DIR__.'/../../models/ProduitCerfaModel.php';



$app->post('/api/addProduitCerfa', function (Request $request, Response $response) use ($key) {
    $token=$request->getAttribute('user');
    $role=intval($token['role']);
    $data = $request->getParsedBody();

    // Check : Intégrité du formulaire
    // -- Intégrité => Champs obligatoires
    if(!isset($data['nom'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ nom doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    
    if(!isset($data['type'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ type doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['prix_dossier'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ prix_dossier doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['prix_abonement'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ prix_abonement doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['caracteristique1'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ caracteristique1 doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['caracteristique2'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ caracteristique2 doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['caracteristique3'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ caracteristique3 doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!isset($data['caracteristique4'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ caracteristique4 doit figurer sur le formulaire"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    
    
    if(empty($data['nom'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ nom est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['type'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ type est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['prix_dossier'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ prix_dossier est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
   
    if(empty($data['caracteristique1'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ caracteristique1 est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(empty($data['caracteristique2'])){
        $response->getBody()->write(json_encode(['erreur' => "Le champ caracteristique2 est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    
   
    if ($role == 1) {
        try {
           
    
            $produitCerfa = new Produit(); 
            $produitCerfa->type = $data['type'];
            if(($produitCerfa->boolType())){
                $response->getBody()->write(json_encode(['erreur' => "un produit avec ce type existe deja"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
            $produitCerfa->nom = $data['nom'];
            $produitCerfa->type = $data['type'];
            $produitCerfa->prix_dossier = $data['prix_dossier'];
            $produitCerfa->prix_abonement = $data['prix_abonement'];
            $produitCerfa->caracteristique1 = $data['caracteristique1'];
            $produitCerfa->caracteristique2 = $data['caracteristique2'];
            $produitCerfa->caracteristique3 = $data['caracteristique3'];
            $produitCerfa->caracteristique4 = $data['caracteristique4'];
        
            $result = $produitCerfa->addProduitCerfa();
    
            if ($result['success']) {
                $response->getBody()->write(json_encode([
                    'valid' => 'Vous avez ajouté un produit'
                ]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
            } else {
                throw new Exception($result['message']);
            }
        } catch (InvalidArgumentException $e) {
            $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['erreur' => "Une erreur est survenue lors de l'ajout du produit: " . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    } else {
        $response->getBody()->write(json_encode(['erreur' => "Vous n'avez pas les droits nécessaires pour effectuer cette action"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
    }

    
    

})->add($auth);
