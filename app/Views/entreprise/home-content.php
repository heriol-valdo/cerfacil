<?php 

include __DIR__.'../../elements/header.php';
require_once __DIR__ . '/../../Controller/HomeController.php';
require_once __DIR__ . '/../../Controller/CerfaController.php';
require_once __DIR__ . '/../../Controller/FormationController.php';
require_once __DIR__ . '/../../Controller/EntrepriseController.php';
require_once __DIR__ . '/../../Controller/LogoutController.php';

$tableaux = CerfaController::getCerfasEntreprise();

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Acceuil | CerFacil</title>
    <style>
         .status-container {
            max-width: 600px;
            margin: 20px auto;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .completion-status {
            display: flex;
            align-items: center;
            padding: 20px 25px;
            border-radius: 15px;
            margin: 15px 0;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-left: 5px solid;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .completion-status::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }

        .completion-status:hover::before {
            transform: translateX(100%);
        }

        .completion-status:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
        }

        /* Style pour "Remplir le contrat" */
        .status-fill-contract {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            border-left-color: #ff6b9d;
            color: #8b1538;
        }

        /* Style pour "Contrat rempli - attente signature" */
        .status-waiting-signature {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            border-left-color: #17a2b8;
            color: #0c5460;
        }

        /* Style pour "Signer le contrat" */
        .status-sign-contract {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            border-left-color: #fd7e14;
            color: #7a3e0c;
        }

        /* Style pour "Dossier complet" */
        .status-complete {
            background: linear-gradient(135deg, #a8e6cf 0%, #dcedc1 100%);
            border-left-color: #28a745;
            color: #155724;
        }

        /* Style pour "Aucune action" */
        .status-no-action {
            background: linear-gradient(135deg, #e2e8f0 0%, #f8fafc 100%);
            border-left-color: #6c757d;
            color: #495057;
        }

        .status-icon {
            font-size: 2.5rem;
            margin-right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        .completion-text {
            flex: 1;
            font-size: 1.1rem;
            font-weight: 600;
            line-height: 1.4;
        }

        .mascot-character {
            font-size: 2.5rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        /* Animation d'apparition */
        .completion-status {
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
            body {
                font-family: 'Poppins', sans-serif;
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                min-height: 100vh;
                color: #1e293b;
            }
        
        footer {
             margin-bottom: 0;
            margin-top: 180px;
            padding: 0;
            text-align: center;
            color: var(--3main-color);
            width: 100%;
             background-color:rgb(228, 234, 245);
        }

        .footer-bottom p {
            font-size: 15px;
            margin-bottom: 0;
        }
        
        .container {
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #e91e63, #9c27b0, #3f51b5);
            border-radius: 8px;
            margin-right: 15px;
        }

        .welcome-section h1 {
            font-size: 24px;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .welcome-section p {
            color: #666;
            font-size: 14px;
        }

        .language-selector {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 14px;
            color: #007bff;
            cursor: pointer;
        }

        .language-selector .flag {
            margin-right: 5px;
        }

       

        .main-content {
            margin-left: 80px;
        }

        /* Views */
        .view {
            display: none;
        }

        .view.active {
            display: block;
        }

        /* Folder Selection */
      .folder-selection {
        margin-bottom: 40px;
        /* Container pour le scroll horizontal */
        overflow-x: auto;
        overflow-y: hidden;
        /* Styling de la scrollbar (optionnel) */
        scrollbar-width: thin;
        scrollbar-color: #bdc3c7 #ecf0f1;
}

.folder-selection h2 {
    font-size: 20px;
    margin-bottom: 20px;
    color: #2c3e50;
}

/* Container flex pour les cards */
.folder-selection .folder-container {
    display: flex;
    gap: 20px;
    padding-bottom: 10px; /* Espace pour la scrollbar */
    min-width: fit-content;
}

.folder-card {
    background: white;
    border: 2px solid #e3f2fd;
    border-radius: 12px;
    padding: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    
    /* Dimensions fixes pour le scroll horizontal */
    min-width: 300px;
    max-width: 400px;
    width: 350px; /* Largeur fixe recommandée */
    
    /* Empêche la carte de rétrécir */
    flex-shrink: 0;
    
    /* Supprime margin-bottom car on utilise gap */
    margin-bottom: 0;
}

/* Hover effect */
.folder-card:hover {
    border-color: #2196f3;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.15);
}

/* Styling personnalisé de la scrollbar pour Webkit browsers */
.folder-selection::-webkit-scrollbar {
    height: 8px;
}

.folder-selection::-webkit-scrollbar-track {
    background: #ecf0f1;
    border-radius: 4px;
}

.folder-selection::-webkit-scrollbar-thumb {
    background: #bdc3c7;
    border-radius: 4px;
}

.folder-selection::-webkit-scrollbar-thumb:hover {
    background: #95a5a6;
}

/* Version alternative avec scroll plus smooth */
.folder-selection {
    scroll-behavior: smooth;
}

/* Responsive: sur mobile, garder vertical si préféré */
@media (max-width: 768px) {
    .folder-selection .folder-container {
        /* Décommentez ces lignes si vous voulez vertical sur mobile */
        flex-direction: column; 
         align-items: stretch; 
    }
    
    .folder-card {
        /* Sur mobile, ajustez la largeur minimum */
        min-width: 280px;
        width: 320px;
    }
}

        .progress-bar {
            background: #f0f0f0;
            border-radius: 20px;
            height: 8px;
            margin-bottom: 15px;
            position: relative;
            overflow: hidden;
        }

        .progress-fill {
            background: linear-gradient(90deg, #4caf50, #2196f3);
            height: 100%;
            border-radius: 20px;
            transition: width 0.3s ease;
        }

        .progress-text {
            position: absolute;
            right: 10px;
            top: -25px;
            font-size: 12px;
            font-weight: bold;
            color: #2196f3;
        }

        .folder-meta {
            display: flex;
            gap: 15px;
            font-size: 12px;
            color: #666;
        }

        .status {
            padding: 8px 4px;
            border-radius: 12px;
            font-weight: bold;
        }

        .apprentissage {
            background: #e8f5e8;
            color: #4caf50;
        }

        .alternance {
            background: #e3f2fd;
            color: #2196f3;
        }

        .rupture {
            background: #ffebee;
            color: #f44336;
        }

        /* Progress Section */
        .progress-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .progress-info h2 {
            font-size: 20px;
            color: #2c3e50;
        }

        .engage-btn {
            background: #2196f3;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s ease;
        }

        .engage-btn:hover {
            background: #1976d2;
        }

        .completion-status {
            text-align: center;
        }

        .mascot {
            margin-bottom: 20px;
        }

        .mascot-character {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            background: linear-gradient(45deg, #ffeb3b, #ff9800);
            border-radius: 50%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }

        

        /* Status Cards */
        .status-cards {
            display: grid;
            gap: 20px;
            margin-bottom: 30px;
        }

        .profile-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .profile-card h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .card-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            font-size: 12px;
            color: #666;
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .status-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #2196f3;
        }

        .status-card.enterprise {
            border-left-color: #ff9800;
        }

        .status-card.school {
            border-left-color: #4caf50;
        }

        .card-icon {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .status-card h4 {
            font-size: 16px;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .status-card p {
            color: #666;
            margin-bottom: 15px;
        }

        /* Breadcrumb */
        .breadcrumb {
            font-size: 12px;
            color: #666;
            margin-bottom: 15px;
        }

        .breadcrumb a {
            color: #2196f3;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        /* Tab Navigation */
        .tab-nav {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
            border-bottom: 1px solid #e0e0e0;
        }

        .tab-nav button {
            background: none;
            border: none;
            padding: 10px 0;
            font-size: 16px;
            color: #666;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .tab-nav button.active {
            color: #2196f3;
            border-bottom-color: #2196f3;
        }

        /* Form Styles */
        .form-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .form-section h3 {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #2196f3;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .radio-group {
            display: flex;
            gap: 20px;
        }

        .radio-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        
      

       

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 12px;
            color: #666;
        }

        .info-value {
            font-size: 12px;
            font-weight: bold;
            color: #2c3e50;
        }

        .atlas-logo {
            font-size: 24px;
            font-weight: bold;
            color: #2196f3;
            margin-bottom: 20px;
        }

        .upload-section {
            background: #f8f9fa;
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-section:hover {
            border-color: #2196f3;
            background: #f0f8ff;
        }

        .upload-section button {
            background: #2196f3;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }
            
            .main-content {
                margin-left: 0;
            }
            
           
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .status-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <!-- Header -->
            <header class="header">
                <div class="logo">
                    <div class="logo-icon"></div>
                    <div class="welcome-section">
                        <h1>Bienvenue sur votre espace Entreprise</h1>
                        <p>Vous pouvez suivre l'avancement de votre (vos) dossier(s).</p>
                    </div>
                </div>
                <div class="language-selector">
                    <span class="flag">🇫🇷</span>
                    FR
                </div>
            </header>

            <!-- Home View -->
            <div id="home" class="view active">
                <!-- Folder Selection -->
                <section class="folder-selection">
                    <div class="folder-selection">
                        <h2>Sélectionnez un dossier </h2>
                        <div class="folder-container">

                                  
                            <?php  if(!empty($tableaux)) {  
                                foreach($tableaux as $tableau){
                                    $formation = FormationController::getFormations($tableau->idformation);
                                    $entreprise = EntrepriseController::getEntreprises($tableau->idemployeur);
                                ?>
                                    <div class="folder-card" onclick="showView('<?= $tableau->id ?>')">
                                        <h3><?= $entreprise->nomE ?></h3>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: 100%"></div>
                                            <div class="progress-text">100%</div>
                                        </div>
                                        <div class="folder-meta">
                                            <span>👁️<?= $formation->nomF ?></span>
                                            <span>📅<?= date('d/m/Y', strtotime($formation->debutO))." ".date('d/m/Y', strtotime($formation->prevuO)) ?></span>
                                            <span class="status apprentissage">Apprentissage</span>
                                        </div>
                                    </div>

                            <?php } }else{ 
                                header('Location: /app/logout');
                                ?>
                                <!-- <div class="folder-card" onclick="showView('folder-detail')">
                                    <h3>LGX CREATION</h3>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 100%"></div>
                                        <div class="progress-text">100%</div>
                                    </div>
                                    <div class="folder-meta">
                                        <span>👁️ INGETIS 3</span>
                                        <span>📅 01/11/2024 - 09/09/2025</span>
                                        <span class="status apprentissage">Apprentissage</span>
                                    </div>
                                </div> -->
                            <?php } ?> 
                        </div>
                    </div>
                </section>

                <!-- Progress Overview -->
                <section class="progress-section">
                    <div class="progress-info">
                        <h2>Avancée du dossier</h2>
                        
                    </div>

                      <?php   if(!empty($tableaux)) {   
                                    $elementPlusRecent = null;
                                    $datePlusRecente = null;
                                    $elementAvecDateNull = null;

                                    foreach($tableaux as $tableau) {
                                        // Si la date de fin est null, on le garde en priorité
                                        if ($tableau->finC === null || $tableau->finC === '') {
                                            $elementAvecDateNull = $tableau;
                                            continue;
                                        }
                                        
                                        // Sinon, on compare les dates
                                        $dateFinTimestamp = strtotime($tableau->finC);
                                        
                                        if ($datePlusRecente === null || $dateFinTimestamp > $datePlusRecente) {
                                            $datePlusRecente = $dateFinTimestamp;
                                            $elementPlusRecent = $tableau;
                                        }
                                    }

                                    // Priorité : élément avec date null, sinon le plus récent
                                    $resultat = $elementAvecDateNull !== null ? $elementAvecDateNull : $elementPlusRecent;

                                    if(empty($resultat->nomA)) {
                                ?>
                                        <div class="completion-status status-fill-contract"  onclick="showViewDetail('<?= $resultat->id ?>')">
                                            <div class="status-icon">
                                                <i class="fas fa-file-contract"></i>
                                            </div>
                                            <div class="completion-text">
                                                <strong>Action requise :</strong><br>
                                                Vous devez remplir votre contrat d'apprentissage
                                            </div>
                                        </div>
                                <?php 
                                    } else {
                                        if(!empty($resultat->nomA) && empty($resultat->modeC)) {
                                ?>
                                            <div class="completion-status status-waiting-signature">
                                                <div class="status-icon">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                                <div class="completion-text">
                                                    <strong>En attente :</strong><br>
                                                    Vous avez rempli votre contrat. Vous serez invité prochainement à le signer
                                                </div>
                                            </div>
                                <?php 
                                        } elseif(!empty($resultat->nomA) && !empty($resultat->modeC)) { 
                                            if(empty($resultat->signatureApprenti)) {
                                ?>
                                                <div class="completion-status status-sign-contract" onclick="showViewDetail('<?= $resultat->id ?>')">
                                                    <div class="status-icon">
                                                        <i class="fas fa-pen-nib"></i>
                                                    </div>
                                                    <div class="completion-text">
                                                        <strong>Signature requise :</strong><br>
                                                        Votre contrat est prêt, vous devez le signer
                                                    </div>
                                                </div>
                                <?php   
                                            } else {
                                ?>
                                                <div class="completion-status status-complete">
                                                    <div class="status-icon mascot-character">🎉</div>
                                                    <div class="completion-text">
                                                        <strong>Félicitations !</strong><br>
                                                        Le dossier est complet et validé !
                                                    </div>
                                                </div>
                                <?php   
                                            }  
                                        } 
                                    }
                                } else { 
                                ?>
                                    <div class="completion-status status-no-action">
                                        <div class="status-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="completion-text">
                                            <strong>Tout est en ordre :</strong><br>
                                            Vous n'avez aucune action à effectuer pour le moment
                                        </div>
                                    </div>
                                <?php } ?>
                    
                   


                </section>

                
            </div>

          
           
        </div>
    </div>
    <footer>
            <?php
            include __DIR__ . '/../elements/footer.php';
            ?>
    </footer>
    <script>
       async function showView(id) {
            try {
                const response = await fetch('select_detail', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest' // Pour identifier les requêtes AJAX
                    },
                    body: JSON.stringify({ id: id })
                });
                
                const data = await response.json();
                
                if (data.erreur) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: data.erreur
                    });
                } else {
                    // Traitement du succès si nécessaire
                    window.location.href = 'detail'; // Redirection si besoin
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur réseau',
                    text: 'Impossible de communiquer avec le serveur'+error
                });
            }
        }


        async function showViewDetail(id) {
    try {
        const response = await fetch('select_signature', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ id: id })
        });
        
        if (response.redirected) {
            // Si le serveur a fait une redirection
            window.location.href = response.url;
            return;
        }
        
        const data = await response.json();
        
        if (data.erreur) {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: data.erreur
            });
        } else {
            window.location.href = 'signature';
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Erreur réseau',
            text: 'Impossible de communiquer avec le serveur: ' + error.message
        });
    }
}
    </script>
</body>
</html>