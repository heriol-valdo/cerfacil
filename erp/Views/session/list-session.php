<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include __DIR__ . '/../elements/header.php';
require_once __DIR__ . '/../../controller/Sessions/ListSessionsController.php';
require_once __DIR__ . '/../../controller/Formation/ListFormationController.php';
require_once __DIR__ . '/../../controller/Formateur/listFormateurByCentre.php';
require_once __DIR__ . '/../../controller/Formation/listFormationByCentre.php';
require_once __DIR__ . '/../../controller/User/validTokenController.php';
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <title>Liste des Sessions | ErpFacil</title>
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/style/listeEtudiants.css" />
  <link rel="stylesheet" href="../../assets/style/pagination.css" />
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
</head>

<body>
  <div class="content-container">
    <header>
      <div class="header-container">
        <i class="fa-solid fa-book header-icon"></i>
        <div class="title">
          <h1>Liste des sessions</h1>
        </div>
      </div>
    </header>
    <main>
      <div class="d-flex justify-content-between mt-3 mb-3">
        <input type="text" id="searchInput" class="form-control w-25" placeholder="Rechercher...">
        <div>
          <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addSessionModal">Ajouter une session</button>
        </div>
      </div>
      <?php if (!empty($allsessions)): ?>

        <div class="table-container">
          <table class="table table-striped mt-3" id="sessionTable">
            <thead>
              <tr>
                <th>Date de debut</th>
                <th>Date de fin</th>
                <th>Nom</th>
                <th>Nombre de places</th>
                <th>Formations</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($allsessions as $allsession): ?>
                <tr>
                  <td><?php echo $allsession->dateDebut !== null ? htmlspecialchars($allsession->dateDebut) : ''; ?>
                  </td>
                  <td><?php echo $allsession->dateFin !== null ? htmlspecialchars($allsession->dateFin) : ''; ?>
                  </td>
                  <td><?php echo $allsession->nomSession !== null ? htmlspecialchars($allsession->nomSession) : ''; ?>
                  </td>
                  <td><?php echo $allsession->nbPlace !== null ? htmlspecialchars($allsession->nbPlace) : ''; ?>
                  </td>
                  <td>
                    <?php echo $allsession->nom_formation !== null ? htmlspecialchars($allsession->nom_formation) : ''; ?>
                  </td>
                  <td>
                    <div class="action-buttons">
                      <a href="../event/planning.php?ref=list-sessions&centreId=<?= $allsession->id_centres_de_formation ?>&selectedSession=<?= $allsession->id ?>"
                        class="btn btn-secondary btn-sm" style="text-decoration: none; color: white; margin-right: 13px;">
                        <i class="fa-regular fa-calendar"></i>
                      </a>
                      <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                        data-id="<?php echo $allsession->id; ?>"
                        data-dateDebut="<?php echo isset($allsession->dateDebut) ? htmlspecialchars($allsession->dateDebut) : ''; ?>"
                        data-dateFin="<?php echo isset($allsession->dateFin) ? htmlspecialchars($allsession->dateFin) : ''; ?>"
                        data-nbPlace="<?php echo isset($allsession->nbPlace) ? htmlspecialchars($allsession->nbPlace) : ''; ?>">
                        <i class="fas fa-edit"></i>
                      </button>

                      <span class="space"></span>
                      <button class="btn btn-danger btn-sm" onclick="confirmDeleteSession(<?php echo $allsession->id ?>)">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </div>
                  </td>
                </tr>

              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <!-- Pagination Nav -->
        <div class="pagination-container">
          <nav aria-label="Pagination" id="paginationNav" style="display: none;">
            <ul class="pagination justify-content-center" id="paginationList">
              <li class="page-item"><a class="page-link" href="#" id="prevPage">Précédent</a></li>
              <!-- Page numbers will be inserted here dynamically -->
              <li class="page-item"><a class="page-link" href="#" id="nextPage">Suivant</a></li>
            </ul>
          </nav>
        </div>
      <?php else: ?>
        <p>Aucune session enregistrée pour le moment</p>
      <?php endif; ?>

      <?php include 'add-session.php'; ?>
      <?php include 'edit-session.php'; ?>
      <?php include 'delete-session.php'; ?>
      <?php include __DIR__ . '/../../view/formation/add-formation.php'; ?>
      <?php include __DIR__ . '/../../view/formateur/add-formateur.php'; ?>

      <script>
         var centreId =  "<?php echo $_SESSION['user']['role'] == 1 ? $_GET['centreId'] : $_SESSION['user']['centre']; ?>";
         var centreName =  "<?php echo isset($_GET['centreName']) ? $_GET['centreName'] : ''; ?>";
      </script>
      <script>

        function showToast(value, text, subtext) {
          const toastContainer = document.createElement("div");
          toastContainer.style.display = "flex";
          toastContainer.style.alignItems = "center";
          toastContainer.style.justifyContent = "space-between";


          const icon = document.createElement("i");
          icon.className = value ? "fas fa-thin fa-check" : "fas fa-exclamation-circle";
          icon.style.marginRight = "10px";
          const closeButton = document.createElement("button");
          closeButton.innerText = "✖";
          closeButton.style.marginLeft = "10px";
          closeButton.style.cursor = "pointer";
          closeButton.style.color = "white";
          closeButton.style.backgroundColor = "transparent";
          closeButton.style.border = "none";
          closeButton.addEventListener("click", () => {
            toastInstance.hideToast();
          });


          const toastText = document.createElement("span");
          toastText.innerHTML = value ? `<strong>${text}</strong>, ${subtext} ` : "<strong>Erreur</strong>: " + text + "," + subtext;


          const progressBar = document.createElement("div");
          progressBar.className = "progress-bar";


          toastContainer.appendChild(icon);
          toastContainer.appendChild(toastText);
          toastContainer.appendChild(progressBar);
          toastContainer.appendChild(closeButton);

          const toastInstance = Toastify({
            duration: 3000,
            backgroundColor: value ? "green" : "red",
            gravity: "top",
            stopOnFocus: true,
            className: "toast-with-progress",
            escapeMarkup: false,
            node: toastContainer,

          });

          toastInstance.showToast();
        }


      </script>
      <!-- Pagination + Barre de recherche -->
      <script> // Variables pour paginationSearch.js
        const targetTable = document.getElementById('sessionTable');
      </script>
      <script src="../../assets/script/paginationSearch.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    </main>
    <footer>
      <?php
      include __DIR__ . '/../elements/footer.php';
      ?>
    </footer>
  </div>

</body>