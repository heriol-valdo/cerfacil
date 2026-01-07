document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour gérer l'affichage et la fermeture de l'animation de chargement
    function loading(data) {
        var current_effect = 'rotation'; // Effet d'animation
        var loadingText = 'Patientez svp...'; // Texte affiché pendant le chargement
        if (data == 1) {
            run_waitMe(current_effect, loadingText); // Démarrer l'animation
        } else {
            dismiss_waitMe(); // Arrêter l'animation
        }
    }

    // Démarrer l'animation de chargement avant le rechargement de la page
    window.addEventListener('beforeunload', function() {
        loading(1);
    });

    // Remonter en haut de la page avant le rechargement
    window.onbeforeunload = function() {
        window.scrollTo(0, 0);
    };

    // Fonction pour mettre à jour la session avec la page actuelle
    function updateSession(link) {
        var page = link.getAttribute('data-page');

        // Requête AJAX pour mettre à jour la session
        $.ajax({
            url: 'session', // URL du script PHP de mise à jour de session
            method: 'POST',
            data: { page: page },
            success: function(response) {
                console.log(response + 'Session mise à jour avec succès.');
            },
            error: function(error) {
                console.error('Erreur lors de la mise à jour de la session:', error);
            }
        });
    }

    // Arrêter l'animation de chargement une fois la page complètement chargée
    window.addEventListener('load', function() {
        loading(2);
    });

    // Ajouter un écouteur d'événements pour les liens qui nécessitent une mise à jour de session
    document.querySelectorAll('[data-page]').forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Empêcher le comportement par défaut du lien
            updateSession(this); // Mettre à jour la session
            // Rediriger vers la nouvelle page après la mise à jour de la session
            setTimeout(function() {
                window.location.href = link.href;
            }, 100);
        });
    });
});