<?php



use Projet\Model\App;
use Projet\Model\Paginator;
use Projet\Model\StringHelper;
use Projet\Model\FileHelper;
use Projet\Model\Session;
use Soap\Url;

$url = substr(explode('?', $_SERVER["REQUEST_URI"])[0], 1);



App::setTitle("Simulateur");
App::setNavigation("Simulateur");
App::setBreadcumb("<li class='active'>Simulateur</li>");
App::addScript('assets/js/simulateur.js',true);
?>
<style>
       

        .container {
            min-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-align: center;
            padding: 30px;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .steps-container {
            display: flex;
            justify-content: center;
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 2px solid #e9ecef;
        }

        .step {
            display: flex;
            align-items: center;
            margin: 0 15px;
            position: relative;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #dee2e6;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .step.active .step-number {
            background: #28a745;
            color: white;
        }

        .step.completed .step-number {
            background: #007bff;
            color: white;
        }

        .step-label {
            font-weight: 500;
            color: #495057;
        }

        .step.active .step-label {
            color: #28a745;
            font-weight: bold;
        }

        .content {
            padding: 40px;
        }

        .form-section {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .form-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .section-title {
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            font-size: 1.5rem;
            transition: all 0.3s ease;
            height: 40px;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

       

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-secondarys {
            background: #6c757d;
            color: white;
        }

        .btn-secondarys:hover {
            
            color:white;
        }

        .btn-success {
            background: #28a745;
            color: white;
            font-size: 1.2rem;
            padding: 15px 40px;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .results {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin-top: 20px;
        }

        .result-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .result-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .result-amount {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .positive {
            color: #28a745;
        }

        .negative {
            color: #dc3545;
        }

        .neutral {
            color: #17a2b8;
        }

        .result-detail {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .radio-group {
                flex-direction: column;
            }
            
            .steps-container {
                flex-wrap: wrap;
                gap: 10px;
            }
            
            .button-group {
                flex-direction: column;
                gap: 10px;
            }
        }
.radio-groups {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.radio-options {
    flex: 1;
    min-width: 150px;
    position: relative;
}

.radio-options input[type="radio"] {
    position: absolute;
    opacity: 0;
    /* Amélioration : masquer complètement des lecteurs d'écran si nécessaire */
    pointer-events: none;
    display: none;
}

.radio-options label {
    display: block;
    padding: 15px;
    border: 2px solid #dee2e6;
    border-radius: 10px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
    color: #333;
    /* Amélioration : meilleure accessibilité */
    user-select: none;
    font-weight: 500;
}

/* État sélectionné */
.radio-options input[type="radio"]:checked + label {
    border-color: #667eea;
    background-color: #667eea;
    color: white;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

/* Effet hover (seulement si pas déjà sélectionné) */
.radio-options label:hover {
    border-color: #667eea;
    background: rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

/* État focus pour l'accessibilité */
.radio-options input[type="radio"]:focus + label {
    outline: 2px solid #667eea;
    outline-offset: 2px;
}

/* Style actif */
.radio-options label:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.2);
}

/* Animation de sélection */
.radio-options input[type="radio"]:checked + label {
    animation: selectAnimation 0.3s ease;
}

@keyframes selectAnimation {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Responsive : sur mobile */
@media (max-width: 768px) {
    .radio-groups {
        flex-direction: column;
    }
    
    .radio-options {
        min-width: auto;
    }
    
    .radio-options label {
        padding: 12px;
        font-size: 14px;
    }
}



</style>
<!-- <div class="row">
    <div class="col-md-12">
        <div class="panel panel-dark" style="border-radius: 10px;border: 5px solid #fff;">
            <div class="panel-heading">
                <h5 class="panel-title">
                </h5>
            </div>
                 <div class="panel-body">
                
                    <div class="row m-t-sm" style="min-height: 470px;">
                        <div class="col-md-12">

                         
                       
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->



  <div class="container">
                                <div class="header">
                                    <h1>💼 Simulateur de Coût d'Alternance</h1>
                                    <p>Calculez le coût réel de votre contrat d'alternance</p>
                                </div>

                                <div class="steps-container">
                                    <div class="step active" id="step1">
                                        <div class="step-number">1</div>
                                        <div class="step-label">Informations générales</div>
                                    </div>
                                    <div class="step" id="step2">
                                        <div class="step-number">2</div>
                                        <div class="step-label">Détails du contrat</div>
                                    </div>
                                    <div class="step" id="step3">
                                        <div class="step-number">3</div>
                                        <div class="step-label">Formation</div>
                                    </div>
                                    <div class="step" id="step4">
                                        <div class="step-number">4</div>
                                        <div class="step-label">Résultats</div>
                                    </div>
                                </div>

                                <div class="content">
                                    <!-- Étape 1: Informations générales -->
                                    <div class="form-section active" id="section1">
                                        <h2 class="section-title">
                                            📋 Informations générales
                                        </h2>
                                           <p class="mainColor text-right">* Champs obligatoires</p>
                                        
                                      <div class="form-group">
                                            <label>Type de contrat : <b>*</b></label>
                                            <div class="radio-groups">
                                                <div class="radio-options">
                                                    <input type="radio" id="apprentissage" name="typeContrat" value="apprentissage" onclick="selectTypeContrat('apprentissage')">
                                                    <label for="apprentissage">Contrat d'apprentissage</label>
                                                </div>
                                                <div class="radio-options">
                                                    <input type="radio" id="professionnalisation" name="typeContrat" value="professionnalisation" onclick="selectTypeContrat('professionnalisation')">
                                                    <label for="professionnalisation">Contrat de professionnalisation</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Zone géographique : <b>*</b></label>
                                            <div class="radio-groups">
                                                <div class="radio-options">
                                                    <input type="radio" id="hexagonale" name="zone" value="hexagonale" onclick="selectZone('hexagonale')">
                                                    <label for="hexagonale">France hexagonale</label>
                                                </div>
                                                <div class="radio-options">
                                                    <input type="radio" id="outremer" name="zone" value="outremer" onclick="selectZone('outremer')">
                                                    <label for="outremer">Territoires d'outre-mer</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group">
                                                <label for="age">Âge de l'alternant : <b>*</b></label>
                                                <input type="number" id="age" class="form-control" min="16" max="30" placeholder="Ex: 20">
                                            </div>
                                            <div class="form-group">
                                                <label for="dateDebut">Date de début du contrat : <b>*</b></label>
                                                <input type="date" id="dateDebut" class="form-control">
                                            </div>
                                        </div>

                                        <div class="button-group">
                                            <div></div>
                                            <button class="btn btn-primary" onclick="nextStep(1)">Suivant →</button>
                                        </div>
                                    </div>

                                    <!-- Étape 2: Détails du contrat -->
                                    <div class="form-section" id="section2">
                                        <h2 class="section-title">
                                            📝 Détails du contrat
                                        </h2>
                                          <p class="mainColor text-right">* Champs obligatoires</p>

                                        <div class="form-group">
                                            <label for="anneeApprentissage">Année d'apprentissage : <b>*</b></label>
                                            <select id="anneeApprentissage" class="form-control" onchange="updateDureeOptions()">
                                                <option value="">Sélectionnez l'année</option>
                                                <option value="1">1ère année</option>
                                                <option value="2">2ème année</option>
                                                <option value="3">3ème année</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="duree">Durée restante (en mois) :  <b>*</b></label>
                                            <select id="duree" class="form-control">
                                                <option value="">Sélectionnez d'abord l'année d'apprentissage</option>
                                            </select>
                                        </div>

                                        <div class="alert alert-info">
                                            <strong>💡 Information :</strong> Le salaire sera calculé automatiquement selon la grille officielle en fonction de l'âge, du type de contrat et de l'année d'apprentissage.
                                        </div>

                                        <div class="button-group">
                                            <button class="btn btn-secondarys" onclick="prevStep(2)">← Précédent</button>
                                            <button class="btn btn-primary" onclick="nextStep(2)">Suivant →</button>
                                        </div>
                                    </div>

                                    <!-- Étape 3: Formation -->
                                    <div class="form-section" id="section3">
                                        <h2 class="section-title">
                                            🎓 Formation
                                        </h2>
                                         <p class="mainColor text-right">* Champs obligatoires</p>

                                        <div class="form-group">
                                            <label for="codeRNCP">Code RNCP de la formation :  <b>*</b></label>
                                            <input type="text" id="codeRNCP" class="form-control" placeholder="Ex: RNCP34492">
                                        </div>

                                        <div class="form-group">
                                            <label for="coutFormation">Coût total de la formation (€) :</label>
                                            <input type="number" id="coutFormation" class="form-control" min="0" step="0.01" placeholder="Ex: 15000">
                                        </div>

                                        <div class="button-group">
                                            <button class="btn btn-secondarys" onclick="prevStep(3)">← Précédent</button>
                                            <button class="btn btn-success" onclick="calculer()">🧮 Calculer</button>
                                        </div>
                                    </div>

                                    <!-- Étape 4: Résultats -->
                                    <div class="form-section" id="section4">
                                        <h2 class="section-title">
                                            📊 Résultats de la simulation
                                        </h2>

                                        <div class="loading" id="loading">
                                            <div class="spinner"></div>
                                            <p>Calcul en cours...</p>
                                        </div>

                                        <div class="results" id="results" style="display: none;">
                                            <!-- Les résultats seront affichés ici -->
                                        </div>

                                        <div class="button-group">
                                           <button class="btn btn-secondary" onclick="window.location.href='simulateur'">🔄 Nouvelle simulation</button>
                                            <button class="btn btn-primary" onclick="window.print()">🖨️ Imprimer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

<script>
// Fonctions pour gérer le changement de couleur des radio buttons

// Fonction pour le type de contrat
function selectTypeContrat(selectedValue) {
    // Réinitialiser tous les labels du groupe
    const apprentissageLabel = document.querySelector('label[for="apprentissage"]');
    const professionnalisationLabel = document.querySelector('label[for="professionnalisation"]');
    
    // Styles par défaut
    apprentissageLabel.style.borderColor = '#dee2e6';
    apprentissageLabel.style.backgroundColor = 'white';
    apprentissageLabel.style.color = '#333';
    
    professionnalisationLabel.style.borderColor = '#dee2e6';
    professionnalisationLabel.style.backgroundColor = 'white';
    professionnalisationLabel.style.color = '#333';
    
    // Appliquer le style sélectionné
    if (selectedValue === 'apprentissage') {
        apprentissageLabel.style.borderColor = '#667eea';
        apprentissageLabel.style.backgroundColor = '#667eea';
        apprentissageLabel.style.color = 'white';
        apprentissageLabel.style.boxShadow = '0 2px 8px rgba(102, 126, 234, 0.3)';
    } else if (selectedValue === 'professionnalisation') {
        professionnalisationLabel.style.borderColor = '#667eea';
        professionnalisationLabel.style.backgroundColor = '#667eea';
        professionnalisationLabel.style.color = 'white';
        professionnalisationLabel.style.boxShadow = '0 2px 8px rgba(102, 126, 234, 0.3)';
    }
}

// Fonction pour la zone géographique
function selectZone(selectedValue) {
    // Réinitialiser tous les labels du groupe
    const hexagonaleLabel = document.querySelector('label[for="hexagonale"]');
    const outremerLabel = document.querySelector('label[for="outremer"]');
    
    // Styles par défaut
    hexagonaleLabel.style.borderColor = '#dee2e6';
    hexagonaleLabel.style.backgroundColor = 'white';
    hexagonaleLabel.style.color = '#333';
    hexagonaleLabel.style.boxShadow = 'none';
    
    outremerLabel.style.borderColor = '#dee2e6';
    outremerLabel.style.backgroundColor = 'white';
    outremerLabel.style.color = '#333';
    outremerLabel.style.boxShadow = 'none';
    
    // Appliquer le style sélectionné
    if (selectedValue === 'hexagonale') {
        hexagonaleLabel.style.borderColor = '#667eea';
        hexagonaleLabel.style.backgroundColor = '#667eea';
        hexagonaleLabel.style.color = 'white';
        hexagonaleLabel.style.boxShadow = '0 2px 8px rgba(102, 126, 234, 0.3)';
    } else if (selectedValue === 'outremer') {
        outremerLabel.style.borderColor = '#667eea';
        outremerLabel.style.backgroundColor = '#667eea';
        outremerLabel.style.color = 'white';
        outremerLabel.style.boxShadow = '0 2px 8px rgba(102, 126, 234, 0.3)';
    }
}
</script>



