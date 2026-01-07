$(document).ready(function(){
    $(document).on('submit', '#changePasswordForm', function (e) {
        e.preventDefault();
        var $form = $(this);
        var oldPassword = $('#oldPassword').val(),
            newPassword = $('#newPassword').val(),
            confirmPassword = $('#confirmPassword').val(),
            url = $(this).attr('action');

        // Fonction pour valider les critères du mot de passe
        function validatePassword(password) {
            var hasUpperCase = /[A-Z]/.test(password);
            var hasNumber = /[0-9]/.test(password);
            var hasSpecialChar = /[\W_]/.test(password);
            var hasMinLength = password.length >= 12;

            return {
                hasUpperCase: hasUpperCase,
                hasNumber: hasNumber,
                hasSpecialChar: hasSpecialChar,
                hasMinLength: hasMinLength
            };
        }

        // Fonction pour afficher les critères du mot de passe avec indicateurs visuels
        function showPasswordCriteria(criteria) {
            var message = '<ul>';
            message += criteria.hasMinLength ? '<li style="color:green;">✔ Au moins 12 caractères</li>' 
                                             : '<li style="color:red;">✘ Au moins 12 caractères</li>';
            message += criteria.hasUpperCase ? '<li style="color:green;">✔ Une majuscule</li>' 
                                             : '<li style="color:red;">✘ Une majuscule</li>';
            message += criteria.hasNumber ? '<li style="color:green;">✔ Un chiffre</li>' 
                                          : '<li style="color:red;">✘ Un chiffre</li>';
            message += criteria.hasSpecialChar ? '<li style="color:green;">✔ Un caractère spécial</li>' 
                                               : '<li style="color:red;">✘ Un caractère spécial</li>';
            message += '</ul>';
            showAlert($form, 2, message);  // Affichage des critères de sécurité
        }

        if (oldPassword !== '' && newPassword !== '' && confirmPassword !== '') {
            if (newPassword === confirmPassword) {
                var criteria = validatePassword(newPassword);

                // Vérification si tous les critères sont respectés
                if (criteria.hasMinLength && criteria.hasUpperCase && criteria.hasNumber && criteria.hasSpecialChar) {
                    $.ajax({
                        type: 'post',
                        url: url,
                        data: {
                            oldpassword: oldPassword,
                            newpassword: newPassword,
                            confirmpassword: confirmPassword
                        },
                        datatype: 'json',
                        beforeSend: function () {
                            $('.sendBtn').text('Chargement en cours...').prop('disabled', true);
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                                // Si le statut est 0, cacher les critères
                                $('#passwordCriteria').hide(); // Cache la section des critères
                                
                               
                                toastr.success(json.mes, 'Succès');
                               
                                $('#oldPassword').val('');
                                $('#newPassword').val('');
                                $('#confirmPassword').val('');
                                window.location.reload();
                            } else {
                                toastr.error(json.mes, 'Oups');
                            }
                        },
                        complete: function () {
                            $('.sendBtn').text('Modifier').prop('disabled', false);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR + textStatus + errorThrown);
                        }
                    });
                } else {
                    // Afficher les critères manquants
                    $('#passwordCriteria').show(); // Affiche la section des critères si non respectés
                    showPasswordCriteria(criteria);
                }
            } else {
                toastr.error("Le nouveau mot de passe doit être identique à la confirmation.", 'Oups');
            }
        } else {
            toastr.error('Veuillez remplir tous les champs', 'Oups');
        }
    });
});
