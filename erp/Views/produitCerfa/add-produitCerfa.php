<!-- Modale pour ajouter un étudiant -->
<div class="modal fade" id="addClientCerfaModal" tabindex="-1" aria-labelledby="addClientCerfaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addStudentModalLabel">Ajouter un produit Cerfa</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="addProduitCerfaForm">
                <div class="mb-3">
                  <label for="lastname" class="form-label">Nom</label>
                  <input type="text" pattern="^[a-zA-Z' -]+$" title="Veuillez entrer un nom valide (lettres, espaces, apostrophes et tirets seulement)" name="nom" id="nom" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label for="type" class="form-label">Type</label>
                  <select name="type" id="type" class="form-control" required>
                    <option value="">______</option>
                    <option value="1">Dossier d'apprentissage</option>
                    <option value="2">Dossier de Professionalisation</option>
                    <option value="3">Facturation Dossier Apprentissage</option>
                    <option value="4">Facturation Dossier Professionnalisation</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="prix_dossier" class="form-label">Prix / Dossier</label>
                  <input type="number" min="0" name="prix_dossier" id="prix_dossier" class="form-control"required>
                </div>
                <div class="mb-3">
                  <label for="prix_abonement" class="form-label">Prix / Abonenement</label>
                  <input type="number" min="0" name="prix_abonement" id="prix_abonement" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="caracteristique1" class="form-label">Description 1 Produit</label>
                  <input type="text" name="caracteristique1" id="caracteristique1" class="form-control"required>
                </div>

                <div class="mb-3">
                  <label for="caracteristique1" class="form-label">Description 2 Produit</label>
                  <input type="text" name="caracteristique2" id="caracteristique2" class="form-control" required>
                </div>

                <div class="mb-3">
                  <label for="caracteristique1" class="form-label">Description 3 Produit</label>
                  <input type="text" name="caracteristique3" id="caracteristique3" class="form-control">
                </div>

                <div class="mb-3">
                  <label for="caracteristique1" class="form-label">Description 4 Produit</label>
                  <input type="text" name="caracteristique4" id="caracteristique4" class="form-control">
                </div>
               
                <div class="modal-footer">
                  <button type="submit" class="btn btn-secondary">Ajouter</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <script>
         const addForm = document.getElementById("addProduitCerfaForm");

if (addForm) {
  addForm.addEventListener("submit", async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = {
      nom: formData.get("nom"),
      type: formData.get("type"),
      prix_dossier: formData.get("prix_dossier"),
      prix_abonement: formData.get("prix_abonement"),
      caracteristique1: formData.get("caracteristique1"),
      caracteristique2: formData.get("caracteristique2"),
      caracteristique3: formData.get("caracteristique3"),
      caracteristique4: formData.get("caracteristique4")
    };

    try {
      const response = await fetch("addProduitCerfa", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      });

      const result = await response.json();

      if (result.erreur) {
        showToast(false, result.erreur, "Erreur lors de l'ajout du produit cerfa");
      } else if (result.valid) {
        showToast(true, result.valid, "Ajout réussi");

        setTimeout(() => {
          window.location.href = "produitCerfa";
        }, 3000);
      }
    } catch (error) {
      console.error("Erreur réseau : ", error);
    }
  });
}

      </script>