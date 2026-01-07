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


$app->post('/api/addupdateEntreprises', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    $id=$data['id'];
    $nomE=$data['nomE'];
    $typeE=$data['typeE'];
    $specifiqueE=$data['specifiqueE'];
    $totalE=$data['totalE'];
    $siretE=$data['siretE'];
    $codeaE=$data['codeaE'];
    $codeiE=$data['codeiE'];
    $rueE=$data['rueE'];
    $voieE=$data['voieE'];
    $complementE=$data['complementE'];
    $postalE=$data['postalE'];
    $communeE=$data['communeE'];
    $emailE=$data['emailE'];
    $numeroE=$data['numeroE'];
    $idopco=$data['idopco'];
    

    if ($role === 7 || $role === 3) {
        $entreprise = new Entreprises();
        if($role === 7){
            $clientCerfa = new ClientCerfa();
            $clientCerfa->id_users =  $userConnected ;

            $profilClient = $clientCerfa->getProfil();

            $effectiveUserId = $profilClient[0]['roleCreation'] == 2 ? $profilClient[0]['idCreation'] :$userConnected;

            $result = $entreprise->save(null,$effectiveUserId,$nomE,$typeE,$specifiqueE,$totalE,$siretE,$codeaE,$codeiE,$rueE,$voieE,$complementE,$postalE,$communeE,$emailE,$numeroE,$idopco,  
            $id);

        }else{
            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_users = $userConnected;
            $datagestionnairescentre = $gestionnaireCentre->searchForId();
            $effectiveUserId = $datagestionnairescentre['id_centres_de_formation'];

            $result = $entreprise->save($effectiveUserId,null,$nomE,$typeE,$specifiqueE,$totalE,$siretE,$codeaE,$codeiE,$rueE,$voieE,$complementE,$postalE,$communeE,$emailE,$numeroE,$idopco,  
            $id);
          
        }


       
     
        if (!isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['valid' => 'Vous avez ajouté une entreprise']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }


    
})->add($auth);


$app->post('/api/sendEmailFormDataEmployeur', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    

    $requiredKeys = [
        'id'
    ];

    // Vérification que toutes les clés sont présentes et non vides
    foreach ($requiredKeys as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            $response->getBody()->write(json_encode(['error' => "La clé '$key' est manquante ou vide"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
   
    $id=$data['id'];
    

    if ($role === 7 || $role === 3) {
        $entreprise = new Entreprises();
        $entreprises = $entreprise->find($id);
        $result =Email::sendEmailFormDataEmployeur($entreprises['emailE'], $id);
        if($result){
            $response->getBody()->write(json_encode(['valid' => "Le formulaire a été envoyé  avec succès à l'entreprise"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $response->getBody()->write(json_encode(['erreur' => "Le formulaire n'a pas été envoyé  avec succès à l'entreprise"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }
    } else {
        $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }


    
})->add($auth);

$app->post('/api/updateEntreprises', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    $id=$data['id'];
    $typeE=$data['typeE'];
    $specifiqueE=$data['specifiqueE'];
    $totalE=$data['totalE'];
    $siretE=$data['siretE'];
    $codeaE=$data['codeaE'];
    $codeiE=$data['codeiE'];
    $rueE=$data['rueE'];
    $voieE=$data['voieE'];
    $complementE=$data['complementE'];
    $postalE=$data['postalE'];
    $communeE=$data['communeE'];
    $numeroE=$data['numeroE'];
    
    
   

   
        $entreprise = new Entreprises();
    


        $result = $entreprise->update($typeE,$specifiqueE,$totalE,$siretE,$codeaE,
        $codeiE,$rueE,$voieE,$complementE,$postalE,
        $communeE,$numeroE,  
        $id);
     
        if (!isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['valid' => 'Vous avez ajouté une entreprise']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['erreur' => $result['erreur']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    


    
});
