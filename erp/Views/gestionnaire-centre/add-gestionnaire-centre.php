<!-- Modale pour ajouter un gestionnaire -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Ajouter un gestionnaire de centre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addStudentForm">
                    <div class="mb-3">
                        <label for="firstname" class="form-label">Prénom</label>
                        <input type="text" pattern="^[a-zA-Z' -]+$"
                            title="Veuillez entrer un prénom valide (lettres, espaces, apostrophes et tirets seulement)"
                            name="firstname" id="firstname" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Nom</label>
                        <input type="text" pattern="^[a-zA-Z' -]+$"
                            title="Veuillez entrer un nom valide (lettres, espaces, apostrophes et tirets seulement)"
                            name="lastname" id="lastname" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="id_centres_de_formation" class="form-label">Centre de formation</label>
                        <select name="id_centres_de_formation" id="id_centres_de_formation" class="form-control">
                            <?php foreach ($allcentres as $centre): ?>
                                <option value="<?= htmlspecialchars($centre->id) ?>">
                                    <?= htmlspecialchars($centre->nomCentre) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone (facultatif)</label>
                        <input type="text" pattern="^\+?[0-9\s\-\(\)]+$"
                            title="Veuillez entrer un numéro de téléphone valide (par exemple : +1234567890, 123-456-7890, (123) 456-7890)"
                            name="telephone" id="telephone" class="form-control">
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
    const addForm = document.getElementById("addStudentForm");
    const addFormController = "addGestionnaireCentreFormation";
    const addFormSuccessHeader = "gestionnaireCentreFormation";
</script>
<script src="../../erp/assets/script/addForm.js"></script>