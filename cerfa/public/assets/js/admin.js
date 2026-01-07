
$(document).ready(function(){
    
    $(document).on('submit', '#newForm', function (e) {
        e.preventDefault();
        var $form = $(this),
            url = $(this).attr('action'),
            nom = $('#nom').val(),
            prenom = $('#prenom').val(),
            email = $('#email').val(),

            adresse = $('#adresse').val(),
            postal = $('#postal').val(),
            ville= $('#ville').val(),
            telephone = $('#telephone').val(),
          
            id = $('#idElement').val(),
            action = $('#action').val(),
            act = $('.newBtn').html();
        if (nom != ''  && prenom != ''  && url != '') {
            $.ajax({
                type: 'post',
                url: url,
                data: 'nom='+nom+'&prenom='+prenom+'&email='+email+'&adresse='+adresse+'&postal='+postal+'&ville='+ville+'&telephone='+telephone+'&action='+action+'&id='+id,
                datatype: 'json',
                beforeSend: function () {
                    $('.newBtn').html('<i class="fa fa-refresh fa-spin fa-2x"></i>').prop('disabled', true);
                },
                success: function (json) {
                    if (json.statuts === 0) {
                        $('.newModal').modal('hide');
                        showAlert($form,1,json.mes);
                        toastr.success(json.mes,'Succès!');
                        window.location.reload();
                        //$('.newModal').modal('hide');
                    } else {
                        showAlert($form,2,json.mes);
                        toastr.error(json.mes,'Oups!');
                    }
                },
                complete: function () {
                    $('.newBtn').html(act).prop('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {}
            });
        } else {
            toastr.error('Veuillez remplir correctement tous les champs requis','Oups!');
            showAlert($form,2,'Veuillez remplir correctement tous les champs requis');
        }
    });
    $(document).on('click','.edit', function (e) {
        e.preventDefault();
        var email = $(this).data('email'),
            prenom = $(this).data('prenom'),
            nom = $(this).data('nom'),

            adresse = $(this).data('adresse'),
            postal = $(this).data('postal'),
            ville= $(this).data('ville'),
            telephone = $(this).data('telephone'),

            id = $(this).data('id');
       
      
        $('#email').val(email);
        $('#prenom').val(prenom);
        $('#nom').val(nom);

       $('#adresse').val(adresse),
       $('#postal').val(postal),
       $('#ville').val(ville),
       $('#telephone').val(telephone),


        $('#idElement').val(id);
        $('#action').val('edit');
        $('.titleForm').html("MODIFIER L'ADMINISTRATEUR");
        $('.newBtn').html("ENREGISTRER");
        $('.newModal').modal({backdrop: 'static'});
    });
    $(document).on('click','.new', function (e) {
        e.preventDefault();
       
       
        $('#email').val('');
        $('#prenom').val('');
        $('#nom').val('');

        $('#adresse').val(''),
        $('#postal').val(''),
        $('#ville').val(''),
        $('#telephone').val(''),


        $('#idElement').val('');
        $('#action').val('add');
        $('.titleForm').html("NOUVEL ADMINISTRATEUR");
        $('.newBtn').html("ENREGISTRER");
        $('.newModal').modal({backdrop: 'static'});
    });
    $(document).on('click','.delete', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        swal({
            title: "Etes vous sûr?",
            text: "L'administrateur va être supprimé",
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
                    url: url,
                    data: 'id='+id,
                    datatype: 'json',
                    beforeSend: function () {},
                    success: function (json) {
                        if (json.statuts === 0) {
                            toastr.success(json.mes,'Succès!');
                            window.location.reload();
                        } else {
                            toastr.error(json.mes,'Oups!');
                        }
                    },
                    complete: function () {},
                    error: function (jqXHR, textStatus, errorThrown) {}
                });
            }
        });
    });
    $(document).on('click','.activate', function (e) {
        e.preventDefault();
        var val,
            url = $(this).data('url'),
            id = $(this).data('id'),
            etat = $(this).data('etat');
        if(etat==1){
            mess = "L'administrateur va être désactivé";
            val = 0;


        }else{
            mess = "L'administrateur va être activé";
            val = 1;
        }
        swal({
                title: "Etes vous sûr?",
                text: mess,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#CD730F",
                confirmButtonText: "Oui, valider!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url: url,
                        data: 'id='+id+'&etat='+val,
                        datatype: 'json',
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                                toastr.success(json.mes,'Succès!');
                                window.location.reload();
                            } else {
                                toastr.error(json.mes,'Oups!');
                            }
                        },
                        complete: function () {
                            dismiss_waitMe();
                            window.location.reload();
                        },
                        error: function (jqXHR, textStatus, errorThrown) {}
                    });
                }
            });
    });
    
    $(document).on('click','.editPhoto', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        $('#idPhoto').val(id);
        $('.photoModal').modal({backdrop: 'static'});

    });
  
    $(document).on('click','.reset', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        swal({
                title: "Réinitialisation?",
                text: "le mot de passse de l'administrateur sera réinitialisé et il recevra un Email contenant le nouveau mot de passe",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#CD730F",
                confirmButtonText: "Oui, réinitialiser!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url: url,
                        data: 'id='+id,
                        datatype: 'json',
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                            window.location.reload();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                                toastr.success(json.mes,'Succès!');
                                window.location.reload();
                            } else {
                                toastr.error(json.mes,'Oups!');
                            }
                        },
                        
                        
                        error: function (jqXHR, textStatus, errorThrown) {}
                    });
                }
            });
    });

    $(document).on('submit', '#photoForm', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
    
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            contentType: false,
            processData: false,
            dataType: 'text', // Spécifiez le type de données comme texte
            beforeSend: function () {
                $('.photoBtn').html('<i class="fa fa-refresh fa-spin fa-2x"></i>').prop('disabled', true);
            },
            success: function (text) {
                if (text.indexOf("Succès") !== -1) {
                    showAlert($form, 1, text);
                    toastr.success(text, 'Succès!');
                    window.location.reload();
                } else {
                    showAlert($form, 2, text);
                    toastr.error(text, 'Oups!');
                }
            },
            complete: function () {
                $('.photoBtn').html('ENREGISTRER').prop('disabled', false);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR + textStatus + errorThrown);
            }
        });
    });
    

});