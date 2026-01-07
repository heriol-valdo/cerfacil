
$(document).ready(function(){
 
    $(document).on('submit', '#resetFormSend', function (e) {
        e.preventDefault();
        
        var $form = $(this),
            email = $('#email').val();
                   
          
            act = $('.newBtn').html(),
            url = $(this).attr('action');


         
            
        if ( email!==''){

            $.ajax({
                type: 'post',
                url: url,
                data: { 
                    email: email
                  },

                  
                  
                datatype: 'json',
                beforeSend: function () {
                   
                    $('.newBtn').html('<i class="fa fa-refresh fa-spin fa-2x"></i>').prop('disabled', true);
                },
                success: function (json) {
                   
                    if (json.statuts === 0){
                      
                        showAlert($form,1,json.mes);
                        toastr.success(json.mes,'Succès!');
                        window.location.reload();
                    } else {
                        toastr.error(json.mes,'Oups!');
                        showAlert($form,2,json.mes);
                    }
                },
                complete: function () {
                    $('.newBtn').html(act).prop('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.log(jqXHR+ textStatus+ errorThrown);
                }
            });
        }else{
            toastr.error('Veuillez remplir correctement tous les champs requis','Oups!');
            showAlert($form,2,'Veuillez remplir correctement tous les champs requis');
        }

    });

    
    $(document).on('submit', '#resetForm', function (e) {
        e.preventDefault();
        
        var $form = $(this),
            newPassword = $('#newPassword').val(),
            token = $('#token').val(),
            confirmPassword = $('#confirmPassword').val();
                   
          
            act = $('.newBtn').html(),
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


         
            
        if ( newPassword == confirmPassword){
 var criteria = validatePassword(newPassword);

                // Vérification si tous les critères sont respectés
                if (criteria.hasMinLength && criteria.hasUpperCase && criteria.hasNumber && criteria.hasSpecialChar) {

            $.ajax({
                type: 'post',
                url: url,
                data: { 
                    newPassword:  newPassword,
                    confirmPassword:confirmPassword,
                    token :token
                  },

                  
                  
                datatype: 'json',
                beforeSend: function () {
                   
                    $('.newBtn').html('<i class="fa fa-refresh fa-spin fa-2x"></i>').prop('disabled', true);
                },
                success: function (json) {
                   
                    if (json.statuts === 0){
                      
                        showAlert($form,1,json.mes);
                        toastr.success(json.mes,'Succès!');
                         window.location.assign("login");
                    } else {
                        toastr.error(json.mes,'Oups!');
                        showAlert($form,2,json.mes);
                    }
                },
                complete: function () {
                    $('.newBtn').html(act).prop('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.log(jqXHR+ textStatus+ errorThrown);
                }
            });
              } else {
                    // Afficher les critères manquants
                    $('#passwordCriteria').show(); // Affiche la section des critères si non respectés
                    showPasswordCriteria(criteria);
                }



        }else{
            toastr.error("Le nouveau mot de passe doit etre identique a la confirmation svp !!!",'Oups');
            showAlert($form,2,'Le nouveau mot de passe doit etre identique a la confirmation svp !!!');
        }

    });
   

});