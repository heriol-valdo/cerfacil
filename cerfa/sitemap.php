<?php
header("Content-Type: application/xml; charset=utf-8");

// Définir l'URL de base
$base_url = "https://cerfa.heriolvaldo.com/cerfa/";

// Tableau associatif des routes réécrites et des pages PHP correspondantes
// Exemple : chaque clé est l'URL réécrite, et la valeur est le fichier PHP correspondant dans Views
$routes = [
    "" => "Page/Home/login.php", 
    "resetPassword" => "Page/Home/resetPassword.php", 
    "resetPasswordSend" => "Page/Home/resetPasswordSend.php", 

    "employeurs" => "Admin/Admin/entreprise.php",     
    "formations" => "Admin/Admin/formation.php", 
    "admins" => "Admin/Admin/index.php",  
    "opco" => "Admin/Admin/opco.php",  

    "home" => "Admin/User/index.php", 
    "profil" => "Admin/User/profil.php", 
    "password" => "Admin/User/password.php",

    "cerfas" => "Admin/Scolorite/cerfa.php",
    "produits" => "Admin/Scolorite/produit.php",
    "cerfasdetails" => "Admin/Scolorite/cerfa_detail.php",
];

// Générer le sitemap XML
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

// Générer une entrée pour chaque route
foreach ($routes as $route => $php_file) {
    // Construire l'URL réécrite pour la route
    $url = $base_url . $route;

    // Vérifier si le fichier PHP existe dans le dossier Views
    // Remarquez que nous utilisons $_SERVER['DOCUMENT_ROOT'] pour générer le chemin complet vers le fichier PHP
    $file_path = $_SERVER['DOCUMENT_ROOT'] . "/cerfa/Views/" . $php_file;

    // Vérifier si le fichier existe réellement dans le dossier Views
    if (file_exists($file_path)) {
        echo "  <url>\n";
        echo "    <loc>" . htmlspecialchars($url) . "</loc>\n";
        echo "    <changefreq>weekly</changefreq>\n";
        echo "    <priority>0.8</priority>\n";
        echo "  </url>\n";
    }
}

echo "</urlset>";
?>
