
$(document).ready(function(){
    /**
     * Fonction qui ouvre la modal pour l'ajouter
     */
    $(document).on('click','#add',function(e){
        e.preventDefault();
        $('#intro').text("Faire une demande d'assistance");
        $('#confirm').text('ENREGISTRER');

        $('#objet').val('');
        $('#message').val('');
        

     
        $('#action').val('add');
        $('#new').modal();
    });

   

    $(document).on('submit', '#newFrom', function (e) {
        e.preventDefault();
        
         
        var   act = $('.newBtn').html();
           
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();

         

 // Récupération des valeurs des champs
        var objet = $form.find('input[name="objet"]').val();
        var telephone = $form.find('input[name="telephone"]').val();
        var message = $form.find('input[name="message"]').val();
        
            
        if (objet!=='' ){
              
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                contentType: false,
                processData: false,
                beforeSend: function () {
                   
                    $('.newBtn').html('<i class="fa fa-refresh fa-spin fa-2x"></i>').prop('disabled', true);
                },
                success: function (json) {
                   
                    if (json.statuts === 0){
                       $('#new').modal('hide');
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

    $(document).on('click','.trash', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        swal({
                title: "Etes vous sûr?",
                text: "Voulez-vous supprimer ce message ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#153C4A",
                confirmButtonText: "Oui, valider!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url : "assistanceDeleteMessage",
                        data: 'id='+id,
                        datatype: 'json',
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                                toastr.success(json.mes,'Succès!');
                                window.location.reload();
                            } else {
                                toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown){}
                    });
                }
            });
    });



    $(document).on('click','#adds',function(e){
        e.preventDefault();
        $('#intros').text("Envoyer un message");
        $('#confirms').text('ENREGISTRER');

        $('#messages').val('');
        

     
        $('#actions').val('add');
        $('#news').modal();
    });

    $(document).on('submit', '#newFroms', function(e) {
    e.preventDefault();
    
    var $form = $(this);
    var formData = new FormData($form[0]);
    var btn = $('.newBtn');
    var btnHtml = btn.html();

    if (!$form.find('[name="messages"]').val().trim()) {
        toastr.error('Le message ne peut pas être vide', 'Erreur');
        return;
    }

    $.ajax({
        type: 'POST',
        url: $form.attr('action'),
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function() {
            btn.html('<i class="fa fa-refresh fa-spin"></i>').prop('disabled', true);
        },
        success: function(response) {
            if (response.statuts === 0) {
                $('#news').modal('hide');
                toastr.success(response.mes, 'Succès');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                toastr.error(response.mes, 'Oups!');
            }
        },
        error: function(xhr) {
            toastr.error('Erreur de communication avec le serveur', 'Oups!');
            console.error(xhr.responseText);
        },
        complete: function() {
            btn.html(btnHtml).prop('disabled', false);
        }
    });
});

   

});