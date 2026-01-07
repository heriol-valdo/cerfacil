<?php
require_once __DIR__ . '/../../controller/Sessions/EditSessionController.php';
?>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Modifier une session</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editForm">
          <input type="hidden" name="id" id="edit-id">
          <div class="mb-3">
            <label for="dateDebut" class="form-label">Date de début</label>
            <input type="date" name="dateDebut" id="edit-dateDebut" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="dateFin" class="form-label">Date de fin</label>
            <input type="date" name="dateFin" id="edit-dateFin" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="nbPlace" class="form-label">Nombre de places</label>
            <input type="number" name="nbPlace" id="edit-nbPlace" class="form-control" min="0" required>
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
      var id = button.getAttribute('data-id');
      var dateDebut = button.getAttribute('data-dateDebut');
      var dateFin = button.getAttribute('data-dateFin');
      var nbPlace = button.getAttribute('data-nbPlace');

      var editForm = document.getElementById('editForm');
      editForm.querySelector('#edit-id').value = id;
      editForm.querySelector('#edit-dateDebut').value = dateDebut;
      editForm.querySelector('#edit-dateFin').value = dateFin;
      editForm.querySelector('#edit-nbPlace').value = nbPlace;
    });

    const editForm = document.getElementById('editForm');
    editForm.addEventListener('submit', async function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      const data = {
        id: formData.get('id'),
        dateDebut: formData.get('dateDebut'),
        dateFin: formData.get('dateFin'),
        nbPlace: formData.get('nbPlace'),
      };

      console.log(data);

      try {
        const response = await fetch("../../controller/Sessions/editSessionController.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        });

        const result = await response.json();
       


        if (result.erreur) {
          showToast(false, result.erreur, "Erreur lors de la modification de la session");
        } else if (result.valid) {
          showToast(true, result.valid, "Modification réussie");
          setTimeout(() => {
            window.location.href = "./list-session.php";
          }, 3000);
        }
      } catch (error) {
        console.error("Erreur réseau : ", error);
      }
    });
  });
</script>
