<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . "/../../controller/User/validTokenController.php";
require_once __DIR__ . "/../../controller/CentreFormation/centreListUserController.php";
require_once __DIR__ . '/../../controller/Sessions/sessionDetailsController.php';
require_once __DIR__ . '/../../controller/Sessions/ListSessionsController.php';
require_once __DIR__ . '/../../controller/Financeur/ListFinanceurController.php';
require_once __DIR__ . '/../../controller/Entreprise/ListEntrepriseAccueilController.php';
require_once __DIR__ . '/../../controller/Formateur/listFormateurByCentre.php';
require_once __DIR__ . '/../../controller/Formation/listFormationByCentre.php';

include __DIR__ . '/../elements/header.php';

$role = $_SESSION['user']['role'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Détails de la session | ErpFacil</title>
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/style/pagination.css" />
  <link rel="stylesheet" href="../../assets/style/listeEtudiants.css" />
  <link rel="stylesheet" href="../../assets/style/twoContainerList.css" />
</head>

<body>
  <div class="content-container">
    <header>
      <div class="header-container">
        <i class="fa-solid fa-users header-icon"></i>
        <div class="title">
          <h1>Liste des utilisateurs du centre</h1>
        </div>
        <h2 id="centreName"><?php echo $_GET["centreName"] ?></h2>

        <div class="back-icon-container">
          <a href="../centre-formation/centre-details?centreId=<?= $_GET["centreId"] ?>">
            <i class="fa-solid fa-arrow-left-long fa-2xl" style="color: #263b4a;"></i>
          </a>
        </div>
      </div>
    </header>
    <main>
      <div class="d-flex justify-content-between mt-3 mb-3">
        <div>
          <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addStudentModal">Ajouter un étudiant</button>
          <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addFormateurModal">Ajouter un formateur</button>
          <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addSessionModal">Ajouter une
            session</button>
        </div>
      </div>
      <div class="flex-container">
        <div class="main-content">
          <nav>
            <h2 class="underline">Sessions</h2>
            <div class="main-buttons-container">
              <div class="main-buttons">

              </div>
            </div>
          </nav>
          <div class="sub-container">
            <h2 class="underline">Participants de la session</h2>
            <div class="search-container">
              <input type="text" class="search-input" placeholder="Rechercher un participant...">
            </div>
            <div id="sub-list">
              <?php if (isset($noMainMessage)): ?>
                <p><?= $noMainMessage ?></p>
              <?php endif; ?>
            </div>
            <div class="pagination">
              <button id="prevPage" disabled>Précédent</button>
              <button id="nextPage">Suivant</button>
            </div>
          </div>
        </div>
      </div>
      <?php include __DIR__ . '/../../view/etudiant/add-etudiant.php'; ?>
      <?php include __DIR__ . '/../../view/formateur/add-formateur.php'; ?>
      <?php include __DIR__ . '/../../view/session/add-session.php'; ?>
      <?php include __DIR__ . '/../../view/formation/add-formation.php'; ?>
      
      <script>
         var centreId =  "<?php echo $_SESSION['user']['role'] == 1 ? $_GET['centreId'] : $_SESSION['user']['centre']; ?>";
         var centreName =  "<?php echo isset($_GET['centreName']) ? $_GET['centreName'] : ''; ?>";
      </script>
      <!-- Script showToast -->
      <script src="../../assets/script/toast.js"></script>

      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

      <script>
        const noSubItemMessage = "Aucun utilisateur enregistré dans cette session.";
        var mainSubs = <?= json_encode($mainSubs) ?>;
        var mains = <?= json_encode($mains) ?>;
        function generateSubListContent(item) {
          return `
              <a href="../user/details-user?userId=${item.id}&centreId=<?= $selectedCentre ?>&centreName=<?= $_GET['centreName'] ?>&ref=list-centre-users" class="sub-item-link">
                <div class="sub-info-left">
                    <span class="sub-role">${item.role}</span>
                    <span class="sub-name">${item.lastname} ${item.firstname}</span>
                </div>
                <span class="sub-email">${item.email}</span>
              </a>
            `;
        }
      </script>
      <script src="../../assets/script/twoContainerList_sub.js"></script>

    </main>

    <footer>
      <?php include __DIR__ . '/../elements/footer.php'; ?>
    </footer>
  </div>
</body>

</html>