<?php



use Route\Route;

// Fonction d'autoloading des classes
spl_autoload_register(function ($className) {
    // Convertir l'espace de noms en chemin d'accès
    $classFile = __DIR__ . '/Controller/' . str_replace('\\', '/', $className) . '.php';

    // Vérifier si le fichier de classe existe
    if (file_exists($classFile)) {
        // Charger la classe
        require $classFile;
    }
});

// Exemple d'utilisation des classes
$myObject1 = new Router();


// ...
