<?php // Admin : home-content.php

include __DIR__.'../../elements/header.php';
// require_once __DIR__ . '/../../requestFile/authRequet.php';
require_once __DIR__ . '/../../Controller/HomeController.php';
require_once __DIR__ . '/../../Controller/AssistanceController.php';
// require_once __DIR__ . '/../../Controller/User/validTokenController.php';

$tableau = AssistanceController::ticket();
$ticketList = $tableau["ticketList"];
$ticketsEnCours = $tableau["ticketsEnCours"];
$ticketsHistorique = $tableau["ticketsHistorique"];
?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Acceuil | CerFacil</title>
    <style>
        footer {
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
            max-width: 1500px;
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

        .completion-text {
            font-size: 18px;
            font-weight: bold;
            color: #4caf50;
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
                        <h1>Bienvenue sur votre espace</h1>
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
                        <h2>Sélectionnez un dossier sss</h2>
                        <div class="folder-container">

                            <div class="folder-card" onclick="showView('folder-detail')">
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
                            </div>
                                    
                            <div class="folder-card" onclick="showView('folder-detail')">
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
                            </div>

                           

                            

                             

                           

                            
                            
                        </div>
                    </div>
                </section>

                <!-- Progress Overview -->
                <section class="progress-section">
                    <div class="progress-info">
                        <h2>Avancée du dossier</h2>
                        <button class="engage-btn" onclick="window.location.href='detail'">S'ENGAGER</button>
                    </div>
                    
                    <div class="completion-status">
                        <div class="mascot">
                            <div class="mascot-character">🎉</div>
                        </div>
                        <div class="completion-text">Le dossier est complet et validé!</div>
                    </div>
                </section>

                <!-- Student Profile Card -->
                <section class="status-cards">
                    <div class="profile-card">
                        <h3>Heriol Valdo Zeufack Fiemo</h3>
                        <div class="card-meta">
                            <span>👁️ Ingetis 3</span>
                            <span>📅 1 novembre 2024</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 100%"></div>
                            <div class="progress-text">100%</div>
                        </div>
                        <div class="folder-meta">
                            <span>4/4 tâches</span>
                        </div>
                    </div>
                </section>

                <!-- Status Grid -->
                <section class="status-grid">
                    <div class="status-card enterprise">
                        <div class="card-icon">🏢</div>
                        <h4>Entreprise</h4>
                        <p>Lgx Creation</p>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 100%"></div>
                            <div class="progress-text">100%</div>
                        </div>
                        <div class="folder-meta">
                            <span>5/5 tâches</span>
                        </div>
                    </div>

                    <div class="status-card school">
                        <div class="card-icon">🎓</div>
                        <h4>École</h4>
                        <p>Ingetis 3</p>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 100%"></div>
                            <div class="progress-text">100%</div>
                        </div>
                        <div class="folder-meta">
                            <span>3/3 tâches</span>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Folder Detail View -->
            <div id="folder-detail" class="view">
                <!-- Breadcrumb -->
                <div class="breadcrumb">
                    <a href="#" onclick="showView('home')">EXPERT EN ARCHITECTURE ET DÉVELOPPEMENT LOGICIEL</a> > 
                    <a href="#">EXPERT DEV - 24/25</a> > 
                    <a href="#">Heriol Valdo ZEUFACK FIEMO</a> > 
                    <span>Dossier</span>
                </div>

                <!-- Folder Header -->
                <div class="folder-header">
                    <h1>Heriol Valdo ZEUFACK FIEMO</h1>
                    <span class="status rupture">RUPTURE 25/02/2025</span>
                </div>

                <!-- Tab Navigation -->
                <div class="tab-nav">
                    <button class="active" onclick="showTab('suivi')">Suivi dossier</button>
                    <button onclick="showTab('etudiant')">Étudiant</button>
                    <button onclick="showTab('contrat')">Contrat</button>
                    <button onclick="showTab('entreprise')">Entreprise</button>
                </div>

                <!-- Suivi Dossier Tab -->
                <div id="suivi" class="tab-content active">
                    <div class="progress-section">
                        <div class="progress-info">
                            <h2>Avancée du dossier</h2>
                            <a href="#" class="engage-btn">Historique</a>
                        </div>
                        
                        <div class="completion-status">
                            <div class="mascot">
                                <div class="mascot-character">🎉</div>
                            </div>
                            <div class="completion-text">Le dossier est complet et validé!</div>
                        </div>
                    </div>
                </div>

                <!-- Étudiant Tab -->
                <div id="etudiant" class="tab-content">
                    <div class="form-section">
                        <h3>Formation</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nom de la formation</label>
                                <input type="text" value="EXPERT EN ARCHITECTURE ET DÉVELOPPEMENT LOGICIEL" readonly>
                            </div>
                            <div class="form-group">
                                <label>Nom de la promotion</label>
                                <input type="text" value="EXPERT DEV - 24/25" readonly>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Adresse de réalisation de la formation</label>
                            <input type="text" value="62 bis Rue Gay-Lussac, 75005 Paris, France" readonly>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Numéro</label>
                                <input type="text" value="62 bis" readonly>
                            </div>
                            <div class="form-group">
                                <label>Rue</label>
                                <input type="text" value="Rue Gay-Lussac" readonly>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Complément d'adresse (optionnel)</label>
                            <input type="text" readonly>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Code postal</label>
                                <input type="text" value="75005" readonly>
                            </div>
                            <div class="form-group">
                                <label>Ville</label>
                                <input type="text" value="Paris" readonly>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Pays</label>
                            <input type="text" value="France" readonly>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Date de début de la formation</label>
                                <input type="date" value="2024-09-23" readonly>
                            </div>
                            <div class="form-group">
                                <label>Date de fin de la formation</label>
                                <input type="date" value="2025-07-09" readonly>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Durée de la formation</label>
                            <input type="text" value="1204 heures" readonly>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Responsable pédagogique</label>
                                <input type="text" value="Nicolas Rita" readonly>
                            </div>
                            <div class="form-group">
                                <label>Convention</label>
                                <input type="text" value="FILIZ - APPRENTISSAGE" readonly>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Responsable dossier</label>
                            <input type="text" value="Safa Moumen" readonly>
                        </div>
                    </div>
                </div>

                <!-- Contrat Tab -->
                <div id="contrat" class="tab-content">
                    <div class="form-section">
                        <h3>Termes du contrat</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Contrat</label>
                                <input type="text" value="Succession de contrats (étudiant qui était déjà en contrat d'alternance précédemment)" readonly>
                            </div>
                            <div class="form-group">
                                <label>Raison de ce nouveau contrat</label>
                                <input type="text" value="21 - Nouveau contrat avec un apprenti qui a terminé son précédent contrat auprès d'un même employeur" readonly>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Numéro du contrat étudiant</label>
                            <input type="text" value="003202310080464" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Quelle est la nature du contrat ?</label>
                            <input type="text" value="CDD" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Existe-t-il une dérogation à ce contrat ?</label>
                            <div class="radio-group">
                                <div class="radio-item">
                                    <input type="radio" name="derogation" value="oui" id="derogation-oui">
                                    <label for="derogation-oui">Oui</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" name="derogation" value="non" id="derogation-non" checked>
                                    <label for="derogation-non">Non</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Durée hebdomadaire du travail</label>
                                <input type="text" value="35 heures" readonly>
                            </div>
                            <div class="form-group">
                                <label>Durée hebdomadaire du travail</label>
                                <input type="text" value="00 minutes" readonly>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Montant par repas</label>
                                <input type="text" value="0 €/repas" readonly>
                            </div>
                            <div class="form-group">
                                <label>Montant pour le logement</label>
                                <input type="text" value="0 €/mois" readonly>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Existe-t-il d'autres avantages en nature ?</label>
                            <input type="text" value="Non" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Intitulé du poste</label>
                            <input type="text" value="Développeur fullstack" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>L'apprenti va-t-il travailler sur des machines dangereuses ou à des risques ?</label>
                            <input type="text" value="Non" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Décrivez les missions de votre futur apprenti</label>
                            <textarea readonly>Concevoir et analyser des logiciels</textarea>
                        </div>
                    </div>
                </div>

                <!-- Entreprise Tab -->
                <div id="entreprise" class="tab-content">
                    <div class="form-section">
                        <h3>LGX CREATION</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Prénom contact entreprise</label>
                                <input type="text" value="Jessica" readonly>
                            </div>
                            <div class="form-group">
                                <label>Nom contact entreprise</label>
                                <input type="text" value="Dubois" readonly>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Adresse email contact entreprise</label>
                            <input type="email" value="jessica@lgx-france.fr" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Adresse de réalisation de la formation</label>
                            <input type="text" value="17 Doyat, 03250 Arronnes, France" readonly>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Numéro</label>
                                <input type="text" value="17" readonly>
                            </div>
                            <div class="form-group">
                                <label>Rue</label>
                                <input type="text" value="Doyat" readonly>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Complément d'adresse (optionnel)</label>
                            <input type="text" readonly>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Code postal</label>
                                <input type="text" value="03250" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
            <?php
            include __DIR__ . '/../elements/footer.php';
            ?>
    </footer>
</body>
</html>