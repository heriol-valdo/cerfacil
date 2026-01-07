<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . "/../../controller/User/validTokenController.php";
require_once __DIR__ . '/../../controller/Sessions/sessionDetailsController.php';
include __DIR__ . '/../elements/header.php';

$role = $_SESSION['user']['role'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Détails de la session | ErpFacil</title>
  <link rel="stylesheet" href="../../assets/style/centreDetails.css">
</head>

<body>
  <div class="content-container">
    <header>
      <div class="header-container">
        <i class="fa-solid fa-question header-icon"></i>
        <div class="title"><h1>Détails de la session</h1></div>
      </div>
    </header>
    <main>
      <div class="centre-details-section">
        <h3>Informations de la session</h3>
        <p><strong>Formation</strong> <?= $sessionInfo->formations_nom; ?></p>
        <p><strong>Nom de la session</strong> <?= $sessionInfo->nomSession; ?></p>
        <p><strong>Début de la session</strong> <?= $sessionInfo->dateDebut; ?></p>
        <p><strong>Fin de la session</strong> <?= $sessionInfo->dateFin; ?></p>
        <p><strong>Nombre de place</strong> <?= $sessionInfo->nbPlace; ?></p>
      </div>
      <div class="centre-details-section">
        <h3>Formateurs</h3>
        <ul>
            <?php foreach ($formateursParticipantInfo as $formateur) :?>
                <p>- <?= strtoupper($formateur->lastname); ?> <?= $formateur->firstname; ?></p>
            <?php endforeach;?>
        </ul>
      </div>
      <div class="centre-details-section">
        <h3>Étudiants</h3>
        <ul>
            <?php foreach ($etudiantsParticipantInfo as $etudiant) :?>
                <p>- <?= strtoupper($etudiant->lastname); ?> <?= $etudiant->firstname; ?></p>
            <?php endforeach;?>
        </ul>
      </div>
    </main>
    <footer>
      <?php include __DIR__ . '/../elements/footer.php'; ?>
    </footer>
  </div>

</body>
</html>