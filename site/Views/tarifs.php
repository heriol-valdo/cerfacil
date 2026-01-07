<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarifs | CerFacil</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.8/lottie.min.js"></script>
    <link rel="icon" type="image/png" href="./Public/img/favicon.png" >
    <link rel="stylesheet" type="text/css" href="./Public/css/tarifs.css">
    <script  src="./Public/js/script.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>


    <main  class="main-p1">
      <h class="main-p1-h">H</h>
    <!-- NAVIGATION -->
    <nav class="navbar">
        <div class="logo">
            <img src="./Public/img/logo.png" alt="CerFacil Logo">
            <span>CerFacil</span>
            <div class="hamburger-icon" id="hamburger-icon">&#9776;</div>
        </div>

        <ul class="menu" id="menu">
            <li><a href="home">Accueil</a></li>
           
            <li>
                <a href="" class="dropdown-link" id="cfa-dropdown">CFA <span class="dropdown-arrow">&lt;</span></a>
                <ul class="submenu" id="cfa-submenu">
                    <li><a href="alternance">Alternance</a></li>
                    <li><a href="facturation">Facturation</a></li>
                </ul>
            </li>
            <!-- <li><a href="#">Entreprise <span class="new-badge">New</span></a></li> -->
            <li><a href="tarifs" class="navbaract">Tarifs</a></li>
            
            <li>
                <a href="faq" class="dropdown-link " id="resources-dropdown"> Ressources<span class="dropdown-arrow">&lt;</span></a>
                <ul class="submenu" id="resources-submenu">
                    <li><a href="faq" >FAQ</a></li>
                </ul>
            </li>

            <li><a href="contact">Contact</a></li>
        </ul>
        <a href="https://cerfa.heriolvaldo.com/cerfa/" class="btn" id="connect-btn">Se connecter</a>
    </nav>



    <section class="hero">
        <h1>Une <span class="highlight">tarification</span> transparente <br> et adaptée à votre besoin</h1>
        
        
        <div class="sparkles">
            <!-- SVG sparkle icon -->
            <svg width="60" height="60" viewBox="0 0 60 60">
                <line x1="30" y1="0" x2="30" y2="15" stroke="#e74c3c" stroke-width="2"></line>
                <line x1="30" y1="45" x2="30" y2="60" stroke="#e74c3c" stroke-width="2"></line>
                <line x1="0" y1="30" x2="15" y2="30" stroke="#e74c3c" stroke-width="2"></line>
                <line x1="45" y1="30" x2="60" y2="30" stroke="#e74c3c" stroke-width="2"></line>
            </svg>
        </div>
        
        <p>
            Gagnez du temps sur la gestion de vos contrats d'alternance et de stage. Découvrez les tarifs de notre logiciel pour les centre de formation.
        </p>
        
        <img class="payment-illustration" src="./Public/img/facture.png" alt="Payment Illustration">
        
        <div class="wave"></div>
    </section>

    </main>

    <main class="card-div">
        <div class="tabs">
            <div class="tab active" data-tab="contrats">Contrats</div>
            <div class="tab" data-tab="facturation">Facturation</div>
        </div>
    
        <div class="content-tab active" id="contrats">
            <div class="pricing-container">
                <!-- Apprentissage -->
                <div class="pricing-carddiv">
                    <h3 class="pricing-title pink">Apprentissage</h3>
                    <div class="price">25€ <span class="per-unit">HT /dossier</span></div>
                    <ul class="features pink-check">
                        <li>Remplissage automatique du CERFA et convention</li>
                        <li>Adapté à votre convention de formation</li>
                        <li>Envoi automatique du dossier aux OPCO</li>
                        <li>Signature électronique</li>
                        <li>Remontée de l'historique de vos dossiers auprès des OPCO</li>
                    </ul>
                </div>
                
                <!-- Professionnalisation -->
                <div class="pricing-carddiv">
                    <h3 class="pricing-title pink">Professionnalisation</h3>
                    <div class="price">20€ <span class="per-unit">HT /dossier</span></div>
                    <ul class="features pink-check">
                        <li>Remplissage automatique du CERFA et convention</li>
                        <li>Adapté à votre convention de formation</li>
                        <li>Envoi automatique du dossier aux OPCO</li>
                        <li>Signature électronique</li>
                        <li>Remontée de l'historique de vos dossiers auprès des OPCO</li>
                    </ul>
                </div>
                
                <!-- Dossier Stage -->
                <div class="pricing-carddiv">
                    <h3 class="pricing-title blue">Dossier Stage</h3>
                    <div class="badge bientot">Bientôt</div>
                    <div class="price">15€ <span class="per-unit">HT /dossier</span></div>
                    <ul class="features blue-check">
                        <li>Remplissage automatique de la convention</li>
                        <li>Adapté à votre convention de stage</li>
                        <li>Signature électronique</li>
                        <li>Gestion des stages à l'étranger</li>
                    </ul>
                </div>
                
                <!-- Étudiant Sans Entreprise -->
                <!-- <div class="pricing-carddiv">
                    <h3 class="pricing-title pink">Étudiant Sans Entreprise</h3>
                    <div class="badge">new</div>
                    <div class="price">10€ <span class="per-unit">HT /dossier</span></div>
                    <ul class="features pink-check">
                        <li>Adapté à la convention de formation</li>
                        <li>Remplissage automatique du CERFA Étudiant Sans Entreprise (P2S)</li>
                    </ul>
                </div> -->
                
                <!-- Pro A -->
                <!-- <div class="pricing-carddiv">
                    <h3 class="pricing-title pink">Pro A</h3>
                    <div class="badge bientot">Bientôt</div>
                    <div class="price">20€ <span class="per-unit">HT /dossier</span></div>
                    <ul class="features pink-check">
                        <li>Adapté à la convention de formation</li>
                        <li>Remplissage automatique du CERFA Pro A et convention</li>
                    </ul>
                </div> -->
                
                <!-- Devis -->
                <div class="pricing-carddiv">
                    <h3 class="pricing-title blue">Devis</h3>
                    <div class="price">5€ <span class="per-unit">HT /dossier</span></div>
                    <ul class="features blue-check">
                        <li>Remplissage automatique du devis</li>
                        <li>Une fois validé, il est converti en dossier (pas de double saisie)</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="content-tab" id="facturation">
            <div class="pricing-container">
                <!-- Apprentissage facturation -->
                <div class="pricing-carddiv">
                    <h3 class="pricing-title pink">Apprentissage</h3>
                    <div class="price">10€ <span class="per-unit">HT /facture</span></div>
                    <ul class="features pink-check">
                        <li>Génération automatique des factures et des certificats de réalisation</li>
                        <li>Récupération des échéanciers à jour auprès des OPCO</li>
                        <li>Envoi automatique des factures avec le certificat de réalisation</li>
                    </ul>
                </div>
                
                <!-- Professionnalisation facturation -->
                <div class="pricing-carddiv">
                    <h3 class="pricing-title pink">Professionnalisation</h3>
                    <div class="price">10€ <span class="per-unit">HT /facture</span></div>
                    <div class="price"></div>
                    <ul class="features pink-check">
                        <li>Génération automatique des factures</li>
                        <li>Gestion des échéanciers automatique des OPCO</li>
                        <li>Envoi automatique des factures avec le certificat de réalisation</li>
                    </ul>
                </div>
                
                <!-- Reste à charge -->
                <!-- <div class="pricing-carddiv">
                    <h3 class="pricing-title blue">Reste à charge</h3>
                    <div class="badge">new</div>
                    <div class="price">10€ <span class="per-unit">HT /facture</span></div>
                    <ul class="features blue-check">
                        <li>Génération automatique des factures de reste à charge</li>
                        <li>Synchronisation des échéanciers auprès des OPCO</li>
                        <li>Édition des factures</li>
                    </ul>
                </div> -->
            </div>
        </div>
    </main>


    <main class="accordeon">
        <!-- First Image Content -->
        <div class="containers">
            <header>
                <div class="logos">
                    <span class="cloud">☁️</span> Inclus pour tous
                </div>
                <p class="subtitle">Bénéficiez de toutes les fonctionnalités de notre solution CerFacil pour la gestion de vos conventions et contrats d'alternance</p>
            </header>
            
            <div class="featureses">
               
                
                <!-- Document Adaptation Feature -->
                <div class="featurese">
                    <div class="feature-header">
                        <div class="feature-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                                <rect x="4" y="2" width="16" height="20" rx="2" stroke="#4254b5" stroke-width="2"/>
                                <path d="M8 7h8M8 12h8M8 17h4" stroke="#4254b5" stroke-width="2"/>
                                <circle cx="18" cy="17" r="4" fill="#f5f5f5" stroke="#4254b5" stroke-width="2"/>
                                <path d="M18 15v4M16 17h4" stroke="#f5a623" stroke-width="2"/>
                            </svg>
                        </div>
                        <h2 class="feature-title">Adaptation à vos documents internes</h2>
                    </div>
                    <ul class="feature-list">
                        <li class="feature-item">Vous gardez vos conventions de stage ou convention de formation</li>
                        <li class="feature-item">Vos documents sont remplis automatiquement avec vos champs</li>
                        <li class="feature-item">On s'adapte à vous et là est toute la magie de CerFacil</li>
                    </ul>
                </div>

                 <!-- Dashboard Feature -->
                 <div class="featurese">
                    <div class="feature-header">
                        <div class="feature-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="#4254b5">
                                <rect x="3" y="3" width="18" height="18" rx="2" stroke="#4254b5" stroke-width="2" fill="none"/>
                                <circle cx="9" cy="12" r="3" fill="#4254b5"/>
                            </svg>
                        </div>
                        <h2 class="feature-title">Un tableau de bord intuitif et complet</h2>
                    </div>
                    <ul class="feature-list">
                        <li class="feature-item">Gestion de toutes vos formations, promotions et vos étudiants</li>
                        <li class="feature-item">Avertissement des points de blocage de vos dossiers et relances automatiques</li>
                        <!-- <li class="feature-item">Gestion de vos entreprises partenaires (CRM)</li> -->
                    </ul>
                </div>
                
                <!-- Electronic Signature Feature -->
                <div class="featurese">
                    <div class="feature-header">
                        <div class="feature-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                                <path d="M20 6L9 17l-5-5" stroke="#4254b5" stroke-width="2"/>
                                <path d="M16 4l4 2-2 4" stroke="#4254b5" stroke-width="2" fill="none"/>
                            </svg>
                        </div>
                        <h2 class="feature-title">Signature électronique et notifications</h2>
                    </div>
                    <ul class="feature-list">
                        <li class="feature-item">Chaque partie reçoit automatiquement le document à signer lorsqu'il est généré</li>
                        <li class="feature-item">Aucune relance nécessaire ! Les documents (<span class="cerfa">CERFA, convention, facture</span>) sont envoyés par email pour signature auprès de chaque personne en <span class="automatic">automatique</span></li>
                    </ul>
                </div>
                
                <!-- Support Feature -->
                <div class="featurese">
                    <div class="feature-header">
                        <div class="feature-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" stroke="#4254b5" stroke-width="2"/>
                                <path d="M12 8v4M12 16h.01" stroke="#4254b5" stroke-width="2"/>
                            </svg>
                        </div>
                        <h2 class="feature-title">Support client illimité</h2>
                    </div>
                    <ul class="feature-list">
                        <li class="feature-item">Notre équipe qualité répond à toutes vos questions, celles de vos étudiants et de vos entreprises partenaires</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Second Image Content -->
        <section class="pricings">
            <div class="containeres">
               <div class="containeres-element1">
                   <div class="pricing-headers">
                        <h2 class="pricing-titles">La tarification</h2>
                        <p class="pricing-subtitles">Tout ce que vous devez savoir sur la facturation.</p>
                        <p class="pricing-notes">Vous ne trouvez pas la réponse que vous cherchez? Vous pouvez discuter avec nos experts</p>
                        <div class="pricing-image">
                            <img src="./Public/img/tarifs.png" alt="Piggy bank with growth chart" />
                        </div>
                    </div>
               </div>
                
                <div class="containeres-element2">
                    <div class="pricing-contents">
                      
                        
                        <div class="faq">
                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFAQ(0)">
                                    <span>Combien coûte CerFacil ?</span>
                                    <div class="faq-toggle">-</div>
                                </div>
                                <div class="faq-answer active" id="faq-0">
                                    <p>La tarification est simple, un tarif au dossier ou à la facture. Vous payez seulement ce que vous consommez !</p>
                                </div>
                            </div>
                            
                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFAQ(1)">
                                    <span>Y a-t-il un tarif au volume ?</span>
                                    <div class="faq-toggle">+</div>
                                </div>
                                <div class="faq-answer" id="faq-1">
                                    <p>En effet, nous effectuons des devis spécifiques en fonction 
                                        du nombre de dossiers et des fonctionnalités souhaitées.</p>
                                </div>
                            </div>
                            
                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFAQ(2)">
                                    <span>Mon école CFA gère peu de contrats, est-ce que CerFacil fonctionne ?</span>
                                    <div class="faq-toggle">+</div>
                                </div>
                                <div class="faq-answer" id="faq-2">
                                    <p>
                                    Oui, CerFacil fonctionne pour tous types d’organismes de formation ou d’écoles, peu importe le nombre de contrats que vous gérez à l’année. 
                                    Notre solution SaaS vous permet une totale flexibilité.</p>
                                </div>
                            </div>
                            
                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFAQ(3)">
                                    <span>En combien de temps puis-je démarrer avec CerFacil ?</span>
                                    <div class="faq-toggle">+</div>
                                </div>
                                <div class="faq-answer" id="faq-3">
                                    <p>En 24h ! Nous effectuons une intégration de votre centre de formation (CFA) et en 1 jour vous pouvez commencer à traiter vos dossiers et leur facturation sur notre plateforme en ligne. Elle est très simple d’utilisation, elle 
                                        ne nécessite donc d’aucune formation, ni de logiciel à installer.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>


    <main>
        <div class="containe">
           
            <!-- <section class="cfa-section">
                <div class="section-headers">
                    <span class="sparkle">✨</span>
                    <h1 class="section-titles">Nouveautés CFA</h1>
                    <span class="sparkle">✨</span>
                </div>
                
                <div class="content-grids">
                   
                    <div class="cerfa-section">
                        <h2 class="cerfa-title">CERFA Étudiant Sans Entreprise (P2S)</h2>
                        <div class="price">10€ <span class="price-unit">HT /dossier</span></div>
                        
                        <ul class="feature-lists">
                            <li class="feature-item">Parcours simplifié CFA / Étudiant</li>
                            <li class="feature-item">Remplissage automatique du CERFA</li>
                           
                            <li class="feature-item">Signature électronique pour tous</li>
                        </ul>
                    </div>
                    
                   
                    <div class="info-section">
                        <div class="info-groups">
                            <h3 class="info-title">Qu'est-ce que le CERFA Sans Entreprise (P2S) ?</h3>
                            <p class="info-content">Le document pour les apprentis en formation en attente d'entreprise.</p>
                            <p class="info-content">Vous souhaitez garder un étudiant au sein de votre CFA mais il n'a pas encore trouvé son entreprise ? Le CERFA P2S ou formulaire 12576*03 permet :</p>
                        </div>
                        
                        <div class="info-groups">
                            <h4 class="info-label">À l'apprenti :</h4>
                            <div class="info-item">de commencer sa formation avant d'avoir trouvé un employeur pendant 3 mois</div>
                            <div class="info-item">d'avoir le statut de "stagiaire de la formation professionnelle"</div>
                            <div class="info-item">d'être protégé et couvert en cas d'accident</div>
                        </div>
                        
                        <div class="info-groups">
                            <h4 class="info-label">Au centre de formation :</h4>
                            <div class="info-item">permet de faire financer rétroactivement la période de formation par l'OPCO de l'employeur si l'étudiant signe un contrat d'apprentissage durant les 3 mois après le début de sa formation</div>
                            <div class="info-item">d'être conforme en cas de contrôle des OPCO</div>
                        </div>
                    </div>
                </div>
            </section> -->
            
          
            <!-- <section class="enterprise-section">
                <div class="section-header">
                    <span class="sparkle">💫</span>
                    <h2 class="enterprise-title">Nouvelle offre Entreprise</h2>
                    <span class="sparkle">💫</span>
                </div>
                
                <p class="enterprise-subtitle">Sur Filiz, les entreprises ont aussi la main sur leurs contrats d'alternance !</p>
                
                <div class="pricing-card">
                    <h3 class="pricing-header">Tarifs - Sur devis</h3>
                    
                    <ul class="pricing-list">
                        <li class="pricing-item">Support client illimité par notre équipe qualité pour vous vos étudiants et leurs écoles</li>
                        <li class="pricing-item">Nombre de licences illimités sans frais supplémentaires</li>
                        <li class="pricing-item">Installation rapide avec une formation gratuite</li>
                        <li class="pricing-item">Pas de frais d'installation ni de logiciel à installer</li>
                    </ul>
                </div>
            </section> -->
        </div>
    </main>


    <main>
        <div class="container">
            <!-- Pricing Section -->
            <section class="pricing-section">
                <div class="pricing-cards">
                    <h2 class="pricing-title">Un tarif adapté à vos besoins</h2>
                    
                    <div class="pricing-features">
                        <div class="pricing-feature">
                            <div class="feature-icon">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14 3V7C14 7.55228 14.4477 8 15 8H19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M17 21H7C5.89543 21 5 20.1046 5 19V5C5 3.89543 5.89543 3 7 3H14L19 8V19C19 20.1046 18.1046 21 17 21Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9 17H15" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9 13H15" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="feature-content">
                                <p>Que vous gériez entre 1 à 50000 contrats, la tarification est adaptée à votre école ou centre de formation et votre fonctionnement.</p>
                            </div>
                        </div>
                        
                        <div class="pricing-feature">
                            <div class="feature-icon">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M15 9L9 15" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9 9L15 15" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="feature-content">
                                <h3></h3>
                                <p>Vous ne payez qu'en fonction de votre activité. Un tarif tout compris : Aucun frais cachés, pas de frais d'installation ni de formation</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Solution Section -->
            <section class="solution-section">
                <div class="solution-image">
                    <img src="./Public/img/monnaie.gif" alt="Illustration of a person managing documents">
                </div>
                
                <div class="solution-content">
                    <h2 class="solution-title">Une solution adaptée à tous les enjeux des centres de formation</h2>
                    
                    <p class="solution-description">Notre logiciel s'adapte à vos besoins et est la solution de référence pour la gestion digitalisée de vos contrats d'apprentissage et de professionnalisation.</p>
                    
                    <a href="alternance" class="solution-button">Gestion des contrats d'alternance</a>
                </div>
            </section>
        </div>
    </main>

    


   
   
    <main class="main-p5">
        <div class="testimonials-section">
    
            <div class="experts-section">
                <h2>Nos experts vous accompagnent pour simplifier vos démarches</h2>
                <p>Toute l'équipe de CerfFacil est là pour répondre à vos questions et vous faire découvrir la simplicité de notre logiciel en ligne, sans frais d'installation. Prenez rendez-vous avec nous en choisissant le créneau de votre choix.</p>
                
                <div class="cta-buttons">
                    <a href="contact" class="primary-btn">Nous contacter </a>
                    <a href="tarifs" class="secondary-btn">Voir les tarifs </a>
                </div>
            </div>
        </div>
    </main>



    <?php include 'footer.php'; ?>
    
 
    <script>
         function toggleFAQ(index) {
            const answer = document.getElementById(`faq-${index}`);
            const allAnswers = document.querySelectorAll('.faq-answer');
            const allToggles = document.querySelectorAll('.faq-toggle');
            
            // Close all answers first
            allAnswers.forEach((item, i) => {
                if (i !== index) {
                    item.classList.remove('active');
                    allToggles[i].innerHTML = '+';
                }
            });
            
            // Toggle the selected answer
            if (answer.classList.contains('active')) {
                answer.classList.remove('active');
                allToggles[index].innerHTML = '+';
            } else {
                answer.classList.add('active');
                allToggles[index].innerHTML = '-';
            }
        }
    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('.faq-answer').forEach(item => item.classList.remove('active'));
        document.querySelectorAll('.faq-toggle').forEach(toggle => toggle.innerHTML = '+');
        

        const accordionHeaders = document.querySelectorAll('.accordion-header');
            
            accordionHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const item = this.parentElement;
                    item.classList.toggle('active');
                });
            });
    // Éléments DOM
    const hamburgerBtn = document.getElementById('hamburger-icon');
    const menuList = document.getElementById('menu');
    const connectBtn = document.getElementById('connect-btn');
    
    // Sous-menus spécifiques
    const cfaDropdown = document.getElementById('cfa-dropdown');
    const cfaSubmenu = document.getElementById('cfa-submenu');
    const resourcesDropdown = document.getElementById('resources-dropdown');
    const resourcesSubmenu = document.getElementById('resources-submenu');
    
    // Fonction pour ouvrir/fermer le menu hamburger
    hamburgerBtn.addEventListener('click', function() {
        menuList.classList.toggle('active');
        connectBtn.classList.toggle('active');
    });
    
    // Variable pour suivre si on est sur un appareil tactile
    let isTouchDevice = false;
    
    // Détecter l'utilisation d'un appareil tactile
    document.addEventListener('touchstart', function() {
        isTouchDevice = true;
        document.body.classList.add('touch-device');
    }, {once: true});
    
    // Sur mobile uniquement - Gérer les clics pour les sous-menus
    cfaDropdown.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            e.preventDefault();
            e.stopPropagation();
            
            // Empêcher le comportement de survol de s'activer
            if (isTouchDevice) {
                // Toggle la flèche et le sous-menu
                const cfaArrow = this.querySelector('.dropdown-arrow');
                if (cfaArrow) cfaArrow.classList.toggle('active');
                
                // Si le menu est déjà actif, on le cache, sinon on l'affiche
                if (cfaSubmenu.classList.contains('active')) {
                    cfaSubmenu.classList.remove('active');
                } else {
                    cfaSubmenu.classList.add('active');
                }
            }
        }
    });
    
    resourcesDropdown.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            e.preventDefault();
            e.stopPropagation();
            
            // Empêcher le comportement de survol de s'activer
            if (isTouchDevice) {
                // Toggle la flèche et le sous-menu
                const resourcesArrow = this.querySelector('.dropdown-arrow');
                if (resourcesArrow) resourcesArrow.classList.toggle('active');
                
                // Si le menu est déjà actif, on le cache, sinon on l'affiche
                if (resourcesSubmenu.classList.contains('active')) {
                    resourcesSubmenu.classList.remove('active');
                } else {
                    resourcesSubmenu.classList.add('active');
                }
            }
        }
    });
    
    // Gérer le redimensionnement de la fenêtre
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            // Réinitialiser les états des sous-menus en mode desktop
            cfaSubmenu.classList.remove('active');
            resourcesSubmenu.classList.remove('active');
            
            const cfaArrow = cfaDropdown.querySelector('.dropdown-arrow');
            const resourcesArrow = resourcesDropdown.querySelector('.dropdown-arrow');
            
            if (cfaArrow) cfaArrow.classList.remove('active');
            if (resourcesArrow) resourcesArrow.classList.remove('active');
        }
    });


    const tabs = document.querySelectorAll('.tab');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Hide all content tabs
                    document.querySelectorAll('.content-tab').forEach(content => {
                        content.classList.remove('active');
                    });
                    
                    // Show the content tab corresponding to the clicked tab
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });
});
    </script>

</body>
</html>
