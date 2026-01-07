<?php
require_once __DIR__ . '/../../controller/CentreFormation/ListCentreFormationController.php';
?>

<!-- Modale pour modifier une salle -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Modifier une salle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editForm">
          <input type="hidden" name="id" id="edit-id">
          <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" id="edit-nom" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="capacite_accueil" class="form-label">Capacité d'accueil</label>
            <input type="number" min="0" name="capacite_accueil" id="edit-capacite_accueil" class="form-control" required>
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
    var button = event.relatedTarget; // Bouton qui a déclenché la modale
    var id = button.getAttribute('data-id');
    var nom = button.getAttribute('data-nom');
    var capacite = button.getAttribute('data-capacite');

    // Remplir les champs du formulaire avec les données du bouton
    var editForm = document.getElementById('editForm');
    editForm.querySelector('#edit-id').value = id;
    editForm.querySelector('#edit-nom').value = nom;
    editForm.querySelector('#edit-capacite_accueil').value = capacite;

  });

  // Gestionnaire de soumission du formulaire de modification
  const editForm = document.getElementById('editForm');
  editForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = {
      id: formData.get('id'),
      nom: formData.get('nom'),
      capacite_accueil: formData.get('capacite_accueil'),

    };

    console.log(data)

    try {
      const response = await fetch("../../controller/Salle/editSalleController.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      });

      const result = await response.json();

      if (result.erreur) {
        showToast(false, result.erreur, "Erreur lors de la modification de la salle");
      } else if (result.valid) {
        showToast(true, result.valid, "Modification réussie");
        setTimeout(() => {
          window.location.href = "./list-salle.php";
        }, 3000);
      }
    } catch (error) {
      console.error("Erreur réseau : ", error);
    }
  });
});
</script>
