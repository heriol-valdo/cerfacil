<?php

namespace Core;



use Model\UserAdmin;



class Router {
    public  static function  handleRequest($url, $routes) {
        // Recherche de la correspondance de l'URL dans les routes
        if (array_key_exists($url, $routes)) {

            // if(!UserAdmin::isLogin()) {
            //     include 'Views/login.php';
            //     exit();
            // } 

            // Si une correspondance est trouvée, obtenez le contrôleur et l'action associés
            $controllerAction = $routes[$url];

            // Divisez le contrôleur et l'action en utilisant le caractère '#' comme séparateur
            list($controllerName, $actionName) = explode('#', $controllerAction);

            // Incluez le fichier du contrôleur
            include __DIR__ . '/../Controller/' . $controllerName . '.php';

            // Créez une instance du contrôleur
            $controller = new $controllerName();

            // Appelez la méthode/action appropriée du contrôleur
            $controller->$actionName();
        } else {
            // Si aucune correspondance n'est trouvée, affichez une page d'erreur 404
            include 'Views/404.php';
        }
    }
}
?>
