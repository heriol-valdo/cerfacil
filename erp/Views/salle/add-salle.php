<!-- Modale pour ajouter une salle -->
<div class="modal fade" id="addSalleModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addModalLabel">Ajouter une salle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addSalleForm">
          <div class="mb-3">
            <label for="nom" class="form-label">Nom*</label>
            <input type="text" name="nom" id="nom" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="capacite_accueil" class="form-label">Capacité d'accueil</label>
            <input type="number" min="0" name="capacite_accueil" id="capacite_accueil" class="form-control">
          </div>
          <div class="mb-3">
            <?php if ($_SESSION['user']['role'] == 1): ?>
              <?php if (isset($_GET['centreId']) && filter_var($_GET['centreId'], FILTER_VALIDATE_INT) == true): ?>
                <input type="hidden" name="id_centres_de_formation" value="<?= $_GET['centreId'] ?>" />
              <?php else: ?>
                <label for="id_centres_de_formation" class="form-label">Centre de formation*</label>
                <select name="id_centres_de_formation" id="id_centres_de_formation" class="form-control">
                  <option value="">-- Sélectionner un centre --</option>
                  <?php foreach ($allcentres as $centre): ?>
                    <option value="<?= htmlspecialchars($centre->id) ?>">
                      <?= htmlspecialchars($centre->nomCentre) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              <?php endif; ?>
            <?php elseif ($_SESSION['user']['role'] == 3): ?>
              <input type="hidden" name="id_centres_de_formation" value="<?= $_SESSION['user']['centre'] ?>" />
            <?php endif; ?>
          </div>
          <div class="d-flex justify-content-center gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-lgx">Ajouter</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  const addSalleForm = document.getElementById("addSalleForm");

  if (addSalleForm) {
    addSalleForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      const data = {
        nom: formData.get("nom"),
        capacite_accueil: formData.get("capacite_accueil"),
        id_centres_de_formation: formData.get("id_centres_de_formation")
      };

      try {
        const response = await fetch("../../controller/Salle/addSalleController.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        });

        const result = await response.json();

        if (result.erreur) {
          showToast(false, result.erreur, "Erreur lors de l'ajout de la salle");
        } else if (result.valid) {
          showToast(true, result.valid, "Ajout réussie");
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