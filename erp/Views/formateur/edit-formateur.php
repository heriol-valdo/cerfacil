<?php
require_once __DIR__ . '/../../controller/CentreFormation/ListCentreFormationController.php';
?>

<!-- Modale pour modifier un administrateur -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Modifier un formateur</h5>
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
            <label for="adresse" class="form-label">Adresse Postale</label>
            <input type="text" name="adresse" id="edit-adresse" class="form-control" >
          </div>
          <div class="mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input type="text" name="ville" id="edit-ville" class="form-control" >
          </div>
          <div class="mb-3">
            <label for="codePostal" class="form-label">Code Postal</label>
            <input type="text" name="codePostal" id="edit-codePostal" class="form-control" pattern="^(?:0[1-9]|[1-8]\d|9[0-8])\d{3}$" title="Si renseigné, veuillez entrer un code postal français valide (5 chiffres)">
          </div>
          <input type="tel" name="telephone" id="edit-telephone" class="form-control" 
       pattern="(0|\+33|0033)[1-9][0-9]{8}" 
       title="Veuillez entrer un numéro de téléphone français valide (10 chiffres, optionnellement précédé de +33 ou 0033)">
          <div class="mb-3">
                  <?php if ($_SESSION['user']['role'] == 3): ?>
                    <input type="hidden" name="id_centres_de_formation" value="<?= $_SESSION['user']['centre'] ?>">
                  <?php else: ?>
                    <label for="id_centres_de_formation" class="form-label">Centre de formation</label>
                    <select name="id_centres_de_formation" id="id_centres_de_formation" class="form-control">
                      <?php foreach ($allcentres as $centre): ?>
                        <option value="<?= htmlspecialchars($centre->id) ?>"><?= htmlspecialchars($centre->nomCentre) ?></option>
                      <?php endforeach; ?>
                    </select>
                  <?php endif; ?>
                </div>
          <div class="mb-3">
            <label for="siret" class="form-label">Siret</label>
            <input type="text" name="siret" id="edit-siret" class="form-control" 
       pattern="^[0-9]{14}$" 
       title="Veuillez entrer un numéro SIRET valide (14 chiffres sans espaces ni tirets)" 
       maxlength="14">
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
    var adresse = button.getAttribute('data-adresse');
    var ville = button.getAttribute('data-ville');
    var codePostal = button.getAttribute('data-code-postal');
    var telephone = button.getAttribute('data-telephone');
    var siret = button.getAttribute('data-siret');
    var centre = button.getAttribute('data-centre');
    var lieuTravail = button.getAttribute('data-lieu_travail');

    // Remplir les champs du formulaire avec les données du bouton
    var editForm = document.getElementById('editForm');
    editForm.querySelector('#edit-id').value = id;
    editForm.querySelector('#edit-email').value = email;
    editForm.querySelector('#edit-firstname').value = firstname;
    editForm.querySelector('#edit-lastname').value = lastname;
    editForm.querySelector('#edit-telephone').value = telephone;
    editForm.querySelector('#edit-adresse').value = adresse;
    editForm.querySelector('#edit-ville').value = ville;
    editForm.querySelector('#edit-codePostal').value = codePostal;
    editForm.querySelector('#edit-siret').value = siret;
    editForm.querySelector('#edit-centre').value = centre;

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
      telephone: formData.get('telephone'),
      adresse: formData.get('adresse'),
      ville: formData.get('ville'),
      codePostal: formData.get('codePostal'),
      siret: formData.get('siret'),
      id_centres_de_formation: formData.get('id_centres_de_formation'),
    };

    console.log(data)

    try {
      const response = await fetch("../../controller/Formateur/editFormateurController.php", {
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
          window.location.href = "./list-formateur.php";
        }, 3000);
      }
    } catch (error) {
      console.error("Erreur réseau : ", error);
    }
  });
});
</script>
