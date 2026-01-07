<?php

include __DIR__ . '/../elements/header.php';

require_once __DIR__ . '/../../Controller/CentreFormationController.php';
require_once __DIR__ . '/../../Controller/EntrepriseController.php';

$allcentres = CentreFormationController::ListCentreFormation();



$allentreprises = EntrepriseController::ListEntreprise();



?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <title>Liste des Centres Formations | ErpFacil</title>
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../erp/assets/style/listeEtudiants.css" />
  <link rel="stylesheet" href="../../erp/assets/style/modals.css" />
  <link rel="stylesheet" href="../../erp/assets/style/pagination.css" />
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
</head>

<body>
  <div class="content-container">
    <header>
      <div class="header-container">
        <i class="bx bx-buildings header-icon"></i>
        <div class="title">
          <h1>Liste des centres de formation</h1>
        </div>
      </div>
      <div class="back-icon-container">
          <a href="home">
            <i class="fa-solid fa-arrow-left-long fa-2xl" style="color: #263b4a;"></i>
          </a>
        </div>
    </header>
    <main>
      <div class="d-flex justify-content-between mt-3 mb-3">
        <input type="text" id="searchInput" class="form-control w-25" placeholder="Rechercher...">
        <div>
          <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addStudentModal">Ajouter un centre
            formation</button>
        </div>
      </div>
      <?php if (!empty($allcentres)): ?>
        <div class="table-container">
          <table class="table table-striped mt-3" id="centreTable">
            <thead id="tableHead">
              <tr>
                <th>Nom du centre </th>
                <th>Nom de l'entreprise</th>
                <th>Nombre de formations </th>
                <th colspan="3">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($allcentres as $allcentre): ?>
                <tr>
                  <td><?php echo $allcentre->nomCentre; ?></td>
                  <td><?php echo $allcentre->nomEntreprise; ?></td>
                  <td><?php echo $allcentre->nbFormations;  ?></td>
                  <td>
                    
                        <!-- <a href="centre-details.php?centreId=<?= $allcentre->id ?>" style="text-decoration: none; color: black;">
                          <i class="fa-solid fa-arrow-right"></i>
                        </a> -->
                      </div>
                    </form>
                  </td>
                  <td>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                      data-id="<?php echo $allcentre->id; ?>"
                      data-nomCentre="<?php echo isset($allcentre->nomCentre) ? htmlspecialchars($allcentre->nomCentre) : ''; ?>"
                      data-adresseCentre="<?php echo isset($allcentre->adresseCentre) ? htmlspecialchars($allcentre->adresseCentre) : ''; ?>"
                      data-codePostalCentre="<?php echo isset($allcentre->codePostalCentre) ? htmlspecialchars($allcentre->codePostalCentre) : ''; ?>"
                      data-villeCentre="<?php echo isset($allcentre->villeCentre) ? htmlspecialchars($allcentre->villeCentre) : ''; ?>"
                      data-telephoneCentre="<?php echo isset($allcentre->telephoneCentre) ? htmlspecialchars($allcentre->telephoneCentre) : ''; ?>"
                      data-idEntreprise="<?php echo isset($allcentre->entrepriseId) ? htmlspecialchars($allcentre->entrepriseId) : ''; ?>">
                      <i class="fas fa-edit"></i>
                    </button>
                  </td>
                  <td>
                    <button class="btn btn-danger btn-sm"  onclick="confirmDeleteElement(<?php echo $allcentre->id; ?>)">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </td>
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
              <!-- Page numbers will be inserted here dynamically -->
              <li class="page-item"><a class="page-link" href="#" id="nextPage">Suivant</a></li>
            </ul>
          </nav>
        </div>

      <?php else: ?>
        <p><?php echo "Aucun centre de formation";  ?></p>
      <?php endif; ?>

      <!-- Inclusion des modales -->
      <?php require_once 'modalAddCentre.php'; ?>
      <?php require_once 'modalEditCentre.php'; ?>
      <?php require_once 'modalDeleteCentre.php'; ?>
      
     
      

      <script src="../../erp/assets/script/toast.js"></script>

      <!-- Pagination + Barre de recherche -->
      <script> // Variables pour paginationSearch.js
        const targetTable = document.getElementById('centreTable');
      </script>
      <script src="../../erp/assets/script/paginationSearch.js"></script>

      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    </main>
    <footer>
      <?php include __DIR__ . '/../elements/footer.php'; ?>
    </footer>
  </div>
</body>

</html>