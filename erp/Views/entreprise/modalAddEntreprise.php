
<!-- Modale pour ajouter une entreprise -->
<div class="modal fade" id="addEntrepriseModal" tabindex="-1" aria-labelledby="addEntrepriseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEntrepriseModalLabel">Ajouter une entreprise</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEntrepriseForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="siret" class="form-label">Siret*</label>
                        <input type="text" pattern="^\d{14}$"
                            title="Veuillez entrer un numéro SIRET valide (14 chiffres)" name="siret" id="siret"
                            class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="nomEntreprise" class="form-label">Nom Entreprise*</label>
                        <input type="text" name="nomEntreprise" id="nomEntreprise" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nomDirecteur" class="form-label">Nom Directeur*</label>
                        <input type="text" name="nomDirecteur" id="nomDirecteur" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="adressePostale" class="form-label">Adresse Postale*</label>
                        <input type="text" name="adressePostale" id="adressePostale" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="codePostal" class="form-label">Code Postal*</label>
                        <input type="text" name="codePostal" id="codePostal" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="ville" class="form-label">Ville*</label>
                        <input type="text" name="ville" id="ville" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email*</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone*</label>
                        <input type="text" name="telephone" pattern="^\+?[0-9\s\-\(\)]+$"
                            title="Veuillez entrer un numéro de téléphone valide (par exemple : +1234567890, 123-456-7890, (123) 456-7890)"
                            id="telephone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="ape" class="form-label">APE*</label>
                        <input type="text" name="ape" id="ape" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="intracommunautaire" class="form-label">Intracommunautaire*</label>
                        <input type="text" name="intracommunautaire" id="intracommunautaire" class="form-control"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="soumis_tva" class="form-label">Soumis à la TVA*</label>
                        <div>
                            <input type="radio" id="soumis_tva_oui" name="soumis_tva" value="Oui" required>
                            <label for="soumis_tva_oui">Oui</label>
                            <input type="radio" id="soumis_tva_non" name="soumis_tva" value="Non" required>
                            <label for="soumis_tva_non">Non</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="domaineActivite" class="form-label">Domaine d'Activité*</label>
                        <input list="domaines" name="domaineActivite" id="domaineActivite" class="form-control"
                            required>
                        <datalist id="domaines">
                            <option value="Agriculture">
                            <option value="Agroalimentaire">
                            <option value="Aéronautique">
                            <option value="Automobile">
                            <option value="Banque et Assurance">
                            <option value="Biotechnologie">
                            <option value="BTP (Bâtiment et Travaux Publics)">
                            <option value="Chimie">
                            <option value="Commerce">
                            <option value="Communication">
                            <option value="Construction">
                            <option value="Culture et Loisirs">
                            <option value="Défense">
                            <option value="Distribution">
                            <option value="Éducation">
                            <option value="Électronique">
                            <option value="Énergie">
                            <option value="Environnement">
                            <option value="Finance">
                            <option value="Formation">
                            <option value="Hôtellerie">
                            <option value="Immobilier">
                            <option value="Informatique">
                            <option value="Ingénierie">
                            <option value="Internet">
                            <option value="Logistique">
                            <option value="Luxe">
                            <option value="Marketing">
                            <option value="Médical">
                            <option value="Mode">
                            <option value="Pharmaceutique">
                            <option value="Recherche">
                            <option value="Restauration">
                            <option value="Ressources Humaines">
                            <option value="Santé">
                            <option value="Sécurité">
                            <option value="Sport">
                            <option value="Télécommunications">
                            <option value="Textile">
                            <option value="Tourisme">
                            <option value="Transport">
                            <option value="Vente">
                        </datalist>
                    </div>

                    <div class="mb-3">
                        <label for="formeJuridique" class="form-label">Forme Juridique*</label>
                        <select name="formeJuridique" id="formeJuridique" class="form-control" required>
                            <option value="SARL">SARL</option>
                            <option value="SA">SA</option>
                            <option value="SAS">SAS</option>
                            <option value="EURL">EURL</option>
                            <option value="SNC">SNC</option>
                            <option value="SCS">SCS</option>
                            <option value="SCI">SCI</option>
                            <!-- Ajouter d'autres options si nécessaire -->
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="siteWeb" class="form-label">Lien site web</label>
                        <input type="url" name="siteWeb" id="siteWeb" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="fax" class="form-label">Fax</label>
                        <input type="text" name="fax" id="fax" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" name="logo" id="logo" accept="image/*" class="form-control">
                    </div>
                    <label for="typeEntreprise" class="form-label">Type d'Entreprise</label>
                    <div id="typeEntreprise" class="form-group">
                        <!-- <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_financeur" id="financeur"
                                value="financeur">
                            <label class="form-check-label" for="is_financeur">Organisme financeur</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_accueil" id="entreprise_accueil"
                                value="entreprise_accueil">
                            <label class="form-check-label" for="is_accueil">Entreprise d'Accueil</label>
                        </div> -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_centre_de_formation"
                                id="is_centre_de_formation" value="centre_formation" onchange="toggleCentreDeFormation()">
                            <label class="form-check-label" for="centre_formation">Centre de Formation</label>
                        </div>
                    </div>
                    <div class="mb-3" id="centreFormationSelect" style="display: none;">
                        <label for="id_centres_de_formation" class="form-label">Centre de formation</label>
                        <select name="id_centres_de_formation" id="id_centres_de_formation" class="form-control">
                            <option value="" disabled selected>-- Sélectionnez le centre de formation --</option>
                            <?php foreach ($allcentres as $centre): ?>
                                <option value="<?= htmlspecialchars($centre->id) ?>">
                                    <?= htmlspecialchars($centre->nomCentre) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-secondary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables for addForm_multipart.js
    const addForm_multipart = document.getElementById("addEntrepriseForm");
    const addForm_multipartController = "addEntreprise";
    const addForm_multipartSuccessHeader = "entreprises";
    const addForm_fileName = "logo";

    function toggleCentreDeFormation() {
        const centreFormationCheckbox = document.getElementById("is_centre_de_formation");
        const centreFormationSelect = document.getElementById("centreFormationSelect");
        
        if (centreFormationCheckbox.checked) {
            centreFormationSelect.style.display = "block";
        } else {
            centreFormationSelect.style.display = "none";
        }
    }

    if (addForm_multipart) {
        const addForm_multipartFileName = `input[name="${addForm_fileName}"]`;
        
        addForm_multipart.addEventListener("submit", async function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            const fileInput = document.querySelector(addForm_multipartFileName);
            if (fileInput && fileInput.files[0]) {
                formData.set(addForm_fileName, fileInput.files[0]); 
            }

            try {
                const response = await fetch(addForm_multipartController, {
                    method: "POST",
                    body: formData,
                });
                const result = await response.json();
                if (result.valid) {
                    showToast(true, result.valid, "Mise à jour réussie");
                    setTimeout(() => {
                        window.location.href = addForm_multipartSuccessHeader;
                    }, 1000);
                } else {
                    showToast(false, result.erreur, "Erreur lors de l'enregistrement");
                }
            } catch (error) {
                showToast(false, error, "Erreur au try view");
            }
        });
    }
</script>