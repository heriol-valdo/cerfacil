<!-- Modale pour ajouter un admin -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addStudentModalLabel">Ajouter un administrateur</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="addStudentForm">
                <div class="mb-3">
                  <label for="firstname" class="form-label">Prénom</label>
                  <input type="text" name="firstname" pattern="^[a-zA-Z' -]+$" title="Veuillez entrer un prénom valide (lettres, espaces, apostrophes et tirets seulement)" id="firstname" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="lastname" class="form-label">Nom</label>
                  <input type="text" name="lastname" pattern="^[a-zA-Z' -]+$" title="Veuillez entrer un nom valide (lettres, espaces, apostrophes et tirets seulement)" id="lastname" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" name="email" id="email" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="telephone" class="form-label">Telephone (facultatif)</label>
                  <input type="text" name="telephone" pattern="^\+?[0-9\s\-\(\)]+$" title="Veuillez entrer un numéro de téléphone valide (par exemple : +1234567890, 123-456-7890, (123) 456-7890)" id="telephone" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="lieu_travail" class="form-label">Lieu de travail (facultatif)</label>
                  <input type="text" name="lieu_travail" id="lieu_travail" class="form-control">
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
        const updateForm = document.getElementById("addStudentForm");

if (updateForm) {
  updateForm.addEventListener("submit", async function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = {
      email: formData.get("email"),
      firstname: formData.get("firstname"),
      lastname: formData.get("lastname"),
      telephone: formData.get("telephone"),
      lieu_travail: formData.get("lieu_travail"),
    };

    try {
      const response = await fetch("addAdmins", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      });

      const result = await response.json();

      if (result.erreur) {
        showToast(false, result.erreur, "Erreur lors l'ajout de l'administrateur");
      } else if (result.valid) {
        showToast(true, result.valid, "Mise à jour réussie");

        setTimeout(() => {
          window.location.href = "admins";
        }, 3000);
      }
    } catch (error) {
      console.error("Erreur réseau : ", error);
    }
  });
}
      </script>