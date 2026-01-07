<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . "/../../controller/User/validTokenController.php";
require_once __DIR__ . '/../../controller/CentreFormation/centreDetails.php';
include __DIR__ . '/../elements/header.php';

$role = $_SESSION['user']['role'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Détails du centre LGX CAMPUS | ErpFacil</title>
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" href="../../assets/style/centreDetails.css">
  <link rel="stylesheet" href="../../assets/style/contentContainer.css">
  <link rel="stylesheet" href="../../assets/style/textStyle.css">
  <link rel="stylesheet" href="../../assets/style/updateStyle.css">
</head>

<body>
  <div class="content-container">
    <header>
      <div class="header-container">
        <i class="fa-solid fa-question header-icon"></i>
        <div class="title">
          <h1>Modifier les informations du centre</h1>
        </div>
      </div>
    </header>
    <main>
      <div class="flex">
        <h2>Informations du centre</h2>
        <button id="toStudentList">Liste des étudiants</button>
      </div>
      <div class="update-container">
        <form id="updateCentreForm">
          <div class="inputs-container">
            <div class="form-input-container">
              <label for="centreNom" class="text-bold">Nom du centre</label>
              <input type="text" name="centreNom" id="centreNom" class="input-text"
                value="<?= $centreDetails->data->dataCentre[0]->nomCentre; ?>">
            </div>
            <div class="form-input-container">
              <label for="centreVille" class="text-bold">Ville</label>
              <input type="text" name="centreVille" id="centreVille" class="input-text"
                value="<?= $centreDetails->data->dataCentre[0]->villeCentre ?? '' ?>">
            </div>
            <div class="form-input-container">
              <label for="centreCodePostal" class="text-bold">Code postal</label>
              <input type="text" name="centreCodePostal" id="centreCodePostal" class="input-text"
                value="<?= $centreDetails->data->dataCentre[0]->codePostalCentre ?? '' ?>">
            </div>
            <div class="form-input-container">
              <label for="centreAdresse" class="text-bold">Adresse</label>
              <input type="text" name="centreAdresse" id="centreAdresse" class="input-text"
                value="<?= $centreDetails->data->dataCentre[0]->adresseCentre ?? '' ?>">
            </div>
            <div class="form-input-container">
              <label for="centreTelephone" class="text-bold">Téléphone</label>
              <input type="text" name="centreTelephone" id="centreTelephone" class="input-text"
                value="<?= $centreDetails->data->dataCentre[0]->telephoneCentre ?? '' ?>">
            </div>
            <div class="bottom-btn">
              <input type="hidden" name="centreId" value="<?= $centreDetails->data->dataCentre[0]->centreId ?>" />
              <input type="hidden" name="centreEntrepriseId"
                value="<?= $centreDetails->data->dataCentre[0]->id_entreprises ?>" />
              <a href="list-centre-formation.php" class="custom-button">Retour à la liste des centres</a>
              <button type="submit" class="custom-btn">Valider</button>
            </div>
          </div>
      </div>
      </section>
      </form>
  </div>

  <script> // Variables pour updateForm.js
    const updateForm = document.getElementById("updateCentreForm");
    const updateFormController = "../../controller/CentreFormation/editCentreController.php";
    const updateFormSuccessHeader = "list-centre-formation.php";
  </script>
  <script src="../../assets/script/updateForm.js"></script>
  <script src="../../assets/script/toast.js"></script>
  </main>
  <footer>
    <?php include __DIR__ . '/../elements/footer.php'; ?>
  </footer>
  </div>

</body>

</html>