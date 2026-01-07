
$(document).ready(function(){
   
    
    $(document).on('submit', '#newForm', function (e) {
        e.preventDefault();
        
        var $form = $(this),
            url = $(this).attr('action'),
            nom = $('#nom').val(),
            prenom = $('#prenom').val(),
           
            numero = $('#numero').val(),
            email = $('#email').val(),
            sexe = $('#sexe').val(),
            id = $('#idElement').val(),
            action = $('#action').val(),
            act = $('.newBtn').html();
        if (nom != '' &&  prenom != '' && sexe != '' && numero != '' && url != '') {
            $.ajax({
                type: 'post',
                url: url,
                data: 'nom='+nom+'&prenom='+prenom+'&sexe='+sexe+'&email='+email+'&numero='+numero+'&action='+action+'&id='+id,
                datatype: 'json',
                beforeSend: function () {
                    $('.newBtn').html('<i class="fa fa-refresh fa-spin fa-2x"></i>').prop('disabled', true);
                },
                success: function (json) {
                    if (json.statuts === 0) {
                        showAlert($form,1,json.mes);
                        toastr.success(json.mes,'Succès!');
                        window.location.reload();
                      
                    } else {
                        showAlert($form,2,json.mes);
                        toastr.error(json.mes,'Oups!');
                    }
                },
                complete: function () {
                    $('.newBtn').html(act).prop('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR+ textStatus+ errorThrown);
                }
            });
        } else {
            toastr.error('Veuillez remplir correctement tous les champs requis','Oups!');
            showAlert($form,2,'Veuillez remplir correctement tous les champs requis');
        }
    });
    $(document).on('click','.edit', function (e) {
        e.preventDefault();
        var sexe = $(this).data('sexe'),
            email = $(this).data('email'),
            numero = $(this).data('numero'),
            prenom = $(this).data('prenom'),
            nom = $(this).data('nom'),
            id = $(this).data('id');
       
        $('#sexe').val(sexe);
        $('#email').val(email);
        $('#numero').val(numero);
        $('#prenom').val(prenom);
        $('#nom').val(nom);
        $('#idElement').val(id);
        $('#action').val('edit');
        $('.titleForm').html("MODIFIER L'Alternant");
        $('.newBtn').html("ENREGISTRER");
        $('.newModal').modal({backdrop: 'static', keyboard: false});
    });
    $(document).on('click','.new', function (e) {
        e.preventDefault();
        
        $('#sexe').val('');
        $('#email').val('');
        $('#numero').val('');
        $('#prenom').val('');
        $('#nom').val('');
        $('#idElement').val('');
        $('#action').val('add');
        $('.titleForm').html("NOUVEAU Alternant");
        $('.newBtn').html("ENREGISTRER");
        $('.newModal').modal({backdrop: 'static',  keyboard: false});
    });
    
   
    
    
   

  




    $(document).on('click','.trash', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        swal({
                title: "Etes vous sûr?",
                text: "L'alternant va être supprimée.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#00008B",
                confirmButtonText: "Oui, valider!",
                cancelButtonText: "Annuler",
                closeOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        type: 'post',
                        url : url,
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

   
    

});