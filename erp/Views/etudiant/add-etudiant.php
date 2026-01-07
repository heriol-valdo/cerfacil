<!-- Modale pour ajouter un étudiant -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addStudentModalLabel">Ajouter un étudiant</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addStudentForm">
          <div class="mb-3">
            <label for="firstname" class="form-label">Prénom*</label>
            <input type="text" pattern="^[a-zA-Z' -]+$"
              title="Veuillez entrer un prénom valide (lettres, espaces, apostrophes et tirets seulement)"
              name="firstname" id="firstname" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="lastname" class="form-label">Nom*</label>
            <input type="text" pattern="^[a-zA-Z' -]+$"
              title="Veuillez entrer un nom valide (lettres, espaces, apostrophes et tirets seulement)" name="lastname"
              id="lastname" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email*</label>
            <input type="email" name="email" id="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="date_naissance" class="form-label">Date de naissance*</label>
            <input type="date" name="date_naissance" id="date_naissance" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="adressePostale" class="form-label">Adresse*</label>
            <input type="text" name="adressePostale" id="adressePostale" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="codePostal" class="form-label">Code postal*</label>
            <input type="number" min="0" name="codePostal" id="codePostal" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="ville" class="form-label">Ville*</label>
            <input type="text" name="ville" id="ville" class="form-control" required>
          </div>
          <?php if ($_SESSION['user']['role'] == 1): ?>
            <?php if (!isset($_GET['centreId']) || filter_var($_GET['centreId'], FILTER_VALIDATE_INT) == false): ?>
              <div class="mb-3">
                <label for="id_centres_de_formation" class="form-label">Centre de formation</label>
                <select name="id_centres_de_formation" id="id_centres_de_formation" class="form-control">
                  <?php foreach ($allcentres as $centre): ?>
                    <option value="<?= htmlspecialchars($centre->id) ?>"><?= htmlspecialchars($centre->nomCentre) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            <?php else: ?>
              <input type="hidden" name="id_centres_de_formation" value="<?= $_GET['centreId'] ?>">
            <?php endif; ?>
          <?php else: ?>
            <input type="hidden" name="id_centres_de_formation" value="<?= $_SESSION['user']['centre'] ?>">
          <?php endif; ?>

          <div class="mb-3">
            <label for="id_session" class="form-label">Session de formation (facultatif) </label>
            <select name="id_session" id="id_session" class="form-control" <?= isset($filtered_list_sessions) && !empty($filtered_list_sessions) ? "" : "disabled" ?>>
              <?php if (isset($filtered_list_sessions) && !empty($filtered_list_sessions)): ?>
                <option value="" disabled selected>-- Sélectionner une option --</option>
                <?php foreach ($filtered_list_sessions as $session): ?>
                  <option value="<?= $session->id ?>"><?= $session->nomSession ?> (<?= $session->nom_formation ?>)
                    <?php if (new DateTime($session->dateDebut) > new DateTime()): ?>
                      (commence le : <?= (new DateTime($session->dateDebut))->format('d-m-Y') ?>)
                    <?php elseif (new DateTime($session->dateDebut) < new DateTime() && new DateTime($session->dateFin) > new DateTime()): ?>
                      (en cours)
                    <?php elseif (new DateTime($session->dateFin) < new DateTime()): ?>
                      (terminée)
                    <?php endif; ?>
                  <?php endforeach; ?>
                <?php else: ?>
                <option value="" selected>-- Aucune session enregistrée --</option>
              <?php endif; ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="id_entreprises" class="form-label">Entreprise d'accueil (alternance, facultatif) </label>
            <select name="id_entreprises" id="id_entreprises" class="form-control" <?= isset($allEntreprisesAccueil) && !empty($allEntreprisesAccueil) ? "" : "disabled" ?>>
              <?php if (isset($list_sessions) && !empty($list_sessions)): ?>
                <option value="" disabled selected>-- Sélectionner une option --</option>
                <?php foreach ($allEntreprisesAccueil as $entreprise): ?>
                  <option value="<?= htmlspecialchars($entreprise->id) ?>">
                    <?= htmlspecialchars($entreprise->nomEntreprise) ?>
                  </option>
                <?php endforeach; ?>
              <?php else: ?>
                <option value="" selected>-- Aucune entreprise d'accueil enregistrée --</option>
              <?php endif; ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="id_conseillers_financeurs" class="form-label">Conseiller financeur (facultatif)</label>
            <select name="id_conseillers_financeurs" id="id_conseillers_financeurs" class="form-control"
              <?= isset($allfinanceurs) && !empty($allfinanceurs) ? "" : "disabled" ?>>
              <?php if (isset($allfinanceurs) && !empty($allfinanceurs)): ?>
                <option value="" disabled selected>-- Sélectionner une option --</option>
                <?php foreach ($allfinanceurs as $financeur): ?>
                  <option value="<?= htmlspecialchars($financeur->id) ?>">
                    <?= htmlspecialchars($financeur->firstname) . " " . htmlspecialchars($financeur->lastname) ?>
                  </option>
                <?php endforeach; ?>
              <?php else: ?>
                <option value="" selected>-- Aucun conseiller financeur enregistré --</option>
              <?php endif; ?>
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
  const addForm = document.getElementById("addStudentForm");

  if (addForm) {
    addForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      const data = {
        email: formData.get("email"),
        firstname: formData.get("firstname"),
        lastname: formData.get("lastname"),
        adressePostale: formData.get("adressePostale"),
        codePostal: formData.get("codePostal"),
        ville: formData.get("ville"),
        date_naissance: formData.get("date_naissance"),
        id_entreprises: formData.get("id_entreprises"),
        id_centres_de_formation: formData.get("id_centres_de_formation") ? formData.get("id_centres_de_formation") : "",
        id_conseillers_financeurs: formData.get("id_conseillers_financeurs") ? formData.get("id_conseillers_financeurs") : "",
        id_session: formData.get("id_session") ? formData.get("id_session") : "",
      };

      try {
        const response = await fetch("../../controller/Etudiant/addEtudiantController.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        });

        const result = await response.json();

        if (result.erreur) {
          showToast(false, result.erreur, "Erreur lors de l'ajout de l'étudiant");
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