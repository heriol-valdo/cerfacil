
$(document).ready(function(){
    /**
     * Fonction qui ouvre la modal pour l'ajouter
     */
    $(document).on('click','#add',function(e){
        e.preventDefault();
        $('#intro').text('AJOUTER UN OPCO');
        $('#confirm').text('ENREGISTRER');

        $('#nom').val('');
        $('#cle').val('');

        $('#lienE').val('');
        $('#lienCe').val('');
        $('#lienCo').val('');
        $('#lienF').val('');
        $('#lienT').val('');
        $('#clid').val('');
        $('#clse').val('');
       
     
        $('#action').val('add');
        $('#new').modal();
    });

    /**
     * Fonction qui ouvre la Modal d'edition
     */
    $(document).on('click','.edit',function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
 
        var nom = $(this).attr('data-nom');
        var cle = $(this).attr('data-cle');

        var lienE = $(this).attr('data-lienE');
        var lienCe = $(this).attr('data-lienCe');
        var lienCo = $(this).attr('data-lienCo');
        var lienF = $(this).attr('data-lienF');
        var lienT = $(this).attr('data-lienT');
        var clid = $(this).attr('data-clid');
        var clse = $(this).attr('data-clse');
        
       
        
        $('#idElement').val(id);


        $('#nom').val(nom);
        $('#cle').val(cle);

        $('#lienE').val(lienE);
        $('#lienCe').val(lienCe);
        $('#lienCo').val(lienCo);
        $('#lienF').val(lienF);
        $('#lienT').val(lienT);
        $('#clid').val(clid);
        $('#clse').val(clse);
       

        
        $('#action').val('edit');
        $('#intro').text('MODIFIER UN OPCO');
        $('#confirm').text('ENREGISTRER');
        $('#new').modal();
    });

    $(document).on('submit', '#newFrom', function (e) {
        e.preventDefault();
        
        var $form = $(this),
            id = $('#idElement').val(),
            nom = $('#nom').val();
            cle = $('#cle').val();

            lienE = $('#lienE').val();
            lienCe = $('#lienCe').val();
            lienCo = $('#lienCo').val();
            lienF = $('#lienF').val();
            lienT = $('#lienT').val();
            clid = $('#clid').val();
            clse = $('#clse').val();
            
           
          
            action = $('#action').val(),
            act = $('.newBtn').html(),
            url = $(this).attr('action');


         
            
        if (nom!=='' && cle!=='' ){

            $.ajax({
                type: 'post',
                url: url,
                data: {
                  
                    nom: nom,
                    cle: cle,

                    lienE :  lienE,
                    lienCe :  lienCe,
                    lienCo: lienCo,
                    lienF: lienF,
                    lienT:  lienT,
                    clid: clid,
                    clse: clse,
                  
                   
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
                text: "L'opco va être supprimée.",
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