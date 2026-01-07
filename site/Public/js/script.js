document.addEventListener("DOMContentLoaded", function() {
    const elements = document.querySelectorAll(".card");
    elements.forEach(card => {
        card.style.opacity = "0";
        card.style.transform = "translateY(20px)";
        setTimeout(() => {
            card.style.transition = "all 0.8s ease-out";
            card.style.opacity = "1";
            card.style.transform = "translateY(0)";
        }, 300);
    });

    var animation = lottie.loadAnimation({
        container: document.getElementById('animation'), // Le conteneur
        renderer: 'svg', // Type de rendu (svg, canvas, html)
        loop: true, // Si l'animation doit boucler
        autoplay: true, // Si l'animation doit démarrer dès que la page charge
        path: 'MainScene.json' // Chemin vers ton fichier JSON
    });

    


    const cards = document.querySelectorAll('.difference-card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'scale(1.05)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'scale(1)';
        });
    });


    const features = document.querySelectorAll('.feature-item');
    const chatButton = document.querySelector('.chat-button');

    features.forEach(feature => {
        feature.addEventListener('mouseenter', () => {
            feature.style.transform = 'scale(1.05)';
        });

        feature.addEventListener('mouseleave', () => {
            feature.style.transform = 'scale(1)';
        });
    });

    chatButton.addEventListener('click', () => {
        alert('Fonctionnalité de chat à implémenter');
    });

    // Animation des statistiques
    const statNumbers = document.querySelectorAll('.stat-number');
    
    statNumbers.forEach(statNumber => {
        const number = parseInt(statNumber.textContent.replace('.', ''));
        let start = 0;
        
        const animateNumber = () => {
            if (start < number) {
                start += Math.ceil(number / 100);
                statNumber.textContent = start.toLocaleString('fr-FR');
                requestAnimationFrame(animateNumber);
            } else {
                statNumber.textContent = number.toLocaleString('fr-FR');
            }
        };

        animateNumber();
    });

    const authors = document.querySelectorAll('.author');
    const ctaButtons = document.querySelectorAll('.primary-btn, .secondary-btn');

    authors.forEach(author => {
        author.addEventListener('mouseenter', () => {
            author.style.transform = 'scale(1.05)';
        });

        author.addEventListener('mouseleave', () => {
            author.style.transform = 'scale(1)';
        });
    });

    ctaButtons.forEach(button => {
        button.addEventListener('mouseenter', () => {
            button.style.transform = 'translateY(-5px)';
        });

        button.addEventListener('mouseleave', () => {
            button.style.transform = 'translateY(0)';
        });

        button.addEventListener('click', () => {
            if (button.classList.contains('primary-btn')) {
                alert('Vous allez être redirigé vers notre service de contact.');
            } else {
                alert('Vous allez consulter nos tarifs.');
            }
        });
    });

    const toolLabels = document.querySelectorAll('.tool-label');
    const filizLogo = document.querySelector('.filiz-logo');

    toolLabels.forEach(label => {
        label.addEventListener('mouseenter', () => {
            label.style.transform = 'scale(1.1)';
            label.style.backgroundColor = '#5D6FFF';
            label.style.color = 'white';
        });

        label.addEventListener('mouseleave', () => {
            label.style.transform = 'scale(1)';
            label.style.backgroundColor = 'white';
            label.style.color = '#5D6FFF';
        });
    });

    filizLogo.addEventListener('mouseenter', () => {
        filizLogo.style.transform = 'translate(-50%, -50%) scale(1.1)';
        filizLogo.style.backgroundColor = '#4a5bff';
    });

    filizLogo.addEventListener('mouseleave', () => {
        filizLogo.style.transform = 'translate(-50%, -50%) scale(1)';
        filizLogo.style.backgroundColor = '#5D6FFF';
    });

  

// menu bergeur 



});
