<!DOCTYPE html>
<html>  
<head>
   
    <style>
                /* Classes utilitaires */
        .hidden {
            display: none;
        }

        /* Formulaire de réservation */
        .booking-form {
            margin-top: 30px;
        }

        .booking-form h3 {
            margin-bottom: 15px;
            font-size: 16px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #555;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .submit-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
        }

        .submit-button:hover {
            background-color: #0069d9;
        }
        /* Modal de confirmation */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            position: relative;
           
        }
        .modal-content p{
            text-align: left;
        }

        .modal-content .valid {
            border-top: 4px solid #33ff66;
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
        }

        .confirmation-details {
            margin: 20px 0;
            line-height: 1.6;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .booking-container {
                flex-direction: column;
            }
            
            .booking-left, .booking-right {
                width: 100%;
            }
            
            .booking-left {
                border-right: none;
                border-bottom: 1px solid #eee;
                padding-bottom: 20px;
            }
            
            .time-slots {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .time-slots {
                grid-template-columns: 1fr;
            }
        }


        /* Ajoutez ceci à votre CSS */
        .modal.hidden {
            display: none;
        }

        .modal.visible, 
        .modal:not(.hidden) {
            display: block;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }


        /* error modal */
        /* Style pour la modal d'erreur */
        .modal-content.error {
            border-top: 4px solid #ff3333;
        }

        .modal-content.error h2 {
            color: #ff3333;
        }

        #error-message {
            color: #333;
            margin: 20px 0;
        }
    </style>
</head>
 <body>
    <footer class="footer">
        <div class="footer-container">
            <!-- Section CerFacil -->
            <div class="footer-brand">
                <img src="./Public/img/logo.png" alt="CerFacil Logo">
                <p>La solution en ligne pour la gestion administrative et financière des centres de formation et entreprises</p>
                   <div class="social-links">
                    <a  target="_blank" href="https://www.linkedin.com/in/heriol-valdo-zeufack-fiemo-85bba3258/"><i class="fa-brands fa-linkedin"></i>LinkedIn</a>
                    <a target="_blank" href="https://www.youtube.com/embed/dQw4w9WgXcQ"><i class="fa-brands fa-youtube"></i>Youtube</a>
                   
                </div>
            </div>
    
            <!-- Nos solutions -->
            <div class="footer-column">
                <h3>Nos solutions</h3>
                <ul>
                    <li><a href="alternance">Apprentissage</a></li>
                    <!-- <li><a href="#">Stage</a></li> -->
                    <li><a href="facturation">Facturation <span class="new-tag">Nouveau</span></a></li>
                    <li><a href="tarifs">Tarifs</a></li>
                    <!-- <li><a href="#">Cerfa P2S</a></li>
                    <li><a href="#">Cerfa Pro-A</a></li>
                    <li><a href="#">Entreprise <span class="new-tag">Nouveau</span></a></li> -->
                </ul>
            </div>
    
            <!-- Ressources -->
            <div class="footer-column">
                <h3>Ressources</h3>
                <ul>
                    <li><a href="faq">FAQ</a></li>
                </ul>
            </div>
    
            <!-- Entreprise Filiz -->
            <div class="footer-column">
                <h3>Entreprise CerFacil</h3>
                <ul>
                    <!-- <li><a href="#">Recrutement</a></li>
                    <li><a href="#">Tarifs</a></li> -->
                    <li><a href="contact">Contact</a></li>
                </ul>
            </div>
    
            <!-- Newsletter -->
            <div class="footer-column">
                <h3>Rester au courant de nos actualités !</h3>
                <form id="newsletter-form">
                    <input type="email" name="emailletter"  id="emailletter" placeholder="Renseigner votre e-mail" required>
                    <button type="submit">S'inscrire à notre newsletter</button>
                </form>
            </div>
        </div>
    
        <!-- Footer Bottom -->
        <div class="footer-bottom">
           
            <ul>
                <li><a href="Public/documents/CGUCFA.pdf" download="CGU_CFA.pdf">CGU CFA</a></li>
                <!-- <li><a href="#">EULA Entreprises</a></li> -->
                <li><a href="Public/documents/CGUETU.pdf" download="CGU_ETU.pdf">CGU Étudiants</a></li>
                <!-- <li><a href="'">Gestion des cookies</a></li> -->
            </ul>
            <p>© CerFacil 2025 • Tous droits réservés</p>
        </div>
    </footer>

    <div id="confirmation-modal-letter" class="modal hidden">
            <div class="modal-content valid">
                <span class="close-modal close">&times;</span>
                <h3>Enregistrement  confirmé</h3>
                <p>Un email de confirmation a été envoyé à votre adresse.</p>
                <button id="close-confirmation-letter" class="submit-button">Fermer</button>
            </div>
        </div>

        <!-- Modal pour les erreurs -->
        <div id="error-modals" class="modal hidden">
            <div class="modal-content error">
                <span class="close-modal">&times;</span>
                <h2>Erreur d'enregistrement</h2>
                <p id="error-messages">Une erreur est survenue lors de l'enregistrement.</p>
                <button id="close-errors" class="submit-button">Fermer</button>
            </div>
        </div>

 </body>   
<script>
     document.addEventListener('DOMContentLoaded', function() {

    const closeConfirmBtns = document.getElementById('close-confirmation-letter');
    if (closeConfirmBtns) {
        closeConfirmBtns.addEventListener('click', closeModals);
    }

    const closeModalBtns = document.querySelector('.close');
    if (closeModalBtns) {
        closeModalBtns.addEventListener('click', closeModals);
    }

    
    function closeModals() {
        document.getElementById('confirmation-modal-letter').style.display = 'none';
   
    }

    function  showConfirmationNewLetter() {
    
    const confirmationModalEl = document.getElementById('confirmation-modal-letter');

      
    
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

    function showConfirmationNewLetterError(errorMessage) {
        // Vérifier si les éléments existent
        const errorModalEl = document.getElementById('error-modals');
        const errorMessageEl = document.getElementById('error-messages');

        if (errorModalEl && errorMessageEl) {
            // Mettre à jour le message d'erreur
            errorMessageEl.textContent = errorMessage;

            // Afficher la modal
            errorModalEl.classList.remove('hidden');
            errorModalEl.style.display = 'block';

            // Ajouter un écouteur d'événement pour fermer la modal
            const closeButton = document.getElementById('close-errors');
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

        document.getElementById('newsletter-form').addEventListener('submit', function(e) {
            e.preventDefault();
    
            // Récupérer les données du formulaire
            const formData = new FormData(this);
        
            
            // Envoyer les données au serveur
            fetch('sendNewLetter', {
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
                    showConfirmationNewLetter();
                } else { 
                    showConfirmationNewLetterError(data.message);
                }
            })
            .catch(error => {
                showConfirmationNewLetterError("Une erreur est survenue lors  de l'inscription. Veuillez réessayer. " + error);
            });
                        
        });
    });

   



</script>
</html>