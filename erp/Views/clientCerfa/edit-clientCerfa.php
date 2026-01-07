<!-- Modale pour modifier un étudiant -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Modifier un client cerfa</h5>
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
                  <label for="adressePostale" class="form-label">Adresse</label>
                  <input type="text" name="adressePostale" id="edit-adresse" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="codePostal" class="form-label">Code postal</label>
                  <input type="number" min="0" name="codePostal" id="edit-codePostal" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="ville" class="form-label">Ville</label>
                  <input type="text" name="ville" id="edit-ville" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="Telephone" class="form-label">Telephone</label>
                  <input type="text" name="telephone" id="edit-telephone" class="form-control">
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
    var codePostal = button.getAttribute('data-codepostal');
    var ville = button.getAttribute('data-ville');
    var telephone = button.getAttribute('data-telephone');



    // Remplir les champs du formulaire avec les données du bouton
    var editForm = document.getElementById('editForm');
    editForm.querySelector('#edit-id').value = id;
    editForm.querySelector('#edit-email').value = email;
    editForm.querySelector('#edit-firstname').value = firstname;
    editForm.querySelector('#edit-lastname').value = lastname;
    editForm.querySelector('#edit-adresse').value = adresse;
    editForm.querySelector('#edit-codePostal').value = codePostal;
    editForm.querySelector('#edit-ville').value = ville;
    editForm.querySelector('#edit-telephone').value = telephone;
   

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
      adresse: formData.get('adressePostale'),
      codePostal: formData.get('codePostal'),
      ville: formData.get('ville'),
      telephone: formData.get('telephone'),
    
    };

    console.log(data)

    try {
      const response = await fetch("updateClientCerfa", {
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
          window.location.href = "clientCerfa";
        }, 3000);
      }
    } catch (error) {
      console.error("Erreur réseau : ", error);
    }
  });
});
</script>
