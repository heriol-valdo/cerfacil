<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include __DIR__ . '/../elements/header.php';
require_once __DIR__ . '/../../requestFile/authRequet.php';
require_once __DIR__ . '/../../controller/User/profilController.php';
require_once __DIR__ . '/../../controller/Absences/voirAbsenceController.php';
require_once __DIR__ . '/../../controller/User/validTokenController.php';
?>

<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <title>Demande d'assistance | ErpFacil</title>
  <link rel="stylesheet" href="../../assets/style/assistanceStyle.css" />
  <link rel="stylesheet" href="../../assets/style/contentContainer.css" />
  <link rel="stylesheet" href="../../assets/style/cardStyle.css" />
  <link rel="stylesheet" href="../../assets/style/tableStyle.css" />
  <link rel="stylesheet" href="../../assets/style/profilstyle.css" />

  <!-- Google Font Link for Icons -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
</head>

<body>
  <div class="content-container">
    <header>
      <div class="header-container">
        <i class="fa-solid fa-question header-icon"></i>
        <div class="title">
          <h1>Demande d'assistance</h1>
        </div>
      </div>
    </header>
    <main>
      <div class="ticket-details-container">
        <div class="ticket-exchange-details">
          <div class="ticket-asking">
            <?php if ($_SESSION['user']['role'] == 1): ?>
              <div>
                <div class="ticket-section-title">
                  <p>Prénom et nom de l'étudiant</p>
                </div>
                <div class="ticket-asking-text"><?= $absenceDetails->data->firstname ?> <?= strtoupper($absenceDetails->data->lastname) ?></div>
              </div>
            <?php endif; ?>
            <div>
              <div class="ticket-section-title">
                <p>Début de l'absence</p>
              </div>
              <div class="ticket-asking-text"><?= $dateDebut ?></div>
            </div>
            <div>
              <div class="ticket-section-title">
                <p>Fin de l'absence</p>
              </div>
              <div class="ticket-asking-text">
                <?= $dateFin !== null ? $dateFin : "Aucune date de fin entrée" ?>
              </div>
            </div>
            <div>
              <div class="ticket-section-title">
                <p>Raison</p>
              </div>
              <div class="ticket-asking-text">
                <?= $absenceDetails->data->raison !== null ? $absenceDetails->data->raison : "Aucune raison entrée" ?>
              </div>
            </div>
            <div>
              <div class="ticket-section-title">
                <p>Justificatif</p>
              </div>
              <div class="ticket-asking-text-description">
                <?= $lienJustificatif !== null ? '<a target="_blank" href="https://apierp.lgx-creation.fr/'. htmlspecialchars($lienJustificatif) . '"><i class="fa-regular fa-file fa-2x" style="color:black"></i></a>'  : "Aucun document justificatif trouvé" ?>
              </div>
            </div>
          </div>
        </div>
        
        <div class="assistance-button-centre">
          <a href="list-absences.php" class="custom-button">Retour à la liste des absences</a>
        </div>

      </div>
    </main>
    <footer>
      <?php
      include __DIR__ . '/../elements/footer.php';
      ?>
    </footer>
  </div>
</body>