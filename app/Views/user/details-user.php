<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . "/../../controller/User/validTokenController.php";
require_once __DIR__ . '/../../controller/User/detailsUserController.php';
include __DIR__ . '/../elements/header.php';

$role = $_SESSION['user']['role'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Détails de l'utilisateur | ErpFacil</title>
  <link rel="stylesheet" href="../../assets/style/centreDetails.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
  <div class="content-container">
    <header>
      <div class="header-container">
        <i class="fa-solid fa-question header-icon"></i>
        <div class="title">
          <h1>Détails de l'utilisateur</h1>
        </div>
        <div class="back-icon-container">
          <a href="<?= $backlink ?>">
            <i class="fa-solid fa-arrow-left-long fa-2xl" style="color: #263b4a;"></i>
          </a>
        </div>
      </div>
    </header>
    <main>
      <div class="centre-details-section">
        <h3>Identité</h3>
        <p><strong>Nom et prénom </strong> <?= strtoupper($roleInfos->lastname); ?> <?= $roleInfos->firstname; ?></p>
        <p><strong>Email </strong> <?= $userInfos->email; ?></p>
        <p><strong>Rôle </strong> <?= $userInfos->role; ?></p>
        <?php if (in_array($_SESSION['user']['role'], [1, 2, 3, 4, 6])): ?>
          <?php if (isset($roleInfos->telephone)): ?>
            <p><strong>Téléphone </strong>
              <?= !empty($userInfos->telephone) ? $roleInfos->lieu_travail : "Non renseigné"; ?></p>
          <?php endif; ?>
          <?php if (isset($roleInfos->dateNaissance)): ?>
            <?php if (!empty($roleInfos->dateNaissance)) {
              $dateNaissance = new DateTime($roleInfos->dateNaissance);
              $dateNaissance = $dateNaissance->format('d-m-Y');
            } else {
              $dateNaissance = "Non renseigné";
            } ?>
            <p><strong>Date de naissance </strong> <?= $dateNaissance; ?></p>
          <?php endif; ?>
        </div>
        <div class="centre-details-section">
          <h3>Informations complémentaires</h3>
          <?php if ($userInfos->id_role == 2): ?>
            <p><strong>Nom de l'entreprise </strong> <?= $roleInfos->nomEntreprise; ?></p>
            <p><strong>Lieu de travail </strong>
              <?= !empty($roleInfos->lieu_travail) ? $roleInfos->lieu_travail : "Non renseigné"; ?></p>
          <?php elseif ($userInfos->id_role == 3): ?>
            <p><strong>Nom du centre </strong> <?= $roleInfos->nomCentre; ?></p>
          <?php elseif ($userInfos->id_role == 4): ?>
            <?php if (!empty($roleInfos->siret)): ?>
              <p><strong>Siret du formateur </strong> <?= $roleInfos->siret; ?></p>
            <?php endif; ?>
            <p><strong>Adresse </strong>
              <?= !empty($roleInfos->adressePostale) ? $roleInfos->adressePostale : "Non renseigné"; ?></p>
            <p><strong>Code postal </strong>
              <?= !empty($roleInfos->codePostal) ? $roleInfos->codePostal : "Non renseigné"; ?></p>
            <p><strong>Ville </strong> <?= !empty($roleInfos->ville) ? $roleInfos->ville : "Non renseigné"; ?></p>
            <p><strong>Nom du centre </strong> <?= $roleInfos->nomCentre; ?></p>
          <?php elseif ($userInfos->id_role == 5): ?>
            <p><strong>Adresse </strong>
              <?= !empty($roleInfos->adressePostale) ? $roleInfos->adressePostale : "Non renseigné"; ?></p>
            <p><strong>Code postal </strong>
              <?= !empty($roleInfos->codePostal) ? $roleInfos->codePostal : "Non renseigné"; ?></p>
            <p><strong>Ville </strong> <?= !empty($roleInfos->ville) ? $roleInfos->ville : "Non renseigné"; ?></p>
            <p><strong>Nom du centre </strong> <?= $roleInfos->nomCentre; ?></p>
            <?php if (!empty($roleInfos->nomEntreprise)): ?>
              <p><strong>Nom de l'entreprise </strong> <?= $roleInfos->nomEntreprise; ?></p>
            <?php endif; ?>
            <?php if (!empty($roleInfos->financeur_lastname)): ?>
              <p><strong>Nom du conseiller financeur </strong> <?= $roleInfos->financeur_lastname; ?></p>
            <?php endif; ?>
          <?php elseif ($userInfos->id_role == 6): ?>
            <p><strong>Nom de l'entreprise </strong> <?= $roleInfos->nomEntreprise; ?></p>
            <p><strong>Type de financeur </strong> <?= !empty($roleInfos->type_financeur) ? $roleInfos->type_financeur : "Non renseigné"; ?></p>
          <?php endif; ?>
        </div>
        <?php if ($userInfos->id_role == 5): ?>
          <div class="centre-details-section">
            <h3>Session de l'étudiant</h3>
            <p><strong>Nom de la formation </strong>
              <?= !empty($roleInfos->formations_nom) ? $roleInfos->formations_nom : "Non renseigné"; ?></p>
            <p><strong>Nom de la session </strong>
              <?= !empty($roleInfos->nomSession) ? $roleInfos->nomSession : "Non renseigné"; ?></p>
            <?php if (!empty($roleInfos->nomSession)): ?>
              <?php
              $dateDebut = new DateTime($roleInfos->dateDebut);
              $dateDebut = $dateDebut->format('d-m-Y');

              $dateFin = new DateTime($roleInfos->dateFin);
              $dateFin = $dateFin->format('d-m-Y');
              ?>
              <div style="display:flex; flex-direction: row;">
                <p><strong>Date de début </strong> <?= $dateDebut; ?></p>
                <p><strong>Date de fin </strong> <?= $dateFin; ?></p>
              </div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </main>
    <footer>
      <?php include __DIR__ . '/../elements/footer.php'; ?>
    </footer>
  </div>

</body>

</html>