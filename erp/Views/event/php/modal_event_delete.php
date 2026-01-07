<!-- Delete Confirmation Modal -->
<div id="deleteConfirmationModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmer la suppression</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="deleteMessage">Êtes-vous sûr de vouloir supprimer cet évènement?</p>
        <div id="recurrenceOptions" style="display: none;">
          <p>Ce évènement est récurrent. Que souhaitez-vous faire?</p>
          <div class="btn-group-vertical w-100">
            <button type="button" class="btn btn-danger-dark w-100 mb-2" id="deleteAll">Supprimer tous les évènements</button>
            <button type="button" class="btn btn-danger-mid w-100 mb-2" id="deleteAfter">Supprimer les évènements futurs</button>
            <button type="button" class="btn btn-danger-light w-100" id="deleteDefault">Supprimer seulement cet évènement</button>
          </div>
        </div>
        <div class="btn-group-vertical w-100">
          <button type="button" class="btn btn-danger" id="confirmDelete" style="display: none;">Confirmer la suppression</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-top:5px;">Annuler</button>
        </div>
      </div>

    </div>
  </div>
</div>

<script src="../event/js/deleteEventModal.js"></script>