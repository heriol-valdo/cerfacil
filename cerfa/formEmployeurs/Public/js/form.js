function sendData() {
    buttoncircle();
    
    // Récupération des valeurs des champs de formulaire
    var formData = {
        typeE: document.getElementById("typeE").value,
        specifiqueE: document.getElementById("specifiqueE").value,
        totalE: document.getElementById("totalE").value,
        siretE: document.getElementById("siretE").value,
        codeaE: document.getElementById("codeaE").value,
        codeiE: document.getElementById("codeiE").value,
        rueE: document.getElementById("rueE").value,
        voieE: document.getElementById("voieE").value,
        complementE: document.getElementById("complementE").value,
        postalE: document.getElementById("postalE").value,
        communeE: document.getElementById("communeE").value,
        numeroE: document.getElementById("numeroE").value
        

      
    };

    // Liste des champs obligatoires
    var requiredFields = [
        typeE,specifiqueE,totalE,siretE,codeaE,codeiE,postalE,voieE,communeE,numeroE
      
    ];

    // Validation des champs requis
    for (var key of requiredFields) {
        if (formData[key] === "") {
            toastr.options.timeOut = 1500;
            toastr.error("Veuillez remplir correctement tous les champs obligatoires", "Erreur", { "iconClass": 'customer-error' });
            return false;
        }
    }

    // Vérification spécifique des formats   
 if (!/^\d{14}$/.test(formData.siretE)) {
        toastr.options.timeOut = 2000;
        toastr.error("Le numero de siret de l'l'employeur doit contenir exactement 14 chiffres.", "Erreur", { "iconClass": 'customer-error' });
        return false;
    }

    if (!/^\d{10}$/.test(formData.numeroE)) {
        toastr.options.timeOut = 2000;
        toastr.error("Veuillez remplir exactement 10 chiffres sur le numéro de l'employeur.", "Erreur", { "iconClass": 'customer-error' });
        return false;
    }
    if (!/^\d{5}$/.test(formData.postalE)) {
        toastr.options.timeOut = 2000;
        toastr.error("Le code postal de l'employeur doit contenir exactement 5 chiffres.", "Erreur", { "iconClass": 'customer-error' });
        return false;
    }

if (formData.codeaE.length > 6) {
    toastr.options.timeOut = 2000;
    toastr.error("Le code NAF ne doit pas dépasser 6 caractères.", "Erreur", { "iconClass": 'customer-error' });
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
