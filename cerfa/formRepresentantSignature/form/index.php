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




?>



