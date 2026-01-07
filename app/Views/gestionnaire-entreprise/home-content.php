<?php // GestionnaireCentre : home-content.php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

  require_once __DIR__ .'/../../requestFile/authRequet.php';
  require_once __DIR__ . '/../../controller/GestionnaireEntreprise/homeContentController.php';
  require_once __DIR__ . '/../../controller/User/validTokenController.php';

?>

<div class="content-container-with-cards-column">
    <div class="cards-container-column">
        <div class="container-card-column">
            <h3>Absences cette semaine</h3>
            <p class="card-main-text"><?= $nbAbsenceSemaine ?></p>
            <p class="card-sub-text">absence<?= $nbAbsenceSemaine > 1 ? 's' : '' ?></p>
            <a href="absences-list.php" class="card-button">((Voir la liste des absences))</a>
        </div>
        <div class="container-card-column">
            <h3>Réserver une formation</h3>
            <img class="svgIcon" src="../../assets/svg/calendar.svg"/>
            <a href="absences-list.php" class="card-button">((Faire une demande))</a>
        </div>
        <div class="container-card-column">
            <h3>Demander une assistance</h3>
            <img class="svgIcon" src="../../assets/svg/question.svg"/>
            <a href="../../view/user/assistance.php" class="card-button">Ouvrir un ticket</a>
        </div>
    </div>

    <?php require_once __DIR__. '/../../assets/php/homeCalendar.php';?>

</div>