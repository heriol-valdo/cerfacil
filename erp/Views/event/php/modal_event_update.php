<link rel="stylesheet" href="assets/css/modal_event_add.css" />

<!-- Update event modal -->
<div class="modal fade" id="eventUpdateModal" tabindex="-1" role="dialog" aria-labelledby="eventUpdateModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventUpdateModalLabel">Modifier un évènement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body mt-3">
                <form id="updateEventForm" method="POST">
                    <input type="hidden" name="action" value="updateEvent">
                    <input type="hidden" name="event_id_centre"
                        value="<?= $_SESSION['user']['role'] == 1 ? $_POST['id_centres_de_formation'] : null; ?>">
                    <div class="mb-3">
                        <label class="form-label" for="event_nom">Nom</label>
                        <input type="text" name="event_nom" placeholder="Nom de l'évènement" id="edit-event_nom"
                            class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="event_type">Type d'évènement</label>
                        <select id="edit-event_type" class="form-control" name="event_type">
                            <option value="">-- Sélectionner un type d'évènement --</option>
                            <option value="1">Public</option>
                            <option value="3">Privé</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="event_id_modalites">Modalités</label>
                        <select class="form-control" id="edit-event_id_modalites" name="event_id_modalites">
                            <option value="">-- Sélectionner la modalité --</option>
                            <option value="1">Présentiel</option>
                            <option value="2">Distanciel</option>
                            <option value="3">Autre</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div id="edit-salle-selector-container" class="hidden">
                            <input type="text" id="edit-salleSearchBar" class="form-control"
                                placeholder="Rechercher une salle..."
                                oninput="filter('edit-salleSearchBar', 'salle-item')">
                            <div id="userList">
                                <table class="table table-hover table-striped" style="margin-bottom:0;">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th></th>
                                            <th>Nom de la salle</th>
                                            <th>Nombre de places</th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="table-body-container" style="max-height: 200px; overflow-y: auto;">
                                    <table class="table table-hover table-striped">
                                        <tbody>
                                            <?php foreach ($list_salles as $salle): ?>
                                                <tr class="salle-item">
                                                    <td><input type="radio" id="edit-salle-<?= $salle->id ?>"
                                                            value="<?= $salle->id ?>" name="event_id_salles" /></td>
                                                    <td><?= $salle->nom ?></td>
                                                    <td><?= $salle->capacite_accueil ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="event_url">URL</label>
                        <input type="text" name="event_url" id="edit-event_url"
                            placeholder="Ajoute un lien cliquable sur l'évènement" class="form-control">
                    </div>
                    <div class="row mb-3 form-check">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="edit-recurrent-switch"
                                name="recurrent-switch">
                            <label class="form-check-label" for="recurrent-switch">Répéter l'évènement ?</label>
                        </div>
                    </div>
                    <div id="edit-no-recurrent-period-selector" class="container-fluid px-0">
                        <div class="row no-gutters">
                            <div class="col-md-3 mb-2" style="padding-left: 0px;" id="edit-jourDebut-container">
                                <label class="form-label" for="event_jourDebut">Date de début</label>
                                <input class="form-control" type="date" name="event_jourDebut" id="edit-event_jourDebut"
                                    placeholder="Date de début" required>
                            </div>
                            <div class="col-md-3 mb-2" style="padding-left: 0px;" id="edit-heureDebut-container">
                                <label class="form-label" for="event_heureDebut">Heure de début</label>
                                <input class="form-control" type="time" name="event_heureDebut"
                                    id="edit-event_heureDebut" placeholder="Heure de début" required>
                            </div>
                            <div class="col-md-3 mb-2" style="padding-left: 10px;" id="edit-jourFin-container">
                                <label class="form-label" for="event_jourFin">Date de fin</label>
                                <input class="form-control" type="date" name="event_jourFin" id="edit-event_jourFin"
                                    placeholder="Date de fin" required>
                            </div>
                            <div class="col-md-3 mb-2" style="padding-left: 0px; padding-right: 0px;"
                                id="edit-heureFin-container">
                                <label class="form-label" for="event_heureFin">Heure de fin</label>
                                <input class="form-control" type="time" name="event_heureFin" id="edit-event_heureFin"
                                    placeholder="Heure de fin" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <span id="edit-dateError" class="error-message" style="display:none">La date de fin doit
                                être
                                après la date de début</span>
                            <span id="edit-recurrentHeureError" class="error-message" style="display:none">L'heure de
                                fin
                                doit
                                être après l'heure de début</span><br />
                        </div>
                    </div>
                    <div class="hidden container-fluid px-0" id="edit-recurrent-period-selector">
                        <div class="row mb-3 form-check" style="padding-left:0; margin-left:0">
                            <div class="form-check" style="margin-left:0; padding-left:0;">
                                <input class="form-check-input" type="checkbox" id="edit-all-switch"
                                    name="edit-all-switch" style="margin-left:0; margin-right:10px;">
                                <label class="form-check-label" for="edit-all-switch">Modifier tous les évènements
                                    existants ?</label>
                                <span id="edit-edit-all-info" class="error-message" style="display:none">Attention :
                                    Ceci modifiera également les évènements créés avant la date du jour</span><br />
                            </div>
                        </div>
                        <div class="mb-3">
                            <div id="edit-jours-selector-container">
                                <label class="form-label">Répéter le</label>
                                <div class="jours-selector-container">
                                    <div class="edit-jour-button" data-day="1">L</div>
                                    <div class="edit-jour-button" data-day="2">M</div>
                                    <div class="edit-jour-button" data-day="3">M</div>
                                    <div class="edit-jour-button" data-day="4">J</div>
                                    <div class="edit-jour-button" data-day="5">V</div>
                                    <div class="edit-jour-button" data-day="6">S</div>
                                    <div class="edit-jour-button" data-day="7">D</div>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden inputs to store selected days -->
                        <input type="hidden" name="jours[]" id="edit-jour-0" value="" class="edit-hidden-input">
                        <input type="hidden" name="jours[]" id="edit-jour-1" value="" class="edit-hidden-input">
                        <input type="hidden" name="jours[]" id="edit-jour-2" value="" class="edit-hidden-input">
                        <input type="hidden" name="jours[]" id="edit-jour-3" value="" class="edit-hidden-input">
                        <input type="hidden" name="jours[]" id="edit-jour-4" value="" class="edit-hidden-input">
                        <input type="hidden" name="jours[]" id="edit-jour-5" value="" class="edit-hidden-input">
                        <input type="hidden" name="jours[]" id="edit-jour-6" value="" class="edit-hidden-input">

                        <div class="d-flex align-items-center mb-3">
                            <label class="form-label" for="frequence" style="margin: 0 10px 0 0;">Répéter
                                tou(te)s
                                les
                                :</label>
                            <input class="form-control" type="number" name="frequence[]" id="edit-number-input"
                                value="1" min="1" style="width: 80px; margin-right: 10px;">
                            <select class="form-control" name="frequence[]" id="edit-frequence" style="width: 120px;">
                                <option value="week">Semaine(s)</option>
                                <option value="month">Mois</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="event_dateDebut">Commence à partir de :</label>
                            <input class="form-control" type="date" name="event_dateDebut" id="edit-event_dateDebut"
                                placeholder="Date de début">
                        </div>
                        <div class="mb-3">
                            <p>Se termine :</p>
                            <div class="d-flex align-items-center">
                                <!-- First Option: Le -->
                                <input type="radio" name="eventOption" id="edit-eventOption1" value="date"
                                    style="margin-right: 10px;">
                                <label class="form-label" for="eventOption1" style="margin: 0 10px 0 0;">Le
                                    :</label>
                                <input class="form-control" type="date" name="event_dateFin" id="edit-event_dateFin"
                                    placeholder="Date" disabled
                                    style="width: auto; display: inline-block; margin-right: 20px;">
                                <!-- Second Option: Après -->
                                <input type="radio" name="eventOption" id="edit-eventOption2" value="occurrences"
                                    style="margin-right: 10px;">
                                <label class="form-label" for="eventOption2" style="margin: 0 10px 0 0;">Après
                                    :</label>
                                <input class="form-control" type="number" name="event_nbOccurences"
                                    id="edit-event_nbOccurences" placeholder="0" disabled
                                    style="width: 80px; display: inline-block; margin-right: 10px;">
                                <span>fois</span>
                            </div>
                        </div>
                        <div>
                            <span id="edit-recurrentDateError" class="error-message" style="display:none">La date de fin
                                doit
                                être après la date de début</span><br />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="event_description">Description</label>
                        <textarea id="edit-event_description" name="event_description"
                            placeholder="Description de l'évènement" style="width:100%"></textarea>
                    </div>

                    <div class="mb-3">
                        <div id="edit-sessions-selector-container" class="hidden">
                            <input type="text" id="editEvent-sessionSearchBar" class="form-control"
                            placeholder="Rechercher une session..." oninput="filter('editEvent-sessionSearchBar', 'editEvent-session-item')">
                            <label class="form-label" for="event-sessions[]">Sessions participantes :</label>
                            <div class="sessions-selector-list">
                                <?php foreach ($currentSessionsList as $session): ?>
                                    <div class="editEvent-session-item">
                                        <input type="checkbox" id="edit-session-<?= $session->id ?>" name="event_sessions[]"
                                            value="<?= $session->id ?>" />
                                        <label for="edit-session-<?= $session->id ?>"><?= $session->nomSession ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="button" id="edit-toggleUserList" class="btn btn-secondary hidden">Ajouter d'autres
                            utilisateurs</button>
                        <div id="edit-user-selector-container" class="hidden">
                            <input type="text" id="edit-userSearchBar" class="form-control"
                                placeholder="Rechercher un utilisateur..."
                                oninput="filter('edit-userSearchBar', 'user-item')">

                            <div id="userList">
                                <table class="table table-hover table-striped">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th></th>
                                            <th>Nom / Prénom</th>
                                            <th>Session</th>
                                            <th>Rôle</th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="user-table-body-container" style="max-height: 200px; overflow-y: auto;">
                                    <table class="table table-hover table-striped">
                                        <tbody>
                                            <?php foreach ($currentParticipantsList as $session_name => $unique_session): ?>
                                                <?php if (!is_array($unique_session))
                                                    continue; ?>
                                                <?php foreach ($unique_session as $participant): ?>
                                                    <tr class="user-item">
                                                        <td>
                                                            <input type="checkbox" id="edit-user-<?= $participant->id_users ?>"
                                                                value="<?= $participant->id_users ?>" name="event_users[]" />
                                                        </td>
                                                        <td><?= strtoupper($participant->lastname) ?>
                                                            <?= $participant->firstname ?>
                                                        </td>
                                                        <td><?= $session_name ?></td>
                                                        <td><?= $participant->role ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 form-check" style="padding-left: 30px;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="update-notification-switch"
                                name="update-notification-switch">
                            <label class="form-check-label" for="update-notification-switch">Notifier les participants par
                                email ?</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <input type="submit" name="isEditEvent"
                        class="btn btn-lgx"
                        id="edit-submitButton" value="Modifier l'évènement" disabled />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../event/js/updateEventModal.js"></script>