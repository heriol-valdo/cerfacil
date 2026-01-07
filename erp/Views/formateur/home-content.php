<?php // Formateur : home-content.php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../../controller/User/validTokenController.php';
require_once __DIR__ . '/../../requestFile/authRequet.php';
require_once __DIR__ . '/../../controller/Formateur/homeContentController.php';

?>
<div class="content-container-with-cards-column">
  <div class="main-container-with-column-cal">
    <?php include_once __DIR__ . '/../event/event_calendar.php'; ?>
  </div>
  <div class="cards-container-column">
  <div class="container-card-column">
    <h3>Absences cette semaine</h3>
    <p class="card-main-text"><?= $nbAbsenceSemaine ?></p>
    <p class="card-sub-text">absence<?= $nbAbsenceSemaine > 1 ? 's' : '' ?></p>
    <a href="../absences/list-absences.php" class="card-button">Voir la liste des absences</a>
  </div>
  <div class="container-card-column">
    <h3>Absences non justifiées</h3>
    <p class="card-main-text"><?= $nbAbsenceNonJustifie ?></p>
    <p class="card-sub-text">absence<?= $nbAbsenceNonJustifie > 1 ? 's' : '' ?></p>
    <a href="../absences/list-absences.php" class="card-button">Voir la liste des absences</a>
  </div>
  <div class="container-card-column">
    <h3>Demander une assistance</h3>
    <img class="svgIcon" src="../../assets/svg/question.svg" />
    <a href="../../view/assistance/assistance.php" class="card-button">Ouvrir un ticket</a>
  </div>
</div>


