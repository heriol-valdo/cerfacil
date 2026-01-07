function sendData() {
    buttoncircle();
    
    // Récupération des valeurs des champs de formulaire
    var formData = {
        nomA: document.getElementById("nomA").value,
        nomuA: document.getElementById("nomuA").value,
        prenomA: document.getElementById("prenomA").value,
        sexeA: document.getElementById("sexeA").value,
        naissanceA: document.getElementById("naissanceA").value,
        departementA: document.getElementById("departementA").value,
        communeNA: document.getElementById("communeNA").value,
        nationaliteA: document.getElementById("nationaliteA").value,
        regimeA: document.getElementById("regimeA").value,
        situationA: document.getElementById("situationA").value,
        titrePA: document.getElementById("titrePA").value,
        derniereCA: document.getElementById("derniereCA").value,
        securiteA: document.getElementById("securiteA").value,
        intituleA: document.getElementById("intituleA").value,
        titreOA: document.getElementById("titreOA").value,

        declareSA: document.getElementById("declareSA").value,
        declareHA: document.getElementById("declareHA").value,
        declareRA: document.getElementById("declareRA").value,
        rueA: document.getElementById("rueA").value,
        voieA: document.getElementById("voieA").value,
        complementA: document.getElementById("complementA").value,
        postalA: document.getElementById("postalA").value,
        communeA: document.getElementById("communeA").value,
        numeroA: document.getElementById("numeroA").value,

        nomR: document.getElementById("nomR").value,
        prenomR: document.getElementById("prenomR").value,
        emailR: document.getElementById("emailR").value,
        rueR: document.getElementById("rueR").value,
        voieR: document.getElementById("voieR").value,
        complementR: document.getElementById("complementR").value,
        postalR: document.getElementById("postalR").value,
        communeR: document.getElementById("communeR").value,

        nomM: document.getElementById("nomM").value,
        prenomM: document.getElementById("prenomM").value,
        naissanceM: document.getElementById("naissanceM").value,
        securiteM: document.getElementById("securiteM").value,
        emailM: document.getElementById("emailM").value,
        emploiM: document.getElementById("emploiM").value,
        diplomeM: document.getElementById("diplomeM").value,
        niveauM: document.getElementById("niveauM").value,

        nomM1: document.getElementById("nomM1").value,
        prenomM1: document.getElementById("prenomM1").value,
        naissanceM1: document.getElementById("naissanceM1").value,
        securiteM1: document.getElementById("securiteM1").value,
        emailM1: document.getElementById("emailM1").value,
        emploiM1: document.getElementById("emploiM1").value,
        diplomeM1: document.getElementById("diplomeM1").value,
        niveauM1: document.getElementById("niveauM1").value
    };

    // Liste des champs obligatoires
    var requiredFields = [
        'nomA', 'prenomA', 'sexeA', 'naissanceA', 'departementA',
        'communeNA', 'nationaliteA', 'regimeA', 'situationA', 'titrePA',
        'derniereCA', 'securiteA', 'intituleA', 'titreOA', 'declareSA',
        'declareHA', 'declareRA', 'rueA', 'voieA', 'postalA', 'communeA',
        'numeroA', 
        'nomM', 'prenomM', 'naissanceM', 'securiteM', 'emailM', 'emploiM','diplomeM', 'niveauM'
       
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
    if (!/^\d{13,15}$/.test(formData.securiteA)) {
        toastr.options.timeOut = 2000;
        toastr.error("Le numéro de sécurité sociale de l'apprenti doit contenir entre 13 et 15 caractères.", "Erreur", { "iconClass": 'customer-error' });
        return false;
    }
    if (!/^\d{13,15}$/.test(formData.securiteM)) {
        toastr.options.timeOut = 2000;
        toastr.error("Le numéro de sécurité sociale du maitre de stage 1 doit contenir entre 13 et 15 caractères.", "Erreur", { "iconClass": 'customer-error' });
        return false;
    }
    if (formData.securiteM1 && !/^\d{13,15}$/.test(formData.securiteM1)) {
        toastr.options.timeOut = 2000;
        toastr.error("Le numéro de sécurité sociale du maitre de stage 2 doit contenir entre 13 et 15 caractères.", "Erreur", { "iconClass": 'customer-error' });
        return false;
    }
    if (!/^\d{10}$/.test(formData.numeroA)) {
        toastr.options.timeOut = 2000;
        toastr.error("Veuillez remplir exactement 10 chiffres sur le numéro de l'apprenti.", "Erreur", { "iconClass": 'customer-error' });
        return false;
    }
    if (!/^\d{5}$/.test(formData.postalA)) {
        toastr.options.timeOut = 2000;
        toastr.error("Le code postal de l'apprenti doit contenir exactement 5 chiffres.", "Erreur", { "iconClass": 'customer-error' });
        return false;
    }
    if (formData.postalR && !/^\d{5}$/.test(formData.postalR)) {
        toastr.options.timeOut = 2000;
        toastr.error("Le code postal du représentant légal doit contenir exactement 5 chiffres.", "Erreur", { "iconClass": 'customer-error' });
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
            toastr.error(response.message , "Erreur", { "iconClass": 'customer-error' });
        }
    })
    .catch(error => {
        toastr.options.timeOut = 1500;
        toastr.error("Une erreur s'est produite lors de la soumission du formulaire  Veuillez renvoyer vos informations a partir du lien recu par Email: " + error, "Erreur", { "iconClass": 'customer-error' });
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
