<?php
use Model\Form;

class FormsController {
    public function index() {
        // Logique du contrôleur pour la page de connexion
        include 'Views/form.php';
    }

    public function formSignature() {
        if (isset($_POST['fileContent']) && isset($_POST['fileName']) && isset($_POST['fileType'])) {
            $allowed = [ 'png','jpg','jpeg'];
            $fileContent = base64_decode($_POST['fileContent']);
            $fileName = $_POST['fileName'];
            $fileType = $_POST['fileType'];
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    
            if (!in_array($fileExt, $allowed)) {
                echo json_encode([
                    'error' => true,
                    'message' => "Type de fichier non autorisé."
                ]);
                return;
            }
    
            $fileName = preg_replace('/[^a-zA-Z0-9.]/', '', $fileName);
            $fileName = str_replace(' ', '', $fileName);
            $unique_id = uniqid(rand(), true);
            $path = 'assets/signatureEcole/' . str_replace('\\', '/', $unique_id.$fileName);
            $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/cerfa/public/' . $path;
            
            if (file_put_contents($fullPath, $fileContent) === false) {
                echo json_encode([
                    'error' => true,
                    'message' => 'Erreur lors de la signature du Fichier.'
                ]);
            } else {
                $urlPath = 'https://cerfa.heriolvaldo.com/cerfa/public/' . $path;
                $result = Form::formSignature( $urlPath);
                if ($result['valid']) {
                    echo json_encode([
                        'error' => false,
                        'message' => "Le formulaire a été signer avec succès."
                    ]);
                } else {
                    echo json_encode([
                        'error' => true,
                        'message' => "Une erreur s'est produite lors de la signature."
                    ]);
                }
            }
        } else {
            echo json_encode([
                'error' => true,
                'message' => "Fichier non valide ou manquant."
            ]);
          
        }
    }

    public function formSignatureManuelle() {
        if (isset($_POST['signature']) && !empty($_POST['signature'])) {
            $data = $_POST['signature'];
            list(, $data) = explode(',', $data); 
            $data = base64_decode($data);
            
    
            $unique_id = uniqid(rand(), true);
            $path = 'assets/signatureEcole/' . str_replace('\\', '/', $unique_id);
            $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/cerfa/public/' . $path.'.png';
            
            if (file_put_contents($fullPath, $data) === false) {
                echo json_encode([
                    'error' => true,
                    'message' => 'Erreur lors de la signature du Fichier.'
                ]);
            } else {
                $urlPath = 'https://cerfa.heriolvaldo.com/cerfa/public/' . $path.'.png';
                $result = Form::formSignature( $urlPath);
                if ($result) {
                    echo json_encode([
                        'error' => false,
                        'message' => "Le formulaire a été signer avec succès."
                    ]);
                } else {
                    echo json_encode([
                        'error' => true,
                        'message' => "Une erreur s'est produite lors de la signature."
                    ]);
                }
            }
        } else {
            echo json_encode([
                'error' => true,
                'message' => "Signature non fournie.."
            ]);
          
        }
    }
    

}
?>