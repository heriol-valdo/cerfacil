<?php

class Database {
    protected $db;

    public function __construct() {
        $host = 'localhost'; // Remplacez par votre hôte
        $dbname = 'bhaf2949_api_erp_cerfa'; // Remplacez par votre base de données
        $username = 'bhaf2949_zeufackvaldo'; // Remplacez par votre nom d'utilisateur
        $password = 'Demanou2@'; // Remplacez par votre mot de passe

        try {
            $this->db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
}
?>
