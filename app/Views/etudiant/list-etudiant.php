<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include __DIR__ . '/../elements/header.php';
require_once __DIR__ . '/../../controller/Etudiant/ListEtudiantController.php';
require_once __DIR__ . '/../../controller/CentreFormation/ListCentreFormationController.php';
require_once __DIR__ . '/../../controller/Financeur/ListFinanceurController.php';
require_once __DIR__ . '/../../controller/Entreprise/ListEntrepriseAccueilController.php';
require_once __DIR__ . '/../../controller/Sessions/ListSessionsController.php';
require_once __DIR__ . '/../../controller/User/validTokenController.php';
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <title>Liste des Étudiants | ErpFacil</title>
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/style/listeEtudiants.css" />
  <link rel="stylesheet" href="../../assets/style/contentContainer.css" />
  <link rel="stylesheet" href="../../assets/style/profilstyle.css" />
  <link rel="stylesheet" href="../../assets/style/modals.css" />
  <link rel="stylesheet" href="../../assets/style/pagination.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
</head>

<body>
  <div class="content-container">
    <header>
      <div class="header-container">
        <i class="fa-solid fa-users header-icon"></i>
        <div class="title">
          <h1>Liste des étudiants</h1>
        </div>
      </div>
    </header>
    <main>
      <div class="d-flex justify-content-between mt-3 mb-3">
        <input type="text" id="searchInput" class="form-control w-25" placeholder="Rechercher...">
        <div>
          <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addStudentModal">Ajouter un étudiant</button>
          <button class="btn btn-secondary">Envoi récapitulatif pointage</button>
        </div>
      </div>
      <?php if (!empty($alletudiants)) : ?>
        <div class="table-container">
          <table class="table table-striped mt-3" id="studentTable">
            <thead id="tableHead">
              <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Code postal</th>
                <th>Ville</th>
                <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 1) : ?>
                  <th>Centre de formation</th>
                <?php endif; ?>
                <th>Dernier pointage</th>
                <th>Actions</th>
              </tr>
            </thead>
         
            <tbody>
              <?php foreach ($alletudiants->valid as $alletudiant) : ?>
                <tr>
                  <td><?php echo htmlspecialchars($alletudiant->lastname ?? ''); ?></td>
                  <td><?php echo htmlspecialchars($alletudiant->firstname ?? ''); ?></td>
                  <td><?php echo htmlspecialchars($alletudiant->codePostal ?? ''); ?></td>
                  <td><?php echo htmlspecialchars($alletudiant->ville ?? ''); ?></td>
                  <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 1) : ?>
                    <td>
                      <?php 
                        $nomCentre = '';
                        foreach ($allcentres as $item) {
                          if ($item->id == $alletudiant->id_centres_de_formation) {
                            $nomCentre = $item->nomCentre;
                            break;
                          }
                        }
                        echo htmlspecialchars($nomCentre ?? '');
                      ?>
                    </td>
                  <?php endif; ?>
                  <td><?php echo htmlspecialchars($alletudiant->dernierPointage ?? ''); ?></td>
                  <td>
                      <div class="action-buttons">
                        <a href="../user/details-user?userId=<?= $alletudiant->id_users ?>&centreId=<?= $alletudiant->id_centres_de_formation ?>&ref=list-etudiants" style="text-decoration: none; color: black; margin-right:7px" title="Détails de l'utilisateur">
                          <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        <a class="btn btn-secondary btn-sm" title="Pointages de <?= $alletudiant->lastname ?> <?= $alletudiant->firstname ?>" href="../../view/pointage/list-pointage.php?selectedUser=<?= $alletudiant->id_users ?>" style="text-decoration: none; color: black; margin-right: 10px" alt="pointage">
                            <i style="color: white; "class="fa-solid fa-clock-rotate-left"></i>
                        </a>
                        <button class="btn btn-warning btn-sm" title="Modifier l'étudiant" data-bs-toggle="modal" data-bs-target="#editModal"
                                data-userid="<?php echo $alletudiant->id_users; ?>"
                                data-email="<?php echo $alletudiant->email; ?>"
                                data-firstname="<?php echo isset($alletudiant->firstname) ? htmlspecialchars($alletudiant->firstname) : ''; ?>"
                                data-lastname="<?php echo isset($alletudiant->lastname) ? htmlspecialchars($alletudiant->lastname) : ''; ?>"
                                data-adresse="<?php echo isset($alletudiant->adressePostale) ? htmlspecialchars($alletudiant->adressePostale) : ''; ?>"
                                data-codepostal="<?php echo isset($alletudiant->codePostal) ? htmlspecialchars($alletudiant->codePostal) : ''; ?>"
                                data-ville="<?php echo isset($alletudiant->ville) ? htmlspecialchars($alletudiant->ville) : ''; ?>"
                                data-naissance="<?php echo isset($alletudiant->date_naissance) ? htmlspecialchars($alletudiant->date_naissance) : ''; ?>"
                                data-entreprise="<?php echo isset($alletudiant->id_entreprises) ? htmlspecialchars($alletudiant->id_entreprises) : ''; ?>"
                                data-centre="<?php echo isset($alletudiant->id_centres_de_formation) ? htmlspecialchars($alletudiant->id_centres_de_formation) : ''; ?>"
                                data-financeur="<?php echo isset($alletudiant->id_conseillers_financeurs) ? htmlspecialchars($alletudiant->id_conseillers_financeurs) : ''; ?>"
                                data-session="<?php echo isset($alletudiant->id_session) ? htmlspecialchars($alletudiant->id_session) : ''; ?>">
                          <i class="fas fa-edit"></i>
                        </button>
                        <span class="space"></span>
                        <?php if (isset($_SESSION['user']['role']) && in_array($_SESSION['user']['role'],[1,3])) : ?>
                          <button class="btn btn-danger btn-sm" title="Supprimer l'étudiant" onclick="confirmDeleteEtudiant(<?php echo $alletudiant->id_users; ?>)">
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        <?php endif; ?>
                      </div>
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
      <?php else : ?>
        <p><?php echo $result->erreur ?? 'Aucun étudiant trouvé.'; ?></p>
      <?php endif; ?>

      <script>
         var centreId =  "<?php echo $_SESSION['user']['role'] == 1 ? $_GET['centreId'] : $_SESSION['user']['centre']; ?>";
         var centreName =  "<?php echo isset($_GET['centreName']) ? $_GET['centreName'] : ''; ?>";
      </script>

      <?php include 'add-etudiant.php'; ?>
      <?php include 'edit-etudiant.php'; ?>
      <?php include 'delete-etudiant.php'; ?>
      
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
        
        // Pagination and search scripts
        const searchInput = document.getElementById('searchInput');
        const studentTable = document.getElementById('studentTable');
        const noResultsMessage = document.getElementById('noResultsMessage');
        const tableHead = document.getElementById('tableHead');
        const paginationList = document.getElementById('paginationList');
        const rowsPerPage = 10;  // Nombre d'éléments par page
        const maxPagesVisible = 9; // Limite de pages visibles
        let currentPage = 1;

        function updatePagination(totalPages) {
          paginationList.innerHTML = '';
          const prevPageItem = document.createElement('li');
          prevPageItem.className = 'page-item';
          const prevPageLink = document.createElement('a');
          prevPageLink.className = 'page-link';
          prevPageLink.href = '#';
          prevPageLink.id = 'prevPage';
          prevPageLink.textContent = 'Précédent';
          prevPageItem.appendChild(prevPageLink);
          paginationList.appendChild(prevPageItem);

          const startPage = Math.max(1, currentPage - Math.floor(maxPagesVisible / 2));
          const endPage = Math.min(totalPages, startPage + maxPagesVisible - 1);

          for (let i = startPage; i <= endPage; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = 'page-item' + (i === currentPage ? ' active' : '');
            const pageLink = document.createElement('a');
            pageLink.className = 'page-link';
            pageLink.href = '#';
            pageLink.textContent = i;
            pageLink.addEventListener('click', function(e) {
              e.preventDefault();
              currentPage = i;
              displayPage(currentPage);
            });
            pageItem.appendChild(pageLink);
            paginationList.appendChild(pageItem);
          }

          const nextPageItem = document.createElement('li');
          nextPageItem.className = 'page-item';
          const nextPageLink = document.createElement('a');
          nextPageLink.className = 'page-link';
          nextPageLink.href = '#';
          nextPageLink.id = 'nextPage';
          nextPageLink.textContent = 'Suivant';
          nextPageItem.appendChild(nextPageLink);
          paginationList.appendChild(nextPageItem);

          prevPageItem.classList.toggle('disabled', currentPage === 1);
          nextPageItem.classList.toggle('disabled', currentPage === totalPages);

          // Event handlers for the Prev and Next buttons
          prevPageLink.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage > 1) {
              currentPage--;
              displayPage(currentPage);
            }
          });

          nextPageLink.addEventListener('click', function(e) {
            e.preventDefault();

            if (currentPage < totalPages) {
              currentPage++;
              displayPage(currentPage);

            }
          });
        }

        function displayPage(page) {
          const rows = studentTable.querySelectorAll('tbody tr');
          const totalRows = rows.length;
          const totalPages = Math.ceil(totalRows / rowsPerPage);
          const start = (page - 1) * rowsPerPage;
          const end = start + rowsPerPage;

          rows.forEach((row, index) => {
            row.style.display = (index >= start && index < end) ? '' : 'none';
          });

          document.getElementById('paginationNav').style.display = totalRows > rowsPerPage ? 'block' : 'none';
          currentPage = page;
          updatePagination(totalPages);
        }

        function searchAndPaginate() {
          const filter = searchInput.value.toLowerCase();
          const rows = studentTable.querySelectorAll('tbody tr');
          let visibleRows = [];

          rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            let found = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(filter));

            row.style.display = 'none';
            if (found) visibleRows.push(row);
          });

          visibleRows.forEach(row => row.style.display = 'none');
          for (let i = 0; i < visibleRows.length; i++) {
            visibleRows[i].style.display = (i < rowsPerPage) ? '' : 'none';
          }

          document.getElementById('paginationNav').style.display = visibleRows.length > rowsPerPage ? 'block' : 'none';
          document.getElementById('noResultsMessage').style.display = visibleRows.length ? 'none' : 'block';
          tableHead.style.display = visibleRows.length ? 'table-header-group' : 'none';

          currentPage = 1;
          updatePagination(Math.ceil(visibleRows.length / rowsPerPage));
        }

        // Initial call to display the first page
        displayPage(currentPage);

        searchInput.addEventListener('input', searchAndPaginate);

        // Set table height
        document.addEventListener("DOMContentLoaded", function() {
          const rowsPerPage = 10; // Nombre d'éléments par page
          const tableContainer = document.querySelector('.table-container');
          const rowHeight = 50; // Approximate row height, adjust based on your styling
          const tableHeadHeight = document.querySelector('thead').offsetHeight;
          const minHeight = (rowsPerPage * rowHeight) + tableHeadHeight;

          function setTableHeight() {
            const totalRows = studentTable.querySelectorAll('tbody tr').length;
            const visibleRows = Math.min(rowsPerPage, totalRows);
            const newHeight = (visibleRows * rowHeight) + tableHeadHeight;
            tableContainer.style.height = `${Math.max(newHeight, minHeight)}px`;
          }

          setTableHeight();
          searchInput.addEventListener('input', setTableHeight);
          document.getElementById('paginationList').addEventListener('click', function(e) {
            if (e.target.tagName === 'A') {
              setTimeout(setTableHeight, 100); // Adjust this timeout as needed
            }
          });
        });
      </script>

      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    </main>
    <footer>
      <?php include __DIR__ . '/../elements/footer.php'; ?>
    </footer>
  </div>
</body>
</html>
