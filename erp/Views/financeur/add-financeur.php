<!-- Modale pour ajouter un financeur -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addStudentModalLabel">Ajouter un financeur</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addStudentForm">
                                <div class="mb-3">
                                    <label for="firstname" class="form-label">Prénom</label>
                                    <input type="text" pattern="^[a-zA-Z' -]+$" title="Veuillez entrer un prénom valide (lettres, espaces, apostrophes et tirets seulement)" name="firstname" id="firstname" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="lastname" class="form-label">Nom</label>
                                    <input type="text" pattern="^[a-zA-Z' -]+$" title="Veuillez entrer un nom valide (lettres, espaces, apostrophes et tirets seulement)" name="lastname" id="lastname" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="type_financeur" class="form-label">Type de financeur</label>
                                    <select name="type_financeur" id="type_financeur" class="form-control" required>
                                        <option value="" disabled selected>-- Sélectionner une option --</option>
                                        <option value="type-1">type-1</option>
                                        <option value="type-2">type-2</option>
                                        <option value="type-3">type-3</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="id_entreprises" class="form-label">Entreprise</label>
                                    <select name="id_entreprises" id="id_entreprises" class="form-control" required>
                                        <?php foreach ($allentreprises as $entreprise) : ?>
                                            <option value="<?= htmlspecialchars($entreprise->id) ?>"><?= htmlspecialchars($entreprise->nomEntreprise) ?></option>
                                        <?php endforeach; ?>
                                    </select>
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
         document.getElementById("addStudentForm").addEventListener("submit", async function (e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const data = {
                        email: formData.get("email"),
                        firstname: formData.get("firstname"),
                        lastname: formData.get("lastname"),
                        type_financeur: formData.get("type_financeur"),
                        id_entreprises: formData.get("id_entreprises"),
                    };

                    try {
                        const response = await fetch("../../controller/Financeur/addFinanceurController.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify(data),
                        });
                        const result = await response.json();

                        if (result.erreur) {
                            showToast(false, "Erreur", result.erreur);
                        } else if (result.valid) {
                            showToast(true, "Succès", result.valid);
                            setTimeout(() => {
                                window.location.href = "./list-financeur.php";
                            }, 3000);
                        }
                    } catch (error) {
                        console.error("Erreur réseau : ", error);
                    }
                });
      </script>