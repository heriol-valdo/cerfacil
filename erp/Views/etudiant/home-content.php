<?php // Etudiant : home-content.php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . '/../../controller/User/validTokenController.php';
require_once __DIR__ . '/../../requestFile/authRequet.php';
require_once __DIR__ . '/../../controller/Etudiant/homeContentController.php';
require_once __DIR__ . '/../../controller/Etudiant/pointageController.php';

?>
<div class="content-container-with-cards-column-alt">
  <div class="main-container-with-column-alt">
    <?php include_once __DIR__.'/../event/event_calendar.php'; ?>
  </div>
  <div class="cards-container-column-alt">
    <div class="container-card-column">
      <h3>Pointage <?= $has_pointed ? "en cours..." : "arrêté"; ?></h3>
      <p class="card-main-text">
          <?= $has_pointed 
              ? '<i class="fa-sharp fa-solid fa-play fa-lg" style="color: #63E6BE;"></i> ' . htmlspecialchars($currentTime) 
              : '<i class="fa-sharp fa-solid fa-pause fa-lg" style="color: #d5d5d5;"></i> ' . htmlspecialchars($currentTime); ?>
      </p>
      <form method="POST" action="#">
        <input type="submit" name="sendPointage" value="<?= $has_pointed ? "Arrêter le pointage" : "Pointer" ?>" class="card-button">
      </form>
      </a>
    </div>
    <div class="container-card-column">
      <h3>Absences cette semaine</h3>
      <p class="card-main-text"><?= $nbAbsenceSemaine ?></p>
      <p class="card-sub-text">absence<?= $nbAbsenceSemaine > 1 ? 's' : '' ?></p>
      <a href="../absences/list-absences.php" class="card-button">Voir la liste des absences</a>
    </div>
    <div class="container-card-column">
      <h3>Accéder à mes documents</h3>
      <img class="svgIcon" src="../../assets/svg/document.svg" />
      <a href="../../view/etudiant/documents.php" class="card-button">Voir mes documents</a>
    </div>
  </div>
</div>