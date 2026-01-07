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
  <title>Demande d'assistance</title>
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/style/absenceStyle.css" />
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
          <h1>Modifier une absence</h1>
        </div>
      </div>
    </header>
    <main>
      <div class="ticket-details-container">
        <form id="updateAbsenceForm" enctype="multipart/form-data">
          <?php if ($_SESSION['user']['role'] == 1): ?>
            <div>
              <div class="form-input-container">
                <p class="ticket-section-title">Prénom et nom de l'étudiant</p>
              </div>
              <div class="ticket-asking-text"><?= $absenceDetails->data->firstname ?>
                <?= strtoupper($absenceDetails->data->lastname) ?>
              </div>
            </div>
          <?php endif; ?>
          <div class="date-picker">
            <div class="form-date-container">
              <label for="dateDebut" class="ticket-section-title">Début de l'absence</label>
              <input type="date" name="dateDebut" id="dateDebut" class="form-date"
                value="<?= $absenceDetails->data->dateDebut ?>" />
            </div>
            <div class="form-date-container">
              <label for="dateFin" class="ticket-section-title">Fin de l'absence</label>
              <?php if ($absenceDetails->data->dateFin !== NULL): ?>
                <input type="date" name="dateFin" id="dateFin" class="form-date"
                  value="<?= $absenceDetails->data->dateFin ?>" />
              <?php else: ?>
                <input type="date" name="dateFin" id="dateFin" class="form-date">
                <p>Aucune date de fin enregistrée pour le moment</p>
              <?php endif; ?>
            </div>
          </div>
          <div class="form-input-container">
            <label for="justificatif" class="ticket-section-title">Justificatif</label>
            <?php if ($lienJustificatif !== null): ?>
              <div class="ticket-asking-text-description">
                <?= $lienJustificatif !== null ? '<a target="_blank" href="https://apierp.lgx-creation.fr/' . htmlspecialchars($lienJustificatif) . '"><i class="fa-regular fa-file fa-2x" style="color:black"></i></a>' : "Aucun document justificatif trouvé" ?>
              </div>
              <p>Attention : Ajouter un nouveau justificatif effacera l'ancien</p>
            <?php else: ?>
              <p>Aucun justificatif enregistré pour le moment</p>
            <?php endif; ?>
            <input style="font-size: 1em;" type="file" name="justificatif" id="justificatif"
              accept="application/pdf,image/jpeg,image/png" class="form-control">
          </div>
          <div class="form-input-container">
            <label for="raison" class="ticket-section-title">Raison</label>
            <input type="text" name="raison" id="raison" class="input-raison"
              value="<?= $absenceDetails->data->raison ?>">
          </div>
          <div class="bottom-btn">
            <input type="hidden" name="etudiantId" value="<?= $absenceDetails->data->etudiants_id ?>" />
            <input type="hidden" name="absenceId" value="<?= $absenceDetails->data->id ?>" />
            <a href="list-absences.php" class="custom-button">Retour à la liste des absences</a>
            <button type="submit" class="custom-btn">Valider</button>
          </div>

        </form>
      </div>

      <script> // Variables pour addForm_multipart.js
        const addForm_multipart = document.getElementById("updateAbsenceForm");
        const addForm_multipartController = "../../controller/Absences/editAbsenceController.php";
        const addForm_multipartSuccessHeader = "list-absences.php";
        const addForm_fileName = "justificatif";
      </script>
      <script src="../../assets/script/addForm_multipart.js"></script>
      
      <!-- Script showToast -->
      <script src="../../assets/script/toast.js"></script>
    </main>
    <footer>
      <?php
      include __DIR__ . '/../elements/footer.php';
      ?>
    </footer>
  </div>
</body>