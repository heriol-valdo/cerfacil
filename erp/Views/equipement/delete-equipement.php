<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmation de suppression</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Voulez-vous vraiment supprimer cet équipement ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteButton">OK</button>
      </div>
    </div>
  </div>
</div>



<script>
let equipementToDelete = null;
let equipementToDeleteSalle = null;

function confirmDeleteEquipement(id, idSalle) {
  equipementToDelete = id;
  equipementToDeleteSalle = idSalle;
  const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
  confirmDeleteModal.show();
}

document.getElementById('confirmDeleteButton').addEventListener('click', function() {
  if (equipementToDelete !== null) {
    deleteEquipement(equipementToDelete, equipementToDeleteSalle);
  }
});

async function deleteEquipement(id, idSalle) {

  console.log(idSalle);
  try {
    const response = await fetch('../../controller/Equipement/deleteEquipementController.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ id, salle_id: idSalle }),
    });

  

    const result = await response.json();
    showToast(false, result, "Erreur lors de la suppression de l'équipement");

    if (result.erreur) {
      showToast(false, result.erreur, "Erreur lors de la suppression de l'équipement");
    } else if (result.valid) {
      showToast(true, result.valid, "Suppression réussie");
      setTimeout(() => {
        window.location.href = "./list-equipement.php";
      }, 3000);
    }
  } catch (error) {
    console.error("Erreur réseau : ", error);
  }
}


</script>