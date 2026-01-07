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
    <title>Detail | CerFacil</title>
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
        .header {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 12px 100px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo {
            width: 32px;
            height: 32px;
            background: linear-gradient(45deg, #3b82f6, #8b5cf6);
            border-radius: 8px;
        }

        .breadcrumb {
            font-size: 14px;
            color: #6b7280;
        }

        .breadcrumb a {
            color: #3b82f6;
            text-decoration: none;
        }

        .main-header {
            background: white;
            padding: 20px 100px;
            border-bottom: 1px solid #e5e7eb;
        }

        .profile-section {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .profile-name {
            font-size: 24px;
            font-weight: 600;
            color: #111827;
        }

        .rupture-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            border: 1px solid #f59e0b;
        }

        .date {
            color: #6b7280;
            font-size: 14px;
        }

        .nav-tabs {
            background: white;
            padding: 0 100px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            gap: 32px;
        }

        .nav-tab {
            padding: 16px 0;
            color: #6b7280;
            text-decoration: none;
            border-bottom: 2px solid transparent;
            font-weight: 500;
            cursor: pointer;
        }

        .nav-tab.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }

        .nav-tab:hover {
            color: #3b82f6;
        }

        .content {
            max-width: auto;
            margin: 0 auto;
            padding: 32px 100px;
        }

        .form-section {
            background: white;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 24px;
            color: #111827;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        .form-row.full {
            grid-template-columns: 1fr;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-input {
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-input:disabled {
            background-color: #f9fafb;
            color: #9ca3af;
        }

        .form-select {
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            cursor: pointer;
        }

        .form-textarea {
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            resize: vertical;
            min-height: 80px;
        }

        .info-icon {
            width: 16px;
            height: 16px;
            background: #6b7280;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            margin-left: 8px;
            cursor: help;
        }

        .radio-group {
            display: flex;
            gap: 16px;
            margin-top: 8px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .radio-input {
            width: 16px;
            height: 16px;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .contract-section {
            margin-bottom: 32px;
        }

        .contract-info {
            background: #f3f4f6;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .contract-reason {
            font-size: 14px;
            color: #6b7280;
            background: #f9fafb;
            padding: 12px;
            border-radius: 6px;
            border-left: 3px solid #3b82f6;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .nav-tabs {
                gap: 16px;
                overflow-x: auto;
            }
            
            .content {
                padding: 16px;
            }
            
            .form-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="header">
        <div class="logo"></div>
        <div class="breadcrumb">
            <a href="#">EXPERT EN ARCHITECTURE ET DÉVELOPPEMENT LOGICIEL</a> › 
            <a href="#">EXPERT DEV - 24/25</a> › 
            <strong>Heriot Valdo ZEUFACK FIEMO</strong> › 
            Dossier
        </div>
    </div>

    <div class="main-header">
        <div class="profile-section">
            <h1 class="profile-name">Heriot Valdo ZEUFACK FIEMO</h1>
            <span class="rupture-badge">RUPTURE</span>
            <span class="date">25/02/2025</span>
        </div>
    </div>

    <nav class="nav-tabs">
        <!-- <a href="home" class="nav-tab" data-tab="follow">Suivi dossier</a> -->
        <a href="#" class="nav-tab active" data-tab="student">Étudiant</a>
         <a href="#" class="nav-tab " data-tab="formation">Formation</a>
        <a href="#" class="nav-tab" data-tab="contract">Contrat</a>
        <a href="#" class="nav-tab" data-tab="company">Entreprise</a>
    </nav>

    <div class="content">

     <!-- Onglet Étudiant -->
        <div id="tab-student" class="tab-content active">
            <div class="form-section">
                <h2 class="section-title">Etudiant</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nom de la formation</label>
                        <input type="text" class="form-input" value="EXPERT EN ARCHITECTURE ET DÉVELOPPEMENT LOGICIEL" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom de la promotion</label>
                        <input type="text" class="form-input" value="EXPERT DEV - 24/25">
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label class="form-label">Adresse de réalisation de la formation</label>
                        <input type="text" class="form-input" value="62 bis Rue Gay-Lussac, 75005 Paris, France">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Numéro</label>
                        <input type="text" class="form-input" value="62 bis">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rue</label>
                        <input type="text" class="form-input" value="Rue Gay-Lussac">
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label class="form-label">Complément d'adresse (optionnel)</label>
                        <input type="text" class="form-input">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Code postal</label>
                        <input type="text" class="form-input" value="75005">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ville</label>
                        <input type="text" class="form-input" value="Paris">
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label class="form-label">Pays</label>
                        <input type="text" class="form-input" value="France">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Date de début de la formation</label>
                        <input type="date" class="form-input" value="2024-09-23">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de fin de la formation</label>
                        <input type="date" class="form-input" value="2025-07-09">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Durée de la formation</label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="number" class="form-input" value="1204" style="flex: 1;">
                            <span>heures</span>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Responsable pédagogique</label>
                        <input type="text" class="form-input" value="Nicolas Rita">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Formation</label>
                        <input type="text" class="form-input" value="FILIZ - APPRENTISSAGE">
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label class="form-label">Responsable dossier</label>
                        <input type="text" class="form-input" value="Safa Moumen">
                    </div>
                </div>
            </div>
        </div>

        <!-- Onglet formation -->
        <div id="tab-formation" class="tab-content ">
            <div class="form-section">
                <h2 class="section-title">Formation</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nom de la formation</label>
                        <input type="text" class="form-input" value="EXPERT EN ARCHITECTURE ET DÉVELOPPEMENT LOGICIEL" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom de la promotion</label>
                        <input type="text" class="form-input" value="EXPERT DEV - 24/25">
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label class="form-label">Adresse de réalisation de la formation</label>
                        <input type="text" class="form-input" value="62 bis Rue Gay-Lussac, 75005 Paris, France">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Numéro</label>
                        <input type="text" class="form-input" value="62 bis">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rue</label>
                        <input type="text" class="form-input" value="Rue Gay-Lussac">
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label class="form-label">Complément d'adresse (optionnel)</label>
                        <input type="text" class="form-input">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Code postal</label>
                        <input type="text" class="form-input" value="75005">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ville</label>
                        <input type="text" class="form-input" value="Paris">
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label class="form-label">Pays</label>
                        <input type="text" class="form-input" value="France">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Date de début de la formation</label>
                        <input type="date" class="form-input" value="2024-09-23">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de fin de la formation</label>
                        <input type="date" class="form-input" value="2025-07-09">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Durée de la formation</label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="number" class="form-input" value="1204" style="flex: 1;">
                            <span>heures</span>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Responsable pédagogique</label>
                        <input type="text" class="form-input" value="Nicolas Rita">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Formation</label>
                        <input type="text" class="form-input" value="FILIZ - APPRENTISSAGE">
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label class="form-label">Responsable dossier</label>
                        <input type="text" class="form-input" value="Safa Moumen">
                    </div>
                </div>
            </div>
        </div>

        <!-- Onglet Contrat -->
        <div id="tab-contract" class="tab-content">
            <div class="form-section">
                <h2 class="section-title">Termes du contrat</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Contrat</label>
                        <input type="text" class="form-input" value="Succession de contrats (étudiant qui était déjà en contrat d'alternance précédemment)">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Raison de ce nouveau contrat</label>
                        <div class="contract-reason">
                            21 - Nouveau contrat avec un apprenti qui a terminé son précédent contrat auprès d'un même employeur
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Numéro du contrat étudiant</label>
                        <div style="display: flex; align-items: center;">
                            <input type="text" class="form-input" value="003202310080464">
                            <span class="info-icon">i</span>
                        </div>
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label class="form-label">Quelle est la nature du contrat ?</label>
                        <select class="form-select">
                            <option value="CDD" selected>CDD</option>
                            <option value="CDI">CDI</option>
                        </select>
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label class="form-label">Existe-t-il une dérogation à ce contrat ?</label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="derogation" value="oui" class="radio-input">
                                <span>Oui</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="derogation" value="non" class="radio-input" checked>
                                <span>Non</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Durée hebdomadaire du travail</label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="number" class="form-input" value="35" style="flex: 1;">
                            <span>heures</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Durée hebdomadaire du travail</label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="number" class="form-input" value="00" style="flex: 1;">
                            <span>minutes</span>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Montant par repas</label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="number" class="form-input" value="0" style="flex: 1;">
                            <span>€/repas</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Montant pour le logement</label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="number" class="form-input" value="0" style="flex: 1;">
                            <span>€/mois</span>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Existe-t-il d'autres avantages en nature ?</label>
                        <select class="form-select">
                            <option value="non" selected>Non</option>
                            <option value="oui">Oui</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Intitulé du poste</label>
                        <input type="text" class="form-input" value="Développeur fullstack">
                    </div>
                    <div class="form-group">
                        <label class="form-label">L'apprenti va-t-il travailler sur des machines dangereuses ou expositions à des risques ?</label>
                        <select class="form-select">
                            <option value="non" selected>Non</option>
                            <option value="oui">Oui</option>
                        </select>
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label class="form-label">Décrivez les missions de votre futur apprenti</label>
                        <textarea class="form-textarea" placeholder="Concevoir et analyser des logiciels"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Onglet Entreprise -->
        <div id="tab-company" class="tab-content">
            <div class="form-section">
                <h2 class="section-title">LGX CREATION</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Prénom contact entreprise</label>
                        <input type="text" class="form-input" value="Jessica">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom contact entreprise</label>
                        <input type="text" class="form-input" value="Dubois">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Adresse email contact entreprise</label>
                        <input type="email" class="form-input" value="jessica@lgx-france.fr">
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label class="form-label">Adresse de réalisation de la formation</label>
                        <input type="text" class="form-input" value="17 Doyat, 03250 Arronnes, France">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Numéro</label>
                        <input type="text" class="form-input" value="17">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rue</label>
                        <input type="text" class="form-input" value="Doyat">
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label class="form-label">Complément d'adresse (optionnel)</label>
                        <input type="text" class="form-input">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Code postal</label>
                        <input type="text" class="form-input" value="03250">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ville</label>
                        <input type="text" class="form-input" value="Arronnes">
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label class="form-label">Pays</label>
                        <input type="text" class="form-input" value="France">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Code APE/NAF</label>
                        <div style="display: flex; align-items: center;">
                            <input type="text" class="form-input" value="6201Z">
                            <span class="info-icon">i</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Code IDCC</label>
                        <div style="display: flex; align-items: center;">
                            <input type="text" class="form-input" value="1486">
                            <span class="info-icon">i</span>
                        </div>
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label class="form-label">Convention collective</label>
                        <textarea class="form-textarea" readonly>Convention collective nationale applicable au personnel des bureaux d'études techniques, des cabinets d'ingénieurs-conseils et des sociétés de conseils</textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Caisse de retraite complémentaire</label>
                        <input type="text" class="form-input" value="MALAKOFF MEDERIC">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nombre de salariés total</label>
                        <input type="number" class="form-input" value="10">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Quel est le secteur de votre entreprise ?</label>
                        <select class="form-select">
                            <option value="prive" selected>Privé</option>
                            <option value="public">Public</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Type d'employeur*</label>
                        <input type="text" class="form-input" value="Entreprise inscrite uniquement au registre du commerce et des sociétés" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sélectionnez le type d'employeur spécifique*</label>
                        <select class="form-select">
                            <option value="aucun" selected>Aucun de ces cas</option>
                        </select>
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
    </div>

    <script>
        // Gestion des onglets
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.nav-tab');
            const tabContents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Retirer la classe active de tous les onglets
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(tc => tc.classList.remove('active'));
                    
                    // Ajouter la classe active à l'onglet cliqué
                    this.classList.add('active');
                    
                    // Afficher le contenu correspondant
                    const tabId = this.getAttribute('data-tab');
                    const targetContent = document.getElementById(`tab-${tabId}`);
                    if (targetContent) {
                        targetContent.classList.add('active');
                    }
                });
            });

            // Gestion des formulaires
            const inputs = document.querySelectorAll('.form-input, .form-select, .form-textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.borderColor = '#3b82f6';
                    this.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1)';
                });

                input.addEventListener('blur', function() {
                    this.style.borderColor = '#d1d5db';
                    this.style.boxShadow = 'none';
                });
            });

            // Animation des info-icons
            const infoIcons = document.querySelectorAll('.info-icon');
            infoIcons.forEach(icon => {
                icon.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#3b82f6';
                    this.style.transform = 'scale(1.1)';
                });

                icon.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '#6b7280';
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>