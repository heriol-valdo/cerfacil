<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include __DIR__ . '/../elements/header.php';
require_once __DIR__ . '/../../controller/Salle/ListSalleController.php';
require_once __DIR__ . '/../../controller/User/validTokenController.php';
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <title>Liste des Salles | ErpFacil</title>
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/style/listeEtudiants.css" />
  <link rel="stylesheet" href="../../assets/style/modals.css" />
  <link rel="stylesheet" href="../../assets/style/contentContainer.css" />
  <link rel="stylesheet" href="../../assets/style/pagination.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
</head>

<body>
  <div class="content-container">
    <header>
      <div class="header-container">
        <i class="bx bx-buildings header-icon"></i>
        <div class="title">
          <h1>Liste des Salles</h1>
        </div>
      </div>
    </header>
    <main>
      <div class="d-flex justify-content-between mt-3 mb-3">
        <input type="text" id="searchInput" class="form-control w-25" placeholder="Rechercher...">
        <div>
          <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addSalleModal">Ajouter une salle</button>
        </div>
      </div>
      <?php if (!empty($allsalles)): ?>
        <div class="table-container">
          <table class="table table-striped mt-3" id="sallesTable">
            <thead id="tableHead">
              <tr>
                <th>Nom</th>
                <th>Capacité d'accueil</th>
                <?php if ($_SESSION['user']['role'] == 1) {
                  echo "<th>Centre de formation</th>";
                } ?>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
  
              <?php 
                foreach ($allsalles as $allsalle):
              ?>
                <tr>
                  <td><?php echo isset($allsalle->nom) ? htmlspecialchars($allsalle->nom) : ''; ?></td>
                  <td><?php echo isset($allsalle->capacite_accueil) ? htmlspecialchars($allsalle->capacite_accueil) : ''; ?></td>
                  <?php if ($_SESSION['user']['role'] == 1) { ?>
                    <td><?php echo isset($allsalle->nomCentre) ? htmlspecialchars($allsalle->nomCentre) : ''; ?></td>
                  <?php } ?>
                  <td>
                    <div class="action-buttons">
                      <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                              data-id="<?php echo $allsalle->id; ?>"
                              data-nom="<?php echo isset($allsalle->nom) ? htmlspecialchars($allsalle->nom) : ''; ?>"
                              data-capacite="<?php echo isset($allsalle->capacite_accueil) ? htmlspecialchars($allsalle->capacite_accueil) : ''; ?>">
                        <i class="fas fa-edit"></i>
                      </button>
                      <span class="space"></span>
                      <button class="btn btn-danger btn-sm" onclick="confirmDeleteSalle(<?php echo $allsalle->id; ?>)">
                        <i class="fas fa-trash-alt"></i>
                      </button>


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
              <!-- Les numéros de page seront insérés ici dynamiquement -->
              <li class="page-item"><a class="page-link" href="#" id="nextPage">Suivant</a></li>
            </ul>
          </nav>
        </div>

      <?php else: ?>
        <p><?php echo $erreur; ?></p>
      <?php endif; ?>

      <?php include 'add-salle.php'; ?>
      <?php include 'edit-salle.php'; ?>
      <?php include 'delete-salle.php'; ?>

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

    
        const searchInput = document.getElementById('searchInput');
        const entrepriseTable = document.getElementById('sallesTable');
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

          // Gestionnaires d'événements pour les boutons Précédent et Suivant
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
          const rows = entrepriseTable.querySelectorAll('tbody tr');
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
          const rows = entrepriseTable.querySelectorAll('tbody tr');
          let visibleRows = [];

          rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            let found = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(filter));

            row.style.display = 'none';
            if (found) visibleRows.push(row);
          });

          visibleRows.forEach(row => row.style.display = 'none';
          for (let i = 0; i < visibleRows.length; i++) {
            visibleRows[i].style.display = (i < rowsPerPage) ? '' : 'none';
          }

          document.getElementById('paginationNav').style.display = visibleRows.length > rowsPerPage ? 'block' : 'none';
          document.getElementById('noResultsMessage').style.display = visibleRows.length ? 'none' : 'block';
          tableHead.style.display = visibleRows.length ? 'table-header-group' : 'none';

          currentPage = 1;
          updatePagination(Math.ceil(visibleRows.length / rowsPerPage));
        }

        // Appel initial pour afficher la première page
        displayPage(currentPage);

        searchInput.addEventListener('input', searchAndPaginate);

        // Faire en sorte que la pagination reste fixe et ne remonte pas si moins de ligne dans le tableau
        document.addEventListener("DOMContentLoaded", function() {
          const rowsPerPage = 10; // Nombre d'éléments par page
          const tableContainer = document.querySelector('.table-container');
          const rowHeight = 50; // Hauteur approximative des lignes, ajuster en fonction de votre style
          const tableHeadHeight = document.querySelector('thead').offsetHeight;
          const minHeight = (rowsPerPage * rowHeight) + tableHeadHeight;

          function setTableHeight() {
            const totalRows = entrepriseTable.querySelectorAll('tbody tr').length;
            const visibleRows = Math.min(rowsPerPage, totalRows);
            const newHeight = (visibleRows * rowHeight) + tableHeadHeight;
            tableContainer.style.height = `${Math.max(newHeight, minHeight)}px`;
          }

          setTableHeight();
          searchInput.addEventListener('input', setTableHeight);
          document.getElementById('paginationList').addEventListener('click', function(e) {
            if (e.target.tagName === 'A') {
              setTimeout(setTableHeight, 100); // Ajustez ce délai si nécessaire
            }
          });
        });

        document.addEventListener('DOMContentLoaded', function() {
          var editModal = document.getElementById('editModal');
          editModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget; // Bouton qui a déclenché la modale
            var id = button.getAttribute('data-id');
            var nom = button.getAttribute('data-nom');
            var capacite = button.getAttribute('data-capacite');
            var centre = button.getAttribute('data-centre');

            // Remplir les champs du formulaire avec les données du bouton
            var editForm = document.getElementById('editForm');
            editForm.querySelector('#edit-id').value = id;
            editForm.querySelector('#edit-nom').value = nom;
            editForm.querySelector('#edit-capacite_accueil').value = capacite;

            // Définir la valeur sélectionnée du champ centre
            if (centre !== null) {
              var centreField = editForm.querySelector('#id_centres_de_formation');
              if (centreField) {
                centreField.value = centre;
              }
            }
          });
        });
      </script>

      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    </main>
    <footer class="footer-container">
      <?php include __DIR__ . '/../elements/footer.php'; ?>
    </footer>
  </div>
</body>
</html>
