<!-- Modale pour ajouter une absence -->
<div class="modal fade" id="addAbsenceModal" tabindex="-1" aria-labelledby="addAbsenceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAbsenceModalLabel">Ajouter une absence</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addAbsenceForm" enctype="multipart/form-data">
                    <?php if ($_SESSION['user']['role'] == 1): ?>
                        <div class="mb-3">
                            <label for="idCentre" class="form-label">Centre de formation *</label>
                            <select name="idCentre" id="idCentre" class="form-control" required>
                                <option value="" disabled selected>-- Sélectionnez un centre --</option>';
                                <?php foreach ($allcentres as $allcentre): ?>
                                    <option value="<?= $allcentre->id; ?>"><?= $allcentre->nomCentre; ?></option>';
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label for="idFormation" class="form-label">Formation *</label>
                        <select name="idFormation" id="idFormation" class="form-control" <?php if ($_SESSION['user']['role'] == 1): ?>disabled<?php endif; ?> required>
                            <option value="">............</option>
                            <?php foreach ($allformations as $allformation): ?>
                                <option value="<?= $allformation->id; ?>"
                                    formation_centreId="<?= $allformation->id_centres_de_formation; ?>">
                                    <?= $allformation->nom; ?>
                                </option>;
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="idSession" class="form-label">Sessions *</label>
                        <select name="idSession" id="idSession" class="form-control" disabled required>
                            <option value="">............</option>
                            <?php foreach ($filtered_list_sessions as $allsession): ?>
                                <option value="<?= $allsession->id; ?>"
                                    session_formationId="<?= $allsession->id_formations; ?>">
                                    <?= $allsession->nomSession; ?>     <?= $allsession->id_formations; ?>
                                </option>;
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="idEtudiant" class="form-label">Étudiant *</label>
                        <select name="idEtudiant" id="idEtudiant" class="form-control" disabled required>
                            <option value="">............</option>
                            <?php foreach ($alletudiants as $etudiant): ?>
                                <option value="<?= $etudiant->id; ?>" etudiant_sessionId="<?= $etudiant->id_session; ?>">
                                    <?= $etudiant->lastname; ?>     <?= $etudiant->firstname; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dateDebut" class="form-label">Début de l'absence *</label>
                        <input type="date" name="dateDebut" id="dateDebut" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="dateFin" class="form-label">Fin de l'absence</label>
                        <input type="date" name="dateFin" id="dateFin" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="justificatif" class="form-label">Justificatif</label>
                        <input type="file" name="justificatif" id="justificatif"
                            accept="application/pdf,image/jpeg,image/png" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="raison" class="form-label">Raison *</label>
                        <input type="text" name="raison" id="raison" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-secondary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script> // Variables pour addForm_multipart.js
    const addForm_multipart = document.getElementById("addAbsenceForm");
    const addForm_multipartController = "../../controller/Absences/addAbsenceController.php";
    const addForm_multipartSuccessHeader = "list-absences.php";
    const addForm_fileName = "justificatif";
</script>
<script src="../../assets/script/addForm_multipart.js"></script>

<script> // Gère désactivation / activation menus dans la modale
    document.addEventListener('DOMContentLoaded', function () {
        const idCentre = document.getElementById('idCentre');
        const idFormation = document.getElementById('idFormation');
        const idSession = document.getElementById('idSession');
        const idEtudiant = document.getElementById('idEtudiant');
        const allFormations = Array.from(idFormation.options);
        const allSessions = Array.from(idSession.options);
        const allEtudiants = Array.from(idEtudiant.options);

        <?php if ($_SESSION['user']['role'] == 1): ?>
            idCentre.addEventListener('change', function () {
                const selectedCentreId = this.value;
                if (selectedCentreId) {
                    idFormation.removeAttribute('disabled');
                    idFormation.innerHTML = '<option value="">-- Sélectionnez une formation --</option>';
                    idSession.setAttribute('disabled', 'disabled');
                    idSession.innerHTML = '<option value="">............</option>';
                    idEtudiant.setAttribute('disabled', 'disabled');
                    idEtudiant.innerHTML = '<option value="">............</option>';

                    allFormations.forEach(function (option) {
                        if (option.getAttribute('formation_centreId') === selectedCentreId) {
                            idFormation.appendChild(option);
                        }
                    });
                } else {
                    idFormation.setAttribute('disabled', 'disabled');
                    idFormation.innerHTML = '<option value="">............</option>';
                }
            });
        <?php endif; ?>

        idFormation.addEventListener('change', function () {
            const selectedFormationId = this.value;
            if (selectedFormationId) {
                idSession.removeAttribute('disabled');
                idSession.innerHTML = '<option value="">-- Sélectionnez une session --</option>';
                idEtudiant.setAttribute('disabled', 'disabled');
                idEtudiant.innerHTML = '<option value="">............</option>';

                allSessions.forEach(function (option) {
                    if (option.getAttribute('session_formationId') === selectedFormationId) {
                        idSession.appendChild(option);
                    }
                });
            } else {
                idSession.setAttribute('disabled', 'disabled');
                idSession.innerHTML = '<option value="">............</option>';
            }
        });

        idSession.addEventListener('change', function () {
            const selectedSessionId = this.value;
            if (selectedSessionId) {
                idEtudiant.removeAttribute('disabled');
                idEtudiant.innerHTML = '<option value="">-- Sélectionnez un étudiant --</option>';

                allEtudiants.forEach(function (option) {
                    if (option.getAttribute('etudiant_sessionId') === selectedSessionId) {
                        idEtudiant.appendChild(option);
                    }
                });
            } else {
                idEtudiant.setAttribute('disabled', 'disabled');
                idEtudiant.innerHTML = '<option value="">............</option>';
            }
        });
    });
</script>