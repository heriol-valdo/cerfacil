

$(document).ready(function(){
    /**
     * Fonction qui ouvre la modal pour l'ajouter
     */

    //pour les dossiers 
    $(document).on('click', '#typebutton', function(e) {
        e.preventDefault();
      
        $('#typebutton').text('Enregistrer les informations')
        .attr('type', 'submit')
        .removeAttr('id');
        

        $('#nom, #prenom, #email, #telephone, #adresse, #ville, #postal').prop('disabled', false);
    
        const annullerSubmit = document.getElementById('annullerSubmit');
        annullerSubmit.style.display = 'flex';


        const updatePassword = document.getElementById('updatePassword');
        updatePassword.style.display = 'none';
       
    });


 $(document).on('click', '#annullerSubmit', function(e) {
        e.preventDefault();
      
        $('.typebutton')
        .text('Modifier mon compte')
        .attr({
            'type': 'button',
            'id': 'typebutton'
        });
        $('#nom, #prenom, #email, #telephone, #adresse, #ville, #postal').prop('disabled', true);

        const annullerSubmit = document.getElementById('annullerSubmit');
        annullerSubmit.style.display = 'none';


        const updatePassword = document.getElementById('updatePassword');
        updatePassword.style.display = 'block';
       
    
       
    });

   

    $(document).on('submit', '#newFrom', function (e) {

        console.log('ok');
        e.preventDefault();
        
        var $form = $(this),
            id = $('#idElement').val(),
            nom = $('#nom').val(),
            prenom =  $('#prenom').val(),
            email = $('#email').val(),
            telephone = $('#telephone').val(),
            adresse = $('#adresse').val(),
            ville = $('#ville').val(),
            postal = $('#postal').val()
            


            action = $('#action').val(),
            act = $('#typebutton').html(),
            url = $(this).attr('action');


          
            
        if ( nom!=='' && prenom!=='' ){

            $.ajax({
                type: 'post',
                url: url,
                data: {
                  
                    nom:   nom,
                    prenom: prenom,
                    email: email,
                    telephone : telephone,


                    adresse : adresse,
                    ville: ville,
                    postal: postal,
    
                    id: id,
                    action: action
                  },

                  
                  
                datatype: 'json',
                beforeSend: function () {
                   
                    $('#typebutton').html('<i class="fa fa-refresh fa-spin fa-2x"></i>').prop('disabled', true);
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
                    $('#typebutton').html(act).prop('disabled', false);
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
    



});