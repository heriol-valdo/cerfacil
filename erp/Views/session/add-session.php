 <!-- Modale pour ajouter une session -->
 <div class="modal fade" id="addSessionModal" tabindex="-1" aria-labelledby="addModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addModalLabel">Ajouter une nouvelle session</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?php $disableFields = !isset($centre_formations) || empty($centre_formations) || !isset($list_formateurs) || empty($list_formateurs); ?>
            <?php if ($disableFields): ?>
              <?php if(!isset($centre_formations) || empty($centre_formations)) :?>
                <div class="alert alert-warning d-flex flex-column align-items-center" role="alert">
                    Aucune formation disponible. Veuillez ajouter une formation avant d'ajouter une session.
                    <button class="btn btn-lgx mt-3" data-bs-toggle="modal" data-bs-target="#addModalFormation">Ajouter une formation</button>
                </div>
              <?php endif; ?>
              <?php if(!isset($list_formateurs) || empty($list_formateurs)) :?>
                <div class="alert alert-warning d-flex flex-column align-items-center" role="alert">
                    Aucun formateur enregistré. Veuillez ajouter un formateur avant d'ajouter une session.
                    <button class="btn btn-lgx mt-3" data-bs-toggle="modal" data-bs-target="#addFormateurModal">Ajouter un formateur</button>
                </div>
              <?php endif; ?>
            <?php endif; ?>
            <div class="modal-body">
              <form id="addForm">
                <input type="hidden" value="<?= $_SESSION['user']['role'] == 1 ? $_GET["centreId"] : $_SESSION['user']['centre']; ?>" name="idCentre">
                <div class="mb-3">
                  <label for="nom" class="form-label">Nom de la session*</label>
                  <input type="text" name="nom" id="nom" class="form-control" required <?= $disableFields ? 'disabled' : '' ?>>
                </div>
                <div class="mb-3">
                  <label for="dateDebut" class="form-label">Date de début*</label>
                  <input type="date" name="dateDebut" id="dateDebut" class="form-control" required <?= $disableFields ? 'disabled' : '' ?>>
                </div>
                <div class="mb-3">
                  <label for="dateFin" class="form-label">Date de fin*</label>
                  <input type="date" name="dateFin" id="dateFin" class="form-control" required <?= $disableFields ? 'disabled' : '' ?>>
                </div>
                <div class="mb-3">
                  <label for="nbPlace" class="form-label">Nombre de places*</label>
                  <input type="number" name="nbPlace" id="nbPlace" class="form-control" min="0" required <?= $disableFields ? 'disabled' : '' ?>>
                </div>
                <div class="mb-3">
                    <label for="date_naissance" class="form-label">Formation*</label>
                    <select name="idFormation" id="idFormation" class="form-control" required <?= $disableFields ? 'disabled' : '' ?>>
                      <option value="">-- Sélectionner une formation --</option>
                      <?php if(isset($centre_formations)) : ?>
                        <?php foreach ($centre_formations as $allformation) : ?>
                          <option value="<?= $allformation->id ?>"><?= $allformation->nom ?></option>
                        <?php endforeach; ?>
                      <?php else : ?>
                        <option value="" selected>Aucune formation disponible</option>
                      <?php endif; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="idFormateur" class="form-label">Référent pédagogique*</label>
                    <select name="idFormateur" id="idFormateur" class="form-control" required <?= $disableFields ? 'disabled' : '' ?>>
                      <option value="">-- Sélectionner le référent pédagogique --</option>
                      <?php if(isset($list_formateurs)) : ?>
                        <?php foreach ($list_formateurs as $formateur) : ?>
                          <option value="<?= $formateur->id ?>"><?= strtoupper($formateur->lastname) ?> <?= ucfirst($formateur->firstname) ?></option>
                        <?php endforeach; ?> 
                      <?php else : ?>
                        <option value="" selected>Aucun formateur enregistré</option>
                      <?php endif; ?>
                    </select>
                </div>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-lgx" <?= $disableFields ? 'disabled' : '' ?>>Ajouter</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <script>
        const addSessionForm = document.getElementById("addForm");

if (addSessionForm) {
  addSessionForm.addEventListener("submit", async function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = {
      nom: formData.get("nom"),
      dateDebut: formData.get("dateDebut"),
      dateFin: formData.get("dateFin"),
      nbPlace: formData.get("nbPlace"),
      idFormation: formData.get("idFormation"),
      idFormateur: formData.get("idFormateur"),
      idCentre: formData.get("idCentre")
    };


    try {
      const response = await fetch("../../controller/Sessions/addSessionController.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      });

      const result = await response.json();

      if (result.erreur) {
        showToast(false, result.erreur, "Erreur lors l'ajout de la session");
      } else if (result.valid) {
        showToast(true, result.valid, "Ajout réussi");
        setTimeout(() => {
            let queryParams = [];
            if (centreName) {
                queryParams.push(`centreName=${centreName}`);
            }
            if (centreId) {
                queryParams.push(`centreId=${centreId}`);
            }

            const queryString = queryParams.length ? `?${queryParams.join('&')}` : '';

            window.location.href = window.location.pathname + queryString;
          }, 500);
      }
    } catch (error) {
      console.error("Erreur réseau : ", error);
    }
  });
}
    </script>