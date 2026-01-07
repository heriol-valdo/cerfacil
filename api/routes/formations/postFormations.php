<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/FormationsModel.php';
require_once __DIR__.'/../../models/ClientCerfaModel.php';

require_once __DIR__.'/../../models/GestionnaireCentreModel.php';
require_once __DIR__.'/../../models/CentreFormationModel.php';


$app->post('/api/addupdateFormation', function (Request $request, Response $response) use ($key) {
    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];
    $data =$request->getParsedBody();
    $id=$data['id'];
    $nomF=$data['nomF'];
    $diplomeF=$data['diplomeF'];
    $intituleF=$data['intituleF']; 
    $numeroF=$data['numeroF'];
    $siretF=$data['siretF'];
    $codeF=$data['codeF'];
    $rnF=$data['rnF'];
    $entrepriseF=$data['entrepriseF'];
    $responsableF=$data['responsableF'];
    $prix=$data['prix'];
    $rueF=$data['rueF'];
    $voieF=$data['voieF'];
    $complementF=$data['complementF'];
    $postalF=$data['postalF'];
    $communeF=$data['communeF'];
    $emailF=$data['emailF'];
    $debutO=$data['debutO'];
    $prevuO=$data['prevuO'];
    $dureO=$data['dureO'];
    $nomO=$data['nomO'];
    $numeroO=$data['numeroO'];
    $siretO=$data['siretO'];
    $rueO=$data['rueO'];
    $voieO=$data['voieO'];
    $complementO=$data['complementO'];
    $postalO=$data['postalO'];
    $communeO=$data['communeO'];
   
    

    if ($role === 7 || $role === 3) {
        $formation = new Formations();
        
        if($role === 7){
            $clientCerfa = new ClientCerfa();
            $clientCerfa->id_users =  $userConnected ;

            $profilClient = $clientCerfa->getProfil();

            $effectiveUserId = $profilClient[0]['roleCreation'] == 2 ? $profilClient[0]['idCreation'] :$userConnected;

            $result = $formation->save(null,$effectiveUserId,
            $nomF,$diplomeF,$intituleF,$numeroF,$siretF,$codeF,$rnF,$entrepriseF,$responsableF,$prix,$rueF,$voieF,$complementF,$postalF,$communeF,$emailF,
            $debutO,$prevuO,$dureO,$nomO,$numeroO,$siretO,$rueO,$voieO,$complementO,$postalO,$communeO,
            $id);

        }else{
            $gestionnaireCentre = new GestionnaireCentre();
            $gestionnaireCentre->id_users = $userConnected;
            $datagestionnairescentre = $gestionnaireCentre->searchForId();
            $effectiveUserId = $datagestionnairescentre['id_centres_de_formation'];

            $result = $formation->save($effectiveUserId,null,
            $nomF,$diplomeF,$intituleF,$numeroF,$siretF,$codeF,$rnF,$entrepriseF,$responsableF,$prix,$rueF,$voieF,$complementF,$postalF,$communeF,$emailF,
            $debutO,$prevuO,$dureO,$nomO,$numeroO,$siretO,$rueO,$voieO,$complementO,$postalO,$communeO,
            $id);
          
        }

       
       
        if (!isset($result['erreur'])) {
            $response->getBody()->write(json_encode(['valid' => 'Vous avez ajouté une formation']));
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

