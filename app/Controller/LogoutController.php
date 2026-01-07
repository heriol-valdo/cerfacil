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

            session_destroy();
            // Si l'utilisateur n'est pas connecté, message d'erreur
            setcookie("logout", "Veuillez vous connecter", time() + 5, "/");
        }

        // Redirection vers la page de connexion
        header('Location: /app/'); 
        exit;
    }


    public static function checkLogin($id) {
       if($id==1){
            // Supprime toutes les variables de session
            $_SESSION = [];

            // Détruit la session
            session_destroy();

            // Supprime le cookie de session si nécessaire
            if (ini_get("session.use_cookies")) {
                setcookie(session_name(), '', time() - 42000, '/');
            }

            // Ajout d'un message de déconnexion
            setcookie("logout", "La session a expiré, veuillez vous reconnecter", time() + 5, "/");
        // Redirection vers la page de connexion
        header('Location: /app/'); 
        exit;

       }else{
            // Vérifie si l'utilisateur est connecté (ex : via une variable de session)
            if (empty($_SESSION)) {
                // Supprime toutes les variables de session
                $_SESSION = [];

                // Détruit la session
                session_destroy();

                // Supprime le cookie de session si nécessaire
                if (ini_get("session.use_cookies")) {
                    setcookie(session_name(), '', time() - 42000, '/');
                }

                // Ajout d'un message de déconnexion
                setcookie("logout", "Veuillez vous connecter pour accéder à cette ressource", time() + 5, "/");
            } 

            // Redirection vers la page de connexion
            header('Location: /app/'); 
            exit;

       }
    }

    public static function getLogin() {
            // Supprime toutes les variables de session
            $_SESSION = [];

            // Détruit la session
            session_destroy();

            // Supprime le cookie de session si nécessaire
            if (ini_get("session.use_cookies")) {
                setcookie(session_name(), '', time() - 42000, '/');
            }

            // Ajout d'un message de déconnexion
            setcookie("logout", "Pour le moment vous ne pouvez pas avoir accès a cette ressource", time() + 5, "/");
        // Redirection vers la page de connexion
        header('Location: /app/'); 
        exit;
    }

    
}

