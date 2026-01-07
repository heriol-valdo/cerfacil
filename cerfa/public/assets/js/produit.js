$(document).ready(function(){
    /**
     * Fonction qui ouvre la modal pour l'ajouter
     */

    //pour les dossiers 
    $(document).on('click', '#add', function(e) {
        e.preventDefault();
        $('#intro').text('Debloquer Ce Produit');
        $('#confirm').text('ENREGISTRER');
    
        var subscriptionPrice = parseFloat($(this).attr('data-prixAbonement')) || 0;
        var productId = $(this).attr('data-idProduit');
        var dossierPrice = parseFloat($(this).attr('data-prixDossier')) || 0;
        var quantity = parseFloat($('#quantite').val()) || 0;
    
        if (isNaN(subscriptionPrice) || isNaN(dossierPrice)) {
            console.error('Invalid price data');
            return;
        }
    
        var totalDossier = (dossierPrice * quantity).toFixed(2);
        var totalSubscription = (subscriptionPrice * 1).toFixed(2);
        var totalInvoice = (parseFloat(totalDossier) + parseFloat(totalSubscription)).toFixed(2);
    
        $('#prix_abonement').val(subscriptionPrice);
        $('#prix_dossier').val(dossierPrice);
        $('#totalDossier').val(totalDossier);
        $('#totalAbonement').val(totalSubscription);
        $('#totalFacture').val(totalInvoice);
        $('#idProduit').val(productId);
        $('#action').val('add');
    
        $('#new').modal();
    });

    $('#selectAbonemet').on('change', function() {
        var selectedValue = $(this).val(),
            totalDossier = parseFloat($('#totalDossier').val()) || 0,
            prix_abonement = parseFloat($('#prix_abonement').val()) || 0;
        
        if (selectedValue === '1') {
            calculttotalAbonement = number_format(prix_abonement * 1, 2, '.', '');
        } else if (selectedValue === '2') {
            calculttotalAbonement = number_format(prix_abonement * 12, 2, '.', '');
        }
        
        $('#totalAbonement').val(calculttotalAbonement);
        
        var totalFacture = number_format(parseFloat(calculttotalAbonement) + totalDossier, 2, '.', '');
        $('#tva').val(totalFacture*0.2);
        $('#totalFacture').val(totalFacture*1.2);
    });


    $('#quantite').on('input', function() {
        var inputValue = parseFloat($(this).val()) || 0,
            totalAbonement = parseFloat($('#totalAbonement').val()) || 0,
            prix_dossier = parseFloat($('#prix_dossier').val()) || 0;
        
        if (inputValue < 0) {
            $(this).val(0);
            inputValue = 0;
        }
        
        var calculttotalDossier = number_format(prix_dossier * inputValue, 2, '.', '');
        $('#totalDossier').val(calculttotalDossier);
        
        var totalFacture = number_format(parseFloat(calculttotalDossier) + totalAbonement, 2, '.', '');
        $('#tva').val(totalFacture*0.2);
        $('#totalFacture').val(totalFacture*1.2);
    });



    // avec claude pour les dossiers Ajout 
$(document).on('submit', '#newFrom', function (e) {
    e.preventDefault();
    
    var $form = $(this),
        id = $('#idElement').val(),
        idProduit = $('#idProduit').val(),
        prix_abonement = parseFloat($('#prix_abonement').val()),
        prix_dossier = parseFloat($('#prix_dossier').val()),
        selectAbonemet = parseInt($('#selectAbonemet').val()),
        quantite = parseInt($('#quantite').val()),
        action = $('#action').val(),
        act = $('.newBtn').html(),
        url = $(this).attr('action');

    // Calcul côté client (sera vérifié côté serveur)
    var number = (selectAbonemet==1)? 1: 12; 
    var totalAbonement = prix_abonement * number;
    var totalDossier = prix_dossier * quantite;
    var total = (totalAbonement + totalDossier);
    var tva = total*0.20;
    var totalFacture = (totalAbonement + totalDossier + tva);
    


    if (quantite && totalFacture > 0) {
        var stripe = Stripe('pk_test_51Q0jJtP2q6i0JROS80Uh7vxaA1Wf8bLqLLdvJmdEqU9sNI5z2yY2rLTMJFTT5qLdAxGfu16bvEVJNig8EQaVELWN00JkpAj1Za');
        var elements = stripe.elements();
        var card;

        Swal.fire({
            title: 'Paiement Sécurisé',
            html: `
                <div style="margin-bottom: 20px;">
                    <img src="https://lgx-solution.fr/cerfa/public/assets/img/stripe.png" alt="Secure Payment" style="width:100%; margin-bottom: 15px;">
                    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
                        <h6 style="margin: 0 0 10px 0; color: #333;">Récapitulatif:</h6>
                        <div style="text-align: left;">
                            <div>Prix abonnement: <strong>${totalAbonement.toFixed(2)} €</strong></div>
                            <div>Prix dossier: <strong>${totalDossier.toFixed(2)} €</strong></div>
                            <div>TVA(20%): <strong>${tva.toFixed(2)}</strong></div>
                            <hr style="margin: 10px 0;">
                            <div style="font-size: 18px; font-weight: bold; color: #007bff;">
                                Total à payer: <strong>${totalFacture.toFixed(2)} €</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="card-element" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"></div>
                <div id="card-errors" role="alert" style="color: #dc3545; font-size: 14px; margin-top: 10px;"></div>
                <div class="loader" style="display:none;">
                    <div style="text-align: center; padding: 20px;">
                        <i class="fa fa-spinner fa-spin fa-2x"></i>
                        <div style="margin-top: 10px;"></div>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: `Payer ${totalFacture.toFixed(2)} €`,
            cancelButtonText: 'Annuler',
            width: '500px',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                // Créer l'élément de carte
                card = elements.create('card', {
                    style: {
                        base: {
                            fontSize: '16px',
                            color: '#424770',
                            '::placeholder': {
                                color: '#aab7c4',
                            },
                        },
                        invalid: {
                            color: '#9e2146',
                        },
                    },
                });
                card.mount('#card-element');

                // Gérer les erreurs en temps réel
                card.on('change', function(event) {
                    var displayError = document.getElementById('card-errors');
                    if (event.error) {
                        displayError.textContent = event.error.message;
                    } else {
                        displayError.textContent = '';
                    }
                });
            },
            preConfirm: () => {
                return new Promise((resolve) => {
                    // Afficher le loader
                    $('.loader').show();
                    $('.swal2-confirm').prop('disabled', true);
                    
                    // Créer le token
                    stripe.createToken(card).then(function(result) {
                        if (result.error) {
                            // Erreur de validation de carte
                            $('.loader').hide();
                            $('.swal2-confirm').prop('disabled', false);
                            document.getElementById('card-errors').textContent = result.error.message;
                            resolve(false);
                        } else {
                            // Token créé avec succès, envoyer au serveur
                            $.ajax({
                                type: 'POST',
                                url: url,
                                data: {
                                    prix_abonement: prix_abonement,
                                    prix_dossier: prix_dossier,
                                    quantite: quantite,
                                    selectAbonemet: selectAbonemet,
                                    totalAbonement: totalAbonement,
                                    totalDossier: totalDossier,
                                    totalFacture: totalFacture,
                                    id: id,
                                    idProduit: idProduit,
                                    action: action,
                                    stripe_token: result.token.id
                                },
                                dataType: 'json',
                                success: function (json) {
                                    $('.loader').hide();
                                    if (json.statuts === 0) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Paiement réussi!',
                                            text: json.mes,
                                            timer: 3000
                                        }).then(() => {
                                            $('#new').modal('hide');
                                            window.location.reload();
                                        });
                                        resolve(true);
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Erreur',
                                            text: json.mes
                                        });
                                        resolve(false);
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    $('.loader').hide();
                                    console.error('Erreur AJAX:', textStatus, errorThrown);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Erreur système',
                                        text: 'Une erreur inattendue s\'est produite. Veuillez réessayer.'
                                    });
                                    resolve(false);
                                }
                            });
                        }
                    });
                });
            }
        });
    } else {
        toastr.error('Veuillez remplir correctement tous les champs requis', 'Oups!');
        showAlert($form, 2, 'Veuillez remplir correctement tous les champs requis');
    }
});


    





    // pour les facture avec claude ajout 
    
    $(document).on('click', '#adds', function(e) {
        e.preventDefault();
        $('#intros').text('Debloquer Ce Produit');
        $('#confirms').text('ENREGISTRER');
    
      
        var productId = $(this).attr('data-idProduit');
        var dossierPrice = parseFloat($(this).attr('data-prixDossier')) || 0;
        var quantity = parseFloat($('#quantites').val()) || 0;
    
        if (isNaN(dossierPrice)) {
            console.error('Invalid price data');
            return;
        }
    
        var totalDossier = (dossierPrice * quantity).toFixed(2);
        var totalInvoice = (parseFloat(totalDossier) + 0).toFixed(2);
    
        $('#prix_dossiers').val(dossierPrice);
        $('#totalDossiers').val(totalDossier);
     
        $('#totalFactures').val(totalInvoice);
        $('#idProduits').val(productId);
        $('#action').val('add');
    
        $('#news').modal();
    });
    

    $('#quantites').on('input', function() {
        var inputValue = parseFloat($(this).val()) || 0,
            prix_dossier = parseFloat($('#prix_dossiers').val()) || 0;
        
        if (inputValue < 0) {
            $(this).val(0);
            inputValue = 0;
        }
        
        var calculttotalDossier = number_format(prix_dossier * inputValue, 2, '.', '');
        $('#totalDossiers').val(calculttotalDossier);
        
        var totalFacture = number_format(parseFloat(calculttotalDossier) + 0, 2, '.', '');
        $('#tvas').val(totalFacture*0.2);
        $('#totalFactures').val(totalFacture*1.2);
    });

    $(document).on('submit', '#newFromFacture', function (e) {
    e.preventDefault();
    
    var $form = $(this),
        id = $('#idElements').val(),
        idProduits = $('#idProduits').val(),
        prix_dossiers = parseFloat($('#prix_dossiers').val()),
        quantites = parseInt($('#quantites').val()),
        action = $('#action').val(),
        act = $('.newBtn').html(),
        url = $(this).attr('action');

    // Calcul côté client pour affichage (sera recalculé côté serveur)
    var totalDossiers = prix_dossiers * quantites;
    var tva = totalDossiers*0.20;
    var totalFactures = totalDossiers + tva;
   

    console.log('Produit ID:', idProduits);
    console.log('Total calculé:', totalFactures);
        
    if (quantites && totalFactures && prix_dossiers) {

        var stripe = Stripe('pk_test_51Q0jJtP2q6i0JROS80Uh7vxaA1Wf8bLqLLdvJmdEqU9sNI5z2yY2rLTMJFTT5qLdAxGfu16bvEVJNig8EQaVELWN00JkpAj1Za');
        var elements = stripe.elements();
        var card;

        Swal.fire({
            title: 'Paiement Sécurisé',
            html: `
                <div> 
                    <img src="https://lgx-solution.fr/cerfa/public/assets/img/stripe.png" alt="Secure Payment" style="width:100%; margin-bottom: 20px;"> 
                </div>
                <div class="payment-summary" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
                    <h4>Résumé de la commande</h4>
                    <p><strong>Quantité:</strong> ${quantites}</p>
                    <p><strong>Prix unitaire:</strong> ${prix_dossiers.toFixed(2)} €</p>
                    <p><strong>TVA(20%):</strong> ${tva.toFixed(2)} €</p>
                    <p><strong>Total:</strong> ${totalFactures.toFixed(2)} €</p>
                </div>
                <div id="card-element" style="margin-bottom: 20px;"></div>
                <div class="loader" style="display:none;">
                    <div style="text-align: center; padding: 20px;">
                        
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Payer ' + totalFactures.toFixed(2) + ' €',
            cancelButtonText: 'Annuler',
            width: '500px',
            didOpen: () => {
                card = elements.create('card', {
                    style: {
                        base: {
                            fontSize: '16px',
                            color: '#424770',
                            '::placeholder': {
                                color: '#aab7c4',
                            },
                        },
                    },
                });
                card.mount('#card-element');

                // Gestion des erreurs de carte en temps réel
                card.addEventListener('change', function(event) {
                    var displayError = document.getElementById('card-errors');
                    if (event.error) {
                        if (displayError) {
                            displayError.textContent = event.error.message;
                        }
                    } else {
                        if (displayError) {
                            displayError.textContent = '';
                        }
                    }
                });
            },
            preConfirm: () => {
                return new Promise((resolve) => {
                    $('.loader').show();
                    
                    stripe.createToken(card).then(function(result) {
                        if (result.error) {
                            $('.loader').hide();
                            toastr.error(result.error.message, 'Erreur de carte');
                            resolve(false);
                        } else {
                            // Envoyer directement au backend avec le token
                            $.ajax({
                                type: 'POST',
                                url: url,
                                data: {
                                    prix_dossiers: prix_dossiers,
                                    quantites: quantites,
                                    totalDossiers: totalDossiers,
                                    totalFactures: totalFactures,
                                    id: id,
                                    idProduits: idProduits,
                                    action: action,
                                    stripe_token: result.token.id
                                },
                                dataType: 'json',
                                success: function (json) {
                                    $('.loader').hide();
                                    if (json.statuts === 0) {
                                         Swal.fire({
                                            icon: 'success',
                                            title: 'Paiement facture réussi!',
                                            text: json.mes,
                                            timer: 3000
                                        }).then(() => {
                                            $('#news').modal('hide');
                                            window.location.reload();
                                        });
                                      
                                        showAlert($form, 1, json.mes);
                                        toastr.success(json.mes, 'Succès!');
                                        setTimeout(() => {
                                            window.location.reload();
                                        }, 1500);
                                        resolve(true);
                                    } else {
                                        toastr.error(json.mes, 'Erreur de paiement');
                                        showAlert($form, 2, json.mes);
                                        resolve(false);
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    $('.loader').hide();
                                    console.error('Erreur AJAX:', textStatus, errorThrown);
                                    toastr.error('Erreur de communication avec le serveur', 'Erreur');
                                    resolve(false);
                                }
                            });
                        }
                    });
                });
            }
        });
        
    } else {
        toastr.error('Veuillez remplir correctement tous les champs requis', 'Champs manquants');
        showAlert($form, 2, 'Veuillez remplir correctement tous les champs requis');
    }
});



   


     /**    Fonction qui ouvre la modal pour recharger
     */

     // pour les dossiers


     
     function getCurrentDate() {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    

    $(document).on('click','#edit',function(e){
        e.preventDefault();

        var id = $(this).attr('data-id');
        
        var quantiteuplode = parseInt($(this).attr('data-quantite')) || 0; 
        var subscriptionPrice = parseFloat($(this).attr('data-prixAbonement')) || 0;
        var productId = $(this).attr('data-idProduit');
        var dossierPrice = parseFloat($(this).attr('data-prixDossier')) || 0;
        var quantity = parseFloat($('#quantiterecharge').val()) || 0;


        var dateDebut = $(this).attr('data-dateDebut'); 
        var dateFin = $(this).attr('data-dateFin');
    
        if (isNaN(subscriptionPrice) || isNaN(dossierPrice)) {
            console.error('Invalid price data');
            return;
        }
    
        var totalDossier = (dossierPrice * quantity).toFixed(2);
        var totalSubscription = (subscriptionPrice * 1).toFixed(2);
        var totalInvoice = (parseFloat(totalDossier) + parseFloat(totalSubscription)).toFixed(2);
    
        $('#prix_abonementrecharge').val(subscriptionPrice);
        $('#prix_dossierrecharge').val(dossierPrice);
        $('#totalDossierrecharge').val(totalDossier);
        $('#totalAbonementrecharge').val(totalSubscription);
      
        $('#idProduitRecharge').val(productId);

        $('#datedebutRecharge').val(dateDebut);
        $('#datefinRecharge').val(dateFin);
        


        $('#quantiteuplodeRecharge').val(quantiteuplode);
        $('#idElementRecharge').val(id);


        const dateCourante = getCurrentDate();
        if(dateCourante < dateFin){
            const divabonnement = document.getElementById('divabonnement');
            const diverror = document.getElementById('diverror');
            diverror.style.display = 'flex';
            divabonnement.style.display = 'none';

            $('#totalFacturerecharge').val(totalDossier);

        }else{
            const divabonnement = document.getElementById('divabonnement');
            const diverror = document.getElementById('diverror');
            diverror.style.display = 'none';
            divabonnement.style.display = 'flex';
            $('#totalFacturerecharge').val(totalInvoice);
        }


        var totalFacture = parseFloat($('#totalFacturerecharge').val()) || 0;
        if (totalFacture === 0) {
            $('#confirmsDisabledtotal').prop('disabled', true);
        } else {
            $('#confirmsDisabledtotal').prop('disabled', false);
        }

        const stockRecharge = document.getElementById('stockRecharge');
        stockRecharge.innerHTML='Quantite de dossier(il vous reste '+quantiteuplode+') En stock';
     
        
        $('#actionRecharge').val('edit');
        $('#recharge').modal();
    });

    $('#selectAbonemetrecharge').on('change', function() {
        var selectedValue = $(this).val(),
            totalDossier = parseFloat($('#totalDossierrecharge').val()) || 0,
            prix_abonement = parseFloat($('#prix_abonementrecharge').val()) || 0;
        
        if (selectedValue === '1') {
            calculttotalAbonement = number_format(prix_abonement * 1, 2, '.', '');
        } else if (selectedValue === '2') {
            calculttotalAbonement = number_format(prix_abonement * 12, 2, '.', '');
        }
        
        $('#totalAbonementrecharge').val(calculttotalAbonement);
        
        var totalFacture = number_format(parseFloat(calculttotalAbonement) + totalDossier, 2, '.', '');
        $('#tvarecharge').val(totalFacture*0.2);
        $('#totalFacturerecharge').val(totalFacture*1.2);
    });


    $('#quantiterecharge').on('input', function() {
        var inputValue = parseFloat($(this).val()) || 0,
             date_fin = $('#datefinRecharge').val(),
            totalAbonement = parseFloat($('#totalAbonementrecharge').val()) || 0,
            prix_dossier = parseFloat($('#prix_dossierrecharge').val()) || 0;

        const dateCourante = getCurrentDate();
        
        if (inputValue < 0) {
            $(this).val(0);
            inputValue = 0;
        }
        
        var calculttotalDossier = number_format(prix_dossier * inputValue, 2, '.', '');
        $('#totalDossierrecharge').val(calculttotalDossier);
        
        if(dateCourante < date_fin){
            $('#tvarecharge').val(calculttotalDossier*0.2);
            $('#totalFacturerecharge').val(calculttotalDossier*1.2);
        }else{
            var totalFacture = number_format(parseFloat(calculttotalDossier) + totalAbonement, 2, '.', '');
            $('#tvarecharge').val(totalFacture*0.2);
            $('#totalFacturerecharge').val(totalFacture*1.2);
        }

        var totalFacture = parseFloat($('#totalFacturerecharge').val()) || 0;
        if (totalFacture === 0) {
            $('#confirmsDisabledtotal').prop('disabled', true);
        } else {
            $('#confirmsDisabledtotal').prop('disabled', false);
        }
       
    });


    $(document).on('submit', '#rechargeFrom', function (e) {
    e.preventDefault();
    
    var $form = $(this),
        id = $('#idElementRecharge').val(),
        idProduit = $('#idProduitRecharge').val(),
        prix_abonement = parseFloat($('#prix_abonementrecharge').val()),
        prix_dossier = parseFloat($('#prix_dossierrecharge').val()),
        selectAbonemet = parseInt($('#selectAbonemetrecharge').val()),
        quantite = parseInt($('#quantiterecharge').val()) || 0,
        quantiteuplodeRecharge = parseInt($('#quantiteuplodeRecharge').val()) || 0,
        date_debut = $('#datedebutRecharge').val(),
        date_fin = $('#datefinRecharge').val(),
        total = parseInt(quantite + quantiteuplodeRecharge),
        action = $('#actionRecharge').val(),
        act = $('.newBtn').html(),
        url = $(this).attr('action');

    // Calcul côté client (sera vérifié côté serveur)
    const dateCourante = getCurrentDate();
    let number;
    if(dateCourante < date_fin){
       number = 0; 
    }else{
        number = (selectAbonemet == 1) ? 1 : 12; 
    }
    var totalAbonement = prix_abonement * number;
    var totalDossier = prix_dossier * quantite;
    var totalBase = (totalAbonement + totalDossier);
    var tva = totalBase * 0.20;
    var totalFacture = (totalAbonement + totalDossier + tva);

    if (quantite !== '' && totalFacture !== '') {
        var stripe = Stripe('pk_test_51Q0jJtP2q6i0JROS80Uh7vxaA1Wf8bLqLLdvJmdEqU9sNI5z2yY2rLTMJFTT5qLdAxGfu16bvEVJNig8EQaVELWN00JkpAj1Za');
        var elements = stripe.elements();
        var card;

        Swal.fire({
            title: 'Paiement Sécurisé - Recharge',
            html: `
                <div style="margin-bottom: 20px;">
                    <img src="https://lgx-solution.fr/cerfa/public/assets/img/stripe.png" alt="Secure Payment" style="width:100%; margin-bottom: 15px;">
                    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
                        <h6 style="margin: 0 0 10px 0; color: #333;">Récapitulatif Recharge:</h6>
                        <div style="text-align: left;">
                            <div>Prix abonnement: <strong>${ totalAbonement.toFixed(2)} €</strong></div>
                            <div>Prix dossier : <strong>${totalDossier.toFixed(2)} €</strong></div>
                            <div>TVA (20%): <strong>${tva.toFixed(2)} €</strong></div>
                            <hr style="margin: 10px 0;">
                            <div style="font-size: 18px; font-weight: bold; color: #007bff;">
                                Total à payer: <strong>${totalFacture.toFixed(2)} €</strong>
                            </div>
                        </div>
                    </div>
                    <div style="background-color: #e7f3ff; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 14px;">
                        <strong>Quantité totale:</strong> ${total} dossiers
                    </div>
                </div>
                <div id="card-element" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"></div>
                <div id="card-errors" role="alert" style="color: #dc3545; font-size: 14px; margin-top: 10px;"></div>
                <div class="loader" style="display:none;">
                    <div style="text-align: center; padding: 20px;">
                     
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: `Payer ${totalFacture.toFixed(2)} €`,
            cancelButtonText: 'Annuler',
            width: '500px',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                // Créer l'élément de carte
                card = elements.create('card', {
                    style: {
                        base: {
                            fontSize: '16px',
                            color: '#424770',
                            '::placeholder': {
                                color: '#aab7c4',
                            },
                        },
                        invalid: {
                            color: '#9e2146',
                        },
                    },
                });
                card.mount('#card-element');

                // Gérer les erreurs en temps réel
                card.on('change', function(event) {
                    var displayError = document.getElementById('card-errors');
                    if (event.error) {
                        displayError.textContent = event.error.message;
                    } else {
                        displayError.textContent = '';
                    }
                });
            },
            preConfirm: () => {
                return new Promise((resolve) => {
                    // Afficher le loader
                    $('.loader').show();
                    $('.swal2-confirm').prop('disabled', true);
                    
                    // Créer le token
                    stripe.createToken(card).then(function(result) {
                        if (result.error) {
                            // Erreur de validation de carte
                            $('.loader').hide();
                            $('.swal2-confirm').prop('disabled', false);
                            document.getElementById('card-errors').textContent = result.error.message;
                            resolve(false);
                        } else {
                            // Token créé avec succès, envoyer au serveur
                            $.ajax({
                                type: 'POST',
                                url: url,
                                data: {
                                    prix_abonement: prix_abonement,
                                    prix_dossier: prix_dossier,
                                    quantite: total,
                                    quantiterecharge: quantite,
                                    selectAbonemet: selectAbonemet,
                                    totalAbonement: totalAbonement,
                                    totalDossier: totalDossier,
                                    totalFacture: totalFacture,
                                    date_debut: date_debut,
                                    date_fin: date_fin,
                                    id: id,
                                    idProduit: idProduit,
                                    action: action,
                                    stripe_token: result.token.id
                                },
                                dataType: 'json',
                                success: function (json) {
                                    $('.loader').hide();
                                    if (json.statuts === 0) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Recharge réussie!',
                                            text: json.mes,
                                            timer: 3000
                                        }).then(() => {
                                            $('#recharge').modal('hide'); // Ajustez selon votre modal
                                            window.location.reload();
                                        });
                                        resolve(true);
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Erreur',
                                            text: json.mes
                                        });
                                        resolve(false);
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    $('.loader').hide();
                                    console.error('Erreur AJAX:', textStatus, errorThrown);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Erreur système',
                                        text: 'Une erreur inattendue s\'est produite. Veuillez réessayer.'
                                    });
                                    resolve(false);
                                }
                            });
                        }
                    });
                });
            }
        });
    } else {
        toastr.error('Veuillez remplir correctement tous les champs requis', 'Oups!');
        showAlert($form, 2, 'Veuillez remplir correctement tous les champs requis');
    }
});
    
    


     // pour les factures

     $(document).on('click', '#edits', function(e) {
        e.preventDefault(); 

        var id = $(this).attr('data-id');
        var quantiteuplode = parseInt($(this).attr('data-quantite')) || 0; 
        var productId = $(this).attr('data-idProduit');
        var dossierPrice = parseFloat($(this).attr('data-prixDossier')) || 0;
        var quantity = parseFloat($('#quantitesrecharge').val()) || 0;
    
        if (isNaN(dossierPrice)) {
            console.error('Invalid price data');
            return;
        }
    
        var totalDossier = (dossierPrice * quantity).toFixed(2);
        var totalInvoice = (parseFloat(totalDossier) + 0).toFixed(2);
    
        $('#prix_dossiersrecharge').val(dossierPrice);
        $('#totalDossiersrecharge').val(totalDossier);
     
        $('#totalFacturesrecharge').val(totalInvoice);
        $('#idProduitsrecharge').val(productId);
        $('#actionactioneecharges').val('edit');

        const stockRecharge = document.getElementById('stockRechargesFacture');
        stockRecharge.innerHTML='Quantite de dossier(il vous reste '+quantiteuplode+') En stock <b>*</b>';

        $('#quantiteuploderechargefacture').val(quantiteuplode);
    
        $('#idElementsrecharge').val(id);
        $('#recharges').modal();
    });
    

    $('#quantitesrecharge').on('input', function() {
        var inputValue = parseFloat($(this).val()) || 0,
            prix_dossier = parseFloat($('#prix_dossiersrecharge').val()) || 0;
        
        if (inputValue < 0) {
            $(this).val(0);
            inputValue = 0;
        }
        
        var calculttotalDossier = number_format(prix_dossier * inputValue, 2, '.', '');
        $('#totalDossiersrecharge').val(calculttotalDossier);
        
        var totalFacture = number_format(parseFloat(calculttotalDossier) + 0, 2, '.', '');
        $('#tvasrecharge').val(totalFacture*0.2);
        $('#totalFacturesrecharge').val(totalFacture*1.2);
    });



  $(document).on('submit', '#rechargeFromFacture', function (e) {
    e.preventDefault();
    
    var $form = $(this),
        id = $('#idElementsrecharge').val(),
        idProduits = $('#idProduitsrecharge').val(),
        prix_dossiers = parseFloat($('#prix_dossiersrecharge').val()),
        quantites = parseInt($('#quantitesrecharge').val()) || 0,
        totalDossiers = parseFloat($('#totalDossiersrecharge').val()),
        totalFactures = parseFloat($('#totalFacturesrecharge').val()),
        quantiteuplodeRecharge = parseInt($('#quantiteuploderechargefacture').val()) || 0,
        total = parseInt(quantites + quantiteuplodeRecharge),
        action = $('#actionactioneecharges').val(),
        act = $('.newBtn').html(),
        url = $(this).attr('action');

    // Calcul côté client avec TVA (sera vérifié côté serveur)
    var totalDossiersCalculated = prix_dossiers * quantites;
    var tva = totalDossiersCalculated * 0.20;
    var totalFactureCalculated = totalDossiersCalculated + tva;

    if (quantites > 0 && totalFactures > 0) {
        var stripe = Stripe('pk_test_51Q0jJtP2q6i0JROS80Uh7vxaA1Wf8bLqLLdvJmdEqU9sNI5z2yY2rLTMJFTT5qLdAxGfu16bvEVJNig8EQaVELWN00JkpAj1Za');
        var elements = stripe.elements();
        var card;

        Swal.fire({
            title: 'Paiement Sécurisé - Facture',
            html: `
                <div style="margin-bottom: 20px;">
                    <img src="https://lgx-solution.fr/cerfa/public/assets/img/stripe.png" alt="Secure Payment" style="width:100%; margin-bottom: 15px;">
                    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
                        <h6 style="margin: 0 0 10px 0; color: #333;">Récapitulatif Facture:</h6>
                        <div style="text-align: left;">
                            <div>Prix dossiers : <strong>${totalDossiersCalculated.toFixed(2)} €</strong></div>
                            <div>TVA (20%): <strong>${tva.toFixed(2)} €</strong></div>
                            <hr style="margin: 10px 0;">
                            <div style="font-size: 18px; font-weight: bold; color: #007bff;">
                                Total à payer: <strong>${totalFactureCalculated.toFixed(2)} €</strong>
                            </div>
                        </div>
                    </div>
                    <div style="background-color: #e7f3ff; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 14px;">
                        <strong>Total final:</strong> ${total} dossiers
                    </div>
                </div>
                <div id="card-element" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"></div>
                <div id="card-errors" role="alert" style="color: #dc3545; font-size: 14px; margin-top: 10px;"></div>
                <div class="loader" style="display:none;">
                    <div style="text-align: center; padding: 20px;">
                        
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: `Payer ${totalFactures.toFixed(2)} €`,
            cancelButtonText: 'Annuler',
            width: '500px',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                // Créer l'élément de carte
                card = elements.create('card', {
                    style: {
                        base: {
                            fontSize: '16px',
                            color: '#424770',
                            '::placeholder': {
                                color: '#aab7c4',
                            },
                        },
                        invalid: {
                            color: '#9e2146',
                        },
                    },
                });
                card.mount('#card-element');

                // Gérer les erreurs en temps réel
                card.on('change', function(event) {
                    var displayError = document.getElementById('card-errors');
                    if (event.error) {
                        displayError.textContent = event.error.message;
                    } else {
                        displayError.textContent = '';
                    }
                });
            },
            preConfirm: () => {
                return new Promise((resolve) => {
                    // Afficher le loader
                    $('.loader').show();
                    $('.swal2-confirm').prop('disabled', true);
                    
                    // Créer le token
                    stripe.createToken(card).then(function(result) {
                        if (result.error) {
                            // Erreur de validation de carte
                            $('.loader').hide();
                            $('.swal2-confirm').prop('disabled', false);
                            document.getElementById('card-errors').textContent = result.error.message;
                            resolve(false);
                        } else {
                            // Token créé avec succès, envoyer au serveur
                            // IMPORTANT: Le serveur doit gérer le paiement Stripe avec la clé secrète
                            $.ajax({
                                type: 'POST',
                                url: url,
                                data: {
                                    prix_dossiers: prix_dossiers,
                                    quantites: total,
                                    quantitesrecharge: quantites,
                                    totalDossiers: totalDossiers,
                                    totalFactures: totalFactures,
                                    id: id,
                                    idProduits: idProduits,
                                    action: action,
                                    stripe_token: result.token.id
                                },
                                dataType: 'json',
                                success: function (json) {
                                    $('.loader').hide();
                                    if (json.statuts === 0) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Paiement facture réussi!',
                                            text: json.mes,
                                            timer: 3000
                                        }).then(() => {
                                            $('#factureRecharge').modal('hide'); // Ajustez selon votre modal
                                            window.location.reload();
                                        });
                                        resolve(true);
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Erreur',
                                            text: json.mes
                                        });
                                        resolve(false);
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    $('.loader').hide();
                                    console.error('Erreur AJAX:', textStatus, errorThrown);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Erreur système',
                                        text: 'Une erreur inattendue s\'est produite. Veuillez réessayer.'
                                    });
                                    resolve(false);
                                }
                            });
                        }
                    });
                });
            }
        });
    } else {
        toastr.error('Veuillez remplir correctement tous les champs requis', 'Oups!');
        showAlert($form, 2, 'Veuillez remplir correctement tous les champs requis');
    }
});


});