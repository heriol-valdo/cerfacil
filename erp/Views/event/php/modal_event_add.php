<!-- Add event modal -->
<div class="modal fade" id="eventAddModal" tabindex="-1" role="dialog" aria-labelledby="eventAddModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventAddModalLabel">Ajouter un évènement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body mt-3">
                <form id="addEventForm" method="POST">
                    <input type="hidden" name="action" value="addEvent">
                    <div class="mb-3">
                        <label class="form-label" for="event_nom">Nom</label>
                        <input type="text" name="event_nom" placeholder="Nom de l'évènement" class="form-control"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="event_type">Type d'évènement</label>
                        <select id="event_type" class="form-control" name="event_type">
                            <option value="">-- Sélectionner un type d'évènement --</option>
                            <option value="1">Public</option>
                            <option value="3">Privé</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="event_id_modalites">Modalités</label>
                        <select class="form-control" id="event_id_modalites" name="event_id_modalites">
                            <option value="">-- Sélectionner la modalité --</option>
                            <option value="1">Présentiel</option>
                            <option value="2">Distanciel</option>
                            <option value="3">Autre</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div id="salle-selector-container" class="hidden">
                            <input type="text" id="salleSearchBar" class="form-control"
                                placeholder="Rechercher une salle..." oninput="filter('salleSearchBar', 'salle-item')">
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
                                            <?php foreach ($listSalles as $salle): ?>
                                                <tr class="salle-item">
                                                    <td><input type="radio" id="salle-<?= $salle->id ?>"
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
                        <div class="mb-3">
                            <label class="form-label" for="event_url">URL</label>
                            <input type="text" name="event_url" placeholder="Ajoute un lien cliquable sur l'évènement"
                                class="form-control">
                        </div>
                        <div class="row mb-3 form-check" style="padding-left: 30px;">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="recurrent-switch"
                                    name="recurrent-switch">
                                <label class="form-check-label" for="recurrent-switch">Répéter l'évènement ?</label>
                            </div>
                        </div>
                        <div id="no-recurrent-period-selector" class="container-fluid px-0">
                            <div class="row no-gutters">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label" for="event_debut">Date de début</label>
                                    <input class="form-control" type="datetime-local" name="event_debut"
                                        id="event_debut" placeholder="Date de début" required>
                                </div>
                                <div class="col-md-6 mb-2" style="padding-left: 10px;">
                                    <label class="form-label" for="event_fin">Date de fin</label>
                                    <input class="form-control" type="datetime-local" name="event_fin" id="event_fin"
                                        placeholder="Date de fin" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <span id="dateError" class="error-message" style="display:none">La date de fin doit être
                                    après la date de début</span>
                            </div>
                        </div>

                        <div class="hidden container-fluid px-0" id="recurrent-period-selector">
                            <div class="row no-gutters">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label" for="event_heureDebut">Heure de début</label>
                                    <input class="form-control" type="time" name="event_heureDebut"
                                        id="event_heureDebut" placeholder="Heure de début">
                                </div>
                                <div class="col-md-6 mb-2" style="padding-left: 10px;">
                                    <label class="form-label" for="event_heureFin">Heure de fin</label>
                                    <input class="form-control" type="time" name="event_heureFin" id="event_heureFin"
                                        placeholder="Heure de fin">
                                </div>

                            </div>
                            <span id="recurrentHeureError" class="error-message" style="display:none">L'heure de fin
                                doit
                                être
                                après
                                l'heure de début</span><br />
                            <div class="mb-3">
                                <div id="jours-selector-container">
                                    <label class="form-label">Répéter le</label>
                                    <div class="jours-selector-container">
                                        <div class="add-event-jour-button" data-day="1">L</div>
                                        <div class="add-event-jour-button" data-day="2">M</div>
                                        <div class="add-event-jour-button" data-day="3">M</div>
                                        <div class="add-event-jour-button" data-day="4">J</div>
                                        <div class="add-event-jour-button" data-day="5">V</div>
                                        <div class="add-event-jour-button" data-day="6">S</div>
                                        <div class="add-event-jour-button" data-day="7">D</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden inputs to store selected days -->
                            <input type="hidden" name="jours[]" id="jour-0" value="" class="add-event-hidden-input">
                            <input type="hidden" name="jours[]" id="jour-1" value="" class="add-event-hidden-input">
                            <input type="hidden" name="jours[]" id="jour-2" value="" class="add-event-hidden-input">
                            <input type="hidden" name="jours[]" id="jour-3" value="" class="add-event-hidden-input">
                            <input type="hidden" name="jours[]" id="jour-4" value="" class="add-event-hidden-input">
                            <input type="hidden" name="jours[]" id="jour-5" value="" class="add-event-hidden-input">
                            <input type="hidden" name="jours[]" id="jour-6" value="" class="add-event-hidden-input">

                            <div class="d-flex align-items-center mb-3">
                                <label class="form-label" for="frequence" style="margin: 0 10px 0 0;">Répéter
                                    tou(te)s
                                    les
                                    :</label>
                                <input class="form-control" type="number" name="frequence[]" id="number-input"
                                    value="1" min="1" style="width: 80px; margin-right: 10px;">
                                <select class="form-control" name="frequence[]" id="frequence" style="width: 120px;">
                                    <option value="week">Semaine(s)</option>
                                    <option value="month">Mois</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="event_dateDebut">Commence à partir de :</label>
                                <input class="form-control" type="date" name="event_dateDebut" id="event_dateDebut"
                                    placeholder="Date de début">
                            </div>
                            <div class="mb-3">
                                <p>Se termine :</p>
                                <div class="d-flex align-items-center">
                                    <!-- First Option: Le -->
                                    <input type="radio" name="eventOption" id="eventOption1" value="date"
                                        style="margin-right: 10px;">
                                    <label class="form-label" for="eventOption1" style="margin: 0 10px 0 0;">Le
                                        :</label>
                                    <input class="form-control" type="date" name="event_dateFin" id="event_dateFin"
                                        placeholder="Date" disabled
                                        style="width: auto; display: inline-block; margin-right: 20px;">
                                    <!-- Second Option: Après -->
                                    <input type="radio" name="eventOption" id="eventOption2" value="occurrences"
                                        style="margin-right: 10px;">
                                    <label class="form-label" for="eventOption2" style="margin: 0 10px 0 0;">Après
                                        :</label>
                                    <input class="form-control" type="number" name="event_nbOccurences"
                                        id="event_nbOccurences" placeholder="0" disabled
                                        style="width: 80px; display: inline-block; margin-right: 10px;">
                                    <span>fois</span>
                                </div>
                            </div>
                            <span id="recurrentDateError" class="error-message" style="display:none">La date de fin doit
                                être après la date de début</span><br />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="event_description">Description</label>
                            <textarea id="event_description" name="event_description"
                                placeholder="Description de l'évènement" style="width:100%"></textarea>
                        </div>

                        <div class="mb-3">
                            <div id="sessions-selector-container" class="hidden">
                            <input type="text" id="addEvent-sessionSearchBar" class="form-control"
                            placeholder="Rechercher une session..." oninput="filter('addEvent-sessionSearchBar', 'addEvent-session-item')">
                                <label class="form-label" for="event-users[]">Sessions participantes :</label>
                                <div class="sessions-selector-list">
                                    <?php foreach ($currentSessionsList as $session): ?>
                                        <div class="addEvent-session-item">
                                            <input type="checkbox" id="session-<?= $session->id ?>" name="event_sessions[]"
                                                value="<?= $session->id ?>" />
                                            <label for="session-<?= $session->id ?>"><?= $session->nomSession ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="button" id="toggleUserList" class="btn btn-secondary hidden">Ajouter d'autres
                                utilisateurs</button>
                            <div id="user-selector-container" class="hidden">
                                <input type="text" id="userSearchBar" class="form-control"
                                    placeholder="Rechercher un utilisateur..."
                                    oninput="filter('userSearchBar', 'user-item')">

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
                                                                <input type="checkbox" id="user-<?= $participant->id_users ?>"
                                                                    value="<?= $participant->id_users ?>"
                                                                    name="event_users[]" />
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
                                <input class="form-check-input" type="checkbox" id="notification-switch"
                                    name="notification-switch">
                                <label class="form-check-label" for="notification-switch">Notifier les participants par email ?</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <input type="submit" name="isAddEvent"
                                class="btn btn-lgx"
                                id="submitButton" value="Ajouter l'évènement" disabled />
                        </div>
                        
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script src="../event/js/addEvent.js"></script>