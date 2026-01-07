<?php







use Core\Router;

use Model\UserAdmin;



 $encodedData = isset($_GET['data']) ? $_GET['data'] : null;


 $decodedData = json_decode(base64_decode(urldecode($encodedData)), true);

 setcookie("info", $decodedData);

    

  

    







    

require_once 'config/DbAuth.php';

require 'autoload.php';

require_once "Core/Router.php";

require_once "Model/Form.php";





$routes = require 'routes.php';

$url = isset($_GET['url']) ? $_GET['url'] : null;



Router::handleRequest($url, $routes);



// N'oubli pas d'activiter la gestion du routing dans appache avec le fichier .htaccess 

// et aussi ajouter les droits ( ALL) dans  le fichier 000defaut de appache



?>



