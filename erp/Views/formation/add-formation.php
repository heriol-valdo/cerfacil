<!-- Modale pour ajouter une formation -->
<div class="modal fade" id="addModalFormation" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addModalLabel">Ajouter une formation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addFormationForm">
          <div class="mb-3">
            <label for="nom" class="form-label">Nom*</label>
            <input type="text" name="nom" id="nom" class="form-control">
          </div>
          <div class="mb-3">
            <label for="prix" class="form-label">Prix (€)*</label>
            <input type="number" name="prix" id="prix" class="form-control">
          </div>
          <div class="mb-3">
            <label for="lienFranceCompetence" class="form-label">Lien France Compétence*</label>
            <input type="text" name="lienFranceCompetence" id="lienFranceCompetence" class="form-control">
          </div>
          <input type="hidden" name="idCentre"
            value="<?= $_SESSION['user']['role'] == 1 ? $_GET["centreId"] : $_SESSION['user']['centre']; ?>" />
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
  const addFormationForm = document.getElementById("addFormationForm");

  if (addFormationForm) {
    addFormationForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      const data = {
        nom: formData.get("nom"),
        prix: formData.get("prix"),
        lienFranceCompetence: formData.get("lienFranceCompetence"),
        idCentre: formData.get("idCentre"),
      };

      try {
        const response = await fetch("../../controller/Formation/addFormationController.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        });

        const result = await response.json();
        if (result.erreur) {
          showToast(false, result.erreur, "Erreur lors de l'ajout de la formation");
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