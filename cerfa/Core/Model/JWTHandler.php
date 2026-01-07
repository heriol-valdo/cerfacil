<?php
namespace Projet\Model;

use Firebase\JWT\JWT;

require_once __DIR__ . '/../../vendor/firebase/php-jwt/src/JWT.php';

class JWTHandler {

    /**
     * Crée un token JWT avec les données et la clé privée fournies.
     * 
     * @param array $payload Les données à inclure dans le token.
     * @param string $privateKey La clé privée pour signer le token.
     * @param array $header L'en-tête JWT (par exemple typ et alg).
     * @return string Le token JWT généré.
     */
    public static function createToken(array $payload, string $privateKey, array $header): string {
        try {
            // Encodage du JWT avec l'en-tête, la charge utile et la clé privée.
            return JWT::encode($payload, $privateKey, 'RS256', null, $header);
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de la création du token : ' . $e->getMessage());
        }
    }

    /**
     * Décode un token JWT avec une clé publique donnée.
     * 
     * @param string $token Le token JWT à décoder.
     * @param string $publicKey La clé publique pour vérifier le token.
     * @return object Les données du token décodé.
     */
    public static function decodeToken(string $token, string $publicKey): object {
        try {
            return JWT::decode($token, $publicKey, ['RS256']);
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors du décodage du token : ' . $e->getMessage());
        }
    }
}
