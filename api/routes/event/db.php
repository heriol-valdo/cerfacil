<?php
// db.php - Connexion à la base de données

$host = 'localhost';
$dbname = 'u864174266_ecf';
$username = 'u864174266_ecf3'; // Modifier selon votre configuration
$password = '404d7hzkdI4FN!0';     // Modifier selon votre configuration

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
}
?>
