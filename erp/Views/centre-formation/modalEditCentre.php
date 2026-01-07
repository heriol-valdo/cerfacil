<!-- Modale pour modifier un centre formation -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="updateCentreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateCentreModalLabel">Modifier un Centre Formation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="mb-3">
                        <label for="centreNom" class="form-label">Nom du centre de formation</label>
                        <input type="text" name="centreNom" id="edit-nomCentre" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="centreAdresse" class="form-label">Adresse du centre</label>
                        <input type="text" name="centreAdresse" id="edit-adresseCentre" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="centreCodePostal" class="form-label">Code postal</label>
                        <input type="number" min="0" name="centreCodePostal" id="edit-codePostalCentre"
                            class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="centreVille" class="form-label">Ville</label>
                        <input type="text" pattern="^[a-zA-ZÀ-ÿ' -]+$"
                            title="Veuillez entrer un nom de ville valide (lettres, espaces, apostrophes et traits d'union seulement)"
                            name="centreVille" id="edit-villeCentre" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="centreTelephone" class="form-label">Téléphone (facultatif)</label>
                        <input name="centreTelephone" pattern="^\+?[0-9\s\-\(\)]+$"
                            title="Veuillez entrer un numéro de téléphone valide (par exemple : +1234567890, 123-456-7890, (123) 456-7890)"
                            id="edit-telephoneCentre" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="centreId" id="edit-centreId" />
                        <input type="hidden" name="centreEntrepriseId" id="edit-entrepriseId" />
                        <button type="submit" class="btn btn-secondary">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script> // Variables pour updateForm.js
    const updateForm = document.getElementById("editForm");
    const updateFormController = "updateCentreFormation";
    const updateFormSuccessHeader = "centreFormation";
    // Tous les champs du formulaire et l'id associé
    // commencer par 'data-xxx' et finir par id des champs '#edit-xxx'
    const dataMap = {
        'data-id': '#edit-centreId',
        'data-nomCentre': '#edit-nomCentre',
        'data-adresseCentre': '#edit-adresseCentre',
        'data-codePostalCentre': '#edit-codePostalCentre',
        'data-villeCentre': '#edit-villeCentre',
        'data-telephoneCentre': '#edit-telephoneCentre',
        'data-idEntreprise': '#edit-entrepriseId'
    };
</script>
<script src="../../erp/assets/script/updateForm.js"></script>

