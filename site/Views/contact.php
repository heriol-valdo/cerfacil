<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | CerFacil</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.8/lottie.min.js"></script>
    <link rel="icon" type="image/png" href="./Public/img/favicon.png" >
    <link rel="stylesheet" type="text/css" href="./Public/css/contact.css">
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
            <li><a href="tarifs">Tarifs</a></li>
            
            <li>
                <a href="faq" class="dropdown-link " id="resources-dropdown"> Ressources<span class="dropdown-arrow">&lt;</span></a>
                <ul class="submenu" id="resources-submenu">
                    <li><a href="faq" >FAQ</a></li>
                </ul>
            </li>

            <li><a href="contact" class="navbaract">Contact</a></li>
        </ul>
        <a href="https://cerfa.heriolvaldo.com/cerfa/" class="btn" id="connect-btn">Se connecter</a>
    </nav>



        <section class="hero-ac">

        <div class="hero-content">
            <h1>Prendre contact avec nous</h1>
            <p>Une équipe disponible pour vous accompagner, répondre à vos questions et vous aider dans vos projets.</p>
        </div>


            <div class="hero-image">
               <img src="./Public/img/alternance/contact.png" alt="Illustration" class="hero-imgs">
            </div>
            
        </section>

    </main>

    
    <main class="main-p2">
        <div class="booking-container">
            <div class="booking-left">
               
                <div class="presenter-info">
                    <img src="./Public/img/heriol.jpg" alt="Photo du présentateur" class="presenter-photo">
                    <h3>Heriol Zeufack</h3>
                    <h2>Présentation CerFacil</h2>
                    <div class="duration">
                        <i class="fa-solid fa-clock"></i><span class="heure"> 1 h</span> 
                    </div>
                    <div class="conference-info">
                       <i class="fa-solid fa-circle-info"></i>
                       <span class="heure">Informations sur la conférence en ligne fournies à la confirmation.</span>
                        
                    </div>
                </div>
            </div>
            
            <div class="booking-right">
                <h2>Sélectionnez la date et l'heure</h2>
                
                <div class="calendar-navigation">
                    <button id="prev-month" class="nav-button">&lt;</button>
                    <div id="current-month">mars 2025</div>
                    <button id="next-month" class="nav-button">&gt;</button>
                </div>
                
                <div class="calendar">
                    <div class="weekdays">
                        <div>LUN</div>
                        <div>MAR</div>
                        <div>MER</div>
                        <div>JEU</div>
                        <div>VEN</div>
                        <div>SAM</div>
                        <div>DIM</div>
                    </div>
                    <div id="calendar-days" class="days">
                        <!-- Les jours seront générés par JavaScript -->
                    </div>
                </div>
                
                <div id="time-slots-container" class="time-slots-container">
                    <h3>Créneaux horaires</h3>
                    <div id="time-slots" class="time-slots">
                        <!-- Les créneaux horaires seront générés par JavaScript -->
                    </div>
                </div>
                
                <div id="booking-form" class="booking-form hidden">
                    <h3>Vos informations</h3>
                    <form id="reservation-form">
                        <div class="form-group">
                            <label for="name">Nom complet</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Téléphone</label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="company">Entreprise (optionnel)</label>
                            <input type="text" id="company" name="company">
                        </div>
                        <div class="form-group">
                            <label for="message">Message (optionnel)</label>
                            <textarea id="message" name="message" rows="3"></textarea>
                        </div>
                        <input type="hidden" id="selected-date" name="selected-date">
                        <input type="hidden" id="selected-time" name="selected-time">
                        <button type="submit" id="circle" class="submit-button">Confirmer la réservation</button>
                    </form>
                </div>
                
                <!-- <div class="timezone">
                    <h3>Fuseau horaire</h3>
                    <div class="timezone-selector">
                        <i class="icon-globe"></i>
                        <select id="timezone-select">
                            <option value="Europe/Paris">Heure d'Europe centrale (UTC+1)</option>
                            <option value="Europe/London">Heure de Londres (UTC+0)</option>
                            <option value="America/New_York">Heure de New York (UTC-5)</option>
                        </select>
                    </div>
                </div> -->
            </div>
        </div>
        
        <div id="confirmation-modal" class="modal hidden">
            <div class="modal-content ">
                <span class="close-modal valid">&times;</span>
                <h2>Réservation confirmée</h2>
                <p>Votre rendez-vous a bien été réservé. Un email de confirmation a été envoyé à votre adresse.</p>
                <p class="confirmation-details">
                    <strong>Date:</strong> <span id="confirm-date"></span><br>
                    <strong>Heure:</strong> <span id="confirm-time"></span><br>
                    <strong>Avec:</strong> Heriol Zeufack - Présentation CerFacil
                </p>
                <button id="close-confirmation" class="submit-button">Fermer</button>
            </div>
        </div>

        <!-- Modal pour les erreurs -->
        <div id="error-modal" class="modal hidden">
            <div class="modal-content error">
                <span class="close-modal">&times;</span>
                <h2>Erreur de réservation</h2>
                <p id="error-message">Une erreur est survenue lors de la réservation.</p>
                <button id="close-error" class="submit-button">Fermer</button>
            </div>
        </div>
    </main>



   
       



    <?php include 'footer.php'; ?>
    
 
    <script>


document.addEventListener('DOMContentLoaded', function() {

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

        

        // contact #

        // Gestionnaire pour fermer la modal d'erreur
const closeErrorBtn = document.getElementById('close-error');
if (closeErrorBtn) {
closeErrorBtn.addEventListener('click', function() {
    const errorModalEl = document.getElementById('error-modal');
    if (errorModalEl) {
        errorModalEl.classList.add('hidden');
        errorModalEl.classList.remove('visible');
        errorModalEl.style.display = 'none';
    }
});
}

        // Variables globales
let currentDate = new Date();
let currentYear = currentDate.getFullYear();
let currentMonth = currentDate.getMonth();
let selectedDate = null;
let selectedTimeSlot = null;

// Liste des jours fériés en France pour l'année courante et l'année suivante
const joursFeries = getJoursFeries(currentYear);
Object.assign(joursFeries, getJoursFeries(currentYear + 1));

// Initialisation du calendrier
renderCalendar(currentYear, currentMonth);

// Gestionnaires d'événements pour la navigation du calendrier
document.getElementById('prev-month').addEventListener('click', function() {
currentMonth--;
if (currentMonth < 0) {
    currentMonth = 11;
    currentYear--;
}
renderCalendar(currentYear, currentMonth);
});

document.getElementById('next-month').addEventListener('click', function() {
currentMonth++;
if (currentMonth > 11) {
    currentMonth = 0;
    currentYear++;
}
renderCalendar(currentYear, currentMonth);
});

// Gestionnaire d'événements pour le formulaire de réservation
document.getElementById('reservation-form').addEventListener('submit', function(e) {
e.preventDefault();

// Récupérer les données du formulaire
const formData = new FormData(this);


// Envoyer les données au serveur
fetch('sendContact', {
    method: 'POST',
    body: formData
})
.then(response => {
    if (!response.ok) {
        throw new Error(`Erreur HTTP: ${response.status}`);
    }
    return response.json();
})
.then(data => {
    if (data.success) {
        showConfirmation(formData.get('selected-date'), formData.get('selected-time'));
    } else { 
        showConfirmationError(data.message);
    }
})
.catch(error => {
    showConfirmationError('Une erreur est survenue lors de la réservation. Veuillez réessayer. ' + error);
});
});


document.querySelectorAll('.close-modal').forEach(btn => {
    btn.addEventListener('click', closeModal);
});

// Fermeture pour le bouton de confirmation
const closeConfirmBtn = document.getElementById('close-confirmation');
if (closeConfirmBtn) {
    closeConfirmBtn.addEventListener('click', closeModal);
}



// Fonction pour afficher le calendrier
function renderCalendar(year, month) {
const firstDay = new Date(year, month, 1);
const lastDay = new Date(year, month + 1, 0);
const daysInMonth = lastDay.getDate();
const startingDay = firstDay.getDay(); // 0 = Dimanche, 1 = Lundi, etc.

// Ajuster pour commencer la semaine le lundi (0 = Lundi, 6 = Dimanche)
let startDay = startingDay === 0 ? 6 : startingDay - 1;

// Mettre à jour l'affichage du mois et de l'année
const monthNames = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
document.getElementById('current-month').textContent = `${monthNames[month]} ${year}`;

// Générer les jours
const calendarDays = document.getElementById('calendar-days');
calendarDays.innerHTML = '';

// Jours vides au début
for (let i = 0; i < startDay; i++) {
    const emptyDay = document.createElement('div');
    emptyDay.className = 'day empty';
    calendarDays.appendChild(emptyDay);
}

// Jours du mois
const today = new Date();
today.setHours(0, 0, 0, 0); // Réinitialiser l'heure pour comparer seulement les dates

for (let i = 1; i <= daysInMonth; i++) {
    const day = document.createElement('div');
    day.className = 'day';
    day.textContent = i;
    
    const dayDate = new Date(year, month, i);
    dayDate.setHours(0, 0, 0, 0); // Réinitialiser l'heure pour comparer seulement les dates
    
    // Marquer le jour actuel
    if (dayDate.getDate() === today.getDate() && 
        dayDate.getMonth() === today.getMonth() && 
        dayDate.getFullYear() === today.getFullYear()) {
        day.classList.add('today');
    }
    
    // Vérifier si c'est un weekend ou un jour férié
    const dayOfWeek = dayDate.getDay();
    const isWeekend = (dayOfWeek === 0 || dayOfWeek === 6); // 0 = Dimanche, 6 = Samedi
    const isFerie = isJourFerie(dayDate);
    
    // Désactiver les jours passés, weekends et jours fériés
    if (dayDate < today || isWeekend || isFerie) {
        day.classList.add('inactive');
        if (isWeekend) {
            day.setAttribute('title', 'Week-end non disponible');
        } else if (isFerie) {
            day.setAttribute('title', 'Jour férié non disponible');
        }
    } else {
        day.classList.add('active');
        
        // Ajouter un gestionnaire d'événements pour les jours actifs
        day.addEventListener('click', function() {
            // Retirer la classe selected de tous les jours
            document.querySelectorAll('.day').forEach(day => day.classList.remove('selected'));
            
            // Ajouter la classe selected au jour cliqué
            this.classList.add('selected');
            
            // Enregistrer la date sélectionnée
            selectedDate = new Date(year, month, i);
            
            // Générer les créneaux horaires pour cette date
            generateTimeSlots(selectedDate);
            
            // Masquer le formulaire de réservation
            document.getElementById('booking-form').classList.add('hidden');
        });
    }
    
    calendarDays.appendChild(day);
}
}

// Fonction pour générer les créneaux horaires
function generateTimeSlots(date) {
const timeSlotsContainer = document.getElementById('time-slots-container');
const timeSlots = document.getElementById('time-slots');

// Afficher le conteneur des créneaux horaires
timeSlotsContainer.style.display = 'block';

// Vider les créneaux horaires précédents
timeSlots.innerHTML = '';

// Exemple de créneaux horaires
const slots = [
    '09:00', '10:00', '11:00', '14:00', '15:00', '16:00'
];

// Déterminer l'heure actuelle pour désactiver les créneaux passés
const now = new Date();
const isToday = date.getDate() === now.getDate() && 
               date.getMonth() === now.getMonth() && 
               date.getFullYear() === now.getFullYear();

// Créer les éléments de créneau horaire
slots.forEach(slot => {
    const [hours, minutes] = slot.split(':').map(Number);
    const slotTime = new Date(date);
    slotTime.setHours(hours, minutes, 0, 0);
    
    // Vérifier si le créneau est déjà passé (pour aujourd'hui uniquement)
    const isPassed = isToday && slotTime <= now;
    
    if (!isPassed) {
        const timeSlot = document.createElement('div');
        timeSlot.className = 'time-slot';
        timeSlot.textContent = slot;
        
        // Ajouter un gestionnaire d'événements pour le créneau horaire
        timeSlot.addEventListener('click', function() {
            // Retirer la classe selected de tous les créneaux horaires
            document.querySelectorAll('.time-slot').forEach(slot => slot.classList.remove('selected'));
            
            // Ajouter la classe selected au créneau horaire cliqué
            this.classList.add('selected');
            
            // Enregistrer le créneau horaire sélectionné
            selectedTimeSlot = slot;
            
            // Mettre à jour les champs cachés du formulaire
            document.getElementById('selected-date').value = formatDate(selectedDate);
            document.getElementById('selected-time').value = selectedTimeSlot;
            
            // Afficher le formulaire de réservation
            document.getElementById('booking-form').classList.remove('hidden');
        });
        
        timeSlots.appendChild(timeSlot);
    }
});

// Afficher un message si aucun créneau n'est disponible
if (timeSlots.children.length === 0) {
    const noSlot = document.createElement('p');
    noSlot.textContent = "Aucun créneau disponible pour cette date.";
    noSlot.className = "no-slots";
    timeSlots.appendChild(noSlot);
}
}

// Fonction pour formater la date (YYYY-MM-DD)
function formatDate(date) {
const year = date.getFullYear();
const month = String(date.getMonth() + 1).padStart(2, '0');
const day = String(date.getDate()).padStart(2, '0');
return `${year}-${month}-${day}`;
}

// Fonction pour afficher la confirmation
function showConfirmation(date, time) {
// Débogage  console.log("Fonction showConfirmation appelée avec date:", date, "et time:", time);

// Formater la date pour l'affichage
const formattedDate = formatDisplayDate(date);


// Vérifier si les éléments existent
const confirmDateEl = document.getElementById('confirm-date');
const confirmTimeEl = document.getElementById('confirm-time');
const confirmationModalEl = document.getElementById('confirmation-modal');


if (confirmDateEl && confirmTimeEl) {
    // Mettre à jour les détails de la confirmation
    confirmDateEl.textContent = formattedDate;
    confirmTimeEl.textContent = time;
   
} else {
    showConfirmationError("Les éléments de confirmation n'existent pas");
    
}

if (confirmationModalEl) {
    // Afficher la modal
    
    // Supprimer la classe 'hidden'
    confirmationModalEl.classList.remove('hidden');
    
    // Ajouter une classe 'visible' si nécessaire
    confirmationModalEl.classList.add('visible');
    
    // Forcer l'affichage avec style
    confirmationModalEl.style.display = 'block';
    
  
} else {
    showConfirmationError("L'élément modal de confirmation n'existe pas");
   
}
}

function showConfirmationError(errorMessage) {
// Vérifier si les éléments existent
const errorModalEl = document.getElementById('error-modal');
const errorMessageEl = document.getElementById('error-message');

if (errorModalEl && errorMessageEl) {
    // Mettre à jour le message d'erreur
    errorMessageEl.textContent = errorMessage;

    // Afficher la modal
    errorModalEl.classList.remove('hidden');
    errorModalEl.style.display = 'block';

    // Ajouter un écouteur d'événement pour fermer la modal
    const closeButton = document.getElementById('close-error');
    const closeModal = errorModalEl.querySelector('.close-modal');

    function closeModalHandler() {
        errorModalEl.classList.add('hidden');
        errorModalEl.style.display = 'none';
    }

    if (closeButton) {
        closeButton.addEventListener('click', closeModalHandler);
    }
    if (closeModal) {
        closeModal.addEventListener('click', closeModalHandler);
    }

} else {
    console.error("L'élément de la modal d'erreur n'existe pas.");
}
}

// Fonction pour formater la date d'affichage (JJ mois AAAA)
function formatDisplayDate(dateStr) {
const date = new Date(dateStr);
const options = { day: 'numeric', month: 'long', year: 'numeric' };
return date.toLocaleDateString('fr-FR', options);
}

// Fonction pour fermer la modal
function closeModal() {

document.getElementById('confirmation-modal').style.display = 'none';

// Réinitialiser le formulaire
const formEl = document.getElementById('reservation-form');
if (formEl) {
    formEl.reset();
}

// Masquer le formulaire
const bookingFormEl = document.getElementById('booking-form');
if (bookingFormEl) {
    bookingFormEl.classList.add('hidden');
}

// Masquer les créneaux horaires
const timeSlotsContainerEl = document.getElementById('time-slots-container');
if (timeSlotsContainerEl) {
    timeSlotsContainerEl.style.display = 'none';
}

// Réinitialiser les sélections
document.querySelectorAll('.day').forEach(day => day.classList.remove('selected'));
document.querySelectorAll('.time-slot').forEach(slot => slot.classList.remove('selected'));

selectedDate = null;
selectedTimeSlot = null;
}

// Fonction pour calculer les jours fériés en France pour une année donnée
function getJoursFeries(annee) {
const joursFeries = {};

// Jour de l'an
joursFeries[`${annee}-01-01`] = "Jour de l'An";

// Calcul de Pâques (algorithme de Butcher)
const a = annee % 19;
const b = Math.floor(annee / 100);
const c = annee % 100;
const d = Math.floor(b / 4);
const e = b % 4;
const f = Math.floor((b + 8) / 25);
const g = Math.floor((b - f + 1) / 3);
const h = (19 * a + b - d - g + 15) % 30;
const i = Math.floor(c / 4);
const k = c % 4;
const l = (32 + 2 * e + 2 * i - h - k) % 7;
const m = Math.floor((a + 11 * h + 22 * l) / 451);
const n = Math.floor((h + l - 7 * m + 114) / 31);
const p = (h + l - 7 * m + 114) % 31;

const paques = new Date(annee, n - 1, p + 1);

// Lundi de Pâques (J+1)
const lundiPaques = new Date(paques);
lundiPaques.setDate(paques.getDate() + 1);
joursFeries[formatDate(lundiPaques)] = "Lundi de Pâques";

// Fête du travail
joursFeries[`${annee}-05-01`] = "Fête du Travail";

// 8 mai
joursFeries[`${annee}-05-08`] = "Victoire 1945";

// Ascension (J+39)
const ascension = new Date(paques);
ascension.setDate(paques.getDate() + 39);
joursFeries[formatDate(ascension)] = "Ascension";

// Pentecôte (J+49 et J+50)
// Suite du code pour les jours fériés
const pentecote = new Date(paques);
pentecote.setDate(paques.getDate() + 49);
const lundiPentecote = new Date(paques);
lundiPentecote.setDate(paques.getDate() + 50);
joursFeries[formatDate(lundiPentecote)] = "Lundi de Pentecôte";

// 14 juillet
joursFeries[`${annee}-07-14`] = "Fête Nationale";

// Assomption
joursFeries[`${annee}-08-15`] = "Assomption";

// Toussaint
joursFeries[`${annee}-11-01`] = "Toussaint";

// 11 novembre
joursFeries[`${annee}-11-11`] = "Armistice 1918";

// Noël
joursFeries[`${annee}-12-25`] = "Noël";

return joursFeries;
}

// Fonction pour vérifier si une date est un jour férié
function isJourFerie(date) {
const dateStr = formatDate(date);
return dateStr in joursFeries;
}







    });
</script>

</body>
</html>
