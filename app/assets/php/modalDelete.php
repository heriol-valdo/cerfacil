<!-- Imports à copier pour modalDelete.php
  <button class="btn btn-danger btn-sm"
      onclick="confirmDeleteElement(<!?php echo $allentreprise->id; ?>)">
      <i class="fas fa-trash-alt"></i>
  </button>
  // remplacer par "confirmeDeleteElement()"

  <script> // modalDelete.php
    // Variables pour modalDelete.php
    const modalDeleteController = "../../controller/Absences/deleteAbsenceController.php";
    const modalDeleteMessage = "Voulez-vous supprimer cette absence ?";
    const modalDeleteSuccessHeader = "../absences/list-absences.php";
  </script>
  <?php // include __DIR__.'/../../assets/php/modalDelete.php'; ?>


  Si on veut juste refresh une page qui se génère avec un $_POST
   // A mettre dans voirPageController 
    $queryString = http_build_query($_POST);
    $currentPage = "voir-demande.php?$queryString";

    // modalDeleteSuccessHeader doit être remplacé par ça
    const modalDeleteSuccessHeader = "<!?= $currentPage; ?>";

-->

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmation de suppression</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="delete-modal-body">
        Placeholder
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteButton">Oui</button>
      </div>
    </div>
  </div>
</div>

<script>
  let idToDelete = null;

  function confirmDeleteElement(id) {
    idToDelete = id;
    const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    confirmDeleteModal.show();
  }

  document.getElementById('confirmDeleteButton').addEventListener('click', function () {
    if (idToDelete !== null) {
      deleteElement(idToDelete);
    }
  });

  async function deleteElement(id) {
    try {
      const response = await fetch(modalDeleteController, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: id }),
      });

      const result = await response.json();

      if (result.erreur) {
        showToast(false, result.erreur, "Erreur lors de la suppression");
      } else if (result.valid) {
        showToast(true, result.valid, "Suppression réussie");
        setTimeout(() => {
          window.location.href = modalDeleteSuccessHeader;
        }, 1000);
      }
    } catch (error) {
      console.error("Erreur réseau : ", error);
    }
  }

  // Remplace le message
  function replaceDivContent() {
    var modalBodyDiv = document.querySelector('#delete-modal-body');

    modalBodyDiv.innerHTML = modalDeleteMessage;
  }

  // Execute the function to replace the content after the page loads
  window.onload = replaceDivContent;


</script>