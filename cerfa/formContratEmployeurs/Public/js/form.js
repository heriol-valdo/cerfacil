function sendData() {
    buttoncircle();
    
    // Récupération des valeurs des champs de formulaire
    var formData = { 
        nomM : document.getElementById("nomM").value,
        prenomM : document.getElementById("prenomM").value,
        naissanceM : document.getElementById("naissanceM").value,
        securiteM : document.getElementById("securiteM").value,
        emailM : document.getElementById("emailM").value,
        emploiM : document.getElementById("emploiM").value,
        diplomeM : document.getElementById("diplomeM").value,
        niveauM : document.getElementById("niveauM").value,

        nomM1 : document.getElementById("nomM1").value,
        prenomM1 : document.getElementById("prenomM1").value,
        naissanceM1 : document.getElementById("naissanceM1").value,
        securiteM1 : document.getElementById("securiteM1").value,
        emailM1 : document.getElementById("emailM1").value,
        emploiM1 : document.getElementById("emploiM1").value,
        diplomeM1 : document.getElementById("diplomeM1").value,
        niveauM1 : document.getElementById("niveauM1").value,

        travailC:document.getElementById("travailC").value,
        modeC:document.getElementById("modeC").value,
        derogationC:document.getElementById("derogationC").value,
        numeroC:document.getElementById("numeroC").value,
       conclusionC:document.getElementById("conclusionC").value,
        debutC:document.getElementById("debutC").value,
        finC:document.getElementById("finC").value,
        avenantC:document.getElementById("avenantC").value,

        executionC:document.getElementById("executionC").value,
        dureC:document.getElementById("dureC").value,
        dureCM:document.getElementById("dureCM").value,
        typeC:document.getElementById("typeC").value,

        rdC:document.getElementById("rdC").value,
        raC:document.getElementById("raC").value,
        rpC:document.getElementById("rpC").value,
        rsC:document.getElementById("rsC").value,

        rdC1:document.getElementById("rdC1").value,
        raC1:document.getElementById("raC1").value,
        rpC1:document.getElementById("rpC1").value,
        rsC1:document.getElementById("rsC1").value,

        rdC2:document.getElementById("rdC2").value,
        raC2:document.getElementById("raC2").value,
        rpC2:document.getElementById("rpC2").value,
        rsC2:document.getElementById("rsC2").value,

        salaireC:document.getElementById("salaireC").value,
        caisseC:document.getElementById("caisseC").value,
        logementC:document.getElementById("logementC").value,
        avantageC:document.getElementById("avantageC").value,
        autreC:document.getElementById("autreC").value,

        lieuO : "",
        priveO : document.getElementById("priveO").value,
        attesteO : "oui"

        

      
    };

    // Liste des champs obligatoires
    var requiredFields = [
        nomM, prenomM,naissanceM,securiteM,emailM,emploiM,diplomeM,niveauM,rdC,raC,rpC,rsC,travailC,modeC, conclusionC,debutC,finC, 
        executionC,dureC,dureCM,typeC,salaireC,caisseC, priveO
      
    ];

    // Validation des champs requis
    for (var key of requiredFields) {
        if (formData[key] === "") {
            toastr.options.timeOut = 1500;
            toastr.error("Veuillez remplir correctement tous les champs obligatoires", "Erreur", { "iconClass": 'customer-error' });
            return false;
        }
    }

    

    if (formData.securiteM1 && !/^\d{13,15}$/.test(formData.securiteM1)) {
        toastr.error("Le numéro de sécurité sociale du maitre de stage 2 doit contenir entre 13 et 15 caractères.",'Oups!');
        return false;
}

   if(isNaN(formData.salaireC.trim())){
        toastr.error("Veuillez remplir correctement le champ salaire ",'Oups!');
        return false;
    }

    if (!/^\d{13,15}$/.test(formData.securiteM)) {
        toastr.error("Le numéro de sécurité sociale du maitre de stage 1 doit contenir entre 13 et 15 caractères.",'Oups!');
        return false;
    }

    if (!parseInt(formData.dureCM, 10) > 59) {
        toastr.error("La durée contrat en minutes  ne doit pas dépasser 59.", 'Oups!');
        return false;
    }



 

    // Préparation des données pour l'envoi
    var data = new URLSearchParams();
    for (var key in formData) {
        data.append(key, formData[key]);
    }



   // Envoi de la requête POST
    fetch('form', {
        method: 'POST',
        body: data,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
    .then(response => response.json())  // Change response.text() to response.json()
    .then(response => {
        if (response.status === "success") {  // Check the status in the JSON response
            toastr.options.timeOut = 2000;
            toastr.success(response.message, "Succès", { "iconClass": 'customer-success' });
            toastr.info("Merci pour votre collaboration. Nous reviendrons vers vous au plus vite", "Formulaire soumis avec succès", { "iconClass": 'customer-authentification' });
            setTimeout(redirection, 2000);
            document.getElementById("myForm").reset();
        } else {
            toastr.options.timeOut = 2000;
            toastr.error(response.message, "Veuillez renvoyer vos informations a partir du lien recu par Email Erreur", { "iconClass": 'customer-error' });
        }
    })
    .catch(error => {
        toastr.options.timeOut = 1500;
        toastr.error("Une erreur s'est produite lors de la soumission du formulaire Veuillez renvoyer vos informations a partir du lien recu par Email: " + error, "Erreur", { "iconClass": 'customer-error' });
    });

  
    
 console.log(formData.travailC);
    return false; // Empêche le formulaire de se soumettre normalement
}

function redirection() {
    window.location.replace('home');
}

function buttoncircle() {
    document.getElementById("circle").innerHTML = '<i class="fa fa-refresh fa-spin"></i>';
    setTimeout(buttonredirection, 1500);
}

function buttonredirection() {
    document.getElementById("circle").innerHTML = "Envoyer";
}
