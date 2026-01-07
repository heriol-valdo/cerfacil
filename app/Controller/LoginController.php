<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


class LoginController
{
    public function index()
    {
        include 'Views/user/login.php';
    }

    public function login()
    {
        require_once __DIR__ . '/../requestFile/authRequet.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = file_get_contents('php://input');
            $data = json_decode($postData, true);

            if (!isset($data['email']) || !isset($data['password'])) {
                echo json_encode([
                    'erreur' => 'Email et mot de passe sont obligatoires'
                ]);
                exit;
            }

            $result = sendRequest($data, '', 'login', 'post');
            $result = json_decode($result);

            if (property_exists($result, 'erreur')) {
                echo json_encode([
                    'erreur' => $result->erreur
                ]);
                exit;
            }

            if (property_exists($result, 'valid')) {
                $_SESSION['userToken'] = $result->data;

                $getUserInfo = sendRequest([], $_SESSION['userToken'], 'user/decode', 'get');
                $userInfo = json_decode($getUserInfo);


                // Détermine le type de réponse selon le rôle
                if($userInfo->data->userRole == 5 || $userInfo->data->userRole == 6) {
                    $_SESSION['user'] = [
                        'role' => $userInfo->data->userRole,
                        'id' => $userInfo->data->userId,
                        'idByRole' => $userInfo->data->userIdByRole,
                        'centre' => $userInfo->data->idCentre,
                        'exp' => $userInfo->data->exp
                    ];
                    // Si c'est un admin, on renvoie juste les données JSON
                    echo json_encode([
                        'valid' => "Connexion réussie",
                        'data' => [
                            'role' => $userInfo->data->userRole,
                            'id' => $userInfo->data->userId,
                            'idByRole' => $userInfo->data->userIdByRole,
                            'centre' => $userInfo->data->idCentre,
                            'exp' => $userInfo->data->exp
                        ]
                    ]);
                    exit;
                } else {
                     echo json_encode([
                        'valid' => "Connexion réussie",
                        'redirect' => 'https://cerfa.heriolvaldo.com/cerfa/',
                        'data' => [
                            'role' => $userInfo->data->userRole,
                            'id' => $userInfo->data->userId,
                            'idByRole' => $userInfo->data->userIdByRole,
                            'centre' => $userInfo->data->idCentre,
                            'exp' => $userInfo->data->exp
                        ]
                    ]);
                    exit;

                   
                }
            }
        }

        echo json_encode([
            'erreur' => 'Méthode non autorisée'
        ]);
        exit;
    }

}