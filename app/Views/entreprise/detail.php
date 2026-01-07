<?php // Admin : home-content.php

include __DIR__.'../../elements/header.php';
// require_once __DIR__ . '/../../requestFile/authRequet.php';
require_once __DIR__ . '/../../Controller/FormationController.php';
require_once __DIR__ . '/../../Controller/EntrepriseController.php';
require_once __DIR__ . '/../../Controller/CerfaController.php';

$cerfa = CerfaController::getCerfasbyId($_SESSION['idDossier']);
$formation = FormationController::getFormations($cerfa->idformation);
$entreprise = EntrepriseController::getEntreprises($cerfa->idemployeur);

function nameOpco($idopco){
   if($idopco== null || $idopco == 'null'  || $idopco == 0 || $idopco == '0' || $idopco == ''){
      return isEmptySave('');
   }else{
        $opco = CerfaController::getOpco($idopco);
         if (property_exists( $opco, 'erreur')) {
               return '<span class="text-danger">Erreur : '.$opco->erreur.'</span>';
        }else if(property_exists( $opco, 'valid')) {

            return $opco->data->nom;
        }
   }
}

function nameOpcos($idopco){
   if($idopco== null || $idopco == 'null'  || $idopco == 0 || $idopco == '0' || $idopco == ''){
      return isEmpty('');
   }else{
        $opco = CerfaController::getOpco($idopco);
         if (property_exists( $opco, 'erreur')) {
               return '<span class="text-danger">Erreur : '.$opco->erreur.'</span>';
        }else if(property_exists( $opco, 'valid')) {

            return $opco->data->nom;
        }
   }
}

  function isEmpty($string) {
        if(!empty($string)){
            return $string;
        }
        return '<span class="text-danger">Pas renseigné</span>';
    }

    function isEmptySave($string) {
        if(!empty($string)){
            return $string;
        }
        return 'Pas renseigné';
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail | CerFacil</title>
    <style>
        .validation-errors .alert {
    border-left: 4px solid #dc3545;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

.validation-modal .swal2-html-container {
    text-align: left !important;
}


        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-row.full {
            grid-template-columns: 1fr;
        }

        .form-row.triple {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            color: #495057;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-input, .form-select {
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .form-input[readonly] {
            background: #f8f9fa;
            color: #6c757d;
            cursor: not-allowed;
        }

        .form-display {
            padding: 12px 15px;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            color: #495057;
            min-height: 42px;
            display: flex;
            align-items: center;
        }

        .required {
            color: #e74c3c;
        }

        .control-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #2980b9, #1c5a7a);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-success {
            background: linear-gradient(45deg, #27ae60, #219a52);
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(45deg, #219a52, #1a7a41);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(45deg, #95a5a6, #7f8c8d);
            color: white;
        }

        .btn-secondary:hover {
            background: linear-gradient(45deg, #7f8c8d, #6c7b7c);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(149, 165, 166, 0.3);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .status-indicator {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }

        .status-view {
            background: #e3f2fd;
            color: #1976d2;
        }

        .status-edit {
            background: #fff3e0;
            color: #f57c00;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .control-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }

        .validation-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 8px;
            margin: 15px 0;
            border: 1px solid #c3e6cb;
        }

        
        .section-title {
            color: #2c3e50;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #3498db;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 60px;
            height: 3px;
            background: #e74c3c;
        }

        .form-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
            border-left: 4px solid #3498db;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .card-title {
            color: #2c3e50;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .text-danger {
  color: #F44336;
   font-size: 12px;
   font-weight: 600;
}


/* Style de base pour tous les labels */
.label {
  display: inline-block;
  padding: 4px 8px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin: 2px;
  line-height: 1;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  transition: all 0.2s ease-in-out;
}

/* Styles spécifiques pour chaque statut */
.label-success {
  background-color: #4CAF50;
  color: white;
  border-radius: 10px;
  border: 1px solid #388E3C;
}

.label-info {
  background-color: #2196F3;
  color: white;
  border-radius: 5px;
}

.label-primary {
  background-color: #3F51B5;
  color: white;
  border-radius: 5px;
}

.label-danger {
  background-color: #F44336;
  color: white;
  border-radius: 5px;
}

.label-default {
  background-color: #9E9E9E;
  color: white;
  border-radius: 5px;
}

.label-warning {
  background-color: #FF9800;
  color: white;
  border-radius: 5px;
}


/* Effet au survol */
.label:hover {
  opacity: 0.9;
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Version responsive */
@media (max-width: 768px) {
  .label {
    font-size: 10px;
    padding: 3px 6px;
  }
}
          body {
                font-family: 'Poppins', sans-serif;
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                min-height: 100vh;
                color: #1e293b;
            }
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
            width: 100%;
            margin-right: 20px;
            padding-left: 100px;
            padding-top: 32px;
            padding-right: 20px

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
    <div class="header">
        <div class="logo"></div>
        <div class="breadcrumb">
            <a href="home"><?= $formation->intituleF ?></a> › 
            <a href="home"><?=  date('d/m/Y', strtotime($cerfa->date_creation ))?></a> › 
            <strong><?= (empty($cerfa->nomA)? $cerfa->emailA : $cerfa->nomA ) ?></strong> › 
            Dossier
        </div>
    </div>

    <div class="main-header">
        <div class="profile-section">
            <h1 class="profile-name"><?= (empty($cerfa->nomA)? $cerfa->emailA : $cerfa->nomA ) ?></h1>
            <?= CerfaController::getEtatCerfa($cerfa->numeroInterne,$entreprise->idopco);?>
            <!-- <span class="date">25/02/2025</span> -->
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
                    <h2 class="section-title">
                        Informations de l'Apprenti(e)
                        <span id="mode-indicator" class="status-indicator status-view">Mode Consultation</span>
                    </h2>
                    
                    <div class="control-buttons">
                        <button id="edit-btn" class="btn btn-primary">Modifier</button>
                        <button id="save-btn" class="btn btn-success" style="display: none;">Valider</button>
                        <button id="cancel-btn" class="btn btn-secondary" style="display: none;">Annuler</button>
                    </div>

                    <div id="success-message" class="success-message" style="display: none;">
                        Les modifications ont été enregistrées avec succès !
                    </div>

                    <!-- Informations personnelles -->
                    <div class="form-card">
                        <h3 class="card-title">Informations personnelles</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                 <input type="hidden" class="form-input" data-input="id" value="<?= $cerfa->id ?>" style="display: none;" required>
                                <label class="form-label">Nom de naissance de l'apprenti(e) <span class="required">*</span></label>
                                <div class="form-display" data-field="nomA"><?= isEmpty($cerfa->nomA) ?></div>
                                <input type="text" class="form-input" data-input="nomA" value="<?= $cerfa->nomA ?>" style="display: none;" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nom d'usage</label>
                                <div class="form-display" data-field="nomuA"><?= isEmpty($cerfa->nomuA) ?></div>
                                <input type="text" class="form-input" data-input="nomuA" value="<?= $cerfa->nomuA ?>" style="display: none;">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Le premier prénom <span class="required">*</span></label>
                                <div class="form-display" data-field="prenomA"><?= isEmpty($cerfa->prenomA) ?></div>
                                <input type="text" class="form-input" data-input="prenomA" value="<?= $cerfa->prenomA?>" style="display: none;" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Date de naissance <span class="required">*</span></label>
                                <div class="form-display" data-field="naissanceA"><?= empty($cerfa->naissanceA)? isEmpty($cerfa->naissanceA)  : date('d/m/Y', strtotime($cerfa->naissanceA )) ?></div>
                                <input type="date" class="form-input" data-input="naissanceA" value="<?= $cerfa->naissanceA ?>" style="display: none;" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Email </span></label>
                                <div class="form-display" data-field="emailA"><?= isEmpty($cerfa->emailA) ?></div>
                                <input type="email" class="form-input" data-input="emailA" value="<?= $cerfa->emailA ?>" style="display: none;" required disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Sexe <span class="required">*</span></label>
                                <div class="form-display" data-field="sexeA"><?= isEmpty($cerfa->sexeA) ?></div>
                                <select class="form-select" data-input="sexeA" value="<?= $cerfa->sexeA ?>" style="display: none;" required>
                                    <option value="">__</option>
                                    <option value="M">M</option>
                                    <option value="F">F</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Lieu de naissance -->
                    <div class="form-card">
                        <h3 class="card-title">Lieu de naissance</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Département de naissance <span class="required">*</span></label>
                                <div class="form-display" data-field="departementA"><?= isEmpty($cerfa->departementA) ?></div>
                                <select class="form-select" data-input="departementA" style="display: none;" value="<?= $cerfa->departementA ?>"  required>
                                    <option value="">_______</option>
                                    <!-- <option value="75" selected>75 - Paris - Paris</option> -->
                                    <option value="01">01 - Ain - Bourg-en-Bresse</option>
                                    <option value="02">02 - Aisne - Laon</option>
                                    <option value="03">03 - Allier - Moulins</option>
                                    <option value="04">04 - Alpes-de-Haute-Provence - Digne-les-Bains</option>
                                    <option value="05">05 - Hautes-Alpes - Gap</option>
                                    <option value="06">06 - Alpes-Maritimes - Nice</option>
                                    <option value="07">07 - Ardèche - Privas</option>
                                    <option value="08">08 - Ardennes - Charleville-Mézières</option>
                                    <option value="09">09 - Ariège - Foix</option>
                                    <option value="10">10 - Aube - Troyes</option>
                                    <option value="11">11 - Aude - Carcassonne</option>
                                    <option value="12">12 - Aveyron - Rodez</option>
                                    <option value="13">13 - Bouches-du-Rhône - Marseille</option>
                                    <option value="14">14 - Calvados - Caen</option>
                                    <option value="15">15 - Cantal - Aurillac</option>
                                    <option value="16">16 - Charente - Angoulême</option>
                                    <option value="17">17 - Charente-Maritime - La Rochelle</option>
                                    <option value="18">18 - Cher - Bourges</option>
                                    <option value="19">19 - Corrèze - Tulle</option>
                                    <option value="2a">2A - Corse-du-Sud - Ajaccio</option>
                                    <option value="2b">2B - Haute-Corse - Bastia</option>
                                    <option value="21">21 - Côte-d'Or - Dijon</option>
                                    <option value="22">22 - Côtes-d'Armor - Saint-Brieuc</option>
                                    <option value="23">23 - Creuse - Guéret</option>
                                    <option value="24">24 - Dordogne - Périgueux</option>
                                    <option value="25">25 - Doubs - Besançon</option>
                                    <option value="26">26 - Drôme - Valence</option>
                                    <option value="27">27 - Eure - Évreux</option>
                                    <option value="28">28 - Eure-et-Loir - Chartres</option>
                                    <option value="29">29 - Finistère - Quimper</option>
                                    <option value="30">30 - Gard - Nîmes</option>
                                    <option value="31">31 - Haute-Garonne - Toulouse</option>
                                    <option value="32">32 - Gers - Auch</option>
                                    <option value="33">33 - Gironde - Bordeaux</option>
                                    <option value="34">34 - Hérault - Montpellier</option>
                                    <option value="35">35 - Ille-et-Vilaine - Rennes</option>
                                    <option value="36">36 - Indre - Châteauroux</option>
                                    <option value="37">37 - Indre-et-Loire - Tours</option>
                                    <option value="38">38 - Isère - Grenoble</option>
                                    <option value="39">39 - Jura - Lons-le-Saunier</option>
                                    <option value="40">40 - Landes - Mont-de-Marsan</option>
                                    <option value="41">41 - Loir-et-Cher - Blois</option>
                                    <option value="42">42 - Loire - Saint-Étienne</option>
                                    <option value="43">43 - Haute-Loire - Le Puy-en-Velay</option>
                                    <option value="44">44 - Loire-Atlantique - Nantes</option>
                                    <option value="45">45 - Loiret - Orléans</option>
                                    <option value="46">46 - Lot - Cahors</option>
                                    <option value="47">47 - Lot-et-Garonne - Agen</option>
                                    <option value="48">48 - Lozère - Mende</option>
                                    <option value="49">49 - Maine-et-Loire - Angers</option>
                                    <option value="50">50 - Manche - Saint-Lô</option>
                                    <option value="51">51 - Marne - Châlons-en-Champagne</option>
                                    <option value="52">52 - Haute-Marne - Chaumont</option>
                                    <option value="53">53 - Mayenne - Laval</option>
                                    <option value="54">54 - Meurthe-et-Moselle - Nancy</option>
                                    <option value="55">55 - Meuse - Bar-le-Duc</option>
                                    <option value="56">56 - Morbihan - Vannes</option>
                                    <option value="57">57 - Moselle - Metz</option>
                                    <option value="58">58 - Nièvre - Nevers</option>
                                    <option value="59">59 - Nord - Lille</option>
                                    <option value="60">60 - Oise - Beauvais</option>
                                    <option value="61">61 - Orne - Alençon</option>
                                    <option value="62">62 - Pas-de-Calais - Arras</option>
                                    <option value="63">63 - Puy-de-Dôme - Clermont-Ferrand</option>
                                    <option value="64">64 - Pyrénées-Atlantiques - Pau</option>
                                    <option value="65">65 - Hautes-Pyrénées - Tarbes</option>
                                    <option value="66">66 - Pyrénées-Orientales - Perpignan</option>
                                    <option value="67">67 - Bas-Rhin - Strasbourg</option>
                                    <option value="68">68 - Haut-Rhin - Colmar</option>
                                    <option value="69">69 - Rhône - Lyon</option>
                                    <option value="70">70 - Haute-Saône - Vesoul</option>
                                    <option value="71">71 - Saône-et-Loire - Mâcon</option>
                                    <option value="72">72 - Sarthe - Le Mans</option>
                                    <option value="73">73 - Savoie - Chambéry</option>
                                    <option value="74">74 - Haute-Savoie - Annecy</option>
                                    <option value="75">75 - Paris - Paris</option>
                                    <option value="76">76 - Seine-Maritime - Rouen</option>
                                    <option value="77">77 - Seine-et-Marne - Melun</option>
                                    <option value="78">78 - Yvelines - Versailles</option>
                                    <option value="79">79 - Deux-Sèvres - Niort</option>
                                    <option value="80">80 - Somme - Amiens</option>
                                    <option value="81">81 - Tarn - Albi</option>
                                    <option value="82">82 - Tarn-et-Garonne - Montauban</option>
                                    <option value="83">83 - Var - Toulon</option>
                                    <option value="84">84 - Vaucluse - Avignon</option>
                                    <option value="85">85 - Vendée - La Roche-sur-Yon</option>
                                    <option value="86">86 - Vienne - Poitiers</option>
                                    <option value="87">87 - Haute-Vienne - Limoges</option>
                                    <option value="88">88 - Vosges - Épinal</option>
                                    <option value="89">89 - Yonne - Auxerre</option>
                                    <option value="90">90 - Territoire de Belfort - Belfort</option>
                                    <option value="91">91 - Essonne - Évry</option>
                                    <option value="92">92 - Hauts-de-Seine - Nanterre</option>
                                    <option value="93">93 - Seine-Saint-Denis - Bobigny</option>
                                    <option value="94">94 - Val-de-Marne - Créteil</option>
                                    <option value="95">95 - Val-d'Oise - Pontoise</option>
                                    <option value="971">971 - Guadeloupe - Basse-Terre</option>
                                    <option value="972">972 - Martinique - Fort-de-France</option>
                                    <option value="973">973 - Guyane - Cayenne</option>
                                    <option value="974">974 - La Réunion - Saint-Denis</option>
                                    <option value="976">976 - Mayotte - Dzaoudzi</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Commune de naissance <span class="required">*</span></label>
                                <div class="form-display" data-field="communeNA"><?= isEmpty($cerfa->communeNA) ?></div>
                                <input type="text" class="form-input" data-input="communeNA" value="<?= $cerfa->communeNA ?>" style="display: none;" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Nationalité <span class="required">*</span></label>
                                <div class="form-display" data-field="nationaliteA"><?= isEmpty($cerfa->nationaliteA) ?></div>
                                <select class="form-select" data-input="nationaliteA" style="display: none;"  value="<?= $cerfa->nationaliteA ?>" required>
                                    <option value="">_______</option>
                                    <option value="1" >1 : Française</option>
                                    <option value="2">2 : Union Européenne</option>
                                    <option value="3">3 : Étranger hors Union Européenne</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Numéro sécurité sociale <span class="required">*</span></label>
                                <div class="form-display" data-field="securiteA"><?= isEmpty($cerfa->securiteA) ?></div>
                                <input type="text" class="form-input" data-input="securiteA" value="<?= $cerfa->securiteA ?>" style="display: none;" minlength="13" maxlength="15" required>
                            </div>
                        </div>
                    </div>

                    <!-- Situation professionnelle -->
                    <div class="form-card">
                        <h3 class="card-title">Situation professionnelle</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Situation avant ce contrat <span class="required">*</span></label>
                                <div class="form-display" data-field="situationA"><?= isEmpty($cerfa->situationA) ?></div>
                                <select class="form-select" data-input="situationA" style="display: none;" value="<?= $cerfa->situationA ?>" required>
                                    <option value="">_______</option>
                                    <option value="1">1 : Scolaire</option>
                                    <option value="2">2 : Prépa apprentissage</option>
                                    <option value="3">3 : Étudiant</option>
                                    <option value="4">4 : Contrat d’apprentissage</option>
                                    <option value="5">5 : Contrat de professionnalisation</option>
                                    <option value="6">6 : Contrat aidé</option>
                                    <option value="7">7 : En formation au CFA avant signature d’un contrat d’apprentissage (L6222-12-1 du code du travail)</option>
                                    <option value="8">8 : En formation, au CFA, sans contrat, suite à rupture (5° de L6231-2 du code du travail)</option>
                                    <option value="9">9 : Stagiaire de la formation professionnelle</option>
                                    <option value="10">10 : Salarié</option>
                                    <option value="11">11 : Personne à la recherche d’un emploi (inscrite ou non au Pôle Emploi)</option>
                                    <option value="12">12 : Inactif</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Régime social <span class="required">*</span></label>
                                <div class="form-display" data-field="regimeA"><?= isEmpty($cerfa->regimeA) ?></div>
                                <select class="form-select" data-input="regimeA" style="display: none;" value="<?= $cerfa->regimeA ?>" required>
                                    <option value="">_______</option>
                                    <option value="1">1 : MSA</option>
                                    <option value="2" >2 : URSSAF</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Formation -->
                    <div class="form-card">
                        <h3 class="card-title">Formation et diplômes</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Dernier diplôme ou titre préparé <span class="required">*</span></label>
                                <div class="form-display" data-field="titrePA"><?= isEmpty($cerfa->titrePA) ?></div>
                                <select class="form-select" data-input="titrePA" style="display: none;" value="<?= $cerfa->tirePA ?>" required>
                                    <option value="">_______</option>
                                       <optgroup label="Diplôme ou titre de niveau bac +5 et plus">
                                        <option value="80">80 : Doctorat</option>
                                        <option value="71">71 : Master professionnel/DESS</option>
                                        <option value="72">72 : Master recherche/DEA</option>
                                        <option value="73">73 : Master indifférencié</option>
                                        <option value="74">74 : Diplôme d'ingénieur, diplôme d'école de commerce</option>
                                        <option value="79">79 : Autre diplôme ou titre de niveau bac+5 ou plus</option>
                                    </optgroup>
                                    <optgroup label="Diplôme ou titre de niveau bac +3 et 4">
                                        <option value="61">61 : 1ère année de Master</option>
                                        <option value="62">62 : Licence professionnelle</option>
                                        <option value="63">63 : Licence générale</option>
                                        <option value="69">69 : Autre diplôme ou titre de niveau bac +3 ou 4</option>
                                    </optgroup>
                                    <optgroup label="Diplôme ou titre de niveau bac +2">
                                        <option value="54">54 : Brevet de Technicien Supérieur</option>
                                        <option value="55">55 : Diplôme Universitaire de technologie</option>
                                        <option value="58">58 : Autre diplôme ou titre de niveau bac+2</option>
                                    </optgroup>
                                    <optgroup label="Diplôme ou titre de niveau bac">
                                        <option value="41">41 : Baccalauréat professionnel</option>
                                        <option value="42">42 : Baccalauréat général</option>
                                        <option value="43">43 : Baccalauréat technologique</option>
                                        <option value="49">49 : Autre diplôme ou titre de niveau bac</option>
                                    </optgroup>
                                    <optgroup label="Diplôme ou titre de niveau CAP/BEP">
                                        <option value="33">33 : CAP</option>
                                        <option value="34">34 : BEP</option>
                                        <option value="35">35 : Mention complémentaire</option>
                                        <option value="38">38 : Autre diplôme ou titre de niveau CAP/BEP</option>
                                    </optgroup>
                                    <optgroup label="Aucun diplôme ni titre">
                                        <option value="25">25 : Diplôme national du Brevet (DNB)</option>
                                        <option value="26">26 : Certificat de formation générale</option>
                                        <option value="13">13 : Aucun diplôme ni titre professionnel</option>
                                    
                                    </optgroup>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Dernière classe <span class="required">*</span></label>
                                <div class="form-display" data-field="derniereCA"><?= isEmpty($cerfa->derniereCA) ?></div>
                                <select class="form-select" data-input="derniereCA" value="<?= $cerfa->derniereCA ?>" style="display: none;" required>
                                    <option value="">_______</option>
                                    <option value="1">1 : l’apprenti a suivi la dernière année du cycle de formation et a obtenu le diplôme ou titre</option>
                                    <option value="11">11 : l’apprenti a suivi la 1ère année du cycle et l’a validée (examens réussis mais année non diplômante)</option>
                                    <option value="12">12 : l’apprenti a suivi la 1ère année du cycle mais ne l’a pas validée (échec aux examens, interruption ou abandon de formation)</option>
                                    <option value="21">21 : l’apprenti a suivi la 2è année du cycle et l’a validée (examens réussis mais année non diplômante)</option>
                                    <option value="22">22 : l’apprenti a suivi la 2è année du cycle mais ne l’a pas validée (échec aux examens, interruption ou abandon de formation)</option>
                                    <option value="31">31 : l’apprenti a suivi la 3è année du cycle et l’a validée (examens réussis mais année non diplômante, cycle adapté)</option>
                                    <option value="32">32 : l’apprenti a suivi la 3è année du cycle mais ne l’a pas validée (échec aux examens, interruption ou abandon de formation)</option>
                                    <option value="40">40 : l’apprenti a achevé le 1er cycle de l’enseignement secondaire (collège)</option>
                                    <option value="41">41 : l’apprenti a interrompu ses études en classe de 3ème</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row ">
                              <div class="form-group">
                                <label class="form-label">Diplôme ou titre le plus élevé obtenu <span class="required">*</span></label>
                                <div class="form-display" data-field="titreOA"><?= isEmpty($cerfa->titreOA) ?></div>
                                <select class="form-select" data-input="titreOA" style="display: none;"   value="<?= $cerfa->titreOA ?>" required>
                                    <option value="">_______</option>
                                     <optgroup label="Diplôme ou titre de niveau bac +5 et plus">
                                        <option value="80">80 : Doctorat</option>
                                        <option value="71">71 : Master professionnel/DESS</option>
                                        <option value="72">72 : Master recherche/DEA</option>
                                        <option value="73">73 : Master indifférencié</option>
                                        <option value="74">74 : Diplôme d'ingénieur, diplôme d'école de commerce</option>
                                        <option value="79">79 : Autre diplôme ou titre de niveau bac+5 ou plus</option>
                                    </optgroup>
                                    <optgroup label="Diplôme ou titre de niveau bac +3 et 4">
                                        <option value="61">61 : 1ère année de Master</option>
                                        <option value="62">62 : Licence professionnelle</option>
                                        <option value="63">63 : Licence générale</option>
                                        <option value="69">69 : Autre diplôme ou titre de niveau bac +3 ou 4</option>
                                    </optgroup>
                                    <optgroup label="Diplôme ou titre de niveau bac +2">
                                        <option value="54">54 : Brevet de Technicien Supérieur</option>
                                        <option value="55">55 : Diplôme Universitaire de technologie</option>
                                        <option value="58">58 : Autre diplôme ou titre de niveau bac+2</option>
                                    </optgroup>
                                    <optgroup label="Diplôme ou titre de niveau bac">
                                        <option value="41">41 : Baccalauréat professionnel</option>
                                        <option value="42">42 : Baccalauréat général</option>
                                        <option value="43">43 : Baccalauréat technologique</option>
                                        <option value="49">49 : Autre diplôme ou titre de niveau bac</option>
                                    </optgroup>
                                    <optgroup label="Diplôme ou titre de niveau CAP/BEP">
                                        <option value="33">33 : CAP</option>
                                        <option value="34">34 : BEP</option>
                                        <option value="35">35 : Mention complémentaire</option>
                                        <option value="38">38 : Autre diplôme ou titre de niveau CAP/BEP</option>
                                    </optgroup>
                                    <optgroup label="Aucun diplôme ni titre">
                                        <option value="25">25 : Diplôme national du Brevet (DNB)</option>
                                        <option value="26">26 : Certificat de formation générale</option>
                                        <option value="13">13 : Aucun diplôme ni titre professionnel</option>
                                    
                                    </optgroup>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Intitulé précis du dernier diplôme ou titre préparé <span class="required">*</span></label>
                                <div class="form-display" data-field="intituleA"><?= isEmpty($cerfa->intituleA) ?></div>
                                <input type="text" class="form-input" data-input="intituleA" value="<?= $cerfa->intituleA ?>" style="display: none;" required>
                            </div>
                        </div>

                      
                    </div>

                    <!-- Déclarations -->
                    <div class="form-card">
                        <h3 class="card-title">Déclarations</h3>
                        
                        <div class="form-row triple">
                            <div class="form-group">
                                <label class="form-label">Sportif de haut niveau <span class="required">*</span></label>
                                <div class="form-display" data-field="declareSA"><?= isEmpty($cerfa->declareSA) ?></div>
                                <select class="form-select" data-input="declareSA" value="<?= $cerfa->declareSA ?>" style="display: none;" required>
                                    <option value="">__</option>
                                    <option value="oui">Oui</option>
                                    <option value="non" selected>Non</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Travailleur handicapé  <span class="required">*</span> </label>
                                <div class="form-display" data-field="declareHA"><?= isEmpty($cerfa->declareHA) ?></div>
                                <select class="form-select" data-input="declareHA" style="display: none;" value="<?= $cerfa->declareHA ?>" required>
                                    <option value="">__</option>
                                    <option value="oui">Oui</option>
                                    <option value="non" selected>Non</option>
                                </select>
                            </div>
                             <div class="form-group">
                                <label class="form-label">Projet de création/reprise d'entreprise  <span class="required">*</span></label>
                                <div class="form-display" data-field="declareRA"> <?= isEmpty($cerfa->declareRA) ?></div>
                                <select class="form-select" data-input="declareRA" value="<?= $cerfa->declareRA ?>" style="display: none;">
                                    <option value="">__</option>
                                    <option value="oui">Oui</option>
                                    <option value="non" selected>Non</option>
                                </select>
                            </div>
                        </div>

                       
                    </div>

                    <!-- Adresse -->
                    <div class="form-card">
                        <h3 class="card-title">Adresse de l'apprenti(e)</h3>
                        
                        <div class="form-row triple">
                            <div class="form-group">
                                <label class="form-label">N°</label>
                                <div class="form-display" data-field="rueA"><?= isEmpty($cerfa->rueA) ?></div>
                                <input type="text" class="form-input" data-input="rueA"   value="<?= $cerfa->rueA ?>" style="display: none;">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Voie <span class="required">*</span></label>
                                <div class="form-display" data-field="voieA"><?= isEmpty($cerfa->voieA) ?></div>
                                <input type="text" class="form-input" data-input="voieA" value="<?= $cerfa->voieA ?>" style="display: none;" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Complément</label>
                                <div class="form-display" data-field="complementA"><?= isEmpty($cerfa->complementA) ?></div>
                                <input type="text" class="form-input" data-input="complementA" value="<?= $cerfa->complementA ?>" style="display: none;">
                            </div>
                        </div>

                        <div class="form-row triple">
                            <div class="form-group">
                                <label class="form-label">Code postal <span class="required">*</span></label>
                                <div class="form-display" data-field="postalA"><?= isEmpty($cerfa->postalA) ?></div>
                                <input type="text" class="form-input" data-input="postalA"  style="display: none;" value="<?= $cerfa->postalA ?>" pattern="[0-9]{5}" minlength="5" maxlength="5" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Commune <span class="required">*</span></label>
                                <div class="form-display" data-field="communeA"><?= isEmpty($cerfa->communeA) ?></div>
                                <input type="text" class="form-input" data-input="communeA"  style="display: none;" value="<?= $cerfa->communeA ?>" required>
                            </div>
                             <div class="form-group">
                                <label class="form-label">Téléphone <span class="required">*</span></label>
                                <div class="form-display" data-field="numeroA"><?= isEmpty($cerfa->numeroA) ?></div>
                                <input type="tel" class="form-input" data-input="numeroA" value="<?= $cerfa->numeroA ?>" style="display: none;" pattern="[0-9]{10}"   minlength="10" maxlength="10" required>
                            </div>
                        </div>

                       
                    </div>

                    <!-- Représentant légal -->
                    <div class="form-card">
                        <h3 class="card-title">Représentant légal (mineur non émancipé)</h3>
                        
                        <div class="form-row triple">
                            <div class="form-group">
                                <label class="form-label">Nom de naissance</label>
                                <div class="form-display" data-field="nomR"><?= isEmpty($cerfa->nomR) ?></div>
                                <input type="text" class="form-input" data-input="nomR" value="<?= $cerfa->nomR ?>" style="display: none;">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Prénom</label>
                                <div class="form-display" data-field="prenomR"><?= isEmpty($cerfa->prenomR) ?></div>
                                <input type="text" class="form-input" data-input="prenomR" value="<?= $cerfa->prenomR ?>" style="display: none;">
                            </div>

                             <div class="form-group">
                                <label class="form-label">Courriel</label>
                                <div class="form-display" data-field="emailR"><?= isEmpty($cerfa->emailR) ?></div>
                                <input type="email" class="form-input" data-input="emailR" value="<?= $cerfa->emailR ?>" style="display: none;">
                            </div>
                        </div>

                        

                        <h4 style="margin: 20px 0 15px 0; color: #495057;">Adresse du représentant légal</h4>
                        
                        <div class="form-row triple">
                            <div class="form-group">
                                <label class="form-label">N°</label>
                                <div class="form-display" data-field="rueR"><?= isEmpty($cerfa->rueR) ?></div>
                                <input type="text" class="form-input" data-input="rueR" value="<?= $cerfa->rueR ?>"  style="display: none;">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Voie</label>
                                <div class="form-display" data-field="voieR"><?= isEmpty($cerfa->voieR) ?></div>
                                <input type="text" class="form-input" data-input="voieR" value="<?= $cerfa->voieR ?>" style="display: none;">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Complément</label>
                                <div class="form-display" data-field="complementR"><?= isEmpty($cerfa->complementR) ?></div>
                                <input type="text" class="form-input" data-input="complementR" value="<?= $cerfa->complementR?>" style="display: none;">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Code postal</label>
                                <div class="form-display" data-field="postalR"><?= isEmpty($cerfa->postalR) ?></div>
                                <input type="text" class="form-input" data-input="postalR" value="<?= $cerfa->postalR ?>" style="display: none;" pattern="[0-9]{5}" minlength="5" maxlength="5">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Commune</label>
                                <div class="form-display" data-field="communeR"><?= isEmpty($cerfa->communeR) ?></div>
                                <input type="text" class="form-input" data-input="communeR" value="<?= $cerfa->communeR ?>" style="display: none;">
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        <!-- Onglet formation -->
        <div id="tab-formation" class="tab-content ">
            <div class="form-section">
                <h2 class="section-title">Formation</h2>
                
               <div class="form-card">
                     <h3 class="card-title">Informations CFA</h3>
                
                 
                
                    <div class="form-row double">
                        <div class="form-group">
                            <label class="form-label">Dénomination du CFA responsable </label>
                            <div class="form-display" data-field="nomF"><?= isEmpty($formation->nomF) ?></div>
                            <input type="text" class="form-input" data-input="nomF" value="<?= isEmptySave($formation->nomF) ?>" 
                             style="display: none; <?= empty($formation->nomF) ? 'color: red;' : '' ?>"   disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Numero UAI du CFA </label>
                            <div class="form-display" data-field="numeroF"><?= isEmpty($formation->numeroF) ?></div>
                            <input type="text" class="form-input" data-input="numeroF" value="<?= isEmptySave($formation->numeroF) ?>" 
                             style="display: none; <?= empty($formation->numeroF) ? 'color: red;' : '' ?>"  disabled>
                        </div>
                    </div>

                    <div class="form-row triple">
                        <div class="form-group">
                            <label class="form-label">CFA d'entreprise</label>
                            <div class="form-display" data-field="entrepriseF"><?= isEmpty($formation->entrepriseF) ?></div>
                            <select class="form-select" data-input="entrepriseF" value="<?= isEmptySave($formation->entrepriseF) ?>" 
                              style="display: none; <?= empty($formation->entrepriseF) ? 'color: red;' : '' ?>" disabled>
                                <option value="">__</option>
                                <option value="oui">Oui</option>
                                <option value="non">Non</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Numero SIRET CFA </label>
                            <div class="form-display" data-field="siretF"><?= isEmpty($formation->siretF)?></div>
                            <input type="text" class="form-input" data-input="siretF" value="<?= isEmptySave($formation->siretF) ?>" 
                              style="display: none; <?= empty($formation->siretF) ? 'color: red;' : '' ?>" pattern="[0-9]{14}" minlength="14" maxlength="14"  disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Le CFA responsable est le lieu de formation principal</label>
                            <div class="form-display" data-field="responsableF"><?= isEmpty($formation->responsableF)?></div>
                            <select class="form-select" data-input="responsableF" value="<?= isEmptySave($formation->responsableF) ?>" 
                             style="display: none; <?= empty($formation->responsableF) ? 'color: red;' : '' ?>" disabled>
                                <option value="">__</option>
                                <option value="oui">Oui</option>
                                <option value="non">Non</option>
                            </select>
                        </div>
                    </div>
               </div>

               <!-- diplôme -->
                <div class="form-card">
                    <h3 class="card-title">Diplôme</h3>
                    
                    <div class="form-row double">
                         <div class="form-group">
                            <label class="form-label">Intitulé précis du titre visé par l’apprenti</label>
                            <div class="form-display" data-field="intituleF"><?= isEmpty($formation->intituleF) ?></div>
                            <input type="text" class="form-input" data-input="intituleF" value="<?= isEmptySave($formation->intituleF) ?>" 
                             style="display: none; <?= empty($formation->intituleF) ? 'color: red;' : '' ?>"  disabled>
                        </div>
                       <div class="form-group">
                            <label class="form-label">Diplôme  ou  titre visé par l’apprenti</label>
                            <div class="form-display" data-field="diplomeF"> <?= isEmpty($formation->diplomeF) ?></div>
                            <select class="form-select" data-input="diplomeF" value="<?= isEmptySave($formation->diplomeF) ?>" 
                              style="display: none; <?= empty($formation->diplomeF) ? 'color: red;' : '' ?>" disabled>
                                   <option value="">__</option>
                                   <optgroup label="Diplôme ou titre de niveau bac +5 et plus">
                                        <option value="80">80 : Doctorat</option>
                                        <option value="71">71 : Master professionnel/DESS</option>
                                        <option value="72">72 : Master recherche/DEA</option>
                                        <option value="73">73 : Master indifférencié</option>
                                        <option value="74">74 : Diplôme d'ingénieur, diplôme d'école de commerce</option>
                                        <option value="79">79 : Autre diplôme ou titre de niveau bac+5 ou plus</option>
                                    </optgroup>
                                    <optgroup label="Diplôme ou titre de niveau bac +3 et 4">
                                        <option value="61">61 : 1ère année de Master</option>
                                        <option value="62">62 : Licence professionnelle</option>
                                        <option value="63">63 : Licence générale</option>
                                        <option value="69">69 : Autre diplôme ou titre de niveau bac +3 ou 4</option>
                                    </optgroup>
                                    <optgroup label="Diplôme ou titre de niveau bac +2">
                                        <option value="54">54 : Brevet de Technicien Supérieur</option>
                                        <option value="55">55 : Diplôme Universitaire de technologie</option>
                                        <option value="58">58 : Autre diplôme ou titre de niveau bac+2</option>
                                    </optgroup>
                                    <optgroup label="Diplôme ou titre de niveau bac">
                                        <option value="41">41 : Baccalauréat professionnel</option>
                                        <option value="42">42 : Baccalauréat général</option>
                                        <option value="43">43 : Baccalauréat technologique</option>
                                        <option value="49">49 : Autre diplôme ou titre de niveau bac</option>
                                    </optgroup>
                                    <optgroup label="Diplôme ou titre de niveau CAP/BEP">
                                        <option value="33">33 : CAP</option>
                                        <option value="34">34 : BEP</option>
                                        <option value="35">35 : Mention complémentaire</option>
                                        <option value="38">38 : Autre diplôme ou titre de niveau CAP/BEP</option>
                                    </optgroup>
                                    <optgroup label="Aucun diplôme ni titre">
                                        <option value="25">25 : Diplôme national du Brevet (DNB)</option>
                                        <option value="26">26 : Certificat de formation générale</option>
                                        <option value="13">13 : Aucun diplôme ni titre professionnel</option>
                                    
                                    </optgroup>
                               
                            </select>
                        </div>
                    </div>

                    <div class="form-row triple">
                         <div class="form-group">
                            <label class="form-label">Code RNCP</label>
                            <div class="form-display" data-field="rnF"><?= isEmpty($formation->rnF) ?></div>
                            <input type="text" class="form-input" data-input="rnF" value="<?= isEmptySave($formation->rnF) ?>" 
                             style="display: none; <?= empty($formation->rnF) ? 'color: red;' : '' ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Code du diplôme</label>
                            <div class="form-display" data-field="codeF"><?= isEmpty($formation->codeF) ?></div>
                            <input type="text" class="form-input" data-input="codeF" value="<?= isEmptySave($formation->codeF) ?>" 
                             style="display: none; <?= empty($formation->codeF) ? 'color: red;' : '' ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Prix formation</label>
                            <div class="form-display" data-field="prix"><?= isEmpty($formation->prix) ?></div>
                            <input type="text" class="form-input" data-input="prix" value="<?= isEmptySave($formation->prix) ?>" 
                             style="display: none; <?= empty($formation->prix) ? 'color: red;' : '' ?>" disabled>
                        </div>
                    </div>
                </div>

                <!-- Adresse du CFA responsable -->
                <div class="form-card">
                    <h3 class="card-title">Adresse du CFA responsable</h3>
                    
                    <div class="form-row triple">
                        <div class="form-group">
                            <label class="form-label">Rue</label>
                            <div class="form-display" data-field="rueF"><?= isEmpty($formation->rueF) ?></div>
                            <input type="text" class="form-input" data-input="rueF" value="<?= isEmptySave($formation->rueF) ?>" 
                             style="display: none; <?= empty($formation->rueF) ? 'color: red;' : '' ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Voie</label>
                            <div class="form-display" data-field="voieF"><?= isEmpty($formation->voieF) ?></div>
                            <input type="text" class="form-input" data-input="voieF" value="<?= isEmptySave($formation->voieF) ?>" 
                             style="display: none; <?= empty($formation->voieF) ? 'color: red;' : '' ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Complément d'adresse</label>
                            <div class="form-display" data-field="complementF"><?= isEmpty($formation->complementF) ?></div>
                            <input type="text" class="form-input" data-input="complementF" value="<?= isEmptySave($formation->complementF) ?>" 
                             style="display: none; <?= empty($formation->complementF) ? 'color: red;' : '' ?>" disabled>
                        </div>
                    </div>

                    <div class="form-row triple">
                        <div class="form-group">
                            <label class="form-label">Code postal</label>
                            <div class="form-display" data-field="postalF"><?= isEmpty($formation->postalF) ?></div>
                            <input type="text" class="form-input" data-input="postalF" value="<?= isEmptySave($formation->postalF) ?>" 
                             style="display: none; <?= empty($formation->postalF) ? 'color: red;' : '' ?>" pattern="[0-9]{5}" minlength="5" maxlength="5" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Commune</label>
                            <div class="form-display" data-field="communeF"><?= isEmpty($formation->communeF) ?></div>
                            <input type="text" class="form-input" data-input="communeF" value="<?= isEmptySave($formation->communeF) ?>" 
                             style="display: none; <?= empty($formation->communeF) ? 'color: red;' : '' ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <div class="form-display" data-field="emailF"><?= isEmpty($formation->emailF) ?></div>
                            <input type="email" class="form-input" data-input="emailF" value="<?= isEmptySave($formation->emailF) ?>"
                             style="display: none; <?= empty($formation->emailF) ? 'color: red;' : '' ?>" disabled>
                        </div>
                    </div>
                </div>

                <!-- Organisation de la formation en CFA -->
                <div class="form-card">
                    <h3 class="card-title">Organisation de la formation en CFA</h3>
                    
                    <div class="form-row triple">
                        <div class="form-group">
                            <label class="form-label">Date prévue de fin des épreuves ou examens</label>
                            <div class="form-display" data-field="prevuO"><?= empty($formation->prevuO)? isEmpty($formation->prevuO) :  date('d/m/Y', strtotime($formation->prevuO )) ?></div>
                            <input type="text" class="form-input" data-input="prevuO" value="<?=  
                            empty($formation->prevuO)? isEmptySave($formation->prevuO) :  date('d/m/Y', strtotime($formation->prevuO )) ?>" 
                             style="display: none; <?= empty($formation->prevuO) ? 'color: red;' : '' ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date de début de formation</label>
                            <div class="form-display" data-field="debutO"><?= empty($formation->debutO)?  isEmpty($formation->debutO) : date('d/m/Y', strtotime($formation->debutO)) ?></div>
                            <input type="text" class="form-input" data-input="debutO" value="<?= 
                             empty($formation->debutO)?  isEmptySave($formation->debutO) : date('d/m/Y', strtotime($formation->debutO))  ?>" 
                              style="display: none; <?= empty($formation->debutO) ? 'color: red;' : '' ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Durée de la formation (heures)</label>
                            <div class="form-display" data-field="dureO"><?= isEmpty($formation->dureO) ?></div>
                            <input type="number" class="form-input" data-input="dureO" value="<?= isEmptySave($formation->dureO) ?>" 
                             style="display: none; <?= empty($formation->dureO) ? 'color: red;' : '' ?>" disabled>
                        </div>
                    </div>
                </div>

                <!-- Lieu principal de réalisation de la formation -->
                <div class="form-card">
                    <h3 class="card-title">Lieu principal de réalisation de la formation si différent du CFA responsable</h3>
                    
                    <div class="form-row triple">
                        <div class="form-group">
                            <label class="form-label">Dénomination du lieu de formation principal</label>
                            <div class="form-display" data-field="nomO"><?= isEmpty($formation->nomO) ?></div>
                            <input type="text" class="form-input" data-input="nomO" value="<?= isEmptySave($formation->nomO) ?>" 
                             style="display: none; <?= empty($formation->nomO) ? 'color: red;' : '' ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Numero UAI</label>
                            <div class="form-display" data-field="numeroO"><?= isEmpty($formation->numeroO) ?></div>
                            <input type="text" class="form-input" data-input="numeroO" value="<?= isEmptySave($formation->numeroO) ?>" 
                             style="display: none; <?= empty($formation->numeroO) ? 'color: red;' : '' ?>" pattern="[0-9]{7}" minlength="7" maxlength="7"  disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Numero SIRET</label>
                            <div class="form-display" data-field="siretO"><?= isEmpty($formation->siretO) ?></div>
                            <input type="text" class="form-input" data-input="siretO" value="<?= isEmptySave($formation->siretO) ?>" 
                             style="display: none; <?= empty($formation->siretO) ? 'color: red;' : '' ?>" pattern="[0-9]{14}" minlength="14" maxlength="14" disabled>
                        </div>
                    </div>
                </div>

                <!-- Adresse du lieu de formation principal -->
                <div class="form-card">
                    <h3 class="card-title">Adresse du lieu de formation principal</h3>
                    
                    <div class="form-row triple">
                        <div class="form-group">
                            <label class="form-label">Rue</label>
                            <div class="form-display" data-field="rueO"><?= isEmpty($formation->rueO) ?></div>
                            <input type="text" class="form-input" data-input="rueO" value="<?= isEmptySave($formation->rueO) ?>" 
                             style="display: none; <?= empty($formation->rueO) ? 'color: red;' : '' ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Voie</label>
                            <div class="form-display" data-field="voieO"><?= isEmpty($formation->voieO) ?></div>
                            <input type="text" class="form-input" data-input="voieO" value="<?= isEmptySave($formation->voieO) ?>" 
                             style="display: none; <?= empty($formation->voieO) ? 'color: red;' : '' ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Complément d'adresse</label>
                            <div class="form-display" data-field="complementO"><?= isEmpty($formation->complementO) ?></div>
                            <input type="text" class="form-input" data-input="complementO" value="<?= isEmptySave($formation->complementO) ?>" 
                             style="display: none; <?= empty($formation->complementO) ? 'color: red;' : '' ?>" disabled>
                        </div>
                    </div>

                    <div class="form-row double">
                        <div class="form-group">
                            <label class="form-label">Code postal</label>
                            <div class="form-display" data-field="postalO"><?= isEmpty($formation->postalO) ?></div>
                            <input type="text" class="form-input" data-input="postalO" value="<?= isEmptySave($formation->postalO) ?>"
                              style="display: none; <?= empty($formation->postalO) ? 'color: red;' : '' ?>" pattern="[0-9]{5}" minlength="5" maxlength="5" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Commune</label>
                            <div class="form-display" data-field="communeO"><?= isEmpty($formation->communeO) ?></div>
                            <input type="text" class="form-input" data-input="communeO" value="<?= isEmptySave($formation->communeO) ?>" 
                             style="display: none; <?= empty($formation->communeO) ? 'color: red;' : '' ?>"   disabled>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Onglet Contrat -->
        <div id="tab-contract" class="tab-content">
            <div class="form-section">
                <h2 class="section-title">Termes du contrat</h2>
                
                  <!-- Carte 1 : Informations Contractuelles de Base -->
<div class="form-card">
    <h3 class="card-title">Informations Contractuelles</h3>
    
    <div class="form-row triple">
        <div class="form-group">
            <label class="form-label">Mode Contractuel</label>
            <div class="form-display" data-field="modeC"><?= isEmpty($cerfa->modeC) ?></div>
            <?php  if(empty($cerfa->modeC)){ ?>
                   <input type="text"  data-input="modec" value="<?= isEmptySave($cerfa->modeC) ?>" class="form-input"  style="display: none; <?= empty($cerfa->modeC) ? 'color: red;' : '' ?>" 
                    disabled>
            <?php  } else{ ?>
                <select class="form-select" data-input="modeC"   style="display: none;"  disabled>
                    <option value="">______</option>
                    <option value="1" >1 : À durée limitée</option>
                    <option value="2">2 : Dans le cadre d'un CDI</option>
                    <option value="3" >3 : Entreprise de travail temporaire</option>
                    <option value="4" >4 : Activités saisonnières à deux employeurs</option>
            </select>
            <?php  } ?>        
           
        </div>
        
        <div class="form-group">
            <label class="form-label">Machines dangereuses</label>
            <div class="form-display" data-field="travailC"><?= isEmpty($cerfa->travailC) ?></div>
            <?php  if(empty($cerfa->travailC)){ ?>
                   <input type="text"  data-input="travailC" value="<?= isEmptySave($cerfa->travailC) ?>" class="form-input"  
                    style="display: none; <?= empty($cerfa->travailC) ? 'color: red;' : '' ?>" 
                     disabled>
            <?php  } else{ ?>
                 <select  data-input="travailC" class="form-select" style="display: none;" disabled>
                <option value="">__</option>
                <option value="oui" >Oui</option>
                <option value="non" >Non</option>
            </select>
            <?php  } ?>  



        </div>
        
        <div class="form-group">
            <label class="form-label">Type de dérogation</label>
            <div class="form-display" data-field="derogationC"><?= isEmpty($cerfa->derogationC) ?></div>
             <?php  if(empty($cerfa->derogationC)){ ?>
                   <input type="text"  data-input="derogationC" value="<?= isEmptySave($cerfa->derogationC) ?>" class="form-input"  
                    style="display: none; <?= empty($cerfa->derogationC) ? 'color: red;' : '' ?>" 
                     disabled>
            <?php  } else{ ?>
                 <select  data-input="derogationC" class="form-select" style="display: none;"  disabled>
                    <option value="">______________</option>
                    <option value="11" >11 : Age < 16 ans</option>
                    <option value="12">12 : Age > 29 ans</option>
                    <option value="21" >21 : Réduction durée</option>
                    <option value="22" >22 : Allongement durée</option>
                    <option value="50" >50 : Cumul</option>
                    <option value="60">60 : Autre</option>
                </select>
            <?php  } ?> 
        </div>
    </div>

    <div class="form-row triple">
        <div class="form-group">
            <label class="form-label">Numéro contrat précédent</label>
            <div class="form-display" data-field="numeroC"><?= isEmpty($cerfa->numeroC) ?></div>
            <input type="text"  data-input="numeroC" value="<?= isEmptySave($cerfa->numeroC) ?>" class="form-input"
             style="display: none; <?= empty($cerfa->numeroC) ? 'color: red;' : '' ?>"  
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Date de conclusion</label>
            <div class="form-display" data-field="conclusionC"><?= empty($cerfa->conclusionC)? isEmpty($cerfa->conclusionC) : date('d/m/Y', strtotime($cerfa->conclusionC )) ?></div>
            <input type="text" data-input="conclusionC" 
            value="<?= empty($cerfa->conclusionC)? isEmptySave($cerfa->conclusionC) : date('d/m/Y', strtotime($cerfa->conclusionC ))  ?>" class="form-input"
                style="display: none; <?= empty($cerfa->conclusionC) ? 'color: red;' : '' ?>"  disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Date début formation</label>
            <div class="form-display" data-field="debutC"><?=  empty($cerfa->debutC)?isEmpty($cerfa->debutC) : date('d/m/Y', strtotime($cerfa->debutC )) ?></div>
            <input type="text" data-input="debutC" value="<?= empty($cerfa->debutC)?isEmptySave($cerfa->debutC) : date('d/m/Y', strtotime($cerfa->debutC ))  ?>" class="form-input" 
              style="display: none; <?= empty($cerfa->debutC) ? 'color: red;' : '' ?>"
             disabled>
        </div>
    </div>
</div>

<!-- Carte 2 : Dates et Durées -->
<div class="form-card">
    <h3 class="card-title">Dates et Durées</h3>
    
    <div class="form-row triple">
        <div class="form-group">
            <label class="form-label">Date de fin</label>
            <div class="form-display" data-field="finC"><?= empty($cerfa->finC)? isEmpty($cerfa->finC) : date('d/m/Y', strtotime($cerfa->finC ))   ?></div>
            <input type="text" data-input="finC"
             value="<?= empty($cerfa->finC)? isEmptySave($cerfa->finC) : date('d/m/Y', strtotime($cerfa->finC ))  ?>" class="form-input" 
             style="display: none; <?= empty($cerfa->finC) ? 'color: red;' : '' ?>" disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Date d'effet avenant</label>
            <div class="form-display" data-field="avenantC"><?=  empty($cerfa->avenantC)?isEmpty($cerfa->avenantC) : date('d/m/Y', strtotime($cerfa->avenantC )) ?></div>
            <input type="text" data-input="avenantC" 
            value="<?= empty($cerfa->avenantC)?isEmptySave($cerfa->avenantC) : date('d/m/Y', strtotime($cerfa->avenantC ))?>" class="form-input" 
                style="display: none; <?= empty($cerfa->avenantC) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Date d'exécution</label>
            <div class="form-display" data-field="executionC"><?= empty($cerfa->executionC)?  isEmpty($cerfa->executionC) : date('d/m/Y', strtotime($cerfa->executionC)) ?></div>
            <input type="text" data-input="executionC"
             value="<?= empty($cerfa->executionC)?  isEmptySave($cerfa->executionC) : date('d/m/Y', strtotime($cerfa->executionC ))?>" class="form-input" 
                style="display: none; <?= empty($cerfa->executionC) ? 'color: red;' : '' ?>"
             disabled>
        </div>
    </div>

    <div class="form-row triple">
        <div class="form-group">
            <label class="form-label">Durée hebdo (heures)</label>
            <div class="form-display" data-field="dureC"><?= isEmpty($cerfa->dureC) ?></div>
            <input type="text" data-input="dureC" value="<?= isEmptySave($cerfa->dureC) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->dureC) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Durée hebdo (minutes)</label>
            <div class="form-display" data-field="dureCM"><?= isEmpty($cerfa->dureCM) ?></div>
            <input type="text" data-input="dureCM" value="<?= isEmptySave($cerfa->dureCM) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->dureCM) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Type de contrat</label>
            <div class="form-display" data-field="typeC"><?= isEmpty($cerfa->typeC) ?></div>

               <?php  if(empty($cerfa->typeC)){ ?>
                   <input type="text"  data-input="typeC" value="<?= isEmptySave($cerfa->typeC) ?>" class="form-input"  
                    style="display: none; <?= empty($cerfa->typeC) ? 'color: red;' : '' ?>" 
                     disabled>
            <?php  } else{ ?>
                 <select  data-input="typeC" class="form-select" style="display: none;"  disabled>
                   <option value="">__________</option>
                        <optgroup label="Contrat initial">
                            <option value="11" >Premier contrat</option>
                        </optgroup>
                        <optgroup label="Succession">
                            <option value="21" >Nouveau même employeur</option>
                            <option value="22" >Nouveau autre employeur</option>
                            <option value="23" >>Contrat rompu</option>
                        </optgroup>
                        <optgroup label="Avenant">
                            <option value="31" >Modif situation</option>
                            <option value="32">Changement employeur</option>
                            <option value="33" >Prolongation échec</option>
                            <option value="34" >Prolongation handicap</option>
                            <option value="35" >Changement diplôme</option>
                            <option value="36" >Autres changements</option>
                            <option value="37" >Changement lieu</option>
                        </optgroup>
                </select>
            <?php  } ?>
        </div>
    </div>
</div>

<!-- Carte 3 : Rémunération Année 1 -->
<div class="form-card">
    <h3 class="card-title">Rémunération - 1ère Année</h3>
    
    <div class="form-row quadruple">
        <div class="form-group">
            <label class="form-label">Date début</label>
            <div class="form-display" data-field="rdC"><?=  empty($cerfa->rdC)?  isEmpty($cerfa->rdC) : date('d/m/Y', strtotime($cerfa->rdC ))  ?></div>
            <input type="text" data-input="rdC" value="<?= empty($cerfa->rdC)?  isEmptySave($cerfa->rdC) : date('d/m/Y', strtotime($cerfa->rdC )) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->rdC) ? 'color: red;' : '' ?>"
              disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Date fin</label>
            <div class="form-display" data-field="raC"><?= empty($cerfa->raC)?isEmpty($cerfa->raC) : date('d/m/Y', strtotime($cerfa->raC )) ?></div>
            <input type="text" data-input="raC" value="<?=empty($cerfa->raC)?isEmptySave($cerfa->raC) : date('d/m/Y', strtotime($cerfa->raC )) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->raC) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Pourcentage</label>
            <div class="form-display" data-field="rpC"><?= isEmpty($cerfa->rpC) ?></div>
            <input type="text" data-input="rpC" value="<?= isEmptySave($cerfa->rpC) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->rpC) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Base calcul</label>
            <div class="form-display" data-field="rsC"><?= isEmpty($cerfa->rsC) ?></div>
              <?php  if(empty($cerfa->rsC)){ ?>
                   <input type="text"  data-input="rsC2" value="<?= isEmptySave($cerfa->rsC) ?>" class="form-input"  
                    style="display: none; <?= empty($cerfa->rsC) ? 'color: red;' : '' ?>" 
                     disabled>
            <?php  } else{ ?>
                 <select  data-input="rsC" class="form-select" style="display: none;"  disabled>
                  <option value="">____________</option>
                    <option value="SMIC">SMIC</option>
                    <option value="SMC" >SMC</option>
                </select>
            <?php  } ?>
        </div>
    </div>
</div>

<!-- Carte 4 : Rémunération Année 2 -->
<div class="form-card">
    <h3 class="card-title">Rémunération - 2ème Année</h3>
    
    <div class="form-row quadruple">
        <div class="form-group">
            <label class="form-label">Date début</label>
            <div class="form-display" data-field="rdC1"><?= empty($cerfa->rdC1)?  isEmpty($cerfa->rdC1) : date('d/m/Y', strtotime($cerfa->rdC1 )) ?></div>
            <input type="text" data-input="rdC1" value="<?= empty($cerfa->rdC1)?  isEmptySave($cerfa->rdC1) : date('d/m/Y', strtotime($cerfa->rdC1 )) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->rdC1) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Date fin</label>
            <div class="form-display" data-field="raC1"><?= empty($cerfa->raC1)?isEmpty($cerfa->raC1) : date('d/m/Y', strtotime($cerfa->raC1 )) ?></div>
            <input type="text" data-input="raC1" value="<?= empty($cerfa->raC1)?isEmptySave($cerfa->raC1) : date('d/m/Y', strtotime($cerfa->raC1 ))  ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->raC1) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Pourcentage</label>
            <div class="form-display" data-field="rpC1"><?= isEmpty($cerfa->rpC1) ?></div>
            <input type="text" data-input="rpC1" value="<?= isEmptySave($cerfa->rpC1) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->rpC1) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Base calcul</label>
            <div class="form-display" data-field="rsC1"><?= isEmpty($cerfa->rsC1) ?></div>
              <?php  if(empty($cerfa->rsC1)){ ?>
                   <input type="text"  data-input="rsC1" value="<?= isEmptySave($cerfa->rsC1) ?>" class="form-input"  
                    style="display: none; <?= empty($cerfa->rsC1) ? 'color: red;' : '' ?>" 
                     disabled>
            <?php  } else{ ?>
                 <select  data-input="rsC1" class="form-select" style="display: none;"  disabled>
                  <option value="">____________</option>
                    <option value="SMIC">SMIC</option>
                    <option value="SMC" >SMC</option>
                </select>
            <?php  } ?>
        </div>
    </div>
</div>

<!-- Carte 5 : Rémunération Année 3 -->
<div class="form-card">
    <h3 class="card-title">Rémunération - 3ème Année</h3>
    
    <div class="form-row quadruple">
        <div class="form-group">
            <label class="form-label">Date début</label>
            <div class="form-display" data-field="rdC2"><?= empty($cerfa->rdC2)?  isEmpty($cerfa->rdC2) : date('d/m/Y', strtotime($cerfa->rdC2 )) ?></div>
            <input type="text" data-input="rdC2" value="<?= empty($cerfa->rdC2)?  isEmptySave($cerfa->rdC2) : date('d/m/Y', strtotime($cerfa->rdC2 )) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->rdC2) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Date fin</label>
            <div class="form-display" data-field="raC2"><?= empty($cerfa->raC2)?isEmpty($cerfa->raC2) : date('d/m/Y', strtotime($cerfa->raC2 )) ?></div>
            <input type="text" data-input="raC2" value="<?= empty($cerfa->raC2)?isEmptySave($cerfa->raC2) : date('d/m/Y', strtotime($cerfa->raC2 )) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->raC2) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Pourcentage</label>
            <div class="form-display" data-field="rpC2"><?= isEmpty($cerfa->rpC2) ?></div>
            <input type="text" data-input="rpC2" value="<?= isEmptySave($cerfa->rpC2) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->rpC2) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Base calcul</label>
            <div class="form-display" data-field="rsC2"><?= isEmpty($cerfa->rsC2) ?></div>
              <?php  if(empty($cerfa->rsC2)){ ?>
                   <input type="text"  data-input="rsC2" value="<?= isEmptySave($cerfa->rsC2) ?>" class="form-input"  
                    style="display: none; <?= empty($cerfa->rsC2) ? 'color: red;' : '' ?>" 
                     disabled>
            <?php  } else{ ?>
                 <select  data-input="rsC2" class="form-select" style="display: none;"  disabled>
                  <option value="">____________</option>
                    <option value="SMIC">SMIC</option>
                    <option value="SMC" >SMC</option>
                </select>
            <?php  } ?>
        </div>
    </div>
</div>

<!-- Carte 6 : Avantages et Compléments -->
<div class="form-card">
    <h3 class="card-title">Avantages et Compléments</h3>
    
    <div class="form-row quadruple">
        <div class="form-group">
            <label class="form-label">Salaire brut</label>
            <div class="form-display" data-field="salaireC"><?= isEmpty($cerfa->salaireC) ?></div>
            <input type="text" data-input="salaireC" value="<?= isEmptySave($cerfa->salaireC) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->salaireC) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Caisse retraite</label>
            <div class="form-display" data-field="caisseC"><?= isEmpty($cerfa->caisseC) ?></div>
            <input type="text" data-input="caisseC" value="<?= isEmptySave($cerfa->caisseC) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->caisseC) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Logement (€/mois)</label>
            <div class="form-display" data-field="logementC"><?= isEmpty($cerfa->logementC) ?></div>
            <input type="text" data-input="logementC" value="<?= isEmptySave($cerfa->logementC) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->logementC) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Nourriture (€/repas)</label>
            <div class="form-display" data-field="avantageC"><?= isEmpty($cerfa->avantageC) ?></div>
            <input type="text" data-input="avantageC" value="<?= isEmptySave($cerfa->avantageC) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->avantageC) ? 'color: red;' : '' ?>"
             disabled>
        </div>
    </div>

    <div class="form-row double">
        <div class="form-group">
            <label class="form-label">Autres avantages</label>
            <div class="form-display" data-field="autreC"><?= isEmpty($cerfa->autreC) ?></div>

              <?php  if(empty($cerfa->autreC)){ ?>
                   <input type="text"  data-input="autreC" value="<?= isEmptySave($cerfa->autreC) ?>" class="form-input"  
                    style="display: none; <?= empty($cerfa->autreC) ? 'color: red;' : '' ?>" 
                     disabled>
            <?php  } else{ ?>
                 <select  data-input="autreC" class="form-select" style="display: none;"  disabled>
                   <option value="">__</option>
                    <option value="oui" >Oui</option>
                    <option value="non" >Non</option>
                </select>
            <?php  } ?>
        </div>
        
        <div class="form-group">
            <label class="form-label">Lieu de signature</label>
            <div class="form-display" data-field="lieuO"><?= isEmpty($cerfa->lieuO) ?></div>
            <input type="text" data-input="lieuO" value="<?= isEmptySave($cerfa->lieuO) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->lieuO) ? 'color: red;' : '' ?>"
             disabled>
            
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Type entreprise</label>
            <div class="form-display" data-field="priveO"><?= empty($cerfa->priveO) ? isEmpty($cerfa->priveO) : $cerfa->priveO ?></div>

             <?php  if(empty($cerfa->priveO)){ ?>
                   <input type="text"  data-input="priveO" value="<?= isEmptySave($cerfa->priveO) ?>" class="form-input"  
                    style="display: none; <?= empty($cerfa->priveO) ? 'color: red;' : '' ?>" 
                     disabled>
            <?php  } else{ ?>
                 <select  data-input="priveO" class="form-select" style="display: none;"  disabled>
                    <option value="">__</option>
                    <option value="oui" >Privé</option>
                    <option value="non" >Public</option>
                </select>
            <?php  } ?>
        </div>
    </div>
</div>

<!-- Carte 7 : Maître d'apprentissage 1 -->
<div class="form-card">
    <h3 class="card-title">Maître d'apprentissage n°1</h3>
    
    <div class="form-row quadruple">
        <div class="form-group">
            <label class="form-label">Nom</label>
            <div class="form-display" data-field="nomM"><?= isEmpty($cerfa->nomM) ?></div>
            <input type="text" data-input="nomM" value="<?= isEmptySave($cerfa->nomM) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->nomM) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Prénom</label>
            <div class="form-display" data-field="prenomM"><?= isEmpty($cerfa->prenomM) ?></div>
            <input type="text" data-input="prenomM" value="<?= isEmptySave($cerfa->prenomM) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->prenomM) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Date naissance</label>
            <div class="form-display" data-field="naissanceM"><?= empty($cerfa->naissanceM)?isEmpty($cerfa->naissanceM) : date('d/m/Y', strtotime($cerfa->naissanceM ))  ?></div>
            <input type="text" data-input="naissanceM" value="<?= empty($cerfa->naissanceM)?isEmptySave($cerfa->naissanceM) : date('d/m/Y', strtotime($cerfa->naissanceM ))  ?>" class="form-input" 
            style="display: none; <?= empty($cerfa->naissanceM) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">NIR</label>
            <div class="form-display" data-field="securiteM"><?= isEmpty($cerfa->securiteM) ?></div>
            <input type="text" data-input="securiteM" value="<?= isEmptySave($cerfa->securiteM) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->securiteM) ? 'color: red;' : '' ?>" pattern="[0-9]{15}" minlength="15" maxlength="15"
             disabled>
        </div>
    </div>

    <div class="form-row quadruple">
        <div class="form-group">
            <label class="form-label">Email</label>
            <div class="form-display" data-field="emailM"><?= isEmpty($cerfa->emailM) ?></div>
            <input type="email" data-input="emailM" value="<?= isEmptySave($cerfa->emailM) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->emailM) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Emploi</label>
            <div class="form-display" data-field="emploiM"><?= isEmpty($cerfa->emploiM) ?></div>
            <input type="text" data-input="emploiM" value="<?= isEmptySave($cerfa->emploiM) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->emploiM) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Diplôme</label>
            <div class="form-display" data-field="diplomeM"><?= isEmpty($cerfa->diplomeM) ?></div>
            <input type="text" data-input="diplomeM" value="<?= isEmptySave($cerfa->diplomeM) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->diplomeM) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Niveau diplôme</label>
            <div class="form-display" data-field="niveauM"><?= isEmpty($cerfa->niveauM) ?></div>
             <?php  if(empty($cerfa->niveauM)){ ?>
                   <input type="text"  data-input="niveauM" value="<?= isEmptySave($cerfa->niveauM) ?>" class="form-input"  
                    style="display: none; <?= empty($cerfa->niveauM) ? 'color: red;' : '' ?>" 
                     disabled>
            <?php  } else{ ?>
                 <select  data-input="niveauM" class="form-select" style="display: none;"  disabled>
                     <option value="">________</option>
                    <option value="1" >CAP, BEP</option>
                    <option value="2" >Baccalauréat</option>
                    <option value="3" >DEUG, BTS, DUT</option>
                    <option value="4" >Licence, Maîtrise</option>
                    <option value="5" >Master, Ingénieur</option>
                    <option value="6" >Doctorat</option>
                </select>
            <?php  } ?>
        </div>
    </div>
</div>

<!-- Carte 8 : Maître d'apprentissage 2 -->
<div class="form-card">
    <h3 class="card-title">Maître d'apprentissage n°2</h3>
    
    <div class="form-row quadruple">
        <div class="form-group">
            <label class="form-label">Nom</label>
            <div class="form-display" data-field="nomM1"><?= isEmpty($cerfa->nomM1) ?></div>
            <input type="text" data-input="nomM1" value="<?= isEmptySave($cerfa->nomM1) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->nomM1) ? 'color: red;' : '' ?>"
            disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Prénom</label>
            <div class="form-display" data-field="prenomM1"><?= isEmpty($cerfa->prenomM1) ?></div>
            <input type="text" data-input="prenomM1" value="<?= isEmptySave($cerfa->prenomM1) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->prenomM1) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Date naissance</label>
            <div class="form-display" data-field="naissanceM1"><?= empty($cerfa->naissanceM1)?isEmpty($cerfa->naissanceM1) : date('d/m/Y', strtotime($cerfa->naissanceM1 ))  ?></div>
            <input type="text" data-input="naissanceM1" value="<?= empty($cerfa->naissanceM1)?isEmptySave($cerfa->naissanceM1) : date('d/m/Y', strtotime($cerfa->naissanceM1 ))  ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->naissanceM1) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">NIR</label>
            <div class="form-display" data-field="securiteM1"><?= isEmpty($cerfa->securiteM1) ?></div>
            <input type="text" data-input="securiteM1" value="<?= isEmptySave($cerfa->securiteM1) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->securiteM1) ? 'color: red;' : '' ?>" pattern="[0-9]{15}" minlength="15" maxlength="15"
             disabled>
        </div>
    </div>

    <div class="form-row quadruple">
        <div class="form-group">
            <label class="form-label">Email</label>
            <div class="form-display" data-field="emailM1"><?= isEmpty($cerfa->emailM1) ?></div>
            <input type="email" data-input="emailM1" value="<?= isEmptySave($cerfa->emailM1) ?>" class="form-input"
                style="display: none; <?= empty($cerfa->emailM1) ? 'color: red;' : '' ?>" 
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Emploi</label>
            <div class="form-display" data-field="emploiM1"><?= isEmpty($cerfa->emploiM1) ?></div>
            <input type="text" data-input="emploiM1" value="<?= isEmptySave($cerfa->emploiM1) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->emploiM1) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Diplôme</label>
            <div class="form-display" data-field="diplomeM1"><?= isEmpty($cerfa->diplomeM1) ?></div>
            <input type="text" data-input="diplomeM1" value="<?= isEmptySave($cerfa->diplomeM1) ?>" class="form-input" 
                style="display: none; <?= empty($cerfa->diplomeM1) ? 'color: red;' : '' ?>"
             disabled>
        </div>
        
        <div class="form-group">
            <label class="form-label">Niveau diplôme</label>
            <div class="form-display" data-field="niveauM1"><?= isEmpty($cerfa->niveauM1) ?></div>
            
            <?php  if(empty($cerfa->niveauM1)){ ?>
                   <input type="text"  data-input="niveauM1" value="<?= isEmptySave($cerfa->niveauM1) ?>" class="form-input"  
                    style="display: none; <?= empty($cerfa->niveauM1) ? 'color: red;' : '' ?>" 
                     disabled>
            <?php  } else{ ?>
                 <select  data-input="niveauM1" class="form-select" style="display: none;"  disabled>
                     <option value="">________</option>
                    <option value="1" >CAP, BEP</option>
                    <option value="2" >Baccalauréat</option>
                    <option value="3" >DEUG, BTS, DUT</option>
                    <option value="4" >Licence, Maîtrise</option>
                    <option value="5" >Master, Ingénieur</option>
                    <option value="6" >Doctorat</option>
                </select>
            <?php  } ?>


        </div>
    </div>
</div>
            </div>
        </div>

        <!-- Onglet Entreprise -->
        <div id="tab-company" class="tab-content">
            <div class="form-section">
                <h2 class="section-title">Entreprise</h2>
                
              <div class="form-card">
                    <h3 class="card-title">Informations Employeur</h3>
                    
                    <div class="form-row triple">
                        <div class="form-group">
                            <label class="form-label">Dénomination</label>
                            <div class="form-display" data-field="nomE"><?= isEmpty($entreprise->nomE) ?></div>
                            <input type="text" class="form-input" data-input="nomE" value="<?= isEmptySave($entreprise->nomE) ?>" 
                            style="display: none; <?= empty($entreprise->nomE) ? 'color: red;' : '' ?>"  disabled>

                          
                        </div>
                        <div class="form-group">
                            <label class="form-label">Type d'employeur</label>
                            <div class="form-display" data-field="typeE"><?= isEmpty($entreprise->typeE) ?></div>

                            <?php  if(empty($entreprise->typeE)){ ?>
                                    <input type="text"  data-input="typeE" value="<?= isEmptySave($entreprise->typeE) ?>" class="form-input"  
                                    style="display: none; <?= empty($entreprise->typeE) ? 'color: red;' : '' ?>" 
                                     disabled>
                            <?php  } else{ ?>
                                   <select class="form-select" data-input="typeE" 
                                    style="display: none; " 
                                    value="<?=$entreprise->typeE ?>"   disabled>
                                        <optgroup label="Privé">
                                                <option value="11">11 :Entreprise inscrite au répertoire des métiers ou au registre des entreprises pour l’Alsace-Moselle</option>
                                                <option value="12">12 :Entreprise inscrite uniquement au registre du commerce et des sociétés</option>
                                                <option value="13">13 :Entreprises dont les salariés relèvent de la mutualité sociale agricole</option>
                                                <option value="14">14 :Profession libérale</option>
                                                <option value="15">15 :Association</option>
                                                <option value="16">16 :Autre employeur privé</option>
                                            </optgroup>
                                            <optgroup label="Public">
                                                <option value="21">21 :Service de l’Etat (administrations centrales et leurs services déconcentrés de la fonction publique d’Etat)</option>
                                                <option value="22">22 :Commune</option>
                                                <option value="23">23 :Département</option>
                                                <option value="24">24 :Région</option>
                                                <option value="25">25 :Etablissement public hospitalier</option>
                                                <option value="26">26 :Etablissement public local d’enseignement</option>
                                                <option value="27">27 :Etablissement public administratif de l’Etat</option>
                                                <option value="28">28 :Etablissement public administratif local (y compris établissement public de coopération intercommunale EPCI)</option>
                                                <option value="29">29 :Autre employeur public</option>
                                            </optgroup>
                                    </select>
                            <?php  } ?>  
                        </div>



                        <div class="form-group">
                            <label class="form-label">Employeur spécifique</label>
                            <div class="form-display" data-field="specifiqueE"><?= isEmpty($entreprise->specifiqueE) ?></div>

                            <?php  if(empty($entreprise->specifiqueE)){ ?>
                                <input type="text"  data-input="specifiqueE" value="<?= isEmptySave($entreprise->specifiqueE) ?>" class="form-input"  
                                style="display: none; <?= empty($entreprise->specifiqueE) ? 'color: red;' : '' ?>" 
                                     disabled>
                            <?php  } else{ ?>
                                   <select class="form-select" data-input="specifiqueE" 
                                    style="display: none; " 
                                    value="<?=$entreprise->specifiqueE ?>"   disabled>
                                        <option value="">_________</option>
                                        <option value="1">1 :Entreprise de travail temporaire</option>
                                        <option value="2">2 :Groupement d’employeurs</option>
                                        <option value="3">3 :Employeur saisonnier</option>
                                        <option value="4">4 :Apprentissage familial : l’employeur est un ascendant de l’apprenti</option>
                                        <option value="0">0 :Aucun de ces cas</option>
                                    </select>
                            <?php  } ?>
                        </div>
                    </div>

                    <div class="form-row triple">
                        <div class="form-group">
                            <label class="form-label">Effectif</label>
                            <div class="form-display" data-field="totalE"><?= isEmpty($entreprise->totalE) ?></div>
                              <input type="text" class="form-input" data-input="totalE" value="<?= isEmptySave($entreprise->totalE) ?>" 
                            style="display: none; <?= empty($entreprise->totalE) ? 'color: red;' : '' ?>"
                               disabled>
                            
                        </div>
                        <div class="form-group">
                            <label class="form-label">Adresse Email</label>
                            <div class="form-display" data-field="emailE"><?= isEmpty($entreprise->emailE) ?></div>
                            <input type="email" class="form-input" data-input="emailE" value="<?= isEmptySave($entreprise->emailE) ?>" 
                            style="display: none; <?= empty($entreprise->emailE) ? 'color: red;' : '' ?>"
                              disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Téléphone</label>
                            <div class="form-display" data-field="numeroE"><?= isEmpty($entreprise->numeroE) ?></div>
                            <input type="tel" class="form-input" data-input="numeroE" value="<?= isEmptySave($entreprise->numeroE) ?>" 
                            style="display: none; <?= empty($entreprise->numeroE) ? 'color: red;' : '' ?>"
                              disabled>
                           
                        </div>
                    </div>

                    <div class="form-row triple">
                        <div class="form-group">
                            <label class="form-label">Numéro SIRET</label>
                            <div class="form-display" data-field="siretE"><?= isEmpty($entreprise->siretE) ?></div>
                            <input type="text" class="form-input" data-input="siretE" value="<?= isEmptySave($entreprise->siretE) ?>" 
                            style="display: none; <?= empty($entreprise->siretE) ? 'color: red;' : '' ?>"
                              disabled>
                           
                        </div>
                        <div class="form-group">
                            <label class="form-label">Code activité (NAF)</label>
                            <div class="form-display" data-field="codeaE"><?= isEmpty($entreprise->codeaE) ?></div>
                            <input type="text" class="form-input" data-input="codeaE" value="<?= isEmptySave($entreprise->codeaE) ?>" 
                            style="display: none; <?= empty($entreprise->codeaE) ? 'color: red;' : '' ?>"
                              disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Code IDCC</label>
                            <div class="form-display" data-field="codeiE"><?= isEmpty($entreprise->codeiE) ?></div>
                            <input type="text" class="form-input" data-input="codeiE" value="<?= isEmptySave($entreprise->codeiE) ?>" 
                            style="display: none; <?= empty($entreprise->codeiE) ? 'color: red;' : '' ?>"
                             disabled>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label class="form-label">Opco responsable de l'entreprise</label>
                            <div class="form-display" data-field="opco"><?= nameOpcos($entreprise->idopco) ?></div>
                            <input type="text" class="form-input" data-input="codeiE" value="<?= nameOpco($entreprise->idopco) ?>" 
                              style="display: none; <?= (empty($entreprise->idopco) || $entreprise->idopco == 0) ? 'color: red;' : '' ?>"
                              disabled>
                        </div>
                    </div>

                    <h4 class="card-title">Adresse de l'établissement d'exécution du contrat</h4>
                    
                    <div class="form-row triple">
                        <div class="form-group">
                            <label class="form-label">Rue</label>
                            <div class="form-display" data-field="rueE"><?= isEmpty($entreprise->rueE) ?></div>
                            <input type="text" class="form-input" data-input="rueE" value="<?= isEmptySave($entreprise->rueE) ?>" 
                              style="display: none; <?= empty($entreprise->rueE) ? 'color: red;' : '' ?>"
                              disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Voie</label>
                            <div class="form-display" data-field="voieE"><?= isEmpty($entreprise->voieE) ?></div>
                            <input type="text" class="form-input" data-input="voieE" value="<?= isEmptySave($entreprise->voieE) ?>" 
                            style="display: none; <?= empty($entreprise->voieE) ? 'color: red;' : '' ?>"
                              disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Complément</label>
                            <div class="form-display" data-field="complementE"><?= isEmpty($entreprise->complementE) ?></div>
                            <input type="text" class="form-input" data-input="complementE" value="<?= isEmptySave($entreprise->complementE) ?>" 
                            style="display: none; <?= empty($entreprise->complementE) ? 'color: red;' : '' ?>"
                             disabled>
                        </div>
                    </div>

                    <div class="form-row double">
                        <div class="form-group">
                            <label class="form-label">Code postal</label>
                            <div class="form-display" data-field="postalE"><?= isEmpty($entreprise->postalE) ?></div>
                            <input type="text" class="form-input" data-input="postalE" value="<?= isEmptySave($entreprise->postalE) ?>" 
                            style="display: none; <?= empty($entreprise->postalE) ? 'color: red;' : '' ?>"
                              disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Commune</label>
                            <div class="form-display" data-field="communeE"><?= isEmpty($entreprise->communeE) ?></div>
                            <input type="text" class="form-input" data-input="communeE" value="<?= isEmpty($entreprise->communeE) ?>" 
                            style="display: none; <?= empty($entreprise->communeE) ? 'color: red;' : '' ?>"
                              disabled>
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




         class StudentFormManager {
            constructor() {
                this.isEditMode = false;
                this.originalData = {};
                this.initializeEventListeners();
                this.saveOriginalData();
            }

            initializeEventListeners() {
                document.getElementById('edit-btn').addEventListener('click', () => this.toggleEditMode());
                document.getElementById('save-btn').addEventListener('click', () => this.saveData());
                document.getElementById('cancel-btn').addEventListener('click', () => this.cancelEdit());
            }

            saveOriginalData() {
                const displays = document.querySelectorAll('[data-field]');
                displays.forEach(display => {
                    const fieldName = display.getAttribute('data-field');
                    this.originalData[fieldName] = display.textContent.trim();
                });

                const inputs = document.querySelectorAll('[data-input]');
                inputs.forEach(input => {
                    const fieldName = input.getAttribute('data-input');
                    this.originalData[fieldName] = input.value;
                });
            }

            toggleEditMode() {
                this.isEditMode = true;
                this.updateUI();
            }

            cancelEdit() {
                this.isEditMode = false;
                this.restoreOriginalData();
                this.updateUI();
                this.hideSuccessMessage();
                window.location.reload();
            }

            restoreOriginalData() {
                const inputs = document.querySelectorAll('[data-input]');
                inputs.forEach(input => {
                    const fieldName = input.getAttribute('data-input');
                    if (this.originalData[fieldName] !== undefined) {
                        input.value = this.originalData[fieldName];
                    }
                });

                const displays = document.querySelectorAll('[data-field]');
                displays.forEach(display => {
                    const fieldName = display.getAttribute('data-field');
                    if (this.originalData[fieldName] !== undefined) {
                        display.textContent = this.originalData[fieldName];
                    }
                });
            }

            updateUI() {
                const editBtn = document.getElementById('edit-btn');
                const saveBtn = document.getElementById('save-btn');
                const cancelBtn = document.getElementById('cancel-btn');
                const modeIndicator = document.getElementById('mode-indicator');

                const displays = document.querySelectorAll('[data-field]');
                const inputs = document.querySelectorAll('[data-input]');

                if (this.isEditMode) {
                    editBtn.style.display = 'none';
                    saveBtn.style.display = 'inline-block';
                    cancelBtn.style.display = 'inline-block';
                    modeIndicator.textContent = 'Mode Édition';
                    modeIndicator.className = 'status-indicator status-edit';

                    displays.forEach(display => display.style.display = 'none');
                    inputs.forEach(input => input.style.display = 'block');

                     const requiredElements = document.querySelectorAll('.required');
    
                    requiredElements.forEach(element => {
                        if (studentForm.isEditMode) {
                            element.style.display = 'inline'; // ou 'block' selon vos besoins
                        } else {
                            element.style.display = 'none';
                        }
                    });
                } else {
                    editBtn.style.display = 'inline-block';
                    saveBtn.style.display = 'none';
                    cancelBtn.style.display = 'none';
                    modeIndicator.textContent = 'Mode Consultation';
                    modeIndicator.className = 'status-indicator status-view';

                    displays.forEach(display => display.style.display = 'flex');
                    inputs.forEach(input => input.style.display = 'none');
                }
            }
validateForm() {
    const requiredInputs = document.querySelectorAll('[data-input][required]');
    let isValid = true;
    const errors = [];

    // Fonction utilitaire pour extraire le texte du label
    const getCleanLabelText = (input) => {
        const formGroup = input.closest('.form-group');
        const label = formGroup?.querySelector('label.form-label');
        
        if (label) {
            // Supprimer l'astérisque et nettoyer le texte
            return label.textContent.replace(/\s*\*\s*$/, '').trim();
        }
        
        // Fallback sur data-input
        return input.getAttribute('data-input') || 'Champ inconnu';
    };

    requiredInputs.forEach(input => {
        const fieldLabel = getCleanLabelText(input);
        const fieldValue = input.value.trim();
        
        // Reset du style de bordure
        input.style.borderColor = '#ddd';
        
        // Vérification si le champ est vide
        if (!fieldValue) {
            isValid = false;
            errors.push(`Le champ "${fieldLabel}" est requis.`);
            input.style.borderColor = '#e74c3c';
            return; // Passer au champ suivant si vide
        }
        
        // Validations spécifiques selon le type
        const validationRules = {
            email: () => {
                if (!this.isValidEmail(fieldValue)) {
                    errors.push(`L'email saisi dans "${fieldLabel}" n'est pas valide.`);
                    return false;
                }
                return true;
            },
            
            tel: () => {
                if (!this.isValidPhone(fieldValue)) {
                    errors.push(`Le numéro de téléphone saisi dans "${fieldLabel}" n'est pas valide il doit  contenir entre 10 chiffres.`);
                    return false;
                }
                return true;
            },
            
            securiteA: () => {
                if (!this.isValidSecuriteSociale(fieldValue)) {
                    errors.push(`Le numéro de sécurité sociale doit contenir entre 13 et 15 chiffres.`);
                    return false;
                }
                return true;
            },

            postalA: () => {
                if (!this.isValidPostal(fieldValue)) {
                    errors.push(`Le code postal saisi dans "${fieldLabel}" n'est pas valide,il doit contenir 5 chiffres.`);
                    return false;
                }
                return true;
            },
            
            pattern: () => {
                if (input.pattern && !new RegExp(input.pattern).test(fieldValue)) {
                    errors.push(`Le format du champ "${fieldLabel}" n'est pas correct.`);
                    return false;
                }
                return true;
            }
        };
        
        // Appliquer les validations
        const inputType = input.type;
        const dataInput = input.getAttribute('data-input');
        
        let fieldValid = true;
        
        if (validationRules[inputType]) {
            fieldValid = validationRules[inputType]() && fieldValid;
        }
        
        if (validationRules[dataInput]) {
            fieldValid = validationRules[dataInput]() && fieldValid;
        }
        
        if (input.pattern) {
            fieldValid = validationRules.pattern() && fieldValid;
        }
        
        if (!fieldValid) {
            isValid = false;
            input.style.borderColor = '#e74c3c';
        }
    });

    if (!isValid) {
        showValidationErrors(errors);
    }

    return isValid;
}


isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

isValidPhone(phone) {
    const phoneRegex = /^[0-9]{10}$/;
    return phoneRegex.test(phone);
}

isValidPostal(postal) {
    const postalRegex = /^[0-9]{5}$/;
    return postalRegex.test(postal);
}

isValidSecuriteSociale(numeroSS) {
    // Nettoyer le numéro (enlever espaces, tirets, etc.)
    const cleanNumero = numeroSS.replace(/[\s\-]/g, '');
    
    // Vérifier que c'est entre 13 et 15 chiffres
    const ssRegex = /^[0-9]{13,15}$/;
    
    if (!ssRegex.test(cleanNumero)) {
        return false;
    }
    
    // Validation optionnelle de la structure du numéro de sécurité sociale français
    // Format : 1 + 2 + 2 + 3 + 3 + 2 (+ éventuellement 1 ou 2 chiffres supplémentaires)
    if (cleanNumero.length >= 13) {
        // const sexe = cleanNumero.charAt(0);
        // const annee = cleanNumero.substr(1, 2);
        // const mois = cleanNumero.substr(3, 2);
        
        // // Vérifier le sexe (1 ou 2 pour homme/femme, 7 ou 8 pour temporaire)
        // if (!/^[1278]$/.test(sexe)) {
        //     return false;
        // }
        
        // // Vérifier le mois (01 à 12, ou 20 pour naissance à l'étranger)
        // const moisNum = parseInt(mois, 10);
        // if (moisNum < 1 || (moisNum > 12 && moisNum !== 20)) {
        //     return false;
        // }

        return true;
    }
    
    return true;
}

async saveData() {
    if (!this.validateForm()) {
        return;
    }

    // Afficher un indicateur de chargement
    this.showLoadingState();

    try {
        const formData = this.getAllFormData();
        
        // Envoyer les données au serveur
        const response = await this.sendDataToServer(formData);


       
        
        if (response.success) {
            this.isEditMode = false;
            this.updateDisplayValues();
            this.saveOriginalData();
            this.updateUI();
            this.showSuccessMessage('Les modifications ont été enregistrées avec succès !');

        } else {
           this.resetLoadingState();
           this.showErrorMessage(response.message || 'Erreur lors de la sauvegarde');
        }
    } catch (error) {
        console.error('Erreur lors de la sauvegarde:', error);
        this.showErrorMessage('Erreur lors de la sauvegarde : ' + error.message);
    } 
}

async sendDataToServer(formData) {
    try {
        const response = await fetch('update_cerfa', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                studentId: this.getStudentId(), // ID de l'étudiant
                data: formData
            })
        });


        return await response.json();
    } catch (error) {
        throw new Error('Impossible de contacter le serveursend: ' + error.message);
    }
}

getCSRFToken() {
    // Récupérer le token CSRF depuis une meta tag ou un input hidden
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                  document.querySelector('input[name="_token"]')?.value;
    return token || '';
}

getStudentId() {
    // Récupérer l'ID de l'étudiant depuis l'URL, un attribut data, ou une variable globale
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id') || 
           document.body.getAttribute('data-student-id') || 
           window.studentId || 
           null;
}

updateDisplayValues() {
    const inputs = document.querySelectorAll('[data-input]');
    inputs.forEach(input => {
        const fieldName = input.getAttribute('data-input');
        const display = document.querySelector(`[data-field="${fieldName}"]`);
        
        if (display) {
            let displayValue = input.value;
            
            // Formatage spécial pour certains champs
            if (input.type === 'date' && displayValue) {
                displayValue = this.formatDate(displayValue);
            }
            
            display.textContent = displayValue;
        }
    });
}

formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
}

showSuccessMessage(message = 'Les modifications ont été enregistrées avec succès !') {
    const successMessage = document.getElementById('success-message');
    successMessage.textContent = message;
    successMessage.style.display = 'block';
    
    setTimeout(() => {
        this.hideSuccessMessage();
        window.location.reload(); // Recharger la page après 2 secondes
    }, 1000);
}

showErrorMessage(message) {
    // Créer ou utiliser un élément pour afficher les erreurs
    let errorMessage = document.getElementById('error-message');
    if (!errorMessage) {
        errorMessage = document.createElement('div');
        errorMessage.id = 'error-message';
        errorMessage.className = 'error-message';
        errorMessage.style.cssText = `
            display: none;
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin: 10px 0;
            border: 1px solid #f5c6cb;
        `;
        document.querySelector('.control-buttons').insertAdjacentElement('afterend', errorMessage);
    }
    
    errorMessage.textContent = message;
    errorMessage.style.display = 'block';
    
    setTimeout(() => {
        errorMessage.style.display = 'none';
    }, 5000);
}

showLoadingState() {
    const saveBtn = document.getElementById('save-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    
    // Désactiver les boutons et afficher un indicateur de chargement
    saveBtn.disabled = true;
    cancelBtn.disabled = true;
    saveBtn.textContent = 'Sauvegarde...';
    
    // Ajouter une classe de chargement
    saveBtn.classList.add('loading');
}

resetLoadingState() {
    const saveBtn = document.getElementById('save-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    
    // Réactiver les boutons et restaurer le texte d'origine
    saveBtn.disabled = false;
    cancelBtn.disabled = false;
    saveBtn.textContent = 'VALIDER'; // Texte par défaut
    
    // Retirer la classe de chargement
    saveBtn.classList.remove('loading');
}

hideLoadingState() {
    const saveBtn = document.getElementById('save-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    
    // Réactiver les boutons
    saveBtn.disabled = false;
    cancelBtn.disabled = false;
    saveBtn.textContent = 'Valider';
    
    // Retirer la classe de chargement
    saveBtn.classList.remove('loading');
}

// Méthode pour charger les données depuis le serveur
async loadStudentData(studentId) {
    try {
        this.showLoadingState();
        
        const response = await fetch(`/api/student/${studentId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.success) {
            this.loadFormData(data.student);
        } else {
            throw new Error(data.message || 'Erreur lors du chargement des données');
        }
    } catch (error) {
        console.error('Erreur lors du chargement:', error);
        this.showErrorMessage('Erreur lors du chargement des données : ' + error.message);
    } finally {
        this.hideLoadingState();
    }
}

hideSuccessMessage() {
    const successMessage = document.getElementById('success-message');
    successMessage.style.display = 'none';
}

// Méthode pour récupérer toutes les données du formulaire
getAllFormData() {
    const formData = {};
    const inputs = document.querySelectorAll('[data-input]');
    
    inputs.forEach(input => {
        const fieldName = input.getAttribute('data-input');
        formData[fieldName] = input.value;
    });
    
    return formData;
}

// Méthode pour charger des données dans le formulaire
loadFormData(data) {
    Object.keys(data).forEach(fieldName => {
        const input = document.querySelector(`[data-input="${fieldName}"]`);
        const display = document.querySelector(`[data-field="${fieldName}"]`);
        
        if (input) {
            input.value = data[fieldName] || '';
        }
        
        if (display) {
            let displayValue = data[fieldName] || '';
            
            // Formatage spécial pour les dates
            if (input && input.type === 'date' && displayValue) {
                displayValue = this.formatDate(displayValue);
            }
            
            display.textContent = displayValue;
        }
    });
    
    this.saveOriginalData();
}

// Méthode pour réinitialiser le formulaire
resetForm() {
    const inputs = document.querySelectorAll('[data-input]');
    inputs.forEach(input => {
        input.value = '';
        input.style.borderColor = '#ddd';
    });
    
    const displays = document.querySelectorAll('[data-field]');
    displays.forEach(display => {
        display.textContent = '';
    });
    
    this.isEditMode = false;
    this.updateUI();
    this.hideSuccessMessage();
}

// Méthode pour vérifier si le formulaire a été modifié
hasChanges() {
    const inputs = document.querySelectorAll('[data-input]');
    
    for (let input of inputs) {
        const fieldName = input.getAttribute('data-input');
        if (this.originalData[fieldName] !== input.value) {
            return true;
        }
    }
    
    return false;
}

// Méthode pour confirmer la sortie si des modifications non sauvegardées existent
confirmExit() {
    if (this.isEditMode && this.hasChanges()) {
        return confirm('Vous avez des modifications non sauvegardées. Voulez-vous vraiment quitter sans sauvegarder ?');
    }
    return true;
}
}

// Initialisation du gestionnaire de formulaire
document.addEventListener('DOMContentLoaded', function() {
    const studentForm = new StudentFormManager();
    
    // Charger les données de l'étudiant si un ID est présent
    const studentId = studentForm.getStudentId();
    if (studentId) {
        studentForm.loadStudentData(studentId);
    }

    const requiredElements = document.querySelectorAll('.required');
    
    requiredElements.forEach(element => {
        if (studentForm.isEditMode) {
            element.style.display = 'inline'; // ou 'block' selon vos besoins
        } else {
            element.style.display = 'none';
        }
    });
    
    // Gestion de la fermeture de la page avec des modifications non sauvegardées
    window.addEventListener('beforeunload', function(e) {
        if (studentForm.isEditMode && studentForm.hasChanges()) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
    
    // Gestion du raccourci clavier Ctrl+S pour sauvegarder
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            if (studentForm.isEditMode) {
                studentForm.saveData();
            }
        }
        
        // Gestion du raccourci Escape pour annuler
        if (e.key === 'Escape' && studentForm.isEditMode) {
            studentForm.cancelEdit();
        }
    });
    
    // Ajout d'une animation de focus sur les champs
    const inputs = document.querySelectorAll('[data-input]');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.boxShadow = '0 0 5px rgba(0, 123, 255, 0.5)';
        });
        
        input.addEventListener('blur', function() {
            this.style.boxShadow = 'none';
        });
        
        // Validation en temps réel pour le numéro de sécurité sociale
        if (input.getAttribute('data-input') === 'securiteA') {
            input.addEventListener('input', function() {
                const cleanValue = this.value.replace(/[\s\-]/g, '');
                const isValid = studentForm.isValidSecuriteSociale(this.value);
                
                if (this.value && !isValid) {
                    this.style.borderColor = '#e74c3c';
                    this.title = 'Le numéro de sécurité sociale doit contenir entre 13 et 15 chiffres';
                } else {
                    this.style.borderColor = '#ddd';
                    this.title = '';
                }
                
                // Afficher le nombre de caractères
                let counter = this.parentNode.querySelector('.char-counter');
                if (!counter) {
                    counter = document.createElement('small');
                    counter.className = 'char-counter';
                    counter.style.cssText = 'color: #666; font-size: 0.8em; margin-top: 2px; display: block;';
                    this.parentNode.appendChild(counter);
                }
                counter.textContent = `${cleanValue.length}/13-15 chiffres`;
                counter.style.color = isValid || !this.value ? '#666' : '#e74c3c';
            });
        }
    });
    
    // Exposer l'instance globalement pour le débogage (optionnel)
    window.studentForm = studentForm;

function synchronizeFormDisplayWithSelects() {
    // Sélectionner tous les éléments select avec la classe form-select
    const selects = document.querySelectorAll('select.form-select');
    
    selects.forEach(select => {
        // Récupérer l'attribut data-input du select
        const dataInput = select.getAttribute('data-input');
        
        if (dataInput) {
            // Trouver la div form-display correspondante avec data-field identique
            const formDisplay = document.querySelector(`.form-display[data-field="${dataInput}"]`);
            
            if (formDisplay) {
                // Récupérer la valeur actuelle dans form-display (en supprimant les espaces)
                const currentValue = formDisplay.textContent.trim();
                
                // Chercher l'option correspondante dans le select
                const options = select.querySelectorAll('option');
                let matchingOption = null;
                
                options.forEach(option => {
                    const optionValue = option.value.trim();
                    const optionText = option.textContent.trim();
                    
                    // Vérifier si la valeur correspond soit à la value soit au texte de l'option
                    if (optionValue === currentValue || optionText === currentValue) {
                        matchingOption = option;
                    }
                });
                
                if (matchingOption) {
                    // Retirer l'attribut selected de toutes les options
                    options.forEach(opt => opt.removeAttribute('selected'));
                    
                    // Ajouter l'attribut selected à l'option trouvée
                    matchingOption.setAttribute('selected', 'selected');
                    
                    // Mettre à jour le form-display avec le texte de l'option
                    formDisplay.textContent = matchingOption.textContent.trim();
                    
                    console.log(`Synchronisé: ${dataInput} -> ${matchingOption.textContent.trim()}`);
                } else {
                    console.log(`Aucune option correspondante trouvée pour: ${dataInput} (valeur: "${currentValue}")`);
                }
            }
        }
    });
}

    // Exécuter la synchronisation
synchronizeFormDisplayWithSelects();
    
});




function showValidationErrors(errors) {
    if (errors.length === 0) return;
    
    Swal.fire({
        title: 'Erreurs de validation',
        html: `
            <div class="validation-errors">
                <p class="mb-3 text-muted">
                    ${errors.length === 1 ? 'Une erreur a été détectée :' : errors.length + ' erreurs ont été détectées :'}
                </p>
                <div class="text-left">
                    ${errors.map(error => `
                        <div class="alert alert-danger py-2 mb-2" style="font-size: 0.9rem;">
                            <i class="fas fa-exclamation-circle me-2"></i>${error}
                        </div>
                    `).join('')}
                </div>
            </div>
        `,
        icon: 'error',
        confirmButtonText: 'Corriger les erreurs',
        confirmButtonColor: '#dc3545',
        width: '600px',
        customClass: {
            container: 'validation-modal'
        }
    });
}




    </script>
</body>
</html>