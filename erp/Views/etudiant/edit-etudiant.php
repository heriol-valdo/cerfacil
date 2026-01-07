<!-- Modale pour modifier un étudiant -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Modifier un étudiant</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editForm">
          <input type="hidden" name="id" id="edit-id">
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="edit-email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="firstname" class="form-label">Prénom</label>
            <input type="text" name="firstname" id="edit-firstname" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="lastname" class="form-label">Nom</label>
            <input type="text" name="lastname" id="edit-lastname" class="form-control" required>
          </div>
          <div class="mb-3">
                  <label for="date_naissance" class="form-label">Date de naissance</label>
                  <input type="date" name="date_naissance" id="edit-naissance" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="adressePostale" class="form-label">Adresse</label>
                  <input type="text" name="adressePostale" id="edit-adresse" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="codePostal" class="form-label">Code postal</label>
                  <input type="text" name="codePostal" id="edit-codePostal" class="form-control" pattern="^(?:0[1-9]|[1-8]\d|9[0-8])\d{3}$" title="Si renseigné, veuillez entrer un code postal français valide (5 chiffres)">
                </div>
                <div class="mb-3">
                  <label for="ville" class="form-label">Ville</label>
                  <input type="text" name="ville" id="edit-ville" class="form-control">
                </div>
                <?php if ($_SESSION['user']['role'] == 1): ?>
                  <div class="mb-3">
                      <label for="id_centres_de_formation" class="form-label">Centre de formation </label>
                      <select name="id_centres_de_formation" id="edit-centre" class="form-control">
                          <?php foreach ($allcentres as $centre): ?>
                              <option value="<?= htmlspecialchars($centre->id) ?>"><?= htmlspecialchars($centre->nomCentre) ?></option>
                          <?php endforeach; ?>
                      </select>
                  </div>
                <?php endif; ?>

                <div class="mb-3">
                  <label for="id_session" class="form-label">Session de formation </label>
                  <select name="id_session" id="edit-session" class="form-control">
                    <option value="" disabled selected>-- Sélectionner une option --</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="id_entreprises" class="form-label">Entreprise d'accueil </label>
                  <select name="id_entreprises" id="edit-entreprise" class="form-control">
                    <option value="" disabled selected>-- Sélectionner une option --</option>
                    <?php foreach ($allEntreprisesAccueil as $entreprise) : ?>
                      <option value="<?= htmlspecialchars($entreprise->id) ?>"><?= htmlspecialchars($entreprise->nomEntreprise) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="id_conseillers_financeurs" class="form-label">Conseiller financeur </label>
                  <select name="id_conseillers_financeurs" id="edit-financeur" class="form-control">
                    <option value="" disabled selected>-- Sélectionner une option --</option>
                      <?php foreach ($allfinanceurs as $financeur) : ?>
                          <option value="<?= htmlspecialchars($financeur->id) ?>"><?= htmlspecialchars($financeur->firstname)." ".htmlspecialchars($financeur->lastname) ?></option>
                      <?php endforeach; ?>
                  </select>
                </div>
            
          <div class="modal-footer">
            <button type="submit" class="btn btn-secondary">Modifier</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
  var editModal = document.getElementById('editModal');
  editModal.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget;
    var id = button.getAttribute('data-userid');
    var email = button.getAttribute('data-email');
    var firstname = button.getAttribute('data-firstname');
    var lastname = button.getAttribute('data-lastname');
    var naissance = button.getAttribute('data-naissance');
    var adresse = button.getAttribute('data-adresse');
    var codePostal = button.getAttribute('data-codepostal');
    var ville = button.getAttribute('data-ville');
    var centre = button.getAttribute('data-centre');
    var session = button.getAttribute('data-session');
    var entreprise = button.getAttribute('data-entreprise');
    var financeur = button.getAttribute('data-financeur');


    // Remplir les champs du formulaire avec les données du bouton
    var editForm = document.getElementById('editForm');
    editForm.querySelector('#edit-id').value = id;
    editForm.querySelector('#edit-email').value = email;
    editForm.querySelector('#edit-firstname').value = firstname;
    editForm.querySelector('#edit-lastname').value = lastname;
    editForm.querySelector('#edit-naissance').value = naissance;
    editForm.querySelector('#edit-adresse').value = adresse;
    editForm.querySelector('#edit-codePostal').value = codePostal;
    editForm.querySelector('#edit-ville').value = ville;
    editForm.querySelector('#edit-centre').value = centre;
    editForm.querySelector('#edit-session').value = session;
    editForm.querySelector('#edit-entreprise').value = entreprise;
    editForm.querySelector('#edit-financeur').value = financeur;

  });

  // Gestionnaire de soumission du formulaire de modification
  const editForm = document.getElementById('editForm');
  editForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = {
      id: formData.get('id'),
      email: formData.get('email'),
      firstname: formData.get('firstname'),
      lastname: formData.get('lastname'),
      naissance: formData.get('date_naissance'),
      adresse: formData.get('adressePostale'),
      codePostal: formData.get('codePostal'),
      ville: formData.get('ville'),
      centre: formData.get('id_centres_de_formation'),
      session: formData.get('id_session') ? formData.get('id_session') : "",
      entreprise: formData.get('id_entreprises') ? formData.get('id_entreprises') :"",
      financeur: formData.get('id_conseillers_financeurs'),
    };

    console.log(data)

    try {
      const response = await fetch("../../controller/Etudiant/editEtudiantController.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      });

      const result = await response.json();

      if (result.erreur) {
        showToast(false, result.erreur, "Erreur lors de la modification de la formation");
      } else if (result.valid) {
        showToast(true, result.valid, "Modification réussie");
        setTimeout(() => {
          window.location.href = "./list-etudiant.php";
        }, 3000);
      }
    } catch (error) {
      console.error("Erreur réseau : ", error);
    }
  });
});
</script>
