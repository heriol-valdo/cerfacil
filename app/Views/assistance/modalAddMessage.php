<div class="modal fade" id="addMessageModal" tabindex="-1" aria-labelledby="addMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMessageModalLabel">Écrire un message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addMessageForm">
                <div class="modal-body">
                    <input type="hidden" name="ticketId" value="<?= $_POST['ticketId'] ?>">
                    <div class="mb-3">
                        <label for="contenu">Message</label>
                        <textarea placeholder="Repondre à la demande" name="contenu" style="width:100%; height:200px"
                            class="form-control" required></textarea>
                    </div>
                    <?php if ($_SESSION['user']['role'] == 1): ?>
                        <div class="mb-3" id="entrepriseCentreSelect">
                            <label for="etat-ticket" class="form-label">État de la demande</label>
                            <select name="etat" id="etat-ticket" required>
                                <option class="option" value="">Sélectionner l'état de la demande</option>
                                <option class="option" name="En cours de traitement" value="2">En cours de traitement</option>
                                <option class="option" name="Résolu" value="3">Résolu</option>
                                <option class="option" name="Abandonné" value="4">Abandonné</option>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-secondary">Envoyer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script> 
document.getElementById('addMessageForm').addEventListener('submit', async function(event) {
        event.preventDefault(); // Empêche le rechargement de la page lors de la soumission

        // Collecte les données du formulaire
        const formData = new FormData(this);

        try {
            // Envoi des données du formulaire via fetch
            const response = await fetch('repondreTicket', {
                method: 'POST',
                body: formData
            });

            // Vérifie la réponse du serveur
            const result = await response.json();

            // Si la réponse contient un message de succès
            if (result.valid) {
                showToast(true, result.message, "Succès");
                // Ferme la modal après une seconde (temps d'attente pour l'animation de succès)
                setTimeout(() => {
                    $('#addMessageModal').modal('hide');
                    window.location.href = 'assistance';
                }, 1000);
            } else {
                // Affiche le message d'erreur
                showToast(false, result.erreur, "Erreur");
            }
        } catch (error) {
            // En cas d'erreur (réseau, etc.), afficher une erreur générique
            showToast(false, "Une erreur s'est produite lors de l'envoi", "Erreur");
        }
    });
  
</script>
<script src="../../erp/assets/script/addForm.js"></script>