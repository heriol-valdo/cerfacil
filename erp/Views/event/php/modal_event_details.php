<!-- Event Info Modal -->
<div class="modal fade" id="eventInfoModal" tabindex="-1" role="dialog" aria-labelledby="eventInfoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventInfoModalLabel">Détails de l'évènement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 0px 20px">
                <div id="event-container">
                    <div style="flex: 1;">
                        <p><span id="eventTitle" style="font-size: 1.3em; "></span></p>
                        <div id="cours_details" style="display: flex; flex: 1;">
                            <p><strong><span id="matiere_nom"></span></strong> avec <span id="formateur_firstname"></span> <span id="formateur_lastname"></span></p>
                        </div>
                        <p><span id="eventStart"></span></p>

                        <div style="display: flex; gap: 10px;">
                            <p><strong>Modalités : </strong><span id="modalites_nom"></span></p>
                            <p><strong>Salle : </strong><span id="salles_nom"></span></p>
                        </div>

                        <div id="urlContainer">
                            <p><strong>Lien : </strong><a id="url" href="#"></a></p>
                        </div>
                        <p><strong>Description : </strong></p>
                        <div id="description"></div>
                    </div>

                    <div>
                        <p style="font-size:0.9em">Créé par <span id="author_firstname"></span> <span id="author_lastname"></span> (<span
                                id="author_role"></span>)</p>
                    </div>
                </div>

            </div>
            <?php if(in_array($_SESSION['user']['role'], [1,3,4])):?>
                <div class="modal-footer">
                    <i class="fa-regular fa-pen-to-square modal-footer-icons" id="editButton" title="Éditer l'évènement"></i>
                    <i class="fa-regular fa-trash-can modal-footer-icons" id="deleteButton" title="Supprimer l'évènement"></i>
                </div>
            <?php endif;?>
        </div>
    </div>
</div>