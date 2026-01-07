<?php // /middlewares/checkRole.php
use Slim\Factory\AppFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Psr7\Response;

$check_required = function (array $requiredFields) {
    return function ($request, $handler) use ($requiredFields) {
        $response = new Response();
        $data = $request->getParsedBody();

        try {
            // Check if $requiredFields is an array and if all required fields are present
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Tous les champs obligatoires ne sont pas remplis: $field manquant.");
                }
            }

            // If all required fields are present, proceed with the next middleware or route handler
            return $handler->handle($request);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    };
};