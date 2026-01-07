<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../../models/FormateursParticipantSessionModel.php';
require_once __DIR__.'/../../models/FormateurModel.php';
require_once __DIR__.'/../../models/SessionModel.php';

$app->post('/api/session/{idSession}/addFormateur', function (Request $request, Response $response, $param) use ($key) {

    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];

    // Check => Format param
    if(empty($param['idSession'])) {
        $response->getBody()->write(json_encode(['error' => "Le champ idSession est vide"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    if(!preg_match('/^\d+$/', $param['idSession'])) {
        $response->getBody()->write(json_encode(['error' => "Mauvais format"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }


    $datas = $request->getParsedBody();
    $idSession=$param['idSession'];

    $session = new Session();
    $session->id= $idSession;
    $checkSessionExist = $session->boolId();

    if (!$checkSessionExist) {
        $response->getBody()->write(json_encode(['error' => "La session n'existe pas"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }


    if($role !== 1 && $role !== 3) {
        $response->getBody()->write(json_encode(['error' => "Vous n'avez pas les droits nécéssaires"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }

    $formateurParticipantSession = new FormateursParticipantSession();
    $formateurParticipantSession->id = $idSession;

    foreach ($datas as $key => $value) {
        $formateur=new Formateur();
        $formateur->id=$value;
        $checkFormateurExist = $formateur->checkFormateurExist();

        if(!$checkFormateurExist) {
            $response->getBody()->write(json_encode(['error' => "Ce formateur n'existe pas : id = ".$value]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $formateurParticipantSession->id_formateurs = $value;
        $check = $formateurParticipantSession->checkIfFormateurParticipantSessionExist();
        echo $check;
        if(!$check) {
            $formateurParticipantSession->postFormateurParticipantSession();
        } else {
            $response->getBody()->write(json_encode(['error' => "ce formateur est déjà associée à cette session"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(409);
        }
    }

    $response->getBody()->write(json_encode(['success' => "Le(s) formateur(s) a(ont) été ajouté(s) avec succès"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add($auth); 

$app->post('/api/addFormateurSession', function (Request $request, Response $response, $param) use ($key) {

    $token = $request->getAttribute('user');
    $role = intval($token['role']);
    $userConnected = $token['id'];

        if($role === 1 || $role === 3 ){
          
        
            $datas = $request->getParsedBody();

            $formateurParticipantSession = new FormateursParticipantSession();
            $formateurParticipantSession->id = $datas['id'];
            $formateurParticipantSession->id_formateurs = $datas['id_formateurs'];
            $resultInser = $formateurParticipantSession->postFormateurParticipantSession();


            if ($resultInser) {
                $response->getBody()->write(json_encode(['valid' => 'Vous avez ajouté un formateur a une session de formation']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
            } else {
                $response->getBody()->write(json_encode(['error' => "Échec, il y a eu un problème lors de l'ajout du formateur a une session de formation"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }


        } else {
            $response->getBody()->write(json_encode(['error' => 'Vous n\'avez pas le droit d\'effectuer cette action']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
})->add($auth);

?>