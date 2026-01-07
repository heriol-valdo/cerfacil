<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include __DIR__ . '/../elements/header.php';
require_once __DIR__ . '/../../controller/Absences/ListAbsencesController.php';
require_once __DIR__ . '/../../controller/Formation/ListFormationController.php';
require_once __DIR__ . '/../../controller/CentreFormation/ListCentreFormationController.php';
require_once __DIR__ . '/../../controller/Absences/allSessionsController.php';
require_once __DIR__ . '/../../controller/Absences/allEtudiantsController.php';
require_once __DIR__ . '/../../controller/User/validTokenController.php';
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <title>Liste des absences | ErpFacil</title>
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/style/listeEtudiants.css" />
  <link rel="stylesheet" href="../../assets/style/contentContainer.css" />
  <link rel="stylesheet" href="../../assets/style/modals.css" />
  <link rel="stylesheet" href="../../assets/style/pagination.css" />
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
</head>

<body>
  <div class="content-container">
    <header>
      <div class="header-container">
        <i class="fa-solid fa-users header-icon"></i>
        <div class="title">
          <h1>Liste des absences</h1>
        </div>
        <h2 id="centreName"><?php echo isset($_GET["centreName"]) ? $_GET["centreName"] : ''; ?></h2>
        <div class="back-icon-container">
          <a href="<?= !empty($selectedCentre) && $selectedCentre != "all" ? "../centre-formation/centre-details?centreId=$selectedCentre" : "../user/home.php" ;?>">
            <i class="fa-solid fa-arrow-left-long fa-2xl" style="color: #263b4a;"></i>
          </a>
        </div>
      </div>
    </header>
    <main>
      <div class="d-flex justify-content-between mt-3 mb-3">
        <input type="text" id="searchInput" class="form-control w-25" placeholder="Rechercher...">
        <?php if (in_array($_SESSION['user']['role'], [1, 3, 4])): ?>
          <div>
            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addAbsenceModal">
              Ajouter une absence
            </button>
          </div>
        <?php endif; ?>
      </div>
      <?php if (!empty($absences)): ?>
        <div class="table-container">
          <table class="table table-striped mt-3" id="adminTable">
            <thead id="tableHead">
              <tr>
                <th>Justificatif</th>
                <th>Date de début</th>
                <th>Date de fin</th>
                <?php if ($_SESSION['user']['role'] != 5): ?>
                  <th>Nom</th>
                  <th>Prénom</th>
                <?php endif; ?>
                <?php if (in_array($_SESSION['user']['role'], [1, 2, 6])): ?>
                  <th>Centre de formation</th>
                <?php endif; ?>
                <?php if (in_array($_SESSION['user']['role'], [1, 2, 3, 4, 6])): ?>
                  <th>Formation</th>
                <?php endif; ?>
                <th colspan="3">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($absences as $absence): ?>
                <?php // Formatage à la date FR
                  $dateDebut = new DateTime($absence->dateDebut);
                  $dateFin = new DateTime($absence->dateFin);
                                
                  $dateDebut = $dateDebut->format('d-m-Y');
                  $dateFin = $dateFin->format('d-m-Y'); 
                ?>
                <tr>
                  <td style="text-align: center;">
                    <?php echo $absence->justificatif !== null ? '<a target="_blank" href="https://apierp.lgx-creation.fr/' . htmlspecialchars($absence->justificatif) . '"><i class="fa-regular fa-file" style="color:black"></i></a>' : ''; ?>
                  </td>
                  <td><?php echo $dateDebut !== null ? htmlspecialchars($dateDebut) : ''; ?></td>
                  <td><?php echo $dateFin !== null ? htmlspecialchars($dateFin) : ''; ?></td>
                  <?php if ($_SESSION['user']['role'] != 5): ?>
                    <td><?php echo $absence->lastname !== null ? htmlspecialchars($absence->lastname) : ''; ?></td>
                    <td><?php echo $absence->firstname !== null ? htmlspecialchars($absence->firstname) : ''; ?></td>
                  <?php endif; ?>
                  <?php if (in_array($_SESSION['user']['role'], [1, 2, 6])): ?>
                    <td><?php echo $absence->nomCentre !== null ? htmlspecialchars($absence->nomCentre) : ''; ?></td>
                  <?php endif; ?>
                  <?php if (in_array($_SESSION['user']['role'], [1, 2, 3, 4, 6])): ?>
                    <td><?php echo $absence->formations_nom !== null ? htmlspecialchars($absence->formations_nom) : ''; ?>
                    </td>
                  <?php endif; ?>
                  <td>
                    <form action="details-absence.php" method="POST">
                      <input type="hidden" name="absenceId" value="<?= $absence->id ?>" />
                      <div>
                        <button type="submit" name="voirAbsence" class="submit-button">
                          <i class="fa-solid fa-arrow-right"></i>
                        </button>
                      </div>
                    </form>
                  </td>
                  <?php if($_SESSION['user']['role'] !=5) : ?>
                  <td>
                    <form action="edit-absence.php" method="POST">
                      <input type="hidden" name="absenceId" value="<?= $absence->id ?>" />
                      <div>
                        <button type="submit" name="editAbsence" class="btn btn-warning btn-sm">
                          <i class="fas fa-edit"></i>
                        </button>
                      </div>
                    </form>
                  </td>
                  <td>
                    <button class="btn btn-danger btn-sm" onclick="confirmDeleteElement(<?php echo $absence->id; ?>)">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </td>
                  <?php endif; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <p id="noResultsMessage" style="display:none;">Aucun résultat trouvé</p>
        </div>

        <!-- Pagination Nav -->
        <div class="pagination-container">
          <nav aria-label="Pagination" id="paginationNav" style="display: none;">
            <ul class="pagination justify-content-center" id="paginationList">
              <li class="page-item"><a class="page-link" href="#" id="prevPage">Précédent</a></li>
              <li class="page-item"><a class="page-link" href="#" id="nextPage">Suivant</a></li>
            </ul>
          </nav>
        </div>

      <?php else: ?>
        <p><?php echo $erreur; ?></p>
      <?php endif; ?>

      <?php include 'modalAddAbsence.php'; ?>

      <script> // modalDelete.php
        // Variables pour modalDelete.php
        const modalDeleteController = "../../controller/Absences/deleteAbsenceController.php";
        const modalDeleteMessage = "Voulez-vous supprimer cette absence ?";
        const modalDeleteSuccessHeader = "../absences/list-absences.php";
      </script>
      <?php include __DIR__.'/../../assets/php/modalDelete.php'; ?>


      <!-- Script showToast -->
      <script src="../../assets/script/toast.js"></script>

      <!-- Pagination + Barre de recherche -->
      <script> // Variables pour paginationSearch.js
        const targetTable = document.getElementById('adminTable');
      </script>
      <script src="../../assets/script/paginationSearch.js"></script>

      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    </main>

    <footer>
      <?php include __DIR__ . '/../elements/footer.php'; ?>
    </footer>
  </div>
</body>

</html>