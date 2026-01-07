<?php

require_once __DIR__ . '/../requestFile/authRequet.php';
require_once __DIR__ . '/LogoutController.php';

class EtudiantController {
    public  function select_detail() {


        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];


        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Vérifier si c'est une requête AJAX
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    $postData = file_get_contents('php://input');
                    $data = json_decode($postData, true);
                    
                    // Validation
                    if (empty($data['id'])) {
                        header('Content-Type: application/json');
                        echo json_encode(['erreur' => "L'id du dossier est obligatoire"]);
                        exit;
                    }
                    
                    // Stockage en session
                    $_SESSION['idDossier'] = $data['id'];
                    
                    // Réponse JSON
                    header('Content-Type: application/json');
                    echo json_encode(['success' => $_SESSION['idDossier']]);
                    exit;
                } else {
                    // Si ce n'est pas AJAX, retourner une erreur
                    header('HTTP/1.1 400 Bad Request');
                    exit;
                }
            } else {
                 header('Location: /app/');
                 exit;
            }
            
        }
    }

    public static function detail() {

        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];
        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{
            if (isset($_SESSION['idDossier']) && !empty($_SESSION['idDossier'])) {
                 include("Views/etudiant/detail.php");
            } else {
                header('Location: /app/');
                exit;
            }
        }
    }

    public  function select_signature() {


        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];


        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Vérifier si c'est une requête AJAX
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    $postData = file_get_contents('php://input');
                    $data = json_decode($postData, true);
                    
                    // Validation
                    if (empty($data['id'])) {
                        header('Content-Type: application/json');
                        echo json_encode(['erreur' => "L'id du dossier est obligatoire"]);
                        exit;
                    }
                    
                    // Stockage en session
                    $_SESSION['idDossierSignature'] = $data['id'];

                    //include("Views/etudiant/signature.php");
                    
                    header('Location: /app/signature');
                    exit;
                } else {
                    // Si ce n'est pas AJAX, retourner une erreur
                    header('HTTP/1.1 400 Bad Request');
                    exit;
                }
            } else {
                 header('Location: /app/');
                 exit;
            }
            
        }
    }

    public static function signature() {

        $LogOutIfTokenExpired = self::validToken()['logOutIfTokenExpired'];
        $LogOutUserIfNotConnected = self::validToken()['logOutUserIfNotConnected'];
        if ($LogOutIfTokenExpired) {
            LogoutController::checkLogin(1);
           
        } else if ($LogOutUserIfNotConnected) {
            LogoutController::checkLogin(2);
        }else{
            if (isset($_SESSION['idDossierSignature']) && !empty($_SESSION['idDossierSignature'])) {
                 include("Views/etudiant/signature.php");
            } else {
                header('Location: /app/');
                exit;
            }
        }
    }

public function formSignatureManuelleApprenti() {
    // Validation de la signature
    if (!isset($_POST['signature']) || empty($_POST['signature'])) {
        echo json_encode([
            'error' => true,
            'message' => 'Signature non fournie.'
        ]);
        return;
    }

    try {
        // Traitement de l'image base64
        $data = $_POST['signature'];
        
        if (strpos($data, 'data:image/') !== 0) {
            throw new Exception('Format de signature invalide.');
        }
        
        list(, $data) = explode(',', $data); 
        $decodedData = base64_decode($data);
        
        if ($decodedData === false) {
            throw new Exception('Erreur lors du décodage de la signature.');
        }
        
        // AMÉLIORATION: Génération du chemin plus robuste
        $unique_id = uniqid(rand(), true);
        $relativePath = 'assets/signatureApprenti/' . str_replace(['\\', '/'], '_', $unique_id) . '.png';
        
        // Tester plusieurs options de chemin
        $pathOptions = [
            'option1' => __DIR__ . '/../public/' . $relativePath,
            'option2' => $_SERVER['DOCUMENT_ROOT'] . '/cerfa/public/' . $relativePath,
            'option3' => dirname(__FILE__) . '/../public/' . $relativePath,
            'option4' => realpath('.') . '/public/' . $relativePath,
            'option5' => '/home/bhaf2949/cerfa.heriolvaldo.com/cerfa/public/' . $relativePath
        ];
        
        // DEBUG: Afficher tous les chemins possibles
        error_log("=== DEBUG CHEMINS ===");
        error_log("Chemin relatif: " . $relativePath);
        error_log("Document root: " . $_SERVER['DOCUMENT_ROOT']);
        error_log("__DIR__: " . __DIR__);
        error_log("dirname(__FILE__): " . dirname(__FILE__));
        error_log("realpath('.'): " . realpath('.'));
        
        foreach ($pathOptions as $name => $path) {
            error_log($name . ": " . $path);
            error_log($name . " - parent dir exists: " . (is_dir(dirname($path)) ? 'OUI' : 'NON'));
            error_log($name . " - parent writable: " . (is_writable(dirname($path)) ? 'OUI' : 'NON'));
        }
        
        // Choisir le chemin le plus approprié
        $fullPath = null;
        $selectedOption = null;
        
        foreach ($pathOptions as $name => $path) {
            $directory = dirname($path);
            if (is_dir($directory) && is_writable($directory)) {
                $fullPath = $path;
                $selectedOption = $name;
                error_log("Chemin sélectionné: " . $name . " -> " . $fullPath);
                break;
            }
        }
        
        if ($fullPath === null) {
            // Forcer la création avec le chemin le plus probable
            $fullPath = $pathOptions['option5']; // Basé sur votre log précédent
            $selectedOption = 'option5 (forcé)';
            error_log("Aucun chemin valide trouvé, utilisation forcée: " . $fullPath);
        }
        
        // Créer le répertoire s'il n'existe pas
        $directory = dirname($fullPath);
        error_log("Répertoire final de destination: " . $directory);
        error_log("Répertoire existe: " . (is_dir($directory) ? 'OUI' : 'NON'));
        
        if (!is_dir($directory)) {
            error_log("Tentative de création du répertoire...");
            if (!mkdir($directory, 0755, true)) {
                $error = error_get_last();
                error_log("Erreur mkdir: " . print_r($error, true));
                throw new Exception('Impossible de créer le répertoire: ' . $directory . ' - ' . ($error['message'] ?? 'Erreur inconnue'));
            }
            error_log("Répertoire créé avec succès: " . $directory);
        }
        
        // Vérifier les permissions avant sauvegarde
        if (!is_writable($directory)) {
            error_log("Permissions du répertoire: " . substr(sprintf('%o', fileperms($directory)), -4));
            throw new Exception('Répertoire non accessible en écriture: ' . $directory);
        }
        
        // DEBUG: Taille des données à sauvegarder
        error_log("Taille des données décodées: " . strlen($decodedData) . " bytes");
        
        // Sauvegarde du fichier avec gestion d'erreur détaillée
        error_log("Tentative de sauvegarde vers: " . $fullPath);
        $bytesWritten = file_put_contents($fullPath, $decodedData);
        
        if ($bytesWritten === false) {
            $error = error_get_last();
            error_log("Erreur file_put_contents: " . print_r($error, true));
            throw new Exception('Erreur lors de la sauvegarde: ' . ($error['message'] ?? 'Erreur inconnue'));
        }
        
        error_log("Fichier sauvegardé avec succès: " . $fullPath . " (" . $bytesWritten . " bytes)");
        
        // Vérifier immédiatement après la sauvegarde
        if (!file_exists($fullPath)) {
            error_log("ERREUR: Le fichier n'existe pas après file_put_contents !");
            throw new Exception('Le fichier n\'a pas été créé: ' . $fullPath);
        }
        
        // Vérifier la taille du fichier
        $fileSize = filesize($fullPath);
        error_log("Taille du fichier créé: " . $fileSize . " bytes");
        
        if ($fileSize !== $bytesWritten) {
            error_log("ATTENTION: Différence entre bytes écrits et taille fichier !");
        }
        
        // Construction de l'URL publique
        $urlPath = 'https://cerfa.heriolvaldo.com/cerfa/public/' . $relativePath;
        
        // Test d'accessibilité de l'URL
        error_log("Test d'accessibilité de l'URL: " . $urlPath);
        $urlTest = $this->testUrlAccessibility($urlPath);
        
        if (!$urlTest['accessible']) {
            error_log("URL non accessible: " . $urlTest['error']);
            // Essayer avec une URL alternative
            $alternativeUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/cerfa/public/' . $relativePath;
            error_log("Test URL alternative: " . $alternativeUrl);
            
            $urlTestAlt = $this->testUrlAccessibility($alternativeUrl);
            if ($urlTestAlt['accessible']) {
                $urlPath = $alternativeUrl;
                error_log("URL alternative accessible");
            } else {
                error_log("URL alternative non accessible: " . $urlTestAlt['error']);
                // Continuer quand même avec l'URL originale
            }
        }
        
        // Appel de la fonction de signature
        $result = self::formSignature($urlPath);
        
        error_log("Résultat formSignature: " . json_encode($result));
        
        if ($result['valid']) {
            echo json_encode([
                'error' => false,
                'message' => 'Le formulaire a été signé avec succès.',
                'debug' => [
                    'file_path' => $fullPath,
                    'url_path' => $urlPath,
                    'file_exists' => file_exists($fullPath),
                    'file_size' => filesize($fullPath)
                ]
            ]);
        } else {
            // ATTENTION: Ne pas supprimer le fichier pour debug
            error_log("Échec de la signature, fichier conservé pour debug: " . $fullPath);
            
            // Supprimer le fichier en cas d'échec (décommentez après debug)
            // if (file_exists($fullPath)) {
            //     unlink($fullPath);
            // }
            
            echo json_encode([
                'error' => true,
                'message' => $result['error'] ?? 'Une erreur s\'est produite lors de la signature.',
                'debug' => [
                    'file_path' => $fullPath,
                    'url_path' => $urlPath,
                    'file_exists' => file_exists($fullPath),
                    'result' => $result
                ]
            ]);
        }
        
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage());
        
        // Nettoyage en cas d'erreur (décommentez après debug)
        // if (isset($fullPath) && file_exists($fullPath)) {
        //     unlink($fullPath);
        // }
        
        echo json_encode([
            'error' => true,
            'message' => $e->getMessage(),
            'debug' => [
                'file_path' => isset($fullPath) ? $fullPath : 'Non défini',
                'file_exists' => isset($fullPath) ? file_exists($fullPath) : false
            ]
        ]);
    }
}

public function formSignaturefileApprenti() {
    // Validation des données reçues
    if (!isset($_POST['fileContent']) || !isset($_POST['fileName']) || !isset($_POST['fileType'])) {
        echo json_encode([
            'error' => true,
            'message' => 'Données de fichier manquantes.'
        ]);
        return;
    }

    try {
        $fileContent = $_POST['fileContent'];
        $fileName = $_POST['fileName'];
        $fileType = $_POST['fileType'];
        
        // Validation de l'extension
        $allowed = ['png', 'jpg', 'jpeg'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowed)) {
            echo json_encode([
                'error' => true,
                'message' => "Type de fichier non autorisé. Extensions acceptées: " . implode(', ', $allowed)
            ]);
            return;
        }

        // DEBUG: Informations sur le fichier reçu
        error_log("=== DEBUG FICHIER ===");
        error_log("Nom du fichier: " . $fileName);
        error_log("Type de fichier: " . $fileType);
        error_log("Extension: " . $fileExt);
        error_log("Taille du contenu reçu: " . strlen($fileContent) . " caractères");
        
        // Décodage du contenu base64
        $decodedData = null;
        
        // Cas 1: Le contenu a un header data:image/
        if (strpos($fileContent, 'data:image/') === 0) {
            error_log("Format détecté: base64 avec header");
            list(, $base64Data) = explode(',', $fileContent, 2);
            $decodedData = base64_decode($base64Data);
        }
        // Cas 2: Le contenu est déjà en base64 pur
        else {
            error_log("Format détecté: base64 pur");
            $decodedData = base64_decode($fileContent);
        }
        
        if ($decodedData === false || empty($decodedData)) {
            throw new Exception('Erreur lors du décodage du fichier.');
        }
        
        error_log("Taille des données décodées: " . strlen($decodedData) . " bytes");
        
        // Génération du chemin de fichier
        $unique_id = uniqid(rand(), true);
        $relativePath = 'assets/signatureApprenti/' . str_replace(['\\', '/'], '_', $unique_id) . '.' . $fileExt;
        
        // Options de chemin (basé sur vos logs précédents)
        $pathOptions = [
            'option1' => __DIR__ . '/../public/' . $relativePath,
            'option2' => $_SERVER['DOCUMENT_ROOT'] . '/cerfa/public/' . $relativePath,
            'option3' => dirname(__FILE__) . '/../public/' . $relativePath,
            'option4' => realpath('.') . '/public/' . $relativePath,
            'option5' => '/home/bhaf2949/cerfa.heriolvaldo.com/cerfa/public/' . $relativePath
        ];
        
        // DEBUG: Test des chemins
        error_log("=== DEBUG CHEMINS ===");
        foreach ($pathOptions as $name => $path) {
            $directory = dirname($path);
            error_log($name . ": " . $path);
            error_log($name . " - dir exists: " . (is_dir($directory) ? 'OUI' : 'NON'));
            error_log($name . " - writable: " . (is_writable($directory) ? 'OUI' : 'NON'));
        }
        
        // Sélection du chemin valide
        $fullPath = null;
        foreach ($pathOptions as $name => $path) {
            $directory = dirname($path);
            if (is_dir($directory) && is_writable($directory)) {
                $fullPath = $path;
                error_log("Chemin sélectionné: " . $name . " -> " . $fullPath);
                break;
            }
        }
        
        if ($fullPath === null) {
            $fullPath = $pathOptions['option5']; // Chemin de fallback
            error_log("Utilisation du chemin de fallback: " . $fullPath);
        }
        
        // Création du répertoire si nécessaire
        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true)) {
                throw new Exception('Impossible de créer le répertoire: ' . $directory);
            }
            error_log("Répertoire créé: " . $directory);
        }
        
        // Vérification des permissions
        if (!is_writable($directory)) {
            throw new Exception('Répertoire non accessible en écriture: ' . $directory);
        }
        
        // Sauvegarde du fichier
        error_log("Sauvegarde vers: " . $fullPath);
        $bytesWritten = file_put_contents($fullPath, $decodedData);
        
        if ($bytesWritten === false) {
            $error = error_get_last();
            throw new Exception('Erreur lors de la sauvegarde: ' . ($error['message'] ?? 'Erreur inconnue'));
        }
        
        error_log("Fichier sauvegardé: " . $bytesWritten . " bytes");
        
        // Vérification de l'existence du fichier
        if (!file_exists($fullPath)) {
            throw new Exception('Le fichier n\'a pas été créé: ' . $fullPath);
        }
        
        $actualFileSize = filesize($fullPath);
        error_log("Taille réelle du fichier: " . $actualFileSize . " bytes");
        
        // Construction de l'URL publique
        $urlPath = 'https://cerfa.heriolvaldo.com/cerfa/public/' . $relativePath;
        
        // Test d'accessibilité
        error_log("Test d'accessibilité: " . $urlPath);
        $urlTest = $this->testUrlAccessibility($urlPath);
        
        if (!$urlTest['accessible']) {
            error_log("URL non accessible: " . $urlTest['error']);
            // Essayer une URL alternative
            $alternativeUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/cerfa/public/' . $relativePath;
            $urlTestAlt = $this->testUrlAccessibility($alternativeUrl);
            if ($urlTestAlt['accessible']) {
                $urlPath = $alternativeUrl;
                error_log("URL alternative utilisée: " . $urlPath);
            }
        }
        
        // Appel de l'API de signature
        $result = self::formSignature($urlPath);
        error_log("Résultat API: " . json_encode($result));
        
        if ($result['valid']) {
            echo json_encode([
                'error' => false,
                'message' => 'Le fichier a été signé avec succès.',
                'debug' => [
                    'file_path' => $fullPath,
                    'url_path' => $urlPath,
                    'file_exists' => file_exists($fullPath),
                    'file_size' => $actualFileSize,
                    'original_name' => $fileName
                ]
            ]);
        } else {
            error_log("Échec de la signature: " . ($result['error'] ?? 'Erreur inconnue'));
            
            echo json_encode([
                'error' => true,
                'message' => $result['error'] ?? 'Une erreur s\'est produite lors de la signature.',
                'debug' => [
                    'file_path' => $fullPath,
                    'url_path' => $urlPath,
                    'file_exists' => file_exists($fullPath),
                    'file_size' => $actualFileSize,
                    'api_result' => $result
                ]
            ]);
        }
        
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage());
        
        echo json_encode([
            'error' => true,
            'message' => $e->getMessage(),
            'debug' => [
                'file_path' => isset($fullPath) ? $fullPath : 'Non défini',
                'exception' => $e->getMessage()
            ]
        ]);
    }
}
public static function formSignature($path) {
    // Validation de la session
    if (!isset($_SESSION['idDossierSignature']) || empty($_SESSION['idDossierSignature'])) {
        return [
            'valid' => false,
            'error' => 'ID de dossier manquant dans la session.',
            'data' => null
        ];
    }
    
    if (!isset($_SESSION['userToken']) || empty($_SESSION['userToken'])) {
        return [
            'valid' => false,
            'error' => 'Token utilisateur manquant.',
            'data' => null
        ];
    }

    $id = $_SESSION['idDossierSignature'];
    $userToken = $_SESSION['userToken'];

    try {
        // Préparation des données
        $data = [
            'id' => $id,
            'path' => $path,
            'prov' => 1
        ];
        
        // Appel de l'API
        $result = sendRequest($data, $userToken, 'setPathSignature', 'post');
        
        if ($result === false || $result === null) {
            return [
                'valid' => false,
                'error' => 'Erreur de communication avec l\'API.',
                'data' => null
            ];
        }
        
        $decodedResult = json_decode($result);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'valid' => false,
                'error' => 'Réponse de l\'API invalide.',
                'data' => null
            ];
        }

        // Traitement de la réponse
        $response = [
            'valid' => false,
            'data' => null,
            'error' => null
        ];

        if (property_exists($decodedResult, 'erreur')) {
            $response['error'] = $decodedResult->erreur;
        }
        
        if (property_exists($decodedResult, 'valid')) {
            $response['valid'] = true;
            $response['data'] = $decodedResult->valid;
        }

        return $response;
        
    } catch (PDOException $e) {
        return [
            'valid' => false,
            'error' => 'Erreur de base de données: ' . $e->getMessage(),
            'data' => null
        ];
    } catch (Exception $e) {
        return [
            'valid' => false,
            'error' => 'Erreur système: ' . $e->getMessage(),
            'data' => null
        ];
    }
}



  private function testUrlAccessibility($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($result === false || !empty($error)) {
        return [
            'accessible' => false,
            'error' => $error,
            'http_code' => $httpCode
        ];
    }
    
    if ($httpCode >= 200 && $httpCode < 300) {
        return [
            'accessible' => true,
            'http_code' => $httpCode
        ];
    }
    
    return [
        'accessible' => false,
        'error' => 'HTTP Code: ' . $httpCode,
        'http_code' => $httpCode
    ];
}

    public static function validToken() {

        $logOutIfTokenExpired = false;
        $logOutUserIfNotConnected = false ;
        // Vérifier l'expiration du token
        if (isset($_SESSION['user']['exp'])) {
            $expirationTime = $_SESSION['user']['exp'];
            $currentTime = time();
            
            if ($currentTime > $expirationTime || !isset($_SESSION) || empty($_SESSION)) {
                // Token expiré ou session invalide
                $_SESSION = array();
                $logOutIfTokenExpired = true;
               
            }
        }
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            $_SESSION = [];
            $logOutUserIfNotConnected = true;
        }
        
        // Si on arrive ici, le token est valide
        return [
            'logOutIfTokenExpired' => $logOutIfTokenExpired,
            'logOutUserIfNotConnected' => $logOutUserIfNotConnected
        ];
    }

}
?>