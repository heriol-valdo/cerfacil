<?php
use Slim\Factory\AppFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = 'ïOÖbÈ3~_Äijb¥d-ýÇ£Hf¿@xyLcP÷@';

$auth = function ($request, $handler) use ($key) {

    $token = $request->getHeader('token');
    
    try{
        $decoded = JWT::decode($token[0], new Key($key, 'HS256'));

        $userDatas = [
            'id' => $decoded->id,
            'role' => $decoded->role,
            'idByRole'=>$decoded->idByRole,
            'centre' => $decoded->role == 3 || $decoded->role == 4 ? $decoded->centre : null,
            'exp'=>$decoded->exp
        ];


        if (empty($userDatas['idByRole'])) {
            throw new Exception("Erreur dans la base de données. Profil de l'utilisateur incomplet.");
        }
    

        $request = $request->withAttribute('user', $userDatas);

        return $handler->handle($request);

    } catch (Exception $e){
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['erreur' => 'Token invalide']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
};