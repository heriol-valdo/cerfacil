<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Modifier une entreprise</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form  id="updateEntrepriseForm" enctype="multipart/form-data">
                    <input type="hidden" id="edit_id" name="entrepriseId">
                    
                    <div class="mb-3">
                        <label for="edit_siret" class="form-label">SIRET</label>
                        <input type="text" class="form-control" id="edit_siret" name="siret">
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_nomEntreprise" class="form-label">Nom de l'entreprise</label>
                        <input type="text" class="form-control" id="edit_nomEntreprise" name="nomEntreprise">
                    </div>

                    <div class="mb-3">
                        <label for="edit_nomDirecteur" class="form-label">Nom du Directeur</label>
                        <input type="text" class="form-control" id="edit_nomDirecteur" name="nomDirecteur">
                    </div>

                    <div class="mb-3">
                        <label for="edit_adressePostale" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="edit_adressePostale" name="adressePostale">
                    </div>

                    <div class="mb-3">
                        <label for="edit_codePostal" class="form-label">Code Postal</label>
                        <input type="text" class="form-control" id="edit_codePostal" name="codePostal">
                    </div>

                    <div class="mb-3">
                        <label for="edit_ville" class="form-label">Ville</label>
                        <input type="text" class="form-control" id="edit_ville" name="ville">
                    </div>

                    <div class="mb-3">
                        <label for="edit_telephone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" id="edit_telephone" name="telephone">
                    </div>

                    <div class="mb-3">
                        <label for="edit_ape" class="form-label">Code APE</label>
                        <input type="text" class="form-control" id="edit_ape" name="ape">
                    </div>

                    <div class="mb-3">
                        <label for="edit_intracommunautaire" class="form-label">TVA Intracommunautaire</label>
                        <input type="text" class="form-control" id="edit_intracommunautaire" name="intracommunautaire">
                    </div>

                    <div class="mb-3">
                        <label for="edit_domaineActivite" class="form-label">Domaine d'Activité</label>
                        <input type="text" class="form-control" id="edit_domaineActivite" name="domaineActivite">
                    </div>

                    <div class="mb-3">
                        <label for="edit_formeJuridique" class="form-label">Forme Juridique</label>
                        <input type="text" class="form-control" id="edit_formeJuridique" name="formeJuridique">
                    </div>

                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email">
                    </div>

                    <div class="mb-3">
                        <label for="edit_siteWeb" class="form-label">Site Web</label>
                        <input type="url" class="form-control" id="edit_siteWeb" name="siteWeb">
                    </div>

                    <div class="mb-3">
                        <label for="edit_fax" class="form-label">Fax</label>
                        <input type="text" class="form-control" id="edit_fax" name="fax">
                    </div>

                    <div class="mb-3">
                        <label>Soumis à la TVA :</label>
                        <input type="radio" id="edit_soumis_tva_oui" name="soumis_tva" value="Oui"> Oui
                        <input type="radio" id="edit_soumis_tva_non" name="soumis_tva" value="Non"> Non
                    </div>
                    
                    <!-- <div class="mb-3">
                        <label for="edit_isActif">Actif :</label>
                        <input type="checkbox" id="edit_isActif"  name="isActif">
                    </div> -->

                    <div class="edit-form-logo-container mb-3"></div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-secondary">Ajouter</button>
                    </div>
                   
                </form>
            </div>
        </div>
    </div>
</div>



<script>

function populateEnterpriseModal(button) {

    // Récupérer les valeurs des attributs data-field-* du bouton cliqué
    let entrepriseId = button.getAttribute("data-field-id");
    let siret = button.getAttribute("data-field-siret");
    let nomEntreprise = button.getAttribute("data-field-nomEntreprise");
    let nomDirecteur = button.getAttribute("data-field-nomDirecteur");
    let adressePostale = button.getAttribute("data-field-adressePostale");
    let codePostal = button.getAttribute("data-field-codePostal");
    let ville = button.getAttribute("data-field-ville");
    let telephone = button.getAttribute("data-field-telephone");
    let ape = button.getAttribute("data-field-ape");
    let intracommunautaire = button.getAttribute("data-field-intracommunautaire");
    // let isActif = button.getAttribute("data-field-isActif");
    let soumisTva = button.getAttribute("data-field-soumis_tva");
    let domaineActivite = button.getAttribute("data-field-domaineActivite");
    let formeJuridique = button.getAttribute("data-field-formeJuridique");
    let siteWeb = button.getAttribute("data-field-siteWeb");
    let fax = button.getAttribute("data-field-fax");
    let logo = button.getAttribute("data-field-logo");
    let dateCreation = button.getAttribute("data-field-dateCreation");
    let email = button.getAttribute("data-field-email");

    // Remplir les champs du formulaire dans le modale
    document.getElementById("edit_id").value = entrepriseId;
    document.getElementById("edit_siret").value = siret;
    document.getElementById("edit_nomEntreprise").value = nomEntreprise;
    document.getElementById("edit_nomDirecteur").value = nomDirecteur;
    document.getElementById("edit_adressePostale").value = adressePostale;
    document.getElementById("edit_codePostal").value = codePostal;
    document.getElementById("edit_ville").value = ville;
    document.getElementById("edit_telephone").value = telephone;
    document.getElementById("edit_ape").value = ape;
    document.getElementById("edit_intracommunautaire").value = intracommunautaire;
    document.getElementById("edit_domaineActivite").value = domaineActivite;
    document.getElementById("edit_formeJuridique").value = formeJuridique;
    document.getElementById("edit_siteWeb").value = siteWeb;
    document.getElementById("edit_fax").value = fax;
    document.getElementById("edit_email").value = email;

// Convertir la valeur en vrai/faux
// const isActifBool = isActif === "1" || isActif.toLowerCase() === "Oui";
// document.getElementById("edit_isActif").checked = isActifBool;

const soumisTvaBool = soumisTva === "1" || soumisTva.toLowerCase() === "Oui";
document.getElementById("edit_soumis_tva_oui").checked = soumisTvaBool;
document.getElementById("edit_soumis_tva_non").checked = !soumisTvaBool;


  

    // Gestion de l'affichage du logo
    let logoContainer = document.querySelector(".edit-form-logo-container");
    if (logo) {
        logoContainer.innerHTML = `<img class="form-logo" src="https://cerfa.heriolvaldo.com/api/${logo}" alt="Logo de l'entreprise">`;
    } else {
        logoContainer.innerHTML = "<p>Aucun logo enregistré</p>";
    }
}

 

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("updateEntrepriseForm");

    if (!form) {
        console.error("❌ Formulaire non trouvé !");
        return;
    }

    form.addEventListener("submit", async function (event) {
        event.preventDefault(); // Empêche le rechargement de la page

        const formData = new FormData(form);

        const soumisTvaValue = document.querySelector('input[name="soumis_tva"]:checked').value;

        // Si "Oui" est sélectionné
        if (soumisTvaValue === "Oui") {
            formData.set("soumis_tva", "1"); // Envoyer "1" si "Oui"
        } else {
            formData.set("soumis_tva", "0"); // Envoyer "0" si "Non"
        }

       

        try {
            const response = await fetch("updateEntreprise", {  // Assurez-vous que l'URL est correcte
                method: "POST",
                body: formData,
            });

            // Vérifie si la réponse est correcte
            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }

            const result = await response.json();

            if (result.valid) {
                showToast(true, result.valid, " Mise à jour réussie");
                setTimeout(() => {
                    window.location.href = "entreprises";
                }, 1000);
            } else {
                showToast(false, result.erreur, " Erreur lors de l'enregistrement");
            }
        } catch (error) {
            console.error("Erreur lors de la requête :", error);
            showToast(false, error.message, " Erreur au try/catch");
        }
    });
});

</script>
