<?php // Financeur : home-content.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../controller/User/validTokenController.php';
require_once __DIR__ . '/../../requestFile/authRequet.php';
require_once __DIR__ . '/../../controller/Financeur/homeContentController.php';

?>
<div class="content-container-with-cards-column">
    <div class="div-wrapper">
        <!-- Liste des centres -->
        <table class="table-duo-table">
            <caption>Liste des centres de formation</caption>
            <thead>
                <tr>
                    <th scope="col">Nom du centre</th>
                    <th scope="col">Code postal</th>
                    <th scope="col">Ville</th>
                    <th scope="col">Téléphone</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <?php if (isset($centreList->data)): ?>
                <?php foreach ($centreList->data as $centre): ?>
                    <tr class="centre-clickable">
                        <td><?= $centre->nomCentre ?> </td>
                        <td><?= $centre->codePostalCentre ?></td>
                        <td><?= $centre->villeCentre ?></td>
                        <td><?= $centre->telephoneCentre ?></td>
                        <td>
                            <form action="../centre-formation/centre-details.php" method="POST">
                                <input type="hidden" name="centreId" value="<?= $centre->id ?>" />
                                <div>
                                    <button type="submit" name="voirCentreDetails" class="submit-button">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr class="centre-clickable">
                    <td><?= $_SESSION['error-msg']; ?></td>
                </tr>
                <?php unset($_SESSION['error-msg']); ?>
            <?php endif; ?>
        </table>
    </div>
    <div class="cards-container-column">
        <div class="container-card-column">
            <h3>Réserver une formation</h3>
            <img class="svgIcon" src="../../assets/svg/calendar.svg" />
            <a href="reserver-formation.php" class="card-button">((Faire une demande))</a>
        </div>
        <div class="container-card-column">
            <h3>Demander une assistance</h3>
            <img class="svgIcon" src="../../assets/svg/question.svg" />
            <a href="../assistance/assistance.php" class="card-button">Ouvrir un ticket</a>
        </div>
    </div>
</div>
</div>

<script src="../../assets/script/calendarScript.js"></script>