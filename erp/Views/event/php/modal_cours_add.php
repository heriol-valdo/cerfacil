<?php
// Récupérer le token de l'utilisateur
$userToken = $_SESSION['user'] ?? ''; 
$preselectedSession = $_GET['selectedSession'] ?? '';
?>

<div class="modal fade" id="coursAddModal" tabindex="-1" role="dialog" aria-labelledby="coursAddModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="coursAddModalLabel">Ajouter un cours</h5>
            </div>
            <div class="modal-body">
                <form id="addCoursForm">

                    <input type="hidden" id="idCentreFormation" name="idCentreFormation" value="<?php echo htmlspecialchars(json_encode((int)($userToken['role'] == 1 ? $_GET['centreId'] : $userToken['centre'])), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" id="userToken" name="userToken" value="<?php echo htmlspecialchars(json_encode($userToken), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="form-group">
                        <label for="nom">Nom du cours *</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>

                    <div class="form-group">
                        <label id="sessions-label"><?php echo $preselectedSession ? 'Sessions supplémentaires à ajouter' : 'Sessions à ajouter'; ?></label>
                        <input type="hidden" name="preselected_session" value="<?php echo htmlspecialchars($preselectedSession); ?>">
                        <div id="sessions-container" class="border p-3" style="max-height: 200px; overflow-y: auto;">
                            <?php foreach ($sessionsList as $session): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="additional_sessions[]"
                                        id="session_<?= $session->id; ?>" value="<?= $session->id; ?>"
                                        <?php 
                                        if ($session->id == $preselectedSession) {
                                            echo 'checked disabled';
                                        } elseif (!$preselectedSession) {
                                            echo 'checked';
                                        }
                                        ?>>
                                    <label class="form-check-label" for="session_<?= $session->id; ?>">
                                        <?= htmlspecialchars($session->nomSession); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <small id="sessions-help" class="form-text text-muted">
                            <?php echo $preselectedSession ? 
                                "Cochez les sessions supplémentaires auxquelles ce cours s'applique." : 
                                "Cochez les sessions auxquelles ce cours s'applique."; ?>
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="id_matieres">Matière *</label>
                        <select class="form-control" id="id_matieres" name="id_matieres" required>
                            <option value="" disabled selected>Sélectionnez une matière</option>
                        </select>
                        <div id="matiere-loading" style="display: none;">Chargement des matières...</div>
                        <div id="matiere-empty" class="text-muted" style="display: none;">Aucune matière disponible pour les sessions sélectionnées.</div>
                    </div>

                    <div class="form-group" id="formateurGroup">
                        <label for="id_formateurs">Formateur *</label>
                        <select class="form-control" id="id_formateurs" name="id_formateurs" required>
                            <option value="" disabled selected>Sélectionnez un formateur</option>
                        </select>
                        <div id="formateur-loading" style="display: none;">Chargement des formateurs...</div>
                        <div id="formateur-empty" class="text-muted" style="display: none;">Aucun formateur disponible pour les sessions sélectionnées.</div>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="is_recurrent" name="is_recurrent">
                        <label class="form-check-label" for="is_recurrent">Événement récurrent</label>
                    </div>

                    <div class="form-group">
                        <label for="id_modalites">Modalités *</label>
                        <select class="form-control" id="id_modalites" name="id_modalites" required>
                            <option value="1">Présentiel</option>
                            <option value="2">Distanciel</option>
                            <option value="3">Hybride</option>
                        </select>
                    </div>

                    <div id="modalite-fields">

                        <div class="form-group" id="salleGroup">
                            <label for="id_salles">Salle de cours *</label>
                            <select class="form-control" id="id_salles" name="id_salles">
                                <option value="" disabled selected>Sélectionnez une salle</option>
                            </select>
                            <div id="salle-loading" style="display: none;">Chargement des salles...</div>
                            <div id="salle-empty" class="text-muted" style="display: none;">Aucune salle disponible pour les sessions sélectionnées.</div>
                        </div>
                        <div class="form-group" id="urlGroup" style="display: none;">
                            <label for="url">URL du cours *</label>
                            <input type="url" class="form-control" id="addCoursUrl" name="url">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>

                    <div id="date-fields">
                        <div id="non-recurrent-fields">
                            <div class="form-group">
                                <label for="debut">Date de début *</label>
                                <input type="datetime-local" class="form-control" id="debut" name="debut" required>
                            </div>
                            <div class="form-group">
                                <label for="fin">Date de fin *</label>
                                <input type="datetime-local" class="form-control" id="fin" name="fin" required>
                            </div>
                        </div>
                        <div id="recurrent-fields" style="display: none;">
                            <div class="form-group">
                                <label for="heureDebut">De *</label>
                                <input type="time" class="form-control" id="heureDebut" name="heureDebut">
                            </div>
                            <div class="form-group">
                                <label for="heureFin">À *</label>
                                <input type="time" class="form-control" id="heureFin" name="heureFin">
                            </div>
                            <div class="form-group">
                                <label>Répéter le *</label>
                                <div id="jours-selector-container">
                                    <div class="jours-selector-container">
                                        <div class="jour-button" data-day="1">L</div>
                                        <div class="jour-button" data-day="2">M</div>
                                        <div class="jour-button" data-day="3">M</div>
                                        <div class="jour-button" data-day="4">J</div>
                                        <div class="jour-button" data-day="5">V</div>
                                        <div class="jour-button" data-day="6">S</div>
                                        <div class="jour-button" data-day="7">D</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="frequence">Répéter toutes les</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="numberFrequence[]" name="numberFrequence[]" min="1" value="1" required>
                                    <select class="form-control" id="frequenceUnit" name="frequenceUnit">
                                        <option value="semaine">semaine(s)</option>
                                        <option value="mois">mois</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="dateDebut">Commence à partir de *</label>
                                <input type="date" class="form-control" id="dateDebut" name="dateDebut">
                            </div>
                            <div class="form-group">
                                <label>Termine</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="finType" id="finDate" value="date" checked>
                                    <label class="form-check-label" for="finDate">
                                        Le <input type="date" class="form-control" id="dateFin" name="dateFin">
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="finType" id="finOccurences" value="occurences">
                                    <label class="form-check-label" for="finOccurences">
                                        Après <input type="number" class="form-control" id="nbOccurences" name="nbOccurences" min="1"> occurrences
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Terminé</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../event/js/addCours.js"></script>