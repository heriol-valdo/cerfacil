<?php


require_once __DIR__ . '/../requestFile/authRequet.php';


class LogoutController{
    public function logout(){
        // Vérifie si l'utilisateur est connecté (ex : via une variable de session)
        if (!empty($_SESSION)) {
            // Supprime toutes les variables de session
            $_SESSION = [];

            // Détruit la session
            session_destroy();

            // Supprime le cookie de session si nécessaire
            if (ini_get("session.use_cookies")) {
                setcookie(session_name(), '', time() - 42000, '/');
            }

            // Ajout d'un message de déconnexion
            setcookie("logout", "Vous avez été déconnecté avec succès", time() + 5, "/");
        } else {
            // Si l'utilisateur n'est pas connecté, message d'erreur
            setcookie("logout", "Veuillez vous connecter", time() + 5, "/");
        }

        // Redirection vers la page de connexion
        header('Location: /app/'); 
        exit;
    }
}

