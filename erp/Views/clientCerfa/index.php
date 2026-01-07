<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include __DIR__ . '/../elements/header.php';
require_once __DIR__ . '/../../Controller/ClientCerfaController.php';

$allclients = ClientCerfaController::ListClientCerfa();

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <title>Liste des  clients CerFacil | ErpFacil</title>
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../erp/assets/style/listeEtudiants.css" />
  <link rel="stylesheet" href="../../erp/assets/style/contentContainer.css" />
  <link rel="stylesheet" href="../../erp/assets/style/profilstyle.css" />
  <link rel="stylesheet" href="../../erp/assets/style/modals.css" />
  <link rel="stylesheet" href="../../erp/assets/style/pagination.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
</head>

<body>
  <div class="content-container">
    <header>
      <div class="header-container">
        <i class="fa-solid fa-users header-icon"></i>
        <div class="title">
          <h1>Liste des clients cerfa</h1>
        </div>
      </div>
    </header>
    <main>
      <div class="d-flex justify-content-between mt-3 mb-3">
        <input type="text" id="searchInput" class="form-control w-25" placeholder="Rechercher...">
        <div>
          <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addClientCerfaModal">Ajouter un client cerfa</button>
        </div>
      </div>
      <?php if (!empty($allclients)) : ?>
        <div class="table-container">
          <table class="table table-striped mt-3" id="studentTable">
            <thead id="tableHead">
              <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Code postal</th>
                <th>Ville</th>
                <th>Telephone</th>
                <th>Actions</th>
              </tr>
            </thead>
         
            <tbody>
              <?php foreach ($allclients->valid as $allclient) : ?>
                <tr>
                  <td><?php echo htmlspecialchars($allclient->lastname ?? ''); ?></td>
                  <td><?php echo htmlspecialchars($allclient->firstname ?? ''); ?></td>
                  <td><?php echo htmlspecialchars($allclient->codePostal ?? ''); ?></td>
                  <td><?php echo htmlspecialchars($allclient->ville ?? ''); ?></td>
                  <td><?php echo htmlspecialchars($allclient->telephone ?? ''); ?></td>
                  <td>
                      <div class="action-buttons">
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                data-userid="<?php echo $allclient->id_users; ?>"
                                data-email="<?php echo $allclient->email; ?>"
                                data-firstname="<?php echo isset($allclient->firstname) ? htmlspecialchars($allclient->firstname) : ''; ?>"
                                data-lastname="<?php echo isset($allclient->lastname) ? htmlspecialchars($allclient->lastname) : ''; ?>"
                                data-adresse="<?php echo isset($allclient->adressePostale) ? htmlspecialchars($allclient->adressePostale) : ''; ?>"
                                data-codepostal="<?php echo isset($allclient->codePostal) ? htmlspecialchars($allclient->codePostal) : ''; ?>"
                                data-ville="<?php echo isset($allclient->ville) ? htmlspecialchars($allclient->ville) : ''; ?>"
                                data-telephone="<?php echo isset($allclient->telephone) ? htmlspecialchars($allclient->telephone) : ''; ?>"
                                >
                          <i class="fas fa-edit"></i>
                        </button>
                        <span class="space"></span>
                        <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 1) : ?>
                        <button class="btn btn-danger btn-sm" onclick="confirmDeleteEtudiant(<?php echo $allclient->id_users; ?>)">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                        <span class="space"></span>
                        <form method="POST" action="detailsAchatsClientCerfa" style="display:inline;">
                            <input type="hidden" name="clientId" value="<?= $allclient->id_users ?>">
                            <button type="submit" style="background:none; border:none; color:black; margin-left:20px;" title="listes des Achats">
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </form>
                        <span class="space"></span>
                        <form method="POST" action="detailsCerfasClientCerfa" style="display:inline;">
                            <input type="hidden" name="clientId" value="<?= $allclient->id_users ?>">
                            <button type="submit" style="background:none; border:none; color:black; margin-left:20px;" title="listes des cerfas">
                            <i class="fa fa-info-circle" style="color:white; background-color:blue;"></i>
                            </button>
                        </form>
                       

                        <?php endif; ?>
                      </div>
                    </td>
                </tr>
                 
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
        <p><?php echo $result->erreur ?? 'Aucun client cerfa trouvé.'; ?></p>
      <?php endif; ?>


      <?php include 'add-clientCerfa.php'; ?>
      <?php include 'edit-clientCerfa.php'; ?>
      <?php include 'delete-clientCerfa.php'; ?>

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
