<!-- Modale pour modifier un étudiant -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Modifier un produit cerfa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editForm">
          <input type="hidden" name="id" id="edit-id">
          <div class="mb-3">
            <label for="Nom" class="form-label">Nom</label>
            <input type="text" name="nom" id="edit-nom" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="type" class="form-label">Type</label>
           
            <select name="type" name="type" id="edit-type" class="form-control" id="type" required>
                    <option value="">______</option>
                    <option value="1">Dossier d'apprentissage</option>
                    <option value="2">Dossier de Professionalisation</option>
                    <option value="3">Facturation Dossier Apprentissage</option>
                    <option value="4">Facturation Dossier Professionnalisation</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="prix_dossier" class="form-label">Prix / dossier</label>
            <input type="text" name="prix_dossier" id="edit-prixdossier" class="form-control" required>
          </div>
          
          <div class="mb-3">
            <label for="adressePostale" class="form-label">Prix / abonement</label>
            <input type="text" name="prix_abonement" id="edit-prixabonement" class="form-control">
          </div>
          <div class="mb-3">
            <label for="caracteristique1" class="form-label">Description 1 Produit</label>
            <input type="text"  name="caracteristique1" id="edit-caracteristique1" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="caracteristique2" class="form-label">Description 2 Produit</label>
            <input type="text"  name="caracteristique2" id="edit-caracteristique2" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="caracteristique3" class="form-label">Description 3 Produit</label>
            <input type="text"  name="caracteristique3" id="edit-caracteristique3" class="form-control">
          </div>
          <div class="mb-3">
            <label for="caracteristique4" class="form-label">Description 4 Produit</label>
            <input type="text"  name="caracteristique4" id="edit-caracteristique4" class="form-control">
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
    var nom = button.getAttribute('data-nom');
    var type = button.getAttribute('data-type');
    var prix_dossier = button.getAttribute('data-prixdossier');
    var prix_abonement = button.getAttribute('data-prixabonement');
    var caracteristique1 = button.getAttribute('data-caracteristique1');
    var caracteristique2 = button.getAttribute('data-caracteristique2');
    var caracteristique3 = button.getAttribute('data-caracteristique3');
    var caracteristique4 = button.getAttribute('data-caracteristique4');

    



    // Remplir les champs du formulaire avec les données du bouton
    var editForm = document.getElementById('editForm');
    editForm.querySelector('#edit-id').value = id;
    editForm.querySelector('#edit-nom').value = nom;
    editForm.querySelector('#edit-type').value = type;
    editForm.querySelector('#edit-prixdossier').value = prix_dossier;
    editForm.querySelector('#edit-prixabonement').value = prix_abonement;
    editForm.querySelector('#edit-caracteristique1').value = caracteristique1;
    editForm.querySelector('#edit-caracteristique2').value = caracteristique2;
    editForm.querySelector('#edit-caracteristique3').value = caracteristique3;
    editForm.querySelector('#edit-caracteristique4').value = caracteristique4;
   
   

  });

  // Gestionnaire de soumission du formulaire de modification
  const editForm = document.getElementById('editForm');
  editForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = {
      id: formData.get('id'),
      nom: formData.get('nom'),
      type: formData.get('type'),
      prix_dossier: formData.get('prix_dossier'),
      prix_abonement: formData.get('prix_abonement'),
      caracteristique1: formData.get('caracteristique1'),
      caracteristique2: formData.get('caracteristique2'),
      caracteristique3: formData.get('caracteristique3'),
      caracteristique4: formData.get('caracteristique4'),
      
    
    };

    console.log(data)

    try {
      const response = await fetch("updateProduitCerfa", {
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
          window.location.href = "produitCerfa";
        }, 3000);
      }
    } catch (error) {
      console.error("Erreur réseau : ", error);
    }
  });
});
</script>
