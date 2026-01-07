<?php
require_once __DIR__ . '/../../controller/CentreFormation/ListCentreFormationController.php';
?>

<!-- Modale pour modifier un gestionnaire d'entreprise' -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Modifier un gestionnaire d'entreprise</h5>
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
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="number" name="telephone" id="edit-telephone" class="form-control">
          </div>
          <div class="mb-3">
            <label for="lieu_travail" class="form-label">Lieu de travail</label>
            <input type="text" name="lieu_travail" id="edit-lieuTravail" class="form-control">
          </div>
          <div class="mb-3">
              <label for="id_entreprises" class="form-label">Entreprise</label>
              <select name="id_entreprises" id="edit-entreprise" class="form-control">
                  <?php foreach ($allentreprises as $entreprise) : ?>
                      <option value="<?= htmlspecialchars($entreprise->id) ?>"><?= htmlspecialchars($entreprise->nomEntreprise) ?></option>
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
    var telephone = button.getAttribute('data-telephone');
    var lieuTravail = button.getAttribute('data-lieu-travail');
    var entreprise = button.getAttribute('data-entreprise');


    // Remplir les champs du formulaire avec les données du bouton
    var editForm = document.getElementById('editForm');
    editForm.querySelector('#edit-id').value = id;
    editForm.querySelector('#edit-email').value = email;
    editForm.querySelector('#edit-firstname').value = firstname;
    editForm.querySelector('#edit-lastname').value = lastname;
    editForm.querySelector('#edit-telephone').value = telephone;
    editForm.querySelector('#edit-entreprise').value = entreprise;
    editForm.querySelector('#edit-lieuTravail').value = lieuTravail;

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
      entreprise: formData.get('id_entreprises'),
      lieuTravail: formData.get('lieu_travail'),
    };

    console.log(data);


    try {
      const response = await fetch("../../controller/GestionnaireEntreprise/editGestionnaireEntrepriseController.php", {
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
          window.location.href = "./list-gestionnaire-entreprise.php";
        }, 3000);
      }
    } catch (error) {
      console.error("Erreur réseau : ", error);
    }
  });
});
</script>
