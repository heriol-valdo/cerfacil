<!-- Modale pour ajouter un formateur -->
<div class="modal fade" id="addFormateurModal" tabindex="-1" aria-labelledby="addFormateurModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
            
              <h5 class="modal-title" id="addFormateurModalLabel">Ajouter un formateur</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="addFormateurForm">
                <div class="mb-3">
                  <label for="firstname" class="form-label">Prénom</label>
                  <input type="text" pattern="^[a-zA-Z' -]+$" title="Veuillez entrer un prénom valide (lettres, espaces, apostrophes et tirets seulement)" name="firstname" id="firstname" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="lastname" class="form-label">Nom</label>
                  <input type="text" pattern="^[a-zA-Z' -]+$" title="Veuillez entrer un nom valide (lettres, espaces, apostrophes et tirets seulement)" name="lastname" id="lastname" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" name="email" id="email" class="form-control">
                </div>
                <div class="mb-3">
                  <?php if ($_SESSION['user']['role'] == 3): ?>
                    <input type="hidden" name="id_centres_de_formation" value="<?= $_SESSION['user']['centre'] ?>">
                  <?php elseif($_SESSION['user']['role'] == 1): ?>
                    <?php if(isset($_GET['centreId']) && filter_var($_GET['centreId'], FILTER_VALIDATE_INT) == true) : ?>
                      <input type="hidden" name="id_centres_de_formation" value="<?= $_GET['centreId'] ?>"/> 
                    <?php else : ?>                    
                      <label for="id_centres_de_formation" class="form-label">Centre de formation</label>
                      <select name="id_centres_de_formation" id="id_centres_de_formation" class="form-control">
                      <option value="" disabled selected>-- Sélectionner une option --</option>
                        <?php foreach ($allcentres as $centre): ?>
                          <option value="<?= htmlspecialchars($centre->id) ?>"><?= htmlspecialchars($centre->nomCentre) ?></option>
                        <?php endforeach; ?>
                      </select>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
                <div class="mb-3">
                  <label for="adressePostale" class="form-label">Adresse (facultatif)</label>
                  <input type="text" name="adressePostale" id="adressePostale" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="codePostal" class="form-label">Code postal (facultatif)</label>
                  <input type="text" name="codePostal" id="codePostal" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="ville" class="form-label">Ville (facultatif)</label>
                  <input type="text" name="ville" id="ville" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="siret" class="form-label">Siret (facultatif)</label>
                  <input type="text" pattern="^\d{14}$" title="Veuillez entrer un numéro SIRET valide (14 chiffres)" name="siret" id="siret" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="telephone" class="form-label">Téléphone (facultatif)</label>
                  <input type="text" pattern="^\+?[0-9\s\-\(\)]+$" title="Veuillez entrer un numéro de téléphone valide (par exemple : +1234567890, 123-456-7890, (123) 456-7890)" name="telephone" id="telephone" class="form-control">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  <button type="submit" class="btn btn-lgx">Ajouter</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <script>
        const addFormateurForm = document.getElementById("addFormateurForm");

if (addFormateurForm) {
  addFormateurForm.addEventListener("submit", async function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = {
      email: formData.get("email"),
      firstname: formData.get("firstname"),
      lastname: formData.get("lastname"),
      adressePostale: formData.get("adressePostale"),
      codePostal: formData.get("codePostal"),
      ville: formData.get("ville"),
      siret: formData.get("siret"),
      telephone: formData.get("telephone"),
      id_centres_de_formation: formData.get("id_centres_de_formation"),
    };

    try {
      const response = await fetch("../../controller/Formateur/addFormateurController.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      });

      const result = await response.json();

      if (result.erreur) {
        showToast(false, result.erreur, "Erreur lors de l'ajout du formateur");
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