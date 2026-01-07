<!-- Modale pour ajouter un centre formation -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Ajouter un Centre Formation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body">
                <form id="addCentreForm">
                    <div class="mb-3" id="entrepriseCentreSelect">
                        <label for="idEntrepriseCentre" class="form-label">Entreprise rattachée</label>
                        <select name="idEntrepriseCentre" id="idEntrepriseCentre" class="form-control">
                            <option value="" disabled selected>-- Sélectionner une option --</option>
                            <?php foreach ($allentreprises as $entreprise): ?>
                                <option value="<?= htmlspecialchars($entreprise->id) ?>">
                                    <?= htmlspecialchars($entreprise->nomEntreprise) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nomCentre" class="form-label">Nom du centre de formation</label>
                        <input type="text" name="nomCentre" id="nomCentre" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="adresseCentre" class="form-label">Adresse du centre</label>
                        <input type="text" name="adresseCentre" id="adresseCentre" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="telephoneCentre" class="form-label">Téléphone (facultatif)</label>
                        <input name="telephoneCentre" pattern="^\+?[0-9\s\-\(\)]+$"
                            title="Veuillez entrer un numéro de téléphone valide (par exemple : +1234567890, 123-456-7890, (123) 456-7890)"
                            id="telephoneCentre" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="codePostalCentre" class="form-label">Code postal</label>
                        <input type="number" min="0" name="codePostalCentre" id="codePostalCentre" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="villeCentre" class="form-label">Ville</label>
                        <input type="text" pattern="^[a-zA-ZÀ-ÿ' -]+$"
                            title="Veuillez entrer un nom de ville valide (lettres, espaces, apostrophes et traits d'union seulement)"
                            name="villeCentre" id="villeCentre" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-secondary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script> // Variables pour addForm.js
    const addForm = document.getElementById("addCentreForm");
    const addFormController = "addCentreFormation";
    const addFormSuccessHeader = "centreFormation";
</script>
<script src="../../erp/assets/script/addForm.js"></script>