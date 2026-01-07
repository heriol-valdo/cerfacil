
$(document).ready(function(){
    /**
     * Fonction qui ouvre la modal pour l'ajouter
     */
    $(document).on('click','#add',function(e){
        e.preventDefault();
        $('#intro').text('AJOUTER UN EMPLOYEUR');
        $('#confirm').text('ENREGISTRER');
        $('#nomE').val('');
        $('#typeE').val('');
        $('#specifiqueE').val('');
        $('#totalE').val('');
        $('#siretE').val('');
        $('#codeaE').val('');
        $('#codeiE').val('');
        $('#rueE').val('');
        $('#voieE').val('');
        $('#complementE').val('');
        $('#postalE').val('');
        $('#communeE').val('');
        $('#emailE').val('');
        $('#numeroE').val('');   
        $('#idopco').val('');   
        $('#action').val('add');
        $('#new').modal();
    });

    
    $(document).on('click','.edit',function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var nomE = $(this).attr('data-nomE'); 
        var typeE = $(this).attr('data-typeE');
        var specifiqueE = $(this).attr('data-specifiqueE');
        var totalE = $(this).attr('data-totalE');
        var siretE = $(this).attr('data-siretE');
        var codeaE = $(this).attr('data-codeaE');
        var codeiE = $(this).attr('data-codeiE');
        var rueE = $(this).attr('data-rueE');
        var voieE = $(this).attr('data-voieE');
        var complementE = $(this).attr('data-complementE');
        var postalE = $(this).attr('data-postalE');
        var communeE = $(this).attr('data-communeE');
        var emailE = $(this).attr('data-emailE');
        var numeroE = $(this).attr('data-numeroE');
        var idopco = $(this).attr('data-idopco');



        
        
        $('#idElement').val(id);
        $('#nomE').val(nomE);
        $('#typeE').val(typeE);
        $('#specifiqueE').val(specifiqueE);
        $('#totalE').val(totalE);
        $('#siretE').val(siretE);
        $('#codeaE').val(codeaE);
        $('#codeiE').val(codeiE);
        $('#rueE').val(rueE);
        $('#voieE').val(voieE);
        $('#complementE').val(complementE);
        $('#postalE').val(postalE);
        $('#communeE').val(communeE);
        $('#emailE').val(emailE);
        $('#numeroE').val(numeroE);
        $('#idopco').val(idopco);

        $('#action').val('edit');
        $('#intro').text('MODIFIER UN EMPLOYEUR');
        $('#confirm').text('ENREGISTRER');
        $('#new').modal();
    });

    $(document).on('submit', '#newFrom', function (e) {
        e.preventDefault();
        
        var $form = $(this),
            id = $('#idElement').val(),

            nomE = $('#nomE').val(),
            typeE = $('#typeE').val();
            specifiqueE = $('#specifiqueE').val();
            totalE = $('#totalE').val();
            siretE = $('#siretE').val();
            codeaE = $('#codeaE').val();
            codeiE = $('#codeiE').val();
            rueE = $('#rueE').val();
            voieE = $('#voieE').val();
            complementE = $('#complementE').val();
            postalE = $('#postalE').val();
            communeE = $('#communeE').val();
            emailE = $('#emailE').val();
            numeroE = $('#numeroE').val();
            idopco = $('#idopco').val();

            action = $('#action').val(),
            act = $('.newBtn').html(),
            url = $(this).attr('action');


         
            
        if (nomE!=='' &&  emailE!==''){
            if (postalE !== '') {
                if (/^\d{5}$/.test(postalE))  {
                } else {
                    toastr.error("Le code postal de l'employeur  doit contenir exactement 5 chiffres.", 'Oups!');
                    return;
                }
            } 
            if (numeroE !== '') {
                if (/^\d{10}$/.test(numeroE)) {    } else {
                   
                    toastr.error("Veuillez remplir exactement 10 chiffres sur le numéro de téléphone de l'employeur.", 'Oups!');
                    return;
                }
            }

            var pattern = /^[0-9]{14}$/;
            if (siretE !== '') {
                if (!pattern.test(siretE)) {
                    toastr.error("Le numéro SIRET doit contenir exactement 14 chiffres", 'Oups!');
                    return;
                } 
            } 

            
            if (codeaE !== '') {
                if (codeaE.length > 6) {
                    toastr.error("Le code NAF ne doit pas dépasser 6 caractères.", 'Oups!');
                    return;
                }
            } 
    
            


            $.ajax({
                type: 'post',
                url: url,
                data: {
                    nomE: nomE,
                    typeE: typeE,
                    specifiqueE: specifiqueE,
                    totalE: totalE,
                    siretE: siretE,
                    codeaE: codeaE,
                    codeiE: codeiE,
                    rueE: rueE,
                    voieE: voieE,
                    complementE: complementE,
                    postalE: postalE,
                    communeE: communeE,
                    emailE: emailE,
                    numeroE: numeroE,
                    idopco: idopco,
                     
                    id: id,
                    action: action
                  },

                  
                  
                datatype: 'json',
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
                text: "L'employeur va être supprimée.",
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

  $(document).on('click','.send', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        swal({
                title: "Etes vous sûr?",
                text: "L'employeur va recevoir le formulaire pour remplir ses informations.",
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