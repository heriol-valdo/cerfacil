<!-- Modale pour ajouter un gestionnaire d'entreprise -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addStudentModalLabel">Ajouter un gestionnaire d'entreprise</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addStudentForm">
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
                                    <label for="telephone" class="form-label">Telephone (facultatif)</label>
                                    <input type="text" pattern="^\+?[0-9\s\-\(\)]+$" title="Veuillez entrer un numéro de téléphone valide (par exemple : +1234567890, 123-456-7890, (123) 456-7890)" name="telephone" id="telephone" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="lieu_travail" class="form-label">Lieu de travail (facultatif)</label>
                                    <input type="text" name="lieu_travail" id="lieu_travail" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="id_entreprises" class="form-label">Entreprise</label>
                                    <select name="id_entreprises" id="id_entreprises" class="form-control">
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
                        telephone: formData.get("telephone"),
                        lieu_travail: formData.get("lieu_travail"),
                        id_entreprises: formData.get("id_entreprises"),
                    };

                    try {
                        const response = await fetch("../../controller/GestionnaireEntreprise/addGestionnaireEntrepriseController.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify(data),
                        });

                        const result = await response.json();
                        if (response.ok) {
                            showToast(true, result.valid, "Ajout réussi");
                            setTimeout(() => {
                                window.location.href = "./list-gestionnaire-entreprise.php";
                            }, 3000);
                        } else {
                            showToast(false, "Erreur", result.message || "Une erreur est survenue.");
                        }
                    } catch (error) {
                        console.error("Erreur réseau : ", error);
                        showToast(false, "Erreur réseau", "Vérifiez votre connexion.");
                    }
                });

            </script>