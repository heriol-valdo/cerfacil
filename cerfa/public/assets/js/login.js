toastr.options = {
    closeButton: true,
    progressBar: true,
    // positionClass: 'toast-top-right',
    showDuration: 300,
    hideDuration: 1000,
    timeOut: 3000,
    extendedTimeOut: 1000,
    tapToDismiss: false,
    preventDuplicates: true,
    newestOnTop: false,
    showEasing: 'swing',
    hideEasing: 'linear',
    showMethod: 'fadeIn',
    hideMethod: 'fadeOut'
};

function togglePasswordVisibility() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggle-icon');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    const sendBtn = $('.sendBtn');
    const defaultBtnContent = sendBtn.html(); // sauvegarde le contenu par défaut du bouton

    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();

            sendBtn.html('<i class="fa fa-refresh fa-spin fa-2x"></i>').prop('disabled', true);

            const url = loginForm.getAttribute('action');
            const login = document.getElementById('login').value;
            const password = document.getElementById('password').value;

            if (login && password) {
                if (password.length >= 12) {
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `login=${encodeURIComponent(login)}&password=${encodeURIComponent(password)}`
                    })
                    .then(response => response.text())
                    .then(responseText => {
                        sendBtn.html(defaultBtnContent).prop('disabled', false);

                        if (responseText === 'true') {
                            toastr.success("Authentification réussie", 'Succès');
                            setTimeout(() => window.location.assign("home"), 3000);
                        } else if (responseText === "Mauvais mot de passe") {
                            toastr.error("Votre mot de passe est incorrect", 'Oups!');
                        } else if (responseText === "Cet email n'existe pas") {
                            toastr.error("Aucun administrateur n'est attaché à ce login", 'Oups!');
                        } else {
                            toastr.error("Une erreur inattendue s'est produite : " + responseText, 'Oups!');
                        }
                    })
                    .catch(error => {
                        sendBtn.html(defaultBtnContent).prop('disabled', false);
                        console.error(error);
                        toastr.error("Une erreur réseau est survenue.", "Erreur");
                    });
                } else {
                    sendBtn.html(defaultBtnContent).prop('disabled', false);
                    toastr.error('Veuillez remplir au moins 12 caractères pour le mot de passe', 'Oups!');
                }
            } else {
                sendBtn.html(defaultBtnContent).prop('disabled', false);
                toastr.error('Veuillez remplir correctement tous les champs requis', 'Oups!');
            }
        });
    }
});
