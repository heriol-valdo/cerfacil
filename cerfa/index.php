<?php
// Configuration PHP
ini_set('display_errors', 1);
ini_set('memory_limit', '-1');
date_default_timezone_set("Europe/Paris");

// Définition de constantes
define('ROOT', __DIR__);
define('LANG', __DIR__.'/lang');
define('DEBUG_MODE', 1);
define('ROOT_SITE', "https://cerfa.heriolvaldo.com/cerfa/public/");
define('ROOT_URL', "https://cerfa.heriolvaldo.com/cerfa/");
define('PATH_FILE', realpath(dirname(__FILE__)));
define('MYSQL_DATETIME_FORMAT', 'd-m-Y H:i:s');
define('MYSQL_DATE_FORMAT', 'd-m-Y');
define('DATE_FORMAT', 'd-m-Y');
define('VALUE_OF_POINT', 100);
define('DATE_COURANTE', date(MYSQL_DATETIME_FORMAT));

// Fonctions utilitaires
function var_die($expression){
    echo '<pre>';
    var_dump($expression);
    echo '</pre>';
    die();
}

function thousand($value){
    return number_format($value, 0, '.', ',');
}

function thousands($value){
    return number_format($value, 0, ',', ' ');
}

function float_value($value){
    return strpos($value,'.') !== false ? number_format($value, 2, ',', ' ') : thousand($value);
}

function is_ajax(){
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}






require_once 'Core/Autoloader.php';
require_once 'vendor/autoload.php';


$routes = require 'routes.php';
$url = isset($_GET['url']) ? $_GET['url'] : null;


\Projet\Autoloader::register();

\Projet\Model\Router::handleRequest($url,$routes);


