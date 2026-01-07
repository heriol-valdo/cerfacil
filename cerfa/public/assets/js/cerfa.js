$(document).ready(function(){
    /**
     * Fonction qui ouvre la modal pour l'ajouter
     */
    $(document).on('click','#add',function(e){
        e.preventDefault();
        $('#introplus').text('AJOUTER UN Cerfa');
        $('#confirmplus').text('ENREGISTRER');
        $('#idemployeur').val('');
        $('#idformation').val('');
        $('#emailAA').val('');
        $('#new').modal();
    });

 	$(document).on('click', '.dropdown-toggle', function() {
        var tableResponsive = $('.table-responsive');
        console.log('ok');
        tableResponsive.toggleClass('menu-open');
    	});

    // Gérer le clic en dehors du menu pour le fermer
    $(document).on('click', function(event) {
        var isClickInsideMenu = $(event.target).closest('.btn-group').length > 0;
        if (!isClickInsideMenu) {
            $('.table-responsive.menu-open').removeClass('menu-open');
        }
    });

   

    // Capter l'événement de click sur n'importe quel lien dans le menu déroulant
    $('.dropdown-menu').on('click', 'li > a', function() {
        // Fermer le menu déroulant
        $('.btn-group .dropdown-toggle').dropdown('toggle');

        // Réinitialiser le statut du menu déroulant
        dropdownOpen = false;

        // Réactiver le scroll de la table
        $('.scrollable-table').css('overflow-y', 'auto');

        // Désactiver le scroll de la liste d'actions
        $('.dropdown-menu').css('overflow-y', 'hidden');
    });

    $(document).on('submit', '#newForm1', function (e) {
        e.preventDefault();
        
        var $form = $(this),
            idemployeur = $('#idemployeur').val(),
            idformation = $('#idformation').val(),
            emailAA = $('#emailAA').val(),
            act = $('.newBtn').html(),
            url = $(this).attr('action');
     
        if (idemployeur!==''  ){
            if(emailAA ===''){
                toastr.error("Veuillez remplir l'email de l'apprenant ",'Oups!');
                return;
            }
           
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    idemployeur: idemployeur,
                    idformation: idformation,
                    emailAA: emailAA
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

    
    $(document).on('click','.edit',function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var idemployeur = $(this).attr('data-idemployeur'); 
        var idformation = $(this).attr('data-idformation'); 
        
        var nomA = $(this).attr('data-nomA');
        var nomuA = $(this).attr('data-nomuA');
        var prenomA = $(this).attr('data-prenomA');
        var sexeA = $(this).attr('data-sexeA');
        var naissanceA = $(this).attr('data-naissanceA');
        var departementA = $(this).attr('data-departementA');
        var communeNA = $(this).attr('data-communeNA');
        var nationaliteA = $(this).attr('data-nationaliteA');
        var regimeA = $(this).attr('data-regimeA');
        var situationA = $(this).attr('data-situationA');
        var titrePA = $(this).attr('data-titrePA');
        var derniereCA = $(this).attr('data-derniereCA');
        var securiteA = $(this).attr('data-securiteA');
        var intituleA = $(this).attr('data-intituleA');
        var titreOA = $(this).attr('data-titreOA');
        var declareSA = $(this).attr('data-declareSA');
        var declareHA = $(this).attr('data-declareHA');
        var declareRA = $(this).attr('data-declareRA');
        var rueA = $(this).attr('data-rueA');
        var voieA = $(this).attr('data-voieA');
        var complementA = $(this).attr('data-complementA');
        var postalA = $(this).attr('data-postalA');
        var communeA = $(this).attr('data-communeA');
        var numeroA = $(this).attr('data-numeroA');
        var emailA = $(this).attr('data-emailA');


        var nomR = $(this).attr('data-nomR');
        var prenomR = $(this).attr('data-prenomR');
        var emailR = $(this).attr('data-emailR');
        var rueR = $(this).attr('data-rueR');
        var voieR = $(this).attr('data-voieR');
        var complementR = $(this).attr('data-complementR');
        var postalR = $(this).attr('data-postalR');
        var communeR = $(this).attr('data-communeR');


        var nomM = $(this).attr('data-nomM');
        var prenomM = $(this).attr('data-prenomM');
        var naissanceM = $(this).attr('data-naissanceM');
        var securiteM = $(this).attr('data-securiteM');
        var emailM = $(this).attr('data-emailM');
        var emploiM = $(this).attr('data-emploiM');
        var diplomeM = $(this).attr('data-diplomeM');
        var niveauM = $(this).attr('data-niveauM');


        var nomM1 = $(this).attr('data-nomM1');
        var prenomM1 = $(this).attr('data-prenomM1');
        var naissanceM1 = $(this).attr('data-naissanceM1');
        var securiteM1 = $(this).attr('data-securiteM1');
        var emailM1 = $(this).attr('data-emailM1');
        var emploiM1 = $(this).attr('data-emploiM1');
        var diplomeM1 = $(this).attr('data-diplomeM1');
        var niveauM1 = $(this).attr('data-niveauM1');
        
       
        
        
        
        var travailC = $(this).attr('data-travailC');
        var modeC = $(this).attr('data-modeC');
        var derogationC = $(this).attr('data-derogationC');
        var numeroC = $(this).attr('data-numeroC');
        var conclusionC = $(this).attr('data-conclusionC');
        var debutC = $(this).attr('data-debutC');
        var finC = $(this).attr('data-finC');
        var avenantC = $(this).attr('data-avenantC');
        var executionC = $(this).attr('data-executionC');
        var dureC = $(this).attr('data-dureC');
        var dureCM = $(this).attr('data-dureCM');
        var typeC = $(this).attr('data-typeC');
        var rdC = $(this).attr('data-rdC');
        var raC = $(this).attr('data-raC');
        var rpC = $(this).attr('data-rpC');
        var rsC = $(this).attr('data-rsC');

        var rdC1 = $(this).attr('data-rdC1');
        var raC1 = $(this).attr('data-raC1');
        var rpC1 = $(this).attr('data-rpC1');
        var rsC1 = $(this).attr('data-rsC1');

        var rdC2 = $(this).attr('data-rdC2');
        var raC2 = $(this).attr('data-raC2');
        var rpC2 = $(this).attr('data-rpC2');
        var rsC2 = $(this).attr('data-rsC2');

        var salaireC = $(this).attr('data-salaireC');
        var caisseC = $(this).attr('data-caisseC');
        var logementC = $(this).attr('data-logementC');
        var avantageC = $(this).attr('data-avantageC');
        var autreC = $(this).attr('data-autreC');

        
        var lieuO = $(this).attr('data-lieuO');
        var priveO = $(this).attr('data-priveO');
        var attesteO = $(this).attr('data-attesteO');
        
        
        $('#idElement').val(id);
        $('#idemployeurs').val(idemployeur);
        $('#idformations').val(idformation);
      
        $('#nomA').val(nomA);
        $('#nomuA').val(nomuA);
        $('#prenomA').val(prenomA);
        $('#sexeA').val(sexeA);
        $('#naissanceA').val(naissanceA);
        $('#departementA').val(departementA);
        $('#communeNA').val(communeNA);
        $('#nationaliteA').val(nationaliteA);
        $('#regimeA').val(regimeA);
        $('#situationA').val(situationA);
        $('#titrePA').val(titrePA);
        $('#derniereCA').val(derniereCA);
        $('#securiteA').val(securiteA);
        $('#intituleA').val(intituleA);
        $('#titreOA').val(titreOA);
        $('#declareSA').val(declareSA);
        $('#declareHA').val(declareHA);
        $('#declareRA').val(declareRA);
        $('#rueA').val(rueA);
        $('#voieA').val(voieA);
        $('#complementA').val(complementA);
        $('#postalA').val(postalA);
        $('#communeA').val(communeA);
        $('#numeroA').val(numeroA);
        $('#emailA').val(emailA);

        $('#nomR').val(nomR);
        $('#prenomR').val(prenomR);
        $('#emailR').val(emailR);
        $('#rueR').val(rueR);
        $('#voieR').val(voieR);
        $('#complementR').val(complementR);
        $('#postalR').val(postalR);
        $('#communeR').val(communeR);



        $('#nomM').val(nomM);
        $('#prenomM').val(prenomM);
        $('#naissanceM').val(naissanceM);
        $('#securiteM').val(securiteM);
        $('#emailM').val(emailM);
        $('#emploiM').val(emploiM);
        $('#diplomeM').val(diplomeM);
        $('#niveauM').val(niveauM);


        $('#nomM1').val(nomM1);
        $('#prenomM1').val(prenomM1);
        $('#naissanceM1').val(naissanceM1);
        $('#securiteM1').val(securiteM1);
        $('#emailM1').val(emailM1);
        $('#emploiM1').val(emploiM1);
        $('#diplomeM1').val(diplomeM1);
        $('#niveauM1').val(niveauM1);




        $('#travailC').val(travailC);
        $('#modeC').val(modeC);
        $('#derogationC').val(derogationC);
        $('#numeroC').val(numeroC);
        $('#conclusionC').val(conclusionC);
        $('#debutC').val(debutC);
        $('#finC').val(finC);
        $('#avenantC').val(avenantC);
        $('#executionC').val(executionC);
        $('#dureC').val(dureC);
        $('#dureCM').val(dureCM);
        $('#typeC').val(typeC);
        $('#rdC').val(rdC);
        $('#raC').val(raC);
        $('#rpC').val(rpC);
        $('#rsC').val(rsC);
        $('#rdC1').val(rdC1);
        $('#raC1').val(raC1);
        $('#rpC1').val(rpC1);
        $('#rsC1').val(rsC1);

        $('#rdC2').val(rdC2);
        $('#raC2').val(raC2);
        $('#rpC2').val(rpC2);
        $('#rsC2').val(rsC2);

        $('#salaireC').val(salaireC);
        $('#caisseC').val(caisseC);
        $('#logementC').val(logementC);
        $('#avantageC').val(avantageC);
        $('#autreC').val(autreC);

        $('#lieuO').val(lieuO);
        $('#priveO').val(priveO);
        $('#attesteO').val(attesteO);

        
        $('#action').val('edit');
        $('#intro').text('MODIFIER UN Cerfa');
        $('#confirm').text('ENREGISTRER');
        $('#host').modal();
    });

    $(document).on('submit', '#newFrom', function (e) {
        e.preventDefault();
        
        var $form = $(this),
            id = $('#idElement').val(),

            idemployeur = $('#idemployeurs').val(),
            idformation = $('#idformations').val(),

            nomA = $('#nomA').val();
            nomuA = $('#nomuA').val();
            prenomA = $('#prenomA').val();
            sexeA = $('#sexeA').val();
            naissanceA = $('#naissanceA').val();
            departementA = $('#departementA').val();
            communeNA = $('#communeNA').val();
            nationaliteA = $('#nationaliteA').val();
            regimeA = $('#regimeA').val();
            situationA = $('#situationA').val();
            titrePA = $('#titrePA').val();
            derniereCA = $('#derniereCA').val();
            securiteA = $('#securiteA').val();
            intituleA = $('#intituleA').val();
            titreOA = $('#titreOA').val();
            declareSA = $('#declareSA').val();
            declareHA = $('#declareHA').val();
            declareRA = $('#declareRA').val();
            rueA =$('#rueA').val();
            voieA = $('#voieA').val();
            complementA = $('#complementA').val();
            postalA = $('#postalA').val();
            communeA = $('#communeA').val();
            numeroA = $('#numeroA').val();
            emailA = $('#emailA').val();


            nomR = $('#nomR').val();
            prenomR = $('#prenomR').val();
            emailR = $('#emailR').val();
            rueR =$('#rueR').val();
            voieR = $('#voieR').val();
            complementR = $('#complementR').val();
            postalR = $('#postalR').val();
            communeR = $('#communeR').val();



            nomM = $('#nomM').val();
            prenomM = $('#prenomM').val();
            naissanceM = $('#naissanceM').val();
            securiteM = $('#securiteM').val();
            emailM = $('#emailM').val();
            emploiM = $('#emploiM').val();
            diplomeM = $('#diplomeM').val();
            niveauM = $('#niveauM').val();

            nomM1 = $('#nomM1').val();
            prenomM1 = $('#prenomM1').val();
            naissanceM1 = $('#naissanceM1').val();
            securiteM1 = $('#securiteM1').val();
            emailM1 = $('#emailM1').val();
            emploiM1 = $('#emploiM1').val();
            diplomeM1 = $('#diplomeM1').val();
            niveauM1 = $('#niveauM1').val();



            travailC=$('#travailC').val();
            modeC=$('#modeC').val();
            derogationC=$('#derogationC').val();
            numeroC=$('#numeroC').val();
            conclusionC=$('#conclusionC').val();
            debutC=$('#debutC').val();
            finC=$('#finC').val();
            avenantC=$('#avenantC').val();
            executionC=$('#executionC').val();
            dureC=$('#dureC').val();
            dureCM=$('#dureCM').val();
            typeC=$('#typeC').val();
            rdC=$('#rdC').val();
            raC=$('#raC').val();
            rpC=$('#rpC').val();
            rsC=$('#rsC').val();

            rdC1=$('#rdC1').val();
            raC1=$('#raC1').val();
            rpC1=$('#rpC1').val();
            rsC1=$('#rsC1').val();

            rdC2=$('#rdC2').val();
            raC2=$('#raC2').val();
            rpC2=$('#rpC2').val();
            rsC2=$('#rsC2').val();

            salaireC=$('#salaireC').val();
            caisseC=$('#caisseC').val();
            logementC=$('#logementC').val();
            avantageC=$('#avantageC').val();
            autreC=$('#autreC').val();

            lieuO = $('#lieuO').val();
            priveO = $('#priveO').val();
            attesteO = $('#attesteO').val();


            action = $('#action').val(),
            act = $('.newBtn').html(),
            url = $(this).attr('action');


            
            
        if (idemployeur!==''  ){

            if(emailA ===''){
                toastr.error("Veuillez remplir l'email de l'apprenant ",'Oups!');
                return;
            }

            if(isNaN(salaireC.trim())){
                toastr.error("Veuillez remplir correctement le champ salaire ",'Oups!');
                return;
            }

            if (!/^\d{13,15}$/.test(securiteA)) {
                toastr.options.timeOut = 2000;
                toastr.error("Le numéro de sécurité sociale de l'apprenti doit contenir entre 13 et 15 caractères.",'Oups!');
                return ;
            }

            if (securiteM && !/^\d{13,15}$/.test(securiteM)) {
                toastr.error("Le numéro de sécurité sociale du maitre de stage 1 doit contenir entre 13 et 15 caractères.",'Oups!');
                return ;
            }
            if (securiteM1 && !/^\d{13,15}$/.test(securiteM1)) {
                toastr.error("Le numéro de sécurité sociale du maitre de stage 2 doit contenir entre 13 et 15 caractères.",'Oups!');
                return ;
            }
            if (numeroA && !/^\d{10}$/.test(numeroA)) {
                toastr.error("Veuillez remplir exactement 10 chiffres sur le numéro de l'apprenti.",'Oups!');
                return ;
            }
            if (!/^\d{5}$/.test(postalA)) {
                toastr.error("Le code postal de l'apprenti doit contenir exactement 5 chiffres.",'Oups!');
                return ;
            }
            if (!/^\d{5}$/.test(postalR)) {
                toastr.error("Le code postal du représentant légal doit contenir exactement 5 chiffres.",'Oups!');
                return ;
            }

            if (dureCM && parseInt(dureCM, 10) > 59) {
                toastr.error("La durée CM ne doit pas dépasser 59.", 'Oups!');
                return;
            }
           
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    idemployeur:idemployeur,
                    idformation:idformation,  
                    nomA: nomA,
                    nomuA: nomuA,
                    prenomA: prenomA,
                    sexeA: sexeA,
                    naissanceA: naissanceA,
                    departementA: departementA,
                    communeNA: communeNA,
                    nationaliteA: nationaliteA,
                    regimeA: regimeA,
                    situationA: situationA,
                    titrePA: titrePA,
                    derniereCA: derniereCA,
                    securiteA: securiteA,
                    intituleA: intituleA,
                    titreOA: titreOA,
                    declareSA: declareSA,
                    declareHA: declareHA,
                    declareRA: declareRA,
                    rueA: rueA,
                    voieA: voieA,
                    complementA: complementA,
                    postalA: postalA,
                    communeA: communeA,
                    numeroA: numeroA,
                    emailA: emailA,

                    nomR: nomR,
                    prenomR: prenomR,
                    emailR: emailR,
                    rueR: rueR,
                    voieR: voieR,
                    complementR: complementR,
                    postalR: postalR,
                    communeR: communeR,




                    nomM: nomM,
                    prenomM: prenomM,
                    naissanceM: naissanceM,
                    securiteM: securiteM,
                    emailM: emailM,
                    emploiM: emploiM,
                    diplomeM: diplomeM,
                    niveauM: niveauM,

                    nomM1: nomM1,
                    prenomM1: prenomM1,
                    naissanceM1: naissanceM1,
                    securiteM1: securiteM1,
                    emailM1: emailM1,
                    emploiM1: emploiM1,
                    diplomeM1: diplomeM1,
                    niveauM1: niveauM1,

                    travailC: travailC,
                    modeC: modeC,
                    derogationC: derogationC,
                    numeroC: numeroC,
                    conclusionC: conclusionC,
                    debutC: debutC,
                    finC: finC,
                    avenantC: avenantC,
                    executionC: executionC,
                    dureC: dureC,
                    dureCM: dureCM,
                    typeC: typeC,
                    rdC: rdC,
                    raC: raC,
                    rpC: rpC,
                    rsC: rsC,
                    rdC1: rdC1,
                    raC1: raC1,
                    rpC1: rpC1,
                    rsC1: rsC1,

                    rdC2: rdC2,
                    raC2: raC2,
                    rpC2: rpC2,
                    rsC2: rsC2,

                    salaireC: salaireC,
                    caisseC: caisseC,
                    logementC: logementC,
                    avantageC: avantageC,
                    autreC: autreC,
                    
                    lieuO: lieuO,
                    priveO: priveO,
                    attesteO: attesteO,
                    
                    id: id,
                    action: action
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

    $(document).on('click','.trash', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        swal({
                title: "Etes vous sûr?",
                text: "Le cerfa va être supprimée.",
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
                        error: function(jqXHR, textStatus, errorThrown){
                            toastr.error(jqXHR+ textStatus+ errorThrown,'Oups2!');
                        }
                    });
                }
            });
    });

    $(document).on('click','.gene', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');

          
        swal({
                title: "Etes vous sûr?",
                text: "voulez vous generer le cerfa.",
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
                                console.log(json);
                                toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            console.log(jqXHR+ textStatus+ errorThrown);
                        }
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
                text: "Le formulaire cerfa va être envoyer a l'apprenant.",
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
                        error: function(jqXHR, textStatus, errorThrown){}
                    });
                }
            });
    });

    $(document).on('click','.sendEmployeur', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        swal({
                title: "Etes vous sûr?",
                text: "Le formulaire cerfa va être envoyer a l'employeur.",
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
                        error: function(jqXHR, textStatus, errorThrown){}
                    });
                }
            });
    });

    $(document).on('click','.sendOpco', function (e) {
        e.preventDefault();
        var  id = $(this).data('id');
        $('#idCerfa').val(id);
        $('.cerfaModal').modal({backdrop: 'static'});

    });

    $(document).on('submit', '#FormCerfa', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();

        swal({
                title: "Etes vous sûr?",
                text: "Le cerfa va être envoyer a l'opco responsable.",
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
                        data: data,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                                showAlert($form,1,json.mes);
                                toastr.success(json.mes,'Succès!');
                                window.location.reload();
                            } else {
                         	       toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            toastr.error(jqXHR + textStatus + errorThrown, 'Oups2!');
                            showAlert($form,1,json);
                        }
                    });
                }
            });
    });

    $(document).on('click','.sendOpcoConvention', function (e) {
        e.preventDefault();
        var  id = $(this).data('id');
        $('#idConvention').val(id);
        $('.conventionModal').modal({backdrop: 'static'});

    });

    $(document).on('submit', '#FormConvention', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();

        swal({
                title: "Etes vous sûr?",
                text: "La convention va être envoyer a l'opco responsable.",
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
                        data: data,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                                showAlert($form,1,json.mes);
                                toastr.success(json.mes,'Succès!');
                                window.location.reload();
                            } else {
                         	       toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            toastr.error(jqXHR + textStatus + errorThrown, 'Oups2!');
                        }
                    });
                }
            });
    });

    $(document).on('click','.sendOpcoFacture', function (e) {
        e.preventDefault();
        var  id = $(this).data('id');
        $('#idFacture').val(id);
        $('.FactureModal').modal({backdrop: 'static'});

    });

    $(document).on('submit', '#FormFacture', function (e) {
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
            beforeSend: function () {
                run_waitMe(current_effect,loadingText);
            },
            complete: function () {
                dismiss_waitMe();
            },
            success: function (json) {
                if (json.statuts === 0) {
                    showAlert($form,1,json.mes);
                    toastr.success(json.mes,'Succès!');
                    window.location.reload();
                } else {
                        toastr.error(json.mes,'Oups!');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error(jqXHR + textStatus + errorThrown, 'Oups2!');
            }
        });

        // swal({
        //         title: "Etes vous sûr?",
        //         text: "La Facture va être envoyer a l'opco responsable.",
        //         type: "warning",
        //         showCancelButton: true,
        //         confirmButtonColor: "#153C4A",
        //         confirmButtonText: "Oui, valider!",
        //         cancelButtonText: "Annuler",
        //         closeOnConfirm: true
        //     },
        //     function(isConfirm){
        //         if (isConfirm) {
                  
        //         }
        //     });
    });

    $(document).on('submit', '#FormFactureSend', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();

        swal({
                title: "Etes vous sûr?",
                text: "La Facture va être envoyer a l'opco responsable.",
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
                        data: data,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            run_waitMe(current_effect,loadingText);
                        },
                        complete: function () {
                            dismiss_waitMe();
                        },
                        success: function (json) {
                            if (json.statuts === 0) {
                                showAlert($form,1,json.mes);
                                toastr.success(json.mes,'Succès!');
                                window.location.reload();
                            } else {
                                    toastr.error(json.mes,'Oups!');
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            toastr.error(jqXHR + textStatus + errorThrown, 'Oups2!');
                        }
                    });
                  
                }
            });
    });

    $(document).on('click','.afficheCerfa', function (e) {
        e.preventDefault();
        $('.cerfaModalView').modal({backdrop: 'static'});

        var url = $(this).data('url');

            const documentContainer = document.getElementById("documentContainerCerfa");
            const fileType = url.split('.').pop().toLowerCase();
            documentContainer.innerHTML = '';
            
            if (fileType === 'pdf') {
                const pdfViewer = document.createElement('iframe');
                pdfViewer.src = url;
                pdfViewer.width = "100%";
                pdfViewer.height = "600px";
                documentContainer.appendChild(pdfViewer);
            } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType)) {
                const imageViewer = document.createElement('img');
                imageViewer.src = url;
                imageViewer.style.maxWidth = "100%";
                imageViewer.style.height = "auto";
                documentContainer.appendChild(imageViewer);
            } else {
                documentContainer.innerHTML = 'Unsupported file type.';
            }
        
    });

    $(document).on('click','.afficheConvention', function (e) {
        e.preventDefault();
        $('.conventionModalView').modal({backdrop: 'static'});

        var url = $(this).data('url');

            const documentContainer = document.getElementById("documentContainer");
            const fileType = url.split('.').pop().toLowerCase();
            documentContainer.innerHTML = '';
            
            if (fileType === 'pdf') {
                const pdfViewer = document.createElement('iframe');
                pdfViewer.src = url;
                pdfViewer.width = "100%";
                pdfViewer.height = "600px";
                documentContainer.appendChild(pdfViewer);
            } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType)) {
                const imageViewer = document.createElement('img');
                imageViewer.src = url;
                imageViewer.style.maxWidth = "100%";
                imageViewer.style.height = "auto";
                documentContainer.appendChild(imageViewer);
            } else {
                documentContainer.innerHTML = 'Unsupported file type.';
            }
        
    });

    $(document).on('click','.afficheFacture', function (e) {
        e.preventDefault();
        $('.factureModalView').modal({backdrop: 'static'});

        var url = $(this).data('url');
        var id = $(this).data('id');
        $('#idFactureSend').val(id);
        $('#urlsend').val(url);

            const documentContainer = document.getElementById("documentContainerFacture");
            const fileType = url.split('.').pop().toLowerCase();
            documentContainer.innerHTML = '';
            
            if (fileType === 'pdf') {
                const pdfViewer = document.createElement('iframe');
                pdfViewer.src = url;
                pdfViewer.width = "100%";
                pdfViewer.height = "600px";
                documentContainer.appendChild(pdfViewer);
            } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType)) {
                const imageViewer = document.createElement('img');
                imageViewer.src = url;
                imageViewer.style.maxWidth = "100%";
                imageViewer.style.height = "auto";
                documentContainer.appendChild(imageViewer);
            } else {
                documentContainer.innerHTML = 'Unsupported file type.';
            }
        
    });

    
    $(document).on('click','.sendSignatureEntreprise', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        swal({
                title: "Etes vous sûr?",
                text: "Le formulaire  cerfa va être envoyer a l'employeur pour signature.",
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
                        dataType: 'json',
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
                        error: function(jqXHR, textStatus, errorThrown){
                        }
                    });
                }
            });
    });
   

    $(document).on('click','.sendSignatureApprenti', function (e) {
        e.preventDefault();
        var url = $(this).data('url'),
            id = $(this).data('id');
        swal({
                title: "Etes vous sûr?",
                text: "Le formulaire  cerfa va être envoyer a l'apprenti pour signature.",
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
                        data: { id: id },
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
                        error: function(jqXHR, textStatus, errorThrown){
                            console.error('Error:', jqXHR, textStatus, errorThrown); 
                            toastr.error(jqXHR+ textStatus+ errorThrown,'Oups!');
                        }
                    });
                }
            });
    });
});

