<!-- Modale pour ajouter un étudiant -->
<div class="modal fade" id="addClientCerfaModal" tabindex="-1" aria-labelledby="addClientCerfaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addStudentModalLabel">Ajouter un client Cerfa</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="addClienCerfaForm">
                <div class="mb-3">
                  <label for="firstname" class="form-label">Prénom</label>
                  <input type="text" pattern="^[a-zA-Z' -]+$" title="Veuillez entrer un prénom valide (lettres, espaces, apostrophes et tirets seulement)" name="firstname" id="firstname" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="lastname" class="form-label">Nom</label>
                  <input type="text" pattern="^[a-zA-Z' -]+$" title="Veuillez entrer un nom valide (lettres, espaces, apostrophes et tirets seulement)" name="lastname" id="lastname" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" name="email" id="email" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="adressePostale" class="form-label">Adresse</label>
                  <input type="text" name="adressePostale" id="adressePostale" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="codePostal" class="form-label">Code postal</label>
                  <input type="number" min="0" name="codePostal" id="codePostal" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="ville" class="form-label">Ville</label>
                  <input type="text" name="ville" id="ville" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="telephone" class="form-label">Telephone</label>
                  <input type="text" name="telephone" id="telephone" class="form-control">
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
         const addForm = document.getElementById("addClienCerfaForm");

if (addForm) {
  addForm.addEventListener("submit", async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = {
      email: formData.get("email"),
      firstname: formData.get("firstname"),
      lastname: formData.get("lastname"),
      adressePostale: formData.get("adressePostale"),
      codePostal: formData.get("codePostal"),
      ville: formData.get("ville"),
      telephone: formData.get("telephone")
    };

    try {
      const response = await fetch("addClientCerfa", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      });

      const result = await response.json();

      if (result.erreur) {
        showToast(false, result.erreur, "Erreur lors de l'ajout du client cerfa");
      } else if (result.valid) {
        showToast(true, result.valid, "Ajout réussi");

        setTimeout(() => {
          window.location.href = "clientCerfa";
        }, 3000);
      }
    } catch (error) {
      console.error("Erreur réseau : ", error);
    }
  });
}

      </script>