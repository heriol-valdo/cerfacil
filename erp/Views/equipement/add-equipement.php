<!-- Modale pour ajouter un equipement -->
<div class="modal fade" id="addEquipementModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addModalLabel">Ajouter un equipement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addEquipementForm">
          <div class="mb-3">
            <label for="nom" class="form-label">Nom*</label>
            <input type="text" name="nom" id="nom" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="quantite" class="form-label">Quantité*</label>
            <input type="number" min="0" name="quantite" id="quantite" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="idsalle" class="form-label">Salle*</label>
            <select name="idsalle" id="idsalle" class="form-control" required>
              <option value="">-- Sélectionner une salle --</option>
              <?php foreach ($list_salles as $salle): ?>
                <?php $txt_salle = "";
                if (!empty($salle->capacite_accueil)) {
                  $txt_salle = $salle->nom . " (" . $salle->capacite_accueil . " place" . ($salle->capacite_accueil > 1 ? "s" : "") . ")";
                } else {
                  $txt_salle = "$salle->nom";
                } ?>
                <option value="<?= $salle->id ?>"><?= $txt_salle ?></option>
              <?php endforeach; ?>
            </select>
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

  const addEquipementForm = document.getElementById("addEquipementForm");

  if (addEquipementForm) {
    addEquipementForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      const data = {
        nom: formData.get("nom"),
        quantite: formData.get("quantite"),
        idsalle: formData.get("idsalle"),
      };

      try {
        const response = await fetch("../../controller/Equipement/addEquipementController.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        });

        const result = await response.json();

        if (result.erreur) {
          showToast(false, result.erreur, "Erreur lors l'ajout de l'equipement");
        } else if (result.valid) {
          showToast(true, result.valid, "Mise à jour réussie");
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