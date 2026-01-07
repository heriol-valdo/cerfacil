
$(document).ready(function(){
    /**
     * Fonction qui ouvre la modal pour l'ajouter
     */
    $(document).on('click','#add',function(e){
        e.preventDefault();
        $('#intro').text('AJOUTER UNE FORMATION');
        $('#confirm').text('ENREGISTRER');

        $('#nomF').val('');
        $('#diplomeF').val('');
        $('#intituleF').val('');
        $('#numeroF').val('');
        $('#siretF').val('');
        $('#codeF').val('');
        $('#rnF').val('');
        $('#entrepriseF').val('');
        $('#responsableF').val('');
        $('#prix').val('');
        $('#rueF').val('');
        $('#voieF').val('');
        $('#complementF').val('');
        $('#postalF').val('');
        $('#communeF').val('');

        $('#emailF').val('');
        $('#debutO').val('');
        $('#prevuO').val('');
        $('#dureO').val('');
        $('#nomO').val('');
        $('#numeroO').val('');
        $('#siretO').val('');
        $('#rueO').val('');
        $('#voieO').val('');
        $('#complementO').val('');
        $('#postalO').val('');
        $('#communeO').val('');
     
        $('#action').val('add');
        $('#new').modal();
    });

    /**
     * Fonction qui ouvre la Modal d'edition
     */
    $(document).on('click','.edit',function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
 
        var nomF = $(this).attr('data-nomF');
        var diplomeF = $(this).attr('data-diplomeF');
        var intituleF = $(this).attr('data-intituleF');
        var numeroF = $(this).attr('data-numeroF');
        var siretF = $(this).attr('data-siretF');
        var codeF = $(this).attr('data-codeF');
        var rnF = $(this).attr('data-rnF');
        var entrepriseF = $(this).attr('data-entrepriseF');
        var responsableF = $(this).attr('data-responsableF');
        var prix = $(this).attr('data-prix');
        var rueF = $(this).attr('data-rueF');
        var voieF = $(this).attr('data-voieF');
        var complementF = $(this).attr('data-complementF');
        var postalF = $(this).attr('data-postalF');
        var communeF = $(this).attr('data-communeF');

        var emailF = $(this).attr('data-emailF');
        var debutO = $(this).attr('data-debutO');
        var prevuO = $(this).attr('data-prevuO');
        var dureO = $(this).attr('data-dureO');
        var nomO = $(this).attr('data-nomO');
        var numeroO = $(this).attr('data-numeroO');
        var siretO = $(this).attr('data-siretO');
        var rueO = $(this).attr('data-rueO');
        var voieO = $(this).attr('data-voieO');
        var complementO = $(this).attr('data-complementO');
        var postalO = $(this).attr('data-postalO');
        var communeO = $(this).attr('data-communeO');
      
        
        
        $('#idElement').val(id);


        $('#nomF').val(nomF);
        $('#diplomeF').val(diplomeF);
        $('#intituleF').val(intituleF);
        $('#numeroF').val(numeroF);
        $('#siretF').val(siretF);
        $('#codeF').val(codeF);
        $('#rnF').val(rnF);
        $('#entrepriseF').val(entrepriseF);
        $('#responsableF').val(responsableF);
        $('#prix').val(prix);
        $('#rueF').val(rueF);
        $('#voieF').val(voieF);
        $('#complementF').val(complementF);
        $('#postalF').val(postalF);
        $('#communeF').val(communeF);


        $('#emailF').val(emailF);
        $('#debutO').val(debutO);
        $('#prevuO').val(prevuO);
        $('#dureO').val(dureO);
        $('#nomO').val(nomO);
        $('#numeroO').val(numeroO);
        $('#siretO').val(siretO);
        $('#rueO').val(rueO);
        $('#voieO').val(voieO);
        $('#complementO').val(complementO);
        $('#postalO').val(postalO);
        $('#communeO').val(communeO);

        
        $('#action').val('edit');
        $('#intro').text('MODIFIER UNE FORMATION');
        $('#confirm').text('ENREGISTRER');
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
        var siretF = $form.find('input[name="siretF"]').val();
        var numeroF = $form.find('input[name="numeroF"]').val();
        var intituleF = $form.find('input[name="intituleF"]').val();
        var codeF = $form.find('input[name="codeF"]').val();
        var rnF = $form.find('input[name="rnF"]').val();
        var postalF = $form.find('input[name="postalF"]').val();
        var postalO = $form.find('input[name="postalO"]').val();
        var nomF = $form.find('input[name="nomF"]').val();

            
        if (nomF!=='' ){
            var pattern = /^[0-9]{14}$/;
        
            if (pattern.test(siretF)) {
            } else {
                toastr.error("Le numéro SIRET doit contenir exactement 14 chiffres", 'Oups!');
                return;
            }

            if (numeroF.length !== 8) {
                toastr.error("Le numéro UAI doit contenir exactement 8 caractères.", 'Oups!');
                return;
            }

            if (intituleF.length <= 255) {
            } else {
                toastr.error("L'intitulé précis de la formation  ne doit pas dépasser 255 caractères.", 'Oups!');
                return;
            }

            if (codeF.length <= 8) {
            } else {
                toastr.error("Code du diplôme ou titre visé par l'Alternant  ne doit pas dépasser 8 caractères.", 'Oups!');
                return;
            }
            if (rnF.length <= 9) {
            } else {
                toastr.error("Code RNCP  ne doit pas dépasser 9 caractères.", 'Oups!');
                return;
            }
            if (postalF !== '') {
                if (/^\d{5}$/.test(postalF))  {
                } else {
                    toastr.error("Le code postal de la formation  doit contenir exactement 5 chiffres.", 'Oups!');
                    return;
                }
            } else {
                toastr.error("Veuillez remplir le code postal de la formation .", 'Oups!');
                return;
            }

            if (postalO !== '') {
                if (/^\d{5}$/.test(postalO))  {
                } else {
                    toastr.error("Le code postal de l'organisme de formation  doit contenir exactement 5 chiffres.", 'Oups!');
                    return;
                }
            } 

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
                text: "La formation va être supprimée.",
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